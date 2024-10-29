<?php
/**
 * LinkMetaBoxView class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Admin;

use Required\Aleno\Common\Contracts\Renderable;

/**
 * Class used to implement custom link meta box view.
 *
 * @since 1.0.0
 */
class LinkMetaBoxView implements Renderable {
	/**
	 * Renders the meta box.
	 *
	 * @todo Make it work without JS by using $_nav_menu_placeholder and parsing the data on form submission.
	 *
	 * @since 1.0.0
	 *
	 * @global int        $_nav_menu_placeholder
	 * @global int|string $nav_menu_selected_id
	 */
	public function render() {
		global $_nav_menu_placeholder;

		$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;
		?>
		<div id="aleno-booking-link-wrap" class="hide-if-no-js">
			<input type="hidden" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" value="custom" />
			<p class="aleno-booking-link-item-wrap" class="wp-clearfix">
				<label for="aleno-booking-link-title"><?php _e( 'Title', 'aleno-restaurant-reservations' ); ?></label>
				<input id="aleno-booking-link-title" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]" type="text" class="regular-text">
			</p>
			<p class="button-controls">
				<span class="add-to-menu">
					<input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'aleno-restaurant-reservations' ); ?>" name="add-aleno-booking-link-item" id="submit-aleno-booking-link-add"/>
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}
}
