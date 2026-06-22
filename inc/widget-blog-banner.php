<?php
/**
 * Widget: banner do blog (imagem + link, nova aba por padrão).
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

/**
 * Widget de banner lateral do blog.
 */
class Isabela_Lessa_Blog_Banner_Widget extends WP_Widget {

	/**
	 * Construtor.
	 */
	public function __construct() {
		parent::__construct(
			'isabela_lessa_blog_banner',
			__( 'Banner do blog', 'isabela-lessa' ),
			array(
				'description' => __( 'Imagem com link. Abre em nova aba por padrão.', 'isabela-lessa' ),
				'classname'   => 'blog-banner-widget',
			)
		);
	}

	/**
	 * Front-end.
	 *
	 * @param array<string, mixed> $args     Argumentos da sidebar.
	 * @param array<string, mixed> $instance Instância do widget.
	 */
	public function widget( $args, $instance ): void {
		$image_id = isset( $instance['image_id'] ) ? absint( $instance['image_id'] ) : 0;
		if ( $image_id < 1 ) {
			return;
		}

		$image = wp_get_attachment_image_src( $image_id, 'medium_large' );
		if ( ! is_array( $image ) || empty( $image[0] ) ) {
			return;
		}

		$link_url = isset( $instance['link_url'] ) ? esc_url( (string) $instance['link_url'] ) : '';
		$new_tab  = ! isset( $instance['new_tab'] ) || ! empty( $instance['new_tab'] );

		$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		$alt = is_string( $alt ) && trim( $alt ) !== '' ? $alt : __( 'Banner', 'isabela-lessa' );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$img = sprintf(
			'<img class="blog-banner-widget__img" src="%1$s" alt="%2$s" width="%3$d" height="%4$d" loading="lazy" decoding="async" />',
			esc_url( $image[0] ),
			esc_attr( $alt ),
			isset( $image[1] ) ? (int) $image[1] : 0,
			isset( $image[2] ) ? (int) $image[2] : 0
		);

		if ( $link_url !== '' ) {
			$link_attrs = array(
				'href'   => $link_url,
				'class'  => 'blog-banner-widget__link',
			);
			if ( $new_tab ) {
				$link_attrs['target'] = '_blank';
				$link_attrs['rel']    = 'noopener noreferrer';
			}
			$attr_string = '';
			foreach ( $link_attrs as $key => $value ) {
				$attr_string .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
			}
			echo '<a' . $attr_string . '>' . $img . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $img escapado acima.
		} else {
			echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Formulário no admin.
	 *
	 * @param array<string, mixed> $instance Instância.
	 * @return void
	 */
	public function form( $instance ): void {
		$image_id = isset( $instance['image_id'] ) ? absint( $instance['image_id'] ) : 0;
		$link_url = isset( $instance['link_url'] ) ? (string) $instance['link_url'] : '';
		$new_tab  = ! isset( $instance['new_tab'] ) || ! empty( $instance['new_tab'] );

		$preview_url = '';
		if ( $image_id > 0 ) {
			$preview = wp_get_attachment_image_src( $image_id, 'medium' );
			if ( is_array( $preview ) && ! empty( $preview[0] ) ) {
				$preview_url = $preview[0];
			}
		}

		$field_image = $this->get_field_id( 'image_id' );
		$field_link  = $this->get_field_id( 'link_url' );
		$field_tab   = $this->get_field_id( 'new_tab' );
		$name_image  = $this->get_field_name( 'image_id' );
		$name_link   = $this->get_field_name( 'link_url' );
		$name_tab    = $this->get_field_name( 'new_tab' );
		?>
		<div class="blog-banner-widget-admin" data-blog-banner-widget>
			<p>
				<label for="<?php echo esc_attr( $field_image ); ?>"><?php esc_html_e( 'Imagem do banner', 'isabela-lessa' ); ?></label>
			</p>
			<div class="blog-banner-widget-admin__preview" data-banner-preview>
				<?php if ( $preview_url !== '' ) : ?>
					<img src="<?php echo esc_url( $preview_url ); ?>" alt="" style="max-width:100%;height:auto;display:block;" />
				<?php endif; ?>
			</div>
			<p>
				<input
					type="hidden"
					class="widefat blog-banner-widget-admin__image-id"
					id="<?php echo esc_attr( $field_image ); ?>"
					name="<?php echo esc_attr( $name_image ); ?>"
					value="<?php echo esc_attr( (string) $image_id ); ?>"
					data-banner-image-id
				/>
				<button type="button" class="button button-secondary" data-banner-select>
					<?php esc_html_e( 'Selecionar imagem', 'isabela-lessa' ); ?>
				</button>
				<button type="button" class="button button-link-delete" data-banner-remove <?php echo $image_id > 0 ? '' : 'style="display:none;"'; ?>>
					<?php esc_html_e( 'Remover', 'isabela-lessa' ); ?>
				</button>
			</p>
			<p>
				<label for="<?php echo esc_attr( $field_link ); ?>"><?php esc_html_e( 'Link de destino', 'isabela-lessa' ); ?></label>
				<input
					class="widefat"
					id="<?php echo esc_attr( $field_link ); ?>"
					name="<?php echo esc_attr( $name_link ); ?>"
					type="url"
					value="<?php echo esc_attr( $link_url ); ?>"
					placeholder="https://"
				/>
			</p>
			<p>
				<input
					class="checkbox"
					type="checkbox"
					id="<?php echo esc_attr( $field_tab ); ?>"
					name="<?php echo esc_attr( $name_tab ); ?>"
					value="1"
					<?php checked( $new_tab ); ?>
				/>
				<label for="<?php echo esc_attr( $field_tab ); ?>"><?php esc_html_e( 'Abrir link em nova aba', 'isabela-lessa' ); ?></label>
			</p>
		</div>
		<?php
	}

	/**
	 * Guardar instância.
	 *
	 * @param array<string, mixed> $new_instance Nova instância.
	 * @param array<string, mixed> $old_instance Instância anterior.
	 * @return array<string, mixed>
	 */
	public function update( $new_instance, $old_instance ): array {
		unset( $old_instance );

		return array(
			'image_id' => isset( $new_instance['image_id'] ) ? absint( $new_instance['image_id'] ) : 0,
			'link_url' => isset( $new_instance['link_url'] ) ? esc_url_raw( (string) $new_instance['link_url'] ) : '',
			'new_tab'  => ! empty( $new_instance['new_tab'] ) ? 1 : 0,
		);
	}
}

/**
 * Regista o widget de banner.
 */
function isabela_lessa_register_blog_banner_widget(): void {
	register_widget( 'Isabela_Lessa_Blog_Banner_Widget' );
}

add_action( 'widgets_init', 'isabela_lessa_register_blog_banner_widget' );

/**
 * Media uploader no ecrã de widgets.
 *
 * @param string $hook_suffix Hook atual.
 */
function isabela_lessa_blog_banner_widget_admin_assets( string $hook_suffix ): void {
	if ( ! in_array( $hook_suffix, array( 'widgets.php', 'customize.php' ), true ) ) {
		return;
	}

	wp_enqueue_media();

	$path = get_stylesheet_directory() . '/src/js/blog-banner-widget-admin.js';
	$uri  = get_stylesheet_directory_uri() . '/src/js/blog-banner-widget-admin.js';

	if ( ! is_file( $path ) ) {
		return;
	}

	wp_enqueue_script(
		'isabela-lessa-blog-banner-widget',
		$uri,
		array( 'jquery', 'media-upload', 'media-views' ),
		(string) filemtime( $path ),
		true
	);
}

add_action( 'admin_enqueue_scripts', 'isabela_lessa_blog_banner_widget_admin_assets' );
