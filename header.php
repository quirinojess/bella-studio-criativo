<?php
/**
 * Cabeçalho do site (HTML/PHP estático).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

$is_landing_header = is_front_page() && ! is_search();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="wp-block-group eg-site-header">
	<div class="eg-site-header__bar">
		<div class="wp-block-group eg-site-header__inner eg-site-header__inner--bar">
			<div class="eg-site-header__top">
				<div class="eg-site-header__brand">
					<a class="site-header__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
						<span class="site-header__logo-image">
							<?php isabela_lessa_render_logo( array( 'suffix' => 'header' ) ); ?>
						</span>
					</a>
				</div>

				<div class="site-header__top-end">
					<?php if ( $is_landing_header && function_exists( 'isabela_lessa_render_header_categories_nav' ) ) : ?>
						<?php echo isabela_lessa_render_header_categories_nav( 'site-header__categories-nav--bar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML escapado na função. ?>
					<?php endif; ?>
					<button type="button" class="site-header__menu-toggle" aria-expanded="false" aria-controls="site-header-panel" aria-label="<?php esc_attr_e( 'Abrir menu', 'isabela-lessa' ); ?>">
						<span class="site-header__menu-icon" aria-hidden="true"><span></span><span></span><span></span></span>
					</button>
				</div>
			</div>

			<?php if ( $is_landing_header && function_exists( 'isabela_lessa_render_header_categories_nav' ) ) : ?>
				<div class="eg-site-header__below">
					<?php echo isabela_lessa_render_header_categories_nav(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML escapado na função. ?>
				</div>
			<?php endif; ?>

			<div id="site-header-panel" class="wp-block-group site-header__panel" hidden>
				<?php if ( function_exists( 'isabela_lessa_render_header_categories_mobile' ) ) : ?>
					<?php echo isabela_lessa_render_header_categories_mobile(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML escapado na função. ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="eg-site-header__bar-spacer" aria-hidden="true"></div>

	<?php if ( $is_landing_header ) : ?>
		<figure class="site-header__banner" aria-label="<?php esc_attr_e( 'Banner editorial', 'isabela-lessa' ); ?>">
			<img
				class="site-header__banner-image"
				src="<?php echo esc_url( get_template_directory_uri() . '/assets/banner-header.png' ); ?>"
				alt=""
				width="1920"
				height="400"
				decoding="async"
				fetchpriority="high"
			/>
		</figure>
	<?php endif; ?>
</header>
