<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class SCP_Classroom_Class_List {

	protected $shortcode_name = 'scp_class_list';

	function __construct() {
		add_action( 'admin_init', array( $this, 'integrateWithVC' ) );
		add_shortcode( $this->shortcode_name, array( $this, 'render' ) );
	}

	public function integrateWithVC() {
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		$teachers       = get_posts( array( 'post_type' => 'teacher', 'numberposts' => - 1, ) );
		$teachers_array = array( 'Select Teacher' => 0 );
		foreach ( $teachers as $teacher ) {
			$teachers_array[ $teacher->post_title ] = $teacher->ID;
		}

		$course_categories = get_categories( array(
			'taxonomy' => Aislin_Classroom_Post_Type::TAXONOMY_CATEGORY,
		) );

		$course_category_arr                    = array();
		$course_category_arr['Select Category'] = '';
		foreach ( $course_categories as $course_category ) {
			if ( is_object( $course_category ) && $course_category->term_id ) {
				$course_category_arr[ $course_category->name ] = $course_category->term_id;
			}
		}

		vc_map( array(
			"name"        => esc_html__( 'Class List', 'superwise-plugin' ),
			"description" => esc_html__( 'Show a list of Google Classroom Classes', 'superwise-plugin' ),
			"base"        => $this->shortcode_name,
			"class"       => "",
			"controls"    => "full",
			"icon"        => plugins_url( 'assets/aislin-vc-icon.png', __FILE__ ),
			// or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
			"category"    => esc_html__( 'Aislin', 'js_composer' ),
			//'admin_enqueue_js' => array(plugins_url('assets/vc_extend.js', __FILE__)), // This will load js file in the VC backend editor
			//'admin_enqueue_css' => array(plugins_url('assets/vc_extend_admin.css', __FILE__)), // This will load css file in the VC backend editor
			"params"      => array(
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'heading'     => esc_html__( 'Teacher', 'superwise-plugin' ),
					'param_name'  => 'teacher_id',
					'value'       => $teachers_array,
				),
				array(
					'type'        => 'dropdown',
					'holder'      => '',
					'class'       => '',
					'admin_label' => true,
					'heading'     => esc_html__( 'Category', 'superwise-plugin' ),
					'param_name'  => 'category_id',
					'value'       => $course_category_arr,
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Number of items', 'superwise-plugin' ),
					'param_name' => 'number_of_items',
					'value'      => '5',
					'description' => esc_html__( 'To show all set a big number.', 'superwise-plugin' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS box', 'js_composer' ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', 'js_composer' ),
				),
			)
		) );
	}

	public function render( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'teacher_id'      => 0,
			'number_of_items' => 5,
			'category_id'     => null,
			'css'        => ''
		), $atts ) );

		if ( ! $teacher_id ) {
			return;
		}

		$taxonomy_name = Aislin_Classroom_Post_Type::TAXONOMY_CATEGORY;

		$args = array(
			'numberposts'      => $number_of_items,
			// 'category'         => $category,
			'order'            => 'DESC',
			'post_type'        => Aislin_Classroom_Post_Type::POST_TYPE,
		);

		if ( $category_id ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy_name,
					'field'    => 'term_id',
					'terms'    => $category_id
				)
			);
		}

		$args['meta_key']   = Aislin_Classroom_Post_Type::META_TEACHER_IDS;
		$args['meta_query'] = array(
			array(
				'key'     => Aislin_Classroom_Post_Type::META_TEACHER_IDS,
				// https://wordpress.stackexchange.com/questions/55354/how-can-i-create-a-meta-query-with-an-array-as-meta-field
				'value'   => serialize( strval( $teacher_id ) ),
				'compare' => 'LIKE',
			),
		);

		$courses = get_posts( $args );

		if ( empty( $courses ) ) {
			return;
		}

		$container_class = 'class-list';
		$container_class .= vc_shortcode_custom_css_class( $css, ' ' );
		$container_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $container_class, $this->shortcode_name, $atts );

		ob_start();
		?>

			<ul class="<?php echo esc_attr( $container_class ); ?>">
				<?php foreach ($courses as $post): ?>
					<?php 
						$course = get_course($post, false); 
						$room   = $course->get_room();
					?>
					<li>
					<div class="top">
						
						<h3><a href="<?php echo get_permalink( $post ); ?>"><?php echo esc_html( $course->get_title() ); ?></a></h3>
						<div class="data">
							<span class="date"><?php esc_html_e( 'Date', 'superwise-plugin' ); ?>: <em><?php echo date_i18n( get_option( 'date_format' ), strtotime( $course->get_created_at() ) ); ?></em></span>
							<?php if ( $room ): ?>
								<span class="place"><?php esc_html_e( 'Room', 'superwise-plugin' ); ?>: <em><?php echo esc_html( $room ); ?></em></span>
							<?php endif ?>
						</div>
					</div>
						<hr>
					<div class="bottom">
						<h4><?php echo esc_html( $course->get_section() ); ?></h4>
						<div class="links">
							<?php if ( is_a( $course, 'Aislin_Classroom_Course' ) ) : ?>
								<a class="classroom-link" 
									href="<?php echo esc_attr( $course->get_link() ); ?>" 
									title="<?php echo esc_attr( $course->get_title() ); ?>" 
									target="_blank">
										<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
											 viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
										<style type="text/css">
											.st0{fill:#DFDFDE;}
										</style>
										<path d="M0,0c21.3,0,42.7,0,64,0c0,21.3,0,42.7,0,64c-21.3,0-42.7,0-64,0C0,42.7,0,21.3,0,0z M58.4,58.5
											c0-17.8,0-35.4,0-52.9c-17.8,0-35.4,0-52.9,0c0,17.8,0,35.3,0,52.9c10.6,0,21.1,0,31.7,0c0.1-1.3,0.2-2.5,0.4-3.6
											c5.4,0,10.6,0,15.7,0c0.1,1.3,0.2,2.4,0.3,3.6C55.2,58.5,56.7,58.5,58.4,58.5z"/>
										<circle class="st0" cx="19.5" cy="29.6" r="2.9"/>
										<path class="st0" d="M19.5,33.3c-5.8-0.2-7.3,3.6-7.3,3.6v3h7.2h0.2h7.2v-3C26.8,36.9,25.3,33.1,19.5,33.3z"/>
										<circle class="st0" cx="44.7" cy="29.6" r="2.9"/>
										<path class="st0" d="M44.7,33.3c-5.8-0.2-7.3,3.6-7.3,3.6v3h7.2h0.2H52v-3C52,36.9,50.5,33.1,44.7,33.3z"/>
										<circle cx="32" cy="25.3" r="4.1"/>
										<path d="M42.3,39.9v-4.2c0-0.1,0-0.2-0.1-0.2c-0.1-0.2-2.9-5.3-10.3-5.1l0,0c-8,0-10.2,4.9-10.3,5.1
											c0,0.1,0,0.1,0,0.2v4.2"/>
										</svg>
								</a>
							<?php endif; ?>
							<a class="course-link-btn" 
								href="<?php echo get_permalink( $post->ID ); ?>" 
								title="<?php echo esc_attr( $course->get_title() ); ?>">
								<?php esc_html_e( 'See details', 'superwise-plugin' ); ?></a>
						</div>
					</div>
					</li>
				<?php endforeach ?>
			</ul>
		<?php
		return ob_get_clean();
	}

}

new SCP_Classroom_Class_List();
