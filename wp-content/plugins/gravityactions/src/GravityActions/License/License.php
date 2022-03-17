<?php

namespace GravityKit\GravityActions\License;

use Gravity_Forms\Gravity_Forms\Settings\Fields\Base;
use GravityKit\GravityActions\Addon;
use GravityKit\GravityActions\Plugin;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly

class License {

	/**
	 * @since    1.0
	 * @constant Assets handle.
	 */
	const ASSETS_HANDLE = 'gk-gravityactions-license';

	/**
	 * @since 1.0
	 * @var integer Download ID on gravityview.co
	 */
	const ITEM_ID = 680159;

	/**
	 * @since 1.0
	 * @var string
	 */
	const AUTHOR = 'GravityKit';

	/**
	 * @since 1.0
	 * @var string
	 */
	const url = 'https://gravityview.co';

	/**
	 * @since 1.0
	 * @var EDDPluginUpdater
	 */
	private $edd_plugin_updater;

	/**
	 * @since 1.0
	 * @var Addon
	 */
	private $addon;

	/**
	 * @since 1.0
	 * @var License
	 */
	public static $instance;

	/**
	 * GravityActionsLicense constructor.
	 *
	 * @since 1.0
	 *
	 * @param Addon $addon
	 *
	 * @return void
	 */
	public function __construct( Addon $addon ) {
		$this->addon = $addon;

		$this->edd_plugin_updater = new EDDPluginUpdater(
			self::url,
			Plugin::FILE,
			$this->_get_edd_settings()
		);

		add_action( 'wp_ajax_gravityactions_license', [ $this, 'license_call' ] );
		add_action( 'wp_ajax_nopriv_gravityactions_license', [ $this, 'license_call' ] );
		add_filter( 'gform_noconflict_scripts', [ $this, 'register_noconflict_scripts' ] );
	}

	/**
	 * Get class instance.
	 *
	 * @since 1.0
	 *
	 * @param Addon $addon
	 *
	 * @return License
	 */
	public static function get_instance( Addon $addon ) {
		if ( empty( self::$instance ) ) {
			self::$instance = new self( $addon );
		}

		return self::$instance;
	}

	/**
	 * Whitelist GravityActions' license script.
	 *
	 * @since 1.0
	 *
	 * @param array $scripts Existing no-conflict whitelist.
	 *
	 * @return array Whitelist with script added
	 */
	public function register_noconflict_scripts( $scripts ) {

		$scripts[] = self::ASSETS_HANDLE;

		return $scripts;
	}

	/**
	 * Settings fields.
	 *
	 * @since 1.0
	 *
	 * @return \array[][]
	 */
	public function plugin_settings_fields() {
		return [
			'fields' => [
				[
					'name'          => 'license_key',
					'required'      => true,
					'label'         => __( 'Support License Key', 'gk-gravityactions' ),
					'description'   => '<div class="clear">' . esc_html__( 'Enter the license key that was sent to you on purchase. This enables plugin updates &amp; support.', 'gk-gravityactions' ) . '</div>',
					'type'          => 'edd_license',
					'default_value' => '',
					'class'         => 'activate code regular-text edd-license-key',
				],
				[
					'name'          => 'license_key_response',
					'default_value' => '',
					'type'          => 'hidden',
				],
				[
					'name'          => 'license_key_status',
					'default_value' => '',
					'type'          => 'hidden',
				],
			],
		];
	}

	/**
	 * Check whether the license is valid.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public function license_is_valid() {
		return 'valid' === $this->addon->get_plugin_setting( 'license_key_status' );
	}

	/**
	 * License setting field.
	 *
	 * @since 1.0
	 *
	 * @param Base $field
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function settings_edd_license_activation( $field, $echo ) {
		wp_enqueue_script( self::ASSETS_HANDLE, plugin_dir_url( Plugin::FILE ) . 'src/assets/js/license.js', [ 'jquery' ] );

		$status = trim( $this->addon->get_plugin_setting( 'license_key_status' ) );
		$key    = $this->addon->get_plugin_setting( 'license_key' );

		if ( ! empty( $key ) ) {
			$response = $this->addon->get_plugin_setting( 'license_key_response' );
			$response = is_array( $response ) ? (object) $response : json_decode( $response );
		} else {
			$response = array();
		}

		wp_localize_script( self::ASSETS_HANDLE, 'GravityActionsLicense', [
			'license_box' => $this->get_license_message( $response ),
		] );

		if ( $this->addon->is_gravityforms_supported( '2.5-beta' ) ) {
			wp_register_style( self::ASSETS_HANDLE, false );
			wp_enqueue_style( self::ASSETS_HANDLE );

			$style = <<<CSS
.gv-edd-button-wrapper {
	margin: 10px 0 10px 0;
}

.gv-edd-button-wrapper > input[name*="activate"] {
	margin-left: 0 !important;
}

#gv-edd-status {
	margin-bottom: 10px;
}
CSS;
			wp_add_inline_style( self::ASSETS_HANDLE, $style );
		}

		$fields = [
			[
				'name'              => 'edd-activate',
				'value'             => __( 'Activate License', 'gk-gravityactions' ),
				'data-pending_text' => __( 'Verifying license&hellip;', 'gk-gravityactions' ),
				'data-edd_action'   => 'activate_license',
				'class'             => 'button-primary primary',
			],
			[
				'name'              => 'edd-deactivate',
				'value'             => __( 'Deactivate License', 'gk-gravityactions' ),
				'data-pending_text' => __( 'Deactivating license&hellip;', 'gk-gravityactions' ),
				'data-edd_action'   => 'deactivate_license',
				'class'             => ( empty( $status ) ? 'button-primary primary hide' : 'button-primary primary' ),
			],
			[
				'name'              => 'edd-check',
				'value'             => __( 'Check License', 'gk-gravityactions' ),
				'data-pending_text' => __( 'Verifying license&hellip;', 'gk-gravityactions' ),
				'title'             => 'Check the license before saving it',
				'data-edd_action'   => 'check_license',
				'class'             => 'button-secondary white',
			],
		];

		$class = 'button gv-edd-action';

		$class .= ( ! empty( $key ) && $status !== 'valid' ) ? '' : ' hide';

		$submit = '<div class="gv-edd-button-wrapper">';
		foreach ( $fields as $field ) {
			$field['type']  = 'button';
			$field['class'] = isset( $field['class'] ) ? $field['class'] . ' ' . $class : $class;
			$field['style'] = 'margin-left: 10px;';
			$submit         .= $this->addon->settings_submit( $field, $echo );
		}
		$submit .= '</div>';

		return $submit;
	}

	/**
	 * Generate the array of settings passed to the EDD license call.
	 *
	 * @since 1.0
	 *
	 * @param string $action  The action to send to edd, such as `check_license`.
	 * @param string $license The license key to have passed to EDD.
	 *
	 * @return array
	 */
	function _get_edd_settings( $action = '', $license = '' ) {
		// Retrieve our license key from the DB.
		$license_key = empty( $license ) ? trim( $this->addon->get_plugin_setting( 'license_key' ) ) : $license;

		$settings = [
			'version'   => urlencode( $this->addon->get_version() ),
			'license'   => urlencode( $license_key ),
			'item_name' => urlencode( $this->addon->get_short_title() ),
			'item_id'   => self::ITEM_ID,
			'author'    => urlencode( self::AUTHOR ),
			'url'       => urlencode( home_url() ),
			'beta'      => intval( $this->addon->get_plugin_setting( 'beta' ) ),
		];

		if ( ! empty( $action ) ) {
			$settings['edd_action'] = esc_attr( $action );
		}

		return $settings;
	}

	/**
	 * Perform the remote call.
	 *
	 * @since 1.0
	 *
	 * @param array  $data    AJAX request data.
	 * @param string $license The license key to have passed to EDD.
	 *
	 * @return object|void
	 */
	private function _license_get_remote_response( $data, $license = '' ) {
		$api_params = $this->_get_edd_settings( $data['edd_action'], $license );

		$url = add_query_arg( $api_params, self::url );

		$response = wp_remote_get( $url, [
			'timeout'   => 15,
			'sslverify' => false,
		] );

		if ( is_wp_error( $response ) ) {
			return;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// Not JSON.
		if ( empty( $license_data ) ) {
			delete_transient( 'gk_GravityActions_' . esc_attr( $data['field_id'] ) . '_valid' );

			return;
		}

		// Store the license key inside the data array.
		$license_data->license_key = $license;

		return $license_data;
	}

	/**
	 * Generate the status message displayed in the license field.
	 *
	 * @since 1.0
	 *
	 * @param $license_data
	 *
	 * @return string
	 */
	function get_license_message( $license_data ) {
		if ( empty( $license_data ) ) {
			$class   = 'hide';
			$message = '';
		} else {
			$class = ! empty( $license_data->error ) ? 'error' : $license_data->license;

			$renewal_url = ! empty( $license_data->renewal_url ) ? $license_data->renewal_url : 'https://gravityview.co/account/';

			$message = sprintf( '<p><strong>%s: %s</strong></p>', $this->strings( 'status' ), $this->strings( $license_data->license, $renewal_url ) );

			if ( $this->addon->is_gravityforms_supported( '2.5-beta' ) ) {
				$message = wpautop( $message );
			}
		}

		return $this->generate_license_box( $message, $class );
	}

	/**
	 * Generate the status message box HTML based on the current status.
	 *
	 * @since 1.0
	 *
	 * @param string $message
	 * @param string $class
	 *
	 * @return string
	 */
	private function generate_license_box( $message, $class = '' ) {
		$message = ! empty( $message ) ? $message : '<p><strong></strong></p>';

		if ( $this->addon->is_gravityforms_supported( '2.5-beta' ) ) {
			switch ( $class ) {
				case 'valid':
					$class .= ' success';
					break;
				case 'invalid':
					$class .= ' error';
					break;
				default:
					$class .= ' warning';
			}

			$template = '<div id="gv-edd-status" class="below-h2 alert %s">%s</div>';
		} else {
			$template = '<div id="gv-edd-status" class="below-h2 inline gv-edd-message %s">%s</div>';
		}

		return sprintf( $template, esc_attr( $class ), $message );
	}

	/**
	 * Perform the call to EDD based on the AJAX call or passed data.
	 *
	 * @since 1.0
	 *
	 * @param array  $data       {
	 *
	 * @type string  $license    The license key.
	 * @type string  $edd_action The EDD action to perform, like `check_license`.
	 * @type string  $field_id   The ID of the field to check.
	 * @type boolean $update     Whether to update plugin settings. Prevent updating the data by setting an `update` key to false.
	 * @type string  $format     If `object`, return the object of the license data. Else, return the JSON-encoded object.
	 * }
	 *
	 * @return false|object|string|void
	 */
	public function license_call( $data = [] ) {
		$is_ajax = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
		$data    = empty( $data ) ? $_POST['data'] : $data;

		if ( $is_ajax && empty( $data['license'] ) ) {
			die( -1 );
		}

		$license      = esc_attr( rgget( 'license', $data ) );
		$license_data = $this->_license_get_remote_response( $data, $license );

		// Empty is returned when there's an error.
		if ( ! $license_data ) {
			if ( $is_ajax ) {
				exit( json_encode( [] ) );
			} else { // Non-ajax call
				return json_encode( [] );
			}
		}

		$license_data->message = $this->get_license_message( $license_data );

		$json = json_encode( $license_data );

		$update_license = ( ! isset( $data['update'] ) || ! empty( $data['update'] ) );

		$is_check_action_button = ( 'check_license' === $data['edd_action'] && defined( 'DOING_AJAX' ) && DOING_AJAX );

		// Failed is the response from trying to de-activate a license and it didn't work.
		// This likely happened because people entered in a different key and clicked "Deactivate",
		// meaning to deactivate the original key. We don't want to save this response, since it is
		// most likely a mistake.
		if ( $license_data->license !== 'failed' && ! $is_check_action_button && $update_license ) {
			if ( ! empty( $data['field_id'] ) ) {
				set_transient( 'gk_GravityActions_' . esc_attr( $data['field_id'] ) . '_valid', $license_data, DAY_IN_SECONDS );
			}

			$this->license_call_update_settings( $license_data, $data );

		}

		if ( $is_ajax ) {
			exit( $json );
		} else { // Non-ajax call.
			return ( rgget( 'format', $data ) === 'object' ) ? $license_data : $json;
		}
	}

	/**
	 * Update the license after fetching it.
	 *
	 * @since 1.0
	 *
	 * @param object $license_data
	 *
	 * @return void
	 */
	private function license_call_update_settings( $license_data, $data ) {

		// Update option with passed data license
		$settings = $this->addon->get_current_settings();

		$settings['license_key']          = $license_data->license_key = esc_attr( trim( $data['license'] ) );
		$settings['license_key_status']   = $license_data->license;
		$settings['license_key_response'] = (array) $license_data;

		$this->addon->update_plugin_settings( $settings );
	}

	/**
	 * Override the text used in the Redux Framework EDD field extension.
	 *
	 * @since 1.0
	 *
	 * @param string|null $status      Status to get. If empty, get all strings.
	 * @param string      $renewal_url The URL to renew the current license. GravityView account page if license not set.
	 *
	 * @return array Modified array of content
	 */
	public function strings( $status = null, $renewal_url = '' ) {
		$strings = array(
			'status'              => esc_html__( 'Status', 'gk-gravityactions' ),
			'error'               => esc_html__( 'There was an error processing the request.', 'gk-gravityactions' ),
			'failed'              => esc_html__( 'Could not deactivate the license. The license key you attempted to deactivate may not be active or valid.', 'gk-gravityactions' ),
			'site_inactive'       => esc_html__( 'The license key is valid, but it has not been activated for this site.', 'gk-gravityactions' ),
			'inactive'            => esc_html__( 'The license key is valid, but it has not been activated for this site.', 'gk-gravityactions' ),
			'no_activations_left' => esc_html__( 'Invalid: this license has reached its activation limit.', 'gk-gravityactions' ),
			'deactivated'         => esc_html__( 'The license has been deactivated.', 'gk-gravityactions' ),
			'valid'               => esc_html__( 'The license key is valid and active.', 'gk-gravityactions' ),
			'invalid'             => esc_html__( 'The license key entered is invalid.', 'gk-gravityactions' ),
			'missing'             => esc_html__( 'The license key was not defined.', 'gk-gravityactions' ), // Missing is "the license couldn't be found", not "you submitted an empty license"
			'revoked'             => esc_html__( 'This license key has been revoked.', 'gk-gravityactions' ),
			'invalid_item_id'     => esc_html__( 'This license key does not have access to this plugin.', 'gk-gravityactions' ),
			'expired'             => sprintf( esc_html__( 'This license key has expired. %sRenew your license on the GravityView website%s', 'gk-gravityactions' ), '<a href="' . esc_url( $renewal_url ) . '" rel="external">', '</a>' ),
			'verifying_license'   => esc_html__( 'Verifying license&hellip;', 'gk-gravityactions' ),
			'activate_license'    => esc_html__( 'Activate License', 'gk-gravityactions' ),
			'deactivate_license'  => esc_html__( 'Deactivate License', 'gk-gravityactions' ),
			'check_license'       => esc_html__( 'Verify License', 'gk-gravityactions' ),
		);

		if ( empty( $status ) ) {
			return $strings;
		}

		if ( isset( $strings[ $status ] ) ) {
			return $strings[ $status ];
		}

		return null;
	}
}
