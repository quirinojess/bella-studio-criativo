<?php
/**
 * Barra lateral do post único: autora + posts da mesma categoria.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

if ( get_post_type() !== 'post' ) {
	return;
}

$post_id   = get_the_ID();
$author_id = (int) get_post_field( 'post_author', $post_id );

$cat_ids = wp_get_post_categories( $post_id );
$related = null;
if ( ! empty( $cat_ids ) ) {
	$related = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( $post_id ),
			'category__in'        => $cat_ids,
			'ignore_sticky_posts' => true,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'no_found_rows'       => true,
		)
	);
}

$has_related = $related instanceof WP_Query && $related->have_posts();
?>

<section class="single-sidebar-widget single-sidebar-widget--author" aria-labelledby="single-author-heading">
	<h2 id="single-author-heading" class="single-sidebar-widget__title"><?php esc_html_e( 'Sobre a autora', 'isabela-lessa' ); ?></h2>
	<div class="single-author-card">
		<div class="single-author-card__frame">
			<a class="single-author-card__avatar-link" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
				<?php
				echo get_avatar(
					$author_id,
					480,
					'',
					'',
					array(
						'class' => 'single-author-card__avatar',
					)
				);
				?>
			</a>
		</div>
		<p class="single-author-card__name">
			<a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
				<?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?>
			</a>
		</p>
		<?php
		$bio = get_the_author_meta( 'description', $author_id );
		if ( is_string( $bio ) && trim( $bio ) !== '' ) :
			?>
			<div class="single-author-card__bio">
				<?php echo wp_kses_post( wpautop( $bio ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php if ( $has_related ) : ?>
	<hr class="single-sidebar__gold-rule" />
<?php endif; ?>

<?php
if ( $has_related ) :
	?>
	<section class="single-sidebar-widget single-sidebar-widget--related" aria-labelledby="single-related-heading">
		<h2 id="single-related-heading" class="single-sidebar-widget__title"><?php esc_html_e( 'Leia também', 'isabela-lessa' ); ?></h2>
		<ul class="single-related">
			<?php
			while ( $related->have_posts() ) :
				$related->the_post();
				$excerpt = get_the_excerpt();
				$excerpt = is_string( $excerpt ) ? wp_trim_words( wp_strip_all_tags( $excerpt ), 22, '…' ) : '';
				?>
				<li class="single-related__item">
					<a class="single-related__card" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<span class="single-related__thumb">
								<?php the_post_thumbnail( 'thumbnail' ); ?>
							</span>
						<?php endif; ?>
						<span class="single-related__content">
							<span class="single-related__title"><?php the_title(); ?></span>
							<?php if ( $excerpt !== '' ) : ?>
								<span class="single-related__excerpt"><?php echo esc_html( $excerpt ); ?></span>
							<?php endif; ?>
						</span>
					</a>
				</li>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</ul>
	</section>
	<?php
endif;
