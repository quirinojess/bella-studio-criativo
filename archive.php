<?php
/**
 * Arquivos de categoria, tag, data, autor.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="home-main wp-block-group archive-main">
	<header class="archive-header">
		<?php if ( is_category() ) : ?>
			<p class="archive-header__kicker"><?php esc_html_e( 'Você está lendo sobre', 'isabela-lessa' ); ?></p>
			<h1 class="archive-header__title wp-block-heading"><?php echo esc_html( single_cat_title( '', false ) ); ?></h1>
			<?php
			the_archive_description( '<div class="archive-description">', '</div>' );
			?>
		<?php else : ?>
			<?php the_archive_title( '<h1 class="wp-block-heading">', '</h1>' ); ?>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
		<?php endif; ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<?php get_template_part( 'template-parts/posts', 'grid' ); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nenhum post neste arquivo.', 'isabela-lessa' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
