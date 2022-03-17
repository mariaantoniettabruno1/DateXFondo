<?php

namespace GravityKit\GravityActions\Actions;

use GravityKit\GravityActions\Template;

interface ActionInterface {
	/**
	 * @since 1.0
	 *
	 * @return string[]
	 */
	public static function get_key();

	/**
	 * @since 1.0
	 *
	 * @return string[]
	 */
	public static function get_title();

	/**
	 * Gets the content of action subtitle.
	 *
	 * @since 1.0
	 *
	 * @param Template $template
	 *
	 * @return string
	 */
	public function get_subtitle( Template $template );

	/**
	 * A view file is mapped by "key" name from views folder
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public static function has_view();

	/**
	 * Determines if the trigger for this action is an Ajax one or not.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public static function is_ajax();

	/**
	 * Used to register all the hooks required by this action.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function hook();

	/**
	 * Enqueue any assets related ot this action.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function enqueue_assets();

	/**
	 * Register all secondary actions and methods for this action here.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function register();

	/**
	 * Prepare template vars for the modal usage of this action.
	 *
	 * @since 1.0
	 *
	 * @param array $template_vars Variables already set.
	 *
	 * @return array
	 */
	public function prepare_template_vars( array $template_vars = [] );

	/**
	 * Determines if the current set of template vars is valid for the modal request we are trying to load.
	 *
	 * @since 1.0
	 *
	 * @param array $template_vars Variables already set.
	 *
	 * @return boolean|\WP_Error
	 */
	public function validate_modal_request( array $template_vars = [] );
}
