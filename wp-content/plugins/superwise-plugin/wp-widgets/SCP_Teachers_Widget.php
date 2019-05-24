<?php

class SCP_Teachers_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget-teachers',
			'description' => esc_html__( 'Teachers', 'superwise-plugin' ),
		);

		parent::__construct( 'scp_teachers', SCP_PLUGIN_NAME . ' - Teachers Widget', $widget_ops );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @see WP_Widget::form()
	 *
	 * @param $instance current settings
	 */
	public function form( $instance ) {

		//Get Posts from first category (current one)
		$default = array(
			'title'           => esc_html__( 'Teachers', 'superwise-plugin' ),
			'current_cat'     => null,
			'show_image'      => '1',
			'number_of_posts' => 2,
			'number_of_words' => 10,
			'cat_link_text'   => 'View All',
		);

		$instance = wp_parse_args( (array) $instance, $default );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title', 'superwise-plugin' ); ?></label><br/>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>
		<?php /*
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'current_cat' ) ); ?>"><?php esc_html_e( 'Category', 'superwise-plugin' ); ?></label><br/>
			<?php
			wp_dropdown_categories( array(
				'selected'         => $instance['current_cat'],
				'name'             => $this->get_field_name( 'current_cat' ),
				'id'               => $this->get_field_id( 'current_cat' ),
				'class'            => 'widefat',
				'show_count'       => true,
				'show_option_none' => 'All',
				'hide_empty'       => false,
				'orderby'          => 'name'
			) );
			?>
		</p> */ ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Show Image', 'superwise-plugin' ); ?></label><br/>
			<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" id="<?php echo $this->get_field_id( 'show_image' ); ?>">
				<option value="1" <?php echo $instance['show_image'] == '1' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Yes', 'superwise-plugin' ); ?></option>
				<option value="0" <?php echo $instance['show_image'] == '0' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'No', 'superwise-plugin' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>"><?php esc_html_e( 'Number of Posts', 'superwise-plugin' ); ?></label><br/>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'number_of_posts' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>" value="<?php echo esc_attr( $instance['number_of_posts'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_words' ) ); ?>"><?php esc_html_e( 'Number of Words', 'superwise-plugin' ); ?></label><br/>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'number_of_words' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'number_of_words' ) ); ?>" value="<?php echo esc_attr( $instance['number_of_words'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cat_link_text' ) ); ?>"><?php esc_html_e( 'View All Link Text', 'superwise-plugin' ); ?></label><br/>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'cat_link_text' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'cat_link_text' ) ); ?>" value="<?php echo esc_attr( $instance['cat_link_text'] ); ?>"/>
		</p>
	<?php
	}

	/**
	 * processes widget options to be saved
	 *
	 * @see WP_Widget::update()
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();
		if ( empty( $old_instance ) ) {
			$old_instance = $new_instance;
		}

		if ( $new_instance['num'] > 8 ) {
			$new_instance['num'] = 8;
		}

		foreach ( $old_instance as $k => $value ) {
			$instance[ $k ] = trim( strip_tags( $new_instance[ $k ] ) );
		}

		return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		//Get leatest posts from upcoming Events Category
		$args = array(
			'numberposts'      => $instance['number_of_posts'],
			// 'category'         => $instance['current_cat'],
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'suppress_filters' => false,
			'post_type'        => 'teacher'
		);

		$posts = get_posts( $args );
		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );

		$css_class = $instance['show_image'] ? 'show-image' : '';
		echo $before_widget;
		?>
		<?php if ( $title ): ?>
			<?php echo $before_title . $title . $after_title; ?>
		<?php endif; ?>
		<?php foreach ( $posts as $post ): ?>

			<div class="teacher <?php echo esc_attr( $css_class ); ?>">
				<?php if ( $instance['show_image'] ): ?>

					<div class="thumbnail">
						<?php
						$img_url = '';
						if ( has_post_thumbnail( $post->ID ) ) {
							$img_url = get_the_post_thumbnail( $post->ID, 'full' );
						}
						if ( '' != $img_url ) {
							echo '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( get_post_field( 'post_title', $post->ID ) ) . '">' . $img_url . '</a>';
						}
						?>
					</div>
				<?php endif; ?>
				<h5 class="entry-title">
					<a title="<?php echo esc_attr( $post->post_title ); ?>" href="<?php echo get_permalink( $post->ID ); ?>"><?php echo esc_html( $post->post_title ); ?></a>
				</h5>
				<?php $job_title = wheels_get_rwmb_meta( 'job_title',$post->ID ); ?>
				<?php if ( $job_title ) : ?>
					<div class="job-title"><?php echo esc_html( $job_title ); ?></div>
				<?php endif; ?>
				<div class="item">
					<?php $summary = wheels_get_rwmb_meta( 'summary', $post->ID ); ?>
					<?php if ( $summary ) : ?>
							<div class="summary"><?php echo wp_trim_words( strip_shortcodes( $summary ), $instance[ 'number_of_words' ]); ?></div>
					<?php else: ?>
						<div class="summary"><?php echo wp_trim_words( strip_shortcodes( get_the_excerpt() ) ); ?></div>
					<?php endif; ?>
					<?php $social = wheels_get_rwmb_meta( 'social_meta', $post->ID ); ?>
					<?php if ( $social ) : ?>
						<div class="social"><?php echo do_shortcode( $social ); ?></div>
					<?php endif; ?>
				</div>

			</div>
		<?php endforeach; ?>
		<?php if ( ! empty( $instance['cat_link_text'] ) ): ?>
			<?php $category_link = get_category_link( $instance['current_cat'] ); ?>
			<a class="wh-alt-button" href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( $instance['cat_link_text'] ); ?></a>
		<?php endif; ?>
		<?php
		echo $after_widget;
	}

}

register_widget( 'SCP_Teachers_Widget' );
