<?php
/**
 * Plugin Name:       	GravityView - Entry Revisions
 * Plugin URI:        	https://gravityview.co/extensions/entry-revisions/
 * Description:       	Track changes to Gravity Forms entries and restore values from earlier versions.
 * Version:          	1.0.4
 * Author:            	GravityView
 * Author URI:        	https://gravityview.co
 * Text Domain:       	gravityview-entry-revisions
 * License:           	GPLv2 or later
 * License URI: 		http://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:			/languages
 */

/**
 * The plugin version number
 *
 * @since 1.0
 */
define( 'GV_ENTRY_REVISIONS_VERSION', '1.0.4' );

/** @define "GV_ENTRY_REVISIONS_DIR" "./" The absolute path to the plugin directory */
define( 'GV_ENTRY_REVISIONS_DIR', plugin_dir_path( __FILE__ ) );

/**
 * The path to this file
 *
 * @since 1.0
 */
define( 'GV_ENTRY_REVISIONS_FILE', __FILE__ );

/**
 * Load Inline Edit plugin. Wrapper function to make sure GravityView_Extension has loaded.
 *
 * @since 1.0
 *
 * @return void
 */
function gravityview_entry_revisions_load() {

	if ( ! class_exists( 'GravityView_Extension' ) ) {
		include_once GV_ENTRY_REVISIONS_DIR . 'lib/class-gravityview-extension.php';
	}

	require_once GV_ENTRY_REVISIONS_DIR . 'class-gv-entry-revisions.php';

	GV_Entry_Revisions::get_instance();

	// Won't be loaded if `GFForms` doesn't exist
	if( class_exists('GV_Entry_Revisions_Settings') ) {
		GV_Entry_Revisions_Settings::get_instance();
	}
}

add_action( 'plugins_loaded', 'gravityview_entry_revisions_load', 20 );