<?php
function mdocs_show_file_info_templates() {
	// TABLE SHOW OPTIONS
	register_setting('mdocs-global-settings', 'mdocs-displayed-file-info');
	add_option('mdocs-displayed-file-info',array(
		'show-description' => array('show' => false, 'slug' => 'desc', 'text' =>  __('Description', 'memphis-documents-library'), 'icon' => '', 'color' => '', 'function' => 'mdocs_display_description'),
		'show-downloads' => array('show' => true, 'slug' => 'downloads', 'text' =>  __('Downloads', 'memphis-documents-library'), 'icon' => 'fa fa-cloud-download', 'color' => 'mdocs-orange', 'function' => 'mdocs_display_downloads'),
		'show-version' => array('show' => true, 'slug' => 'version', 'text' =>  __('Version', 'memphis-documents-library'), 'icon' => 'fa fa-power-off', 'color' => 'mdocs-blue', 'function' => 'mdocs_display_version'),
		'show-author' => array('show' => true, 'slug' => 'owner', 'text' =>  __('Owner', 'memphis-documents-library'), 'icon' => 'fa fa-pencil', 'color' => 'mdocs-green', 'function' => 'mdocs_display_owner'),
		'show-real-author' => array('show' => false, 'slug' => 'real-author', 'text' =>  __('Author', 'memphis-documents-library'), 'icon' => '', 'color' => '', 'function' => 'mdocs_display_real_author'),
		'show-update' => array('show' => true, 'slug' => 'modified', 'text' =>  __('Last Modified', 'memphis-documents-library'), 'icon' => 'fa fa-calendar', 'color' => 'mdocs-red', 'function' => 'mdocs_display_updated'),
		'show-ratings' => array('show' => true, 'slug' => 'rating', 'text' =>  __('Rating', 'memphis-documents-library'), 'icon' => '', 'color' => '', 'function' => 'mdocs_display_rating'),
		'show-download-btn' => array('show' => true, 'slug' => 'download', 'text' =>  __('Download', 'memphis-documents-library'), 'icon' => 'fa fa-download', 'color' => '', 'function' => 'mdocs_display_download_btn'),
		'show-file-size' => array('show' => false, 'slug' => 'file-size', 'text' =>  __('File Size', 'memphis-documents-library'), 'icon' => 'fa fa-database', 'color' => '', 'function' => 'mdocs_display_file_size'),
	));
	// EXAMPLE CUSTOM FILE INFO
	//mdocs_add_file_info(false, 'test-template', 'Test Template', '', '', 'mdocs_display_test_template');
	//mdocs_delete_file_info( 'test-template');
}
function mdocs_add_file_info($show=false, $slug='', $title='', $icon='', $color='', $function='mdocs_dispaly_default') {
	if($function == '') $function = 'mdocs_dispaly_default';
	if($slug != '') {
		$index = md5($slug);
		register_setting('mdocs-file-info', 'mdocs-file-info-'.$index);
		add_option('mdocs-file-info-'.$index,false);
		if(get_option('mdocs-file-info-'.$index) == false && is_array(get_option('mdocs-list'))) {
			$show_options = get_option('mdocs-displayed-file-info');
			$show_options['show-'.$slug] = array('show' => $show, 'slug' => $slug, 'text' =>  __($title, 'memphis-documents-library'), 'icon' => $icon, 'color' => $color, 'function' => $function);
			update_option('mdocs-displayed-file-info', $show_options);
			update_option('mdocs-file-info-'.$index, true);
		}
	}
}
function mdocs_update_file_info($key, $show=false, $slug=null, $title=null, $icon=null, $color=null, $function=null) {
	$file_info = get_option('mdocs-displayed-file-info');
	if(is_bool($show)) $file_info[$key]['show'] = $show;
	if($slug != null) $file_info[$key]['slug'] = $slug;
	if($title != null) $file_info[$key]['title'] = $title;
	if($icon != null) $file_info[$key]['icon'] = $icon;
	if($color != null) $file_info[$key]['color'] = $color;
	if($function != null) $file_info[$key]['function'] = $function;
	update_option('mdocs-displayed-file-info',$file_info);
}
function mdocs_delete_file_info($slug='') {
	if(get_option('mdocs-file-info-'.md5($slug))) {
		$show_options = get_option('mdocs-displayed-file-info');
		unset($show_options['show-'.$slug]);
		update_option('mdocs-displayed-file-info', $show_options);
		delete_option('mdocs-file-info-'.md5($slug));
	}
}
function mdocs_dispaly_default($the_mdoc) {
	_e('No function found.', 'memphis-documents-library');
}
function mdocs_display_downloads($the_mdoc) {
	echo $the_mdoc['downloads'].' <small>'.__('downloads','memphis-documents-library').'</small>';
}
function mdocs_display_version($the_mdoc) {
	echo $the_mdoc['version'];
}
function mdocs_display_owner($the_mdoc) {
	echo get_user_by('login', $the_mdoc['owner'])->display_name;
}
function mdocs_display_updated($the_mdoc) {
	$the_date = mdocs_format_unix_epoch($the_mdoc['modified']);
	if($the_date['gmdate'] > time()) $scheduled = '<small class="text-muted"><em>'.__('Scheduled').'</em></small>';
	else $scheduled = '';
	echo $the_date['formated-date'];
}
function mdocs_display_rating($the_mdoc) {
	$the_rating = mdocs_get_rating($the_mdoc);
	for($i=1;$i<=5;$i++) {
		if($the_rating['average'] >= $i) echo '<i class="fa fa-star mdocs-gold" id="'.$i.'" aria-hidden="true"></i>';
		elseif(ceil($the_rating['average']) == $i ) echo '<i class="fa fa-star-half-full mdocs-gold" id="'.$i.'" aria-hidden="true"></i>';
		else echo '<i class="fa fa-star-o" id="'.$i.'" aria-hidden="true"></i>';
	}
}
function mdocs_display_download_btn($the_mdoc) {
	$show_download = false;
	if($the_mdoc['non_members'] == 'on' ) $show_download = true;
	if(get_option( 'mdocs-hide-all-files-non-members' ) == false && $the_mdoc['non_members'] == '')$show_download = true;
	if($the_mdoc['non_members'] == '' ) $show_download = false;
	if(get_option( 'mdocs-hide-all-files-non-members') == true)  $show_download = false;
	if(is_user_logged_in()) $show_download = true;

	if($show_download) {
		?><a href="<?php echo site_url().'/?mdocs-file='.$the_mdoc['id']; ?>"><?php echo __('Download','memphis-documents-library'); ?></a><?php
	} else {
		?><a href="<?php echo wp_login_url(htmlspecialchars(get_permalink($the_mdoc['parent']))); ?>"><?php echo __('Login','memphis-documents-library'); ?></a><?php
	}
}
function mdocs_display_description($the_mdoc) {
	echo $the_mdoc['desc'];
}
function mdocs_display_real_author($the_mdoc) {
	if(isset($the_mdoc['author']))	echo $the_mdoc['author'];
}
function mdocs_display_file_size($the_mdoc) {
	$upload_dir = wp_upload_dir();
	$mdocs_file = $upload_dir['basedir'].'/mdocs/'.$the_mdoc['filename'];
	echo mdocs_convert_bytes(filesize($mdocs_file));
	
}
?>