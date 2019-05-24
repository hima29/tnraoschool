<?php
// Shortcode: [mdocs_upload_btn] - Creates a upload button for the front end.
add_shortcode( 'mdocs_upload_btn', 'mdocs_shortcode_upload' );
function mdocs_shortcode_upload($att, $content=null) { return mdocs_upload_button($att); }
// Shortcode: [mdocs] - Used in list.
add_shortcode( 'mdocs', 'mdocs_shortcode' );
function mdocs_shortcode($att, $content=null) { return mdocs_the_list($att); }
// Shortcode: [mdocs_post_page] - Used with a mdocs post page.
add_shortcode( 'mdocs_post_page', 'mdocs_post_page_shortcode' );
function mdocs_post_page_shortcode($att, $content=null) { return mdocs_post_page($att); }
// Shortcode: [mdocs_upload_btn] - Creates a upload button for the front end.
add_shortcode( 'mdocs_media_attachment', 'mdocs_shortcode_media_attachment' );
function mdocs_shortcode_media_attachment($att, $content=null) { return null; }
// Shortcode Setting Page
function mdocs_shortcodes($current_cat) {
	mdocs_list_header();
	?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title"><?php _e('Short Codes','memphis-documents-library'); ?></h3>
		</div>
		<div class="panel-body">
			<table class="table table-hover table-striped table-bordered" >
				<tr>
					<th><?php _e('Short Codes','memphis-documents-library');?></th>
					<th><?php _e('Description','memphis-documents-library');?></th>
				</tr>
				<tr>
					<td>[mdocs]</td>
					<td><?php _e('Adds the default Memphis Documents Library file list to any page, post or widget.','memphis-documents-library');?></td>
				</tr>
				<tr>
					<td>[mdocs cat="<?php _e('The Category Name','memphis-documents-library');?>"]</td>
					<td><?php _e('Adds files from  a specific folder of the Memphis Documents Library on any page, post or widget.','memphis-documents-library');?></td>
				</tr>
				<tr>
					<td>[mdocs cat="All Files"]</td>
					<td><?php _e('Adds a list of all files of the Memphis Documents Library on any page, post or widget.','memphis-documents-library');?></td>
				</tr>
				<tr>
					<td>[mdocs single-file="<?php _e('Enter the file name.','memphis-documents-library'); ?>"]</td>
					<td><?php _e('Adds a single file to any post, page or widget.','memphis-documents-library');?></td>
				</tr>
				<tr>
					<td>[mdocs header="<?php _e('This text will show up above the documents list.','memphis-documents-library'); ?>"]</td>
					<td><?php _e('Adds a header to the Memphis Documents LIbrary on ay page, post or widget.','memphis-documents-library');?></td>
				</tr>
				<tr>
					<td>[mdocs_upload_btn]</td>
					<td><?php _e('For more details on options for this button please check out','memphis-documents-library');?> <a href="http://kingofnothing.net/frontend-upload-button-help" target="_blank">http://kingofnothing.net/frontend-upload-button-help/</a>.</td>
				</tr>
			</table>
		</div>
	</div>
	<?php
}
?>