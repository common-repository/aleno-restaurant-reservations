<?php
/**
 * Plugin Name: aleno Restaurant Reservations
 * Description: Allows restaurants to create an account with the freemium service www.aleno.me and take restaurant bookings right from within WordPress.
 * Version:     1.0.2
 * Author:      aleno
 * Author URI:  https://www.aleno.me
 * Text Domain: aleno-restaurant-reservations
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	include dirname( __FILE__ ) . '/vendor/autoload.php';
}

if ( ! class_exists( 'WP_Requirements_Check' ) ) {
	trigger_error( sprintf( '%s does not exist. Check Composer\'s autoloader.', 'WP_Requirements_Check' ), E_USER_WARNING );

	return;
}

$requirements_check = new WP_Requirements_Check( array(
	'title' => 'Aleno Restaurant Reservations',
	'php'   => '5.4',
	'wp'    => '4.7',
	'file'  => __FILE__,
) );

if ( $requirements_check->passes() ) {
	include dirname( __FILE__ ) . '/init.php';
}

unset( $requirements_check );
