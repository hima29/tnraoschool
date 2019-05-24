<div class="price">
	<?php $course_price = skilled_sensei_simple_course_price( $post_id ); ?>
	<?php if ( $course_price ): ?>
		<?php echo esc_html( $course_price ); ?>
	<?php else: ?>
		<span class="course-price free-course"><span class="amount"><?php _e( 'Free' ); ?></span></span>
	<?php endif; ?>
</div>
