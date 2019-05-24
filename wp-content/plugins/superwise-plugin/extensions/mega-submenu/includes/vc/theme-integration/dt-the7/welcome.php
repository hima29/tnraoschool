<?php
$current_theme = wp_get_theme();

$content = '';
$content .= '<p>';
$content .= 'Your theme is supported. Please visit General Settings and select your Main and Mobile menu locations.';
$content .= '</p>';
$content .= '<p>';
$content .= 'Also please make sure that your setting for Floating Header Effect is set to Sticky.';
$content .= '</p>';
$content .= '<p>';
$content .= 'To have our templates display correctly, please check if the Visual Composer row is set to "Default".';
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