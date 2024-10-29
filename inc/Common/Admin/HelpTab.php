<?php
/**
 * HelpTab class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Common\Admin;

use Required\Aleno\Common\Contracts\Renderable;
use Required\Aleno\Common\Registrable;

/**
 * Class used to register help tabs.
 *
 * @since 1.0.0
 */
class HelpTab implements Registrable {
	/**
	 * The help tab title.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $title;

	/**
	 * The help tab ID.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $id;

	/**
	 * Help tab content in plain text or HTML.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $content;

	/**
	 * The view to render the help tab content.
	 *
	 * @since 1.0.0
	 *
	 * @var Renderable
	 */
	private $view;

	/**
	 * The priority of the tab.
	 *
	 * @since 1.0.0
	 *
	 * @var null|int
	 */
	private $priority;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string     $title    Title for the tab.
	 * @param string     $id       Tab ID. Must be HTML-safe.
	 * @param string     $content  Optional. Help tab content in plain text or HTML. Default empty string..
	 * @param Renderable $view     The view to render the help tab content.
	 * @param int        $priority Optional. The priority of the tab, used for ordering.
	 */
	public function __construct( $title, $id, $content = '', Renderable $view = null, $priority = 10 ) {
		$this->title    = $title;
		$this->id       = $id;
		$this->content  = $content;
		$this->view     = $view;
		$this->priority = $priority;
	}

	/**
	 * Registers the help tab.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether the help tab was registered successfully.
	 */
	public function register() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return false;
		}

		$screen->add_help_tab( [
			'title'    => $this->title,
			'id'       => $this->id,
			'content'  => $this->content,
			'callback' => [ $this->view, 'render' ],
			'priority' => $this->priority,
		]);

		return true;
	}
}
