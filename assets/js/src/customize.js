((($, _, api, settings) => {
	/**
	 * wp.customize.Menus.AvailableMenuItemsPanelView
	 *
	 * View class for the available menu items panel.
	 *
	 * @constructor
	 * @augments api.Menus.AvailableMenuItemsPanelView
	 * @augments wp.Backbone.View
	 * @augments Backbone.View
	 */
	api.Menus.AvailableMenuItemsPanelView = api.Menus.AvailableMenuItemsPanelView.extend( {
		events() {
			return _.extend( {}, api.Menus.AvailableMenuItemsPanelView.prototype.events, {
				'click #submit-aleno-booking-link-add': 'addAlenoLink'
			} );
		},

		addAlenoLink() {
			let url      = settings.data.url;
			const $label   = $( '#aleno-booking-link-title' );
			const $spinner = $( '#new-aleno-menu-item' ).find( '.spinner' );

			if ( !this.currentMenuControl ) {
				return;
			}

			if ( '' === $label.val() ) {
				$label.addClass( 'invalid' );

				setTimeout( () => {
					$label.removeClass( 'invalid' );
				}, 1500 );

				return;
			}

			$spinner.addClass( 'is-active' );

			this.currentMenuControl.addItemToMenu( {
				'title':      $label.val(),
				'url':        url,
				'type':       'custom',
				'type_label': settings.l10n.label,
				'object':     'custom'
			} );

			// Set form back to defaults
			$label.val( '' ).blur();

			$spinner.removeClass( 'is-active' );
		}
	} );
}))( jQuery, window._, wp.customize, alenoSettings );
