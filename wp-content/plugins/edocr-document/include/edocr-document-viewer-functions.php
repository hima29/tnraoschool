<?php
/*
Plugin Name: edocr Document Viewer
Plugin URI: https://github.com/edocr/edocr-document-viewer
Description: The edocr Document Viewer for Wordpress allows you to embed your documents on your WordPress site using our feature rich document viewer
Author: edocr <info@edocr.com>
Author URI: http://edocr.com
Version: 1.0.3
License: MIT
License URI: https://raw.githubusercontent.com/edocr/edocr-document-viewer/master/LICENSE
*/
  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

  function wp_edocr_shortcode ($atts) {
    global $EDOCR_DEV, $wp_edocr_embed_url, $wp_edocr_embed_url_dev;
    if (!function_exists('curl_version')) {
      $msg = 'Configuration Error: Missing PHP module: CURL';
      return wp_edocr_error_html($msg);
    }

    $guid = null;

    if (isset($atts['guid'])) {
      $guid = $atts['guid'];
      unset($atts['guid']);
      if ($EDOCR_DEV){
        $url = $wp_edocr_embed_url_dev . $guid;
      } else {
        $url = $wp_edocr_embed_url . $guid;
      }
      if (!isset($atts['type'])) {
        $atts['type'] = 'viewer';
      }
      $atts['source'] = 'wordpress';

      if (count($atts)){
        $url = $url . '?' . http_build_query($atts);
      }

      $embed = wp_edocr_get_document($url);

      return $embed;
    } else {
      return '';
    }
  }

  function wp_edocr_get_document ($url) {
    global $EDOCR_DEV;
    $args =[];
    $args['timeout'] = 20;
    if ($EDOCR_DEV) {
      //Testing in dev environment with self signed certificates
      $args['sslverify'] = false;
    }

    $response = wp_remote_get($url, $args);
    if(is_wp_error($response)){
      $err = $response->get_error_message();
      $msg = '<h3>Error ' . $http_code . '</h3><br>WP Error: ' . $err;
      return wp_edocr_error_html($msg);
    }
    $body = wp_remote_retrieve_body($response);
    $http_code = wp_remote_retrieve_response_code($response);

    if ($http_code === 200) {
      return $body;
    } else {
      if ($http_code === 403) {
        $msg = '<h3>Error ' . $http_code . '</h3><br>Embedding the document specified has been disallowed by the document owner. Please try another document or contact the document owner and ask them to enable document embedding in their document settings';
      } elseif ($http_code === 404) {
        $msg = '<h3>Error ' . $http_code . '</h3><br>We couldn\'t find the edocr.com document you requested. Please check your document GUID (aka. Document ID Code) and try again.';
      } else {
        $msg = '<h3>Error ' . $http_code . '</h3><br>Unspecified error. We couldn\'t find the edocr.com document you requested. Please check your document GUID (aka. Document ID Code) and try again.';
      }
      return wp_edocr_error_html($msg);
    }
  }

  function wp_edocr_error_html($message) {
    $err_html = '<div style="border: 3px solid black; padding: 25%; width: 100%; height: 100%;"><p style="text-align: center; font-size: .8em;">' . $message . '</p></div>';
    return $err_html;
  }

  function wp_edocr_options_page_html(){
    global $wp_edocr_plugin_url, $wp_edocr_service_agreement_url,
      $wp_edocr_account_creation_url, $wp_edocr_homepage_url,
      $wp_edocr_search_url, $wp_edocr_support_email;
    require('edocr-document-viewer-options.php');

  }

  function wp_edocr_options_page() {
    //We only execute any of this code if an admin user is logged in
    //This adds an options sub-menu underneath the settings menu
      add_submenu_page('options-general.php', 'edocr Document Viewer', 'edocr Document Viewer', 'manage_options', 'edocr_viewer', 'wp_edocr_options_page_html');
  }

?>
