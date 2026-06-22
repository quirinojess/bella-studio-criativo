<?php
/**
 * Navegação por âncoras no cabeçalho (landing page).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * Links de âncora do cabeçalho.
 *
 * @return array<int, array{label: string, id: string}>
 */
function isabela_lessa_get_header_anchor_links(): array {
	return array(
		array(
			'label' => __( 'Sobre o Studio', 'isabela-lessa' ),
			'id'    => 'sobre-o-studio',
		),
		array(
			'label' => __( 'Nossas Marcas', 'isabela-lessa' ),
			'id'    => 'nossas-marcas',
		),
		array(
			'label' => __( 'Blog', 'isabela-lessa' ),
			'id'    => 'blog',
		),
		array(
			'label' => __( 'Contato', 'isabela-lessa' ),
			'id'    => 'contato',
		),
	);
}

/**
 * URL absoluta para uma âncora da página inicial.
 *
 * @param string $anchor_id ID do alvo (sem #).
 * @return string
 */
function isabela_lessa_get_home_anchor_url( string $anchor_id ): string {
	$anchor_id = sanitize_title( $anchor_id );
	if ( $anchor_id === '' ) {
		return home_url( '/' );
	}

	if ( is_front_page() ) {
		return '#' . $anchor_id;
	}

	return home_url( '/#' . $anchor_id );
}

/**
 * Navegação horizontal de âncoras (desktop, abaixo do logo).
 *
 * @return string
 */
function isabela_lessa_render_header_categories_nav( string $extra_class = '' ): string {
	$links = isabela_lessa_get_header_anchor_links();
	$nav_class = trim( 'site-header__categories-nav ' . $extra_class );

	ob_start();
	?>
	<nav class="<?php echo esc_attr( $nav_class ); ?>" aria-label="<?php esc_attr_e( 'Navegação da página', 'isabela-lessa' ); ?>">
		<ul class="site-header__categories-list">
			<?php foreach ( $links as $link ) : ?>
				<li class="site-header__categories-item">
					<a href="<?php echo esc_url( isabela_lessa_get_home_anchor_url( $link['id'] ) ); ?>">
						<?php echo esc_html( $link['label'] ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
	return (string) ob_get_clean();
}

/**
 * Lista de âncoras no painel mobile.
 *
 * @return string
 */
function isabela_lessa_render_header_categories_mobile(): string {
	$links = isabela_lessa_get_header_anchor_links();

	ob_start();
	?>
	<nav class="site-header__anchor-nav site-header__anchor-nav--mobile" aria-label="<?php esc_attr_e( 'Navegação da página', 'isabela-lessa' ); ?>">
		<ul class="site-header__anchor-list">
			<?php foreach ( $links as $link ) : ?>
				<li class="site-header__anchor-item">
					<a href="<?php echo esc_url( isabela_lessa_get_home_anchor_url( $link['id'] ) ); ?>">
						<?php echo esc_html( $link['label'] ); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
	return (string) ob_get_clean();
}

/**
 * Shortcode legado (mantido por compatibilidade).
 *
 * @return string
 */
function isabela_lessa_render_categories_dropdown(): string {
	return isabela_lessa_render_header_categories_nav();
}

add_shortcode( 'isabela_lessa_categories_dropdown', 'isabela_lessa_render_categories_dropdown' );
