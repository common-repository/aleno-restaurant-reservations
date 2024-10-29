<?php
/**
 * Shortcode class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Common\FrontEnd;

use Required\Aleno\Common\Registrable;

/**
 * Class used to add shortcodes.
 *
 * @since 1.0.0
 */
class Shortcode implements Registrable {
	/**
	 * @since 1.0.0
	 *
	 * @var string Shortcode tag.
	 */
	protected $tag;

	/**
	 * @since 1.0.0
	 *
	 * @var ShortcodeView Shortcode callback view.
	 */
	protected $view;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string        $tag  Shortcode tag name.
	 * @param ShortcodeView $view Shortcode callback
	 */
	public function __construct( $tag, ShortcodeView $view ) {
		$this->tag = $tag;
		$this->view = $view;
	}

	/**
	 * Registers the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_shortcode( $this->tag, [ $this->view, 'parse' ] );
	}

	/**
	 * Registers the Shortcode UI setup for the shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Shortcode UI args.
	 */
	public function register_ui( $args ) {
		if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
			shortcode_ui_register_for_shortcode( $this->tag, $args );
		}
	}
}
