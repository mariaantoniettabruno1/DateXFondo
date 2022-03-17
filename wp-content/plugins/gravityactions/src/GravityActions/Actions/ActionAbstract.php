<?php

namespace GravityKit\GravityActions\Actions;

/**
 * Class ActionAbstract
 *
 * @since 1.0
 *
 * @package GravityKit\GravityActions\Actions
 */
abstract class ActionAbstract implements ActionInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register() {
		$this->hook();
	}

	/**
	 * {@inheritDoc}
	 */
	public function hook() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ], 15 );
	}

	/**
	 * {@inheritDoc}
	 */
	public function prepare_template_vars( array $template_vars = [] ) {
		return $template_vars;
	}

	/**
	 * {@inheritDoc}
	 */
	public function validate_modal_request( array $template_vars = [] ) {
		return true;
	}
}