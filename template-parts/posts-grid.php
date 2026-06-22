<?php
/**
 * Grelha de posts (arquivo / busca) + paginação numerada.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="posts-grid-wrap" id="posts-grid-wrap">
	<div class="posts-grid posts-grid--3col" id="posts-grid-loop" role="list">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'grid-card' );
		endwhile;
		?>
	</div>
	<?php
	if ( function_exists( 'isabela_lessa_the_posts_grid_pagination' ) ) {
		isabela_lessa_the_posts_grid_pagination();
	}
	?>
</div>
