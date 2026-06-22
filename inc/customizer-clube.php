<?php
/**
 * Personalizador: secção “Clube de cartas” (shortcode do formulário).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function isabela_lessa_customize_clube( $wp_customize ): void {
	$wp_customize->add_section(
		'isabela_lessa_clube',
		array(
			'title'       => __( 'Clube de cartas (página inicial)', 'isabela-lessa' ),
			'description' => __( 'Cole aqui o shortcode do formulário de inscrição (ex.: Contact Form 7, Mailchimp, etc.).', 'isabela-lessa' ),
			'priority'    => 36,
		)
	);

	$wp_customize->add_setting(
		'isabela_lessa_clube_shortcode',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'isabela_lessa_clube_shortcode',
		array(
			'label'       => __( 'Shortcode', 'isabela-lessa' ),
			'section'     => 'isabela_lessa_clube',
			'type'        => 'textarea',
			'input_attrs' => array(
				'rows' => 4,
			),
		)
	);
}

add_action( 'customize_register', 'isabela_lessa_customize_clube' );
