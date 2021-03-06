/*
 * Extend the default Inline Edit number type
 *
 * @class Product
 * @extends number
 */
( function ( $ ) {
	"use strict";

	var Product = function ( options ) {
		this.init( 'number', options, Product.defaults );
		this.selectField = null;
	};

	$.fn.editableutils.inherit( Product, $.fn.editabletypes.abstractinput );

	$.extend( Product.prototype, {
		value2html: function ( value, element ) {
			var $el = $( element );
			var html = $el.attr('data-display');

			$el.html( html );
		}
	} );

	Product.defaults = $.extend( {}, $.fn.editabletypes.number.defaults );

	$.fn.editabletypes.product = Product;
}( window.jQuery ) );
