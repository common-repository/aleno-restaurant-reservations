<?php
/**
 * MetaBox class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Common\Admin;

use Required\Aleno\Common\Contracts\Renderable;
use Required\Aleno\Common\Registrable;
use WP_Screen;

/**
 * Class used to register meta boxes.
 *
 * @since 1.0.0
 */
class MetaBox implements Registrable {
	/**
	 * The meta box ID.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $id;

	/**
	 * The meta box title.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $title;

	/**
	 * The view to render the help tab content.
	 *
	 * @since 1.0.0
	 *
	 * @var Renderable
	 */
	private $view;

	/**
	 * Screen to show the meta box on.
	 *
	 * @since 1.0.0
	 *
	 * @var string|array|WP_Screen
	 */
	private $screen;

	/**
	 * Meta box context.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $context;

	/**
	 * The priority of the tab.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $priority;

	/**
	 * Additional args for the meta box view.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $args;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @see add_meta_box()
	 *
	 * @param string                 $id            Meta box ID.
	 * @param string                 $title    Title of the meta box.
	 * @param Renderable             $view     Meta box view that should echo its output.
	 * @param c $screen   Optional. The screen or screens on which to show the box.
	 *                                         Default is the current screen.
	 * @param string                 $context  Optional. The context within the screen where the boxes
	 *                                         should display. Default is 'advanced'.
	 * @param string                 $priority Optional. The priority within the context where the boxes
	 *                                         should show ('high', 'low'). Default 'default'.
	 * @param array                  $args     Optional. Data that should be set as the $args property
	 *                                         of the box array. Default null.
	 */
	public function __construct( $id, $title, Renderable $view = null, $screen = null, $context = 'advanced', $priority = 'default', $args = null ) {
		$this->id       = $id;
		$this->title    = $title;
		$this->view     = $view;
		$this->screen   = $screen;
		$this->context  = $context;
		$this->priority = $priority;
		$this->args     = $args;
	}

	/**
	 * Registers the meta box.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_meta_box(
			$this->id,
			$this->title,
			[ $this->view, 'render' ],
			$this->screen,
			$this->context,
			$this->priority,
			$this->args
		);
	}
}
