<?php
namespace ZiUltimate\Admin;

use WP_Error;
use ZiUltimate\Plugin;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// Load licensing class
if ( ! class_exists( 'ZU_Plugin_Updater' ) ) {
	include dirname( __FILE__ ) . '/ZU_Plugin_Updater.php';
}

class License {

	const STORE_URL 			= 'https://ziultimate.com';
	const ITEM_ID 				= '128';
	const API_KEY_FIELD 		= 'ziultimate_license_key';
	const API_DETAILS_FIELD 	= 'ziultimate_license_details';
	const API_KEY_STATUS_FIELD 	= 'ziultimate_license_status';
	const API_EL_STATUS_FIELD 	= 'ziultimate_el';

	function __construct() {
		add_action( 'admin_init', array( $this, 'check_for_updates' ) );
	}

	/**
	 * Checking the plugin information
	 */
	public function check_for_updates() {
		// Don't proceed if the license is not valid
		if ( ! self::has_valid_license() ) {
			return false;
		}

		$license_key = self::get_license_key();

		// setup the updater
		new \ZU_Plugin_Updater(
			self::STORE_URL,
			Plugin::instance()->get_plugin_file(),
			[
				'version' => Plugin::instance()->get_version(),
				'license' => base64_decode( $license_key ),
				'item_id' => self::ITEM_ID,
				'author'  => 'Chinmoy Paul',
				'beta'    => false,
			]
		);
	}

	/**
	 * Validating the license key
	 * 
	 * @return bool
	 */
	public static function has_valid_license() {
		$license_details = self::get_license_details();
		$license_key     = self::get_license_key();
		$license_status  = self::get_license_status();

		if ( ! $license_key || $license_status !== 'valid' ) {
			return false;
		}

		if ( ! isset( $license_details->expires ) ) {
			return false;
		}

		$expire_date  = strtotime( $license_details->expires );
		$current_date = time();

		return ( ( $expire_date > $current_date ) || ( $license_details->expires === 'lifetime' ) );
	}

	public static function get_license_details() {
		return get_option( self::API_DETAILS_FIELD );
	}

	public static function get_license_key() {
		return get_option( self::API_KEY_FIELD );
	}

	public static function get_license_status() {
		return get_option( self::API_KEY_STATUS_FIELD );
	}

	/**
	 * Activating the license key
	 * 
	 * @param string license key
	 * @return mixed bool or message text
	 */
	public static function zu_acivate_license( $license ) {
		// run a quick security check
		if ( ! wp_verify_nonce( $_POST['zu_nonce_field'], 'zu_nonce_action' ) ) {
			return sprintf('<div class="notice notice-error"><p>%s</p></div>', __( 'An error occurred, please try again.' ));
		}

		$license = trim( $license );
		$license = filter_var( $license, FILTER_SANITIZE_STRING );

		if ( ! $license ) {
			return new \WP_Error( 'invalid_response', __( 'Invalid license.', 'ziultimate' ) );
		}

		// data to send in our API request
		$api_params = [
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => self::ITEM_ID,
			'url'        => home_url(),
		];

		// Call the custom API.
		$response = wp_remote_post(
			self::STORE_URL,
			[
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			]
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'ziultimate' );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {
				switch ( $license_data->error ) {
					case 'expired':
						$message = sprintf(
							__( 'Your license key expired on %s.', 'ziultimate' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'disabled':
					case 'revoked':
						$message = __( 'Your license key has been disabled.', 'ziultimate' );
						break;

					case 'missing':
						$message = __( 'Invalid license.', 'ziultimate' );
						break;

					case 'invalid':
					case 'site_inactive':
						$message = __( 'Your license is not active for this URL.', 'ziultimate' );
						break;

					case 'item_name_mismatch':
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'ziultimate' ), Plugin::instance()->get_plugin_data( 'Name' ) );
						break;

					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'ziultimate' );
						break;

					default:
						$message = __( 'An error occurred, please try again.', 'ziultimate' );
						break;
				}
			}
		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			//return new \WP_Error( 'invalid_response', $message );
			return sprintf('<div class="notice notice-error is-dismissible"><p>%s</p></div>', $message);
		}

		$valid_license = 'valid' === $license_data->license;

		// $license_data->license will be either "valid" or "invalid"
		update_option( self::API_KEY_STATUS_FIELD, $license_data->license );

		// Save the license in DB if valid
		if ( $valid_license ) {
			update_option( self::API_KEY_FIELD, base64_encode($license) );
			update_option( self::API_DETAILS_FIELD, $license_data );
			update_option( self::API_EL_STATUS_FIELD, substr($license, 5, 12) );
			$message = __('Your license key is activated successfully.', 'ziultimate');
			return sprintf('<div class="notice notice-info is-dismissible"><p>%s</p></div>', $message);
		}

		return false;
	}

	/**
	 * Disconnecting the license
	 * 
	 * @return string message
	 */
	public static function delete_license() {
		// retrieve the license from the database
		$license = trim( get_option( self::API_KEY_FIELD ) );

		// data to send in our API request
		$api_params = [
			'edd_action' => 'deactivate_license',
			'license'    => base64_decode( $license ),
			'item_id'    => self::ITEM_ID,
			'url'        => home_url(),
		];

		// Call the custom API.
		$response = wp_remote_post(
			self::STORE_URL,
			[
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			]
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'zionbuilder-pro' );
			}

			return sprintf('<div class="notice notice-error is-dismissible"><p>%s</p></div>', $message);
		}

		// decode the license data
		json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		delete_option( self::API_KEY_STATUS_FIELD );
		delete_option( self::API_KEY_FIELD );
		delete_option( self::API_DETAILS_FIELD );
		delete_option( self::API_EL_STATUS_FIELD );

		$message = __( 'License disconnected.', 'ziultimate' );

		return sprintf('<div class="notice notice-info is-dismissible"><p>%s</p></div>', $message);
	}
}