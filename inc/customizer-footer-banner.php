<?php
/**
 * Personalizador: imagem destacada antes do rodapé (site inteiro).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * ID da imagem do banner antes do rodapé.
 */
function isabela_lessa_get_footer_banner_image_id(): int {
	return absint( get_theme_mod( 'isabela_lessa_footer_banner_image', 0 ) );
}

/**
 * Imagem a exibir: personalizador global ou imagem destacada da página.
 */
function isabela_lessa_resolve_footer_banner_image_id(): int {
	$image_id = isabela_lessa_get_footer_banner_image_id();
	if ( $image_id > 0 ) {
		return $image_id;
	}

	if ( is_page() ) {
		$page_id = get_queried_object_id();
		if ( $page_id > 0 && has_post_thumbnail( $page_id ) ) {
			return (int) get_post_thumbnail_id( $page_id );
		}
	}

	return 0;
}

/**
 * Remove bloco de imagem destacada do conteúdo das páginas (exibida só no rodapé).
 *
 * @param string $content Conteúdo HTML.
 */
function isabela_lessa_strip_page_featured_image_from_content( string $content ): string {
	if ( ! is_page() || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$page_id = get_queried_object_id();
	if ( $page_id <= 0 || ! has_post_thumbnail( $page_id ) ) {
		return $content;
	}

	$thumb_id = (int) get_post_thumbnail_id( $page_id );
	$blocks   = parse_blocks( $content );

	if ( empty( $blocks ) ) {
		return $content;
	}

	$filtered              = array();
	$removed_featured      = false;
	$removed_first_image   = false;

	foreach ( $blocks as $block ) {
		if ( ! is_array( $block ) ) {
			$filtered[] = $block;
			continue;
		}

		$block_name = isset( $block['blockName'] ) ? (string) $block['blockName'] : '';

		if ( ! $removed_featured && 'core/post-featured-image' === $block_name ) {
			$removed_featured = true;
			continue;
		}

		if ( ! $removed_first_image && 'core/image' === $block_name ) {
			$block_id = isset( $block['attrs']['id'] ) ? (int) $block['attrs']['id'] : 0;
			if ( $block_id === $thumb_id ) {
				$removed_first_image = true;
				continue;
			}
		}

		if ( ! $removed_first_image && 'core/cover' === $block_name ) {
			$block_id = isset( $block['attrs']['id'] ) ? (int) $block['attrs']['id'] : 0;
			if ( $block_id === $thumb_id ) {
				$removed_first_image = true;
				continue;
			}
		}

		$filtered[] = $block;
	}

	return serialize_blocks( $filtered );
}

/**
 * @param string               $block_content HTML do bloco.
 * @param array<string, mixed> $block         Dados do bloco.
 */
function isabela_lessa_hide_page_featured_image_block( string $block_content, array $block ): string {
	if ( is_page() && isset( $block['blockName'] ) && 'core/post-featured-image' === $block['blockName'] ) {
		return '';
	}

	return $block_content;
}

add_filter( 'the_content', 'isabela_lessa_strip_page_featured_image_from_content', 5 );
add_filter( 'render_block', 'isabela_lessa_hide_page_featured_image_block', 10, 2 );

/**
 * URL opcional ao clicar na imagem.
 */
function isabela_lessa_get_footer_banner_link(): string {
	$url = get_theme_mod( 'isabela_lessa_footer_banner_link', '' );
	return is_string( $url ) ? esc_url( trim( $url ) ) : '';
}

/**
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function isabela_lessa_customize_footer_banner( $wp_customize ): void {
	$wp_customize->add_section(
		'isabela_lessa_footer_banner',
		array(
			'title'       => __( 'Imagem antes do rodapé', 'isabela-lessa' ),
			'description' => __( 'Faixa com imagem em largura total, imediatamente antes do rodapé. Nas páginas, se não houver imagem aqui, usa a Imagem destacada da própria página.', 'isabela-lessa' ),
			'priority'    => 38,
		)
	);

	$wp_customize->add_setting(
		'isabela_lessa_footer_banner_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'isabela_lessa_footer_banner_image',
			array(
				'label'     => __( 'Imagem destacada', 'isabela-lessa' ),
				'section'   => 'isabela_lessa_footer_banner',
				'mime_type' => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'isabela_lessa_footer_banner_link',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		'isabela_lessa_footer_banner_link',
		array(
			'label'       => __( 'Link da imagem (opcional)', 'isabela-lessa' ),
			'description' => __( 'Deixa vazio se a imagem não precisar de link.', 'isabela-lessa' ),
			'section'     => 'isabela_lessa_footer_banner',
			'type'        => 'url',
		)
	);
}

add_action( 'customize_register', 'isabela_lessa_customize_footer_banner' );
