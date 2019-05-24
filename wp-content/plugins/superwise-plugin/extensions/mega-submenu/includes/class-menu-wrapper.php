<?php

/**
 * @since 1.1.0
 */
class MSM_Menu_Wrapper {

	protected $tag_open = '';
	protected $tag_close = '';
	protected $tag_mobile_open = '';
	protected $tag_mobile_close = '';

	/**
	 * @since 1.1.0
	 */
	public function init() {
		$this->prepare_tags();
	}

	/**
	 * @since 1.1.0
	 */
	public function prepare_tags() {

		$string = msm_get_option( 'menu-wrapper', '' );

		if ( $string ) {
			$parts = explode( '|', $string );
			if ( count( $parts ) == 2 ) {
				$this->tag_open =$parts[0];
				$this->tag_close =$parts[1];
			}
		}

		$string = msm_get_option( 'mobile-menu-wrapper', '<ul class="sub-menu"><li>|</li></ul>' );
		if ( $string ) {
			$parts = explode( '|', $string );
			if ( count( $parts ) == 2 ) {
				$this->tag_mobile_open = $parts[0];
				$this->tag_mobile_close = $parts[1];
			}
		}
	}

	/**
	 * @since 1.1.0
	 */
	public function filter_submenu_before( $before, $menu_location ) {
		if ( Mega_Submenu::$in_mobile_nav || msm_get_menu_location_theme_mobile() == $menu_location) {
			return $this->tag_mobile_open;
		} elseif ( $this->tag_open ) {
			return $this->tag_open;
		}

		return $before;
	}

	/**
	 * @since 1.1.0
	 */
	public function filter_submenu_after( $after, $menu_location ) {
		if ( Mega_Submenu::$in_mobile_nav || msm_get_menu_location_theme_mobile() == $menu_location ) {
			return $this->tag_mobile_close;
		} elseif ( $this->tag_close ) {
			return $this->tag_close;
		}

		return $after;
	}


}
