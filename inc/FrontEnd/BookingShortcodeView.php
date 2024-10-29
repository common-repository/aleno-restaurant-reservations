<?php
/**
 * BookingShortcodeView class.
 *
 * @since 1.0.0
 */

namespace Required\Aleno\FrontEnd;

use Required\Aleno\Common\FrontEnd\ShortcodeView;
use Required\Aleno\Misc\PublicKeyProvider;
use Required\Aleno\Plugin;

/**
 * Class used to implement booking shortcode view.
 *
 * @since 1.0.0
 */
class BookingShortcodeView extends ShortcodeView {
	/**
	 * Renders the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		$public_key = ( new PublicKeyProvider() )->get_key();

		if ( ! $public_key ) {
			return '';
		}

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

		return sprintf( '<a href="%1$s" class="%2$s">%3$s</a>',
			esc_url( add_query_arg( 'k', $public_key, Plugin::ALENO_WIDGET_URL ) ),
			esc_attr( implode( ' ', apply_filters( 'aleno.booking_shortcode_button_classes', [ 'button', 'aleno-button' ] ) ) ),
			esc_html( $this->content ?: __( 'Book now', 'aleno-restaurant-reservations' ) )
		);
	}
}
