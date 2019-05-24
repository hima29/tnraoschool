<?php 

if ( ! function_exists( 'get_course' ) ) {
	return;
}

global $post_id;

$course = get_course( $post_id, false ); 
if (!$course)  {
	return;
} 
	
$section           = $course->get_section();
$room              = $course->get_room();
$student_count     = $course->get_student_count();
$created_at        = $course->get_created_at();
$teacher_ids       = $course->get_teacher_ids();

$teachers = array();
if (is_array($teacher_ids) && count($teacher_ids)) {

	$teachers = get_posts(array(
		'post_type' => 'teacher',
		'posts_per_page' => -1,
		'post__in' => array_map('intval', $teacher_ids),
		'orderby' => 'post__in'
	));
}
$teacher_links = array();
foreach ($teachers as $teacher) {
	$teacher_link = get_permalink($teacher);
	$teacher_links[] = '<span class="name"><a href="' . esc_attr($teacher_link) . '">' . esc_html($teacher->post_title) . '</a></span>';
}
?>
<div class="agc_course one whole wh-padding">
	<div class="thumbnail one third">
		<a href="<?php the_permalink(); ?>"><?php superwise_get_thumbnail( array( 'thumbnail' => 'superwise-square-small' ) ); ?></a>
	</div>
	<div class="item two thirds">
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $section ): ?>
			<h4><?php echo esc_html( $section ); ?></h4>
		<?php endif ?>

		<div class="teachers">
			<span class="label">
				<?php esc_html_e('Teachers', 'superwise'); ?>:
			</span>
			<?php echo implode(' ', $teacher_links); ?>
		</div>

		<div class="summary"><?php echo do_shortcode( get_the_excerpt() ); ?></div>

		<ul class="course-meta">

			<?php /* if ( $created_at ) : ?>
				<li>
					<p class="label"><?php esc_html_e('Date created', 'superwise'); ?></p>
					<p><?php echo date_i18n( get_option( 'date_format' ), strtotime( $created_at ) ); ?></p>
				</li>
			<?php endif;  */?>


			<?php if ( $room ) : ?>
				<li>
					<p class="label"><?php esc_html_e('Room', 'superwise'); ?></p>
					<p><?php echo esc_html( $room ); ?></p>
				</li>
			<?php endif; ?>

			<?php if ( $student_count ) : ?>
				<li>
					<p class="label"><?php esc_html_e('Students', 'superwise'); ?></p>
					<p><?php echo esc_html( $student_count ); ?></p>
				</li>
			<?php endif; ?>

			
		</ul>

		<div class="links">

			<a class="course-link-btn" 
				href="<?php echo get_permalink( $post->ID ); ?>" 
				title="<?php echo esc_attr( $course->get_title() ); ?>">
				<?php esc_html_e('See details', 'superwise'); ?></a>

				<?php if ( function_exists( 'agc_classroom_link_html' ) ) { agc_classroom_link_html( $course ); } ?>
		</div>

	</div>
</div>
