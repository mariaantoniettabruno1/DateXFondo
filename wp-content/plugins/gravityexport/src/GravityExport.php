<?php

namespace GravityKit\GravityExport;

use GravityKit\GravityExport\Addon\GravityExportAddon;
use GravityKit\GravityExport\Filters\Addon\FiltersFeedAddon;
use GravityKit\GravityExport\Filters\ServiceProvider\FiltersProvider;
use GravityKit\GravityExport\PdfRenderer\ServiceProvider\PdfProvider;
use GFExcel\Plugin\BasePlugin;
use GravityKit\GravityExport\Save\Addon\SaveAddon;
use GravityKit\GravityExport\Save\ServiceProvider\ServiceProvider;
use League\Container\Container;

/**
 * The main plugin file.
 *
 * @since 1.0
 */
class GravityExport extends BasePlugin {
	/**
	 * @since 1.0
	 * @var string Assets handle.
	 */
	const ASSETS_HANDLE = 'gk-gravityexport';
	/**
	 * @inheritdoc
	 *
	 * @since 1.0
	 */
	protected $addons = [
		GravityExportAddon::class,
		FiltersFeedAddon::class,
		SaveAddon::class,
	];

	/**
	 * @inheritdoc
	 *
	 * @since 1.0
	 */
	public function __construct( Container $container, string $assets_dir = null ) {
		parent::__construct( $container, $assets_dir );

		add_filter( 'gform_noconflict_scripts', [ $this, 'register_no_conflicts' ] );
		add_filter( 'gform_noconflict_styles', [ $this, 'register_no_conflicts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_ui_assets' ] );
	}

	/**
	 * Registers available service providers.
	 *
	 * @since 1.0
	 */
	public function registerServiceProviders(): self {
		$this->container->addServiceProvider( new FiltersProvider() );
		$this->container->addServiceProvider( new PdfProvider() );
		$this->container->addServiceProvider( new ServiceProvider() );

		return $this;
	}

	/**
	 * Enqueues UI scripts/styles.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function register_ui_assets(): void {
		if ( ! $this->is_gravityexport_page() ) {
			return;
		}

		wp_enqueue_script( self::ASSETS_HANDLE, plugin_dir_url( GK_GRAVITYEXPORT_PLUGIN_FILE ) . 'assets/js/gravityexport.js', [], GK_GRAVITYEXPORT_PLUGIN_VERSION );
		wp_enqueue_style( self::ASSETS_HANDLE, plugin_dir_url( GK_GRAVITYEXPORT_PLUGIN_FILE ) . 'assets/css/gravityexport.css', [], GK_GRAVITYEXPORT_PLUGIN_VERSION );
	}

	/**
	 * Adds UI assets to GF's no-conflict list.
	 *
	 * @since 1.0
	 *
	 * @param array $registered
	 *
	 * @return array $registered
	 *
	 */
	public function register_no_conflicts( array $registered ): array {
		$registered[] = self::ASSETS_HANDLE;

		return $registered;
	}

	/**
	 * Detects if the current page is a GravityExport page.
	 *
	 * @return bool
	 */
	public function is_gravityexport_page(): bool {
		$form_or_feed_settings_page = in_array( rgget( 'page' ), [ 'gf_settings', 'gf_edit_forms' ] );
		$gravityexport_subview      = strpos( rgget( 'subview' ), 'gravityexport' ) !== false;

		return $form_or_feed_settings_page && $gravityexport_subview;
	}
}
