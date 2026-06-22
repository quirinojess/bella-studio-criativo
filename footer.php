<?php
/**
 * Rodapé do site (HTML/PHP estático).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;
?>

<?php get_template_part( 'template-parts/footer-featured-banner' ); ?>

<footer class="wp-block-group eg-site-footer">
	<div class="eg-site-footer__inner">
		<p class="eg-site-footer__credits">
			<?php esc_html_e( 'Desenvolvido por', 'isabela-lessa' ); ?>
			<a class="eg-site-footer__credits-link" href="https://www.qrno.com.br/" target="_blank" rel="noopener noreferrer">QRNO</a>
		</p>

		<a class="eg-site-footer__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
			<span class="eg-site-footer__logo-image">
				<?php isabela_lessa_render_logo( array( 'suffix' => 'footer' ) ); ?>
			</span>
		</a>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
