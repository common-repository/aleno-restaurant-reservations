((( $, settings ) => {
	const $notice       = $( '.aleno-dashboard-launch .notice' );
	const $launchButton = $( '.aleno-dashboard-launch-button' );
	const $spinner      = $( '.aleno-dashboard-launch .spinner' );

	$launchButton.on( 'click', () => {
		$notice.addClass( 'hidden' );
		$spinner.addClass( 'is-active' );

		let dashboard = window.open( '', '_blank' );

		$.ajax( {
			url:      settings.data.alenoUrl + 'api/aleno/v1/user/login',
			type:     'post',
			data:     JSON.stringify( { userId: settings.data.userId } ),
			headers:  {
				'Accept-Language': settings.data.locale,
				Authorization:     settings.data.userKey,
			},
			contentType: 'application/json',
			dataType: 'json',
			jsonp: false,
			success:  function( data ) {
				$spinner.removeClass( 'is-active' );

				if ( data && data.href ) {
					dashboard.location = data.href;
				} else {
					$notice.removeClass( 'hidden' );
				}
			},
			error:    function() {
				$notice.removeClass( 'hidden' );
				$spinner.removeClass( 'is-active' );
			}
		} );
	} );
}))( jQuery, alenoSettings );
