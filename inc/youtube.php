<?php
/**
 * Vídeos do YouTube via feed RSS oficial (sem API key).
 *
 * Requer o ID do canal (UC…). URLs com /channel/UC… ou @handle são resolvidas quando possível.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL pública do canal YouTube (fonte única no tema).
 */
function isabela_lessa_youtube_channel_url(): string {
	return 'https://www.youtube.com/@bellastudiocriativo';
}

/**
 * Valor configurado para o feed RSS (Customizer), com fallback do canal padrão.
 */
function isabela_lessa_youtube_channel_source(): string {
	$canonical = isabela_lessa_youtube_channel_url();
	$stored    = trim( (string) get_theme_mod( 'isabela_lessa_youtube_channel', $canonical ) );

	return $stored !== '' ? $stored : $canonical;
}

/**
 * Atualiza URLs antigas do canal guardadas no Personalizador.
 */
function isabela_lessa_sync_youtube_channel_mod(): void {
	$canonical = isabela_lessa_youtube_channel_url();
	$stored    = trim( (string) get_theme_mod( 'isabela_lessa_youtube_channel', '' ) );

	if ( $stored === '' ) {
		set_theme_mod( 'isabela_lessa_youtube_channel', $canonical );
		return;
	}

	if ( $stored === $canonical ) {
		return;
	}

	// Links de partilha ou variações antigas do mesmo @handle.
	if ( preg_match( '#@bellastudiocriativo#i', $stored ) ) {
		set_theme_mod( 'isabela_lessa_youtube_channel', $canonical );
		if ( function_exists( 'isabela_lessa_youtube_resolve_channel_id' ) ) {
			$old_cid = isabela_lessa_youtube_resolve_channel_id( $stored );
			if ( $old_cid !== '' ) {
				delete_transient( 'isabela_yt_feed_' . md5( $old_cid ) );
			}
		}
	}
}

add_action( 'after_setup_theme', 'isabela_lessa_sync_youtube_channel_mod', 20 );

/**
 * Extrai ID de vídeo a partir de URL do YouTube.
 */
function isabela_lessa_youtube_video_id_from_url( string $url ): string {
	$url = trim( $url );
	if ( preg_match( '#[?&]v=([a-zA-Z0-9_-]{11})#', $url, $m ) ) {
		return $m[1];
	}
	if ( preg_match( '#youtu\.be/([a-zA-Z0-9_-]{11})#', $url, $m ) ) {
		return $m[1];
	}
	return '';
}

/**
 * Resolve ID do canal a partir de texto (UC…, URL /channel/…, ou URL @handle).
 *
 * @return string ID UC… ou string vazia.
 */
function isabela_lessa_youtube_resolve_channel_id( string $input ): string {
	$input = trim( $input );
	if ( $input === '' ) {
		return '';
	}

	if ( preg_match( '/^(UC[\w-]{20,})$/', $input ) ) {
		return $input;
	}

	if ( preg_match( '#youtube\.com/channel/(UC[\w-]+)#i', $input, $m ) ) {
		return $m[1];
	}

	$url = $input;
	if ( ! preg_match( '#^https?://#i', $url ) ) {
		if ( preg_match( '/^@?([\w.-]+)$/', $input, $hm ) ) {
			$url = 'https://www.youtube.com/@' . $hm[1];
		} else {
			return '';
		}
	}

	if ( strpos( $url, 'youtube.com' ) === false && strpos( $url, 'youtu.be' ) === false ) {
		return '';
	}

	$cache_key = 'isabela_yt_cid_' . md5( $url );
	$cached    = get_transient( $cache_key );
	if ( is_string( $cached ) && preg_match( '/^UC[\w-]{20,}$/', $cached ) ) {
		return $cached;
	}

	$response = wp_remote_get(
		$url,
		array(
			'timeout'     => 12,
			'redirection' => 3,
			'user-agent'  => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url( '/' ),
		)
	);

	if ( is_wp_error( $response ) || (int) wp_remote_retrieve_response_code( $response ) !== 200 ) {
		return '';
	}

	$body = (string) wp_remote_retrieve_body( $response );
	if ( preg_match( '/"channelId":"(UC[^"]{20,})"/', $body, $m ) ) {
		set_transient( $cache_key, $m[1], WEEK_IN_SECONDS );
		return $m[1];
	}
	if ( preg_match( '/browse_id":"(UC[^"]{20,})"/', $body, $m ) ) {
		set_transient( $cache_key, $m[1], WEEK_IN_SECONDS );
		return $m[1];
	}

	return '';
}

/**
 * Obtém lista de vídeos recentes do canal.
 *
 * @return array<int, array{id:string,title:string,url:string,thumb:string}>
 */
function isabela_lessa_get_youtube_videos( int $limit = 12 ): array {
	$raw = isabela_lessa_youtube_channel_source();
	$cid = isabela_lessa_youtube_resolve_channel_id( $raw );
	if ( $cid === '' && preg_match( '/UC[\w-]{20,}/', $raw, $m ) ) {
		$cid = $m[0];
	}

	if ( $cid === '' ) {
		return array();
	}

	$transient_key = 'isabela_yt_feed_' . md5( $cid );
	$cached        = get_transient( $transient_key );
	if ( is_array( $cached ) ) {
		return array_slice( $cached, 0, $limit );
	}

	if ( ! function_exists( 'fetch_feed' ) ) {
		require_once ABSPATH . WPINC . '/feed.php';
	}

	$feed_url = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . rawurlencode( $cid );
	$feed     = fetch_feed( $feed_url );

	if ( is_wp_error( $feed ) ) {
		set_transient( $transient_key, array(), HOUR_IN_SECONDS );
		return array();
	}

	$items = $feed->get_items( 0, $limit );
	$out   = array();

	foreach ( $items as $item ) {
		$link = method_exists( $item, 'get_link' ) ? (string) $item->get_link() : '';
		$vid  = isabela_lessa_youtube_video_id_from_url( $link );
		if ( $vid === '' ) {
			continue;
		}
		$title = method_exists( $item, 'get_title' ) ? (string) $item->get_title() : '';
		$out[] = array(
			'id'    => $vid,
			'title' => html_entity_decode( $title, ENT_QUOTES | ENT_HTML5, 'UTF-8' ),
			'url'   => 'https://www.youtube.com/watch?v=' . rawurlencode( $vid ),
			'thumb' => 'https://i.ytimg.com/vi/' . $vid . '/hqdefault.jpg',
		);
	}

	set_transient( $transient_key, $out, HOUR_IN_SECONDS );

	return array_slice( $out, 0, $limit );
}
