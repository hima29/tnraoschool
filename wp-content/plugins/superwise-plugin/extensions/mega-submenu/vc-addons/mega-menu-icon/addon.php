<?php
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class MSM_Icon {

	protected $name = 'Mega Menu Icon';
	protected $namespace = 'msm_icon';
	protected $textdomain = 'mega-submenu';

	function __construct() {
		// We safely integrate with VC with this hook
		add_action( 'init', array( $this, 'integrateWithVC' ) );

		// Use this when creating a shortcode addon
		add_shortcode( $this->namespace, array( $this, 'render' ) );

		// Register CSS and JS
		add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'loadCssAndJs' ) );

		add_filter( 'vc_iconpicker-type-msm-icons', array( $this, 'theme_icons' ) );
	}

	public function integrateWithVC() {
		// Check if Visual Composer is installed
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			// Display notice that Visual Compser is required
			add_action( 'admin_notices', array( $this, 'showVcVersionNotice' ) );

			return;
		}

		/*
		Add your Visual Composer logic here.
		Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

		More info: http://kb.wpbakery.com/index.php?title=Vc_map
		*/
		vc_map( array(
			'name'             => esc_html( $this->name, $this->textdomain ),
			'description'      => '',
			'base'             => $this->namespace,
			'class'            => '',
			'controls'         => 'full',
			'icon'             => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'
			'category'         => __( 'Mega Menu', $this->textdomain ),
			'admin_enqueue_js' => array( plugins_url( 'assets/admin-theme-icon.js', __FILE__ ) ),
			// This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'params'           => array(
				array(
					'type'        => 'iconpicker',
					'param_name'  => 'theme_icon',
					'heading'     => __( 'Icon', $this->textdomain ),
					'value'       => '', // default value to backend editor admin_label
					'class'       => 'msm-icon-name',
					'holder'      => 'div',
					'settings'    => array(
						'emptyIcon'    => false,
						'type'         => 'msm-icons',
						// default true, display an "EMPTY" icon?
						'iconsPerPage' => 4000,
						// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
					),
					'description' => __( 'Select icon from library.', $this->textdomain ),
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Font Size', $this->textdomain ),
					'param_name'  => 'icon_font_size',
					'description' => __( 'Value in px. Enter number only.', $this->textdomain ),
				),
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Position Absolute?', $this->textdomain ),
					'param_name' => 'position_absolute',
				),
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Icon alignment', 'js_composer' ),
					'param_name'  => 'alignment',
					'value'       => array(
						__( 'Left', 'js_composer' )   => 'left',
						__( 'Right', 'js_composer' )  => 'right',
						__( 'Center', 'js_composer' ) => 'center',
					),
					'description' => __( 'Select alignment.', 'js_composer' ),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => __( 'URL (Link)', 'js_composer' ),
					'param_name'  => 'link',
					'description' => __( 'Add link to icon.', 'js_composer' ),
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => __( 'Icon Color', $this->textdomain ),
					'param_name'  => 'color',
				),
				array(
					'type'        => 'colorpicker',
					'heading'     => __( 'Icon Hover Color', $this->textdomain ),
					'param_name'  => 'hover_color',
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'el_class',
					'heading'     => __( 'Extra class name', $this->textdomain ),
					'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', $this->textdomain ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS box', 'js_composer' ),
					'param_name' => 'css',
					'group'      => __( 'Design Options', 'js_composer' ),
				),
			)
		) );
	}


	/*
	Shortcode logic how it should be rendered
	*/
	public function render( $atts, $content = null ) {

		wp_enqueue_style( 'mammoth-icons' );

		extract( shortcode_atts( array(
			'theme_icon'         => 'Text on the button',
			'icon_font_size'     => '',
			'position_absolute'  => '',
			'link'               => '',
			'alignment'          => 'left',
			'color'              => '',
			'hover_color'        => '',
			'hover_accent_color' => '',
			'css'                => '',
			'el_class'           => '',
		), $atts ) );
		// $content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content

		$class_to_filter = 'msm-icon';
		$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter . ' ' . $el_class, $this->namespace, $atts );

		$link     = vc_build_link( $link );
		$a_href   = $link['url'];
		$a_title  = $link['title'];
		$a_target = $link['target'];

		$icon_style = '';

		if ( $icon_font_size ) {
			$icon_style .= 'font-size:' . (int) $icon_font_size . 'px;';
		}

		if ( $position_absolute == 'true' ) {
			$icon_style .= 'position:absolute;';
		}

		$hover = '';

		if ( $hover_color && $color ) {

			$hover = 'onMouseOver="this.style.color=\'' . $hover_color . '\'"';
			$hover .= ' onMouseOut="this.style.color=\'' . $color . '\'"';

		}

		if ( $color ) {
			$icon_style .= 'color:' . $color . '!important;';
		}

		if ( $alignment ) {
			if ( $alignment ) {
				$icon_style .= 'text-align:' . $alignment . ';';
			}
		}

		if ( $icon_style ) {
			$icon_style = 'style="' . $icon_style . '"';
		}

		ob_start();
		?>

		<?php if ( $a_href ) : ?>
			<a
				href="<?php echo esc_attr( $a_href ); ?>"
				class="<?php echo esc_attr( trim( $css_class ) ); ?>"
				<?php if ( $a_title ) : ?>
					title="<?php echo esc_attr( $a_title ); ?>"
				<?php endif; ?>
				<?php if ( $a_target ) : ?>
					target="<?php echo esc_attr( $a_target ); ?>"
				<?php endif; ?>
				<?php echo $icon_style; ?>
				><i class="<?php echo $theme_icon; ?>" <?php echo $hover; ?>></i></a>
		<?php else: ?>
			<div class="<?php echo esc_attr( $css_class ); ?>" <?php echo $icon_style; ?>>
				<i class="<?php echo $theme_icon; ?>" <?php echo $hover; ?>></i>
			</div>
		<?php endif; ?>

		<?php
		$content = ob_get_clean();

		return $content;
	}

	/*
	Load plugin css and javascript files which you may need on front end of your site
	*/
	public function loadCssAndJs() {
		wp_register_style( 'mammoth-icons', plugins_url( 'assets/mammoth-icons/style.css', __FILE__ ), false );

		if (is_admin()) {
			wp_enqueue_style( 'mammoth-icons' );
		}
	}

	/*
	Show notice if your plugin is activated but Visual Composer is not
	*/
	public function showVcVersionNotice() {
		$plugin_data = get_plugin_data( __FILE__ );
		echo '
        <div class="updated">
          <p>' . sprintf( __( '<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', $this->textdomain ), $plugin_data['Name'] ) . '</p>
        </div>';
	}

	function theme_icons( $icons ) {

		$mammoth_icons = array(
			array('icon-cursor3' => 'icon-cursor3'),
			array('icon-download2' => 'icon-download2'),
			array('icon-favorite2' => 'icon-favorite2'),
			array('icon-books' => 'icon-books'),
			array('icon-browser' => 'icon-browser'),
			array('icon-chat3' => 'icon-chat3'),
			array('icon-chat-1' => 'icon-chat-1'),
			array('icon-chat-2' => 'icon-chat-2'),
			array('icon-chat-3' => 'icon-chat-3'),
			array('icon-chat-4' => 'icon-chat-4'),
			array('icon-email-1' => 'icon-email-1'),
			array('icon-email-3' => 'icon-email-3'),
			array('icon-ereader' => 'icon-ereader'),
			array('icon-laptop4' => 'icon-laptop4'),
			array('icon-magazine' => 'icon-magazine'),
			array('icon-monitor' => 'icon-monitor'),
			array('icon-morse-code' => 'icon-morse-code'),
			array('icon-newspaper' => 'icon-newspaper'),
			array('icon-speech-bubble2' => 'icon-speech-bubble2'),
			array('icon-television' => 'icon-television'),
			array('icon-twitter' => 'icon-twitter'),
			array('icon-video-call' => 'icon-video-call'),
			array('icon-analytics' => 'icon-analytics'),
			array('icon-audio' => 'icon-audio'),
			array('icon-blogging' => 'icon-blogging'),
			array('icon-browser6' => 'icon-browser6'),
			array('icon-browser-13' => 'icon-browser-13'),
			array('icon-browser-23' => 'icon-browser-23'),
			array('icon-browser-3' => 'icon-browser-3'),
			array('icon-browser-4' => 'icon-browser-4'),
			array('icon-cloud-computing3' => 'icon-cloud-computing3'),
			array('icon-coding3' => 'icon-coding3'),
			array('icon-customer' => 'icon-customer'),
			array('icon-design' => 'icon-design'),
			array('icon-devices2' => 'icon-devices2'),
			array('icon-folder4' => 'icon-folder4'),
			array('icon-folder-1' => 'icon-folder-1'),
			array('icon-idea2' => 'icon-idea2'),
			array('icon-image3' => 'icon-image3'),
			array('icon-keywords' => 'icon-keywords'),
			array('icon-loupe' => 'icon-loupe'),
			array('icon-monitor4' => 'icon-monitor4'),
			array('icon-monitor-1' => 'icon-monitor-1'),
			array('icon-newspaper2' => 'icon-newspaper2'),
			array('icon-online-shop' => 'icon-online-shop'),
			array('icon-quality' => 'icon-quality'),
			array('icon-ranking' => 'icon-ranking'),
			array('icon-search-engine' => 'icon-search-engine'),
			array('icon-sitemap' => 'icon-sitemap'),
			array('icon-speedometer3' => 'icon-speedometer3'),
			array('icon-check' => 'icon-check'),
			array('icon-circle-check' => 'icon-circle-check'),
			array('icon-infinity' => 'icon-infinity'),
			array('icon-task2' => 'icon-task2'),
			array('icon-thumb-up' => 'icon-thumb-up'),
			array('icon-eye2' => 'icon-eye2'),
			array('icon-paper-clip' => 'icon-paper-clip'),
			array('icon-mail3' => 'icon-mail3'),
			array('icon-layout3' => 'icon-layout3'),
			array('icon-bell3' => 'icon-bell3'),
			array('icon-clock4' => 'icon-clock4'),
			array('icon-camera' => 'icon-camera'),
			array('icon-monitor6' => 'icon-monitor6'),
			array('icon-cog2' => 'icon-cog2'),
			array('icon-heart3' => 'icon-heart3'),
			array('icon-circle-plus' => 'icon-circle-plus'),
			array('icon-circle-minus' => 'icon-circle-minus'),
			array('icon-circle-check2' => 'icon-circle-check2'),
			array('icon-circle-cross' => 'icon-circle-cross'),
			array('icon-square-plus' => 'icon-square-plus'),
			array('icon-square-minus' => 'icon-square-minus'),
			array('icon-square-check' => 'icon-square-check'),
			array('icon-square-cross' => 'icon-square-cross'),
			array('icon-upload4' => 'icon-upload4'),
			array('icon-download3' => 'icon-download3'),
			array('icon-box3' => 'icon-box3'),
			array('icon-marquee' => 'icon-marquee'),
			array('icon-marquee-plus' => 'icon-marquee-plus'),
			array('icon-marquee-minus' => 'icon-marquee-minus'),

		);

		return array_merge( $icons, $mammoth_icons );
	}
}

// Finally initialize code
new MSM_Icon();
