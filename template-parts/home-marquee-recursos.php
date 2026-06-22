<?php
/**
 * Faixa marquee: títulos dos posts da categoria recursos-downloads.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

$marquee_items = function_exists( 'isabela_lessa_get_recursos_downloads_marquee_items' )
	? isabela_lessa_get_recursos_downloads_marquee_items()
	: array();

if ( empty( $marquee_items ) ) {
	return;
}

$render_track = static function ( array $items ): void {
	foreach ( $items as $item ) {
		?>
		<a class="recursos-marquee__link" href="<?php echo esc_url( $item['url'] ); ?>">
			<?php echo esc_html( $item['title'] ); ?>
		</a>
		<span class="recursos-marquee__sep" aria-hidden="true"> • </span>
		<?php
	}
};
?>

<div class="recursos-marquee" aria-label="<?php esc_attr_e( 'Recursos e downloads', 'isabela-lessa' ); ?>">
	<div class="recursos-marquee__viewport">
		<div class="recursos-marquee__track">
			<div class="recursos-marquee__group">
				<?php $render_track( $marquee_items ); ?>
			</div>
			<div class="recursos-marquee__group" aria-hidden="true">
				<?php $render_track( $marquee_items ); ?>
			</div>
		</div>
	</div>
</div>
