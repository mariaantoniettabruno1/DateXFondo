<?php

namespace GravityKit\GravityExport\Save\ServiceProvider;

use GravityKit\GravityExport\Save\Addon\SaveAddon;
use GravityKit\GravityExport\Save\Service\ConnectionManagerService;
use GravityKit\GravityExport\Save\Service\PasswordService;
use GravityKit\GravityExport\Save\Service\StorageService;
use GravityKit\GravityExport\Save\StorageType\Dropbox;
use GravityKit\GravityExport\Save\StorageType\FTP;
use GravityKit\GravityExport\Save\StorageType\Local;
use GravityKit\GravityExport\Save\StorageType\StorageTypeInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Service provider for the container.
 *
 * @since 1.0
 */
class ServiceProvider extends AbstractServiceProvider {
	/**
	 * The tag for storage types.
	 *
	 * @since 1.0
	 * @var string
	 */
	private const STORAGE_TYPE_TAG = 'gfexcel.storage_type';

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $provides = [
		Dropbox::class,
		FTP::class,
		Local::class,
		SaveAddon::class,
		PasswordService::class,
		ConnectionManagerService::class,
	];

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function register(): void {
		$default_salt = bin2hex( openssl_random_pseudo_bytes( 20 ) );
		$secret       = defined( 'AUTH_SALT' ) ? AUTH_SALT : $default_salt;
		$container    = $this->getLeagueContainer();

		$container->add( PasswordService::class )->addArgument( $secret );
		$container->add( ConnectionManagerService::class )->addArgument( PasswordService::class );

		$this->addStorageType( Dropbox::class, PasswordService::class, ConnectionManagerService::class );
		$this->addStorageType( FTP::class, PasswordService::class, ConnectionManagerService::class );
		$this->addStorageType( Local::class, Local::getDefaultUploadDir() );

		$container->add( SaveAddon::class )
		          ->addArgument( StorageService::class )
		          ->addArgument( PasswordService::class )
		          ->addArgument( ConnectionManagerService::class )
		          ->addMethodCall( 'addStorageTypes', [ $container->get( self::STORAGE_TYPE_TAG ) ] );

		if ( $secret === $default_salt ) {
			$container
				->get( SaveAddon::class )
				->add_error_message( strtr( esc_html__(
						'[url]Please generate and define[/url] authentication keys and salts to secure your passwords.',
						'gk-gravityexport'
					), array(
						'[url]'  => '<a href="https://api.wordpress.org/secret-key/1.1/salt/">',
						'[/url]' => '</a>',
					) )
				);
		}
	}

	/**
	 * Helper method to add a storage type.
	 *
	 * @since 1.0
	 *
	 * @param string $storage_type The storage type class.
	 * @param mixed  ...$args      Other arguments.
	 */
	private function addStorageType( string $storage_type, ...$args ): void {
		if ( ! is_subclass_of( $storage_type, StorageTypeInterface::class ) ) {
			throw new \InvalidArgumentException( 'Storage type does not implement correct interface.' );
		}

		$this->getLeagueContainer()
		     ->add( $storage_type )
		     ->addArgument( StorageService::class )
		     ->addArguments( $args )
		     ->addTag( self::STORAGE_TYPE_TAG );
	}
}
