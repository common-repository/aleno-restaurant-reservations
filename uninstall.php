<?php
/**
 * Delete all options when the plugin is uninstalled.
 *
 * @package Required\Aleno
 */


defined( 'WP_UNINSTALL_PLUGIN' ) or die;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	include __DIR__ . '/vendor/autoload.php';
}

if ( ! get_option( 'aleno_uninstall', false ) ) {
	return;
}

if ( class_exists( 'Required\Aleno\Misc\Uninstaller' ) ) {
	$uninstaller = new Required\Aleno\Misc\Uninstaller();

	try {
		$uninstaller->remote_delete_restaurant();
		$uninstaller->remote_delete_user();
	} catch ( Exception $e ) {
		// Silence.
	}

	$uninstaller->delete_options();
}
