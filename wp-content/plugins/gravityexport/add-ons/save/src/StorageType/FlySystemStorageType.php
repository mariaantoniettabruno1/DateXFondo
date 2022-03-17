<?php

namespace GravityKit\GravityExport\Save\StorageType;

use GravityKit\GravityExport\Save\Exception\SaveException;
use GravityKit\GravityExport\Save\Addon\SaveAddon;
use GravityKit\GravityExport\Save\Service\StorageService;
use GravityKit\GravityExport\League\Flysystem\FilesystemAdapter;
use GravityKit\GravityExport\League\Flysystem\Local\LocalFilesystemAdapter as LocalFileSystem;
use GravityKit\GravityExport\League\Flysystem\Filesystem;
use GravityKit\GravityExport\League\Flysystem\MountManager;

/**
 * Abstract storage type backed by Fly System implementations.
 *
 * @since 1.0
 */
abstract class FlySystemStorageType implements StorageTypeInterface {
	/**
	 * Field variable for where to store the file.
	 *
	 * @since 1.0
	 * @var string
	 */
	public const FIELD_STORAGE_PATH = 'storage_path';

	/**
	 * Prefix for the target filesystem
	 *
	 * @since 1.0
	 * @var string
	 */
	public const FILESYSTEM_TARGET = 'target';

	/**
	 * Prefix for the local filesystem
	 *
	 * @since 1.0
	 * @var string
	 */
	public const FILESYSTEM_LOCAL = 'local';

	/**
	 * An instance of the repository.
	 *
	 * @since 1.0
	 * @var StorageService
	 */
	protected $service;

	/**
	 * The filesystem manager instance.
	 *
	 * @since 1.0
	 * @var MountManager
	 */
	protected $filesystem;

	/**
	 * Holds the values of the feed
	 *
	 * @since 1.0
	 * @var mixed[]
	 */
	protected $feed = [];

	/**
	 * Creates the storage type.
	 *
	 * @since 1.0
	 *
	 * @param StorageService $service The storage service.
	 */
	public function __construct( StorageService $service ) {
		$this->service = $service;
	}

	/**
	 * Set a filesystem instance.
	 *
	 * @since 1.0
	 *
	 * @param MountManager $filesystem Filesystem
	 */
	public function setFileSystem( $filesystem ): void {
		$this->filesystem = $filesystem;
	}

	/**
	 * Gets a single the filesystem instance.
	 *
	 * @since 1.0
	 *
	 * @param string $local_folder  The local folder.
	 * @param string $target_folder The target folder.
	 *
	 * @return MountManager
	 */
	public function getFileSystem( string $local_folder, string $target_folder ): MountManager {
		if ( $this->filesystem === null ) {
			$this->filesystem = new MountManager( [
				static::FILESYSTEM_LOCAL  => new Filesystem( $this->getLocalFileSystemAdapter( $local_folder ) ),
				static::FILESYSTEM_TARGET => $this->getTargetFileSystem( $target_folder ),
			] );
		}
		return $this->filesystem;
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function processForm( string $form_id, array $meta, array $feed ): void {
		$this->feed = $feed;
		if ( $this->isSingleEntryFeed( $feed ) ) {
			throw new SaveException( 'Single entry feed cannot be processed as form feed.' );
		}

		$this->copy( $this->service->renderFile( $feed ), $this->getStorePath( $feed ) );
	}

	/**
	 * Whether this feed is a single entry type.
	 *
	 * @since 1.0
	 *
	 * @param array|null $feed the feed array
	 *
	 * @return bool Whether this feed is a single entry type.
	 */
	protected function isSingleEntryFeed( ?array $feed ): bool {
		$meta = $this->getFeedMeta( $feed );

		return rgar( $meta, SaveAddon::FILE_TYPE ) === SaveAddon::FILE_ENTRIES_SINGLE;
	}

	/**
	 * Returns the metadata for a feed array, when available.
	 *
	 * @since 1.0
	 *
	 * @param array|null $feed
	 *
	 * @return mixed[]
	 */
	protected function getFeedMeta( ?array $feed = null ): array {
		$feed = $feed ?? $this->feed;

		return gf_apply_filters( [
			'gravitykit/gravityexport/save/settings',
			$this->getId(),
		], $feed['meta'] ?? [], $this, $feed );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function processEntry( ?array $form, ?array $entry, ?array $feed ): void {
		$this->feed = $feed ?? [];

		if ( $this->isAllEntriesFeed( $feed ) ) {
			//defer to processing the entire form.
			$this->processForm( rgar( $form, 'id' ), rgar( $feed, 'meta', [] ), $feed );

			return;
		}

		// Not a valid feed.
		if ( ! $this->isSingleEntryFeed( $feed ) ) {
			return;
		}

		$file = $this->service->renderFile( $feed, [ $entry ] );
		$this->copy( $file, $this->getStorePath( $feed ) );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getStorageSettings( array $settings, array $feed ): array {
		return $settings;
	}

	/**
	 * Copies the local file to a new target and removes the original.
	 *
	 * @since 1.0
	 * @throws SaveException when the file could not be copied.
	 *
	 * @param string $target_path The new target of the file.
	 * @param string $file        The file to copy.
	 *
	 * @return bool Whether the files was copied successfully.
	 */
	protected function copy( string $file, string $target_path ): bool {
		$fs = $this->getFileSystem( dirname( $file ), $target_path );

		$target_filename = gf_apply_filters( [
			'gravitykit/gravityexport/save/filename',
			$this->getId(),
		], basename( $file ), $this );

		$source = sprintf( '%s://%s', static::FILESYSTEM_LOCAL, basename( $file ) );
		$target = sprintf( '%s://%s', static::FILESYSTEM_TARGET, $target_filename );

		try {
			@$fs->copy( $source, $target );
		} catch ( \Exception $e ) {
			throw new SaveException( $e->getMessage(), $e->getCode(), $e );
		}

		return true;
	}

	/**
	 * The local filesystem for reading/writing.
	 *
	 * @since 1.0
	 *
	 * @param string $folder The folder.
	 *
	 * @return LocalFileSystem The local filesystem.
	 */
	protected function getLocalFileSystemAdapter( string $folder ): LocalFileSystem {
		return new LocalFileSystem( $folder );
	}

	/**
	 * Returns the path where the file will be stored.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $feed The feed object.
	 *
	 * @return string The storage path.
	 */
	protected function getStorePath( array $feed ): string {
		$meta = $this->getFeedMeta( $feed );

		return $meta[ static::FIELD_STORAGE_PATH ] ?: '';
	}

	/**
	 * Whether this feed is a single entry type.
	 *
	 * @since 1.0
	 *
	 * @param array $feed the feed array
	 *
	 * @return bool Whether this feed is a single entry type.
	 */
	protected function isAllEntriesFeed( array $feed ): bool {
		$meta = $this->getFeedMeta( $feed );

		return ( $meta[ SaveAddon::FILE_TYPE ] ?? null ) === SaveAddon::FILE_ENTRIES_ALL;
	}

	/**
	 * Returns the adapter used for storing the file.
	 *
	 * @since 1.0
	 * @return FilesystemAdapter Filesystems adapter.
	 */
	abstract protected function getTargetFileSystemAdapter(): FilesystemAdapter;

	/**
	 * Returns the filesystem for the target.
	 *
	 * @since 1.0
	 *
	 * @param string $path The target path.
	 *
	 * @return object Filesystem.
	 */
	protected function getTargetFileSystem( string $path = '' ): object {
		return new Filesystem( $this->getTargetFileSystemAdapter() );
	}

	/**
	 * Options for a no / yes dropdown.
	 *
	 * @since 1.0
	 * @return int[] The options.
	 */
	protected function noYesChoices(): array {
		return [
			[ 'label' => esc_html_x( 'No', 'Choice selection', 'gk-gravityexport' ), 'value' => 0 ],
			[ 'label' => esc_html_x( 'Yes', 'Choice selection', 'gk-gravityexport' ), 'value' => 1 ],
		];
	}
}
