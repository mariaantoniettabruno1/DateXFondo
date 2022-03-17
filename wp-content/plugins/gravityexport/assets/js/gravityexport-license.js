/*global jQuery, GKGravityExport, document, ajaxurl */

(function ( $ ) {
  'use strict';

  var GravityExportSettings = {
    message: '',
    license_field: $( '#license_key' ),
    activate_button: $( '[data-edd_action=activate_license]' ),
    deactivate_button: $( '[data-edd_action=deactivate_license]' ),
    check_button: $( '[data-edd_action=check_license]' ),

    init: function () {
      GravityExportSettings.message_fadeout();
      GravityExportSettings.add_status_container();

      $( document )
        .on( 'ready keyup', GravityExportSettings.license_field, GravityExportSettings.key_change )
        .on( 'click', '.gv-edd-action', GravityExportSettings.clicked )
        .on( 'gv-edd-failed gv-edd-invalid', GravityExportSettings.failed )
        .on( 'gv-edd-valid', GravityExportSettings.valid )
        .on( 'gv-edd-deactivated', GravityExportSettings.deactivated )
        .on( 'gv-edd-inactive gv-edd-other', GravityExportSettings.other );
    },

    /**
     * Hide the "Settings Updated" message after save
     */
    message_fadeout: function () {
      setTimeout( function () {
        $( '#gform_tab_group #message' ).toggle( 'scale' );
      }, 2000 );
    },

    add_status_container: function () {
      $( GKGravityExport.license_box ).insertBefore( GravityExportSettings.license_field );
    },

    /**
     * When the license key changes, change the button visibility
     * @param e
     */
    key_change: function ( e ) {
      var license_key = $( '#license_key' ).val();

      var showbuttons = false;
      var hidebuttons = false;

      if ( license_key.length > 0 ) {
        switch ( $( '#license_key_status' ).val() ) {
          case 'valid':
            hidebuttons = $( '[data-edd_action=activate_license]' );
            showbuttons = $( '[data-edd_action=deactivate_license],[data-edd_action=check_license]' );
            break;
          case 'deactivated':
          case 'site_inactive':
          default:
            hidebuttons = $( '[data-edd_action=deactivate_license]' );
            showbuttons = $( '[data-edd_action=activate_license],[data-edd_action=check_license]' );
            break;
        }
      } else if ( license_key.length === 0 ) {
        hidebuttons = $( '[data-edd_action*=_license]' );
      }

      // On load, no animation; otherwise, 100ms
      var speed = (e.type === 'ready') ? 0 : 'fast';

      if ( hidebuttons ) {
        hidebuttons.filter( ':visible' ).fadeOut( speed );
      }

      if ( showbuttons ) {
        showbuttons.filter( ':hidden' ).removeClass( 'hide' ).hide().fadeIn( speed );
      }
    },

    /**
     * Show the HTML of the message
     * @param message HTML for new status
     */
    update_status: function ( message ) {
      if ( message !== '' ) {
        $( '#gv-edd-status' ).replaceWith( message );
      }
    },

    set_pending_message: function ( message ) {
      $( '#gv-edd-status' )
        .removeClass( 'hide' )
        .addClass( 'pending' )
        .addClass( 'info' )
        .removeClass( 'success' )
        .removeClass( 'warning' )
        .removeClass( 'error' )
        .html( $( '#gv-edd-status' ).html().replace( /(<strong>)(.*?)(<\/strong)>/, '$1' + message ) );
    },

    clicked: function ( e ) {
      e.preventDefault();

      var $that = $( this );

      var theData = {
        license: $( '#license_key' ).val(),
        edd_action: $that.attr( 'data-edd_action' ),
        field_id: $that.attr( 'id' ),
      };

      $that.not( GravityExportSettings.check_button ).addClass( 'button-disabled' );

      $( '#gform-settings,#gform-settings .button' ).css( 'cursor', 'wait' );

      GravityExportSettings.set_pending_message( $that.attr( 'data-pending_text' ) );

      GravityExportSettings.post_data( theData );
    },

    post_data: function ( data ) {
      $.post( ajaxurl, {
        'action': 'gravityexport_license',
        'data': data
      }, function ( response ) {

        response = $.parseJSON( response );

        GravityExportSettings.message = response.message;

        if ( data.edd_action !== 'check_license' ) {
          $( '#license_key_status' ).val( response.license );
          $( '#license_key_response' ).val( JSON.stringify( response ) );
          $( document ).trigger( 'gv-edd-' + response.license, response );
        }

        GravityExportSettings.update_status( response.message );

        $( '#gform-settings' )
          .css( 'cursor', 'default' )
          .find( '.button' )
          .css( 'cursor', 'pointer' );
      } );
    },

    valid: function () {
      GravityExportSettings.activate_button
        .fadeOut( 'medium', function () {
          GravityExportSettings.activate_button.removeClass( 'button-disabled' );
          GravityExportSettings.deactivate_button.fadeIn().css( 'display', 'inline-block' );
        } );
    },

    failed: function () {
      GravityExportSettings.deactivate_button.removeClass( 'button-disabled' );
      GravityExportSettings.activate_button.removeClass( 'button-disabled' );
    },

    deactivated: function () {
      GravityExportSettings.deactivate_button
        .css( 'min-width', function () {
          return $( this ).width();
        } )
        .fadeOut( 'medium', function () {
          GravityExportSettings.deactivate_button.removeClass( 'button-disabled' );
          GravityExportSettings.activate_button.fadeIn( function () {
            $( this ).css( 'display', 'inline-block' );
          } );
        } );

    },

    other: function () {
      GravityExportSettings.deactivate_button.fadeOut( 'medium', function () {
        GravityExportSettings.activate_button
          .removeClass( 'button-disabled' )
          .fadeIn()
          .css( 'display', 'inline-block' );
      } );
    }
  };

  GravityExportSettings.init();

})( jQuery );
