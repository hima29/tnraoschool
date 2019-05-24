<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//scripts
wp_register_script( 'dxo_cripto', plugins_url('/lib/vendor/js/sha.js',__DIR__ ) );
wp_enqueue_script( 'dxo_cripto', plugins_url('/lib/vendor/js/sha.js',__DIR__ ) );
wp_enqueue_script( 'dxo-color', plugins_url('/js/dxo-color.js', __DIR__ ), array( 'wp-color-picker' ), false, true );
//styles
wp_enqueue_style( 'dxosaas', plugins_url('/css/dxosaas.css',__DIR__));
wp_enqueue_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
wp_enqueue_style( 'wp-color-picker' );

//let us define the vriable $change to establish if there are configuration option modifications
$change = false;

if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'save')) {
    if (isset($_POST['options'])) {
    	/*
    	 * Check $_POST['options']
    	 */
    	$optionsWP = get_option('docxpressoSaaS', array());
    	// trim 
        $options = stripslashes_deep($_POST['options']);

		$urlDocxpresso = trim(esc_url($options['DocxpressoUrl']));
		//remove trailing slashes
		if (substr($urlDocxpresso, -1) == '/'){
			var_dump('entro');
			$urlDocxpresso = substr($urlDocxpresso, 0, -1);
		}
		$options['DocxpressoUrl'] = $urlDocxpresso;
		$optionsWP['DocxpressoUrl'] = sanitize_text_field($options['DocxpressoUrl']);
		$optionsWP['pKey'] = sanitize_text_field(trim($options['pKey']));
		$optionsWP['email'] = sanitize_text_field(trim($options['email']));
		$optionsWP['dxoRedirectType'] = sanitize_text_field(trim($options['dxoRedirectType']));
		$optionsWP['dxoRedirectURL'] = sanitize_text_field(trim($options['dxoRedirectURL']));
		$optionsWP['dxoFrameStyle'] = sanitize_text_field(trim($options['dxoFrameStyle']));
		$optionsWP['dxoMessageStyle'] = sanitize_text_field(trim($options['dxoMessageStyle']));

        update_option('docxpressoSaaS', $optionsWP);
    } else {
        update_option('docxpressoSaaS', array());
    }
} else {
    $options = get_option('docxpressoSaaS', array());
}
if (empty($options['dxoMessage'])){
		$options['dxoMessage'] = __('Thanks for providing us with the requested information.', 'document-data-automation');
}

$pages = get_pages(); 
$counter = 0;
$select = '';
foreach ( $pages as $page ) {
	$link = get_page_link( $page->ID );
	if (isset($options['dxoRedirectURL']) && $link == $options['dxoRedirectURL']){
		$option = '<option value="' . $link . '" selected>';
	} else if (empty($options['dxoRedirectURL']) && $counter == 0){
		$option = '<option value="' . $link . '" selected>';
		if (empty($options['dxoRedirectType']) || $options['dxoRedirectType'] == 'internal'){
			$options['dxoRedirectURL'] = $link;
		}
	} else {
		$option = '<option value="' . $link . '">';
	}
	$option .= $page->post_title;
	$option .= '</option>';
	$select .= $option;
	$counter++;
}

//default style values
$defaultStyleValues = array();
$defaultStyleValues['frameStyle'] = 'border: 0px solid #cccccc';
$defaultStyleValues['messageBox'] = 'border: 1px dotted #cccccc;background-color: #f0f0f0;font-size: inherit;color: #333333;';
?>

<!--  div class="wrap"-->
	<div class="wrap content DXOptionsConf">  
    	<h1 class="wp-heading-inline dxo-logo"><?php _e('Docxpresso configuration options', 'document-data-automation'); ?></h1>
		<div class="updated notice" id="conf_updated" <?php if(!isset($_POST['_wpnonce'])) echo 'style="display: none"'; ?>>
			<p><?php _e('The plugin configuration has been successfully updated.', 'document-data-automation'); ?></p>
		</div>
		<div id="responseDXO" ></div>
    	<form action="" method="post">
        	<?php wp_nonce_field('save') ?>
			<h2 class="nav-tab-wrapper">
				<a href="#" class="nav-tab nav-tab-active" id="tab_1" onclick="return false"><i class="fa fa-plug"> </i> <?php _e('Docxpresso Connection', 'document-data-automation'); ?></a>
				<a href="#" class="nav-tab" id="tab_2" onclick="return false"><i class="fa fa-gear"> </i> <?php _e('General settings', 'document-data-automation'); ?></a>
				<a href="#" class="nav-tab" id="tab_3" onclick="return false"><i class="fa fa-paint-brush"> </i> <?php _e('Styles', 'document-data-automation'); ?></a>
			</h2>
			
			<div id="DXOConnection" style="margin-left: 12px; margin-top: 20px">
				<p><strong><i class="fa fa-globe"> </i> <?php _e('Associated Docxpresso Installation'); ?>:</strong>
					<input class="regular-text" type="text" id="dxoURL" name="options[DocxpressoUrl]" value="<?php if(isset($options['DocxpressoUrl'])){echo $options['DocxpressoUrl'];}?>" placeholder="https://alias.docxpresso.net">
					<button class="button button-primary" id="testConnection" onclick="return false"><i class="fa fa-plug"> </i> <?php  _e('Test connection', 'document-data-automation'); ?></button>
					<br /><small><?php _e('Introduce the full URL of your Docxpresso SaaS installation.', 'document-data-automation'); ?></small>
				</p>
				<p><strong><i class="fa fa-lock"> </i> <?php echo _e('Private API Key', 'document-data-automation'); ?>:</strong>
					<input class="regular-text" type="text" id="dxoKey" name="options[pKey]" value="<?php if(isset($options['pKey'])){echo $options['pKey'];}?>" placeholder="private key" style="width: 36em">
					<br /><small><?php _e('Introduce the API Key of your Docxpresso SaaS installation (you can find it under the Configuration > Account menu entry).', 'document-data-automation'); ?></small>	
				</p>
				<p><strong><i class="fa fa-at"> </i> email:</strong>
					<input class="regular-text" type="text" id="dxoEmail" name="options[email]" value="<?php if(isset($options['email'])){echo $options['email'];}?>" placeholder="Docxpresso registration email">
					<br /><small><?php _e('Introduce your Docxpresso registration email or any other email with ADMIN permissions in your Docxpresso SaaS installation.', 'document-data-automation'); ?></small>	
				</p>
			</div>
			
			<div id="DXOSettings" style="display: none; margin-left: 12px; margin-top: 20px">
				<p style="margin-bottom: 0.4em"><strong><i class="fa fa-globe"> </i> <?php _e('Redirect URL', 'document-data-automation'); ?>:</strong>
					<select id="redirectType" name="options[dxoRedirectType]">
						<option value="external" <?php if(isset($options['dxoRedirectType']) && $options['dxoRedirectType'] == 'external'){echo 'selected';} ?>><?php _e('External URL', 'document-data-automation'); ?></option>
						<option value="internal" <?php if(!isset($options['dxoRedirectType']) || (isset($options['dxoRedirectType']) && $options['dxoRedirectType'] == 'internal')){echo 'selected';} ?>><?php _e('WordPress page', 'document-data-automation'); ?></option>
					</select>
					<input hidden id="dxoRedirectURL" name="options[dxoRedirectURL]" value="<?php if(isset($options['dxoRedirectURL'])){echo $options['dxoRedirectURL'];}?>" />
				</p>
				<p id="externalURL" style="margin-left: 30px; margin-top: 0.3em; <?php if(!isset($options['dxoRedirectType']) || (isset($options['dxoRedirectType']) && $options['dxoRedirectType'] == 'internal')){echo 'display: none';} ?>"><i class="fa fa-link"> </i>  <strong><?php _e('External URL', 'document-data-automation'); ?>:</strong>
					<input class="regular-text" type="text" id="externalRedirectURL" name="externalRedirectURL" value="<?php if(isset($options['dxoRedirectURL']) && (isset($options['dxoRedirectURL']) && $options['dxoRedirectType'] == 'external')){echo $options['dxoRedirectURL'];}?>" placeholder="http://redirect.mydomain.com">
					<br /><small><?php _e('Choose an optional external default page were the users will be redirected after fulfilling a document.', 'document-data-automation'); ?><br></small>	
				</p>
				<p id="internalURL" style="margin-left: 30px; margin-top: 0.3em; <?php if(isset($options['dxoRedirectType']) && $options['dxoRedirectType'] == 'external'){echo 'display: none';} ?>"><i class="fa fa-wordpress"> </i>  <strong> <?php _e('WordPress page', 'document-data-automation'); ?>:</strong>
					<select id="internalRedirectURL" name="internalRedirectURL">
					<?php echo $select; ?>
					</select>
					<br /><small><?php _e('Choose an optional default WordPress page were the users will be redirected after fulfilling a document.', 'document-data-automation'); ?><br></small>	
				</p>
				<p><strong><i class="fa fa-bullhorn"> </i> Default message:</strong><br>
					<textarea id="dxoMessage" name="options[dxoMessage]" cols="80" rows="2"><?php if(isset($options['dxoMessage'])){echo $options['dxoMessage'];}?></textarea>
					<br /><small><?php _e('Choose a default message to be shown to your users after fulfilling the document.', 'document-data-automation'); ?></small>	
				</p>
			</div>
			
			<div id="DXOStyles" style="display: none; margin-left: 12px; margin-top: 20px">
				<p style="margin-bottom: 0.4em"><strong><i class="fa fa-paint-brush"> </i> <?php _e('Document/Web form frame style', 'document-data-automation'); ?>:</strong>
				<p><span><?php _e('Border', 'document-data-automation'); ?>: </span>
					<select id="borderFrameWidth" class="DXOframeStyles">
						<option value="0px" >0px (<?php _e('no border', 'document-data-automation'); ?>)</option>
						<option value="1px" >1px</option>
						<option value="2px" >2px</option>
						<option value="3px" >3px</option>
					</select>
					<select id="borderFrameStyle" class="DXOframeStyles">
						<option value="solid" ><?php _e('solid', 'document-data-automation'); ?></option>
						<option value="dotted" ><?php _e('dotted', 'document-data-automation'); ?></option>
					</select>
					<input class="regular-text color-field DXOframeStyles" type="text" id="borderFrameColor" value="" />
					<input hidden id="dxoFrameStyle" name="options[dxoFrameStyle]" value="<?php if(isset($options['dxoFrameStyle'])){echo $options['dxoFrameStyle'];}else{echo $defaultStyleValues['frameStyle'];}?>" />
				</p>
				
				<p style="margin-bottom: 0.4em"><strong><i class="fa fa-paint-brush"> </i> <?php _e('Message box style', 'document-data-automation'); ?>:</strong>
				<p><span><?php _e('Border', 'document-data-automation'); ?>: </span>
					<select id="borderMessageWidth" class="DXOframeStyles">
						<option value="0px" >0px (<?php _e('no border', 'document-data-automation'); ?>)</option>
						<option value="1px" >1px</option>
						<option value="2px" >2px</option>
						<option value="3px" >3px</option>
					</select>
					<select id="borderMessageStyle" class="DXOframeStyles">
						<option value="solid" ><?php _e('solid', 'document-data-automation'); ?></option>
						<option value="dotted" ><?php _e('dotted', 'document-data-automation'); ?></option>
					</select>
					<input class="regular-text color-field DXOframeStyles" type="text" id="borderMessageColor" value="" />
				</p>
				<p><span><?php _e('Background color', 'document-data-automation'); ?>: </span>
					<input class="regular-text color-field DXOframeStyles" type="text" id="backgroundMessageColor" value="" />	
				</p>
				
				<p><span><?php _e('Font', 'document-data-automation'); ?>: </span>
					<select id="fontMessageSize">
						<option value="inherit" ><?php _e('default size', 'document-data-automation'); ?></option>
						<option value="large" ><?php _e('big', 'document-data-automation'); ?></option>
						<option value="x-large" ><?php _e('very big', 'document-data-automation'); ?></option>
						<option value="small" ><?php _e('small', 'document-data-automation'); ?></option>
						<option value="x-small" ><?php _e('very small', 'document-data-automation'); ?></option>
					</select>
					<input class="regular-text color-field" type="text" id="fontMessageColor" value="" />
				</p>
				
				<input hidden id="dxoMessageStyle" name="options[dxoMessageStyle]" value="<?php if(isset($options['dxoMessageStyle'])){echo $options['dxoMessageStyle'];}else{echo $defaultStyleValues['messageBox'];} ?>" />
			</div>
			
        	<p class="submit">
            	<button class="button button-primary" type="submit" name="save"><i class="fa fa-save"> </i> <?php _e('Save Configuration', 'document-data-automation'); ?></button>
        	</p>
    	</form>
	</div>
<!-- /div-->

<script>
jQuery(function ($) {
	
	$('#testConnection').click(function(){
		testConnection();
	});
	
	function testConnection(){
		//empty response container
		$('#responseDXO').empty();
		var spinner = '<div class="spinner is-active" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 10px;">Testing connection</div>';
		$('#responseDXO').append(spinner);
		//define required vars
		var baseURL = $('#dxoURL').val();
		var pKey = $('#dxoKey').val();
		var email = $('#dxoEmail').val();
		var uniqid = Math.floor((Math.random() * 1000000) + 1);
		var timestamp = + new Date();
		//generate HMAC
		var prehash = timestamp + '-' + uniqid;
		var sha = new jsSHA(prehash, 'TEXT');
		var hash = sha.getHash('SHA-1', "HEX");
		var shaObj = new jsSHA(hash, 'HEX');
		var APIKEY = shaObj.getHMAC(pKey, "TEXT", 'SHA-1', "HEX");
		
		var testURL = baseURL + '/RESTservices/predefined/test_connection?timestamp=' +  timestamp + '&uniqid=' + uniqid + '&APIKEY=' + APIKEY + '&email=' + email;
		console.log(testURL);
		$.ajax({
			url: testURL,
			cache: false,
			crossDomain: true,
			dataType: "jsonp",
			timeout: 10000,
			jsonpCallback: "testConnection",
			// parse response
			success: function( data ) {
				parseResponse(data)
			},
			error: function( xhr, status, errorThrown ) {
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			}
		}).done(function() {
			//do nothing
		  })
		  .fail(function() {
			incorrectURL()
		  })
		  .always(function() {
			//do nothing
		  });
  
  
	}
	
	function parseResponse(data){
		$('#responseDXO').empty();
		var success = true;
		if (data.email == 0){
			var invalid_email = '<div class="notice notice-error"><p> <i class="fa fa-times-circle" style="color: #dc3232"> </i> <?php _e('The email is not a valid registered email with ADMIN privileges.', 'document-data-automation'); ?></p></div>';
			$('#responseDXO').append(invalid_email);
			success = false;
		}
		if (data.auth == 0){
			var invalid_apikey = '<div class="notice notice-error"><p> <i class="fa fa-times-circle" style="color: #dc3232"> </i> <?php _e('The introduced API key is not correct.', 'document-data-automation'); ?></p></div>';
			$('#responseDXO').append(invalid_apikey);
			success = false;
		}
		
		if (success){
			var connection = '<div class="notice updated"><p> <i class="fa fa-check" style="color: #46b450"> </i> <?php _e('The connection data is correct.', 'document-data-automation'); ?></p></div>';
			$('#responseDXO').append(connection);
		}
	}
	
	function incorrectURL(){
		$('#responseDXO').empty();
		var invalid_url = '<div class="notice notice-error"><p> <i class="fa fa-times-circle" style="color: #dc3232"> </i> <?php _e('The Docxpresso SaaS URL is not responding. <strong>Have you introduced the correct URL?', 'document-data-automation'); ?></p></div>';
		$('#responseDXO').append(invalid_url);
	}
	
	$('#redirectType').change(function(){
		if($(this).val() == 'internal'){
			$('#internalURL').show();
			$('#externalURL').hide();
		} else {
			$('#internalURL').hide();
			$('#externalURL').show();
		}
	});
	
	$('#internalRedirectURL').change(function(){
		$('#dxoRedirectURL').val($(this).val());
	});
	
	$('#externalRedirectURL').change(function(){
		$('#dxoRedirectURL').val($(this).val());
	});
	
	//TABS
	$('#tab_1').click(function(){
		$(this).addClass('nav-tab-active');
		$('#tab_2').removeClass('nav-tab-active');
		$('#tab_3').removeClass('nav-tab-active');
		$('#DXOSettings').hide();
		$('#DXOStyles').hide();
		$('#DXOConnection').show();
	});
	$('#tab_2').click(function(){
		$(this).addClass('nav-tab-active');
		$('#tab_1').removeClass('nav-tab-active');
		$('#tab_3').removeClass('nav-tab-active');
		$('#DXOConnection').hide();
		$('#DXOStyles').hide();
		$('#DXOSettings').show();
	});
	$('#tab_3').click(function(){
		$(this).addClass('nav-tab-active');
		$('#tab_1').removeClass('nav-tab-active');
		$('#tab_1').removeClass('nav-tab-active');
		$('#DXOSettings').hide();
		$('#DXOConnection').hide();
		$('#DXOStyles').show();
	});
	
	//STYLES
	$('.color-field').wpColorPicker();
	updateStyles();
	function updateStyles() {
		//parse styles
		//FRAME
		var frameStyle = $('#dxoFrameStyle').val();
		//'border: 0px solid #cccccc'
		var frameStyleArray = frameStyle.split(' ');
		$('#borderFrameWidth').val(frameStyleArray[1]);
		$('#borderFrameStyle').val(frameStyleArray[2]);
		$('#borderFrameColor').val(frameStyleArray[3]);
		$('#borderFrameColor').wpColorPicker('color', frameStyleArray[3]);
		//MessageBox
		var messageBox = $('#dxoMessageStyle').val();
		//'border: 1px dotted #cccccc;background-color: #f0f0f0;font-size: inherit;color: #333333;'
		var messageStyleArray = messageBox.split(';');
		if(messageStyleArray.length == 4){
			var messageBorderStyleArray = messageStyleArray[0].split(' ');
			console.log(messageBorderStyleArray);
			$('#borderMessageWidth').val(messageBorderStyleArray[1]);
			$('#borderMessageStyle').val(messageBorderStyleArray[2]);
			$('#borderMessageColor').val(messageBorderStyleArray[3]);
			$('#borderMessageColor').wpColorPicker('color', messageBorderStyleArray[3]);
			var messageBackgroundStyleArray = messageStyleArray[1].split(' ');
			$('#backgroundMessageColor').val(messageBackgroundStyleArray[1]);
			$('#backgroundMessageColor').wpColorPicker('color', messageBackgroundStyleArray[1]);
			console.log(messageBackgroundStyleArray);
			var messageFontSizeArray = messageStyleArray[2].split(' ');
			$('#fontMessageSize').val(messageFontSizeArray[1]);
			console.log(messageFontSizeArray);
			var messageFontColorArray = messageStyleArray[3].split(' ');
			$('#fontMessageColor').val(messageFontColorArray[1]);
			$('#fontMessageColor').wpColorPicker('color', messageFontColorArray[1]);
			console.log(messageFontColorArray);
		}
	}
	//onChange STYLES
	/*$('.DXOframeStyles').change(function(){
		parseStyles();
	});
	$(".color-field").wpColorPicker(
		'option',
		'change',
		function(event, ui) {
			setTimeout(parseStyles(), 500);
		}
	);*/

	function parseStyles(){
		//parse frame properties
		var frame = 'border: ';
		frame += $('#borderFrameWidth').val() + ' ';
		frame += $('#borderFrameStyle').val() + ' ';
		if ($('#borderFrameColor').val() == ''){
			frame += '#ffffff';
		} else {
			frame += $('#borderFrameColor').val();
		}
		$('#dxoFrameStyle').val(frame)
		console.log(frame);
		//parse message properties
		var message = 'border: ';
		message += $('#borderMessageWidth').val() + ' ';
		message += $('#borderMessageStyle').val() + ' ';
		if ($('#borderMessageColor').val() == ''){
			message += '#ffffff';
		} else {
			message += $('#borderMessageColor').val();
		}
		message += ';background-color: ';
		if ($('#backgroundMessageColor').val() == ''){
			message += '#ffffff';
		} else {
			message += $('#backgroundMessageColor').val();
		}
		message += ';font-size: ';
		message += $('#fontMessageSize').val();
		message += ';color: ';
		if ($('#fontMessageColor').val() == ''){
			message += '#ffffff';
		} else {
			message += $('#fontMessageColor').val();
		}
		$('#dxoMessageStyle').val(message)
		console.log(message);
	}
	
	$('form').submit(function(){
		parseStyles();
		return;
	});
	
});
</script>
