<?php
$current_theme = wp_get_theme();

$content = '';
$content .= '<p>';
$content .= 'Edit footer.php from line 112 to 138 replace any occurrence of <code>wp_nav_menu</code> function with <code>msm_mobile_wp_nav_menu</code>. There should be 3 occurrences.';
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