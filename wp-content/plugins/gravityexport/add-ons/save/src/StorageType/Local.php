<?php

namespace GravityKit\GravityExport\Save\StorageType;

use \GFCommon;
use GravityKit\GravityExport\Save\Addon\SaveAddon;
use GravityKit\GravityExport\Save\Service\StorageService;
use GravityKit\GravityExport\League\Flysystem\Filesystem;
use GravityKit\GravityExport\League\Flysystem\FilesystemAdapter;

/**
 * Storage type that stores to the local environment.
 *
 * @since 1.0
 */
class Local extends FlySystemStorageType {

	/**
	 * @since 1.0
	 * @var string Name of the option that stores the default storage path in the database.
	 */
	protected const DIRECTORY_NAME_OPTION = 'gravityexport/local-directory-name';

	/**
	 * Holds the path to store the file.
	 *
	 * @since 1.0
	 * @var string
	 */
	private $path;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function __construct( StorageService $service, string $path ) {
		parent::__construct( $service );
		$this->path = $path;
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getId(): string {
		return 'local';
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getTitle(): string {
		return esc_html_x( 'Local Storage', 'Storage method', 'gk-gravityexport' );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getIcon(): string {
		return 'fa-folder-open';
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
	public function getFeedFields( SaveAddon $feed ): array {
		$upload_path = self::getDefaultUploadDir();

		return [
			[
				'label'       => esc_html__( 'Local Path', 'gk-gravityexport' ),
				'type'        => 'text',
				'name'        => static::FIELD_STORAGE_PATH,
				'description' => sprintf(
					esc_html_x( "The absolute path on the server where the files will be saved. Leave blank to use the default.", '%s is replaced with storage path.', 'gk-gravityexport' ),
				),
				'placeholder' => esc_url( $upload_path ),
			],
		];
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected function getTargetFileSystemAdapter( string $path = '' ): FilesystemAdapter {
		return $this->getLocalFileSystemAdapter( $path );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected function getTargetFileSystem( string $path = '' ): object {
		return new Filesystem( $this->getTargetFileSystemAdapter( $path ) );
	}

	/**
	 * @inheritdoc
	 *
	 * Fallback to default path.
	 *
	 * @since 1.0
	 */
	protected function getStorePath( array $feed ): string {
		return parent::getStorePath( $feed ) ?: $this->path;
	}

	/**
	 * Returns the default upload directory.
	 *
	 * @return false|string
	 */
	public static function getDefaultUploadDir() {

		// The default base is /wp-uploads/
		$base_path = rgar( wp_upload_dir(), 'basedir' );

		$path = trailingslashit( trailingslashit( $base_path ) . self::maybeGenerateDirectoryName() );

		$path_created = wp_mkdir_p( $path );

		// Add index.html files throughout for privacy.
		GFCommon::recursive_add_index_file( $path );

		return $path_created ? $path : false;
	}

	/**
	 * Generates a stable directory name across all form IDs for the current site.
	 *
	 * @return string
	 */
	private static function maybeGenerateDirectoryName() : string {

		$saved_dir_name = get_site_option( self::DIRECTORY_NAME_OPTION );

		if ( $saved_dir_name ) {
			return $saved_dir_name;
		}

		$directory_name = 'gravityexport/' . bin2hex( openssl_random_pseudo_bytes( 14 ) );

		update_site_option( self::DIRECTORY_NAME_OPTION, $directory_name );

		return $directory_name;
	}
}
