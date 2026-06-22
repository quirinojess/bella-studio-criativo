<?php
/**
 * Seção de contato (Forminator) na página inicial.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

if ( ! shortcode_exists( 'forminator_form' ) ) {
	return;
}
?>

<section id="contato" class="home-section home-section--contact wp-block-group" aria-labelledby="section-contact-heading">
	<div class="contact-section__inner">
		<h2 id="section-contact-heading" class="contact-section__title">
			<?php esc_html_e( 'Entre em contato', 'isabela-lessa' ); ?>
		</h2>

		<div class="contact-section__intro">
			<p>
				<?php esc_html_e( 'Tem uma ideia, uma marca em desenvolvimento, dúvidas sobre um curso, interesse em uma parceria ou simplesmente gostaria de trocar uma mensagem? Este espaço está aberto para projetos, colaborações, dúvidas e conversas.', 'isabela-lessa' ); ?>
			</p>
		</div>

		<div class="contact-section__form">
			<?php echo do_shortcode( '[forminator_form id="1443"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode Forminator. ?>
		</div>
	</div>
</section>
