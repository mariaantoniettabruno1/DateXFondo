<?php

namespace GravityKit\GravityActions;

use \WP_Screen;

/**
 * Class Assets.
 *
 * @since 1.0
 *
 * @package GravityKit\GravityActions
 */
class Assets extends AbstractSingleton {

	/**
	 * Register of the service provider for the assets class.
	 *
	 * @since 1.0
	 */
	protected function register() {

	}

	/**
	 * Gets the URL for Assets related to this plugin.
	 *
	 * @since 1.0
	 *
	 * @param string $append What will be appended to the base URL.
	 *
	 * @return string
	 */
	public static function get_url( $append = '' ) {
		static $base_plugin_dir_url;
		if ( empty( $base_plugin_dir_url ) ) {
			$base_plugin_dir_url = Plugin::get_base_url();
		}

		return $base_plugin_dir_url . $append;
	}

	/**
	 * Register the Styles used.
	 *
	 * @since 1.0
	 */
	public function register_styles() {
		wp_register_style(
			'gk-gravityactions-selectwoo-style',
			static::get_url( 'src/assets/css/selectWoo.min.css' ),
			[],
			Plugin::VERSION,
			'all'
		);

		wp_register_style(
			'gk-gravityactions-featherlight-style',
			static::get_url( 'src/assets/vendor/featherlight/featherlight.min.css' ),
			[],
			Plugin::VERSION,
			'all'
		);

		wp_register_style(
			'gk-gravityactions-admin-style',
			static::get_url( 'src/assets/css/admin.css' ),
			[ 'gk-gravityactions-featherlight-style', 'gk-gravityactions-selectwoo-style' ],
			Plugin::VERSION,
			'all'
		);
	}

	/**
	 * Adds GravityActions assets to no conflict lists.
	 *
	 * @since 1.0
	 *
	 * @param array $handles Array of script and CSS handles
	 *
	 * @return array Handles with GA added.
	 */
	public function register_no_conflict( $handles ) {

		$handles[] = 'gk-gravityactions-admin-style';
		$handles[] = 'gk-gravityactions-selectwoo-style';
		$handles[] = 'gk-gravityactions-featherlight-style';
		$handles[] = 'gk-gravityactions-admin-style';
		$handles[] = 'gk-gravityactions-base';
		$handles[] = 'gk-gravityactions-trigger';
		$handles[] = 'gk-gravityactions-modal';
		$handles[] = 'gk-gravityactions-edit';
		$handles[] = 'gk-gravityactions-featherlight';
		$handles[] = 'gk-gravityactions-qs';
		$handles[] = 'gk-gravityactions-list';
		$handles[] = 'gk-gravityactions-selectwoo';

		return $handles;
	}

	/**
	 * Enqueues the Styles used on the administration page.
	 *
	 * @since 1.0
	 */
	public function admin_enqueue_styles() {
		if ( ! $this->should_enqueue_admin() ) {
			return;
		}

		wp_enqueue_style( 'gk-gravityactions-admin-style' );
	}

	/**
	 * Enqueues the Scripts used on the administration page.
	 *
	 * @since 1.0
	 */
	public function admin_enqueue_scripts() {
		if ( ! $this->should_enqueue_admin() ) {
			return;
		}

		wp_enqueue_script( 'gk-gravityactions-trigger' );
		wp_enqueue_script( 'gk-gravityactions-modal' );

		$this->localize_scripts();
	}

	/**
	 * Determines if the current screen of the WP admin is one that we need to enqueue assets.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public function should_enqueue_admin() {
		if ( ! is_admin() ) {
			return false;
		}

		if ( ! class_exists( 'GFForms' ) ) {
			return false;
		}

		$gf_page_name = \GFForms::get_page();

		return 'entry_list' === $gf_page_name;
	}

	public function localize_scripts() {
		wp_localize_script(
			'gk-gravityactions-trigger',
			'gk_gravityactions_trigger_data',
			[
				'nonce'            => wp_create_nonce( Admin::$modal_nonce_action ),
				'nonce_name'       => Admin::$modal_nonce_name,
				'ajax_actions_map' => Actions\Mapper::instance()->get_ajax_actions_map_keys(),
			]
		);
	}

	public function register_scripts() {
		wp_register_script(
			'gk-gravityactions-base',
			static::get_url( 'src/assets/js/base.js' ),
			[ 'jquery' ],
			Plugin::VERSION,
			true
		);

		wp_register_script(
			'gk-gravityactions-trigger',
			static::get_url( 'src/assets/js/trigger.js' ),
			[ 'jquery', 'gk-gravityactions-base' ],
			Plugin::VERSION,
			true
		);

		wp_register_script(
			'gk-gravityactions-modal',
			static::get_url( 'src/assets/js/modal.js' ),
			[ 'jquery', 'gk-gravityactions-base', 'gk-gravityactions-featherlight', 'gk-gravityactions-qs' ],
			Plugin::VERSION,
			true
		);

		wp_register_script(
			'gk-gravityactions-edit',
			static::get_url( 'src/assets/js/actions/edit.js' ),
			[ 'jquery', 'gk-gravityactions-base', 'gk-gravityactions-trigger', 'gk-gravityactions-modal', 'gk-gravityactions-list' ],
			Plugin::VERSION,
			true
		);

		/**
		 * Vendor Scripts
		 */

		wp_register_script(
			'gk-gravityactions-featherlight',
			static::get_url( 'src/assets/vendor/featherlight/featherlight.min.js' ),
			[ 'jquery' ],
			Plugin::VERSION,
			true
		);

		wp_register_script(
			'gk-gravityactions-qs',
			static::get_url( 'src/assets/vendor/qs/qs.js' ),
			[],
			Plugin::VERSION,
			true
		);

		wp_register_script(
			'gk-gravityactions-list',
			static::get_url( 'src/assets/vendor/list/list.js' ),
			[],
			Plugin::VERSION,
			true
		);

		wp_register_script(
			'gk-gravityactions-selectwoo',
			static::get_url( 'src/assets/js/selectWoo.full.min.js' ),
			[ 'jquery' ],
			Plugin::VERSION,
			true
		);
	}

}