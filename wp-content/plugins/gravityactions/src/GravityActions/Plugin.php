<?php
namespace GravityKit\GravityActions;

use \GFForms;
use \GFAddOn;

/**
 * Class Plugin.
 *
 * @since 1.0
 *
 * @package GravityKit\GravityActions
 */
class Plugin extends AbstractSingleton {
	/**
	 * Holds the current version of the plugin.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	const VERSION = GK_GRAVITYACTIONS_VERSION;

	/**
	 * Main file used to load the plugin, will be used for loading certain files and assets.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	const FILE = GK_GRAVITYACTIONS_FILE;

	/**
	 * Main slug for the plugin.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	const SLUG = 'gk-gravityactions';

	/**
	 * Register of the service provider for the main plugin class.
	 *
	 * @since 1.0
	 */
	protected function register() {
		add_action( 'gform_loaded', [ $this, 'load' ] );
	}

	/**
	 * Determines if Gravity Forms is loaded and if we should load the GravityActions addon.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public static function should_load() {
		if ( ! class_exists( 'GFForms' ) ) {
			return false;
		}

		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Load all pieces of the plugin properly.
	 *
	 * @since 1.0
	 */
	public function load() {
		if ( ! static::should_load() ) {
			return;
		}

		$this->load_functions();
		$this->register_i18n();
		$this->register_addon();

		Hooks::instance();
		Assets::instance();
		Admin::instance();

		/**
		 * Triggers an action when the plugin is fully loaded.
		 *
		 * @since 1.0
		 */
		do_action( 'gk/gravityactions/loaded' );
	}

	/**
	 * The code that runs during plugin activation.
	 *
	 * @since 1.0
	 */
	public static function activate() {

	}

	/**
	 * The code that runs during plugin deactivation.
	 *
	 * @since 1.0
	 */
	public static function deactivate() {

	}

	/**
	 * Register the addon with GravityForms.
	 *
	 * @since 1.0
	 */
	protected function register_addon() {
	    GFForms::include_addon_framework();
		if ( ! class_exists( 'GFFeedAddOn' ) ) {
			GFForms::include_feed_addon_framework();
		}
		GFAddOn::register( Addon::class );
	}

	/**
	 * Fetches the base url for this plugin.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public static function get_base_url() {
		static $base_url;

		if ( empty( $base_url ) ) {
			$base_url = trailingslashit( plugin_dir_url( static::FILE ) );
		}

		return $base_url;
	}

	/**
	 * Fetches the base path for this plugin.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public static function get_base_path() {
		static $base_path;

		if ( empty( $base_path ) ) {
			$base_path = trailingslashit( dirname( static::FILE ) );
		}

		return $base_path;
	}

	/**
	 * Loads all functions for this plugin.
	 *
	 * @since 1.0
	 */
	protected function load_functions() {
		include static::get_base_path() . 'src/functions/variables.php';
	}

	/**
	 * Register internationalization loading for this plugin.
	 *
	 * @since 1.0
	 */
	protected function register_i18n() {
		load_plugin_textdomain( 'gk-gravityactions', false, basename( static::get_base_path() ) . '/languages' );
	}
}