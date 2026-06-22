<?php
/**
 * Áreas de widgets do tema.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * Regista sidebars e suporte a widgets.
 */
function isabela_lessa_register_sidebars(): void {
	register_sidebar(
		array(
			'name'          => __( 'Banners do blog (lateral)', 'isabela-lessa' ),
			'id'            => 'blog-banners',
			'description'   => __( 'Use o widget “Banner do blog” (imagem + link em nova aba) para cada banner na lateral. Aparece apenas em posts individuais.', 'isabela-lessa' ),
			'before_widget' => '<div id="%1$s" class="blog-banners-sidebar__widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="blog-banners-sidebar__heading screen-reader-text">',
			'after_title'   => '</h2>',
		)
	);
}

add_action( 'widgets_init', 'isabela_lessa_register_sidebars' );

/**
 * ID da sidebar de banners do blog.
 */
function isabela_lessa_blog_banners_sidebar_id(): string {
	return 'blog-banners';
}

/**
 * Indica se a sidebar de banners tem widgets ativos.
 */
function isabela_lessa_has_blog_banners_sidebar(): bool {
	return is_active_sidebar( isabela_lessa_blog_banners_sidebar_id() );
}

/**
 * Renderiza a sidebar de banners do blog.
 */
function isabela_lessa_the_blog_banners_sidebar(): void {
	if ( ! is_singular( 'post' ) || ! isabela_lessa_has_blog_banners_sidebar() ) {
		return;
	}

	echo '<section class="blog-banners-sidebar" aria-label="' . esc_attr__( 'Banners', 'isabela-lessa' ) . '">';
	dynamic_sidebar( isabela_lessa_blog_banners_sidebar_id() );
	echo '</section>';
}
