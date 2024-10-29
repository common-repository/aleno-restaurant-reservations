<?php
/**
 * DashboardPageView class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Admin;

use Required\Aleno\Common\Contracts\Renderable;

/**
 * Class used to implement welcome page view.
 *
 * @since 1.0.0
 */
class DashboardPageView implements Renderable {
	/**
	 * Returns the URL for the aleno logo.
	 *
	 * @since 1.0.0
	 *
	 * @return string Escaped logo URL.
	 */
	protected function get_logo_url() {
		return esc_url( plugin_dir_url( \Required\Aleno\PLUGIN_FILE ) . 'onboarding/images/aleno-logo-black.png' );
	}

	/**
	 * Renders the admin page.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		?>
		<div class="wrap">
			<div class="aleno-dashboard card">
				<header class="aleno-dashboard-header">
					<a href="<?php echo esc_url( __( 'https://www.aleno.me', 'aleno-restaurant-reservations' ) ); ?>">
						<img src="<?php echo $this->get_logo_url(); ?>" alt="<?php esc_attr_e( 'aleno', 'aleno-restaurant-reservations' ); ?>"/>
					</a>
				</header>
				<section class="aleno-dashboard-launch">
					<p class="aleno-dashboard-launch-intro">
						<?php _e( 'aleno is successfully set up and connected to your WordPress site.', 'aleno-restaurant-reservations' ); ?>
					</p>
					<p>
						<span class="spinner"></span>
						<button type="button" class="hide-if-no-js button-primary aleno-dashboard-launch-button" target="_blank"><?php _e( 'Launch aleno', 'aleno-restaurant-reservations' ); ?></button>
					</p>

					<div class="notice notice-error hidden">
						<p>
							<?php _e( 'There was an error retrieving the login link.', 'aleno-restaurant-reservations' ); ?>
						</p>
					</div>
				</section>
			</div>
		</div>
		<?php
	}
}
