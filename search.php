<?php
/**
 * Resultados de busca.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="home-main wp-block-group search-main">
	<h1 class="wp-block-heading">
		<?php
		printf(
			/* translators: %s: search query */
			esc_html__( 'Resultados para: %s', 'isabela-lessa' ),
			'<span>' . esc_html( get_search_query() ) . '</span>'
		);
		?>
	</h1>

	<?php if ( have_posts() ) : ?>
		<?php get_template_part( 'template-parts/posts', 'grid' ); ?>
	<?php else : ?>
		<p class="search-main__empty">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'Nenhum post encontrado para “%s”. Tente outras palavras do título ou do texto do artigo.', 'isabela-lessa' ),
				esc_html( get_search_query() )
			);
			?>
		</p>
	<?php endif; ?>
</main>

<?php
get_footer();
