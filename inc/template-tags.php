<?php
/**
 * Funções auxiliares de template.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL da listagem de artigos (para “Ver mais posts” na página inicial).
 *
 * Prioriza a categoria “novidades”; senão página de artigos em Leitura,
 * blog na raiz, /blog/ ou listagem geral.
 *
 * @return string URL absoluta.
 */
function isabela_lessa_get_blog_posts_url(): string {
	$novidades = get_category_by_slug( 'novidades' );
	if ( $novidades instanceof WP_Term ) {
		$url = get_category_link( $novidades->term_id );
		if ( is_string( $url ) && $url !== '' ) {
			return $url;
		}
	}

	$page_id = (int) get_option( 'page_for_posts' );
	if ( $page_id > 0 ) {
		$url = get_permalink( $page_id );
		if ( is_string( $url ) && $url !== '' ) {
			return $url;
		}
	}

	if ( get_option( 'show_on_front' ) === 'posts' ) {
		return home_url( '/' );
	}

	$blog_page = get_page_by_path( 'blog' );
	if ( $blog_page instanceof WP_Post && 'publish' === $blog_page->post_status ) {
		$url = get_permalink( $blog_page );
		if ( is_string( $url ) && $url !== '' ) {
			return $url;
		}
	}

	return add_query_arg( 'post_type', 'post', home_url( '/index.php' ) );
}

/**
 * URL da página Sobre (slug sobre ou about).
 *
 * @return string URL absoluta.
 */
function isabela_lessa_get_about_page_url(): string {
	foreach ( array( 'sobre', 'about' ) as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page instanceof WP_Post && 'publish' === $page->post_status ) {
			$url = get_permalink( $page );
			if ( is_string( $url ) && $url !== '' ) {
				return $url;
			}
		}
	}

	return home_url( '/' );
}

/**
 * Posts da categoria Recursos & Downloads para a faixa marquee (página inicial).
 *
 * @return array<int, array{title: string, url: string}>
 */
function isabela_lessa_get_recursos_downloads_marquee_items(): array {
	$q = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => -1,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'category_name'       => 'recursos-downloads',
			'orderby'             => 'date',
			'order'               => 'DESC',
		)
	);

	$items = array();
	if ( $q->have_posts() ) {
		while ( $q->have_posts() ) {
			$q->the_post();
			$url = get_permalink();
			if ( ! is_string( $url ) || $url === '' ) {
				continue;
			}
			$items[] = array(
				'title' => get_the_title(),
				'url'   => $url,
			);
		}
		wp_reset_postdata();
	}

	return $items;
}

/**
 * Lista de categorias do post atual com classes compatíveis com o CSS do tema.
 *
 * @param string $wrapper_class Classe do wrapper (ex.: hero-meta, side-card__meta).
 */
function isabela_lessa_the_category_links( string $wrapper_class ): void {
	$categories = get_the_category();
	if ( empty( $categories ) ) {
		return;
	}
	echo '<div class="' . esc_attr( $wrapper_class ) . ' wp-block-post-terms">';
	$parts = array();
	foreach ( $categories as $cat ) {
		$parts[] = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( $cat->name ) . '</a>';
	}
	echo implode( '  ', $parts ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- montado com esc_url/esc_html.
	echo '</div>';
}

/**
 * Definições SVG compartilhadas (gradiente dourado nos ícones sociais).
 */
function isabela_lessa_render_social_gradient_defs(): void {
	static $rendered = false;

	if ( $rendered ) {
		return;
	}

	$rendered = true;

	echo '<svg class="social-icons-defs" aria-hidden="true" focusable="false" width="0" height="0" style="position:absolute;width:0;height:0;overflow:hidden">';
	echo '<defs>';
	echo '<linearGradient id="eg-social-gold-gradient" x1="0%" y1="0%" x2="100%" y2="0%">';
	echo '<stop offset="0%" stop-color="#ae8625"/>';
	echo '<stop offset="35%" stop-color="#f7ef8a"/>';
	echo '<stop offset="75%" stop-color="#d2ac47"/>';
	echo '<stop offset="100%" stop-color="#edc967"/>';
	echo '</linearGradient>';
	echo '</defs>';
	echo '</svg>';
}

/**
 * SVG de rede social.
 *
 * @param string $network    spotify|youtube|pinterest
 * @param string $icon_class Classe CSS do SVG.
 * @param int    $size       Largura/altura em px.
 * @param string $paint      current|gradient
 * @return string
 */
function isabela_lessa_get_social_icon_svg( string $network, string $icon_class, int $size, string $paint = 'current' ): string {
	$files = array(
		'spotify'   => 'icon-spotify.svg',
		'youtube'   => 'icon-youtube.svg',
		'pinterest' => 'icon-pinterest.svg',
	);

	if ( ! isset( $files[ $network ] ) ) {
		return '';
	}

	$path = get_stylesheet_directory() . '/assets/icons/' . $files[ $network ];
	if ( ! is_readable( $path ) ) {
		return '';
	}

	$svg = (string) file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( $svg === '' ) {
		return '';
	}

	$is_outline = in_array( $network, array( 'youtube', 'pinterest' ), true );
	$modifier   = $is_outline ? ' social-icon--outline' : ' social-icon--filled';
	$class      = trim( $icon_class . $modifier );

	if ( 'gradient' === $paint ) {
		if ( $is_outline ) {
			$svg = str_replace( 'stroke="currentColor"', 'stroke="url(#eg-social-gold-gradient)"', $svg );
		} else {
			$svg = preg_replace( '/\sfill="currentColor"/', ' fill="none"', $svg, 1 );
			$svg = preg_replace( '/<path\b/', '<path fill="url(#eg-social-gold-gradient)"', $svg, 1 );
		}
	}

	$svg = preg_replace(
		'/<svg\b([^>]*)>/',
		sprintf(
			'<svg class="%s" width="%d" height="%d"$1>',
			esc_attr( $class ),
			(int) $size,
			(int) $size
		),
		$svg,
		1
	);

	return $svg;
}

/**
 * Ícone SVG de rede social (arquivos em assets/icons/).
 *
 * @param string $network spotify|youtube|pinterest
 * @param string $icon_class Classe CSS do SVG.
 * @param int    $size       Largura/altura em px.
 */
function isabela_lessa_render_social_icon( string $network, string $icon_class, int $size ): void {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG do tema, paths estáticos.
	echo isabela_lessa_get_social_icon_svg( $network, $icon_class, $size, 'current' );
}

/**
 * Ícone com camada base + gradiente (hover suave).
 *
 * @param string $network    spotify|youtube|pinterest
 * @param string $icon_class Classe CSS do SVG.
 * @param int    $size       Largura/altura em px.
 */
function isabela_lessa_render_social_icon_stack( string $network, string $icon_class, int $size ): void {
	$base = isabela_lessa_get_social_icon_svg( $network, $icon_class . ' social-icon--base', $size, 'current' );
	$gold = isabela_lessa_get_social_icon_svg( $network, $icon_class . ' social-icon--gold', $size, 'gradient' );

	if ( $base === '' || $gold === '' ) {
		return;
	}

	echo '<span class="social-icon-stack" aria-hidden="true">';
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG do tema, paths estáticos.
	echo $base;
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG do tema, paths estáticos.
	echo $gold;
	echo '</span>';
}

/**
 * Lista de links de redes sociais (header / rodapé).
 *
 * @param array{list_class?: string, link_class?: string, icon_class?: string, icon_size?: int} $args Classes e tamanho do ícone.
 */
function isabela_lessa_render_social_links( array $args = array() ): void {
	$args = wp_parse_args(
		$args,
		array(
			'list_class' => 'site-header__social-list',
			'link_class' => 'site-header__social-link',
			'icon_class' => 'site-header__social-icon',
			'icon_size'  => 14,
		)
	);

	$networks = array(
		array(
			'url'   => 'https://open.spotify.com/user/v0vi4eh1gzn01x2fptwbdzj3i?si=-zA-gxGuRyWR6AxyP7eN5w',
			'label' => __( 'Spotify', 'isabela-lessa' ),
			'icon'  => 'spotify',
		),
		array(
			'url'   => isabela_lessa_youtube_channel_url(),
			'label' => __( 'YouTube', 'isabela-lessa' ),
			'icon'  => 'youtube',
		),
		array(
			'url'   => 'https://br.pinterest.com/',
			'label' => __( 'Pinterest', 'isabela-lessa' ),
			'icon'  => 'pinterest',
		),
	);

	isabela_lessa_render_social_gradient_defs();

	echo '<ul class="' . esc_attr( (string) $args['list_class'] ) . '">';
	foreach ( $networks as $network ) {
		echo '<li>';
		printf(
			'<a class="%s" href="%s" target="_blank" rel="noopener noreferrer" aria-label="%s">',
			esc_attr( (string) $args['link_class'] ),
			esc_url( (string) $network['url'] ),
			esc_attr( (string) $network['label'] )
		);
		isabela_lessa_render_social_icon_stack( (string) $network['icon'], (string) $args['icon_class'], (int) $args['icon_size'] );
		echo '</a></li>';
	}
	echo '</ul>';
}

/**
 * Logo vetorial inline (marrom + studio dourado), com IDs únicos por instância.
 *
 * @param array{class?: string, suffix?: string} $args Argumentos.
 */
function isabela_lessa_render_logo( array $args = array() ): void {
	$args = wp_parse_args(
		$args,
		array(
			'class'  => 'site-logo__svg',
			'suffix' => 'main',
		)
	);

	$path = get_stylesheet_directory() . '/assets/logo-vetorial.svg';
	if ( ! is_readable( $path ) ) {
		return;
	}

	$svg = file_get_contents( $path );
	if ( ! is_string( $svg ) || $svg === '' ) {
		return;
	}

	$suffix      = sanitize_html_class( (string) $args['suffix'] );
	$gradient_id = 'eg-logo-gold-' . $suffix;
	$svg         = str_replace( 'eg-logo-gold', $gradient_id, $svg );
	$svg         = preg_replace( '/\srole="img"/', '', $svg, 1 );
	$svg         = preg_replace( '/\saria-label="[^"]*"/', '', $svg, 1 );
	$svg         = preg_replace(
		'/<svg\s/',
		'<svg class="' . esc_attr( (string) $args['class'] ) . '" aria-hidden="true" focusable="false" ',
		$svg,
		1
	);

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- arquivo SVG do tema.
	echo $svg;
}
