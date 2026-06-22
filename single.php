<?php
/**
 * Post único.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="home-main wp-block-group singular-main singular-main--post">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div class="singular-layout">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'singular-post' ); ?>>
				<header class="singular-post__header">
					<?php if ( get_post_type() === 'post' ) : ?>
						<time class="singular-post-date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
							<?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?>
						</time>
					<?php endif; ?>

					<h1 class="wp-block-post-title singular-post__title"><?php the_title(); ?></h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="wp-block-post-featured-image singular-post__featured"><?php the_post_thumbnail( 'large' ); ?></figure>
				<?php endif; ?>

				<div class="entry-content wp-block-post-content">
					<?php the_content(); ?>
				</div>

				<div class="post-meta-terms">
					<?php echo wp_kses_post( get_the_category_list( ', ' ) ); ?>
					<?php
					the_tags(
						'<div class="post-tags">',
						', ',
						'</div>'
					);
					?>
				</div>
			</article>
		</div>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
