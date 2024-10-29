<?php
/**
 * HelpPageView class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Admin;

use Required\Aleno\Common\Contracts\Renderable;

/**
 * Class used to implement usage page view.
 *
 * @since 1.0.0
 */
class HelpPageView implements Renderable {
	/**
	 * Returns the URL for resetting the whole plugin to its initial state.
	 *
	 * @since 1.0.0
	 *
	 * @return string Plugin reset URL.
	 */
	public function get_reset_url() {
		return esc_url( add_query_arg( [
			'aleno_reset' => 1,
			'aleno_nonce' => wp_create_nonce( 'aleno_reset' ),
		] ) );
	}

	/**
	 * Renders the admin page.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<div class="aleno-help-page">
				<div class="card">
					<h2><?php _e( 'Shortcode', 'aleno-restaurant-reservations' ); ?></h2>
					<p>
						<?php
						/* translators: [aleno_widget] shortcode */
						printf( __( 'You can use the %s shortcode to insert a booking button anywhere in a post or page.', 'aleno-restaurant-reservations' ), '<code>[aleno_widget]</code>'
						);
						?>
					</p>
					<p>
						<?php _e( 'The text enclosed in the shortcode can be used to change the button\'s text. Here is an example:', 'aleno-restaurant-reservations' ); ?>
					</p>
					<p>
						<pre class="pre"><?php _e( "Some text\n\n[aleno_widget /]\n\nSome text\n\n[aleno_widget]Don't miss this offer[/aleno_widget]", 'aleno-restaurant-reservations' ); ?></pre>
					</p>
					<?php if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) : ?>
						<p>
							<?php
							printf(
								/* translators: %s: Shortcode UI */
								__( '<b>Note:</b> We recommend using the %s plugin for an improved experience when inserting shortcodes.', 'aleno-restaurant-reservations' ),
								sprintf( '<a href="%s">%s</a>',
									__( 'https://wordpress.org/plugins/shortcode-ui/', 'aleno-restaurant-reservations' ),
									__( 'Shortcode UI', 'aleno-restaurant-reservations' )
								)
							);
							?>
						</p>
					<?php endif; ?>
				</div>
				<div class="card">
					<h2><?php _e( 'Custom Menu', 'aleno-restaurant-reservations' ); ?></h2>
					<p>
						<?php _e( 'You can also easily add the aleno booking button to a custom nav menu after you have set up your account.', 'aleno-restaurant-reservations' ); ?>
					</p>
				</div>
				<div class="card">
					<h2><?php _e( 'Debugging', 'aleno-restaurant-reservations' ); ?></h2>
					<p>
						<?php
						_e( 'In case you are experiencing problems with the aleno plugin, here is some helpful debugging information you could provide to our support team.', 'aleno-restaurant-reservations' );
						?>
					</p>
					<?php
					$data = [
						'API Key'           => get_option( 'aleno_key', '""' ),
						'Restaurant ID'     => get_option( 'aleno_restaurant_id', '""' ),
						'User ID'           => get_option( 'aleno_user_id', '""' ),
						'WordPress Version' => $GLOBALS['wp_version'],
						'PHP Version'       => PHP_VERSION,
						'MySQL Version'     => $GLOBALS['wpdb']->db_version(),
					];
					?>
					<pre><?php echo esc_html( print_r( $data, true ) ); ?></pre>
				</div>
				<div class="card">
					<h2><?php _e( 'Uninstall', 'aleno-restaurant-reservations' ); ?></h2>
					<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="aleno_uninstall">
						<?php wp_nonce_field( 'aleno_uninstall', 'aleno_nonce' ); ?>
						<?php
						$should_uninstall = get_option( 'aleno_uninstall', false );
						?>
						<p>
							<input type="checkbox" value="true" name="aleno_uninstall" id="aleno_should_uninstall" <?php checked( $should_uninstall ); ?>>
							<label for="aleno_should_uninstall"><?php _e( 'Delete aleno account and settings when deleting this plugin.', 'aleno-restaurant-reservations' ); ?></label>
						</p>
						<p>
							<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Submit', 'aleno-restaurant-reservations' ); ?>">
						</p>
					</form>
					<p>
						<?php _e( 'You can also choose to delete these settings now to reset the plugin to its initial state.', 'aleno-restaurant-reservations' ); ?>
					</p>
					<p>
						<?php _e( 'Attention: this will delete your restaurant and user data on aleno.me!', 'aleno-restaurant-reservations' ); ?>
					</p>
					<p>
						<a href="<?php echo $this->get_reset_url(); ?>" class="button button-secondary"><?php _e( 'Reset settings', 'aleno-restaurant-reservations' ); ?></a>
					</p>
				</div>
			</div>
		</div>
		<?php
	}
}
