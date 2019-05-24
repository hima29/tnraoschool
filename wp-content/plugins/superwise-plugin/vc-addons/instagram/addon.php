<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Instagram {

	protected $namespace = 'scp_instagram';

	function __construct() {
		add_action( 'admin_init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->namespace, array( $this, 'render' ) );

	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		vc_map( array(
			'name'        => esc_html__( 'Instagram', 'superwise-plugin' ),
			'description' => esc_html__( '', 'superwise-plugin' ),
			'base'        => $this->namespace,
			'class'       => '',
			'controls'    => 'full',
			'icon'        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'
			'category'    => 'Aislin',
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			'params'      => array(
				array(
					'type'       => 'textfield',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Username', 'superwise-plugin' ),
					'param_name' => 'username',
				),
				array(
					'type'       => 'textfield',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Number of photos', 'superwise-plugin' ),
					'param_name' => 'number_of_photos',
				),
				array(
					'type'       => 'textfield',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Number of columns', 'superwise-plugin' ),
					'param_name' => 'number_of_columns',
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Photo Size', 'superwise-plugin' ),
					'param_name' => 'photo_size',
					'value'      => array(
						'Thumbnail' => 'thumbnail',
						'Small'      => 'small',
						'Large'      => 'large',
					),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Open links in', 'superwise-plugin' ),
					'param_name' => 'target',
					'value'      => array(
						'New Window'     => '_blank',
						'Current Window' => '_self',
					),
				),
				array(
					'type'       => 'textfield',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Link Text', 'superwise-plugin' ),
					'param_name' => 'link_text',
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

		$atts = shortcode_atts( array(
			'username'          => '',
			'number_of_photos'  => '9',
			'number_of_columns' => '3',
			'photo_size'        => 'large',
			'target'            => '_blank',
			'link_text'         => '',
			'el_class'          => '',
		), $atts );

		// $content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content

		ob_start();

		$username = $atts['username'];
		$limit    = $atts['number_of_photos'];
		$size     = $atts['photo_size'];
		$target   = $atts['target'];
		$link     = $atts['link_text'];

		// Taken from WP Instagram Widget
		if ( $username != '' ) {

			$columns = array(
				'1'  => 'one whole',
				'2'  => 'one half',
				'3'  => 'one third',
				'4'  => 'one forth',
				'5'  => 'one fifth',
				'6'  => 'one sixth',
				'7'  => 'one seventh',
				'8'  => 'one eighth',
				'9'  => 'one ninth',
				'10' => 'one tenth',
				'11' => 'one eleventh',
				'12' => 'one twelfth',
			);

			$column_class = isset( $columns[ $atts['number_of_columns'] ] ) ? $columns[ $atts['number_of_columns'] ] : 'one third';


			$media_array = $this->scrape_instagram( $username );

			if ( is_wp_error( $media_array ) ) {

				echo wp_kses_post( $media_array->get_error_message() );

			} else {

				$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'scp-instagram-pics instagram-size-' . $size . $atts['el_class'], $this->namespace, $atts );

				// filter for images only?
//				if ( $images_only = apply_filters( 'wpiw_images_only', false ) ) {
//					$media_array = array_filter( $media_array, array( $this, 'images_only' ) );
//				}

				// slice list down to required limit
				$media_array = array_slice( $media_array, 0, $limit );

				// filters for custom classes
				$liclass       = $column_class . ' two-up-small-tablet two-up-mobile';
				$aclass        = '';
				$imgclass      = '';
				$template_part = apply_filters( 'scp_template_part', 'parts/wp-instagram-widget.php' );

				?>
				<ul class="<?php echo esc_attr( $css_class ); ?>"><?php
				foreach ( $media_array as $item ) {
					// copy the else line into a new file (parts/wp-instagram-widget.php) within your theme and customise accordingly
					if ( locate_template( $template_part ) != '' ) {
						include locate_template( $template_part );
					} else {
						echo '<li class="' . esc_attr( $liclass ) . '"><a href="' . esc_url( $item['link'] ) . '" target="' . esc_attr( $target ) . '"  class="' . esc_attr( $aclass ) . '"><img src="' . esc_url( $item[ $size ] ) . '"  alt="' . esc_attr( $item['description'] ) . '" title="' . esc_attr( $item['description'] ) . '"  class="' . esc_attr( $imgclass ) . '"/></a></li>';
					}
				}
				?></ul><?php
			}
		}

		$linkclass = apply_filters( 'wpiw_link_class', 'clear' );

		if ( $link != '' ) {
			?><p class="<?php echo esc_attr( $linkclass ); ?>"><a
				href="<?php echo trailingslashit( '//instagram.com/' . esc_attr( trim( $username ) ) ); ?>" rel="me"
				target="<?php echo esc_attr( $target ); ?>"><?php echo wp_kses_post( $link ); ?></a></p><?php
		}


		return ob_get_clean();

	}

	// based on https://gist.github.com/cosmocatalano/4544576
	function scrape_instagram( $username ) {

		$username = strtolower( $username );
		$username = str_replace( '@', '', $username );

		$url = 'http://instagram.com/' . trim( $username );

		if ( false === ( $instagram = get_transient( 'instagram-scp-' . sanitize_title_with_dashes( $username ) ) ) ) {

			$remote = wp_remote_get( $url );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'superwise-plugin' ) );
			}

			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'superwise-plugin' ) );
			}

			$shards      = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json  = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'superwise-plugin' ) );
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'superwise-plugin' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'superwise-plugin' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {
				if ( true === $image['node']['is_video'] ) {
					$type = 'video';
				} else {
					$type = 'image';
				}

				$caption = __( 'Instagram Image', 'superwise-plugin' );
				if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
					$caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
				}

				$instagram[] = array(
					'description' => $caption,
					'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
					'time'        => $image['node']['taken_at_timestamp'],
					'comments'    => $image['node']['edge_media_to_comment']['count'],
					'likes'       => $image['node']['edge_liked_by']['count'],
					'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
					'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
					'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
					'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
					'type'        => $type,
				);
			} // End foreach().

			// do not set an empty transient - should help catch private or empty accounts
			if ( ! empty( $instagram ) ) {
				$instagram = base64_encode( serialize( $instagram ) );
				set_transient( 'instagram-scp-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'scp_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
			}
		}

		if ( ! empty( $instagram ) ) {

			return unserialize( base64_decode( $instagram ) );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'superwise-plugin' ) );

		}
	}

	function images_only( $media_item ) {
		if ( $media_item['type'] == 'image' ) {
			return true;
		}
		return false;
	}
}

new SCP_Instagram();