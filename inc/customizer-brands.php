<?php
/**
 * Personalizador: Nossas Marcas (página inicial).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function isabela_lessa_customize_brands( $wp_customize ): void {
	$wp_customize->add_section(
		'isabela_lessa_brands',
		array(
			'title'       => __( 'Nossas Marcas (página inicial)', 'isabela-lessa' ),
			'description' => __( 'Título da seção, texto introdutório, título da aba, logo e texto de cada marca. Deixe em branco para usar o padrão do tema.', 'isabela-lessa' ),
			'priority'    => 37,
		)
	);

	$wp_customize->add_setting(
		'isabela_lessa_brands_section_title',
		array(
			'default'           => isabela_lessa_get_default_brands_section_title(),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'isabela_lessa_brands_section_title',
		array(
			'label'   => __( 'Título da seção', 'isabela-lessa' ),
			'section' => 'isabela_lessa_brands',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'isabela_lessa_brands_section_intro',
		array(
			'default'           => isabela_lessa_get_default_brands_section_intro(),
			'sanitize_callback' => 'wp_kses_post',
		)
	);

	$wp_customize->add_control(
		'isabela_lessa_brands_section_intro',
		array(
			'label'   => __( 'Texto introdutório da seção', 'isabela-lessa' ),
			'section' => 'isabela_lessa_brands',
			'type'    => 'textarea',
		)
	);

	$defaults = isabela_lessa_get_default_brand_items();

	for ( $i = 1; $i <= 4; $i++ ) {
		$default_title = isset( $defaults[ $i - 1 ]['title'] ) ? (string) $defaults[ $i - 1 ]['title'] : '';
		$default_text  = isset( $defaults[ $i - 1 ]['text'] ) ? (string) $defaults[ $i - 1 ]['text'] : '';

		$wp_customize->add_setting(
			"isabela_lessa_brand_{$i}_title",
			array(
				'default'           => $default_title,
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			"isabela_lessa_brand_{$i}_title",
			array(
				/* translators: %d: brand tab number */
				'label'   => sprintf( __( 'Título da aba %d', 'isabela-lessa' ), $i ),
				'section' => 'isabela_lessa_brands',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			"isabela_lessa_brand_{$i}_logo",
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				"isabela_lessa_brand_{$i}_logo",
				array(
					/* translators: %d: brand tab number */
					'label'     => sprintf( __( 'Logo da marca %d', 'isabela-lessa' ), $i ),
					'section'   => 'isabela_lessa_brands',
					'mime_type' => 'image',
				)
			)
		);

		$wp_customize->add_setting(
			"isabela_lessa_brand_{$i}_text",
			array(
				'default'           => $default_text,
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			"isabela_lessa_brand_{$i}_text",
			array(
				/* translators: %d: brand tab number */
				'label'   => sprintf( __( 'Texto da marca %d', 'isabela-lessa' ), $i ),
				'section' => 'isabela_lessa_brands',
				'type'    => 'textarea',
			)
		);
	}
}

add_action( 'customize_register', 'isabela_lessa_customize_brands' );
