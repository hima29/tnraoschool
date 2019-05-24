<li class="event">
	<div class="date">
		<?php echo tribe_events_event_schedule_details($post); ?>
	</div>
	<div class="info">
		<div class="title">
			<a style="<?php echo $main_heading_style_inline; ?>"
			   href="<?php echo tribe_get_event_link( $post ); ?>"
			   rel="bookmark"><?php echo $post->post_title; ?> »</a>
		</div>
		<?php if ( (int) $show_description ) : ?>
			<div class="content" style="<?php echo $sub_heading_style_inline; ?>;">
				<?php echo wp_trim_words( strip_shortcodes( $post->post_content ), $description_word_length, '&hellip;' ); ?>
			</div>
		<?php endif; ?>
	</div>
</li>