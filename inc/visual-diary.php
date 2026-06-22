<?php
/**
 * Diário visual — galeria autoral na página inicial (CPT + imagens).
 *
 * No admin: Diário visual → Adicionar foto. Imagem destacada obrigatória; título opcional.
 * Ordem: campo “Ordem” no editor (número menor = aparece primeiro).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * Slug do tipo de conteúdo.
 */
function isabela_lessa_visual_diary_post_type(): string {
	return 'ila_visual';
}

/**
 * Registra tamanho de imagem para o carrossel.
 */
function isabela_lessa_visual_diary_image_sizes(): void {
	add_image_size( 'ila_visual_card', 640, 800, true );
}

add_action( 'after_setup_theme', 'isabela_lessa_visual_diary_image_sizes', 11 );

/**
 * Registra o CPT (sem URL pública — só gestão e query na home).
 */
function isabela_lessa_register_visual_diary_cpt(): void {
	$labels = array(
		'name'               => _x( 'Diário visual', 'post type general name', 'isabela-lessa' ),
		'singular_name'      => _x( 'Foto do diário', 'post type singular name', 'isabela-lessa' ),
		'menu_name'          => _x( 'Diário visual', 'admin menu', 'isabela-lessa' ),
		'add_new'            => _x( 'Adicionar foto', 'ila_visual', 'isabela-lessa' ),
		'add_new_item'       => __( 'Adicionar nova foto', 'isabela-lessa' ),
		'edit_item'          => __( 'Editar foto', 'isabela-lessa' ),
		'new_item'           => __( 'Nova foto', 'isabela-lessa' ),
		'view_item'          => __( 'Ver foto', 'isabela-lessa' ),
		'search_items'       => __( 'Pesquisar fotos', 'isabela-lessa' ),
		'not_found'          => __( 'Nenhuma foto ainda.', 'isabela-lessa' ),
		'not_found_in_trash' => __( 'Nada na lixeira.', 'isabela-lessa' ),
	);

	register_post_type(
		isabela_lessa_visual_diary_post_type(),
		array(
			'labels'              => $labels,
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_icon'           => 'dashicons-camera-alt',
			'menu_position'       => 22,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'query_var'           => false,
			'show_in_rest'        => true,
		)
	);
}

add_action( 'init', 'isabela_lessa_register_visual_diary_cpt' );

/**
 * Avisa no editor que a imagem destacada é o conteúdo principal.
 *
 * @param string $post_type Post type.
 */
function isabela_lessa_visual_diary_admin_notice(): void {
	$screen = get_current_screen();
	if ( ! $screen || 'post' !== $screen->base || isabela_lessa_visual_diary_post_type() !== $screen->post_type ) {
		return;
	}
	echo '<div class="notice notice-info inline"><p>';
	echo esc_html__( 'Define a imagem destacada — ela aparece no carrossel da página inicial. O título é opcional (legenda curta sobre a foto). Usa “Ordem” para ordenar (menor = primeiro).', 'isabela-lessa' );
	echo '</p></div>';
}

add_action( 'admin_notices', 'isabela_lessa_visual_diary_admin_notice' );

/**
 * Coluna miniatura na lista do admin.
 *
 * @param array<string, string> $columns Colunas.
 * @return array<string, string>
 */
function isabela_lessa_visual_diary_columns( array $columns ): array {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['ila_visual_thumb'] = __( 'Imagem', 'isabela-lessa' );
		}
	}
	return $new;
}

add_filter( 'manage_' . isabela_lessa_visual_diary_post_type() . '_posts_columns', 'isabela_lessa_visual_diary_columns' );

/**
 * Conteúdo da coluna miniatura.
 *
 * @param string $column  Nome da coluna.
 * @param int    $post_id ID do post.
 */
function isabela_lessa_visual_diary_column_thumb( string $column, int $post_id ): void {
	if ( 'ila_visual_thumb' !== $column ) {
		return;
	}
	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, array( 60, 60 ), array( 'style' => 'object-fit:cover;border-radius:4px;' ) );
	} else {
		echo '—';
	}
}

add_action( 'manage_' . isabela_lessa_visual_diary_post_type() . '_posts_custom_column', 'isabela_lessa_visual_diary_column_thumb', 10, 2 );

/**
 * URL para partilhar no Pinterest (abre o fluxo “Guardar”).
 *
 * @param string $media_url URL da imagem em tamanho completo.
 * @param string $description Texto da legenda (opcional).
 * @return string
 */
function isabela_lessa_pinterest_pin_create_url( string $media_url, string $description = '' ): string {
	return add_query_arg(
		array(
			'url'         => home_url( '/' ),
			'media'       => $media_url,
			'description' => $description,
		),
		'https://www.pinterest.com/pin/create/button/'
	);
}
