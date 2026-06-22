<?php
/**
 * Trecho de post em listagens.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'wp-block-post' ); ?>>
	<h2 class="wp-block-post-title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</h2>
	<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	<div class="wp-block-post-excerpt">
		<?php the_excerpt(); ?>
	</div>
</article>
