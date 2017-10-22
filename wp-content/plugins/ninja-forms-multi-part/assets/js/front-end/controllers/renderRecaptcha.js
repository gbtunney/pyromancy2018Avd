/**
 * Handles making sure that any google recaptcha fields render if they are on this part.
 * 
 * @package Ninja Forms Front-End
 * @subpackage Main App
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( [], function() {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			this.listenTo( nfRadio.channel( 'nfMP' ), 'change:part', this.changePart, this );
		},

		changePart: function( conditionModel, then ) {
			jQuery( '.g-recaptcha' ).each( function() {
				var opts = {
					theme: jQuery( this ).data( 'theme' ),
					sitekey: jQuery( this ).data( 'sitekey' ),
					callback: nf_recaptcha_response
				};
				
				grecaptcha.render( jQuery( this )[0], opts );
			} );
		},

	});

	return controller;
} );