<?php
/**
 * Separate init file that isn't compatible with PHP 5.3 or lower.
 *
 * @package Required\Aleno
 */

namespace Required\Aleno;

define( __NAMESPACE__ . '\PLUGIN_FILE', __FILE__ );
define( __NAMESPACE__ . '\PLUGIN_DIR', __DIR__ );
define( __NAMESPACE__ . '\PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Initializes the plugin.
 *
 * @since 1.0.0
 */
function init() {
	$plugin = new Plugin();
	$plugin->init();
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\init' );
