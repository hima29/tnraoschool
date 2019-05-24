<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class Aislin_Classroom_Post_Type {
	
	const POST_TYPE          = 'agc_course';
	const TAXONOMY_CATEGORY  = 'agc_course_category';
	const OPTION_COURSE_MAP  = 'aislin_gc_course_map';

	const META_COURSE_ID               = '_course_id';
	const META_COURSE                  = '_course';
	const META_SECTION                 = '_section';
	const META_TEACHER_IDS             = '_teacher_ids';
	const META_STUDENT_COUNT           = '_student_count';

	const META_ROOM                    = 'room';
	const META_EMAIL                   = 'email';
	const META_IS_FEATURED             = 'is_featured';
	const META_SHOW_ONLY_FIRST_TEACHER = 'show_only_first_teacher';

	const FILTER_COURSE_SLUG = 'agc_filter_course_slug';
	

	public static function scripts() {

		if ( is_single() && get_post_type() == self::POST_TYPE ) {

			wp_enqueue_style( 'fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.css' );
			wp_enqueue_style( 'fullcalendar.print', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.print.css', array(), false, 'print' );

			wp_enqueue_script( 'moment.js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js', array('jquery') );
			wp_enqueue_script( 'fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.js', array('jquery') );
			wp_enqueue_script( 'gcal', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/gcal.min.js', array('jquery') );
		}
	}

	public static function structured_data() {

		if ( is_single() && get_post_type() == self::POST_TYPE ) {

			$use_structured_data = (int) get_option('ac_use_course_structured_data');

			if (! $use_structured_data) {
				return;
			}

			$org_name = get_option('ac_organization_name');

			if (! $org_name) {
				return;
			}



			$course = get_course();

			if (! $course) {
				return;
			}

			$data = array(
				'@context'    => 'http://schema.org',
				'@type'       => 'course',
				'name'        => $course->get_title(),
				'description' => $course->get_description(),
				'provider'    => array(
					'@type'  => 'Organization',
					'name'   => $org_name,
					'sameAs' => get_home_url(),
				),
			);

			?>
				<script type="application/ld+json">
					<?php echo json_encode($data); ?>
				</script>
			<?php
		}
	}

	public static function test() {
		// dd(self::get_map());

		$post_ids = get_posts(array(
			'post_type'   => self::POST_TYPE,
			'numberposts' => -1,
			'fields'      => 'ids',
		));

		$map = array();
		foreach ($post_ids as $post_id) {
			$course_id = get_post_meta($post_id, self::META_COURSE_ID, true);
			if ($course_id) {
				$map[$course_id] = $post_id;
			}
		}

		dd($map);
	}

	public static function store_student_count($course_id, $students) {

		$map = self::get_map();

		if ($map) {
			$post_id = self::get_imported_post_type_id($course_id, $map);

			if ($post_id) {
				update_post_meta( $post_id, Aislin_Classroom_Post_Type::META_STUDENT_COUNT, count($students));
			}
		}


	}

	public static function store_students($data) {

		if (!is_array($data)) {
			return false;
		}

		foreach ($data as $course_id => $students) {
			if (count($students)) {
				self::store_student_count($course_id, $students);
			}
		}
	}

	public static function create_post_type() {

		$cpt = new CPT( array(
			'post_type_name' => self::POST_TYPE,
			'singular'       => 'Class',
			'plural'         => 'Classes',
			'slug'           => apply_filters(self::FILTER_COURSE_SLUG, 'classes'),
			), array(
				'supports'        => array( 
					'title', 
					'editor', 
					'thumbnail', 
					'excerpt'
				 ),
				'has_archive' => true,
				'capability_type' => 'post',
				'capabilities' => array(
				    // 'create_posts' => 'do_not_allow', // Removes support for the "Add New" function, including Super Admin's
				),
				'map_meta_cap' => true, // Set to false, if users are not allowed to edit/delete existing posts
			) );
		$cpt->set_textdomain( 'superwise-plugin' );
		$cpt->menu_icon( 'dashicons-welcome-learn-more' );

		$cpt->columns( array(
			'cb'                    => '<input type="checkbox" />',
			'title'                 => __( 'Title' ),
			self::TAXONOMY_CATEGORY => __( 'Category' ),
			'source'                => __( 'Source' ),
			'date'                  => __( 'Date' )
		) );
		$cpt->populate_column( 'source', function ( $column, $post ) {

			$google_course = $post->_course;

			if ($google_course) {
				$course = new Aislin_Classroom_Course($google_course);
				echo '<a class="notice notice-warning" target="_blank" href="' . $course->get_link() . '">' . $course->get_title() . '</a>';
			} else {
				esc_html_e('Internal', 'superwise-plugin');
			}

		} );


		$cpt->register_taxonomy( array(
				'taxonomy_name' => self::TAXONOMY_CATEGORY,
				'singular'      => 'Category',
				'plural'        => 'Categories',
			) );
	}

	

	public static function store($google_courses, $wp_teacher_id = null) {

		$map = self::get_map();

		foreach ($google_courses as $course) {
			$post_id = self::get_imported_post_type_id($course->get_id(), $map);

			if ($post_id) {
				self::update($post_id, $course, $wp_teacher_id);
			} else {
				self::create_new_post($course, $wp_teacher_id);
			}

		}

		self::remap();

	}

	protected static function remap() {

		$post_ids = get_posts(array(
			'post_type'   => self::POST_TYPE,
			'numberposts' => -1,
			'fields'      => 'ids',
		));

		$map = array();
		foreach ($post_ids as $post_id) {
			$course_id = get_post_meta($post_id, self::META_COURSE_ID, true);
			if ($course_id) {
				$map[$course_id] = $post_id;
			}
		}

		return update_option(self::OPTION_COURSE_MAP, $map);
	}

	protected static function get_map() {
		return get_option(self::OPTION_COURSE_MAP);
	}

	protected static function get_imported_post_type_id($course_id, $map) {

		if (isset($map[$course_id])) {
			return $map[$course_id];
		}
		return false;
	}

	protected static function create_new_post($course, $wp_teacher_id) {

		$data = array(
			'post_type'   => self::POST_TYPE,
			'post_title'  => wp_strip_all_tags( $course->get_title() ),
			'post_status' => 'publish',
			'post_author' => 1,
		);
		 
		$post_id = wp_insert_post( $data );

		if ($post_id) {
			self::update_post_meta($post_id, $course, $wp_teacher_id);

			return $post_id;
		}

		return false;
	}

	protected static function update($post_id, $course, $wp_teacher_id) {

		$data = array(
			'ID'           => $post_id,
			'post_title'   => $course->get_title(),
			'post_content' => $course->get_description(),
		);

		$post_id = wp_update_post( $data );

		if ($post_id) {
			self::update_post_meta($post_id, $course, $wp_teacher_id);

			return $post_id;
		}

		return false;
	}

	protected static function update_post_meta($post_id, $course, $wp_teacher_id) {
		
		update_post_meta( $post_id, self::META_COURSE_ID, $course->get_id());
		update_post_meta( $post_id, self::META_COURSE, $course->get_course());
		update_post_meta( $post_id, self::META_SECTION, $course->get_section());

		if (!$wp_teacher_id) {
			return;
		}

		$teacher_ids = get_post_meta($post_id, self::META_TEACHER_IDS, true);

		if (!is_array($teacher_ids)) {
			$teacher_ids = array($wp_teacher_id);
		}

		if (!in_array($wp_teacher_id, $teacher_ids)) {
			$teacher_ids[] = $wp_teacher_id;
		} 
		update_post_meta( $post_id, self::META_TEACHER_IDS, $teacher_ids);
	}

}