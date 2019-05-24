<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Teachers {

	protected $namespace = 'scp_teachers';

	function __construct() {
		add_action( 'admin_init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->namespace, array( $this, 'render' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		$thumbs_dimensions_array = array_merge( array( 'thumbnail'), scp_get_thumbnail_sizes_vc() , array( 'full-width' ) );

		$teachers       = get_posts( array( 'post_type' => 'teacher', 'numberposts' => - 1, ) );
		$teachers_array = array( 'Select Teacher' => 0 );
		foreach ( $teachers as $teacher ) {
			$teachers_array[ $teacher->post_title ] = $teacher->ID;
		}

		vc_map( array(
			'name'        => esc_html__( 'Teachers', 'superwise-plugin' ),
			'description' => '', 
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
					'heading'    => esc_html__( 'Number of Posts', 'superwise-plugin' ),
					'param_name' => 'number_of_posts',
					'value'      => '2',
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Teachers', 'superwise-plugin' ),
					'param_name'  => 'teacher_id',
					'value'       => $teachers_array,
					'description' => esc_html__( 'If specific teacher is selected, then this widget prints only that teacher.', 'superwise-plugin' ),
				),
				array(
					'type'       => 'dropdown',
					'holder'     => '',
					'class'      => '',
					'heading'    => esc_html__( 'Image Size', 'superwise-plugin' ),
					'param_name' => 'image_size',
					'value'      => $thumbs_dimensions_array,
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Number of Columns', 'superwise-plugin' ),
					'param_name' => 'number_of_columns',
					'value'      => array(
						'1' => '1',
						'2' => '2',
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
				),
				array(
					'type'       => 'dropdown',
					'class'      => '',
					'heading'    => esc_html__( 'Show social icons?', 'superwise-plugin' ),
					'param_name' => 'show_social_icons',
					'value'      => array(
						'No'  => '0',
						'Yes' => '1',

					),
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

		extract( shortcode_atts( array(
			// 'category'                => null,
			'number_of_posts'         => 2,
			// 'cat_link_text'           => '',
			'number_of_columns'       => 1,
			'teacher_id'              => 0,
			'image_size'              => 'thumbnail',
			'show_social_icons'       => '0',
			'el_class'                => '',
		), $atts ) );

		// $content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content

		$args = array(
			'numberposts'      => $number_of_posts,
			// 'category'         => $category,
			'orderby'          => 'menu_order',
			'order'            => 'DESC',
			'suppress_filters' => false,
			'post_type'        => 'teacher'
		);

		$wrapper_class = 'vc_row scp-teachers ';

		if ( $teacher_id ) {
			$args['include'] = $teacher_id;
			$wrapper_class   = 'scp-teachers ';
		}

		$posts = get_posts( $args );


		// If no posts let's bail
		if ( ! $posts ) {
			return;
		}

		$grid = array(
			'one whole',
			'one half',
			'one third',
			'one fourth',
			'one fifth',
			'one sixth',
		);

		$grid_class = $grid[ (int) $number_of_columns - 1 ];


		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $wrapper_class . $el_class, $this->namespace, $atts );

		ob_start();
		?>
		<div class="<?php echo esc_attr( $css_class ); ?>">
			<?php foreach ( $posts as $post ) : ?>
				<div class="teacher <?php echo esc_html( $grid_class ); ?> <?php echo esc_html( $teacher_id ) ? '' : 'wh-padding'; ?>">
					<div class="thumbnail">
						<?php
						$img_url = '';
						if ( has_post_thumbnail( $post->ID ) ) {
							$thumb_atts    = array();
							$hover_img_url = superwise_get_rwmb_meta_image_url( 'teacher_hover_img', $post->ID );
							if ( $hover_img_url ) {
								$thumb_atts['class'] = 'top-img';
							}
							$img_url = get_the_post_thumbnail( $post->ID, $image_size, $thumb_atts );
							if ( $hover_img_url ) {
								$img_url .= "<img class=\"hover-img\" src=\"{$hover_img_url}\" />";
							}
						}
						if ( '' != $img_url ) {
							echo '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( get_post_field( 'post_title', $post->ID ) ) . '">' . $img_url . '</a>';
						}
						?>
					</div>
					<div class="item">
						<h5 class="entry-title"><a
								href="<?php the_permalink( $post ); ?>"><?php echo esc_html( $post->post_title ); ?></a>
						</h5>
						<?php $job_title = superwise_get_rwmb_meta( 'job_title', $post->ID ); ?>
						<?php if ( $job_title ) : ?>
							<h6 class="job-title"><?php echo esc_html( $job_title ); ?></h6>
						<?php endif; ?>
						<?php $summary = superwise_get_rwmb_meta( 'summary', $post->ID ); ?>
						<?php if ( $summary ) : ?>
							<div class="summary"><?php echo do_shortcode( $summary ); ?></div>
						<?php else: ?>
							<div class="summary"><?php echo do_shortcode( get_the_excerpt() ); ?></div>
						<?php endif; ?>
						<?php $social = superwise_get_rwmb_meta( 'social_meta', $post->ID ); ?>
						<?php if ( (int) $show_social_icons && $social ) : ?>
							<div class="social">
								<div class="text"><?php esc_html_e( 'Meet me on:', 'superwise-plugin' ); ?></div>
								<?php echo do_shortcode( $social ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		 return ob_get_clean();	
	}

}

new SCP_Teachers();
