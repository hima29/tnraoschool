<?php
function mdocs_manage_roles() {
	$wp_roles = get_editable_roles();
	foreach($wp_roles as $index => $role) {
		$role_object = get_role($index);
		if($index != 'administrator') {
			foreach(get_option('mdocs-caps') as $i => $cap) {
				if(isset($cap['roles'])) {
					if(is_array($cap['roles'])) {
						if(in_array($index, $cap['roles'])) {
							foreach($cap['caps'] as $name) {
								$role_object->add_cap($name);
							}
						} else {
							foreach($cap['caps'] as $name) {
								$role_object->remove_cap($name);
							}
						}
					}
				}
			}
		} elseif($index == 'administrator') {
			$role_object->add_cap('mdocs_manage_settings');
			$role_object->add_cap('mdocs_allow_upload');
		}
	}
}

function mdocs_add_cap($key, $title='', $roles=array(), $caps=array()) {
	$the_caps = get_option('mdocs-caps');
	$the_caps[$key] = array('title' => $title, 'roles' => $roles, 'caps' => $caps);
	$role_object = get_role('administrator');
	foreach($caps as $cap) {
		$role_object->add_cap($cap);
	}
	update_option('mdocs-caps', $the_caps);
}
function mdocs_update_cap($key, $title=null, $roles=null, $caps=null) {
	$the_caps = get_option('mdocs-caps');
	if($title != null) $the_caps[$key]['title'] = $title;
	if(is_array($roles)) $the_caps[$key]['roles'] = $roles;
	if(is_array($caps)) $the_caps[$key]['caps'] = $caps;
	update_option('mdocs-caps',$the_caps);
}
function mdocs_delete_cap($key) {
	$caps = get_option('mdocs-caps');
	unset($caps[$key]);
	update_option('mdocs-caps',$caps);
}
?>