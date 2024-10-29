<?php
/**
 * Public key provider class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\Misc;

class PublicKeyProvider {
	/**
	 * Returns the public aleno key.
	 *
	 * Used for the booking modal on the front end.
	 *
	 * If the public key is not stored already, it tries to fetch it from the API.
	 *
	 * @since 1.0.0
	 *
	 * @return string|false Public key on success, false on failure.
	 */
	public function get_key() {
		$public_key = get_option( 'aleno_public_key', false );

		if ( false !== $public_key ) {
			return $public_key;
		}

		$restaurant_id = get_option( 'aleno_restaurant_id' );

		if ( ! $restaurant_id ) {
			return false;
		}

		$response = wp_remote_post( 'https://mytools.aleno.me/api/aleno/v1/popup/key', [
			'headers' => [
				'Accept-Language' => strtok( get_locale(), '_' ),
				'Authorization'   => get_option( 'aleno_key' ),
				'Content-Type'    => 'application/json',
			],
			'body'    => json_encode( [
				'locale'       => strtok( get_locale(), '_' ),
				'restaurantId' => (string) $restaurant_id,
			] ),
		] );

		$status = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $status || is_wp_error( $response ) ) {
			// Update option to prevent future API calls.
			update_option( 'aleno_public_key', '' );

			return false;
		}

		$result = wp_remote_retrieve_body( $response );

		$result = json_decode( $result, true );

		if ( isset( $result['key'] ) ) {
			update_option( 'aleno_public_key', $result['key'] );

			return $result['key'];
		}

		return false;
	}
}
