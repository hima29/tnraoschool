<?php
  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<style type="text/css">
.math-block {
  background-color: lightgrey;
  padding: 20px;
  font-family: Monospace;
  font-size: 10pt;
}

.header-image {
  width: 300px;
}
</style>
<img class='header-image' src='<?php echo $wp_edocr_plugin_url . 'images/edocr-master-logo-rgb-w-back.png';?>'>
<h1>edocr Document Viewer for WordPress</h1>
<p>edocr Document Viewer for WordPress is a document viewer that enables you easily embed documents on your WordPress site. It allows everyone that visits your website to view your site’s content. edocr Document Viewer is a HTML5 document viewer that enables
  you to display dozens of different document and image files on your website without worrying about whether your visitors have the software to view them or without having to convert it to another format first and risk degrading its quality. edocr Document
  Viewer supports dozens of file types, including DOC, PDF, PPT, XLS, CAD, GoogleDocs, and more.</p>
<p>Try it today for free.</p>
<h2>Description</h2>
<p>This plugin adds a shortcode which alows you to embed documents on your page or post that are hosted on edocr.com. For example, if you have a document with the following URL:</p>
<p class='math-block'><?php echo $wp_edocr_service_agreement_url;?></p>
<p>You&#39;ll need to find the documents unique identifier. It&#39;s the 8-letter part of the URL directly after the &#39;/v/&#39;. In this example, the unique document identifier (or GUID) is:</p>
<p class='math-block'>aqegexna</p>
<p>Now simply take your document GUID and use it with our shortcode to add a document to your post or page like this:</p>
<p class='math-block'>[edocr guid=aqegexna]</p>
<p>Once you save and view your post, you should now have an embedded instance of the document on your WordPress site. You can embed any unrestricted edocr document on the site.</p>
<h2>Usage</h2>
<p>Once the edocr Document Viewer is installed on your WordPress site, you&#39;ll need to find an edocr document GUID for an existing document to embed from the <a href='<?php echo $wp_edocr_homepage_url;?>' target='_blank'>edocr website</a>. You can use the edocr <a href='<?php echo $wp_edocr_search_url;?>' target='_blank'>search</a> functionality to find existing documents. For existing documents published on edocr, no further action is needed to get started. </p>
<p>To embed new documents, simply <a href='<?php echo $wp_edocr_account_creation_url;?>' target='_blank'>sign up</a> for a free edocr account. This will allow you to publish your documents on <a href='<?php echo $wp_edocr_homepage_url;?>' target='_blank'>edocr.com</a> and then embed them on your WordPress site using the shortcode and the document GUID.</p>
<h2>Shortcode Options</h2>
<p><strong>guid=abcdefgh</strong></p>
<ul>
  <li>
    REQUIRED - This is the only required option. It specifies which unique edocr document you&#39;d like to embed</li>
</ul>
<p>Example:</p>
<p class='math-block'>[edocr guid=aqegexna]</p>
<p><strong>type=(legacy/viewer/thumbnail)</strong></p>
<ul>
  <li>
    viewer - This option embeds an iframe with a default size of 300 x 300 pixels</li>
  <li>
    thumbnail - this option embeds an image thumbnail of the specified document inside of an image tag</li>
</ul>
<p>Example:</p>
<p class='math-block'>[edocr guid=aqegexna type=viewer]<br> [edocr guid=aqegexna type=thumbnail]</p>
<p><strong>autofit=1</strong></p>
<ul>
  <li>
    (Can be used with viewer or thumbnail type)</li>
  <li>
    This option looks at the dimensions of the full-size document thumbnail and sizes the viewer or image accordingly</li>
</ul>
<p>Example:</p>
<p class='math-block'>[edocr guid=aqegexna type=viewer autofit=1]<br> [edocr guid=aqegexna type=thumbnail autofit=1]</p>
<p><strong>width=x</strong>
  <strong>height=y</strong></p>
<ul>
  <li>
    Allows you to explicitly specify the height and width of the iframe or image tag</li>
  <li>
    Can be used with type=viewer or type=thumbnail</li>
  <li>
    Both values must be specified</li>
  <li>
    Units for values are pixels</li>
  <li>
    Cannot be used with autofit=1</li>
</ul>
<p>Example:</p>
<p class='math-block'>[edocr guid=aqegexna type=viewer width=640 height=480]</p>
<p><strong>option=(profile/collection)</strong></p>
<ul>
  <li>
    (Can only be used with type = viewer)</li>
  <li>
    profile - Adds a link after the iframe that allows viewers to subscribe to the edocr profile of the document publisher</li>
  <li>
    collection - Adds a link after the iframe that allows viewers to subscribe to the edocr document collection in which the document resides</li>
</ul>
<br>
<p>
<strong>Questions or Comments?</strong> Direct them to us at <a href='mailto:<?php echo $wp_edocr_support_email;?>' target='_blank'>info@edocr.com</a>
or by visiting the Help Center on our site: <a href='<?php echo $wp_edocr_homepage_url;?>' target='_blank'>edocr.com</a>
</p>
<p>Thank you for using the edocr Document Viewer for WordPress!<br>
  <em>-The edocr Team</em>
</p>
