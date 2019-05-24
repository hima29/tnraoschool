<?php
add_action( 'wp_enqueue_scripts', 'scp_scripts', 100 );

add_filter( 'the_content', 'scp_content_filter' );
add_filter( 'mce_external_plugins', 'scp_add_tinymce_plugins' );
add_filter( 'mce_buttons_3', 'scp_register_tinymce_buttons' );

add_shortcode( 'scp_icon_bullet_text', 'scp_icon_bullet_text_shortcode' );
add_shortcode( 'scp_icon', 'scp_icon_shortcode' );
add_shortcode( 'scp_separator', 'scp_separator_shortcode' );
add_shortcode( 'scp_block_quote_alt', 'scp_block_quote_alt_shortcode' );
add_shortcode( 'scp_table', 'scp_table_shortcode' );
add_shortcode( 'scp_table_row', 'scp_table_row_shortcode' );

add_action( 'scp_load_styles_scp_icon_bullet_text', 'scp_icon_bullet_text_shortcode_css' );
add_action( 'scp_load_styles_scp_icon', 'scp_icon_shortcode_css' );
add_action( 'scp_load_styles_scp_separator', 'scp_separator_shortcode_css' );
add_action( 'scp_load_styles_scp_block_quote_alt', 'scp_block_quote_alt_shortcode_css' );

// For fa_icon shortcode
function scp_scripts() {
	wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0', false );
	wp_enqueue_style( 'scp-style', SCP_PLUGIN_URL . 'public/css/style.css', false );
}


function scp_content_filter( $content ) {
	// array of custom shortcodes requiring the fix
	$block = join( '|', array(
		'scp_icon_bullet_text',
		'scp_icon',
		'scp_separator',
		'scp_block_quote_alt',
		'scp_table',
		'scp_table_row_shortcode',
	) );

	// opening tag
	$rep = preg_replace( "/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content );

	// closing tag
	$rep = preg_replace( "/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep );

	return $rep;
}

function scp_add_tinymce_plugins( $plugin_array ) {
	$plugin_array['scp_mce_shortcodes'] = SCP_PLUGIN_URL . '/public/js/tinymce/customcodes.js';
	return $plugin_array;
}

function scp_register_tinymce_buttons( $buttons ) {
	array_push( $buttons, 'scp_mce_shortcodes' );
	return $buttons;
}

function scp_icon_bullet_text_shortcode_css( $atts ) {
	$uid = SCP_Assets::get_uid( 'scp_icon_bullet_text', $atts );

	extract( shortcode_atts( array(
		'icon_font_size'    => '14px',
		'icon_color'        => '',
		'title_color'       => '',
		'subtitle_color'    => '',
		'description_color' => '',
		'float'             => 'left',
		'padding'           => '',
		'margin'            => '',
		'text_block_margin' => '',
	), $atts ) );

	$css = '';

	/**
	 * Main Wrapper Style
	 */
	$mainWrapperStyle = '';
	if ( $padding ) {
		$mainWrapperStyle .= "padding:{$padding};";
	}
	if ( $margin ) {
		$mainWrapperStyle .= "margin:{$margin};";
	}
	if ($mainWrapperStyle) {
		$css .= ".{$uid}.scp-shortcode{{$mainWrapperStyle}}";
	}

	/**
	 * Icon Wrapper Style
	 */
	$iconWrapperStyle = '';
	if ( $icon_color ) {
		$iconWrapperStyle .= "color:{$icon_color};";
	}
	if ( $icon_font_size ) {
		$icon_font_size = (int) $icon_font_size;
		$iconWrapperStyle .= "font-size:{$icon_font_size}px;";
	}
	if ( $iconWrapperStyle ) {
		$css .= ".{$uid} .scp-icon-bullet-text-icon{{$iconWrapperStyle}}";
	}

	/**
	 * Title Style
	 */
	if ( $title_color ) {
		$css .= ".{$uid} .title{";
		$css .= "color:{$title_color};";
		$css .= '}';
	}

	/**
	 * Title Block Style
	 */
	if ( $text_block_margin ) {
		$css .= ".{$uid} .scp-icon-bullet-text-text{";
		$css .= "margin:{$text_block_margin};";
		$css .= '}';
	}

	/**
	 * Subtitle Style
	 */
	if ( $subtitle_color ) {
		$css .= ".{$uid} .subtitle{";
		$css .= "color:{$subtitle_color};";
		$css .= '}';
	}

	/**
	 * Description Style
	 */
	if ( $description_color ) {
		$css .= ".{$uid} .description{";
		$css .= "color:{$description_color};";
		$css .= '}';
	}

	if ( $css ) {
		wp_add_inline_style( 'superwise_options_style', $css );
	}
}

function scp_icon_bullet_text_shortcode( $atts, $content = null ) {

	$uid = SCP_Assets::get_uid( 'scp_icon_bullet_text', $atts );

	extract( shortcode_atts( array(
		'icon'              => 'fa fa-clock-o',
		'icon_font_size'    => '14',
		'icon_color'        => '',
		'title'             => 'The Title',
		'title_tag'         => 'h3',
		'title_color'       => '',
		'subtitle_tag'      => 'h3',
		'subtitle'          => 'The Subtitle',
		'subtitle_color'    => '',
		'description_tag'   => 'p',
		'description_color' => '',
		'float'             => 'left',
		'padding'           => '',
		'margin'            => '',
		'text_block_margin' => '',
		'subtitle_is_above' => '0',
	), $atts ) );

	if ( $float == 'right' ) {
		$float = 'pull-right';
	} elseif ( $float == 'left' ) {
		$float = 'pull-left';
	} else {
		$float = '';
	}

	$titleTagOpen        = ! empty( $title_tag ) ? "<{$title_tag} class=\"title\">" : '';
	$titleTagClose       = ! empty( $title_tag ) ? "</{$title_tag}>" : '';
	$subtitleTagOpen     = ! empty( $subtitle_tag ) ? "<{$subtitle_tag} class=\"subtitle\">" : '';
	$subtitleTagClose    = ! empty( $subtitle_tag ) ? "</{$subtitle_tag}>" : '';
	$descriptionTagOpen  = ! empty( $description_tag ) ? "<{$description_tag} class=\"description\">" : '';
	$descriptionTagClose = ! empty( $description_tag ) ? "</{$description_tag}>" : '';

	$wrapper_class = "scp-shortcode scp-icon-bullet-text {$float} {$uid}";
	$out = '';
	$out .= '<div class="' . esc_attr( $wrapper_class ) . '">';
	$out .= '<div class="align-center scp-icon-bullet-text-icon">';
	$out .= '<i class="' . esc_attr( $icon ) . '"></i>';
	$out .= '</div>';
	$out .= '<div class="scp-icon-bullet-text-text pad-left">';

	if ( (int) $subtitle_is_above ) {
		$out .= $subtitle ? $subtitleTagOpen . html_entity_decode( $subtitle ) . $subtitleTagClose : '';
		$out .= $title ? $titleTagOpen . html_entity_decode( $title ) . $titleTagClose : '';
	} else {
		$out .= $title ? $titleTagOpen . html_entity_decode( $title ) . $titleTagClose : '';
		$out .= $subtitle ? $subtitleTagOpen . html_entity_decode( $subtitle ) . $subtitleTagClose : '';
	}

	$out .= $content ? $descriptionTagOpen . html_entity_decode( $content ) . $descriptionTagClose : '';
	$out .= '</div>';
	$out .= '</div>';

	return $out;
}

function scp_icon_shortcode_css( $atts ) {
	$uid = SCP_Assets::get_uid( 'scp_icon', $atts );

	extract( shortcode_atts( array(
		'size'        => '24px',
		'color'       => '',
		'hover_color' => '',
		'float'       => 'right',
		'margin'      => '',
		'line_height' => '',
		'bg_color'    => '',
		'bg_width'    => '',
	), $atts ) );

	$css = '';

	/**
	 * Background Style
	 */
	$bg_style = '';
	if ( $bg_color ) {
		$bg_style .= "background-color:{$bg_color};";
	}
	if ( $bg_width ) {
		$bg_style .= "width:{$bg_width};";
	}
	if ( $margin ) {
		$bg_style .= "margin:{$margin};";
	}
	if ( $bg_style ) {
		$bg_style .= 'text-align:center;';
		$css .= ".{$uid} .scp-icon-background{{$bg_style}}";
	}

	/**
	 * Icon Style
	 */
	$icon_style = '';
	if ( $color ) {
		$icon_style .= "color:{$color};";
	}
	if ( $size ) {
		$icon_style .= "font-size:{$size};";
	}
	if ( $line_height ) {
		$icon_style .= "line-height:{$line_height};";
	}
	if ( $icon_style ) {
		$css .= ".{$uid} i{{$icon_style}}";
	}

	/**
	 * Icon Style Hover
	 */
	if ( $hover_color ) {
		$css .= ".{$uid} i:hover{color:{$hover_color}}";
	}

	if ( $css ) {
		wp_add_inline_style( 'superwise_options_style', $css );
	}
}

/**
 *  [scp_icon icon="fa-twitter" link="absolute url" size="20px" color="#fff" hover_color="#fff" float="right" margin="0 5px"]
 */
function scp_icon_shortcode( $atts ) {

	$uid = SCP_Assets::get_uid( 'scp_icon', $atts );

	extract( shortcode_atts( array(
		'link'        => '#',
		'icon'        => '',
		'size'        => '24px',
		'color'       => '',
		'hover_color' => '',
		'float'       => 'right',
		'margin'      => '',
		'line_height' => '',
		'bg_color'    => '',
		'bg_width'    => '',
	), $atts ) );

	if ( $float == 'right' ) {
		$float = 'pull-right';
	} elseif ( $float == 'left' ) {
		$float = 'pull-left';
	} else {
		$float = '';
	}

	$wrapper_class = "scp-icon scp-icon-background {$float} {$uid}";

	$link_open = '';
	$link_close = '';
	if ( $link ) {
		$link_open = '<a href="' . esc_attr( $link ) . '" target="_blank">';
		$link_close = '</a> ';
	}

	$out = '<span class="' . esc_attr( $wrapper_class ) . '">';
	$out .= $link_open . '<i class="' . esc_attr( $icon ) . '"></i>' . $link_close;
	$out .= '</span>';

	return $out;
}

function scp_separator_shortcode_css( $atts ) {
	$uid = SCP_Assets::get_uid( 'scp_separator', $atts );

	extract( shortcode_atts( array(
		'type'           => 'horizontal',
		'width'          => '1px',
		'height'         => '50px',
		'color'          => '#000',
		'margin'         => '20px',
		'float'          => 'left',
	), $atts ) );

	$css = '';

	if ( $width ) {
		$css .= "width:{$width};";
	}
	if ( $height ) {
		$css .= "height:{$height};";
	}
	if ( $color ) {
		$css .= "background-color:{$color};";
	}
	if ( $margin ) {
		$css .= "margin:{$margin};";
	}
	if ( $float ) {
		$css .= "float:{$float};";
	}
	if ( $css ) {
		wp_add_inline_style( 'superwise_options_style', ".{$uid}.scp-shortcode-separator{{$css}}" );
	}
}

/**
 *  [scp_separator type="vertical"]
 */
function scp_separator_shortcode( $atts ) {
	$uid = SCP_Assets::get_uid( 'scp_separator', $atts );

	extract( shortcode_atts( array(
		'type'           => 'horizontal',
		'width'          => '1px',
		'height'         => '50px',
		'color'          => '#000',
		'margin'         => '20px',
		'float'          => 'left',
		'show_on_mobile' => 'no',
	), $atts ) );

	$class = "scp-shortcode-separator {$uid}";
	if ( $show_on_mobile == 'no' ) {
		$class .= ' hide-on-mobile hide-on-small-tablet';
	}

	$out = '<div class="' . esc_attr( $class ) . '"></div>';

	return $out;
}

function scp_block_quote_alt_shortcode_css( $atts ) {
	$uid = SCP_Assets::get_uid( 'scp_block_quote_alt', $atts );

	extract( shortcode_atts( array(
		'width' => '50%',
		'float' => 'right',
	), $atts ) );

	$css = '';

	if ( $width ) {
		$css .= "width:{$width};";
	}
	if ( $float ) {
		$float = $float == 'right' ? 'right' : 'left';
		$css .= "float:{$float};";
	}
	if ( $css ) {
		wp_add_inline_style( 'superwise_options_style', ".{$uid}.scp-block-quote-alt{{$css}}" );
	}
}

/**
 *  [scp_block_quote_alt width="50%" float="right"][/scp_block_quote_alt]
 */
function scp_block_quote_alt_shortcode( $atts, $content ) {

	$uid = SCP_Assets::get_uid( 'scp_block_quote_alt', $atts );

	extract( shortcode_atts( array(
		'width' => '50%',
		'float' => 'right',
	), $atts ) );

	$float = $float == 'right' ? 'right' : 'left';
	$class = "scp-block-quote-alt {$float} {$uid}";
	$out = '<div class="' . esc_attr( $class ) . '">' . do_shortcode( $content ) . '</div>';
	return $out;
}

function scp_table_row_shortcode( $atts ) {

	extract( shortcode_atts( array(
		'labels' => false,
		'class'  => '',
	), $atts ) );

	if ( ! $labels ) {
		return;
	}

	global $scp_table_shortcode_columns;
	global $scp_table_shortcode_column_highlight;
	$parts = explode( '|', $labels );
	?>
	<tr class="<?php echo esc_attr( $class ); ?>">
		<?php foreach ( $parts as $key => $part ) : ?>
			<?php $column_class = $key == $scp_table_shortcode_column_highlight - 1 ? 'column-highlight' : ''; ?>
			<td class="<?php echo esc_attr( $column_class ); ?>"  
				data-title="<?php echo isset( $scp_table_shortcode_columns[ $key ] ) ? trim( $scp_table_shortcode_columns[ $key ] ) : ''; ?>">
				<?php echo trim( $part ); ?>
			</td>
		<?php endforeach; ?>
	</tr>
	<?php
}

$scp_table_shortcode_columns          = array();
$scp_table_shortcode_column_highlight = false;

function scp_table_shortcode( $atts, $content ) {

	extract( shortcode_atts( array(
		'columns'   => false,
		'highlight' => false,
	), $atts ) );

	if ( ! $columns ) {
		return;
	}
	global $scp_table_shortcode_columns;
	global $scp_table_shortcode_column_highlight;
	$scp_table_shortcode_columns          = explode( '|', $columns );
	$scp_table_shortcode_column_highlight = (int) $highlight;

	?>
	<div class='rg-container'>
		<div class='rg-content'>
			<table class='rg-table'>
				<thead>
				<?php foreach ( $scp_table_shortcode_columns as $scp_table_shortcode_column ) : ?>
					<th><?php echo trim( $scp_table_shortcode_column ); ?></th>
				<?php endforeach; ?>
				</thead>
				<tbody>
				<?php echo do_shortcode( $content ); ?>
				</tbody>
			</table>
		</div>
	</div>
<?php
}
