<?php
/**
 * Plugin Name:     GravityExport
 * Version:         1.0.5
 * Plugin URI:      https://gravityview.co/extensions/gravityexport/
 * Description:     Export Gravity Forms entries to multiple formats locally or to remote locations.
 * Author:          GravityView
 * Author URI:      https://gravityview.co
 * License:         GPL2
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     gk-gravityexport
 * Domain Path:     /languages
 */

use GFExcel\GFExcelAdmin;
use League\Container\Container;
use GravityKit\GravityExport\GravityExport;

defined( 'ABSPATH' ) or exit;

if ( ! defined( 'GK_GRAVITYEXPORT_MIN_LITE_VERSION' ) ) {
	define( 'GK_GRAVITYEXPORT_MIN_LITE_VERSION', '1.9.0' );
}

if ( ! defined( 'GK_GRAVITYEXPORT_MIN_PHP_VERSION' ) ) {
	define( 'GK_GRAVITYEXPORT_MIN_PHP_VERSION', '7.3' );
}

if ( ! defined( 'GK_GRAVITYEXPORT_PLUGIN_FILE' ) ) {
	define( 'GK_GRAVITYEXPORT_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'GK_GRAVITYEXPORT_PLUGIN_VERSION' ) ) {
	define( 'GK_GRAVITYEXPORT_PLUGIN_VERSION', '1.0.5' );
}

if ( ! defined( 'GK_GRAVITYEXPORT_PLUGIN_NAME' ) ) {
	define( 'GK_GRAVITYEXPORT_PLUGIN_NAME', esc_html__( 'GravityExport', 'gk-gravityexport' ) );
}

if ( ! defined( 'GK_GRAV`ITYEXPORT_PLUGIN_NAME_SHORT' ) ) {
	define( 'GK_GRAVITYEXPORT_PLUGIN_NAME_SHORT', esc_html__( 'GravityExport', 'gk-gravityexport' ) );
}

if ( ! defined( 'GK_GRAVITYEXPORT_PLUGIN_SLUG' ) ) {
	define( 'GK_GRAVITYEXPORT_PLUGIN_SLUG', 'gravityexport' );
}

function gk_gravityexport_load() {
	if ( version_compare( phpversion(), GK_GRAVITYEXPORT_MIN_PHP_VERSION, '<' ) ) {
		$show_minimum_php_version_message = function () {
			$message = wpautop( strtr(
				esc_html__( 'GravityExport requires PHP [version] or newer.', 'gk-gravityexport' ),
				[ '[version]' => GK_GRAVITYEXPORT_MIN_PHP_VERSION ]
			) );

			echo "<div class='error'>$message</div>";
		};

		add_action( 'admin_notices', $show_minimum_php_version_message );

		return;
	}

	$autoload          = __DIR__ . '/vendor/autoload.php';
	$autoload_prefixed = __DIR__ . '/vendor_prefixed/autoload.php';

	if ( ! file_exists( $autoload ) || ! file_exists( $autoload_prefixed ) ) {
		$show_incomplete_install_message = function () {
			$message = wpautop( strtr(
				esc_html__( 'GravityExport is missing some core files. Please re-install the plugin.', 'gk-gravityexport' ),
				[
					'[url]'     => '<a href="https://wordpress.org/plugins/gf-entries-in-excel/">',
					'[/url]'    => '</a>',
					'[version]' => GK_GRAVITYEXPORT_MIN_LITE_VERSION
				]
			) );

			echo "<div class='error'>$message</div>";
		};

		add_action( 'admin_notices', $show_incomplete_install_message );

		return;
	}

	require_once( $autoload );

	require_once( $autoload_prefixed );

	if ( ! defined( 'GFEXCEL_PLUGIN_FILE' ) ) {
		$show_missing_lite_plugin_message = function () {
			$message = wpautop( strtr(
				esc_html__( 'GravityExport requires an activated installation of [url]GravityExport Lite[/url].', 'gk-gravityexport' ),
				[
					'[url]'  => '<a href="https://wordpress.org/plugins/gf-entries-in-excel/">',
					'[/url]' => '</a>'
				]
			) );

			echo "<div class='error'>$message</div>";
		};

		add_action( 'admin_notices', $show_missing_lite_plugin_message );

		return;
	}

	if ( ! defined( 'GFEXCEL_PLUGIN_VERSION' ) || version_compare( GFEXCEL_PLUGIN_VERSION, GK_GRAVITYEXPORT_MIN_LITE_VERSION, '<' ) ) {
		$show_incorrect_lite_plugin_version_message = function () {
			$message = wpautop( strtr(
				esc_html__( 'GravityExport requires [url]GravityExport Lite[/url] version [version] or newer.', 'gk-gravityexport' ),
				[
					'[url]'     => '<a href="https://wordpress.org/plugins/gf-entries-in-excel/">',
					'[/url]'    => '</a>',
					'[version]' => GK_GRAVITYEXPORT_MIN_LITE_VERSION
				]
			) );

			echo "<div class='error'>$message</div>";
		};

		add_action( 'admin_notices', $show_incorrect_lite_plugin_version_message );

		return;
	}

	add_action( 'gfexcel_loaded', static function ( $container ) {
		if ( ! $container instanceof Container ) {
			return;
		}

		// Bootstrap and catch exceptions.
		try {
			( new GravityExport( $container, plugin_dir_url( __FILE__ ) . 'public/' ) )
				->registerServiceProviders()
				->registerAddOns();

			// Initialize add-ons.
			new \GravityKit\GravityExport\MultiRow\Plugin();
			new \GravityKit\GravityExport\PdfRenderer\Plugin( GFExcelAdmin::get_instance() );
		} catch ( \Exception $e ) {
			$show_incomplete_install_message = function () use ( $e ) {
				$message = wpautop( strtr(
					esc_html__( 'GravityExport failed to initialize: [error]. Please re-install the plugin or [url]contact support[/url].', 'gk-gravityexport' ),
					[
						'[url]'   => '<a href="https://gravityview.co/support/">',
						'[/url]'  => '</a>',
						'[error]' => $e->getMessage()
					]
				) );

				echo "<div class='error'>$message</div>";
			};

			add_action( 'admin_notices', $show_incomplete_install_message );
		}
	} );
}

add_action( 'plugins_loaded', 'gk_gravityexport_load', 1 );
