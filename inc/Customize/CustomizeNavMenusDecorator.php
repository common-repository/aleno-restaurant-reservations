<?php
/**
 * @package Required\Aleno
 */

namespace Required\Aleno\Customize;

use Required\Aleno\Common\Registrable;
use WP_Customize_Manager;
use WP_Customize_Nav_Menus;

class CustomizeNavMenusDecorator implements Registrable {
	/**
	 * @since 1.0.0
	 *
	 * @var WP_Customize_Nav_Menus
	 */
	protected $nav_menus;

	/**
	 * WP_Customize_Manager instance.
	 *
	 * @since 1.0.0
	 *
	 * @var WP_Customize_Manager
	 */
	protected $manager;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $manager WP_Customize_Manager instance.
	 */
	public function __construct( $manager ) {
		$this->manager = $manager;
	}

	/**
	 * Registers the original implementation together with the custom additions.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		remove_action( 'customize_controls_print_footer_scripts', [ $this->manager->nav_menus, 'available_items_template' ] );
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'available_items_template' ] );

		add_action( 'customize_controls_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueues scripts and styles for Customizer pane.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'aleno-customize' );
		wp_enqueue_style( 'aleno-customize' );

		wp_localize_script( 'aleno-customize', 'alenoSettings', [
			'data' => [
				'url' => esc_url( __( 'https://www.aleno.me', 'aleno-restaurant-reservations' ) ),
			],
			'l10n'  => [
				'label' => __( 'Booking Link', 'aleno-restaurant-reservations' ),
			],
		] );
	}

	/**
	 * Prints the html template used to render the add-menu-item frame.
	 *
	 * @since 1.0.0
	 */
	public function available_items_template() {
		ob_start();

		$this->manager->nav_menus->available_items_template();

		$template = ob_get_clean();

		echo str_replace( '</div><!-- #available-menu-items -->', $this->get_booking_links_available_menu_item() . '</div><!-- #available-menu-items -->', $template );
	}

	/**
	 * Returns the markup for aleno booking links.
	 *
	 * @since 1.0.0
	 *
	 * @return string HTML markup.
	 */
	protected function get_booking_links_available_menu_item() {
		ob_start();
		?>
		<div id="new-aleno-menu-item" class="accordion-section">
			<h4 class="accordion-section-title" role="presentation">
				<?php _e( 'Aleno Booking Link', 'aleno-restaurant-reservations' ); ?>
				<button type="button" class="button-link" aria-expanded="false">
					<span class="screen-reader-text"><?php _e( 'Toggle section: Aleno Booking Link', 'aleno-restaurant-reservations' ); ?></span>
					<span class="toggle-indicator" aria-hidden="true"></span>
				</button>
			</h4>
			<div class="accordion-section-content aleno-booking-link">
				<input type="hidden" name="menu-item[-2][menu-item-type]" value="custom" />
				<p class="aleno-booking-link-item-wrap wp-clearfix">
					<label for="aleno-booking-link-title" class="howto"><?php _e( 'Title', 'aleno-restaurant-reservations' ); ?></label>
					<input id="aleno-booking-link-title" name="menu-item[-2][menu-item-title]" type="text" class="regular-text">
				</p>
				<p class="button-controls">
					<span class="add-to-menu">
						<input type="submit" class="button submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'aleno-restaurant-reservations' ); ?>" name="add-aleno-booking-link-item" id="submit-aleno-booking-link-add">
						<span class="spinner"></span>
					</span>
				</p>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
