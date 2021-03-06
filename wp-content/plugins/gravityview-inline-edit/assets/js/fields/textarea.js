/*
 * Extend the default Inline Edit textarea type
 *
 * @class Textarea
 * @extends textarea
 */
( function ( $ ) {
	"use strict";

	var Textarea = function ( options ) {
		this.init( 'tel', options, Textarea.defaults );
		this.selectField = null;
	};

	$.fn.editableutils.inherit( Textarea, $.fn.editabletypes.abstractinput );

	$.extend( Textarea.prototype, {
		value2html: function ( value, element ) {
			var $el = $( element );

			if ( $el.parents( 'table.gf_entries' ).length ) {
				$el.text( value );
			} else {
				var lines = value.split( '\n' );
				var html = '';
				$.each( lines, function ( i, line ) {
					if ( line ) {
						var escapedLine = document.createElement( 'textarea' );
						escapedLine.textContent = line;
						html += '<p>' + ( $el.attr( 'data-allow-html' ) ? line : escapedLine.innerHTML ) + '</p>';
					}
				} );
				$el.html( html );
			}
		}
	} );

	Textarea.defaults = $.extend( {}, $.fn.editabletypes.textarea.defaults );

	$.fn.editabletypes.textarea = Textarea;
}( window.jQuery ) );
