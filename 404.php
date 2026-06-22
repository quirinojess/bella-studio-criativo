<?php
/**
 * Página 404.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="home-main wp-block-group">
	<h1 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Página não encontrada', 'isabela-lessa' ); ?></h1>
	<p class="has-text-align-center"><?php esc_html_e( 'O conteúdo que você procura não existe ou foi movido.', 'isabela-lessa' ); ?></p>
</main>

<?php
get_footer();
