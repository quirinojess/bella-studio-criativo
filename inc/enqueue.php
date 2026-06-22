<?php
/**
 * Enfileiramento de assets do tema.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL do CSS das fontes usadas nos presets Primária / Secundária (theme.json).
 * Sem @font-face ou link, o navegador cai no fallback (Georgia / system).
 */
function isabela_lessa_google_fonts_url(): string {
	return 'https://fonts.googleapis.com/css2?family=Prata&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap';
}

/**
 * Enfileira CSS e JS no fluxo tradicional do WordPress.
 */
function isabela_lessa_enqueue_assets(): void {
	$theme_uri = get_stylesheet_directory_uri();
	$theme_dir = get_stylesheet_directory();

	$style_path = $theme_dir . '/style.css';
	$main_css   = $theme_dir . '/src/css/main.css';
	$main_js    = $theme_dir . '/src/js/main.js';

	wp_enqueue_style(
		'isabela-lessa-fonts',
		isabela_lessa_google_fonts_url(),
		array(),
		null
	);

	wp_enqueue_style(
		'isabela-lessa-style',
		$theme_uri . '/style.css',
		array( 'isabela-lessa-fonts' ),
		is_file( $style_path ) ? (string) filemtime( $style_path ) : null
	);

	if ( is_file( $main_css ) ) {
		wp_enqueue_style(
			'isabela-lessa-main',
			$theme_uri . '/src/css/main.css',
			array( 'isabela-lessa-style' ),
			(string) filemtime( $main_css )
		);
	}

	if ( is_file( $main_js ) ) {
		wp_enqueue_script(
			'isabela-lessa-main',
			$theme_uri . '/src/js/main.js',
			array(),
			(string) filemtime( $main_js ),
			true
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'isabela_lessa_enqueue_assets' );

/**
 * Preconnect para reduzir latência ao buscar fontes no Google Fonts.
 *
 * @param array<int, string> $urls          URLs.
 * @param string              $relation_type Tipo de hint.
 * @return array<int, string>
 */
function isabela_lessa_resource_hints( array $urls, string $relation_type ): array {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = 'https://fonts.gstatic.com';
	}
	return $urls;
}

add_filter( 'wp_resource_hints', 'isabela_lessa_resource_hints', 10, 2 );

/**
 * Carrega as mesmas fontes no editor de blocos (iframe), onde o theme.json sozinho não injeta o link do Google Fonts.
 */
function isabela_lessa_enqueue_fonts_block_editor(): void {
	wp_enqueue_style(
		'isabela-lessa-fonts-editor',
		isabela_lessa_google_fonts_url(),
		array(),
		null
	);
}

add_action( 'enqueue_block_editor_assets', 'isabela_lessa_enqueue_fonts_block_editor', 1 );

/**
 * URL do favicon (logo vetorial do tema).
 */
function isabela_lessa_favicon_url(): string {
	$path = get_stylesheet_directory() . '/assets/logo-vetorial.svg';
	$url  = get_stylesheet_directory_uri() . '/assets/logo-vetorial.svg';

	if ( is_file( $path ) ) {
		$url .= '?ver=' . rawurlencode( (string) filemtime( $path ) );
	}

	return $url;
}

/**
 * Favicon do site: logo SVG do tema.
 */
function isabela_lessa_print_favicon(): void {
	$url = isabela_lessa_favicon_url();

	printf(
		'<link rel="icon" href="%s" type="image/svg+xml" sizes="any">' . "\n",
		esc_url( $url )
	);
	printf(
		'<link rel="shortcut icon" href="%s" type="image/svg+xml">' . "\n",
		esc_url( $url )
	);
}

add_action(
	'init',
	static function (): void {
		remove_action( 'wp_head', 'wp_site_icon', 99 );
	}
);

add_action( 'wp_head', 'isabela_lessa_print_favicon', 2 );
