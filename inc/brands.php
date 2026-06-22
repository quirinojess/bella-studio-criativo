<?php
/**
 * Seção Nossas Marcas (acordeão horizontal).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * Título padrão da seção de marcas.
 */
function isabela_lessa_get_default_brands_section_title(): string {
	return __( 'Marcas e iniciativas desenvolvidas ao longo dos anos', 'isabela-lessa' );
}

/**
 * Texto introdutório padrão da seção de marcas.
 */
function isabela_lessa_get_default_brands_section_intro(): string {
	return __( 'Ao longo dos anos, diferentes projetos contribuíram para a construção da visão criativa e empreendedora que hoje dá vida à Bella Estúdio Criativo. Conheça algumas dessas iniciativas.', 'isabela-lessa' );
}

/**
 * Marcas padrão.
 *
 * @return array<int, array{title: string, text: string, logo: string}>
 */
function isabela_lessa_get_default_brand_items(): array {
	return array(
		array(
			'title' => 'STUDIO 1873',
			'text'  => __( 'Espaço criativo idealizado para reunir trabalho, inspiração e desenvolvimento de projetos, funcionando como um ambiente dedicado à criação, organização e crescimento de diferentes iniciativas empreendedoras.', 'isabela-lessa' ),
			'logo'  => 'assets/brands/1873.png',
		),
		array(
			'title' => 'THÉRÈSE & DESIGN (2017–2024)',
			'text'  => __( 'Primeiro negócio da minha trajetória empreendedora, criado durante a faculdade e voltado ao público católico. Ao longo de sete anos, a marca alcançou clientes em todo o Brasil, desenvolveu parcerias importantes e consolidou sua presença em seu nicho. Em 2024, foi vendida, encerrando um ciclo que teve papel fundamental na construção da minha experiência como empreendedora.', 'isabela-lessa' ),
			'logo'  => 'assets/brands/ted.png',
		),
		array(
			'title' => 'ALMA MODESTA',
			'text'  => __( "Projeto criado para inspirar mulheres a cultivarem uma vida mais simples, intencional e conectada aos seus valores. Através de conteúdos, trocas e experiências compartilhadas, a Alma Modesta reuniu uma comunidade engajada que encontrou no projeto um espaço de acolhimento, inspiração e crescimento.\n\nAlém da dimensão pessoal e espiritual, a comunidade também despertou em muitas mulheres o interesse pela criatividade e pelo empreendedorismo, incentivando o surgimento de novos projetos e iniciativas.", 'isabela-lessa' ),
			'logo'  => 'assets/brands/alma-modesta.png',
		),
		array(
			'title' => 'PIED & PIPER',
			'text'  => __( "Projeto criativo dedicado ao desenvolvimento de produtos, coleções e experiências inspiradas pelo entretenimento, pela cultura pop e pelo universo das comunidades de fãs. A marca reuniu pessoas apaixonadas por arte, música, histórias e expressão criativa, transformando interesses compartilhados em conexões e experiências memoráveis.\n\nMais do que uma loja, a Pied & Piper representou um exercício de construção de comunidade, identidade de marca e desenvolvimento de produtos voltados para um público altamente engajado.", 'isabela-lessa' ),
			'logo'  => 'assets/brands/pied.png',
		),
	);
}

/**
 * URL da logo padrão de uma marca (arquivo no tema).
 *
 * @param string $relative_path Caminho relativo ao diretório do tema.
 */
function isabela_lessa_get_brand_logo_url( string $relative_path ): string {
	$relative_path = ltrim( $relative_path, '/' );
	$absolute_path = get_stylesheet_directory() . '/' . $relative_path;

	if ( $relative_path === '' || ! is_readable( $absolute_path ) ) {
		return '';
	}

	return get_stylesheet_directory_uri() . '/' . $relative_path;
}

/**
 * Marcas (personalizador ou padrão).
 *
 * @return array<int, array{title: string, text: string, logo_id: int, logo_url: string}>
 */
function isabela_lessa_get_brand_items(): array {
	$defaults = isabela_lessa_get_default_brand_items();
	$items    = array();

	for ( $i = 1; $i <= 4; $i++ ) {
		$default_title = isset( $defaults[ $i - 1 ]['title'] ) ? (string) $defaults[ $i - 1 ]['title'] : '';
		$default_text  = isset( $defaults[ $i - 1 ]['text'] ) ? (string) $defaults[ $i - 1 ]['text'] : '';
		$default_logo  = isset( $defaults[ $i - 1 ]['logo'] ) ? (string) $defaults[ $i - 1 ]['logo'] : '';

		$title = trim( (string) get_theme_mod( "isabela_lessa_brand_{$i}_title", $default_title ) );
		$text  = trim( (string) get_theme_mod( "isabela_lessa_brand_{$i}_text", $default_text ) );
		$logo  = absint( get_theme_mod( "isabela_lessa_brand_{$i}_logo", 0 ) );

		if ( $title === '' ) {
			continue;
		}

		$items[] = array(
			'title'    => $title,
			'text'     => $text,
			'logo_id'  => $logo,
			'logo_url' => isabela_lessa_get_brand_logo_url( $default_logo ),
		);
	}

	return $items;
}

/**
 * Renderiza o acordeão de marcas.
 */
function isabela_lessa_render_brands_accordion(): void {
	$items = isabela_lessa_get_brand_items();
	if ( $items === array() ) {
		return;
	}

	$section_title = trim( (string) get_theme_mod( 'isabela_lessa_brands_section_title', isabela_lessa_get_default_brands_section_title() ) );
	$section_intro = trim( (string) get_theme_mod( 'isabela_lessa_brands_section_intro', isabela_lessa_get_default_brands_section_intro() ) );
	?>
	<section id="nossas-marcas" class="home-section home-section--marcas wp-block-group" aria-labelledby="section-marcas-heading">
		<div class="marcas-section__inner">
			<?php if ( $section_title !== '' ) : ?>
				<h2 id="section-marcas-heading" class="marcas-section__title"><?php echo esc_html( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_intro !== '' ) : ?>
				<div class="marcas-section__intro">
					<?php echo wp_kses_post( wpautop( $section_intro ) ); ?>
				</div>
			<?php endif; ?>

			<div class="marcas-haccordion" data-marcas-accordion>
				<div
					class="marcas-haccordion__tabs-left"
					data-marcas-tabs-left
					role="tablist"
					aria-label="<?php esc_attr_e( 'Marcas fechadas', 'isabela-lessa' ); ?>"
				>
					<?php foreach ( $items as $index => $item ) : ?>
						<?php if ( 0 === $index ) : ?>
							<?php continue; ?>
						<?php endif; ?>
						<?php
						$item_number = $index + 1;
						$trigger_id  = 'marca-trigger-' . $item_number;
						$panel_id    = 'marca-panel-' . $item_number;
						?>
						<button
							id="<?php echo esc_attr( $trigger_id ); ?>"
							class="marcas-haccordion__tab marcas-haccordion__tab--<?php echo esc_attr( (string) $item_number ); ?> is-left"
							type="button"
							role="tab"
							aria-selected="false"
							aria-controls="<?php echo esc_attr( $panel_id ); ?>"
							data-marcas-tab
							data-marcas-index="<?php echo esc_attr( (string) $index ); ?>"
						>
							<span class="marcas-haccordion__tab-label"><?php echo esc_html( $item['title'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>

				<div class="marcas-haccordion__stage">
					<?php foreach ( $items as $index => $item ) : ?>
						<?php
						$item_number = $index + 1;
						$trigger_id  = 'marca-trigger-' . $item_number;
						$panel_id    = 'marca-panel-' . $item_number;
						$is_first    = 0 === $index;
						?>
						<div
							id="<?php echo esc_attr( $panel_id ); ?>"
							class="marcas-haccordion__panel<?php echo $is_first ? ' is-active' : ''; ?>"
							role="tabpanel"
							aria-labelledby="<?php echo esc_attr( $trigger_id ); ?>"
							aria-hidden="<?php echo $is_first ? 'false' : 'true'; ?>"
							<?php echo $is_first ? '' : 'hidden'; ?>
							data-marcas-panel
						>
							<div class="marcas-haccordion__panel-grid">
								<div class="marcas-haccordion__logo">
									<?php if ( $item['logo_id'] > 0 ) : ?>
										<?php
										echo wp_get_attachment_image(
											$item['logo_id'],
											'medium',
											false,
											array(
												'class'    => 'marcas-haccordion__logo-image',
												'loading'  => 'lazy',
												'decoding' => 'async',
												'alt'      => $item['title'],
											)
										);
										?>
									<?php elseif ( $item['logo_url'] !== '' ) : ?>
										<img
											class="marcas-haccordion__logo-image"
											src="<?php echo esc_url( $item['logo_url'] ); ?>"
											alt="<?php echo esc_attr( $item['title'] ); ?>"
											loading="lazy"
											decoding="async"
										/>
									<?php else : ?>
										<div class="marcas-haccordion__logo-placeholder" role="img" aria-label="<?php echo esc_attr( $item['title'] ); ?>"></div>
									<?php endif; ?>
								</div>

								<div class="marcas-haccordion__text">
									<?php if ( $item['text'] !== '' ) : ?>
										<?php echo wp_kses_post( wpautop( $item['text'] ) ); ?>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div
					class="marcas-haccordion__tabs-right"
					data-marcas-tabs-right
					role="tablist"
					aria-label="<?php esc_attr_e( 'Marcas abertas', 'isabela-lessa' ); ?>"
				>
					<?php
					$first_item   = $items[0];
					$first_number = 1;
					$first_id     = 'marca-trigger-' . $first_number;
					$first_panel  = 'marca-panel-' . $first_number;
					?>
					<button
						id="<?php echo esc_attr( $first_id ); ?>"
						class="marcas-haccordion__tab marcas-haccordion__tab--<?php echo esc_attr( (string) $first_number ); ?> is-right is-active"
						type="button"
						role="tab"
						aria-selected="true"
						aria-controls="<?php echo esc_attr( $first_panel ); ?>"
						data-marcas-tab
						data-marcas-index="0"
					>
						<span class="marcas-haccordion__tab-label"><?php echo esc_html( $first_item['title'] ); ?></span>
						<span class="marcas-haccordion__tab-caret" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		</div>
	</section>
	<?php
}
