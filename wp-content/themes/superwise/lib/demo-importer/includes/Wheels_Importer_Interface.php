<?php

interface Wheels_Importer_Interface {

	public function import();

	public function get_filename();

	public function set_filename( $filename );

	public function get_textdomain();

	public function set_textdomain( $textdomain );

}