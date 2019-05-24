<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Google_Calendar {

	protected $shortcode_name = 'scp_google_calendar';

	function __construct() {
		add_action( 'admin_init', array( $this, 'integrateWithVC' ) );
		add_action( 'wp', array( $this, 'check_shortcodes' ) );

		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );
	}

	public function check_shortcodes() {

		if ( ! is_admin() ) {
			global $post;

			if ( $post && strpos( $post->post_content, $this->shortcode_name ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
			}
		}
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			"name"        => esc_html__( 'Google Calendar', 'superwise-plugin' ),
			"description" => esc_html__( 'Display Google Calendar', 'superwise-plugin' ),
			"base"        => $this->shortcode_name,
			"class"       => "",
			"controls"    => "full",
			"icon"        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
			"category"    => 'Aislin',
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			"params"      => array(
				array(
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => __( 'Calendar ID', 'superwise-plugin' ),
					'param_name'  => 'calendar_id',
					'value'       => '',
					'description' => __( 'Add public Google Calendar ID. Make sure to set Google Calendar API key in Theme Options/Misc', 'superwise-plugin' )
				)
				
			)
		) );
	}

	public function render( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'calendar_id' => '',
			'css'         => ''
		), $atts ) );

		// $calendar_id = 'classroom104072597717192336518@group.calendar.google.com';
		if (!$calendar_id) {
			return;
		}

		$api_key = get_option('ac_calendar_api_key');
		if (!$api_key) {
			return;
		}

		$uid = uniqid('google-calendar-');

		$container_class = 'scp-google-classroom-list';
		$container_class .= vc_shortcode_custom_css_class( $css, ' ' );
		$container_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $container_class, $this->shortcode_name, $atts );

		$inline_js = "
			jQuery(document).ready(function($) {
				    $('#{$uid}').fullCalendar({
				    	header: {
							left: 'prev,next today',
							center: 'title',
							right: 'month,agendaWeek,agendaDay,listWeek'
						},
				        googleCalendarApiKey: '{$api_key}',
				        events: {
				            className: 'gcal-event',
				            googleCalendarId: '{$calendar_id}
				        },
				        timezone: 'local'
				    });
				});
		";

		wp_add_inline_script( 'fullcalendar', $inline_js );

		return "<div id=\"{$uid}\" class=\"google-calendar\"></div>";
	}

	public function loadCssAndJs() {
		wp_enqueue_style( 'fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.css' );
		wp_enqueue_style( 'fullcalendar.print', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.print.css', array(), false, 'print' );

		// If you need any javascript files on front end, here is how you can load them.
		wp_enqueue_script( 'moment.js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js', array('jquery'), false, true );
		wp_enqueue_script( 'fullcalendar', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/fullcalendar.min.js', array('jquery'), false, true  );
		wp_enqueue_script( 'gcal', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.5.1/gcal.min.js', array('jquery'), false, true  );
	}

}

new SCP_Google_Calendar();
