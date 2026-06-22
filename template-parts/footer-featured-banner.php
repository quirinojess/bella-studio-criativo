<?php
/**
 * Faixa com imagem destacada antes do rodapé (largura total).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

$image_id = function_exists( 'isabela_lessa_resolve_footer_banner_image_id' )
	? isabela_lessa_resolve_footer_banner_image_id()
	: 0;

if ( $image_id <= 0 ) {
	return;
}

$link = function_exists( 'isabela_lessa_get_footer_banner_link' )
	? isabela_lessa_get_footer_banner_link()
	: '';

$alt = (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true );
if ( $alt === '' ) {
	$alt = get_bloginfo( 'name', 'display' );
}
?>

<section class="eg-footer-banner" aria-label="<?php esc_attr_e( 'Imagem destacada', 'isabela-lessa' ); ?>">
	<?php if ( $link !== '' ) : ?>
		<a class="eg-footer-banner__link" href="<?php echo esc_url( $link ); ?>">
			<?php
			echo wp_get_attachment_image(
				$image_id,
				'full',
				false,
				array(
					'class'    => 'eg-footer-banner__img',
					'loading'  => 'lazy',
					'decoding' => 'async',
					'alt'      => $alt,
				)
			);
			?>
		</a>
	<?php else : ?>
		<figure class="eg-footer-banner__figure">
			<?php
			echo wp_get_attachment_image(
				$image_id,
				'full',
				false,
				array(
					'class'    => 'eg-footer-banner__img',
					'loading'  => 'lazy',
					'decoding' => 'async',
					'alt'      => $alt,
				)
			);
			?>
		</figure>
	<?php endif; ?>
</section>
