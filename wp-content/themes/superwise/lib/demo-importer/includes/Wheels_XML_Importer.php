<?php

class Wheels_XML_Importer implements Wheels_Importer_Interface {

	protected $textdomain = 'wheels';
	protected $filename;

	public function import() {

		echo 'importing xml';

		$file = $this->get_filename();

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

		require_once ABSPATH . 'wp-admin/includes/import.php';

		$importer_error = false;

		if ( ! class_exists( 'WP_Importer' ) ) {

			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

			if ( file_exists( $class_wp_importer ) ) {

				require_once( $class_wp_importer );

			} else {

				$importer_error = true;

			}

		}

		if ( ! class_exists( 'WP_Import' ) ) {

			$class_wp_import = get_template_directory() . '/lib/demo-importer/includes/wordpress-importer.php';

			if ( file_exists( $class_wp_import ) ) {
				require_once( $class_wp_import );
			} else {
				$importer_error = true;
			}

		}

		if ( $importer_error ) {

			die( "Error on import" );

		} else {

			if ( ! is_file( $file ) ) {

				echo "The XML file containing the dummy content is not available or could not be read .. You might want to try to set the file permission to chmod 755.<br/>If this doesn't work please use the WordPress importer and import the XML file (should be located in your download .zip: Sample Content folder) manually ";

			} else {
				set_time_limit( 0 );
				$wp_import                    = new WP_Import();
				$wp_import->fetch_attachments = true;
				$wp_import->import( $file );

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

}
