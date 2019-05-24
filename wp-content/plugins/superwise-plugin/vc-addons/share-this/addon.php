<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Share_This {

	protected $namespace = 'scp_share_this';

	function __construct() {
		add_action( 'init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->namespace, array( $this, 'render' ) );

		
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			'name'        => esc_html__( 'Share This', 'superwise-plugin' ),
			'description' => '', 
			'base'        => $this->namespace,
			'class'       => '',
			'controls'    => 'full',
			'icon'        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'
			'category'    => 'Aislin',
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'params'      => array( //
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

		extract( shortcode_atts( array(
			'el_class'                => '',
		), $atts ) );

		// $content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'scp-share-this share-this' . $el_class, $this->namespace, $atts );

		ob_start();
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<!-- http://simplesharingbuttons.com/ -->
			<ul class="share-buttons">
				<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( site_url() ); ?>&t="
				       target="_blank" title="Share on Facebook"
				       onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + encodeURIComponent(document.URL)); return false;"><i
							class="fa fa-facebook"></i></a></li>
				<li>
					<a href="https://twitter.com/intent/tweet?source=<?php echo urlencode( site_url() ); ?>&text=:%20<?php echo urlencode( site_url() ); ?>"
					   target="_blank" title="Tweet"
					   onclick="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + ':%20' + encodeURIComponent(document.URL)); return false;"><i
							class="fa fa-twitter"></i></a></li>
				<li><a href="https://plus.google.com/share?url=<?php echo urlencode( site_url() ); ?>"
				       target="_blank" title="Share on Google+"
				       onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL)); return false;"><i
							class="fa fa-google-plus"></i></a></li>
				<li>
					<a href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode( site_url() ); ?>&description="
					   target="_blank" title="Pin it"
					   onclick="window.open('http://pinterest.com/pin/create/button/?url=' + encodeURIComponent(document.URL) + '&description=' +  encodeURIComponent(document.title)); return false;"><i
							class="fa fa-pinterest"></i></a></li>
				<li>
					<a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( site_url() ); ?>&title=&summary=&source=<?php echo urlencode( site_url() ); ?>"
					   target="_blank" title="Share on LinkedIn"
					   onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) + '&title=' +  encodeURIComponent(document.title)); return false;"><i
							class="fa fa-linkedin"></i></a></li>
			</ul>
		</div>

		<?php
		return ob_get_clean();
	}
}

new SCP_Share_This();
