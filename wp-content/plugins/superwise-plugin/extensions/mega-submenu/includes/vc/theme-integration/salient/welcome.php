<?php
$current_theme = wp_get_theme();

$content = '';
$content .= '<p>';
$content .= 'Edit header.php around line 263 replace <code>wp_nav_menu</code> function with <code>msm_mobile_wp_nav_menu</code>.';
$content .= '</p>';
$content .= '<p>';
$content .= 'Edit footer.php from line 193 to 205 replace any occurrence of <code>wp_nav_menu</code> function with <code>msm_mobile_wp_nav_menu</code>. There should be 2 occurrences.';
$content .= '</p>';
$content .= '<p>';
$content .= 'Header Permanent Transparent feature is not supported.';
$content .= '</p>';


$fields = array(
	'id'       => 'opt-raw',
	'type'     => 'raw',
	'title'    => "You are using {$current_theme->get('Name')} theme",
//	'subtitle' => __( 'Subtitle text goes here.', $text_domain ),
//	'desc'     => __( 'To get started no setup needed.', $text_domain ),
	'content'  =>  $content,
);


return $fields;