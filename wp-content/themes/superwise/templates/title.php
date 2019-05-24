<?php
$header_message        = superwise_get_option( 'archive-single-header-message', '' );
$enable_header_message = is_single() && ( get_post_type() == 'post' || get_post_type() == 'teacher' ) && ! empty( $header_message ) ? true : false;
$enable_breadcrumbs    = superwise_get_option( 'page-title-breadcrumbs-enable', false );
$breadcrumbs_position  = superwise_get_option( 'page-title-breadcrumbs-position', 'bellow_title' );
$page_title_layout     = superwise_get_option( 'page-title-layout', 'default' );

$blog_archive_subtitle = superwise_get_option( 'blog-archive-subtitle', '' );
?>
<?php if ( $enable_breadcrumbs && $breadcrumbs_position == 'above_title' ): ?>
	<?php get_template_part( 'templates/breadcrumbs' ); ?>
<?php endif ?>
<?php if ( $enable_header_message ) : ?>
	<div class="<?php echo superwise_class( 'header-mesage-row' ); ?>">
		<div class="<?php echo superwise_class( 'container' ); ?>">
			<div class="one whole wh-padding">
				<p><?php echo esc_html( $header_message ); ?></p>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php if ( superwise_page_title_enabled() ) : ?>
	<div class="<?php echo superwise_class( 'page-title-row' ); ?>">
		<?php if ( $page_title_layout == 'default' ): ?>
			<div class="<?php echo superwise_class( 'container' ); ?>">
				<div class="<?php echo superwise_class( 'page-title-grid-wrapper' ); ?>">
					<h1 class="<?php echo superwise_class( 'page-title' ); ?>"><?php echo superwise_title(); ?></h1>
					<?php if ( is_home() && $blog_archive_subtitle ) : ?>
						<h2 class="<?php echo superwise_class( 'page-subtitle' ); ?>"><?php echo esc_html( $blog_archive_subtitle ); ?></h2>
					<?php elseif ( is_page() 
						|| superwise_is_shop() 
						|| ( is_single() && get_post_type() == 'project' )
						|| ( is_single() && get_post_type() == 'teacher' ) 
						|| ( is_single() && get_post_type() == 'agc_course' ) 
						) : ?>
						<?php global $post;
						if ( superwise_is_shop() ) {
							$post_id = superwise_get_shop_page_id();
						} else {
							$post_id = $post->ID;
						}
						$subtitle = apply_filters('post_subtitle', superwise_get_rwmb_meta( 'subtitle_single_page', $post_id )); ?>
						<?php if ( $subtitle ) : ?>
							<h2 class="<?php echo superwise_class( 'page-subtitle' ); ?>"><?php echo esc_html( $subtitle ); ?></h2>
						<?php endif; ?>

						<?php if ( get_post_type() == 'agc_course' ): ?>
							<?php $course = get_course(); ?>
							<?php if ( $post->post_excerpt || is_a( $course, 'Aislin_Classroom_Course' ) ): ?>
								<div class="featured">
									<?php echo wp_trim_words( strip_shortcodes( $post->post_excerpt ), apply_filters( 'agc_featired_excerpt_word_count', 17 ), '&hellip;' ); ?>
									<?php if ( function_exists( 'agc_classroom_link_html' ) ) { agc_classroom_link_html( $course ); } ?>
								</div>
							<?php endif ?>
						<?php endif ?>
					<?php elseif ( is_single() ) : ?>
						<?php get_template_part( 'templates/entry-meta' ); ?>
					<?php endif; ?>
				</div>
			<?php endif ?>
		</div>
	</div>
<?php endif; ?>
<?php if ( $enable_breadcrumbs && $breadcrumbs_position == 'bellow_title' ): ?>
	<?php get_template_part( 'templates/breadcrumbs' ); ?>
<?php endif ?>
