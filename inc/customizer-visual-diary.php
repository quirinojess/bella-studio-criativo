<?php
/**
 * Personalizador: textos da secção Diário visual (página inicial).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function isabela_lessa_customize_visual_diary( $wp_customize ): void {
	$wp_customize->add_section(
		'isabela_lessa_visual_diary',
		array(
			'title'       => __( 'Diário visual (página inicial)', 'isabela-lessa' ),
			'description' => __( 'Textos da galeria acima do bloco YouTube. As fotos gerem-se em Diário visual no menu do WordPress.', 'isabela-lessa' ),
			'priority'    => 34,
		)
	);

	$wp_customize->add_setting(
		'isabela_lessa_visual_diary_title',
		array(
			'default'           => 'The Most Beautiful Moment in Life ★',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'isabela_lessa_visual_diary_title',
		array(
			'label'   => __( 'Título da secção', 'isabela-lessa' ),
			'section' => 'isabela_lessa_visual_diary',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'isabela_lessa_visual_diary_eyebrow',
		array(
			'default'           => '(B)ANTE',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'isabela_lessa_visual_diary_eyebrow',
		array(
			'label'   => __( 'Linha abaixo do título (ex.: subtítulo)', 'isabela-lessa' ),
			'section' => 'isabela_lessa_visual_diary',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'isabela_lessa_visual_diary_lead',
		array(
			'default'           => 'Eu estou ocupada demais me apaixonando pela vida. pela primeira vez, sinto que esse é o meu lugar. São os detalhes, o processo, as ideias e as perspectivas — tudo aquilo que eu gosto de ver, criar e concretizar.',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'isabela_lessa_visual_diary_lead',
		array(
			'label'       => __( 'Texto principal (largura total do bloco)', 'isabela-lessa' ),
			'description' => __( 'Ocupa toda a largura útil da secção, por baixo do título e da linha (B)ANTE.', 'isabela-lessa' ),
			'section'     => 'isabela_lessa_visual_diary',
			'type'        => 'textarea',
		)
	);
}

add_action( 'customize_register', 'isabela_lessa_customize_visual_diary' );
