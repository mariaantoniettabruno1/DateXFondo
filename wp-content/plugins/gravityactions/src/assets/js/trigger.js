/* globals GravityKit, jQuery, ajaxurl, gk_gravityactions_trigger_data, console */
/**
 * Setups the main variable for this file.
 *
 * @since 1.0
 *
 * @type   {Object}
 */
GravityKit.GravityActions.Trigger = {};

/**
 * Initialize the main part of this class.
 *
 * @since 1.0
 *
 * @param  {Object} $   jQuery
 * @param  {Object} obj GravityKit.GravityActions.Trigger
 *
 * @return {void}
 */
( function ( $, obj ) {
	'use strict';

	/**
	 * Selectors used for configuration and setup.
	 *
	 * @since 1.0
	 *
	 * @type {Object}
	 */
	obj.selectors = {
		entryListForm: '#entry_list_form',
		actionSelector: '[name^="action"]', // action or action2
		entryCheckbox: '.gform_list_checkbox',
	};

	/**
	 * Using the Localized data associated with this script store it locally for references.
	 *
	 * @since 1.0
	 *
	 * @type {Object}
	 */
	if ( 'undefined' === typeof gk_gravityactions_trigger_data ) {
		console.error( 'Localization variable "gk_gravityactions_trigger_data" missing from script "trigger.js"' );
		obj.data = {};
	} else {
		obj.data = gk_gravityactions_trigger_data;
	}

	/**
	 * Handles the initialization of the manager when Document is ready.
	 *
	 * @since 1.0
	 *
	 * @return {Array}
	 */
	obj.getEntriesIDs = function ( $form ) {
		var $entriesChecked = $form.find( obj.selectors.entryCheckbox ).filter( ':checked' );
		var entriesIDs = [];

		$entriesChecked.each( function () {
			entriesIDs.push( $( this ).val() );
		} );

		return entriesIDs;
	};

	/**
	 * Determines if this particular action is handled by ajax, or should be passed to PHP via the form.
	 *
	 * @since 1.0
	 *
	 * @param {String} action Which action we are checking for.
	 *
	 * @return {Boolean}
	 */
	obj.isAjaxAction = function ( action ) {
		var actions = Object.values( obj.data.ajax_actions_map );
		var actionData = obj.getActionData( action );

		if ( false === actionData ) {
			return false;
		}

		return -1 !== actions.indexOf( actionData.action );
	};

	/**
	 * Determines the data from this particular action, normally for ours will have the form ID appended to the end.
	 *
	 * @since 1.0
	 *
	 * @param {String} action Which action we are checking for.
	 *
	 * @return {Object}
	 */
	obj.getActionData = function ( action ) {
		if ( 'string' !== typeof action ) {
			return false;
		}

		var found = action.match( /-([0-9]*)$/i, '' );
		if ( ! found ) {
			return false;
		}

		var formID = found[ 1 ];
		action = action.replace( found[ 0 ], '' );

		return {
			action: action,
			nonce: obj.data.nonce,
			nonceName: obj.data.nonce_name,
			formID: formID,
		};
	};

	/**
	 * When the bulk actions submit button is clicked the form is submitted so we intercept.
	 *
	 * @since 1.0
	 *
	 * @param  {Event} event DOM Event used for the form submit.
	 *
	 * @return {void|Boolean}
	 */
	obj.onFormSubmit = function ( event ) {
		// We make sure we only are listening a trusted submit.
		if ( ! event.originalEvent || ! event.originalEvent.isTrusted ) {
			return;
		}

		var $form = $( this );
		var $field = $form.find( obj.selectors.actionSelector );
		var action = $field.val();

		if ( ! obj.isAjaxAction( action ) ) {
			return;
		}

		var entriesIDs = obj.getEntriesIDs( $form );
		var actionData = obj.getActionData( action );

		$form.trigger( 'submitBulkAction.GravityActions/GK', [ entriesIDs, actionData, event ] );

		event.preventDefault();

		return false;
	};

	/**
	 * Handles the initialization of the manager when Document is ready.
	 *
	 * @since 1.0
	 *
	 * @return {void}
	 */
	obj.ready = function () {
		$( document ).on( 'submit', obj.selectors.entryListForm, obj.onFormSubmit );
	};

	// Configure on document ready.
	$( obj.ready );
} )( jQuery, GravityKit.GravityActions.Trigger );