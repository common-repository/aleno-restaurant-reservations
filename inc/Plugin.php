<?php
/**
 * @package Required\Aleno
 */

namespace Required\Aleno;

use Required\Aleno\Admin\AlenoHelpTabView;
use Required\Aleno\Admin\LinkMetaBoxView;
use Required\Aleno\Admin\NullPageView;
use Required\Aleno\Admin\SetupPageView;
use Required\Aleno\Admin\HelpPageView;
use Required\Aleno\Admin\DashboardPageView;
use Required\Aleno\Common\Admin\HelpTab;
use Required\Aleno\Common\Admin\MetaBox;
use Required\Aleno\Common\Admin\Page;
use Required\Aleno\Common\FrontEnd\Shortcode;
use Required\Aleno\Customize\CustomizeNavMenusDecorator;
use Required\Aleno\FrontEnd\BookingShortcodeView;
use Required\Aleno\Misc\Exception;
use Required\Aleno\Misc\PublicKeyProvider;
use Required\Aleno\Misc\Uninstaller;
use stdClass;
use WP_Customize_Manager;
use WP_Post;

class Plugin {
	/**
	 * Aleno API server key.
	 *
	 * @since 1.0.0
	 */
	const ALENO_SERVER_KEY = 'kk3axl9lip1zto6r0tkj27tad5zv9529geas93c6s2z6ko6r';

	/**
	 * Aleno widget URL.
	 *
	 * @since 1.0.0
	 */
	const ALENO_WIDGET_URL = 'https://mytools.aleno.me/reservations/v2.0/reservations.html';

	/**
	 * Google Fonts API key.
	 *
	 * @since 1.0.0
	 */
	const GOOGLE_API_KEY = 'AIzaSyAbIKV6piu-8Jsdt3QS-i-8iqWoqj22FNA';

	/**
	 * Registers all the needed hooks.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$this->register_hooks();

		if ( is_admin() ) {
			$this->register_admin_hooks();
		}
	}

	/**
	 * Registers actions and filters.
	 *
	 * @since 1.0.0
	 */
	public function register_hooks() {
		// General.
		add_action( 'init', [ $this, 'register_settings' ] );
		add_action( 'init', [ $this, 'register_shortcodes' ] );

		add_action( 'removable_query_args', [ $this, 'removable_query_args' ] );

		// Scripts.
		add_action( 'init', [ $this, 'register_scripts' ] );

		// Nav Menus.
		add_filter( 'nav_menu_link_attributes', [ $this, 'nav_menu_link_attributes' ], 10, 2 );
		add_filter( 'wp_setup_nav_menu_item', [ $this, 'wp_setup_nav_menu_item' ] );

		// Customize.
		add_action( 'customize_register', [ $this, 'register_customize_nav_menus' ] );

		// Settings.
		add_action( 'admin_post_aleno_uninstall', [ $this, 'save_uninstall_option' ] );
	}

	/**
	 * Registers actions and filters in the admin.
	 *
	 * @since 1.0.0
	 */
	public function register_admin_hooks() {
		add_action( 'plugin_action_links_' . PLUGIN_BASENAME, [
			$this,
			'plugin_action_links',
		] );

		$wrapper_page = new Page(
			Page::ADMIN,
			__( 'aleno', 'aleno-restaurant-reservations' ),
			__( 'aleno', 'aleno-restaurant-reservations' ),
			'manage_options',
			'aleno',
			new NullPageView(),
			'',
			'data:image/svg+xml;base64,' . base64_encode( '<svg id="aleno-menu-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="16" viewBox="0 0 38 30"><path fill="black" d="M28.78 26.026L15.755 3.472h4.005l13.026 22.55-4.006.004zm-16.026-6.94l4.004-6.937 4.006 6.934h-8.01zm4.006 6.94H8.748l2.003-3.47h8.015l-2.003 3.47zm-4.01-20.82l2.004 3.473-9.012 15.61-2.003-3.47 9.01-15.615zM37.294 26.89L22.264.87C21.954.33 21.38 0 20.76 0h-8.02c-.425.002-.815.158-1.116.414-.076.064-.146.136-.21.213-.07.084-.132.174-.185.27l-11 19.057c-.31.537-.31 1.198 0 1.735l4.006 6.94c.31.535.882.865 1.502.865h12.02c.62 0 1.192-.33 1.502-.868l3.504-6.072 3.505 6.07c.31.54.883.87 1.503.87l8.015-.005c.62 0 1.19-.33 1.5-.867.31-.537.31-1.2 0-1.736z"></path></svg>' ),
			75
		);

		$wrapper_page->register();

		if ( get_option( 'aleno_key' ) && get_option( 'aleno_user_id' ) ) {
			$dashboard_page = new Page(
				Page::ADMIN,
				__( 'aleno', 'aleno-restaurant-reservations' ),
				__( 'Dashboard', 'aleno-restaurant-reservations' ),
				'manage_options',
				'aleno',
				new DashboardPageView(),
				'aleno'
			);

			add_action( 'admin_enqueue_scripts', function( $hook_suffix ) use ( $dashboard_page ) {
				if ( $hook_suffix !== $dashboard_page->get_hook_name() ) {
					return;
				}

				wp_enqueue_script( 'aleno-dashboard' );
				wp_enqueue_style( 'aleno-admin' );

				wp_localize_script( 'aleno-dashboard', 'alenoSettings', [
					'data' => [
						'alenoUrl' => 'https://mytools.aleno.me/',
						'locale'   => strtok( get_locale(), '_' ),
						'userKey'  => get_option( 'aleno_key' ),
						'userId'   => get_option( 'aleno_user_id' ),
					],
				] );
			} );
		} else {
			$dashboard_page = new Page(
				Page::ADMIN,
				__( 'Set up your restaurant', 'aleno-restaurant-reservations' ),
				__( 'Setup', 'aleno-restaurant-reservations' ),
				'manage_options',
				'aleno',
				new SetupPageView(),
				'aleno'
			);

			add_action( 'admin_enqueue_scripts', function( $hook_suffix ) use ( $dashboard_page ) {
				if ( $hook_suffix !== $dashboard_page->get_hook_name() ) {
					return;
				}

				wp_enqueue_style( 'aleno-admin' );

				wp_enqueue_style(
					'aleno-onboarding',
					plugin_dir_url( PLUGIN_FILE ) . 'onboarding/index.css',
					[],
					'20171008'
				);

				// Needed for logo upload functionality.
				wp_enqueue_media();

				wp_enqueue_script(
					'aleno-onboarding',
					plugin_dir_url( PLUGIN_FILE ) . 'onboarding/index.js',
					[ 'jquery' ],
					'20171008',
					true
				);

				$custom_logo_url = wp_get_attachment_image_url( (int) get_theme_mod( 'custom_logo' ) );

				wp_localize_script( 'aleno-onboarding', 'alenoSettings', [
					'data'   => [
						'rootUrl'     => plugin_dir_url( PLUGIN_FILE ) . 'onboarding',
						'alenoUrl'    => 'https://mytools.aleno.me/',
						'email'       => get_option( 'admin_email' ),
						'defaultLang' => strtok( get_locale(), '_' ),
						'timezone'    => get_option( 'timezone_string' ),
						'gmt_offset'  => get_option( 'gmt_offset' ),
						'customLogo'  => $custom_logo_url ? esc_url( $custom_logo_url ) : '',
						'userId'      => get_option( 'aleno_user_id' ),
						'userKey'     => get_option( 'aleno_key' ),
						'serverKey'   => self::ALENO_SERVER_KEY,
						'googleKey'   => self::GOOGLE_API_KEY,
					],
					'api'    => [
						'root'          => esc_url_raw( get_rest_url() ),
						'nonce'         => wp_create_nonce( 'wp_rest' ),
						'versionString' => 'wp/v2/',
					],
					'wpL10n' => [
						'mediaTitle'       => __( 'Select or upload an image', 'aleno-restaurant-reservations' ),
						'mediaButtonLabel' => __( 'Select', 'aleno-restaurant-reservations' ),
					],
				] );
			} );
		}

		$dashboard_page->register();

		$help_page = new Page(
			Page::ADMIN,
			__( 'Help', 'aleno-restaurant-reservations' ),
			__( 'Help', 'aleno-restaurant-reservations' ),
			'manage_options',
			'aleno-help',
			new HelpPageView(),
			'aleno'
		);

		$help_page->register();

		add_action( 'admin_enqueue_scripts', function( $hook_suffix ) use ( $help_page ) {
			if ( $hook_suffix === $help_page->get_hook_name() ) {
				wp_enqueue_style( 'aleno-admin' );
			}
		} );

		add_action( 'admin_init', function() use ( $dashboard_page, $help_page ) {
			$aleno_tab = new HelpTab(
				__( 'Aleno', 'aleno-restaurant-reservations' ),
				'aleno-help',
				'',
				new AlenoHelpTabView()
			);

			add_action( "load-{$dashboard_page->get_hook_name()}", [ $aleno_tab, 'register' ] );
			add_action( "load-{$help_page->get_hook_name()}", [ $aleno_tab, 'register' ] );

			add_action( "load-{$help_page->get_hook_name()}", [ $this, 'maybe_reset_plugin' ] );
			add_action( "load-{$help_page->get_hook_name()}", [ $this, 'maybe_show_notice' ] );
		});

		add_action( 'admin_footer_text', function( $text ) use ( $dashboard_page, $help_page ) {
			$screen = get_current_screen();

			if ( ! $screen ) {
				return $text;
			}

			if ( ! in_array( $screen->id, [
				$dashboard_page->get_hook_name(),
				$help_page->get_hook_name(),
			], true )
			) {
				return $text;
			}

			return sprintf(
				/* translators: 1: aleno website URL, 2: aleno */
				'<span class="aleno-footer-note">' . __( 'Made with &hearts; by <a href="%1$s">%2$s</a>', 'aleno-restaurant-reservations' ) . '</span>',
				__( 'https://www.aleno.me', 'aleno-restaurant-reservations' ),
				__( 'aleno', 'aleno-restaurant-reservations' )
			);
		} );

		$restaurant_id = get_option( 'aleno_restaurant_id' );

		if ( ! empty( $restaurant_id ) ) {
			$nav_menu_meta_box = new MetaBox(
				'aleno-custom-link',
				__( 'Aleno Booking Link', 'aleno-restaurant-reservations' ),
				new LinkMetaBoxView(),
				null,
				'side',
				'default'
			);

			add_action( 'admin_head-nav-menus.php', [ $nav_menu_meta_box, 'register' ] );
			add_action( 'admin_print_scripts-nav-menus.php', function () {
				wp_enqueue_script( 'aleno-admin' );
				wp_enqueue_style( 'aleno-admin' );

				wp_localize_script( 'aleno-admin', 'alenoSettings', [
					'data' => [
						'url' => esc_url( __( 'https://www.aleno.me', 'aleno-restaurant-reservations' ) ),
					],
				] );
			} );
		}
	}

	/**
	 * Adds a Settings link to the plugin list table.
	 *
	 * @since 1.0.0
	 *
	 * @param array $action_links Plugin action links.
	 * @return array
	 */
	public function plugin_action_links( $action_links ) {
		return [ '<a href="' . menu_page_url( 'aleno', false ) . '">' . __( 'Settings', 'aleno-restaurant-reservations' ) . '</a>' ] + $action_links;
	}

	/**
	 * Registers public scripts and styles.
	 *
	 * These are needed for the reservations popup/modal.
	 *
	 * @since 1.0.0
	 */
	public function register_scripts() {
		$suffix = SCRIPT_DEBUG ? '' : '.min';

		wp_register_script(
			'aleno-reservations',
			'https://mytools.aleno.me/reservations/v2.0/reservations.js'
		);

		wp_register_style(
			'aleno-reservations',
			'https://mytools.aleno.me/reservations/v2.0/reservations.css'
		);

		wp_register_script(
			'aleno-admin',
			plugins_url( 'assets/js/admin' . $suffix . '.js', PLUGIN_FILE ),
			[ 'jquery', 'nav-menu' ],
			'20171008',
			true
		);

		wp_register_style(
			'aleno-admin',
			plugins_url( 'assets/css/admin.css', PLUGIN_FILE ),
			[],
			'20180117'
		);

		wp_register_script(
			'aleno-customize',
			plugins_url( 'assets/js/customize' . $suffix . '.js', PLUGIN_FILE ),
			[ 'jquery', 'customize-nav-menus' ],
			'20171008',
			true
		);

		wp_register_style(
			'aleno-customize',
			plugins_url( 'assets/css/customize.css', PLUGIN_FILE ),
			[ 'customize-nav-menus' ],
			'20171008'
		);

		wp_register_script(
			'aleno-dashboard',
			plugins_url( 'assets/js/dashboard' . $suffix . '.js', PLUGIN_FILE ),
			[ 'jquery' ],
			'20171009',
			true
		);
	}

	/**
	 * Registers various settings that are used by the plugin.
	 *
	 * Registered settings are available via the REST API and have
	 * sanitization callbacks attached to them automatically.
	 */
	public function register_settings() {
		register_setting( 'aleno-settings', 'aleno_key', [
			'type'              => 'string',
			'description'       => __( 'Aleno API Key', 'aleno-restaurant-reservations' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
		] );

		register_setting( 'aleno-settings', 'aleno_public_key', [
			'type'              => 'string',
			'description'       => __( 'Aleno Public Key', 'aleno-restaurant-reservations' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
		] );

		register_setting( 'aleno-settings', 'aleno_restaurant_id', [
			'type'              => 'string',
			'description'       => __( 'Aleno Restaurant ID', 'aleno-restaurant-reservations' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
		] );

		register_setting( 'aleno-settings', 'aleno_user_id', [
			'type'              => 'string',
			'description'       => __( 'Aleno User ID', 'aleno-restaurant-reservations' ),
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
			'show_in_rest'      => true,
		] );

		register_setting( 'aleno-settings', 'aleno_uninstall', [
			'type'              => 'boolean',
			'description'       => __( 'Aleno Uninstall option', 'aleno-restaurant-reservations' ),
			'default'           => false,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'show_in_rest'      => true,
		] );
	}

	/**
	 * Registers the shortcodes used by the plugin.
	 *
	 * @since 1.0.0
	 */
	public function register_shortcodes() {
		$booking = new Shortcode( 'aleno_widget', new BookingShortcodeView() );

		$booking->register();

		$booking->register_ui( [
			'label'         => __( 'Aleno Booking Link', 'aleno-restaurant-reservations' ),
			'listItemImage' => 'dashicons-calendar-alt',
			'inner_content' => [
				'label'       => esc_html__( 'Link text', 'aleno-restaurant-reservations' ),
				'description' => esc_html__( 'You can change the default "Book now" text to something else', 'aleno-restaurant-reservations' ),
			],
		] );
	}

	/**
	 * Filters the HTML attributes applied to a menu item's anchor element.
	 *
	 * @todo Move to new class.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts {
	 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
	 *
	 *     @type string $title  Title attribute.
	 *     @type string $target Target attribute.
	 *     @type string $rel    The rel attribute.
	 *     @type string $href   The href attribute.
	 * }
	 * @param WP_Post  $item  The current menu item.
	 * @return array The modified HTML attributes.
	 */
	public function nav_menu_link_attributes( $atts, $item ) {
		if ( false === strpos( $item->url, esc_url( __( 'https://www.aleno.me', 'aleno-restaurant-reservations' ) ) ) ) {
			return $atts;
		}

		$public_key = ( new PublicKeyProvider() )->get_key();

		if ( ! $public_key ) {
			return $atts;
		}

		$atts['href'] = esc_url( add_query_arg( 'k', $public_key, self::ALENO_WIDGET_URL ) );

		wp_enqueue_script( 'aleno-reservations' );
		wp_enqueue_style( 'aleno-reservations' );

		wp_add_inline_script(
			'aleno-reservations',
			sprintf(
				'ALENO_PUBLIC_KEY=%s',
				json_encode( $public_key )
			),
			'before'
		);

		return $atts;
	}

	/**
	 * Registers our own customizer nav menus implementation.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Customize_Manager $manager WP_Customize_Manager instance.
	 */
	public function register_customize_nav_menus( $manager ) {
		$decorator = new CustomizeNavMenusDecorator( $manager );

		$restaurant_id = get_option( 'aleno_restaurant_id' );

		if ( ! empty( $restaurant_id ) ) {
			$decorator->register();
		}
	}

	/**
	 * Filters a navigation menu item object.
	 *
	 * @since 3.0.0
	 *
	 * @param stdClass $menu_item The menu item object.
	 *
	 * @return stdClass The modified menu item object.
	 */
	public function wp_setup_nav_menu_item( $menu_item ) {
		if ( 'custom' !== $menu_item->type ) {
			return $menu_item;
		}

		if ( false !== strpos( $menu_item->url, esc_url( __( 'https://www.aleno.me', 'aleno-restaurant-reservations' ) ) ) ) {
			$restaurant_id = get_option( 'aleno_restaurant_id' );

			if ( empty( $restaurant_id ) ) {
				$menu_item->_invalid = true;
			}
		}

		return $menu_item;
	}

	/**
	 * @since 1.0.0
	 */
	public function save_uninstall_option() {
		$aleno_uninstall = isset( $_POST['aleno_uninstall'] );
		$aleno_nonce     = isset( $_POST['aleno_nonce'] ) ? sanitize_text_field( $_POST['aleno_nonce'] ) : '';

		if ( wp_verify_nonce( $aleno_nonce, 'aleno_uninstall' ) ) {
			update_option( 'aleno_uninstall', $aleno_uninstall );

			wp_safe_redirect( add_query_arg( [ 'updated' => 1 ], wp_get_referer() ) );
			exit;
		}
	}

	/**
	 * Resets the plugin to its initial state when passed the correct GET params.
	 *
	 * @since 1.0.0
	 */
	public function maybe_reset_plugin() {
		$aleno_reset = isset( $_GET['aleno_reset'] );
		$aleno_nonce = isset( $_GET['aleno_nonce'] ) ? sanitize_text_field( $_GET['aleno_nonce'] ) : '';

		if ( ! $aleno_reset || ! wp_verify_nonce( $aleno_nonce, 'aleno_reset' ) ) {
			return;
		}

		$uninstaller = new Uninstaller();

		try {
			$uninstaller->remote_delete_restaurant();
			$uninstaller->remote_delete_user();
		} catch ( Exception $e ) {
			add_action( 'admin_notices', function () use ( $e ) {
				?>
				<div class="error notice is-dismissible">
					<p>
						<?php
						$message = $e->getMessage();

						if ( ! empty( $message ) ) {
							/* translators: %s: error message */
							printf( __( 'There was an error resetting your settings: %s', 'aleno-restaurant-reservations' ), esc_html( $message ) );
						} else {
							_e( 'There was an unknown error resetting your settings.', 'aleno-restaurant-reservations' );
						}
						?>
					</p>
				</div>
				<?php
			} );

			return;
		}

		$uninstaller->delete_options();

		add_action( 'admin_notices', function () {
			?>
			<div class="updated notice is-dismissible">
				<p><?php _e( 'Your settings were successfully reset.', 'aleno-restaurant-reservations' ); ?></p>
			</div>
			<?php
		} );
	}

	/**
	 * Adds a notice after saving the plugin uninstall settings.
	 *
	 * @since 1.0.0
	 */
	public function maybe_show_notice() {
		if ( isset( $_GET['updated'] ) ) {
			add_action( 'admin_notices', function () {
				?>
				<div class="updated notice is-dismissible">
					<p><?php _e( 'Your settings were successfully updated.', 'aleno-restaurant-reservations' ); ?></p>
				</div>
				<?php
			} );
		}
	}

	/**
	 * Filters the list of query variables to remove.
	 *
	 * @since 1.0.0
	 *
	 * @param array $removable_query_args An array of query variables to remove from a URL.
	 *
	 * @return array Modified array of query variables.
	 */
	public function removable_query_args( $removable_query_args ) {
		$removable_query_args[] = 'aleno_reset';
		$removable_query_args[] = 'aleno_nonce';

		return $removable_query_args;
	}
}
