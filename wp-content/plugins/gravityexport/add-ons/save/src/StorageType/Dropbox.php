<?php

namespace GravityKit\GravityExport\Save\StorageType;

use GravityKit\GravityExport\Addon\GravityExportAddon;
use GravityKit\GravityExport\Save\Addon\SaveAddon;
use GravityKit\GravityExport\Save\Service\ConnectionManagerService;
use GravityKit\GravityExport\Save\Service\PasswordService;
use GravityKit\GravityExport\Save\Service\StorageService;
use GravityKit\GravityExport\League\Flysystem\Filesystem;
use GravityKit\GravityExport\League\Flysystem\FilesystemAdapter;

/**
 * Storage type that stores to a Dropbox folder.
 *
 * @since 1.0
 */
class Dropbox extends FlySystemStorageType {
	/**
	 * The field that holds the authorization token.
	 *
	 * @since 1.0
	 * @var string
	 */
	public const FIELD_AUTH_TOKEN = 'dropbox_auth_token';

	/**
	 * @since 1.0
	 * @var ConnectionManagerService Connection manager service.
	 */
	private $connection_manager_service;

	/**
	 * The password service.
	 *
	 * @since 1.0
	 * @var PasswordService
	 */
	private $password_service;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function __construct( StorageService $service, PasswordService $password_service, ConnectionManagerService $connection_manager_service ) {
		parent::__construct( $service );

		add_filter( 'gravitykit/gravityexport/settings/sections', array( $this, 'getGlobalSettings' ) );

		$this->password_service           = $password_service;
		$this->connection_manager_service = $connection_manager_service;
	}

	public function getGlobalSettings( array $sections ): array {
		$sections[] = [
			'fields' => [
				[
					'label'               => esc_html__( 'Dropbox Access Token', 'gk-gravityexport' ),
					'type'                => 'text',
					'required'            => false,
					'name'                => static::FIELD_AUTH_TOKEN,
					'class'               => 'large-text',
					'tooltip'             => esc_html__( 'Holds the "Generated access token" for your Dropbox app. This value will be encrypted when saved.', 'gk-gravityexport' ),
					'description'         => sprintf(
						'<span style="display: block; clear: both;">' . esc_html__( 'A token can be generated in the %sApp Console%s.', 'gk-gravityexport' ) . '</span>',
						'<a href="https://www.dropbox.com/developers/apps" target="_blank">', ' <span class="dashicons dashicons-external" style="font-size: 12px; width: 12px; height: 12px; line-height: 16px;"></span></a>'
					),
					'placeholder'         => esc_html__( 'Your Dropbox authorization token.', 'gk-gravityexport' ),
					'save_callback'       => function ( $field, $value ) {
						$decrypted = $this->password_service->decrypt( $value );

						// Already encrypted; no need to re-encrypt.
						if ( $decrypted ) {
							return $value;
						}

						return $this->password_service->encrypt( $value );
					},
					'validation_callback' => array( $this, 'auth_key_validation_callback' ),
					'feedback_callback'   => array( $this, 'auth_key_feedback_callback' ),
					'after_input'         => $this->auth_key_after_input_callback(),
				],
			]
		];

		return $sections;
	}

	/**
	 * Adds "Modify Dropbox Token" button after the Access Token input field.
	 *
	 * @return string HTML containing a button that makes the Dropbox token readonly/editable.
	 */
	public function auth_key_after_input_callback() {
		ob_start();
		?>
        <div class="gv-edd-button-wrapper alignleft" id="toggle-auth-readonly-container">
            <button id='toggle-auth-readonly' class="button button-secondary white"><?php
				echo esc_html__( 'Modify Dropbox Token', 'gk-gravityexport' ); ?></button>
        </div>

        <script>
            jQuery( '#dropbox_auth_token' ).attr( 'readonly', function () {

                if( jQuery( this ).val().length === 0 || jQuery( this ).parent('.gform-settings-input__container--feedback-error').length ) {

                	// Hide the toggle to make the Dropbox token editable, since it's not set yet.
                	jQuery('#toggle-auth-readonly-container').hide();

                	return null;
                }

                return 'readonly';
            } );

            jQuery( '#toggle-auth-readonly' ).on( 'click', function ( e ) {
                e.preventDefault();
                jQuery( '#dropbox_auth_token' ).attr( 'readonly', null );
                jQuery( this ).attr( 'disabled', 'disabled' );
                return false;
            } );
        </script>
		<?php

		return ob_get_clean();
	}

	/**
	 * @param array|\Gravity_Forms\Gravity_Forms\Settings\Fields\Base $field    The field properties.
	 * @param string                                                  $auth_key The field value.
	 *
	 * @return bool
	 */
	public function auth_key_validation_callback( $field, string $auth_key ) {

	    if ( empty( $auth_key ) ) {
			return null;
		}

		// Check connection, return `true` if it connects.
		$adapter = $this->connection_manager_service->getDropboxAdapter( $auth_key );

		try {
			$adapter->getClient()->getAccountInfo();
			return true;
		} catch ( \GravityKit\GravityExport\Spatie\Dropbox\Exceptions\BadRequest $bad_request ) {
			// TODO: Try writing a file to make sure it has correct permissions.
			switch ( $bad_request->response->getStatusCode() ) {
				case '400':
				default:
				    $error_message = esc_html__( 'Your Dropbox access token is invalid.', 'gk-gravityexport' );

                    if ( is_array( $field ) ) {
	                    GravityExportAddon::get_instance()->set_field_error( $field, $error_message );
                    } else {
                        $field->set_error( $error_message );
                    }
					break;
			}
		} catch ( \Exception $e ) {

		    $error_message = esc_html__( 'An error occurred when validating the Dropbox access token.', 'gk-gravityexport' );

			if ( is_array( $field ) ) {
				GravityExportAddon::get_instance()->set_field_error( $field, $error_message );
			} else {
				$field->set_error( $error_message );
			}
			SaveAddon::get_instance()->log_error( $e->getMessage() );
		}

		return false;
	}

	/**
	 * @param string|null                                             $auth_key The field value.
	 * @param array|\Gravity_Forms\Gravity_Forms\Settings\Fields\Text $field    The field properties.
	 *
	 * @return bool|null Boolean if setting exists. True: valid; False: invalid. Returns null if no setting value is set.
	 */
	public function auth_key_feedback_callback( ?string $auth_key, $field ) {

		if ( is_array( $field ) ) {
			return empty( GravityExportAddon::get_instance()->get_field_errors( $field ) );
		}

	    if ( $field->get_error() ) {
			return false;
		}

		return ! empty( $auth_key ) ? true : null;
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected function getTargetFileSystemAdapter( ?array $feed = null ): FilesystemAdapter {
		return $this->connection_manager_service->getDropboxAdapter( $this->getAuthToken(), rgar( $feed ?? $this->getFeedMeta(), 'storage_path', '' ) );
	}

	/**
	 * @inheritdoc
	 * Overwritten case_sensitive
	 * @since 1.0
	 */
	protected function getTargetFileSystem( string $path = '' ): object {
		return new Filesystem( $this->getTargetFileSystemAdapter(), [ 'case_sensitive' => false ] );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getId(): string {
		return 'dropbox';
	}

	/**
	 * Should return the title of the storage type.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function getTitle(): string {
		return 'Dropbox';
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getIcon(): string {
		return 'fa-dropbox';
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function isDisabled(): bool {
		$has_token = GravityExportAddon::get_instance()->get_plugin_setting( self::FIELD_AUTH_TOKEN );

		return empty( $has_token );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getFeedFields( SaveAddon $feed ): array {
		return [
			[
				'label'       => esc_html_x( 'Path', 'File location path', 'gk-gravityexport' ),
				'type'        => 'text',
				'name'        => static::FIELD_STORAGE_PATH,
				'class'       => 'large-text',
				'tooltip'     => esc_html__( 'Set the path to write the file to.', 'gk-gravityexport' ),
				'description' => esc_html__( "If the GravityExport Dropbox app has full access to Dropbox, the path is relative to the root folder ('/'). Otherwise, it is relative to folder created specifically for your app (e.g., '/Apps/GravityExport/').", 'gk-gravityexport' ),
				'placeholder' => esc_html__( 'Defaults to the Dropbox Apps directory root.', 'gk-gravityexport' ),
			],
		];
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getStorageSettings( array $settings, array $feed ): array {
		$settings[ static::FIELD_AUTH_TOKEN ] = $this->password_service->decrypt( $this->getAuthToken() );

		return $settings;
	}

	/**
	 * Retrieves  auth token from  settings.
	 *
	 * @since 1.0
	 *
	 * @return string The auth token.
	 */
	private function getAuthToken(): string {
		return GravityExportAddon::get_instance()->get_plugin_setting( self::FIELD_AUTH_TOKEN ) ?: '';
	}
}
