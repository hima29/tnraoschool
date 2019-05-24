<?php
$teachers = array();
$teacher_ids = $course->get_teacher_ids();

if (is_array($teacher_ids) && count($teacher_ids)) {

	$teachers = get_posts(array(
		'post_type' => 'teacher',
		'posts_per_page' => -1,
		'post__in' => array_map('intval', $teacher_ids),
		'orderby' => 'post__in'
	));
}
$teacher_names = array();

foreach ($teachers as $teacher) {
	$teacher_link = get_permalink( $teacher->ID );
	$teacher_names[] = "<a href=\"{$teacher_link}\">{$teacher->post_title}</a>";
}

?>

<h3 class="course-title">
    <a href="<?php echo get_permalink( $post_id ); ?>" title="<?php echo esc_attr( $post_title ); ?>"><?php echo esc_html( $post_title ); ?></a>
</h3>
<ul class="course-meta">
	<li>
		<i class="icon-edsuiteic_bookmark"></i>
		<span class="label"><?php esc_html_e('Section', 'superwise-plugin'); ?>:</span> <?php echo esc_html($section_name); ?>
	</li>
	<?php if ( (int) $show_teacher ): ?>
		
		<li>
			<i class="icon-edsuiteic_account_circle"></i>
			<span class="label"><?php esc_html_e('Teacher', 'superwise-plugin'); ?>:</span> <?php echo implode(', ', $teacher_names); ?>
		</li>
	<?php endif ?>
</ul>