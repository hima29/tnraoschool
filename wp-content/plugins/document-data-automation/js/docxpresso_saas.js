jQuery(function($) {

    $(document).ready(function(){
        $('.insert-docxpresso-saas').click(insert_docxpresso_shortcode);
    });
    

    function insert_docxpresso_shortcode() {
		//we add a class to the body in order to manage the styles of
		//the thickbox popup without messing up with other plugin or themes
		$('body').addClass('DXO');
		tb_show(DXO.popupTitle, DXO.openCategoriesPopUp + '&TB_iframe=true');
    }
    
	window.addEventListener("message", DXOMessage, false);

	function DXOMessage(event){
		//here we shoud make sure that the message comes from the docxpresso instance
		if (event.origin !== DXO.installation){
			return;
		}
		if (typeof event.data == 'string'){
			var sentData = JSON.parse(event.data);
			//console.log(sentData);
			if (sentData.type == 'shortcode') {
				//insert the shortcode
				wp.media.editor.insert(sentData.wp_shortcode);
				//close the popup
				$("#TB_overlay").remove();
				$("#TB_window").remove();
				$("body").removeClass('modal-open');
			} else if (sentData.type == 'shortcodeBlock') {
				//insert the shortcode
				DXO.onSelected = true;
				var blockID = sentData.block;
				DXO.templateShortcode[blockID] = sentData.wp_shortcode;
				console.log(DXO.templateShortcode);
				$('.wp-block-docxpresso-saas-plugin').click();
				//close the popup
				$("#TB_overlay").remove();
				$("#TB_window").remove();
				$("body").removeClass('modal-open');
			}else if (sentData.type == 'refreshToken'){
				//refresh the tokens so we can log in again
				DXO.openCategoriesPopUp = sentData.accessByToken.replace('&amp;', '&');
				//console.log(DXO.openCategoriesPopUp);
			}
		}
	}
	
	$(document).on("mouseup", ".DXO button#TB_closeWindowButton" , function (event) {
		//when the popup closes we remove the DXO class
		if (event.which == 1){
			//we only listen to left mouse button events
			if(DXO.closeConnection == 1){
				$.ajax({
					url: DXO.installation + '/users/remote_logout?url=' + encodeURIComponent('/documents/plugin/tree'),
					cache: false,
					crossDomain: true,
					dataType: "jsonp",
					//jsonpCallback: "logout",
					// parse response
					success: function( data ) {
						DXO.openCategoriesPopUp = data.accessByToken.replace('&amp;', '&');
					},
					error: function( xhr, status, errorThrown ) {
						console.log( "Error: " + errorThrown );
						console.log( "Status: " + status );
						console.dir( xhr );
					}
				});
			}
			setTimeout(function(){ $('body').removeClass('DXO'); }, 500);
		}
	});
   
    
});





