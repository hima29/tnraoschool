<div class="avatar-wrap">
	<?php echo get_avatar( $comment, $size = '54' ); ?>
</div>
<div class="body">
	<span class="author-link">
		<?php echo get_comment_author_link(); ?>
	</span> <i class="lnr lnr-clock"></i>
	<time datetime="<?php echo comment_date( 'c' ); ?>"><a
			href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( esc_html__( '%1$s', 'superwise' ), get_comment_date(), get_comment_time() ); ?></a>
	</time> /
	<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	<?php if (is_user_logged_in()): ?>

	/ <?php edit_comment_link( esc_html__( '(Edit)', 'superwise' ), '', '' ); ?>
	<?php endif ?>

	<?php if ( $comment->comment_approved == '0' ) : ?>
		<div class="alert alert-info">
			<?php esc_html_e( 'Your comment is awaiting moderation.', 'superwise' ); ?>
		</div>
	<?php endif; ?>

	<?php comment_text(); ?>
