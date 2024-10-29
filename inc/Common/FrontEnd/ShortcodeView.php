<?php
/**
 * ShortcodeCallback class
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Common\FrontEnd;
use Required\Aleno\Common\Contracts\Renderable;

/**
 * Interface used to add shortcode callbacks.
 *
 * @since 1.0.0
 */
abstract class ShortcodeView implements Renderable {
	/**
	 * @since 1.0.0
	 *
	 * @var array Allowed shortcode arguments.
	 */
	protected $pairs;

	/**
	 * @since 1.0.0
	 *
	 * @var array Passed shortcode args.
	 */
	protected $args;

	/**
	 * @since 1.0.0
	 *
	 * @var string The enclosed content.
	 */
	protected $content;

	/**
	 * @since 1.0.0
	 *
	 * @var string The shortcode tag the view is used on.
	 */
	protected $tag;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param array $pairs Array of allowed shortcode attributes and their default values.
	 */
	public function __construct( array $pairs = array() ) {
		$this->pairs = $pairs;
	}

	/**
	 * Renders a shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param array|string $atts    Shortcode attributes if there are any.
	 * @param string       $content The enclosed content.
	 * @param string       $tag
	 */
	public function parse( $atts, $content, $tag ) {
		$this->args = shortcode_atts( $this->pairs, $atts, $tag );
		$this->content = $content;

		return $this->render();
	}
}
