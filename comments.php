<?php
/**
 * Template de comentários do post único.
 *
 * @package Isabela_Lessa
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}

if ( ! function_exists( 'isabela_lessa_render_comment' ) ) {
	/**
	 * Renderiza comentário no layout do tema.
	 *
	 * @param WP_Comment $comment Comentário atual.
	 * @param array      $args    Configurações da lista.
	 * @param int        $depth   Nível de profundidade.
	 */
	function isabela_lessa_render_comment( $comment, array $args, int $depth ): void {
		$tag              = ( 'div' === $args['style'] ) ? 'div' : 'li';
		$is_admin_comment = false;

		if ( ! empty( $comment->user_id ) ) {
			$user = get_userdata( (int) $comment->user_id );
			if ( $user instanceof WP_User ) {
				$is_admin_comment = in_array( 'administrator', (array) $user->roles, true );
			}
		}

		$article_classes = 'post-comment__article' . ( $is_admin_comment ? ' post-comment__article--admin' : '' );
		?>
		<<?php echo esc_html( $tag ); ?> <?php comment_class( 'post-comment' ); ?> id="comment-<?php comment_ID(); ?>">
			<article class="<?php echo esc_attr( $article_classes ); ?>">
				<div class="post-comment__avatar">
					<?php echo get_avatar( $comment, (int) $args['avatar_size'] ); ?>
				</div>

				<div class="post-comment__body">
					<header class="post-comment__meta">
						<cite class="post-comment__author"><?php echo esc_html( get_comment_author( $comment ) ); ?></cite>
						<time class="post-comment__date" datetime="<?php echo esc_attr( get_comment_time( 'c' ) ); ?>">
							<?php
							printf(
								/* translators: 1: date 2: time */
								esc_html__( '%1$s as %2$s', 'isabela-lessa' ),
								esc_html( get_comment_date( '', $comment ) ),
								esc_html( get_comment_time( 'H:i', false, $comment ) )
							);
							?>
						</time>
					</header>

					<div class="post-comment__content">
						<?php if ( '0' === $comment->comment_approved ) : ?>
							<p class="post-comment__awaiting"><?php esc_html_e( 'Seu comentário está aguardando moderação.', 'isabela-lessa' ); ?></p>
						<?php endif; ?>
						<?php comment_text(); ?>
					</div>

					<div class="post-comment__actions">
						<?php
						comment_reply_link(
							array_merge(
								$args,
								array(
									'add_below' => 'comment',
									'depth'     => $depth,
									'max_depth' => $args['max_depth'],
									'reply_text'=> esc_html__( 'Responder', 'isabela-lessa' ),
									'class'     => 'post-comment__reply-link',
								)
							)
						);
						?>
					</div>
				</div>
			</article>
		</<?php echo esc_html( $tag ); ?>>
		<?php
	}
}
?>

<section id="comments" class="post-comments" aria-label="<?php esc_attr_e( 'Comentários do post', 'isabela-lessa' ); ?>">
	<?php
	comment_form(
		array(
			'title_reply'          => esc_html__( 'Deixe um comentário', 'isabela-lessa' ),
			'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title post-comments__form-title">',
			'title_reply_after'    => '</h3>',
			'class_form'           => 'post-comments__form',
			'class_submit'         => 'post-comments__submit',
			'label_submit'         => esc_html__( 'Enviar', 'isabela-lessa' ),
			'comment_notes_before' => '',
			'comment_notes_after'  => '',
			'fields'               => array(
				'author' =>
					'<p class="comment-form-author post-comments__field">' .
					'<label for="author">' . esc_html__( 'Nome', 'isabela-lessa' ) . '</label>' .
					'<input id="author" name="author" type="text" autocomplete="name" required />' .
					'</p>',
				'email'  =>
					'<p class="comment-form-email post-comments__field">' .
					'<label for="email">' . esc_html__( 'E-mail', 'isabela-lessa' ) . '</label>' .
					'<input id="email" name="email" type="email" autocomplete="email" required />' .
					'</p>',
			),
			'comment_field'        =>
				'<p class="comment-form-comment post-comments__field post-comments__field--full">' .
				'<label for="comment">' . esc_html__( 'Mensagem', 'isabela-lessa' ) . '</label>' .
				'<textarea id="comment" name="comment" cols="45" rows="6" required></textarea>' .
				'</p>',
		)
	);
	?>

	<?php if ( have_comments() ) : ?>
		<h2 class="post-comments__title">
			<?php
			printf(
				/* translators: %s: comment count */
				esc_html( _n( '%s comentário', '%s comentários', get_comments_number(), 'isabela-lessa' ) ),
				esc_html( number_format_i18n( (int) get_comments_number() ) )
			);
			?>
		</h2>

		<ol class="post-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 56,
					'callback'    => 'isabela_lessa_render_comment',
				)
			);
			?>
		</ol>

		<?php the_comments_pagination(); ?>
	<?php endif; ?>
</section>
