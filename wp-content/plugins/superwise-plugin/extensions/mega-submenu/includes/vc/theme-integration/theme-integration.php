<?php

$integrations = array(
	'betheme',
	'dt-the7',
	'jupiter',
	'Newspaper',
	'savoy',
	'salient',
	'thefox',
	'Total',
	'Zephyr',
);


class MSM_Theme_Integration {

	protected static $template;
	protected static $integrations = array();
	protected static $is_supported = null;

	public static function init( $integrations ) {

		self::$template = get_option( 'template' );
		self::$integrations = $integrations;
	}

	public static function is_supported() {

		if (is_null(self::$is_supported)) {

			if (in_array( self::$template, self::$integrations )) {
				self::$is_supported = true;
			}
		}
		return self::$is_supported;

	}

	public static function integrate() {

		if ( self::is_supported() ) {
			$filepath = plugin_dir_path( dirname( __FILE__ ) ) . '/theme-integration/' . self::$template . '/integration.php';
			if ( file_exists( $filepath ) ) {
				require_once $filepath;
			}
		}
	}

	public static function get_template() {
		return self::$template;
	}
}

MSM_Theme_Integration::init( $integrations );
MSM_Theme_Integration::integrate();




// var_dump($template);




