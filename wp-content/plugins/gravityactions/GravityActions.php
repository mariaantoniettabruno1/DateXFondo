<?php
/**
 * @wordpress-plugin
 * Plugin Name:       GravityActions
 * Plugin URI:        https://gravityview.co/extensions/gravityactions/
 * Description:       A simple, powerful way to perform actions on many Gravity Forms entries at once.
 * Version:           1.0.1
 * Author:            GravityView
 * Author URI:        https://gravityview.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gk-gravityactions
 * Domain Path:       /languages
 */

namespace GravityKit\GravityActions;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define the only constants that we need from this file, all the others should be on the
 * main {@see \GravityKit\GravityActions\Plugin} class.
 */
define( 'GK_GRAVITYACTIONS_FILE', __FILE__ );

define( 'GK_GRAVITYACTIONS_VERSION', '1.0.1' );

/**
 * Autoload Classes from composer.
 */
require_once( 'vendor/autoload.php' );

/**
 * The code that runs during plugin activation.
 */
register_activation_hook( GK_GRAVITYACTIONS_FILE, [ Plugin::class, 'activate' ] );

/**
 * The code that runs during plugin deactivation.
 */
register_deactivation_hook( GK_GRAVITYACTIONS_FILE, [ Plugin::class, 'deactivate' ] );

/**
 * Initializes the whole plugin
 */
Plugin::instance();
