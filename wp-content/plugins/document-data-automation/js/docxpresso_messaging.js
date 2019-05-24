jQuery(function($) {
    
	window.addEventListener("message", DXOResponse, false);

	function DXOResponse(event){
		//here we shoud make sure that the message comes from the docxpresso instance
		if (event.origin !== DXOptions['installation']){
			return;
		}
		console.log('origin: ' + event.origin);
		console.log('DXO: ' + DXOptions['installation']);
		if (typeof event.data == 'string' && event.data != 'undefined'){
			try {
				var sentData = JSON.parse(event.data);
				console.log(sentData);
				if (sentData.type == 'responseDXO') {
					var id = sentData.templateId;
					var ref = '/documents/preview/' + id;
					var ref2 = '/documents/previewForm/' + id;
					var selector = $('iframe[src*="' + ref + '"], iframe[src*="' + ref2 + '"]');
					if (DXOptions['type'] == 'message'){
						$('<p class="DXONotify">' + DXOptions['message'] + '</p>').insertBefore(selector);
						selector.remove();
					} else if (DXOptions['type'] == 'url'){
						$('body').empty();
						window.location.href = DXOptions['url'];
					}
				} else if (sentData.type == 'highlightDXO'){
					var frames = document.getElementsByTagName('iframe');
					for (var i = 0; i < frames.length; i++) {
						if (frames[i].contentWindow === event.source) {
							var iframePos = $(frames[i]).offset().top; //the height sent from iframe
							console.log('iframe: ' + iframePos);
							var selectorPos = sentData.verticalPosition;
							console.log('selector: ' + selectorPos);
							var pos = iframePos + selectorPos;
							$([document.documentElement, document.body]).animate({
								scrollTop: pos
							}, 500);
							break;
						}
					}
					console.log(sentData.verticalPosition);
				}
			} catch (e) {
				console.log ('No JSON format');
			}
			 
		}
	}
   
    
});





