<?php 

if ( ! function_exists( 'get_course' ) ) {
	return;
}

$course = get_course(); 
if (!$course)  {
	return;
} 
	
$caledar_api_key   = get_option('ac_calendar_api_key');
$room              = $course->get_room();
$student_count     = $course->get_student_count();
$created_at        = $course->get_created_at();
$updated_at        = $course->get_updated_at();
$link              = $course->get_link();
$calendar_id       = $course->get_calendar_id();
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
?>

<?php while ( have_posts() ) : the_post(); ?>
	<div <?php post_class(); ?>>
		<?php if ( ! superwise_get_option( 'archive-single-use-page-title', false ) ) : ?>
			<?php the_title( '<h1>', '</h1>' ); ?>
		<?php endif; ?>
		<ul class="teachers">
			<?php foreach ($teachers as $teacher): ?>
				<?php $teacher_link = get_permalink($teacher); ?>
				<li>
					<a href="<?php echo esc_attr($teacher_link); ?>"><?php superwise_get_thumbnail( array( 
						'post_id' => $teacher->ID, 
						'thumbnail' => 'superwise-featured-image'
						) ); ?>
						</a>
					<div class="info">
						<p><?php esc_html_e('Teacher', 'superwise'); ?></p>
						<h3><a href="<?php echo esc_attr($teacher_link); ?>"><?php echo esc_html($teacher->post_title); ?></a></h3>
					</div>
				</li>
			<?php endforeach ?>
		</ul>

		<ul class="course-meta">

			<?php if ( $created_at ) : ?>
				<li>
					<p class="label"><?php esc_html_e('Date created', 'superwise'); ?></p>
					<p><?php echo date_i18n( get_option( 'date_format' ), strtotime( $created_at ) ); ?></p>
				</li>
			<?php endif; ?>

			<?php if ( $updated_at ) : ?>
				<li>
					<p class="label"><?php esc_html_e('Last updated', 'superwise'); ?></p>
					<p><?php echo date_i18n( get_option( 'date_format' ), strtotime( $updated_at ) ); ?></p>
				</li>
			<?php endif; ?>

			<?php if ( $student_count ) : ?>
				<li>
					<p class="label"><?php esc_html_e('Students', 'superwise'); ?></p>
					<p><?php echo esc_html( $student_count ); ?></p>
				</li>
			<?php endif; ?>

			<?php if ( $room ) : ?>
				<li>
					<p class="label"><?php esc_html_e('Room', 'superwise'); ?></p>
					<p><?php echo esc_html( $room ); ?></p>
				</li>
			<?php endif; ?>

			
		</ul>
		<div class="thumbnail">
			<?php superwise_get_thumbnail( array( 'thumbnail' => 'superwise-square' ) ); ?>
		</div>
		<div class="content">
			<?php the_content(); ?>
		</div>
		<?php if ( function_exists( 'agc_classroom_link_html' ) ) { agc_classroom_link_html( $course ); } ?>
	</div>
<?php endwhile; ?>

<?php if ($caledar_api_key && $calendar_id): ?>
	
	<div id="calendar"></div>

	<script type='text/javascript'>

		jQuery(document).ready(function($) {
		    $('#calendar').fullCalendar({
		    	header: {
					left: 'prev,next today',
					center: 'title',
					right: 'month,agendaWeek,agendaDay,listWeek'
				},
		        googleCalendarApiKey: <?php echo json_encode($caledar_api_key) ?>,
		        events: {
		            googleCalendarId: <?php echo json_encode($calendar_id) ?>,
		            className: 'gcal-event'
		        }
		    });
		});

	</script>
<?php endif ?>
