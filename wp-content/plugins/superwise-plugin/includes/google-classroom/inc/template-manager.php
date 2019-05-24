<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class Aislin_Template_Manager {

	protected static $templatePath = '';

	public static function get($template, $data = array()) {

		if (!self::$templatePath) {
			self::$templatePath = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/';
		}
		return self::fetch($template, $data);
	}

	/**
	 * Renders a template and returns the result as a string
	 *
	 * cannot contain template as a key
	 *
	 * throws RuntimeException if $templatePath . $template does not exist
	 *
	 * @param $template
	 * @param array $data
	 *
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException
	 * @throws \RuntimeException
	 */
	public static function fetch($template, array $data = []) {
		if (isset($data['template'])) {
			throw new InvalidArgumentException("Duplicate template key found");
		}



		// any path can be specified
		// $templatePath = KATEDRA_PATH . "{$template}.php";
		$templatePath = apply_filters('aislin_gc_filter_template', self::$templatePath, $template);

		// look in templates folder
		if ( ! is_file( $templatePath ) ) {
			$file = self::$templatePath . $template . '.php';
			if (!is_file($file)) {
				throw new RuntimeException("View cannot render `$template` because the template does not exist");
			}
		}

		ob_start();
		self::protectedIncludeScope($file, $data);
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * @param string $template
	 * @param array $data
	 */
	protected static function protectedIncludeScope ($template, array $data) {
		extract($data);
		include $template;
	}
}