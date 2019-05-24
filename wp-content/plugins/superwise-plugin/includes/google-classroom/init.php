<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/inc/course-interface.php';
require_once __DIR__ . '/inc/course-internal.php';
require_once __DIR__ . '/inc/course.php';
require_once __DIR__ . '/inc/google-classroom.php';
require_once __DIR__ . '/vc-addons/class-list/addon.php';
require_once __DIR__ . '/vc-addons/course-carousel/addon.php';
require_once __DIR__ . '/inc/post-type.php';
require_once __DIR__ . '/inc/template-manager.php';
require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/metaboxes.php';
require_once __DIR__ . '/inc/admin.php';

add_action( 'wp', 'agc_init' );
add_filter( 'post_subtitle', 'agc_filter_subtitle' );
add_action( 'wp_enqueue_scripts', array( 'Aislin_Classroom_Post_Type', 'scripts' ) );
add_action( 'wp_head', array( 'Aislin_Classroom_Post_Type', 'structured_data' ) );
add_action( 'admin_notices', 'agc_admin_notice_course', 100 );
add_action( 'dynamic_sidebar_before', 'agc_dynamic_sidebar_before' );

global $agc_course;
Aislin_Classroom_Post_Type::create_post_type();

function agc_admin_notice_course() {

	$current_screen = get_current_screen();
	if ($current_screen->id != Aislin_Classroom_Post_Type::POST_TYPE) {
		return;
	}
	$post = get_post();
	if (!$post->_course_id) {
		return;
	}

	$course = new Aislin_Classroom_Course($post->_course);

	$message = esc_attr( 'This class is a copy of %s. Please do not edit the content of the class here because it will be overwritten with the original content!', 'superwise-plugin' );
	$course_title = $course->get_title();
	$course_url = $course->get_link();
	$course_link = "<a href=\"{$course_url}\" target=\"_blank\">{$course_title}</a>";


    ?>
    <div class="notice notice-error">
        <p><?php printf( $message, $course_link ); ?></p>
    </div>
    <?php
}

function agc_dynamic_sidebar_before() {

	$post_type = Aislin_Classroom_Post_Type::POST_TYPE;

	if ( is_single() && get_post_type() == $post_type ) {

		$post_id = get_the_ID();
		$cat_ids = array();
		$taxonomy_name = 'agc_course_category';
		$categories = get_the_terms($post_id, $taxonomy_name);

		if (is_array($categories)) {
			foreach ($categories as $category) {
				$cat_ids = $category->term_id;
			}
		}

		$args = array(
			'posts_per_page' => apply_filters('agc_filter_related_courses_count', 3),
			'exclude'        => $post_id,
			'post_type'      => $post_type,
		);

		if (count($cat_ids)) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy_name,
					'field'    => 'id',
					'terms'    => $cat_ids,
				),
			);
		}

		$related_courses = get_posts($args);

		if (empty($related_courses)) {
			return;
		}
		?>

		<div class="widget_text widget custom_html-2 widget_custom_html">
			<h5 class="widget-title"><?php esc_html_e('Related Classes', 'superwise-plugin'); ?></h5>
			<ul class="related-courses">
				<?php foreach ( $related_courses as $post ) : ?>
					<?php 
						$course = get_course( $post, false );
					?>
					<li>
						<?php if ( has_post_thumbnail( $post ) ) : ?>
							
							<div class="img-container">
								<a href="<?php echo get_permalink( $post ) ?>"
								   title="<?php echo esc_attr( get_post_field( 'post_title', $post ) ); ?>"><?php echo get_the_post_thumbnail( $post, 'thumbnail', array( 'class' => 'related-courses-thumb' ) ); ?></a>
							</div>
						<?php endif; ?>
						<div class="data">
							<a href="<?php echo esc_attr( get_permalink( $post ) ); ?>"><?php echo esc_html( $course->get_title() ); ?></a>
							<p><?php echo esc_html( $course->get_section() ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>	
			</ul>
		</div>
		<?php
	}
}

function agc_filter_subtitle($subtitle) {

	if ( is_single() && get_post_type() == Aislin_Classroom_Post_Type::POST_TYPE ) {
		$course = get_course();
		if ($course) {
			return $course->get_section();
		}
		
	}

	return $subtitle;
}


function agc_init() {

	if (is_single() && get_post_type() == 'teacher') {

		$post_id = get_the_ID();
		$teacher_email = get_post_meta($post_id, 'email', true);

		if ( ! $teacher_email ) {
			return;
		}

		$success = Aislin_Classroom::init();

		if ( ! $success ) {
			return;
		}
		// only store course if transient expired
		$transient_key = $teacher_email . '_courses';
		if ( false ===  get_transient( $transient_key ) ) {

			$results = Aislin_Classroom::get_teacher_courses($teacher_email);

			Aislin_Classroom_Post_Type::store($results, $post_id);

			// we just want to have the transient, transient value is not important
		    set_transient( $transient_key, 'stored', 3 * HOUR_IN_SECONDS );
		}
	}
}

function get_course( $post_id = null, $use_global = true ) {

	global $agc_course;

	if($use_global && is_a($agc_course, 'Aislin_Classroom_Course')) {
		return $agc_course;
	}

	$post = get_post($post_id); 

	if ($post) {
		$google_course = $post->{Aislin_Classroom_Post_Type::META_COURSE};

		if ($google_course) {
			$agc_course = new Aislin_Classroom_Course($google_course, array(
				// adding extra props
				'student_count'           => $post->{Aislin_Classroom_Post_Type::META_STUDENT_COUNT},
				'teacher_ids'             => $post->{Aislin_Classroom_Post_Type::META_TEACHER_IDS},
				'is_featured'             => $post->{Aislin_Classroom_Post_Type::META_IS_FEATURED},
				'show_only_first_teacher' => $post->{Aislin_Classroom_Post_Type::META_SHOW_ONLY_FIRST_TEACHER},
			));
		} else {
			$agc_course = new Aislin_Classroom_Course_Internal($post);
		}
		return $agc_course;
	} 
	return false;
}

