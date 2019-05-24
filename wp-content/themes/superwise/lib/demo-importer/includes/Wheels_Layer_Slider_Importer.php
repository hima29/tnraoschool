<?php

class Wheels_Layer_Slider_Importer implements Wheels_Importer_Interface {

	protected $textdomain = 'wheels';
	protected $demo_files_path;
	protected $filename;

	public function import() {

		if ( defined('LS_ROOT_PATH') && file_exists( LS_ROOT_PATH . '/classes/class.ls.importutil.php' ) ) {
			include_once LS_ROOT_PATH . '/classes/class.ls.importutil.php';

			if ( file_exists( $this->get_filename() ) ) {
				$import = new LS_ImportUtil( $this->get_filename() );
			}
		}

	}

	public function get_textdomain() {
		return $this->textdomain;
	}

	public function set_textdomain( $textdomain ) {
		$this->textdomain = $textdomain;
	}

	public function get_filename() {
		return $this->content_filename;
	}

	public function set_filename( $filename ) {
		$this->content_filename = $filename;
	}

	public function get_demo_files_path() {
		return $this->demo_files_path;
	}

	public function set_demo_files_path( $path ) {
		$this->demo_files_path = $path;
	}

}
