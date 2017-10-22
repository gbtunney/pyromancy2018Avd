/**
 * Handle changing a field's value
 * 
 * @package Ninja Forms Conditional Logic
 * @copyright (c) 2016 WP Ninjas
 * @since 3.0
 */
define( [], function() {
	var controller = Marionette.Object.extend( {
		initialize: function() {
			nfRadio.channel( 'condition:trigger' ).reply( 'change_value', this.changeValue, this );
		},

		changeValue: function( conditionModel, then ) {
			var targetFieldModel = nfRadio.channel( 'form-' + conditionModel.collection.formModel.get( 'id' ) ).request( 'get:fieldByKey', then.key );
			/*
			 * Change the value of our field model, and then trigger a re-render of its view.
			 */
			targetFieldModel.set( 'value', then.value );
			targetFieldModel.trigger( 'reRender', targetFieldModel );
		},

	});

	return controller;
} );