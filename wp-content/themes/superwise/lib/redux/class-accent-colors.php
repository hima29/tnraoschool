<?php

class Ed_Suite_Accent_Colors {

	public static function get_items() {

		return array(
			// Background Color
			'background_accent_color'    => array(
				'id'       => 'background_accent_color',
				'type'     => 'bg_color',
				'name'     => 'Background Accent Color',
				'selector' => '.wh-background-accent-color',
			),
			'header_mesage_row'          => array(
				'id'       => 'header_mesage_row',
				'type'     => 'bg_color',
				'name'     => 'Header Message Row',
				'selector' => '.header-mesage-row',
			),
			'custom_vc_separator'        => array(
				'id'       => 'custom_vc_separator',
				'type'     => 'bg_color',
				'name'     => 'Custom VC Separator',
				'selector' => '.wh-vc-separator:before',
			),
			'quick_sidebar_close'        => array(
				'id'       => 'quick_sidebar_close',
				'type'     => 'bg_color',
				'name'     => 'Quick Sidebar Close',
				'selector' => '.wh-quick-sidebar .wh-close',
			),
			'minicart_count'             => array(
				'id'       => 'minicart_count',
				'type'     => 'bg_color',
				'name'     => 'WC Mini Cart Count',
				'selector' => '.wh-minicart .count',
			),
			'woocommerce_onsale'         => array(
				'id'       => 'woocommerce_onsale',
				'type'     => 'bg_color',
				'name'     => 'WC On Sale Label',
				'selector' => '.woocommerce span.onsale',
			),
			'woocommerce_remove'         => array(
				'id'       => 'woocommerce_remove',
				'type'     => 'bg_color',
				'name'     => 'WC Remove',
				'selector' => '.woocommerce a.remove:hover',
			),
			'menu_label'                 => array(
				'id'       => 'menu_label',
				'type'     => 'bg_color',
				'name'     => 'Menu Label',
				'selector' => '.sf-menu .label, .respmenu .label',
			),
			'widget_banner'              => array(
				'id'       => 'widget_banner',
				'type'     => 'bg_color',
				'name'     => 'Widget - Banner (Bg)',
				'selector' => '.widget-banner',
			),
			'widget_banner_label'        => array(
				'id'       => 'widget_banner_label',
				'type'     => 'bg_color',
				'name'     => 'Widget - Banner (Label)',
				'selector' => '.widget-banner .label, .wh-title-with-label b',
			),
			'testimonial_quote_bg'       => array(
				'id'       => 'testimonial_quote_bg',
				'type'     => 'bg_color',
				'name'     => 'Testimonial Quote Bg',
				'selector' => '.testimonial_rotator_wrap .testimonial_rotator .quote-icon, .testimonial_rotator_widget_wrap .testimonial_rotator .quote-icon',
			),
			'vc_addon_tribe_events_date_bg'       => array(
				'id'       => 'vc_addon_tribe_events_date',
				'type'     => 'bg_color',
				'name'     => 'Tribe Events Addon Date Bg',
				'selector' => '.scp-tribe-events .event .date',
			),
			// Border Color
			'quick_sidebar_hr'           => array(
				'id'       => 'quick_sidebar_hr',
				'type'     => 'border_color',
				'name'     => 'Quick Sidebar Hamburger',
				'selector' => '.wh-quick-sidebar hr',
			),
			// Border Color Left
			'blockquote'                 => array(
				'id'       => 'blockquote',
				'type'     => 'border_left_color',
				'name'     => 'Blockquote',
				'selector' => 'blockquote',
			),
			'blockquote_alt'             => array(
				'id'       => 'blockquote_alt',
				'type'     => 'border_left_color',
				'name'     => 'Blockquote Alt',
				'selector' => '.scp-block-quote-alt',
			),
			'child_page_sidebar'         => array(
				'id'       => 'child_page_sidebar',
				'type'     => 'border_left_color',
				'name'     => 'Child Page Sidebar',
				'selector' => '.children-links ul li.current_page_item, .children-links ul li:hover',
			),
			// Border Top Color
			'main_menu_hover_border_top' => array(
				'id'       => 'main_menu_hover_border_top',
				'type'     => 'border_top_color',
				'name'     => 'Menu Hover Border',
				'selector' => '.sf-menu.wh-menu-main > li:hover > a, .sf-menu.wh-menu-main > li.sfHover > a',
			),
			// Color
			'accent_color'               => array(
				'id'       => 'accent_color',
				'type'     => 'color',
				'name'     => 'Accent Color',
				'selector' => '.wh-accent-color',
			),
			'tribe_events_link'          => array(
				'id'       => 'tribe_events_link',
				'type'     => 'color',
				'name'     => 'Tribe Events Link',
				'selector' => '.scp-tribe-events-link a, .scp-tribe-events-link',
			),
			'theme_icon'                 => array(
				'id'       => 'theme_icon',
				'type'     => 'color',
				'name'     => 'Theme Icon',
				'selector' => '.wh-theme-icon',
			),
			'entry_meta_icon'            => array(
				'id'       => 'entry_meta_icon',
				'type'     => 'color',
				'name'     => 'Entry Meta Icon',
				'selector' => '.entry-meta i',
			),
			'teacher_meta_data_icon'     => array(
				'id'       => 'teacher_meta_data_icon',
				'type'     => 'color',
				'name'     => 'Teacher Meta Icon',
				'selector' => '.teacher-meta-data i',
			),
			'recent_tweets_icon'         => array(
				'id'       => 'recent_tweets_icon',
				'type'     => 'color',
				'name'     => 'Recent Tweets Icon',
				'selector' => '.tl-recent-tweets i',
			),
			'prev_next_post_labels'      => array(
				'id'       => 'prev_next_post_labels',
				'type'     => 'color',
				'name'     => 'Prev/Next Post Labels',
				'selector' => '.left-cell .label, .right-cell .label',
			),
			'vc_accordion_title_active'  => array(
				'id'       => 'vc_accordion_title_active',
				'type'     => 'color',
				'name'     => 'VC Accordion Title Active',
				'selector' => '.vc_tta.vc_general .vc_active .vc_tta-panel-title > a',
			),
			'widget_contact_info_icons'  => array(
				'id'       => 'widget_contact_info_icons',
				'type'     => 'color',
				'name'     => 'Widget Contact Info Icons',
				'selector' => '.widget.widget-contact-info ul li i',
			),
			'testimonial_signature'      => array(
				'id'       => 'testimonial_signature',
				'type'     => 'color',
				'name'     => 'Testimonial Signature',
				'selector' => '.testimonial_rotator.template-default .testimonial_rotator_author_info p',
			),
			'vc_addon_schedule_hover'    => array(
				'id'       => 'vc_addon_schedule_hover',
				'type'     => 'color',
				'name'     => 'Schedule Hover',
				'selector' => '.schedule li:hover span',
			),
			'custom_bullet'              => array(
				'id'       => 'custom_bullet',
				'type'     => 'color',
				'name'     => 'Custom Bullet',
				'selector' => '.bullet-before:before',
			),
		);
	}

	public static function get_redux_select_options() {
		$select_options = array();
		foreach ( self::get_items() as $id => $item ) {
			$select_options[ $id ] = $item['name'];
		}

		return $select_options;
	}

	public static function get_pipe_separated_list( $separator = "\n") {
		$list = array();
		foreach ( self::get_items() as $id => $item ) {
			$list[] = $item['id'];
		}

		if ( count( $list ) ) {
			sort($list);
			$list = implode( $separator, $list );
		}

		return $list;
	}

	public static function get_item( $id ) {
		$items = self::get_items();

		if ( isset( $items[ $id ] ) ) {
			return $items[ $id ];
		}

		return false;
	}
}