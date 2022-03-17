<?php

namespace GravityKit\GravityActions;

use GravityKit\GravityActions\Actions\EditAction;

/**
 * Class Hooks.
 *
 * @since 1.0
 *
 * @package GravityKit\GravityActions
 */
class Hooks extends AbstractSingleton {

	/**
	 * Register of the service provider for the hooks class.
	 *
	 * @since 1.0
	 */
	protected function register() {
		$this->add_actions();
		$this->add_filters();
	}

	/**
	 * Add the actions to the correct hooks.
	 *
	 * @since 1.0
	 */
	protected function add_actions() {
		add_action( 'init', [ Assets::instance(), 'register_scripts' ] );
		add_action( 'init', [ Assets::instance(), 'register_styles' ] );
		add_action( 'admin_init', [ Actions\Mapper::instance(), 'action_register_mapped_actions' ], 15 );
		add_action( 'admin_enqueue_scripts', [ Assets::instance(), 'admin_enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ Assets::instance(), 'admin_enqueue_scripts' ] );
		add_action( 'gform_entry_list_action', [ Actions\Mapper::instance(), 'process_actions' ], 15, 3 );
		add_action( 'wp_ajax_gk-gravityactions/modal', [ Admin::instance(), 'load_modal_content' ] );
	}

	/**
	 * Add the filters to the correct hooks.
	 *
	 * @since 1.0
	 */
	protected function add_filters() {
		add_filter( 'gform_entry_list_bulk_actions', [ Actions\Mapper::instance(), 'filter_include_bulk_actions' ], 15, 2 );
		add_filter( 'gform_noconflict_styles', [ Assets::instance(), 'register_no_conflict' ] );
		add_filter( 'gform_noconflict_scripts', [ Assets::instance(), 'register_no_conflict' ] );
		add_filter( 'gravityview_noconflict_styles', [ Assets::instance(), 'register_no_conflict' ] );
		add_filter( 'gravityview_noconflict_scripts', [ Assets::instance(), 'register_no_conflict' ] );
	}
}