<?php
/**
 * Página (exceto front-page estática, que usa front-page.php).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="home-main wp-block-group page-main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<h1 class="wp-block-post-title"><?php the_title(); ?></h1>
			<div class="entry-content wp-block-post-content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
	</main>

<?php
get_footer();
