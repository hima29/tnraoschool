<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Events {

	protected $namespace = 'scp_events';

	function __construct() {
		add_action( 'init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->namespace, array( $this, 'render' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		$events_categories = get_categories( array(
			'taxonomy' => 'tribe_events_cat',
		) );

		$category_arr                    = array();
		$category_arr['Select Category'] = '';
		foreach ( $events_categories as $category ) {
			if ( is_object( $category ) && $category->term_id ) {
				$category_arr[ $category->name ] = $category->term_id;
			}
		}

		vc_map( array(
			'name'        => esc_html__( 'Tribe Events', 'superwise-plugin' ),
			'description' => '', 
			'base'        => $this->namespace,
			'class'       => '',
			'controls'    => 'full',
			'icon'        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ), // or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'
			'category'    => 'Aislin',
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'params'      => array(
				array(
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Widget Title', 'superwise-plugin' ),
					'param_name'  => 'title',
					'admin_label' => true,
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'admin_label' => true,
					'heading'     => esc_html__( 'Category', 'superwise-plugin' ),
					'param_name'  => 'category_id',
					'value'       => $category_arr,
				),
				array(
					'type'        => 'textfield',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Start Date Format', 'superwise-plugin' ),
					'param_name'  => 'start_date_format',
					'admin_label' => true,
					'value'       => 'M d, Y',
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Nubmer of events to display', 'superwise-plugin' ),
					'param_name'  => 'limit',
					'description' => esc_html__( 'Enter number only.', 'superwise-plugin' ),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Layout', 'superwise-plugin' ),
					'param_name' => 'layout',
					'value'      => array(
						'Layout 1' => 'layout_1',
						'Layout 2' => 'layout_2',
						'Layout 3' => 'layout_3',
						'Layout 4' => 'layout_4',
					),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Show Description', 'superwise-plugin' ),
					'param_name' => 'show_description',
					'value'      => array(
						'No'  => '0',
						'Yes' => '1',
					),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Event description word length', 'superwise-plugin' ),
					'param_name'  => 'description_word_length',
					'description' => esc_html__( 'Enter number only.', 'superwise-plugin' ),
					'dependency'  => Array( 'element' => 'show_description', 'value' => array( '1' ) ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'View All Events Link Text', 'superwise-plugin' ),
					'param_name'  => 'view_all_events_link_text',
					'description' => esc_html__( 'If Left Blank link will not show.', 'superwise-plugin' ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', 'superwise-plugin' ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'superwise-plugin' ),
				),

			)
		) );
	}

	
	public function render( $atts, $content = null ) {

		$main_heading_style_inline = $sub_heading_style_inline = $date_style_inline = $date_heading_style_inline = $outer_circle_style = $inner_circle_style = $info_style_inline = '';

		extract( shortcode_atts( array(
			'title'                     => '',
			'category_id'               => null,
			'limit'                     => '3',
			'layout'                    => 'layout_1',
			'description_word_length'   => '20',
			'start_date_format'         => '',
			'show_description'          => '0',
			'view_all_events_link_text' => '',
			'el_class'                  => '',
		), $atts ) );

		// $content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content

		ob_start();

		// Temporarily unset the tribe bar params so they don't apply
		$hold_tribe_bar_args = array();
		foreach ( $_REQUEST as $key => $value ) {
			if ( $value && strpos( $key, 'tribe-bar-' ) === 0 ) {
				$hold_tribe_bar_args[ $key ] = $value;
				unset( $_REQUEST[ $key ] );
			}
		}

		if ( ! function_exists( 'tribe_get_events' ) ) {
			return;
		}

		$args = array(
			'eventDisplay'   => 'list',
			'posts_per_page' => $limit
		);

		$posts = tribe_get_events( apply_filters( 'tribe_events_list_widget_query_args',  $args ) );

		// If no posts let's bail
		if ( ! $posts ) {
			return;
		}

		
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'scp-tribe-events-wrap ' . $el_class, $this->namespace, $atts );

		//Check if any posts were found
		if ( $posts ) {
			?>
			<div class="<?php echo esc_attr( $css_class ); ?> <?php echo esc_attr( $layout ); ?>">
				<?php if ( $title ) : ?>
					<h3 class="widget-title">
						<i class="icon-Calendar-New"></i> <?php echo esc_html( $title ); ?>
					</h3>
				<?php endif; ?>
				<ul class="scp-tribe-events">
					<?php
					foreach ( $posts as $post ) :
						setup_postdata( $post );
						?>
						<?php if ( $layout == 'layout_2' || $layout == 'layout_3' ) : ?>
							<?php // they use the same template, only have diff style ?>
							<?php include "templates/layout_2.php"; ?>
						<?php elseif ( $layout == 'layout_4' ): ?>
							<?php include "templates/layout_4.php"; ?>
						<?php else: ?>
							<?php include 'templates/layout_1.php'; ?>
						<?php endif; ?>
					<?php
					endforeach;
					?>

				</ul>
				<?php if ( ! empty( $view_all_events_link_text ) ) : ?>
					<p class="scp-tribe-events-link">
						<a href="<?php echo tribe_get_events_link(); ?>"
						   rel="bookmark"><?php echo esc_html( $view_all_events_link_text ); ?></a>
					</p>
				<?php endif; ?>
			</div>
			<?php
			//No Events were Found
		} else {
			?>
			<p><?php esc_html_e( 'There are no upcoming events at this time.', 'superwise-plugin' ); ?></p>
		<?php
		}

		wp_reset_query();
		return ob_get_clean();

		return $content;
	}

	
	/*
	Show notice if your plugin is activated but Visual Composer is not
	*/
	public function showVcVersionNotice() {
		$plugin_data = get_plugin_data( __FILE__ );
		echo '
        <div class="updated">
          <p>' . sprintf( esc_html__( '<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'superwise-plugin' ), $plugin_data['Name'] ) . '</p>
        </div>';
	}
}

new SCP_Events();