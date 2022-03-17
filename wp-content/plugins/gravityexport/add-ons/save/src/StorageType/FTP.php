<?php

namespace GravityKit\GravityExport\Save\StorageType;

use GravityKit\GravityExport\Save\Addon\SaveAddon;
use GravityKit\GravityExport\Save\Service\ConnectionManagerService;
use GravityKit\GravityExport\Save\Service\PasswordService;
use GravityKit\GravityExport\Save\Service\StorageService;
use GravityKit\GravityExport\League\Flysystem\FilesystemAdapter;

/**
 * Storage type that stores to an (s)ftp server.
 *
 * @since 1.0
 */
class FTP extends FlySystemStorageType {
	/**
	 * @since 1.0
	 * @var int Connection timeout in seconds.
	 */
	public const CONNECTION_TIMEOUT = 20;

	/**
	 * The field that holds the ftp host.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected const FIELD_HOST = 'ftp_host';

	/**
	 * The field that holds the ftp username.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected const FIELD_USERNAME = 'ftp_username';

	/**
	 * The field that holds the ftp password.
	 *
	 * @since 1.0
	 * @var string
	 */
	public const FIELD_PASSWORD = 'ftp_password';

	/**
	 * The field that holds the ftp port.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected const FIELD_PORT = 'ftp_port';

	/**
	 * The field that holds whether to use PASV mode.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected const FIELD_PASSIVE = 'ftp_passive';

	/**
	 * The field that holds whether to use SSL.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected const FIELD_SSL = 'ftp_ssl';

	/**
	 * The key value for the FTP option.
	 *
	 * @since 1.0
	 * @var int
	 */
	protected const FIELD_SSL_FTP = 0;

	/**
	 * The key value for the FTP + SSL option.
	 *
	 * @since 1.0
	 * @var int
	 */
	protected const FIELD_SSL_FTP_SSL = 1;

	/**
	 * The key value for the SFTP option.
	 *
	 * @since 1.0
	 * @var int
	 */
	protected const FIELD_SSL_SFTP = 2;

	/**
	 * The field that holds the path to the private key on the server.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected const FIELD_PRIVATE_KEY = 'sftp_private_key';

	/**
	 * The field that holds the path to the private key on the server.
	 *
	 * @since 1.0
	 * @var string
	 */
	protected const FIELD_PRIVATE_KEY_PASSPHRASE = 'sftp_private_key_passphrase';

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

		$this->password_service           = $password_service;
		$this->connection_manager_service = $connection_manager_service;
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getId(): string {
		return 'ftp';
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getTitle(): string {
		return esc_html_x( 'FTP Storage', 'Storage method', 'gk-gravityexport' );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getIcon(): string {
		return 'fa-server';
	}

	/**
	 * @inheritdoc
	 * @return bool
	 */
	public function isDisabled(): bool {
		return false;
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getFeedFields( SaveAddon $addon ): array {
		// SFTP is when SSL option is set to "2" or when it's not set at all (e.g., when switching to FTP from another connection type, in which case we default to SFTP).
		$is_sftp = ( 'ftp' === rgar( $addon->get_current_settings(), 'storage_type' ) && ( 2 === (int) rgar( $addon->get_current_settings(), 'ftp_ssl' ) || '' === rgar( $addon->get_current_settings(), 'ftp_ssl' ) ) );

		return [
			[
				'label'       => esc_html_x( 'Address', 'FTP server', 'gk-gravityexport' ),
				'type'        => 'text',
				'name'        => static::FIELD_HOST,
				'placeholder' => esc_html__( 'Host URL', 'gk-gravityexport' ),
			],
			[
				'label'         => esc_html__( 'Port', 'gk-gravityexport' ),
				'type'          => 'text',
				'input_type'    => 'number',
				'class'         => 'small',
				'name'          => static::FIELD_PORT,
				'default_value' => '22',
				'placeholder'   => 22,
			],
			[
				'label'   => esc_html__( 'SSL', 'gk-gravityexport' ),
				'type'    => 'radio',
				'name'    => static::FIELD_SSL,
				'tooltip' => esc_html__( 'Connect through Secure Sockets Layer (SSL).', 'gk-gravityexport' ),
				'value'   => 2,
				'choices' => [
					[ 'label' => esc_html_x( 'FTP (Insecure)', 'Choice selection', 'gk-gravityexport' ), 'value' => self::FIELD_SSL_FTP ],
					[ 'label' => esc_html_x( 'FTP + SSL', 'Choice selection', 'gk-gravityexport' ), 'value' => self::FIELD_SSL_FTP_SSL ],
					[ 'label' => esc_html_x( 'SFTP', 'Choice selection', 'gk-gravityexport' ), 'value' => self::FIELD_SSL_SFTP ],
				],
			],
			[
				'label'   => esc_html_x( 'Passive (PASV)', 'FTP mode', 'gk-gravityexport' ),
				'type'    => class_exists( 'Gravity_Forms\Gravity_Forms\Settings\Fields\Toggle' ) ? 'toggle' : 'radio',
				'name'    => static::FIELD_PASSIVE,
				'choices' => $this->noYesChoices(),
			],
			[
				'label'       => esc_html__( 'Username', 'gk-gravityexport' ),
				'type'        => 'text',
				'name'        => static::FIELD_USERNAME,
				'placeholder' => esc_html__( 'FTP Username', 'gk-gravityexport' ),
			],
			[
				'label'       => esc_html__( 'Path to Private Key', 'gk-gravityexport' ),
				'type'        => 'text',
				'name'        => static::FIELD_PRIVATE_KEY,
				'placeholder' => esc_html__( 'Path to private key on this server', 'gk-gravityexport' ),
				'hidden'      => ! $is_sftp,
			],
			[
				'label'         => esc_html__( 'Private Key Passphrase', 'gk-gravityexport' ),
				'type'          => 'text',
				'input_type'    => 'password',
				'name'          => static::FIELD_PRIVATE_KEY_PASSPHRASE,
				'description'   => esc_html__( 'The passphrase will be stored encrypted.', 'gk-gravityexport' ),
				'placeholder'   => esc_html__( 'Private Key Passphrase', 'gk-gravityexport' ),
				'hidden'        => ! $is_sftp,
				'save_callback' => function ( $field, $value ) {
					$decrypted = $this->password_service->decrypt( $value );

					// Already encrypted; no need to re-encrypt
					if ( $decrypted ) {
						return $value;
					}

					return $this->password_service->encrypt( $value );
				},
			],
			[
				'label'         => esc_html__( 'Password', 'gk-gravityexport' ),
				'type'          => 'text',
				'input_type'    => 'password',
				'name'          => static::FIELD_PASSWORD,
				'description'   => esc_html__( 'The password will be stored encrypted.', 'gk-gravityexport' ),
				'placeholder'   => esc_html__( 'FTP Password', 'gk-gravityexport' ),
				'save_callback' => function ( $field, $value ) {
					$decrypted = $this->password_service->decrypt( $value );

					// Already encrypted; no need to re-encrypt
					if ( $decrypted ) {
						return $value;
					}

					return $this->password_service->encrypt( $value );
				},
			],
			[
				'label'       => esc_html__( 'Remote Path', 'gk-gravityexport' ),
				'type'        => 'text',
				'name'        => static::FIELD_STORAGE_PATH,
				'description' => esc_html__( 'Path where the file will be saved. The path should be absolute.', 'gk-gravityexport' ),
				'placeholder' => esc_html__( '/path/to/directory', 'gk-gravityexport' ),
			],
		];
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected function getTargetFileSystemAdapter( ?array $feed = null ): FilesystemAdapter {
		$meta = $this->getFeedMeta( $feed );

		if ( 2 === (int) rgar( $meta, static::FIELD_SSL, false ) ) {
			return $this->connection_manager_service->getSftpAdapter(
				rgar( $meta, static::FIELD_HOST, '' ),
				(int) rgar( $meta, static::FIELD_PORT ),
				rgar( $meta, static::FIELD_USERNAME, '' ),
				rgar( $meta, static::FIELD_PASSWORD, '' ),
				rgar( $meta, static::FIELD_STORAGE_PATH ),
				rgar( $meta, static::FIELD_PRIVATE_KEY ) ?: null,
				rgar( $meta, static::FIELD_PRIVATE_KEY_PASSPHRASE ) ?: null,
			);
		};

		return $this->connection_manager_service->getFtpAdapter(
			rgar( $meta, static::FIELD_HOST, '' ),
			(int) rgar( $meta, static::FIELD_PORT ),
			(bool) rgar( $meta, static::FIELD_SSL, false ),
			(bool) rgar( $meta, static::FIELD_PASSIVE, false ),
			rgar( $meta, static::FIELD_USERNAME, '' ),
			rgar( $meta, static::FIELD_PASSWORD, '' ),
			rgar( $meta, static::FIELD_STORAGE_PATH ),
		);
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getStorageSettings( array $settings, array $feed ): array {
		$settings[ static::FIELD_PASSWORD ] = $this->password_service->decrypt(
			$feed['meta'][ static::FIELD_PASSWORD ] ?? ''
		);

		$settings[ static::FIELD_PRIVATE_KEY_PASSPHRASE ] = $this->password_service->decrypt(
			$feed['meta'][ static::FIELD_PRIVATE_KEY_PASSPHRASE ] ?? ''
		);

		return $settings;
	}
}
