<?php
/**
 * Template principal do tema.
 *
 * - Índice do blog (Configurações → Leitura: “Os teus últimos artigos”): este ficheiro
 *   é o template da página inicial (não existe home.php para não sobrepor o index).
 * - Arquivos, etc.: quando não há template mais específico (archive.php, search.php…).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_home() ) {
	get_template_part( 'template-parts/home', 'sections' );
} else {
	?>
<main id="main" class="home-main wp-block-group index-main">
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'excerpt' );
		endwhile;

		the_posts_pagination(
			array(
				'mid_size'  => 2,
				'prev_text' => __( 'Anterior', 'isabela-lessa' ),
				'next_text' => __( 'Próximo', 'isabela-lessa' ),
			)
		);
		?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nenhum conteúdo encontrado.', 'isabela-lessa' ); ?></p>
	<?php endif; ?>
</main>
	<?php
}

get_footer();
