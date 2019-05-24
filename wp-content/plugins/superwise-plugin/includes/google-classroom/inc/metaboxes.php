<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

add_filter( 'rwmb_meta_boxes', 'aislin_gc_register_meta_boxes', 100);

function aislin_gc_register_meta_boxes( $meta_boxes ) {

	$prefix     = 'superwise_'; // not using prefix here

	foreach ($meta_boxes as $index => $meta_box) {
		if (in_array('teacher', $meta_box['pages'])) {

			// put it at the top of the list
			array_unshift($meta_boxes[$index]['fields'], array(
				'id'   => Aislin_Classroom_Post_Type::META_EMAIL,
				'type' => 'text',
				'name' => esc_html__( 'Email', 'superwise-plugin' ),
				'desc' => esc_html__( 'Gmail address which is used to pull related classes from Google Classroom', 'superwise-plugin' ),
			));
		}
	}

	$teachers = get_posts(array('post_type' => 'teacher', 'posts_per_page' => -1));
	$teachers_array = array();
	foreach ( $teachers as $teacher ) {
		$teachers_array[ $teacher->ID ] = $teacher->post_title;
	}

	$meta_boxes[] = array(
		'title'  => 'Settings',
		'pages'  => array( Aislin_Classroom_Post_Type::POST_TYPE ), // can be used on multiple CPTs
		'fields' => array(
			array(
				'id'   => Aislin_Classroom_Post_Type::META_SECTION,
				'type' => 'text',
				'name' => esc_html__( 'Section', 'superwise-plugin' ),
				'desc' => esc_html__( 'Section title.', 'superwise-plugin' ),
			),
			array(
				'id'          => Aislin_Classroom_Post_Type::META_TEACHER_IDS, // prefix is left out so we have an id not related to the theme
				'type'        => 'select',
				'name'        => esc_html( 'Teachers', 'superwise-plugin' ),
				'desc'        => esc_html( 'Class teachers. You can reorder them here. First in the list will be highligted on single class page.', 'superwise-plugin' ),
				'options'     => $teachers_array,
				'clone'       => true,
				'sort_clone'  => true,
				'placeholder' => esc_html( 'Default' ),
			),
			array(
				'id'   => Aislin_Classroom_Post_Type::META_SHOW_ONLY_FIRST_TEACHER,
				'type' => 'checkbox',
				'name' => esc_html__( 'Show only first teacher', 'superwise-plugin' ),
				'desc' => esc_html__( 'When class has co-teachers, you can choose to show only one which is first in the list.', 'superwise-plugin' ),
			),
			array(
				'id'   => Aislin_Classroom_Post_Type::META_IS_FEATURED,
				'type' => 'checkbox',
				'name' => esc_html__( 'Is featured?', 'superwise-plugin' ),
				'desc' => esc_html__( 'For display in Course Carousel.', 'superwise-plugin' ),
			),
			array(
				'id'   => Aislin_Classroom_Post_Type::META_STUDENT_COUNT,
				'type' => 'text',
				'name' => esc_html__( 'Student count', 'superwise-plugin' ),
				'desc' => esc_html__( 'This value is used only for Internal classes. Classes imported from Google Classroom will ignore this.', 'superwise-plugin' ),
			),
			array(
				'id'   => Aislin_Classroom_Post_Type::META_ROOM,
				'type' => 'text',
				'name' => esc_html__( 'Room', 'superwise-plugin' ),
				'desc' => esc_html__( 'This value is used only for Internal classes. Classes imported from Google Classroom will ignore this.', 'superwise-plugin' ),
			),
		)
	);

	return $meta_boxes;
}
