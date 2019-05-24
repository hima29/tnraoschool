<?php
if ( post_password_required() ) {
	return;
}

if ( have_comments() ) : ?>
	<section id="comments">
		<h3><?php printf( _n( '1 Comment:', '%1$s Comments:', get_comments_number(), 'superwise' ), number_format_i18n( get_comments_number() ), get_the_title() ); ?></h3>
		<ul class="comment-list">
			<?php wp_list_comments( array( 'walker' => new Wheels_Walker_Comment ) ); ?>
		</ul>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav>
				<ul class="pager">
					<?php if ( get_previous_comments_link() ) : ?>
						<li class="previous"><?php previous_comments_link( esc_html__( '&larr; Older comments', 'superwise' ) ); ?></li>
					<?php endif; ?>
					<?php if ( get_next_comments_link() ) : ?>
						<li class="next"><?php next_comments_link( esc_html__( 'Newer comments &rarr;', 'superwise' ) ); ?></li>
					<?php endif; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<?php if ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
			<div class="alert alert-warning">
				<?php esc_html_e( 'Comments are closed.', 'superwise' ); ?>
			</div>
		<?php endif; ?>
	</section><!-- /#comments -->
<?php endif; ?>

<?php if ( ! have_comments() && ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
	<section id="comments">
		<div class="alert alert-warning">
			<?php esc_html_e( 'Comments are closed.', 'superwise' ); ?>
		</div>
	</section><!-- /#comments -->
<?php endif; ?>

<?php if ( comments_open() ) : ?>
	<section id="respond">
		<h3><?php comment_form_title( esc_html__( 'Leave a Reply', 'superwise' ), esc_html__( 'Leave a Reply to %s', 'superwise' ) ); ?></h3>
		<p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
		<p>
			<?php esc_html_e( 'Your email address will not be published. Required fields are marked *', 'superwise' ); ?>
		</p>
		<?php if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) : ?>
			<p>
				<?php esc_html_e('You must be logged in', 'superwise'); ?>
				<a href="<?php echo wp_login_url( get_permalink() ); ?>"
				   title="<?php esc_attr_e( 'Log in', 'superwise' ); ?>"><?php esc_html_e( 'Log in &raquo;', 'superwise' ); ?></a>
			</p>
		<?php else : ?>
			<form action="<?php echo get_option( 'siteurl' ); ?>/wp-comments-post.php" method="post" id="commentform" class="<?php echo is_user_logged_in() ? 'user-logged-in' : ''; ?>">
				<?php if ( is_user_logged_in() ) : ?>
					<p>
						<?php esc_html_e('Logged in as', 'superwise'); ?> <?php printf( '<a href="%s/wp-admin/profile.php">%s</a>' , get_option( 'siteurl' ), $user_identity ); ?>
						<a href="<?php echo wp_logout_url( get_permalink() ); ?>"
						   title="<?php esc_attr_e( 'Log out of this account', 'superwise' ); ?>"><?php esc_html_e( 'Log out &raquo;', 'superwise' ); ?></a>
					</p>
				<?php else : ?>

					<div class="one whole col-1">

						<div class="form-group one third">
							<input type="text" class="form-control" name="author" id="author"
							       value="<?php echo esc_attr( $comment_author ); ?>" size="22" <?php if ( $req ) {
								echo 'aria-required="true"';
							} ?>
							       placeholder="<?php esc_attr_e( 'Name', 'superwise' );
							       if ( $req ) {
								       echo ' *';
							       } ?>">
						</div>
						<div class="form-group third">
							<input type="email" class="form-control" name="email" id="email"
							       value="<?php echo esc_attr( $comment_author_email ); ?>"
							       size="22" <?php if ( $req ) {
								echo 'aria-required="true"';
							} ?>
							       placeholder="<?php esc_attr_e( 'Email', 'superwise' );
							       if ( $req ) {
								       echo ' *';
							       } ?>">
						</div>
						<div class="form-group third">
							<input type="url" class="form-control" name="url" id="url"
							       value="<?php echo esc_attr( $comment_author_url ); ?>" size="22"
							       placeholder="<?php esc_attr_e( 'Website', 'superwise' ); ?>">
						</div>
					</div>
				<?php endif; ?>
				<div class="one whole">
					<div class="form-group">
						<textarea name="comment" id="comment" class="form-control" rows="5"
							<?php if ( $req ) {
								echo 'aria-required="true"';
							} ?>
							      placeholder="<?php esc_attr_e( 'Comment', 'superwise' );
							      if ( $req ) {
								      echo ' *';
							      } ?>"></textarea>
					</div>
				</div>
				<div class="one whole">
					<input name="submit" class="btn btn-primary" type="submit" id="submit"
				          value="<?php esc_html_e( 'Submit Comment', 'superwise' ); ?>">
				</div>
				<?php comment_id_fields(); ?>
				<?php do_action( 'comment_form', $post->ID ); ?>
			</form>
		<?php endif; ?>
	</section><!-- /#respond -->
<?php endif; ?>
