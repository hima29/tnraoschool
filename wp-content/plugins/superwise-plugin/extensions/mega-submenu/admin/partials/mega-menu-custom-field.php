<?php

$mega_menu_id = get_post_meta($item->ID, Mega_Submenu::META_ID, true);

?>
<div class="menu-item-mega-menu description description-wide">
	<label for="edit-menu-item-mega-menu-<?php echo $item_id; ?>">
		<?php _e('Mega Menu:'); ?>
		<?php
		wp_dropdown_pages(array(
			'post_type' => Mega_Submenu::POST_TYPE,
			'selected' => $mega_menu_id,
			'show_option_none' => __('-- None --'),
			'name' => 'menu-item-mega-menu[' . $item_id . ']',
		));
		?><br />
		<span class="description"><?php _e('The mega menu to display where mega menus are enabled.'); ?></span>
	</label>
</div>