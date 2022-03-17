<?php

namespace GravityKit\GravityExport\Save\Service;

use GravityKit\GravityExport\Save\Exception\SaveException;
use GravityKit\GravityExport\Save\Exception\ExpiredTokenException;
use GravityKit\GravityExport\League\Flysystem\Filesystem;
use GravityKit\GravityExport\League\Flysystem\FilesystemException;
use GravityKit\GravityExport\League\Flysystem\Ftp\FtpAdapter as FtpAdapter;
use GravityKit\GravityExport\League\Flysystem\Ftp\FtpConnectionOptions;
use GravityKit\GravityExport\League\Flysystem\Ftp\UnableToAuthenticate as UnableToAuthenticateFTP;
use GravityKit\GravityExport\League\Flysystem\PhpseclibV2\UnableToAuthenticate as UnableToAuthenticateSFTP;
use GravityKit\GravityExport\League\Flysystem\Ftp\UnableToConnectToFtpHost;
use GravityKit\GravityExport\League\Flysystem\Local\LocalFilesystemAdapter;
use GravityKit\GravityExport\League\Flysystem\MountManager;
use GravityKit\GravityExport\League\Flysystem\PhpseclibV2\SftpAdapter as SftpAdapter;
use GravityKit\GravityExport\League\Flysystem\PhpseclibV2\SftpConnectionProvider;
use GravityKit\GravityExport\League\Flysystem\PhpseclibV2\UnableToLoadPrivateKey;
use GravityKit\GravityExport\League\Flysystem\UnableToCopyFile;
use GravityKit\GravityExport\League\Flysystem\UnableToDeleteFile;
use GravityKit\GravityExport\Spatie\Dropbox\Client;
use GravityKit\GravityExport\Spatie\FlysystemDropbox\DropboxAdapter;

/**
 * Service that handles testing of storage services' connection.
 *
 * @since 1.0
 */
class ConnectionManagerService {
	/**
	 * @since 1.0
	 * @var int Connection timeout in seconds.
	 */
	const CONNECTION_TIMEOUT = 10;

	/**
	 * @since 1.0
	 * @var int SFTP port.
	 */
	const SFTP_PORT = 22;

	/**
	 * @since 1.0
	 * @var int FTP port.
	 */
	const FTP_PORT = 21;

	/**
	 * @since 1.0
	 * @var PasswordService Password service.
	 */
	private $password_service;

	/**
	 * @since 1.0
	 *
	 * @param PasswordService $password_service
	 */
	public function __construct( PasswordService $password_service ) {
		if ( ! function_exists( 'wp_tempnam' ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
		}

		$this->password_service = $password_service;
	}

	/**
	 * Returns FTP connection adapter.
	 *
	 * @since 1.0
	 *
	 * @param string $host
	 * @param int    $port
	 * @param bool   $ssl
	 * @param bool   $passive
	 * @param string $username
	 * @param string $password
	 * @param string $path
	 * @param null   $timeout
	 *
	 * @return FtpAdapter
	 */
	public function getFtpAdapter( string $host, int $port, bool $ssl, bool $passive, string $username, string $password, string $path, $timeout = null ): FtpAdapter {
		$password = $this->maybeDecryptPassword( $password );

		return new FtpAdapter(
			FtpConnectionOptions::fromArray( [
				'host'     => $host,
				'username' => $username,
				'password' => $password,
				'port'     => $port ?: self::FTP_PORT,
				'passive'  => $passive,
				'ssl'      => $ssl,
				'root'     => $path,
				'timeout'  => $timeout ?? self::CONNECTION_TIMEOUT,
			] )
		);
	}

	/**
	 * Returns SFTP connection adapter.
	 *
	 * @since 1.0
	 *
	 * @param string      $host
	 * @param int         $port
	 * @param string      $username
	 * @param string      $password
	 * @param string      $path
	 * @param string|null $private_key
	 * @param string|null $private_key_passphrase
	 * @param null        $timeout
	 *
	 * @return SftpAdapter
	 */
	public function getSftpAdapter( string $host, int $port, string $username, string $password, string $path, string $private_key = null, string $private_key_passphrase = null, $timeout = null ): SftpAdapter {
		$password               = $this->maybeDecryptPassword( $password );
		$private_key_passphrase = $this->maybeDecryptPassword( $private_key_passphrase );

		return new SftpAdapter(
			new SftpConnectionProvider(
				$host,
				$username,
				$password,
				$private_key,
				$private_key_passphrase,
				$port ?: self::SFTP_PORT,
				false,
				$timeout ?? self::CONNECTION_TIMEOUT,
			),
			$path
		);
	}

	/**
	 * Returns Dropbox connection adapter.
	 *
	 * @since 1.0
	 *
	 * @param string $auth_token
	 * @param string $path
	 *
	 * @return DropboxAdapter
	 */
	public function getDropboxAdapter( string $auth_token, string $path = '' ): DropboxAdapter {
		$auth_token = $this->maybeDecryptPassword( $auth_token );

		return new DropboxAdapter( new Client( $auth_token ), $path );
	}

	/**
	 * Tests connection: 1) logging in/listing folder, 2) uploading file, 3) deleting file.
	 *
	 * @since 1.0
	 *
	 * @throws SaveException|ExpiredTokenException
	 *
	 * @param array  $connection_settings
	 * @param string $service
	 */
	public function testConnection( string $service, array $connection_settings ): void {
		$host                   = rgar( $connection_settings, 'host', '' );
		$port                   = (int) rgar( $connection_settings, 'port', 0 );
		$ssl                    = (int) rgar( $connection_settings, 'ssl', 0 );
		$passive                = (bool) rgar( $connection_settings, 'passive', false );
		$username               = rgar( $connection_settings, 'username', '' );
		$password               = rgar( $connection_settings, 'password', '' );
		$private_key            = rgar( $connection_settings, 'private_key', null );
		$private_key_passphrase = rgar( $connection_settings, 'private_key_passphrase', null );
		$path                   = rgar( $connection_settings, 'path', '' );
		$timeout                = (int) rgar( $connection_settings, 'timeout', self::CONNECTION_TIMEOUT );
		$auth_key               = rgar( $connection_settings, 'auth_key', '' );

		switch ( $service ) {
			case 'ftp':
				if ( 2 === $ssl ) {
					$adapter = $this->getSftpAdapter( $host, $port, $username, $password, $path, $private_key, $private_key_passphrase, $timeout );
				} else {
					$adapter = $this->getFtpAdapter( $host, $port, (bool) $ssl, $passive, $username, $password, $path, $timeout );
				}
				break;
			case 'dropbox':
				$adapter = $this->getDropboxAdapter( $auth_key, $path );
				break;
			default:
				throw new SaveException(
					esc_html__( 'Connection service is not defined.', 'gk-gravityexport' )
				);
		}

		try {
			$result = @$adapter->listContents( $path, false );
			$result->valid();
		} catch ( FilesystemException | \Exception $e ) {
			if ( $e instanceof UnableToLoadPrivateKey ) {
				throw new SaveException(
					sprintf( esc_html_x( 'Unable to load private key. Please ensure that the key (%s) exists and that the passphrase, if applicable, is correct.', '"%s" is replaced by the key path', 'gk-gravityexport' ), $private_key )
				);
			}

			if ( $e instanceof UnableToAuthenticateFTP || $e instanceof UnableToAuthenticateSFTP || $e instanceof UnableToConnectToFtpHost ) {
				throw new SaveException(
					esc_html__( 'Unable to connect or log in. Please ensure that the host/port and your username/password are correct.', 'gk-gravityexport' )
				);
			}

			$exception_message = $e->getMessage();

			if ( false !== strpos( $exception_message, 'expired_access_token' ) ) {
				throw new ExpiredTokenException(
					strtr( esc_html__( 'The [settings_url]Dropbox token[/settings_url] has expired. [docs_url]Learn how to re-generate the token[/docs_url]. Note: When regenerating the token, the recommended setting for the "Access token expiration" setting is "No expiration".', 'gk-gravityexport' ), array(
						'[settings_url]' => sprintf( '<a href="%s">', esc_url( admin_url('admin.php?page=gf_settings&subview=gravityexport') ) ),
						'[/settings_url]' => '<span class="dashicons dashicons-external" title="' . esc_attr__( 'This link opens in a new window.', 'gk-gravityexport' ) . '"></span></a>',
						'[docs_url]' => '<a href="https://docs.gravityview.co/article/778-connecting-dropbox-to-gravityexport#clarify-step-8" target="_blank">',
						'[/docs_url]' => '<span class="dashicons dashicons-external" title="' . esc_attr__( 'This link opens in a new window.', 'gk-gravityexport' ) . '"></span></a>',
					) )
				);
			}

			throw new SaveException(
				sprintf( esc_html__( 'An error has occurred when trying to list folder contents. %s', 'gk-gravityexport' ), esc_html( $e->getMessage() ) )
			);
		}

		$temp_file = wp_tempnam( 'gravityexport-save-connection-test' );

		file_put_contents( $temp_file, 'This temporary file can be safely removed.' . PHP_EOL );

		$filesystem = new MountManager( [
			'local'  => new Filesystem( new LocalFilesystemAdapter( dirname( $temp_file ) ) ),
			'target' => new Filesystem( $adapter ),
		] );

		$source = sprintf( 'local://%s', basename( $temp_file ) );
		$target = sprintf( 'target://%s', basename( $temp_file ) );

		try {
			@$filesystem->copy( $source, $target );

			wp_delete_file( $temp_file );
		} catch ( FilesystemException | \Exception $e ) {
			wp_delete_file( $temp_file );

			if ( $e instanceof UnableToCopyFile ) {
				throw new SaveException(
					esc_html__( 'Unable to save file. Please ensure that the destination folder exists and that you have write permissions.', 'gk-gravityexport' )
				);
			}

			throw new SaveException(
				sprintf( esc_html__( 'Unknown error has occurred when trying to save file. %s', 'gk-gravityexport' ), $e->getMessage() )
			);
		}

		try {
			@$filesystem->delete( $target );
		} catch ( FilesystemException | \Exception $e ) {
			if ( $e instanceof UnableToDeleteFile ) {
				throw new SaveException(
					esc_html__( 'Unable to delete file. Please ensure that you have the correct permissions.', 'gk-gravityexport' )
				);
			}

			throw new SaveException(
				sprintf( esc_html__( 'Unknown error has occurred when trying to delete file. %s', 'gk-gravityexport' ), $e->getMessage() )
			);
		}
	}

	/**
	 * Tries to decrypt password or returns an original value.
	 *
	 * @since 1.0
	 *
	 * @param string $password
	 *
	 * @return string
	 */
	private function maybeDecryptPassword( string $password ): string {
		$decrypted_password = $this->password_service->decrypt( $password );

		return $decrypted_password ?? $password;
	}
}
