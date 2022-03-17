/* globals GravityKit, jQuery, List */
/**
 * Setups the variable holding the Stuff in the actions folder.
 *
 * @since 1.0
 *
 * @type   {Object}
 */
GravityKit.GravityActions.Actions = GravityKit.GravityActions.Actions || {};

/**
 * Setups the main variable for this file.
 *
 * @since 1.0
 *
 * @type   {Object}
 */
GravityKit.GravityActions.Actions.Edit = {};

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
( function( $, obj ) {
	'use strict';

	/**
	 * Which action this Object is responsible for.
	 *
	 * @since 1.0
	 *
	 * @type {string}
	 */
	obj.action = 'gk-bulk-edit-entries';

	/**
	 * Selectors used for configuration and setup.
	 *
	 * @since 1.0
	 *
	 * @type {Object}
	 */
	obj.selectors = {
		entryListForm: '#entry_list_form',
		selectedFieldCheckbox: '.gk-gravityactions-edit-selected-field',
		selectedFieldCount: '.gk-gravityactions-edit-selected-field-count',
		entryCheckbox: '.gform_list_checkbox',
	};

	/**
	 * When the action is submitted from the bulk actions dropdown we trigger this.
	 *
	 * @param {Event} event
	 * @param {Array} entries
	 * @param {Object} data
	 * @param {Event} originalEvent
	 */
	obj.onActionSubmit = function( event, entries, data, originalEvent ) {
		if ( obj.action !== data.action ) {
			return;
		}

		const $allEntries = $( '#all_entries' );
		const isAllEntries = $allEntries.val();

		let args = {
			view: 'edit/step-1',
			entries: entries,
			form_id: data.formID,
			bulk_action: data.action,
			is_all_entries: isAllEntries,
			current_query_args: window.location.search.substring( 1 ),
		};

		args[ data.nonceName ] = data.nonce;

		GravityKit.GravityActions.Modal.request( args );
	};


	obj.onCompleteUpdating = ( formID, entries, action, nonce, nonceName ) => {
		let args = {
			view: 'edit/step-complete',
			entries: entries,
			form_id: formID,
			bulk_action: action,
		};

		args[ nonceName ] = nonce;

		GravityKit.GravityActions.Modal.request( args );
	};

	obj.setupList = function() {
		var options = {
			listClass: 'gv-bulk-edit-step-1-fields',
			searchClass: 'gv-bulk-edit-search',
			valueNames: [ 'gk-gravityactions-edit-field-name' ],
		};

		var fieldsList = new List( 'gk-gravityactions-edit-step-1', options );
	};

	obj.onFieldSelect = function( event ){
		const $fieldsCount = $( obj.selectors.selectedFieldCount );
		const $field = $( this );
		const noneSelected = $fieldsCount.data( 'noneSelected' );
		const oneSelected = $fieldsCount.data( 'oneSelected' );
		const multipleSelected = $fieldsCount.data( 'multipleSelected' );
		const $fieldToSelect = $( '.gk-gravityactions-hidden-selected-fields[value="' + $field.val() + '"]' );
		let countSelected = parseInt( $fieldsCount.data( 'countSelected' ), 10 );

		if ( $field.is( ':checked' ) ) {
			countSelected = countSelected + 1;
			$fieldToSelect.prop( 'disabled', false );

		} else {
			countSelected = countSelected - 1;
			$fieldToSelect.prop( 'disabled', true );

		}

		$fieldsCount.data( 'countSelected', countSelected );

		if ( 0 === countSelected ) {
			$fieldsCount.text( noneSelected );
		} else if ( 1 === countSelected ) {
			$fieldsCount.text( oneSelected );
		} else {
			$fieldsCount.text( multipleSelected.replace( '%s', countSelected ) );
		}
	};

	/**
	 * Handles the initialization of the manager when Document is ready.
	 *
	 * @since 1.0
	 *
	 * @return {void}
	 */
	obj.ready = function() {
		$( GravityKit.GravityActions.Trigger.selectors.entryListForm ).on( 'submitBulkAction.GravityActions/GK', obj.onActionSubmit );
		$( document ).on( 'change', obj.selectors.selectedFieldCheckbox, obj.onFieldSelect );
		$( document ).on( 'afterAjaxSuccess.GravityActions/GK', obj.setupList );
	};

	// Configure on document ready.
	$( obj.ready );
} )( jQuery, GravityKit.GravityActions.Actions.Edit );