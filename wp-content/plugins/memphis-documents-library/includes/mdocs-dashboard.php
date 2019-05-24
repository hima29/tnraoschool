<?php
function mdocs_dashboard_menu() {
	add_menu_page( __('Memphis Documents Library','memphis-documents-library'), __('Memphis Docs','memphis-documents-library'), 'mdocs_allow_upload', 'memphis-documents.php', 'mdocs_dashboard', MDOC_URL.'/assets/imgs/kon.ico'  );
	if(get_option('mdocs-disable-bootstrap-admin')) add_submenu_page( 'memphis-documents.php', __('Settings', 'memphis-documents-library'), __('Settings', 'memphis-documents-library'), 'administrator', 'memphis-documents.php&mdocs-cat=settings', 'mdocs_settings' );
}

function mdocs_dashboard() {
	if(isset($_FILES['mdocs']) && $_FILES['mdocs']['name'] != '' && $_POST['mdocs-type'] == 'mdocs-add') mdocs_file_upload();
	elseif(isset($_FILES['mdocs']) && $_POST['mdocs-type'] == 'mdocs-update') mdocs_file_upload();
	elseif(isset($_GET['action']) && $_GET['action'] == 'delete-doc' && !isset($_POST['mdocs-type'])) mdocs_delete();
	elseif(isset($_GET['action']) && $_GET['action'] == 'delete-version') mdocs_delete_version();
	elseif(isset($_POST['action']) && $_POST['action'] == 'mdocs-import') {
		if(mdocs_file_upload_max_size() < $_FILES['mdocs-import-file']['size']) mdocs_errors(MDOCS_ERROR_7, 'error');
		else mdocs_import_zip();
	} elseif(isset($_POST['action']) && $_POST['action'] == 'mdocs-update-revision') mdocs_update_revision();
	elseif(isset($_GET['action']) && $_GET['action'] == 'mdocs-versions') mdocs_versions();
	elseif(isset($_POST['action']) && $_POST['action'] == 'mdocs-update-cats') mdocs_update_cats();
	mdocs_dashboard_view();
}

function mdocs_dashboard_view() {
	if(isset($_GET['mdocs-cat'])) $current_cat = mdocs_sanitize_string($_GET['mdocs-cat']);
	else $current_cat = null;
	if($current_cat == 'import') mdocs_import($current_cat);
	elseif($current_cat == 'export') mdocs_export($current_cat);
	elseif($current_cat == 'cats' && MDOCS_DEV == false) mdocs_edit_cats($current_cat);
	elseif($current_cat == 'cats' && MDOCS_DEV) mdocs_folder_editor($current_cat);
	elseif($current_cat == 'settings') mdocs_settings();
	elseif($current_cat == 'batch') mdocs_batch_upload($current_cat);
	elseif($current_cat == 'short-codes') mdocs_shortcodes($current_cat);
	elseif($current_cat == 'filesystem-cleanup') mdocs_filesystem_cleanup($current_cat);
	elseif($current_cat == 'restore') mdocs_restore_defaults($current_cat);
	elseif($current_cat == 'allowed-file-types') mdocs_allowed_file_types($current_cat);
	elseif($current_cat == 'find-lost-files') mdocs_find_lost_files($current_cat);
	elseif($current_cat == 'server-compatibility') mdocs_server_compatibility($current_cat);
	else echo mdocs_the_list();
}

function mdocs_delete() {
	if ( $_REQUEST['mdocs-nonce'] == MDOCS_NONCE || get_option('mdocs-disable-sessions') == true) {
		$mdocs = get_option('mdocs-list');
		//$mdocs = mdocs_sort_by($mdocs, 0, 'dashboard', false);
		$mdocs = mdocs_array_sort();
		$index = mdocs_sanitize_string($_GET['mdocs-index']);
		$upload_dir = wp_upload_dir();
		$mdocs_file = $mdocs[$index];
		if(is_array($mdocs[$index]['archived'])) foreach($mdocs[$index]['archived'] as $key => $value) @unlink($upload_dir['basedir'].'/mdocs/'.$value);
		wp_delete_attachment( intval($mdocs_file['id']), true );
		wp_delete_post( intval($mdocs_file['parent']), true );
		if(file_exists($upload_dir['basedir'].'/mdocs/'.$mdocs_file['filename'])) @unlink($upload_dir['basedir'].'/mdocs/'.$mdocs_file['filename']);
		unset($mdocs[$index]);
		$mdocs = array_values($mdocs);
		mdocs_save_list($mdocs);
	} else mdocs_errors(MDOCS_ERROR_4,'error');
}

function mdocs_add_update_ajax($edit_type='Add Document') {
	$cats = get_option('mdocs-cats');
	$mdocs = mdocs_array_sort();
	$mdocs_index = '';
	
	if(isset($_POST['mdocs-id'])) {	
		foreach($mdocs as $index => $the_mdoc) {
			if($_POST['mdocs-id'] == $the_mdoc['id']) {
				$mdocs_index = $index; break;
			}
		}
	}
	
	if(!is_string($mdocs_index) && $edit_type == 'Update Document' || $edit_type == 'Add Document') {
		if(mdocs_check_file_rights($mdocs[$mdocs_index]) || $edit_type == 'Add Document') {
			if($edit_type == 'Update Document') $mdoc_type = 'mdocs-update';
			else $mdoc_type = 'mdocs-add';
			// POST CATEGORIES
			$post_categories = wp_get_post_categories($mdocs[$mdocs_index]['parent']);
			if(count($post_categories) > 0) {
				$mdocs[$mdocs_index]['mdocs-categories'] = array();
				foreach($post_categories as $post_cat) {
					$the_category_name = get_the_category_by_ID($post_cat);
					array_push($mdocs[$mdocs_index]['mdocs-categories'], $the_category_name);
				}
			} else $mdocs[$mdocs_index]['mdocs-categories'] = null;
			// POST TAGS
			$post_tags = wp_get_post_tags($mdocs[$mdocs_index]['parent']);
			foreach($post_tags as $post_tag) $the_tags .= $post_tag->name.', ';
			$the_tags = rtrim($the_tags, ', ');
			$mdocs[$mdocs_index]['post-tags'] = $the_tags;
			$date_format = get_option('mdocs-date-format');
			if($edit_type == 'Update Document') {
				$the_date = mdocs_format_unix_epoch($mdocs[$mdocs_index]['modified']);
				$mdocs[$mdocs_index]['gmdate'] = date($date_format, $the_date['date']);
			} else {
				$the_date = mdocs_format_unix_epoch();
				$mdocs[$mdocs_index]['gmdate'] = date($date_format, $the_date['date']);
			}
			echo json_encode($mdocs[$mdocs_index]);
		} else {
			$error['error'] = __('The permission of this file have changed and you no longer have acces to it, please contact the ower of the file.', 'memphis-documents-library')."\n\r";
			$error['error'] .= __('[ File Owner ]', 'memphis-documents-library').' => '.$mdocs[$mdocs_index]['owner']."\n\r";
			echo json_encode($error);
		}
	} else {
		$error['error'] = __('Index value not found, something has gone wrong.', 'memphis-documents-library')."\n\r";
		$error['error'] .= __('[ Index Value ]', 'memphis-documents-library').' => '.$mdocs_index."\n\r";
		$error['error'] .= __('[ Edit Type ]', 'memphis-documents-library').' => '.$edit_type;
		echo json_encode($error);
	}
}
?>