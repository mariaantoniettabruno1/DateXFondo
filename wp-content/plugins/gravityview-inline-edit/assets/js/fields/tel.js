/*
 * Extend the default Inline Edit tel type
 *
 * @class Phone
 * @extends tel
 */
( function ( $ ) {
	"use strict";

	var Phone = function ( options ) {
		this.init( 'tel', options, Phone.defaults );
		this.selectField = null;
	};

	$.fn.editableutils.inherit( Phone, $.fn.editabletypes.abstractinput );

	$.extend( Phone.prototype, {
		value2html: function ( value, element ) {
			var $el = $( element );
			var html = $el.html().match( /href/ ) ? '<a href="tel:' + encodeURIComponent( value ) + '">' + value + '</a>' : value;

			$el.html( html );
		}
	} );

	Phone.defaults = $.extend( {}, $.fn.editabletypes.tel.defaults );

	$.fn.editabletypes.tel = Phone;
}( window.jQuery ) );
