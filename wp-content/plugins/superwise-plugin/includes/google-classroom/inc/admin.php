<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Admin
 */
class AC_Admin
{
	
	public function __construct() {

		add_action( 'admin_menu', array($this, 'add_menu_page') );
	}

	public function add_menu_page() {

		$page_parent = 'edit.php?post_type=' . Aislin_Classroom_Post_Type::POST_TYPE;

		add_submenu_page( $page_parent, 'Classroom Settings', 'Settings', 'manage_options', '_classroom_settings', array($this, 'page') );
	}

	public function page() {

		echo '<h1>Classroom Settings</h1>';

		Aislin_Classroom::init_admin();

		if ( isset( $_POST['ac_auth_revoke'] ) ) {
			if ( check_admin_referer( 'ac-auth-revoke-nonce' ) ) {

				Aislin_Classroom::revoke_token();

			}
		}

		if ( isset( $_POST['ac_auth'] ) ) {
			if ( check_admin_referer( 'ac-auth-nonce' ) ) {

				if (isset($_POST['ac_auth_code']) && $_POST['ac_auth_code']) {
					$auth_code = $_POST['ac_auth_code'];
					Aislin_Classroom::fetch_access_token($auth_code);
				}

			}
		} else {
			$this->get_auth_screen();
		}


		if ( isset( $_POST['ac_settings'] ) ) {
			if ( check_admin_referer( 'ac-settings-nonce' ) ) {

				if (isset($_POST['ac_calendar_api_key']) && $_POST['ac_calendar_api_key']) {
					update_option('ac_calendar_api_key', $_POST['ac_calendar_api_key']);
				}

				if (isset($_POST['ac_organization_name']) && $_POST['ac_organization_name']) {				
					update_option('ac_organization_name', $_POST['ac_organization_name']);
				}

				if (isset($_POST['ac_use_course_structured_data']) && $_POST['ac_use_course_structured_data']) {
					update_option('ac_use_course_structured_data', 1);
				} else {
					delete_option('ac_use_course_structured_data');
				}

			}
		}

		if ( isset( $_POST['ac_import_all'] ) ) {
			if ( check_admin_referer( 'ac-import-all-nonce' ) ) {


				$success = Aislin_Classroom::init();
				if ($success) {

					$emails = array();
					$all_course_ids = array();

					$post_ids = get_posts(array(
						'post_type'   => 'teacher',
						'numberposts' => -1,
						'fields'      => 'ids',
					));

					foreach ($post_ids as $post_id) {
						$teacher_email = get_post_meta($post_id, Aislin_Classroom_Post_Type::META_EMAIL, true);
						if ($teacher_email && !in_array($teacher_email, $emails)) {
							$results = Aislin_Classroom::get_teacher_courses($teacher_email);

							Aislin_Classroom_Post_Type::store($results, $post_id);

							$students = Aislin_Classroom::get_students_batch($results);

							Aislin_Classroom_Post_Type::store_students($students);

							$emails[] = $teacher_email;
						}
					}

					echo '<p>Import complete.</p>';
				} else {
					echo '<p>Import was not performed.</p>';
				}

			}
		}

		$this->get_settings();
		$this->get_import_all_button();

	}

	public function get_import_all_button() {
		?>
		<hr>
		<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>"> 

			<input type="hidden" name="ac_import_all" value="1">
			<?php wp_nonce_field( 'ac-import-all-nonce' ); ?>
			<h3><?php esc_html_e( 'Import all classes from Google Classroom', 'superwise-plugin' ); ?></h3>
			<p><?php esc_html_e('This function will use teacher emails to import the classes. Please make sure that all teachers have email set.'); ?></p>
			<p class="submit">
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Import all', 'superwise-plugin' ); ?></button>
			</p>
		</form>

		<?php
	}

	public function get_settings() {
		?>
		<hr>
		<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>"> 

			<input type="hidden" name="ac_settings" value="1">
			<?php wp_nonce_field( 'ac-settings-nonce' ); ?>

			<table class="form-table">

				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Google Calendar API Key', 'superwise-plugin' ); ?></th>
					<td>
						<fieldset>
							<?php $ac_calendar_api_key = get_option('ac_calendar_api_key'); ?>
							<input type="text" name="ac_calendar_api_key" value="<?php echo esc_attr($ac_calendar_api_key); ?>">
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Use Course structured data?', 'superwise-plugin' ); ?></th>
					<td>
						<fieldset>
							<?php $ac_use_course_structured_data = get_option('ac_use_course_structured_data'); ?>
							<label for="ac_use_course_structured_data_yes">
								<?php esc_html_e('Yes', 'superwise-plugin'); ?>
								<input type="radio" 
										id="ac_use_course_structured_data_yes"
										<?php if ($ac_use_course_structured_data): ?>
											checked
										<?php endif ?>
										name="ac_use_course_structured_data" value="1">
							</label>
							<label for="ac_use_course_structured_data_no">
								<?php esc_html_e('No', 'superwise-plugin'); ?>
								<input type="radio" 
										id="ac_use_course_structured_data_no"
										<?php if (!$ac_use_course_structured_data): ?>
											checked
										<?php endif ?>
										name="ac_use_course_structured_data" value="0">
							</label>
						</fieldset>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Organization name for structured data', 'superwise-plugin' ); ?></th>
					<td>
						<fieldset>
							<?php $ac_organization_name = get_option('ac_organization_name'); ?>
							<input type="text" name="ac_organization_name" value="<?php echo esc_attr($ac_organization_name); ?>">
							<p><em><?php esc_html_e('Organization name must be provided in order to use strucuted data.', 'superwise-plugin'); ?></em></p>

						</fieldset>
					</td>
				</tr>

			</table>
			<p class="submit">
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Submit', 'superwise-plugin' ); ?></button>
			</p>
		</form>

		<?php
	}

	public function get_auth_screen() {

		$auth_url = Aislin_Classroom::get_auth_url();
		$is_validated = Aislin_Classroom::is_validated();
		?>
		<hr>
		<?php if ($is_validated): ?>
			<p class="update-nag"><?php esc_html_e( 'Validated', 'superwise-plugin' ); ?></p>
			<hr>
			<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>"> 

				<input type="hidden" name="ac_auth_revoke" value="1">
				<?php wp_nonce_field( 'ac-auth-revoke-nonce' ); ?>
				<p class="submit">
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Revoke Classroom access token', 'superwise-plugin' ); ?></button>
				</p>
			</form>
			<hr>
		<?php endif; ?>

			<a class="button button-primary" 
				href="<?php echo esc_url( $auth_url ); ?>"
				target="blank"><?php esc_html_e( 'Get auth code', 'superwise-plugin' ); ?></a>

			<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>"> 

				<input type="hidden" name="ac_auth" value="1">
				<?php wp_nonce_field( 'ac-auth-nonce' ); ?>

				<table class="form-table">

					<tr valign="top">
						<th scope="row"><?php esc_html_e( 'Auth code', 'superwise-plugin' ); ?></th>
						<td>
							<fieldset>
								<input type="text" name="ac_auth_code">
							</fieldset>
						</td>
					</tr>

				</table>
				<p class="submit">
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Submit', 'superwise-plugin' ); ?></button>
				</p>
			</form>
		<?php


	}
}

new AC_Admin();