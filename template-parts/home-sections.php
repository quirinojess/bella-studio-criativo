<?php
/**
 * Seções da página inicial (substitui templates/index.html).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;
?>

<main id="main" class="home-main wp-block-group">

	<?php get_template_part( 'template-parts/home', 'marquee-recursos' ); ?>

	<section id="sobre-o-studio" class="home-section home-section--studio wp-block-group" aria-label="<?php esc_attr_e( 'Sobre o studio', 'isabela-lessa' ); ?>">
		<div class="studio-section__accent-row studio-section__accent-row--top">
			<div class="studio-section__accent-spacer" aria-hidden="true"></div>
			<div class="studio-section__accent studio-section__accent--right">
				<span class="studio-section__accent-line" aria-hidden="true"></span>
				<span class="studio-section__accent-label"><?php esc_html_e( 'brasil', 'isabela-lessa' ); ?></span>
			</div>
		</div>

		<div class="studio-section__grid">
			<div class="studio-section__content">
				<p class="studio-section__brand"><?php echo esc_html( get_bloginfo( 'name', 'display' ) ); ?></p>
				<p class="studio-section__tagline"><?php esc_html_e( 'Sou Isabella, criadora da Bella Estúdio Criativo.', 'isabela-lessa' ); ?></p>
				<div class="studio-section__text">
					<p><?php esc_html_e( 'Minha paixão sempre foi transformar ideias em projetos criativos que unem organização, estética e estratégia.', 'isabela-lessa' ); ?></p>
					<p><?php esc_html_e( 'Ao longo dos anos desenvolvi marcas, produtos digitais, conteúdos e soluções criativas para empreendedoras que desejam construir negócios com mais personalidade.', 'isabela-lessa' ); ?></p>
					<p><?php esc_html_e( 'O Bella Estúdio Criativo nasceu desse propósito: criar um espaço onde criatividade e empreendedorismo se encontram para inspirar, ensinar e apoiar mulheres na construção de negócios mais autênticos, organizados e alinhados aos seus objetivos.', 'isabela-lessa' ); ?></p>
				</div>
				<div class="studio-section__accent studio-section__accent--bottom">
					<span class="studio-section__accent-label"><?php esc_html_e( 'Studio criativo', 'isabela-lessa' ); ?></span>
					<span class="studio-section__accent-line" aria-hidden="true"></span>
				</div>
			</div>

			<div class="studio-section__media">
				<img
					class="studio-section__media-image"
					src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/sobre-isabella.png' ); ?>"
					alt="<?php esc_attr_e( 'Isabella, criadora da Bella Estúdio Criativo', 'isabela-lessa' ); ?>"
					loading="lazy"
					decoding="async"
					width="1200"
					height="1500"
				/>
			</div>
		</div>
	</section>

	<?php
	if ( function_exists( 'isabela_lessa_render_brands_accordion' ) ) {
		isabela_lessa_render_brands_accordion();
	}
	?>

	<section id="blog" class="home-section home-section--novidades wp-block-group" aria-labelledby="section-blog-heading">
		<div class="blog-section__inner">
			<div class="blog-section__heading">
				<h2 id="section-blog-heading" class="blog-section__title"><?php esc_html_e( 'Blog', 'isabela-lessa' ); ?></h2>
			</div>

			<div class="home-blog-grid posts-grid--3col" role="list">
				<?php
				$q_blog = new WP_Query(
					array(
						'post_type'           => 'post',
						'posts_per_page'      => 3,
						'post_status'         => 'publish',
						'ignore_sticky_posts' => true,
					)
				);
				if ( $q_blog->have_posts() ) :
					while ( $q_blog->have_posts() ) :
						$q_blog->the_post();
						get_template_part( 'template-parts/content', 'grid-card' );
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>

			<?php
			$posts_archive_url = function_exists( 'isabela_lessa_get_blog_posts_url' )
				? isabela_lessa_get_blog_posts_url()
				: home_url( '/' );
			?>
			<div class="home-novidades__more-wrap">
				<div class="studio-section__accent studio-section__accent--right home-novidades__more-accent">
					<span class="studio-section__accent-line" aria-hidden="true"></span>
					<a class="studio-section__accent-label home-novidades__more" href="<?php echo esc_url( $posts_archive_url ); ?>">
						<?php esc_html_e( 'Ver mais posts', 'isabela-lessa' ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>

	<section class="home-section home-section--manifesto wp-block-group" aria-labelledby="manifesto-heading">
		<div class="manifesto-block">
			<p class="manifesto-block__eyebrow"><?php esc_html_e( 'Transformar o simples em algo bonito', 'isabela-lessa' ); ?></p>

			<h2 id="manifesto-heading" class="manifesto-block__headline">
				<span class="manifesto-block__headline-line">
					<?php esc_html_e( 'A inspiração existe, mas é no ', 'isabela-lessa' ); ?>
					<span class="manifesto-block__circled">
						<svg class="manifesto-block__scribble" viewBox="0 0 220 80" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
							<ellipse cx="110" cy="40" rx="102" ry="34" stroke="currentColor" stroke-width="2" stroke-linecap="round" transform="rotate(-2.5 110 40)"/>
						</svg>
						<span class="manifesto-block__circled-label"><?php esc_html_e( 'criar', 'isabela-lessa' ); ?></span>
					</span>
					<?php esc_html_e( ' que ela se torna visível', 'isabela-lessa' ); ?>
				</span>
			</h2>

			<p class="manifesto-block__lede">
				<?php esc_html_e( 'Propósito: criar coisas bonitas e materializar o que imagino.', 'isabela-lessa' ); ?>
			</p>
		</div>
	</section>

	<?php get_template_part( 'template-parts/home', 'contact' ); ?>

	<?php
	$yt_channel_url = isabela_lessa_youtube_channel_url();
	$yt_videos      = function_exists( 'isabela_lessa_get_youtube_videos' )
		? isabela_lessa_get_youtube_videos( 2 )
		: array();
	$yt_has_videos = ! empty( $yt_videos );
	?>
	<section id="youtube" class="home-section home-section--youtube wp-block-group" aria-labelledby="section-youtube-heading">
		<div class="youtube-section__inner">
			<a
				id="section-youtube-heading"
				class="youtube-section__label"
				href="<?php echo esc_url( $yt_channel_url ); ?>"
				target="_blank"
				rel="noopener noreferrer"
			><?php esc_html_e( 'YouTube', 'isabela-lessa' ); ?></a>

			<div class="youtube-carousel youtube-carousel--duo" data-youtube-carousel>
				<div class="youtube-carousel__viewport">
					<div class="youtube-carousel__track" data-youtube-track role="list">
					<?php if ( $yt_has_videos ) : ?>
						<?php foreach ( $yt_videos as $yt ) : ?>
							<article class="youtube-card" role="listitem">
								<a class="youtube-card__media" href="<?php echo esc_url( $yt['url'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $yt['title'] ); ?>">
									<img
										src="<?php echo esc_url( $yt['thumb'] ); ?>"
										alt=""
										loading="lazy"
										width="1280"
										height="720"
									/>
									<span class="youtube-card__play" aria-hidden="true"></span>
								</a>
							</article>
						<?php endforeach; ?>
					<?php else : ?>
						<?php for ( $i = 0; $i < 2; $i++ ) : ?>
							<article class="youtube-card youtube-card--placeholder" role="listitem" aria-hidden="true">
								<div class="youtube-card__media youtube-card__media--placeholder"></div>
							</article>
						<?php endfor; ?>
					<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

</main>
