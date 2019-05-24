<?php
require_once( get_template_directory() . '/lib/demo-importer/includes/Wheels_Importer_Interface.php' );
require_once( get_template_directory() . '/lib/demo-importer/includes/Wheels_Theme_Options_Importer.php' );
require_once( get_template_directory() . '/lib/demo-importer/includes/Wheels_XML_Importer.php' );
require_once( get_template_directory() . '/lib/demo-importer/includes/Wheels_Layer_Slider_Importer.php' );
require_once( get_template_directory() . '/lib/demo-importer/includes/Wheels_Menu_Importer.php' );
require_once( get_template_directory() . '/lib/demo-importer/includes/Wheels_Widgets_Importer.php' );

class Wheels_Import_Manager {

	protected $textdomain = 'wheels';
	protected $demo_files_path;

	public function __construct() {

		$this->set_demo_files_path( get_template_directory() . '/lib/demo-importer/demo-files/' );
	}

	public function import_xml( $filename ) {

		$filename = $this->get_full_path( $filename );

		$importer = new Wheels_XML_Importer();
		$importer->set_filename( $filename );
		$importer->set_textdomain( $this->textdomain );
		$importer->import();
	}

	public function import_theme_options( $filename ) {

		$filename = $this->get_full_path( $filename );

		$importer = new Wheels_Theme_Options_Importer();
		$importer->set_filename( $filename );
		$importer->set_textdomain( $this->textdomain );
		$importer->import();
	}

	public function import_layer_slider( $filename ) {

		$filename = $this->get_full_path( $filename );

		$importer = new Wheels_Layer_Slider_Importer();
		$importer->set_filename( $filename );
		$importer->set_textdomain( $this->textdomain );
		$importer->import();
	}

	public function import_widgets( $filename, $delete_current_widgets ) {

		$filename = $this->get_full_path( $filename );

		$importer = new Wheels_Widgets_Importer();
		$importer->set_filename( $filename );
		$importer->set_textdomain( $this->textdomain );
		$importer->set_delete_current_widgets( $delete_current_widgets );
		$importer->import();
	}

	public function import_menus( $menus = array() ) {

		$importer = new Wheels_Menu_Importer();
		$importer->set_menus( $menus );
		$importer->set_textdomain( $this->textdomain );
		$importer->import();
	}

	public function get_full_path( $filename ) {
		return $this->get_demo_files_path() . $filename;
	}

	public function get_demo_files_path() {
		return $this->demo_files_path;
	}

	public function set_demo_files_path( $path ) {
		$this->demo_files_path = $path;
	}

	public function get_textdomain() {
		return $this->textdomain;
	}

	public function set_textdomain( $textdomain ) {
		$this->textdomain = $textdomain;
	}

}
