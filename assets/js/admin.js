'use strict';

(function ($, api, settings) {
	api.addAlenoLink = function (processMethod) {
		var url = settings.data.url;
		var $label = $('#aleno-booking-link-title');
		var $wrap = $('#aleno-booking-link-wrap');
		var $spinner = $wrap.find('.spinner');

		processMethod = processMethod || api.addMenuItemToBottom;

		if ('' === $label) {
			$wrap.addClass('form-invalid');

			setTimeout(function () {
				$('#aleno-booking-link-wrap').removeClass('form-invalid');
			}, 1500);

			return false;
		}

		$spinner.addClass('is-active');

		api.addLinkToMenu(url, $label.val(), processMethod, function () {
			$spinner.removeClass('is-active');

			// Set form back to defaults
			$label.val('').blur();
		});
	};

	$('#submit-aleno-booking-link-add').on('click', function () {
		api.addAlenoLink();
	});
})(jQuery, wpNavMenu, alenoSettings);