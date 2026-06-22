<?php
/**
 * Listagens arquivo / busca: grelha 3×2 e paginação numerada.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * Número de posts por página no grid (3×2).
 */
function isabela_lessa_posts_grid_per_page(): int {
	return 6;
}

/**
 * Ajusta a query principal em arquivo e busca.
 *
 * @param WP_Query $query Query.
 */
function isabela_lessa_pre_get_posts_archive_search( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_search() ) {
		$query->set( 'post_type', 'post' );
		$query->set( 'posts_per_page', isabela_lessa_posts_grid_per_page() );
		return;
	}
	if ( $query->is_archive() && ! $query->is_home() ) {
		$query->set( 'posts_per_page', isabela_lessa_posts_grid_per_page() );
	}
}

add_action( 'pre_get_posts', 'isabela_lessa_pre_get_posts_archive_search' );

/**
 * Garante search.php com página inicial estática (evita cair em front-page.php).
 *
 * @param string $template Caminho do template.
 * @return string
 */
function isabela_lessa_include_search_template( string $template ): string {
	if ( is_search() ) {
		$located = locate_template( 'search.php' );
		if ( is_string( $located ) && $located !== '' ) {
			return $located;
		}
	}

	return $template;
}

add_filter( 'template_include', 'isabela_lessa_include_search_template', 99 );

/**
 * Paginação numerada (estilo PageNavi) para a grelha de arquivo / busca.
 */
function isabela_lessa_the_posts_grid_pagination(): void {
	global $wp_query;

	$total = isset( $wp_query->max_num_pages ) ? (int) $wp_query->max_num_pages : 1;
	if ( $total <= 1 ) {
		return;
	}

	$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

	$links = paginate_links(
		array(
			'total'     => $total,
			'current'   => $paged,
			'type'      => 'array',
			'mid_size'  => 2,
			'end_size'  => 1,
			'prev_next' => true,
			'prev_text' => '<span class="posts-grid-pagenavi__arrow" aria-hidden="true">←</span><span class="screen-reader-text">' . esc_html__( 'Página anterior', 'isabela-lessa' ) . '</span>',
			'next_text' => '<span class="posts-grid-pagenavi__arrow" aria-hidden="true">→</span><span class="screen-reader-text">' . esc_html__( 'Página seguinte', 'isabela-lessa' ) . '</span>',
		)
	);

	if ( ! is_array( $links ) || $links === array() ) {
		return;
	}

	echo '<nav class="posts-grid-pagenavi wp-pagenavi" aria-label="' . esc_attr__( 'Navegação de páginas', 'isabela-lessa' ) . '">';
	foreach ( $links as $link ) {
		echo wp_kses_post( $link );
	}
	echo '</nav>';
}
