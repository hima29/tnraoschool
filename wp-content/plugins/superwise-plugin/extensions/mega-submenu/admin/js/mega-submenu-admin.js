(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(function() {
	 
		var menuIds = [];
		var menuItems = {};
    	var $items = $('#menu-to-edit .menu-item');
    	var empty = '';

    	$items.each(function () {

    		var $this = $(this);
    		var id = $this.attr('id');

    		menuIds.push(id);
    		menuItems[id] = {
    			item: $this
    		};
    	}); 

		var data = {
			'action': 'msm_get_custom_fields',
			'menu_ids': menuIds
		};

		$.post(ajaxurl, data, function(response) {

			$.each(response, function(i, obj) {

				if (menuItems[obj.menu_id]) {

					var $item = menuItems[obj.menu_id].item;
					var $fieldActions = $item.find('.menu-item-actions');

                    $fieldActions.before(obj.markup);
				}

				// set Empty for new items
				if (obj.menu_id === 'menu-item-0') {
					empty = obj.markup;
				}

			});

		}, 'json');



		$( document ).ajaxComplete(function( event, xhr, settings ) {
  		    var data = getQueryParameters(decodeURIComponent(settings.data));
		    if (data.action && data.action === 'add-menu-item') {

		  	    var $item = $('#menu-to-edit .menu-item').last();
				var $fieldActions = $item.find('.menu-item-actions');
                $fieldActions.before(empty);
		  }

		});

		function getQueryParameters(str) {
			return (str || document.location.search).replace(/(^\?)/,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
		}

	});


})( jQuery );
