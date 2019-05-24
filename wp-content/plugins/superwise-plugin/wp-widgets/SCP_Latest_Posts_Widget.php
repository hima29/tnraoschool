<?php

class SCP_Latest_Posts_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget-latest-posts',
			'description' => esc_html__( 'Latest Post from News category.( for footer section)', 'superwise-plugin' ),
		);

		parent::__construct( 'scp_latest_posts', SCP_PLUGIN_NAME . ' - Latest Posts Widget', $widget_ops );
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
			'title'           => esc_html__( 'Latest Posts', 'superwise-plugin' ),
			'current_cat'     => null,
			'show_image'      => '0',
			'show_excerpt'    => '0',
			'number_of_words' => 10,
			'number_of_posts' => 2,
			'date_format'     => 'j M, Y',
			'cat_link_text'   => 'View All',
		);

		$instance = wp_parse_args( (array) $instance, $default );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title', 'superwise-plugin' ); ?></label><br/>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>
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
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Show image?', 'superwise-plugin' ); ?></label><br/>
			<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>">
				<option value="0" <?php echo $instance['show_image'] == '0' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'No', 'superwise-plugin' ); ?></option>
				<option value="1" <?php echo $instance['show_image'] == '1' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Yes', 'superwise-plugin' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_html_e( 'Show excerpt?', 'superwise-plugin' ); ?></label><br/>
			<select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>">
				<option value="0" <?php echo $instance['show_excerpt'] == '0' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'No', 'superwise-plugin' ); ?></option>
				<option value="1" <?php echo $instance['show_excerpt'] == '1' ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Yes', 'superwise-plugin' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_words' ) ); ?>"><?php esc_html_e( 'Number of words', 'superwise-plugin' ); ?></label><br/>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'number_of_words' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'number_of_words' ) ); ?>" value="<?php echo esc_attr( $instance['number_of_words'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>"><?php esc_html_e( 'Number of posts', 'superwise-plugin' ); ?></label><br/>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'number_of_posts' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>" value="<?php echo esc_attr( $instance['number_of_posts'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'date_format' ) ); ?>"><?php esc_html_e( 'Date format', 'superwise-plugin' ); ?></label><br/>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'date_format' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'date_format' ) ); ?>" value="<?php echo esc_attr( $instance['date_format'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cat_link_text' ) ); ?>"><?php esc_html_e( 'Category link button text', 'superwise-plugin' ); ?></label><br/>
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

		if ( $instance['current_cat'] == '-1' ) {
			$instance['current_cat'] = null;
		}
		//Get leatest posts from upcoming Events Category
		$args = array(
			'numberposts'      => $instance['number_of_posts'],
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'suppress_filters' => false
		);

		if ($instance['current_cat']) {
			$args['category'] = $instance['current_cat'];
		}

		$posts = get_posts( $args );
		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );
		$number_of_words = (int) $instance['number_of_words'];

		$css_class = $instance['show_image'] ? 'show-image' : '';
		echo $before_widget;
		?>
		<?php if ( $title ): ?>
			<?php echo $before_title . $title . $after_title; ?>
		<?php endif; ?>
		<?php foreach ( $posts as $post ): ?>

			<div class="widget-post-list-item <?php echo $css_class; ?>">
				<?php if ( $instance['show_image'] ): ?>

					<div class="thumbnail">
						<?php
						$img_url = '';
						if ( has_post_thumbnail( $post->ID ) ) {
							$img_url = get_the_post_thumbnail( $post->ID, 'thumbnail' );
						}
						if ( '' != $img_url ) {
							echo '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( get_post_field( 'post_title', $post->ID ) ) . '">' . $img_url . '</a>';
						}
						?>
					</div>
				<?php endif; ?>
				<div class="meta-data">
                    <span class="date">
                        <?php echo date( $instance['date_format'], strtotime( $post->post_date ) ); ?>
                    </span>
					
				</div>
				<div class="title">
					<a title="<?php echo esc_attr( $post->post_title ); ?>" href="<?php echo get_permalink( $post->ID ); ?>"><?php echo esc_html( $post->post_title ); ?></a>
				</div>
				<?php if ( $instance['show_excerpt'] ): ?>
					<div class="summary"><?php echo wp_trim_words( strip_shortcodes( get_the_excerpt() ), $number_of_words ); ?></div>
				<?php endif; ?>
				<div class="meta-data">				
                    <span class="author">
                        <i class="fa fa-user"></i> <?php esc_html_e( 'by', 'superwise-plugin' ); ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
		                    <?php the_author_meta( 'display_name', $post->post_author ); ?>
	                    </a>
                    </span>
					<span class="comments-count">
						<i class="fa fa-comment"></i>&nbsp;<a href="<?php echo get_comments_link( $post->ID ); ?>"><?php echo get_comments_number( $post->ID ); ?></a>
					</span>
					
				</div>

			</div>
		<?php endforeach; ?>
		<?php if ( ! empty( $instance['cat_link_text'] ) ): ?>
			<?php $category_link = get_category_link( $instance['current_cat'] ); ?>
			<a class="view-all" href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( $instance['cat_link_text'] ); ?></a>
		<?php endif; ?>
		<?php
		echo $after_widget;
	}

}

register_widget( 'SCP_Latest_Posts_Widget' );
