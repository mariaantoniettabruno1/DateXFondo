/* globals GravityKit, jQuery, ajaxurl, Qs */
/**
 * Setups the main variable for this file.
 *
 * @since 1.0
 *
 * @type   {Object}
 */
GravityKit.GravityActions.Modal = {};

/**
 * Initialize the main part of this class.
 *
 * @since 1.0
 *
 * @param  {Object} $   jQuery
 * @param  {Object} obj GravityKit.GravityActions.Modal
 * @param  {Object} _   Underscore pulled from the window object.
 *
 * @return {void}
 */
( function( $, obj, _ ) {
	'use strict';

	// Run some magic to allow a better handling of class names for jQuery.hasClass type of methods
	String.prototype.className = function () {
		// Prevent Non Strings to be included
		if (
			(
				'string' !== typeof this
				&& ! this instanceof String
			)
			|| 'function' !== typeof this.replace
		) {
			return this;
		}

		return this.replace( '.', '' );
	};

	/**
	 * Store the document locally.
	 *
	 * @since 1.0
	 *
	 * @type {*|jQuery|HTMLElement}
	 */
	obj.$document = $( document );

	/**
	 * Store the current modal.
	 *
	 * @since 1.0
	 *
	 * @type {*|jQuery|HTMLElement}
	 */
	obj.featherlight = null;

	/**
	 * Selectors used for configuration and setup
	 *
	 * @since 1.0
	 *
	 * @type {Object}
	 */
	obj.selectors = {
		modal: '.gk-gravityactions-modal',
		container: '[data-js="gk-gravityactions-modal-container"]',
		link: '[data-js="gk-gravityactions-modal-link"]',
		form: '[data-js="gk-gravityactions-modal-form"]',
		submit: '[data-js="gk-gravityactions-modal-submit"]',
		submitClicked: '.gk-gravityactions-modal-submit-clicked',
		loader: '.gk-gravityactions-loading',
		hiddenElement: '.gk-gravityactions-hidden',
	};

	/**
	 * Flag when a popstate change is happening.
	 *
	 * @since 1.0
	 *
	 * @type {boolean}
	 */
	obj.doingPopstate = false;

	/**
	 * Stores the current ajax request been handled by the modal.
	 *
	 * @since 1.0
	 *
	 * @type {jqXHR|null}
	 */
	obj.currentAjaxRequest = null;


	/**
	 * Handles the initialization of the manager when Document is ready.
	 *
	 * @since 1.0
	 *
	 * @return {void}
	 */
	obj.ready = function() {
		$( document ).on( 'click.GravityActions/GK', obj.selectors.link, obj.onClickOpen );
		$( document ).on( 'submit.GravityActions/GK', obj.selectors.form, obj.onFormSubmit );
		$( document ).on( 'click.GravityActions/GK', obj.selectors.submit, obj.onSubmitClick );
	};

	obj.onClickOpen = function ( event ) {
		obj.request(
			{
				$trigger: $( this ),
			}
		);

		event.preventDefault();
		return false;
	};

	obj.onSubmitClick = function ( event ) {
		$( this ).addClass( obj.selectors.submitClicked.className() );
	};

	obj.onFormSubmit = function ( event ) {
		const $form = $( this );
		const $submitter = $form.find( obj.selectors.submitClicked );
		let data = new FormData( $form[0] );
		data = obj.serializeFormData( data );
		data.$trigger = $submitter;

		obj.request( data );

		$submitter.removeClass( obj.selectors.submitClicked.className() );

		event.preventDefault();
		return false;
	};

	obj.serializeFormData = function ( data ) {
		let obj = {};
		for ( let [ key, value ] of data ) {
			if ( obj[ key ] !== undefined ) {
				if ( ! Array.isArray( obj[ key ] ) ) {
					obj[ key ] = [ obj[ key ] ];
				}
				obj[ key ].push( value );
			} else {
				obj[ key ] = value;
			}
		}
		return obj;
	};

	obj.openModal = function() {
		let modalConfig = {
			variant: obj.selectors.modal.className(),
			closeIcon: '',
			afterClose: obj.onModalClose,
		};

		obj.featherlight = $.featherlight( '<div class="gk-gravityactions-modal-inner"></div>', modalConfig );
	};

	obj.insertLoader = function() {
		const $loader = $( '<div class="gk-gravityactions-modal-loader"><div class="gk-gravityactions-modal-loader-spinner"><div></div><div></div><div></div><div></div></div></div>' );
		obj.featherlight.$content.append( $loader );
	};

	/**
	 * Performs an AJAX request given the data for the REST API and which container
	 * we are going to pass the answer to.
	 *
	 * @since 1.0
	 *
	 * @param  {object}               data    DOM Event related to the Click action.
	 *
	 * @return {void}
	 */
	obj.request = function( data ) {
		if ( ! obj.featherlight ) {
			obj.openModal();
		}

		obj.featherlight.$instance.trigger( 'beforeRequest.GravityActions/GK', [ data ] );

		let settings = obj.getAjaxSettings();

		// Pass the data setup to the $.ajax settings.
		settings.data = obj.setupRequestData( data );

		obj.currentAjaxRequest = $.ajax( settings );

		obj.featherlight.$instance.trigger( 'afterRequest.GravityActions/GK', [ data ] );
	};

	/**
	 * Sets up the request data for AJAX request.
	 *
	 * @since 1.0
	 *
	 * @param  {object}               data    Data object to modify and setup.
	 *
	 * @return {Object}
	 */
	obj.setupRequestData = function( data ) {
		let $trigger;
		if ( typeof data.$trigger !== 'undefined' ) {
			$trigger = data.$trigger;
			delete data.$trigger;
		}


		let defaultData = {
			action: 'gk-gravityactions/modal',
		};

		if ( $trigger ) {
			defaultData.view = $trigger.data( 'modalView' );
		}

		return $.extend( {}, defaultData, data );
	};

	/**
	 * Gets the jQuery.ajax() settings provided a Modal jQuery Object.
	 *
	 * @since 1.0
	 *
	 * @return {Object}
	 */
	obj.getAjaxSettings = function() {
		var ajaxSettings = {
			url: ajaxurl,
			accepts: 'html',
			dataType: 'html',
			method: 'POST',
			'async': true, // async is keyword
			beforeSend: obj.ajaxBeforeSend,
			complete: obj.ajaxComplete,
			success: obj.ajaxSuccess,
			error: obj.ajaxError,
			context: obj.featherlight.$instance,
		};

		return ajaxSettings;
	};

	/**
	 * Triggered on jQuery.ajax() beforeSend action, which we hook into to replace the contents of the modal with a
	 * loading HTML, as well as trigger a before and after hook so third-party developers can always extend all
	 * requests.
	 *
	 * @since 1.0
	 *
	 * @param  {jqXHR}       jqXHR    Request object
	 * @param  {Object} settings Settings that this request will be made with
	 *
	 * @return {void}
	 */
	obj.ajaxBeforeSend = function( jqXHR, settings ) {
		var $modal = this;

		obj.insertLoader();
		var $loader = $modal.find( obj.selectors.loader );

		$modal.trigger( 'beforeAjaxBeforeSend.GravityActions/GK', [ jqXHR, settings ] );

		if ( $loader.length ) {
			$loader.removeClass( obj.selectors.hiddenElement.className() );
		}

		$modal.trigger( 'afterAjaxBeforeSend.GravityActions/GK', [ jqXHR, settings ] );
	};

	/**
	 * Triggered on jQuery.ajax() complete action, which we hook into to reset appropriate variables and remove the
	 * loading HTML, as well as trigger a before and after hook so third-party developers can always extend all requests
	 *
	 * @since 1.0
	 *
	 * @param  {jqXHR}  jqXHR       Request object
	 * @param  {String} textStatus Status for the request
	 *
	 * @return {void}
	 */
	obj.ajaxComplete = function( jqXHR, textStatus ) {
		var $modal = this;
		var $loader = $modal.find( obj.selectors.loader );

		$modal.trigger( 'beforeAjaxComplete.GravityActions/GK', [ jqXHR, textStatus ] );

		if ( $loader.length ) {
			$loader.addClass( obj.selectors.hiddenElement.className() );
		}

		$modal.trigger( 'afterAjaxComplete.GravityActions/GK', [ jqXHR, textStatus ] );

		// Flag that we are done with popstate if that was the case.
		if ( obj.doingPopstate ) {
			obj.doingPopstate = false;
		}

		// Reset the current ajax request on the manager object.
		obj.currentAjaxRequest = null;
	};

	/**
	 * Triggered on jQuery.ajax() success action, which we hook into to replace the contents of the modal, as well as
	 * trigger a before and after hook so third-party developers can always extend all requests
	 *
	 * @since 1.0
	 *
	 * @param  {String} data       HTML sent from the AJAX request.
	 * @param  {String} textStatus Status for the request.
	 * @param  {jqXHR}  jqXHR      Request object.
	 *
	 * @return {void}
	 */
	obj.ajaxSuccess = function( data, textStatus, jqXHR ) {
		var $modal = this;

		$modal.trigger( 'beforeAjaxSuccess.GravityActions/GK', [ data, textStatus, jqXHR ] );

		var $html = $( data );

		// Replace the current container with the new Data.
		obj.featherlight.$content.empty().append( $html );

		$modal.trigger( 'afterAjaxSuccess.GravityActions/GK', [ data, textStatus, jqXHR ] );
	};

	/**
	 * Triggered on jQuery.ajax() error action, which we hook into to close the modal for now, as well as
	 * trigger a before and after hook so third-party developers can always extend all requests
	 *
	 * @since 1.0
	 *
	 * @param  {jqXHR}  jqXHR    Request object.
	 * @param  {Object} settings Settings that this request was made with.
	 *
	 * @return {void}
	 */
	obj.ajaxError = function( jqXHR, settings ) {
		var $modal = this;

		$modal.trigger( 'beforeAjaxError.GravityActions/GK', [ jqXHR, settings ] );

		/**
		 * @todo  we need to handle errors here
		 */

		// Close the current modal.
		obj.featherlight.close();

		$modal.trigger( 'afterAjaxError.GravityActions/GK', [ jqXHR, settings ] );
	};

	obj.onModalClose = function () {
		obj.featherlight.$instance.trigger( 'beforeCloseModal.GravityActions/GK', [ this ] );

		// Remove it from the object.
		obj.featherlight = null;
	};

	// Configure on document ready.
	$( obj.ready );
} )( jQuery, GravityKit.GravityActions.Modal, window._ );