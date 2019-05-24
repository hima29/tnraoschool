( function( blocks, editor, i18n, element, components, _ ) {
    var el = element.createElement;
    var RichText = editor.RichText;
    var MediaUpload = editor.MediaUpload;


    blocks.registerBlockType( 'docxpresso-saas/plugin', {
        title: 'Docxpresso SaaS',
        icon: {src:'welcome-add-page', foreground: '#d15d29',},
        category: 'widgets',
        attributes: {
                blockID: {
					type: 'string',
                },
				mediaID: {
					type: 'integer',
                },
                content: {
					type: 'array',
					source: 'children',
					selector: 'p',
                },
        },

        edit: function( props ) {
                var attributes = props.attributes;
				//console.log(attributes);
				if (typeof attributes.blockID == 'undefined'){
					attributes.blockID = 'dxo-' + Math.ceil(Math.random() * 100000000);
				}
				if (typeof attributes.content[0] == 'undefined'){
					attributes.content[0] = '';
				}
				if (typeof attributes.content[0] != 'undefined' && attributes.content[0].substring(0,1) == "["){
						DXO.templateShortcode[attributes.blockID] = attributes.content[0];
					}
				var openPopup = function() {
					if (typeof attributes.content[0] != 'undefined' && attributes.content[0].substring(0,1) == "["){
						return false;
					}
					$('body').addClass('DXO');
					var url = DXO.openCategoriesPopUp + '&blockEditor=' + attributes.blockID + '&TB_iframe=true';
					tb_show(DXO.popupTitle, url);
					console.log(url);
					return props.setAttributes( {
								mediaID: Math.ceil(Math.random() * 100000000),
						} );
				};

                return (
                            el( 'div', { className: props.className },

                                        el( components.Button, {
                                                    className: attributes.mediaID ? 'file-button' : 'button button-large',
                                                    onClick: openPopup
                                                },
                                                ! attributes.mediaID ? DXO.popupTitle : el(RichText,{tagName: 'p',className: props.className,onChange: openPopup,value: DXO.templateShortcode[attributes.blockID],})
										)
                            )	
                        );	
            },
        save: function( props ) {
            var attributes = props.attributes;
			if (typeof DXO.templateShortcode[attributes.blockID] != 'undefined'){
				attributes.content[0] = DXO.templateShortcode[attributes.blockID];
			}
			//console.log(attributes);
            return (
                    el( 'div', { className: props.className },
                            el( RichText.Content, {tagName: 'p', value: attributes.content} ),
                            )
            );
        },
    } );

} )(
	window.wp.blocks,
	window.wp.editor,
	window.wp.i18n,
	window.wp.element,
	window.wp.components,
	window._,
);
