<?php
/**
 * SetupPageView class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Admin;

use Required\Aleno\Common\Contracts\Renderable;

/**
 * Class used to implement setup page view.
 *
 * @since 1.0.0
 */
class SetupPageView implements Renderable {
	/**
	 * Renders the admin page.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		?>
		<div class="wrap">
			<div id="root"></div>
		</div>
		<?php
	}
}
