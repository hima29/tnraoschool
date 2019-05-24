<?php 

interface Aislin_Course {
	
	public function get_title();

	public function get_section();

	public function get_description();

	public function get_created_at();
	
	public function get_updated_at();

	public function get_room();

	public function get_calendar_id();

	public function get_link();

	public function get_student_count();

	public function get_teacher_ids();
}