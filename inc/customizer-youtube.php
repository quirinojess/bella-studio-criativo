<?php
/**
 * Personalizador: canal YouTube (feed RSS, sem API key).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * Secção YouTube no Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function isabela_lessa_customize_youtube( $wp_customize ): void {
	$wp_customize->add_section(
		'isabela_lessa_youtube',
		array(
			'title'       => __( 'YouTube (página inicial)', 'isabela-lessa' ),
			'description' => __( 'Cole o ID do canal (começa por UC…) ou o link completo (página do canal, inclusive @handle). Os vídeos são carregados pelo feed RSS público do YouTube — não é necessária API key.', 'isabela-lessa' ),
			'priority'    => 35,
		)
	);

	$wp_customize->add_setting(
		'isabela_lessa_youtube_channel',
		array(
			'default'           => isabela_lessa_youtube_channel_url(),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'isabela_lessa_youtube_channel',
		array(
			'label'       => __( 'Canal YouTube (ID ou URL)', 'isabela-lessa' ),
			'description' => __( 'Ex.: UCxxxxxxxx ou https://www.youtube.com/@seucanal', 'isabela-lessa' ),
			'section'     => 'isabela_lessa_youtube',
			'type'        => 'text',
		)
	);
}

add_action( 'customize_register', 'isabela_lessa_customize_youtube' );

/**
 * Limpa cache do feed ao guardar o Customizer.
 */
function isabela_lessa_customize_youtube_flush_cache(): void {
	$raw = isabela_lessa_youtube_channel_source();
	if ( function_exists( 'isabela_lessa_youtube_resolve_channel_id' ) ) {
		$cid = isabela_lessa_youtube_resolve_channel_id( $raw );
		if ( $cid !== '' ) {
			delete_transient( 'isabela_yt_feed_' . md5( $cid ) );
		}
	}
}

add_action( 'customize_save_after', 'isabela_lessa_customize_youtube_flush_cache' );
