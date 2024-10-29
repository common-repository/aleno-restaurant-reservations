((( $, api, settings ) => {
	api.addAlenoLink = processMethod => {
		let url        = settings.data.url;
		const $label   = $( '#aleno-booking-link-title' );
		const $wrap    = $( '#aleno-booking-link-wrap' );
		const $spinner = $wrap.find( '.spinner' );

		processMethod = processMethod || api.addMenuItemToBottom;

		if ( '' === $label ) {
			$wrap.addClass( 'form-invalid' );

			setTimeout( () => {
				$( '#aleno-booking-link-wrap' ).removeClass( 'form-invalid' );
			}, 1500 );

			return false;
		}

		$spinner.addClass( 'is-active' );

		api.addLinkToMenu( url, $label.val(), processMethod, () => {
			$spinner.removeClass( 'is-active' );

			// Set form back to defaults
			$label.val( '' ).blur();
		} );
	};

	$( '#submit-aleno-booking-link-add' ).on( 'click', () => {
		api.addAlenoLink();
	} );
}))( jQuery, wpNavMenu, alenoSettings );
