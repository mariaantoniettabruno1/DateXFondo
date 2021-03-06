/*
 * Extend the default Inline Edit email type
 *
 * @class Email
 * @extends email
 */
( function ( $ ) {
	"use strict";

	var Email = function ( options ) {
		this.init( 'email', options, Email.defaults );
		this.selectField = null;
	};

	$.fn.editableutils.inherit( Email, $.fn.editabletypes.abstractinput );

	$.extend( Email.prototype, {
		value2html: function ( value, element ) {
			var $el = $( element );
			var oldValue = $el.text();
			var html = $el.html().replace( new RegExp( oldValue, 'g' ), value )

			$el.html( html );
		}
	} );

	Email.defaults = $.extend( {}, $.fn.editabletypes.email.defaults );

	$.fn.editabletypes.email = Email;
}( window.jQuery ) );
