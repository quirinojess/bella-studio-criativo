<?php
/**
 * Theme Functions — tema clássico (PHP/HTML/CSS), sem templates do editor de blocos.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/template-tags.php';
require_once get_stylesheet_directory() . '/inc/enqueue.php';
require_once get_stylesheet_directory() . '/inc/posts-grid.php';
require_once get_stylesheet_directory() . '/inc/sidebars.php';
require_once get_stylesheet_directory() . '/inc/widget-blog-banner.php';
require_once get_stylesheet_directory() . '/inc/header-categories.php';
require_once get_stylesheet_directory() . '/inc/youtube.php';
require_once get_stylesheet_directory() . '/inc/customizer-youtube.php';
require_once get_stylesheet_directory() . '/inc/customizer-clube.php';
require_once get_stylesheet_directory() . '/inc/customizer-footer-banner.php';
require_once get_stylesheet_directory() . '/inc/brands.php';
require_once get_stylesheet_directory() . '/inc/customizer-brands.php';
require_once get_stylesheet_directory() . '/inc/visual-diary.php';
require_once get_stylesheet_directory() . '/inc/customizer-visual-diary.php';
/**
 * Suporte e menus.
 */
add_action(
	'after_setup_theme',
	static function (): void {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
		add_theme_support( 'widgets' );
		add_theme_support( 'widgets-block-editor' );

		register_nav_menus(
			array(
				'primary' => __( 'Menu principal (cabeçalho)', 'isabela-lessa' ),
				'footer'  => __( 'Rodapé — Explore', 'isabela-lessa' ),
			)
		);
	},
	10
);

/**
 * Corpo da página: classe para escopar estilos tipo revista.
 *
 * @param array<int, string> $classes Classes.
 * @return array<int, string>
 */
function isabela_lessa_body_class( array $classes ): array {
	$classes[] = 'eg-theme';
	return $classes;
}

add_filter( 'body_class', 'isabela_lessa_body_class' );

/**
 * Oculta a barra de administração do WordPress no front-end (usuários logados).
 */
add_filter( 'show_admin_bar', '__return_false' );

/**
 * Bloqueia edição de template no editor de posts padrão.
 *
 * Evita cair no modo de prévia/layout ao editar conteúdo de post.
 *
 * @param array<string, mixed> $editor_settings Configurações do editor.
 * @param WP_Block_Editor_Context $editor_context Contexto do editor.
 * @return array<string, mixed>
 */
function isabela_lessa_disable_template_mode_for_posts( array $editor_settings, WP_Block_Editor_Context $editor_context ): array {
	if (
		isset( $editor_context->post )
		&& $editor_context->post instanceof WP_Post
		&& 'post' === $editor_context->post->post_type
	) {
		$editor_settings['supportsTemplateMode'] = false;
	}

	return $editor_settings;
}

add_filter( 'block_editor_settings_all', 'isabela_lessa_disable_template_mode_for_posts', 10, 2 );
