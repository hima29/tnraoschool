<?php

class Aislin_Classroom_Course_Internal implements Aislin_Course {

	protected $post;

	public function __construct($post) {
		$this->post = $post;
	}

	public function get_title() {
		return $this->post->post_title;
	}

	public function get_section() {
		return $this->post->{Aislin_Classroom_Post_Type::META_SECTION};
	}

	public function get_description() {
		return do_shortcode($this->post->post_content);
	}

	public function get_created_at() {
		return $this->post->post_date;
	}

	public function get_updated_at() {
		return $this->post->post_modified;
	}

	public function get_room() {
		return $this->post->{Aislin_Classroom_Post_Type::META_ROOM};
	}

	public function get_calendar_id() {
		return 0;
	}

	public function get_link() {
		return get_permalink($this->post);
	}

	public function get_student_count() {
		return $this->post->{Aislin_Classroom_Post_Type::META_STUDENT_COUNT};
	}

	public function get_teacher_ids() {
		return $this->post->{Aislin_Classroom_Post_Type::META_TEACHER_IDS};
	}
}