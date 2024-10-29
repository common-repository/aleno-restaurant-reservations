'use strict';

(function ($, settings) {
	var $notice = $('.aleno-dashboard-launch .notice');
	var $launchButton = $('.aleno-dashboard-launch-button');
	var $spinner = $('.aleno-dashboard-launch .spinner');

	$launchButton.on('click', function () {
		$notice.addClass('hidden');
		$spinner.addClass('is-active');

		var dashboard = window.open('', '_blank');

		$.ajax({
			url: settings.data.alenoUrl + 'api/aleno/v1/user/login',
			type: 'post',
			data: JSON.stringify({ userId: settings.data.userId }),
			headers: {
				'Accept-Language': settings.data.locale,
				Authorization: settings.data.userKey
			},
			contentType: 'application/json',
			dataType: 'json',
			jsonp: false,
			success: function success(data) {
				$spinner.removeClass('is-active');

				if (data && data.href) {
					dashboard.location = data.href;
				} else {
					$notice.removeClass('hidden');
				}
			},
			error: function error() {
				$notice.removeClass('hidden');
				$spinner.removeClass('is-active');
			}
		});
	});
})(jQuery, alenoSettings);