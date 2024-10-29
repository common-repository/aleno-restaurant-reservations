<?php
/**
 * Renderable class
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Common\Contracts;

/**
 * Interface used to create renderable models.
 *
 * @since 1.0.0
 */
interface Renderable {
	/**
	 * Renders a HTML presentation of an object.
	 *
	 * @since 1.0.0
	 */
	public function render();
}
