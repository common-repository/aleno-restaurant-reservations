<?php
/**
 * Plugin uninstaller / resetter class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Misc;

class Uninstaller {
	/**
	 * Deletes options from the local database.
	 *
	 * @since 1.0.0
	 */
	public function delete_options() {
		delete_option( 'aleno_key' );
		delete_option( 'aleno_public_key' );
		delete_option( 'aleno_restaurant_id' );
		delete_option( 'aleno_user_id' );
	}

	/**
	 * Attempts to remotely delete the restaurant via the API.
	 *
	 * @since 1.0.0
	 *
	 * @throws Exception
	 *
	 * @return bool True on success.
	 */
	public function remote_delete_restaurant() {
		$user_key      = get_option( 'aleno_key' );
		$restaurant_id = get_option( 'aleno_restaurant_id' );

		// There's nothing to remotely delete.
		if ( empty( $user_key ) || empty( $restaurant_id ) ) {
			return true;
		}

		$response = wp_remote_post( 'https://mytools.aleno.me/api/aleno/v1/restaurant/delete', [
			'headers' => [
				'Accept-Language' => strtok( get_locale(), '_' ),
				'Authorization'   => get_option( 'aleno_key' ),
				'Content-Type'    => 'application/json',
			],
			'body'    => json_encode( [ 'restaurantId' => get_option( 'aleno_restaurant_id' ) ] ),
			'timeout' => 30,
		] );

		if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_message() );
		}

		$status = wp_remote_retrieve_response_code( $response );

		if ( 200 === $status ) {
			return true;
		}

		$result = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( is_array( $result ) && isset( $result['code'], $result['text'] ) ) {
			throw new Exception( $result['text'] );
		}

		throw new Exception( wp_remote_retrieve_response_message( $response ), (int) $status );
	}

	/**
	 * Attempts to remotely delete the aleno user via the API.
	 *
	 * @since 1.0.0
	 *
	 * @throws Exception
	 *
	 * @return bool True on success, false on failure.
	 */
	public function remote_delete_user() {
		$user_key = get_option( 'aleno_key' );
		$user_id  = get_option( 'aleno_user_id' );

		// There's nothing to remotely delete.
		if ( empty( $user_key ) || empty( $user_id ) ) {
			return true;
		}

		$response = wp_remote_post( 'https://mytools.aleno.me/api/aleno/v1/user/delete', [
			'headers' => [
				'Accept-Language' => strtok( get_locale(), '_' ),
				'Authorization'   => get_option( 'aleno_key' ),
				'Content-Type'    => 'application/json',
			],
			'body'    => json_encode( [ 'userId' => get_option( 'aleno_user_id' ) ] ),
			'timeout' => 30,
		] );

		if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_message(), $response->get_error_code() );
		}

		$status = wp_remote_retrieve_response_code( $response );

		if ( 200 === $status ) {
			return true;
		}

		$result = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( is_array( $result ) && isset( $result['code'], $result['text'] ) ) {
			throw new Exception( $result['text'] );
		}

		throw new Exception( wp_remote_retrieve_response_message( $response ), (int) $status );
	}
}
