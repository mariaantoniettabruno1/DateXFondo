<?php

namespace GravityKit\GravityActions;

use GFFormsModel;
use GFAPI;
use GravityKit\GravityActions\Actions\Mapper;

class Admin extends AbstractSingleton {

	/**
	 * Every modal request loading requires a Nonce to be passed.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public static $modal_nonce_action = 'modal_form_action';

	/**
	 * Every modal request loading requires a Nonce to be passed with this name.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	public static $modal_nonce_name = 'gk-gravityactions-modal-nonce';

	/**
	 * Stores the template object responsible for rendering the admin views.
	 *
	 * @since 1.0
	 *
	 * @var Template
	 */
	protected $template;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 */
	protected function register() {

	}

	/**
	 * Fetches an initialized object of the Template object.
	 *
	 * @since 1.0
	 *
	 * @return Template
	 */
	public function get_template() {
		if ( ! isset( $this->template ) ) {
			$this->template = new Template();
			$this->template->set_template_origin( Plugin::instance() );
			$this->template->set_template_vars_extract( true );
			$this->template->set_template_folder( 'src/admin-views' );
		}

		return $this->template;
	}

	/**
	 * Creates a WP Error and passes down to the Modal Error template.
	 *
	 * @since 1.0
	 *
	 * @param \WP_Error|string $code    The error code.
	 * @param string           $message Error message.
	 * @param array            $data    Supplemental error data.
	 *
	 * @return false|string
	 */
	protected function modal_render_error( $code, $message = null, array $data = [] ) {

		$error = $code;

		if ( ! is_wp_error( $code ) ) {
			$error = new \WP_Error( $code, $message, $data );
		}

		return $this->get_template()->render( 'modal/error', [ 'error' => $error, 'modal_title' => __( 'Modal Error', 'gk-gravityactions' ) ] );
	}

	/**
	 * Loads the content for the modal.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function load_modal_content() {
		$nonce = get_request_var( static::$modal_nonce_name );
		if ( ! wp_verify_nonce( $nonce, static::$modal_nonce_action ) ) {
			return $this->modal_render_error( 'gk-gravityactions-invalid-nonce', __( 'Invalid nonce passed to the modal loading.', 'gk-gravityactions' ) );
		}

		$view    = get_request_var( 'view' );

		if ( ! $view ) {
			return $this->modal_render_error( 'gk-gravityactions-invalid-modal-view', __( 'Invalid Modal view requested.', 'gk-gravityactions' ), [ 'view' => $view, ] );
		}

		$form_id = absint( get_request_var( 'form_id' ) );

		if ( ! $form_id ) {
			return $this->modal_render_error( 'gk-gravityactions-invalid-form_id', __( 'The Form ID passed to the rendering of the modal was invalid or empty.', 'gk-gravityactions' ) );
		}

		$entries         = array_map( 'absint', (array) get_request_var( 'entries', [] ) );
		$selected_fields = array_map( 'absint', (array) get_request_var( 'selected_fields', [] ) );
		$field_values    = (array) get_request_var( 'field_values', [] );
		$form            = GFAPI::get_form( $form_id );
		$bulk_action     = (string) get_request_var( 'bulk_action' );
		$bulk_action     = Mapper::instance()->get_action_by_key( $bulk_action );
		$action_key      = $bulk_action::get_key();

		$template_vars = $bulk_action->prepare_template_vars( [
			'modal_title'        => $bulk_action->get_title(),
			'view'               => $view,
			'form_id'            => $form_id,
			'form'               => $form,
			'entries'            => $entries,
			'selected_fields'    => $selected_fields,
			'field_values'       => $field_values,
			'bulk_action_object' => $bulk_action,
		] );

		$bulk_action_error = $bulk_action->validate_modal_request( $template_vars );
		if ( is_wp_error( $bulk_action_error ) ) {
			return $this->modal_render_error( $bulk_action_error );
		}

		/**
		 * Filter to allow short-circuiting the modal rendering.
		 *
		 * @since 1.0
		 *
		 * @param mixed $pre_render    Variable that determines if we will short circuit the modal rendering.
		 * @param array $template_vars What variables are being passed down to the modal.
		 */
		$pre_render = apply_filters( 'gk/gravityactions/pre_modal_render', null, $template_vars );

		/**
		 * Filter to allow short-circuiting the modal rendering.
		 *
		 * @since 1.0
		 *
		 * @param mixed $pre_render    Variable that determines if we will short circuit the modal rendering.
		 * @param array $template_vars What variables are being passed down to the modal.
		 */
		$pre_render = apply_filters( "gk/gravityactions/pre_modal_render:{$view}", $pre_render, $template_vars );

		/**
		 * Filter to allow short-circuiting the modal rendering.
		 *
		 * @since 1.0
		 *
		 * @param mixed $pre_render    Variable that determines if we will short circuit the modal rendering.
		 * @param array $template_vars What variables are being passed down to the modal.
		 */
		$pre_render = apply_filters( "gk/gravityactions/pre_modal_render:{$action_key}", $pre_render, $template_vars );
		if ( null !== $pre_render ) {
			return (string) $pre_render;
		}

		/**
		 * Action to allow processing of any actions before rendering the content of the modal.
		 *
		 * @since 1.0
		 *
		 * @param array $template_vars What variables are being passed down to the modal.
		 */
		do_action( 'gk/gravityactions/before_modal_render', $template_vars );

		/**
		 * Action to allow processing of any actions before rendering the content of the modal.
		 *
		 * @since 1.0
		 *
		 * @param array $template_vars What variables are being passed down to the modal.
		 */
		do_action( "gk/gravityactions/before_modal_render:{$view}", $template_vars );

		/**
		 * Action to allow processing of any actions before rendering the content of the modal.
		 *
		 * @since 1.0
		 *
		 * @param array $template_vars What variables are being passed down to the modal.
		 */
		do_action( "gk/gravityactions/before_modal_render:{$action_key}", $template_vars );

		$html = $this->get_template()->render( $view, $template_vars );

		/**
		 * Action to allow processing of any actions after rendering the content of the modal.
		 *
		 * @since 1.0
		 *
		 * @param array $template_vars What variables are being passed down to the modal.
		 */
		do_action( 'gk/gravityactions/after_modal_render', $template_vars );

		/**
		 * Action to allow processing of any actions after rendering the content of the modal.
		 *
		 * @since 1.0
		 *
		 * @param array $template_vars What variables are being passed down to the modal.
		 */
		do_action( "gk/gravityactions/after_modal_render:{$view}", $template_vars );

		/**
		 * Action to allow processing of any actions after rendering the content of the modal.
		 *
		 * @since 1.0
		 *
		 * @param array $template_vars What variables are being passed down to the modal.
		 */
		do_action( "gk/gravityactions/after_modal_render:{$action_key}", $template_vars );

		return $html;
	}

	/**
	 * Gets the form ID based on a get param on the request.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function get_form_id() {
		$forms   = GFFormsModel::get_forms( null, 'title' );
		$form_id = rgget( 'id' );

		if ( sizeof( $forms ) == 0 ) {
			return '';
		} else {
			if ( empty( $form_id ) ) {
				$form_id = $forms[0]->id;
			}
		}

		return $form_id;
	}
}
