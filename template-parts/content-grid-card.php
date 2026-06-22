<?php
/**
 * Card editorial do blog: thumb, título, resumo, leia mais.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card' ); ?> role="listitem">
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="blog-card__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'medium_large', array( 'class' => 'blog-card__img' ) ); ?>
		</a>
	<?php else : ?>
		<a class="blog-card__media blog-card__media--placeholder" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<span class="screen-reader-text"><?php the_title(); ?></span>
		</a>
	<?php endif; ?>

	<div class="blog-card__body">
		<h2 class="blog-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>
		<div class="blog-card__excerpt">
			<?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 42, ' [...]' ) ); ?>
		</div>
		<a class="blog-card__read" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Leia mais', 'isabela-lessa' ); ?>
		</a>
	</div>
</article>
