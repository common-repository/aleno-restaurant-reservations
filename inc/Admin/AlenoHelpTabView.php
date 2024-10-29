<?php
/**
 * AlenoHelpTabView class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Admin;

use Required\Aleno\Common\Contracts\Renderable;

/**
 * Class used to implement the general aleno help tab view.
 *
 * @since 1.0.0
 */
class AlenoHelpTabView implements Renderable {
	/**
	 * Renders the admin page.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		?>
		<p><?php _e( 'Need some help?', 'aleno-restaurant-reservations' ); ?></p>
		<?php
	}
}
