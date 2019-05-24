<?php
/**
 * Teacher Post Type
 *
 * @package   TeacherPostType
 * @author    Devin Price
 * @license   GPL-2.0+
 * @link      http://wptheming.com/teacher-post-type/
 * @copyright 2011-2013 Devin Price
 *
 * @wordpress-plugin
 * Plugin Name: Teacher Post Type
 * Plugin URI:  http://wptheming.com/teacher-post-type/
 * Description: Enables a teacher post type and taxonomies.
 * Version:     0.6.1
 * Author:      Devin Price
 * Author URI:  http://www.wptheming.com/
 * Text Domain: teacherposttype
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

if ( ! class_exists( 'Teacher_Post_Type' ) ) :

class Teacher_Post_Type {

	public $textdomain = SCP_TEXT_DOMAIN;

	public function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_textdomain' ) );

		// add_action( 'add_meta_boxes', array( $this , 'add_teacher_meta_boxes' ) , 10, 2 );

		// Run when the plugin is activated
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );

		// Add the teacher post type and taxonomies
		add_action( 'init', array( $this, 'teacher_init' ) );

		// Thumbnail support for teacher posts
		add_theme_support( 'post-thumbnails', array( 'teacher' ) );

		// Add thumbnails to column view
		add_filter( 'manage_edit-teacher_columns', array( $this, 'add_thumbnail_column'), 10, 1 );
		add_action( 'manage_posts_custom_column', array( $this, 'display_thumbnail' ), 10, 1 );

		// Allow filtering of posts by taxonomy in the admin view
		add_action( 'restrict_manage_posts', array( $this, 'add_taxonomy_filters' ) );

		// Show teacher post counts in the dashboard
		add_action( 'right_now_content_table_end', array( $this, 'add_teacher_counts' ) );

		// Give the teacher menu item a unique icon
//		add_action( 'admin_head', array( $this, 'teacher_icon' ) );

		// Add taxonomy terms as body classes
		add_filter( 'body_class', array( $this, 'add_body_classes' ) );

		add_action( 'save_post',  array( $this, 'save_teacher_meta_box' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_textdomain() {

		$domain = $this->textdomain;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Flushes rewrite rules on plugin activation to ensure teacher posts don't 404.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/flush_rewrite_rules
	 *
	 * @uses Teacher_Post_Type::teacher_init()
	 */
	public function plugin_activation() {
		$this->load_textdomain();
		$this->teacher_init();
		flush_rewrite_rules();
	}

	/**
	 * Initiate registrations of post type and taxonomies.
	 *
	 * @uses Teacher_Post_Type::register_post_type()
	 * @uses Teacher_Post_Type::register_taxonomy_tag()
	 * @uses Teacher_Post_Type::register_taxonomy_category()
	 */
	public function teacher_init() {
		$this->register_post_type();
		// $this->register_taxonomy_category();
		// $this->register_taxonomy_tag();
	}

	/**
	 * Get an array of all taxonomies this plugin handles.
	 *
	 * @return array Taxonomy slugs.
	 */
	protected function get_taxonomies() {
		return array( 'teacher_category', 'teacher_tag' );
	}

	public function add_teacher_meta_boxes ( $post ) {

        if( !current_user_can('manage_options') ){
            return;
        }

        add_meta_box( 'sensei-teacher',  __( 'Link to User' , $this->textdomain ),  array( $this , 'teacher_meta_box_content' ),
            'teacher',
            'side',
            'core'
        );

    }

	public function teacher_meta_box_content ( $post ) {

        // get the current author
        $current_author = $post->post_author;

        //get the users authorised to author courses
        $users = $this->get_teachers_and_authors();

    ?>
        <select name="teacher-user">

            <?php foreach ( $users as $user_id ) { ?>

                    <?php
                        $user = get_user_by('id', $user_id);
                    ?>
                    <option <?php selected(  $current_author , $user_id , true ); ?> value="<?php echo esc_attr( $user_id ); ?>" >
                        <?php echo  $user->display_name; ?>
                    </option>

            <?php }// end for each ?>

        </select>

        <?php

    }

	public function save_teacher_meta_box ( $teacher_id ){

        // check if this is a post from saving the teacher, if not exit early
        if(! isset( $_POST[ 'teacher-user' ] ) || ! isset( $_POST['post_ID'] )  ){
            return;
        }


        //don't fire this hook again
        remove_action('save_post', array( $this, 'save_teacher_meta_box' ) );

        // get the current post object
        $post = get_post( $teacher_id );

        // get the current teacher/author
        $current_author = absint( $post->post_author );
        $new_author = absint( $_POST[ 'teacher-user' ] );

        // do not do any processing if the selected author is the same as the current author
        if( $current_author == $new_author ){
            return;
        }

        // save the course  author
		// so user can edit its own teacher page
        $post_updates = array(
            'ID' => $post->ID ,
            'post_author' => $new_author
        );
        wp_update_post( $post_updates );

    } // end save_teacher_meta_box

	public function get_teachers_and_authors ( ){

        $author_query_args = array(
            'blog_id'      => $GLOBALS['blog_id'],
            'fields'       => 'any',
            'who'          => 'authors'
        );

        $authors = get_users( $author_query_args );

        $teacher_query_args = array(
            'blog_id'      => $GLOBALS['blog_id'],
            'fields'       => 'any',
            'role'         => 'teacher',
        );

        $teachers = get_users( $teacher_query_args );

        return  array_unique( array_merge( $teachers, $authors ) );

    }

	/**
	 * Enable the Teacher custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	protected function register_post_type() {
		$labels = array(
			'name'               => __( 'Teachers', $this->textdomain ),
			'singular_name'      => __( 'Teacher', $this->textdomain ),
			'add_new'            => __( 'Add New Teacher', $this->textdomain ),
			'add_new_item'       => __( 'Add New Teacher', $this->textdomain ),
			'edit_item'          => __( 'Edit Teacher', $this->textdomain ),
			'new_item'           => __( 'Add New Teacher', $this->textdomain ),
			'view_item'          => __( 'View Item', $this->textdomain ),
			'search_items'       => __( 'Search Teacher', $this->textdomain ),
			'not_found'          => __( 'No items found', $this->textdomain ),
			'not_found_in_trash' => __( 'No items found in trash', $this->textdomain ),
		);

		$args = array(
			'labels'          => $labels,
			'public'          => true,
			'supports'        => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				// 'comments',
				'author',
				'custom-fields',
				'revisions',
				'page-attributes',
			),
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => 'teachers' ), // Permalinks format
			'menu_position'   => 5,
			'has_archive'     => true,
            'menu_icon'		  => 'dashicons-admin-users',
			'register_meta_box_cb' => array( $this, 'add_teacher_meta_boxes'),
		);

		$args = apply_filters( 'teacherposttype_args', $args );

		register_post_type( 'teacher', $args );
	}

	/**
	 * Register a taxonomy for Teacher Tags.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomy_tag() {
		$labels = array(
			'name'                       => __( 'Teacher Tags', $this->textdomain ),
			'singular_name'              => __( 'Teacher Tag', $this->textdomain ),
			'menu_name'                  => __( 'Teacher Tags', $this->textdomain ),
			'edit_item'                  => __( 'Edit Teacher Tag', $this->textdomain ),
			'update_item'                => __( 'Update Teacher Tag', $this->textdomain ),
			'add_new_item'               => __( 'Add New Teacher Tag', $this->textdomain ),
			'new_item_name'              => __( 'New Teacher Tag Name', $this->textdomain ),
			'parent_item'                => __( 'Parent Teacher Tag', $this->textdomain ),
			'parent_item_colon'          => __( 'Parent Teacher Tag:', $this->textdomain ),
			'all_items'                  => __( 'All Teacher Tags', $this->textdomain ),
			'search_items'               => __( 'Search Teacher Tags', $this->textdomain ),
			'popular_items'              => __( 'Popular Teacher Tags', $this->textdomain ),
			'separate_items_with_commas' => __( 'Separate teacher tags with commas', $this->textdomain ),
			'add_or_remove_items'        => __( 'Add or remove teacher tags', $this->textdomain ),
			'choose_from_most_used'      => __( 'Choose from the most used teacher tags', $this->textdomain ),
			'not_found'                  => __( 'No teacher tags found.', $this->textdomain ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => false,
			'rewrite'           => array( 'slug' => 'teacher-tag' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		$args = apply_filters( 'teacherposttype_tag_args', $args );

		register_taxonomy( 'teacher_tag', array( 'teacher' ), $args );

	}

	/**
	 * Register a taxonomy for Teacher Categories.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	protected function register_taxonomy_category() {
		$labels = array(
			'name'                       => __( 'Teacher Categories', $this->textdomain ),
			'singular_name'              => __( 'Teacher Category', $this->textdomain ),
			'menu_name'                  => __( 'Teacher Categories', $this->textdomain ),
			'edit_item'                  => __( 'Edit Teacher Category', $this->textdomain ),
			'update_item'                => __( 'Update Teacher Category', $this->textdomain ),
			'add_new_item'               => __( 'Add New Teacher Category', $this->textdomain ),
			'new_item_name'              => __( 'New Teacher Category Name', $this->textdomain ),
			'parent_item'                => __( 'Parent Teacher Category', $this->textdomain ),
			'parent_item_colon'          => __( 'Parent Teacher Category:', $this->textdomain ),
			'all_items'                  => __( 'All Teacher Categories', $this->textdomain ),
			'search_items'               => __( 'Search Teacher Categories', $this->textdomain ),
			'popular_items'              => __( 'Popular Teacher Categories', $this->textdomain ),
			'separate_items_with_commas' => __( 'Separate Teacher categories with commas', $this->textdomain ),
			'add_or_remove_items'        => __( 'Add or remove Teacher categories', $this->textdomain ),
			'choose_from_most_used'      => __( 'Choose from the most used Teacher categories', $this->textdomain ),
			'not_found'                  => __( 'No teacher categories found.', $this->textdomain ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'teacher-category' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		$args = apply_filters( 'teacherposttype_category_args', $args );

		register_taxonomy( 'teacher_category', array( 'teacher' ), $args );
	}

	/**
	 * Add taxonomy terms as body classes.
	 *
	 * If the taxonomy doesn't exist (has been unregistered), then get_the_terms() returns WP_Error, which is checked
	 * for before adding classes.
	 *
	 * @param array $classes Existing body classes.
	 *
	 * @return array Amended body classes.
	 */
	public function add_body_classes( $classes ) {

		// Only single posts should have the taxonomy body classes
		if ( is_single() ) {
			$taxonomies = $this->get_taxonomies();
			foreach( $taxonomies as $taxonomy ) {
				$terms = get_the_terms( get_the_ID(), $taxonomy );
				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach( $terms as $term ) {
						$classes[] = sanitize_html_class( str_replace( '_', '-', $taxonomy ) . '-' . $term->slug );
					}
				}
			}
		}

		return $classes;
	}

	/**
	 * Add columns to teacher list screen.
	 *
	 * @link http://wptheming.com/2010/07/column-edit-pages/
	 *
	 * @param array $columns Existing columns.
	 *
	 * @return array Amended columns.
	 */
	public function add_thumbnail_column( $columns ) {
		$column_thumbnail = array( 'thumbnail' => __( 'Thumbnail', $this->textdomain ) );
		return array_slice( $columns, 0, 2, true ) + $column_thumbnail + array_slice( $columns, 1, null, true );
	}

	/**
	 * Custom column callback
	 *
	 * @global stdClass $post Post object.
	 *
	 * @param string $column Column ID.
	 */
	public function display_thumbnail( $column ) {
		global $post;
		switch ( $column ) {
			case 'thumbnail':
				echo get_the_post_thumbnail( $post->ID, array(35, 35) );
				break;
		}
	}

	/**
	 * Add taxonomy filters to the teacher admin page.
	 *
	 * Code artfully lifted from http://pippinsplugins.com/
	 *
	 * @global string $typenow
	 */
	public function add_taxonomy_filters() {
		global $typenow;

		// An array of all the taxonomies you want to display. Use the taxonomy name or slug
		$taxonomies = $this->get_taxonomies();

		// Must set this to the post type you want the filter(s) displayed on
		if ( 'teacher' != $typenow ) {
			return;
		}

		foreach ( $taxonomies as $tax_slug ) {
			$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
			$tax_obj          = get_taxonomy( $tax_slug );
			if ( ! $tax_obj ) {
				return;
			}
			$tax_name         = $tax_obj->labels->name;
			$terms            = get_terms( $tax_slug );
			if ( 0 == count( $terms ) ) {
				return;
			}
			echo '<select name="' . esc_attr( $tax_slug ) . '" id="' . esc_attr( $tax_slug ) . '" class="postform">';
			echo '<option>' . esc_html( $tax_name ) .'</option>';
			foreach ( $terms as $term ) {
				printf(
					'<option value="%s"%s />%s</option>',
					esc_attr( $term->slug ),
					selected( $current_tax_slug, $term->slug ),
					esc_html( $term->name . '(' . $term->count . ')' )
				);
			}
			echo '</select>';
		}
	}

	/**
	 * Add teacher count to "Right Now" dashboard widget.
	 *
	 * @return null Return early if teacher post type does not exist.
	 */
	public function add_teacher_counts() {
		if ( ! post_type_exists( 'teacher' ) ) {
			return;
		}

		$num_posts = wp_count_posts( 'teacher' );

		// Published items
		$href = 'edit.php?post_type=teacher';
		$num  = number_format_i18n( $num_posts->publish );
		$num  = $this->link_if_can_edit_posts( $num, $href );
		$text = _n( 'teacher Item', 'teacher Items', intval( $num_posts->publish ) );
		$text = $this->link_if_can_edit_posts( $text, $href );
		$this->display_dashboard_count( $num, $text );

		if ( 0 == $num_posts->pending ) {
			return;
		}

		// Pending items
		$href = 'edit.php?post_status=pending&amp;post_type=teacher';
		$num  = number_format_i18n( $num_posts->pending );
		$num  = $this->link_if_can_edit_posts( $num, $href );
		$text = _n( 'teacher Item Pending', 'teacher Items Pending', intval( $num_posts->pending ) );
		$text = $this->link_if_can_edit_posts( $text, $href );
		$this->display_dashboard_count( $num, $text );
	}

	/**
	 * Wrap a dashboard number or text value in a link, if the current user can edit posts.
	 *
	 * @param  string $value Value to potentially wrap in a link.
	 * @param  string $href  Link target.
	 *
	 * @return string        Value wrapped in a link if current user can edit posts, or original value otherwise.
	 */
	protected function link_if_can_edit_posts( $value, $href ) {
		if ( current_user_can( 'edit_posts' ) ) {
			return '<a href="' . esc_url( $href ) . '">' . $value . '</a>';
		}
		return $value;
	}

	/**
	 * Display a number and text with table row and cell markup for the dashboard counters.
	 *
	 * @param  string $number Number to display. May be wrapped in a link.
	 * @param  string $label  Text to display. May be wrapped in a link.
	 */
	protected function display_dashboard_count( $number, $label ) {
		?>
		<tr>
			<td class="first b b-teacher"><?php echo esc_html( $number ); ?></td>
			<td class="t teacher"><?php echo esc_html( $label ); ?></td>
		</tr>
		<?php
	}

	/**
	 * Display the custom post type icon in the dashboard.
	 */
	public function teacher_icon() {
		$plugin_dir_url = plugin_dir_url( __FILE__ );
		?>
		<style>
			#menu-posts-teacher .wp-menu-image {
				background: url(<?php echo  esc_attr( $plugin_dir_url ); ?>images/teacher-icon.png) no-repeat 6px 6px !important;
			}
			#menu-posts-teacher:hover .wp-menu-image, #menu-posts-teacher.wp-has-current-submenu .wp-menu-image {
				background-position: 6px -16px !important;
			}
			#icon-edit.icon32-posts-teacher {
				background: url(<?php echo esc_attr( $plugin_dir_url ); ?>images/teacher-32x32.png) no-repeat;
			}
		</style>
		<?php
	}

}

new Teacher_Post_Type;

endif;
