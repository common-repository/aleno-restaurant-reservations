<?php
/**
 * NullPageView class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Admin;

use Required\Aleno\Common\Contracts\Renderable;

/**
 * Class used to implement an empty page view.
 *
 * @since 1.0.0
 */
class NullPageView implements Renderable {
	/**
	 * Renders the admin page.
	 *
	 * @since 1.0.0
	 */
	public function render() {}
}
