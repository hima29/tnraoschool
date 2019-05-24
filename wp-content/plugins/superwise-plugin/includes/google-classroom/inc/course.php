<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class Aislin_Classroom_Course implements Aislin_Course {

	protected $course;
	protected $student_count;
	protected $teacher_ids;
	protected $is_featured;
	protected $show_only_first_teacher;

	public function __construct($course = null, $args = array()) {

		if (!$course) {
			trigger_error('Course must be set.', E_USER_WARNING);
		}
		$this->course = $course;

		if (isset($args['student_count'])) {
			$this->student_count = $args['student_count'];
		}

		if (isset($args['teacher_ids'])) {
			$this->teacher_ids = $args['teacher_ids'];
		}

		if (isset($args['is_featured'])) {
			$this->is_featured = $args['is_featured'];
		}

		if (isset($args['show_only_first_teacher'])) {
			$this->show_only_first_teacher = $args['show_only_first_teacher'];
		}
	}

	public function get_course() {
		return $this->course;
	}

	public function get_id() {
		return $this->course->getId();
	}

	public function get_title() {
		return $this->course->getName();
	}

	public function get_section() {
		return $this->course->getSection();
	}

	public function get_description() {
		return $this->course->getDescription();
	}

	public function get_created_at() {
		return $this->course->getCreationTime();
	}

	public function get_updated_at() {
		return $this->course->getUpdateTime();
	}

	public function get_room() {
		return $this->course->getRoom();
	}

	public function get_calendar_id() {
		return $this->course->calendarId;
	}

	public function get_link() {
		return $this->course->alternateLink;
	}

	public function get_thumbnail($size) {
		foreach ($this->course->getCourseMaterialSets() as $material_set) {
			if ($material_set->getTitle() == 'class-thumbnail') {
				$thumb = $material_set->current()->getDriveFile()->getThumbnailUrl();

				if ($size) {
					$thumb = str_replace('&sz=s200', "&sz=s{$size}", $thumb);
				}

				return $thumb;
			}
		}
	}

	public function get_student_count() {
		return $this->student_count;
	}

	public function get_teacher_ids() {
		return $this->teacher_ids;
	}

}