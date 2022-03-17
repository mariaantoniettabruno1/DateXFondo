<?php

namespace GravityKit\GravityExport\Save\Addon;

use GFExcel\Addon\AddonHelperTrait;
use GFExcel\Addon\AddonInterface;
use GFExcel\Addon\AddonTrait;
use GFExcel\GFExcel;
use GravityKit\GravityExport\Addon\GravityExportAddon;
use GravityKit\GravityExport\Save\Exception\SaveException;
use GravityKit\GravityExport\Save\Service\ConnectionManagerService;
use GravityKit\GravityExport\Save\Service\PasswordService;
use GravityKit\GravityExport\Save\Service\StorageService;
use GravityKit\GravityExport\Save\StorageType\Dropbox;
use GravityKit\GravityExport\Save\StorageType\Local;
use GravityKit\GravityExport\Save\StorageType\StorageTypeInterface;
use GravityKit\GravityExport\GravityKit\QueryFilters\QueryFilters;

/**
 * An add-on that provides settings for multiple store methods.
 *
 * @since 1.0
 */
class SaveAddon extends SaveAddonVariables implements AddonInterface {
	use AddonTrait;
	use AddonHelperTrait;

	/**
	 * @since 1.0
	 * @var string String used as a nonce scalar value and assets handle.
	 */
	public const AJAX_TEST_CONNECTION_ACTION = 'gravityexport_save_test_connection';

	/**
	 * @since 1.0
	 * @var string String used as a nonce scalar value and assets handle.
	 */
	public const NONCE_AND_ASSETS_HANDLE = 'gravityexport_save';

	/**
	 * @since 1.0
	 * @var string The field that holds the storage type.
	 */
	public const STORAGE_TYPE = 'storage_type';

	/**
	 * @since 1.0
	 * @var string The field that holds the storage title.
	 */
	public const STORAGE_TITLE = 'feedName';

	/**
	 * The field that holds what kind of file we are rendering.
	 *
	 * @since 1.0
	 * @var string
	 */
	public const FILE_TYPE = 'file_type';

	/**
	 * @since 1.0
	 * @var string File type that represents all entries.
	 */
	public const FILE_ENTRIES_ALL = 'all';

	/**
	 * @since 1.0
	 * @var string File type that represents a single entry.
	 */
	public const FILE_ENTRIES_SINGLE = 'single';

	/**
	 * @since 1.0
	 * @var string Feed settings permissions.
	 */
	protected $_capabilities_form_settings = 'gravityforms_export_entries';

	/**
	 * @since 1.0
	 * @var StorageTypeInterface[] Holds all instances of the storage types.
	 */
	private $storage_types = [];

	/**
	 * @since 1.0
	 * @var StorageService The storage service.
	 */
	private $storage_service;

	/**
	 * @since 1.0
	 * @var ConnectionManagerService Connection manager service.
	 */
	private $connection_manager_service;

	/**
	 * @since 1.0
	 * @var PasswordService Password service.
	 */
	private $password_service;

	/**
	 * @since 1.0
	 * @var string Query Filters instance.
	 */
	private $query_filters;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function __construct( StorageService $storage_service, PasswordService $password_service, ConnectionManagerService $connection_manager_service ) {
		parent::__construct();

		$this->storage_service            = $storage_service;
		$this->password_service           = $password_service;
		$this->connection_manager_service = $connection_manager_service;

		$this->query_filters = new QueryFilters();
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function init(): void {
		parent::init();

		add_action( 'wp_ajax_' . self::AJAX_TEST_CONNECTION_ACTION, \Closure::fromCallable( [ $this, 'testConnection' ] ) );

		add_filter( 'gform_noconflict_scripts', \Closure::fromCallable( [ $this, 'whitelist_ui_assets' ] ) );
		add_filter( 'gform_noconflict_styles', \Closure::fromCallable( [ $this, 'whitelist_ui_assets' ] ) );

		add_action( 'shutdown', function () {
			$this->storage_service->clearHistory();
		} );

		$current_form = $this->get_current_form();

		if ( ! $current_form ) {
			return;
		}

		$feeds = $this->get_active_feeds( $current_form['id'] );

		foreach ( $feeds as $feed ) {
			$feed_id           = rgar( $feed, 'id' );
			$form_id           = rgar( $feed, 'form_id' );
			$slug              = rgar( $feed, 'addon_slug' );
			$conditional_logic = rgars( $feed, 'meta/conditional_logic', 'null' );

			if ( $this->_slug !== $slug || 'null' === $conditional_logic ) {
				continue;
			}

			add_filter( sprintf( 'gfexcel_get_entries_%s_%s', $form_id, $feed_id ), \Closure::fromCallable( [ $this, 'getEntries' ] ), 10, 5 );
		}
	}

	/**
	 * Return the plugin's icon for the plugin/form settings menu.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function get_menu_icon(): string {
		return '<svg style="height: 24px;" enable-background="new 0 0 226 148" height="148" viewBox="0 0 226 148" width="226" xmlns="http://www.w3.org/2000/svg"><path d="m176.8 118.8c-1.6 1.6-4.1 1.6-5.7 0l-5.7-5.7c-1.6-1.6-1.6-4.1 0-5.7l27.6-27.4h-49.2c-4.3 39.6-40 68.2-79.6 63.9s-68.2-40-63.9-79.6 40.1-68.2 79.7-63.9c25.9 2.8 48.3 19.5 58.5 43.5.6 1.5-.1 3.3-1.7 3.9-.4.1-.7.2-1.1.2h-9.9c-1.9 0-3.6-1.1-4.4-2.7-14.7-27.1-48.7-37.1-75.8-22.4s-37.2 48.8-22.4 75.9 48.8 37.2 75.9 22.4c15.5-8.4 26.1-23.7 28.6-41.2h-59.4c-2.2 0-4-1.8-4-4v-8c0-2.2 1.8-4 4-4h124.7l-27.5-27.5c-1.6-1.6-1.6-4.1 0-5.7l5.7-5.7c1.6-1.6 4.1-1.6 5.7 0l41.1 41.2c3.1 3.1 3.1 8.2 0 11.3z"/></svg>';
	}

	/**
	 * Returns an array of default filename formats.
	 *
	 * @since 1.0
	 *
	 * @return array[] Array with `value`, `label`, and `title` keys.
	 */
	public static function get_filename_formats(): array {

		$formats = [
			[
				'value'  => 'all-default',
				'label'  => sprintf( esc_html_x( 'Default Format: %s', '%s is replaced by the filename structure', 'gk-gravityexport' ), '"gravityexport-{form_id}-{form_title}-{YYYY}-{MM}-{DD}"' ),
				'format' => 'gravityexport-{form_id}-{form_title}-{YYYY}-{MM}-{DD}',
			],
			[
				'value'  => 'all-{form_id}-{YYYY}-{MM}-{DD}',
				'label'  => sprintf( esc_html_x( 'Form ID and Date: %s', '%s is replaced by the filename structure', 'gk-gravityexport' ), '"{form_id}-{YYYY}-{MM}-{DD}"' ),
				'format' => '{form_id}-{YYYY}-{MM}-{DD}',
			],
			[
				'value'  => 'single-default',
				'label'  => sprintf( esc_html_x( 'Default Format: %s', '%s is replaced by the filename structure', 'gk-gravityexport' ), '"gravityexport-{form_id}-{form_title}-{YYYY}-{MM}-{DD}-{entry_id}"' ),
				'format' => 'gravityexport-{form_id}-{form_title}-{YYYY}-{MM}-{DD}-entry-{entry_id}',
			],
			[
				'value'  => 'single-{form_id}-{entry_id}',
				'label'  => sprintf( esc_html_x( 'Form ID and Entry ID: %s', '%s is replaced by the filename structure', 'gk-gravityexport' ), '"form-{form_id}-entry-{entry_id}"' ),
				'format' => 'form-{form_id}-entry-{entry_id}',
			],
			[
				'value'  => 'custom',
				'label'  => esc_html__( 'Custom Filename', 'gk-gravityexport' ),
				'format' => null,
			],
		];

		/**
		 * Modify the filename formats list in Save. To remove ability to define custom formats, remove array where "value" === "custom".
		 *
		 * @since 1.0
		 *
		 * @param array[] $formats Associative array with `value`, `label`, and `format` keys.
		 */
		return apply_filters( 'gravitykit/gravityexport/filename_formats', $formats );
	}

	/**
	 * Returns the fields set for every feed.
	 *
	 * @since 1.0
	 * @return array[] The fields.
	 */
	public function feed_settings_fields(): array {
		$feed = $this->get_current_feed();

		$settings_description = '';

		if ( ! self::is_download_enabled() ) {
			$form_id = (int) rgget( 'id' );

			$settings_description = strtr( esc_html__( 'Files will not be saved until you [url]enable the download[/url].', 'gk-gravityexport' ), array(
				'[url]'  => '<a href="' . esc_url( admin_url( sprintf( 'admin.php?page=gf_edit_forms&view=settings&subview=%s&id=%s', GFExcel::$slug, $form_id ) ) ) . '">',
				'[/url]' => '</a>',
			) );

			$settings_description = sprintf( '<p class="alert warning">%s</p>', $settings_description );
		} else if ( $feed && ! (bool) $feed['is_active'] ) {
			$settings_description = strtr( esc_html__( 'Files will not be saved until you [url]activate this feed[/url].', 'gk-gravityexport' ), array(
				'[url]'  => '<a href="' . esc_url( admin_url( sprintf( '/admin.php?page=gf_edit_forms&view=settings&subview=%s&id=%s', $this->_slug, $feed['form_id'] ) ) ) . '">',
				'[/url]' => '</a>',
			) );

			$settings_description = sprintf( '<p class="alert warning">%s</p>', $settings_description );
		}

		return array_merge(
			[
				[
					'title'       => esc_html__( 'General Settings', 'gk-gravityexport' ),
					'description' => $settings_description,
					'fields'      => $this->feed_general_settings_fields(),
				],
			],
			[
				[
					'title'       => esc_html__( 'File Settings', 'gk-gravityexport' ),
					'description' => '',
					'fields'      => [
						[
							'label'       => esc_html__( 'Filename Format', 'gk-gravityexport' ),
							'type'        => 'radio',
							'name'        => 'filename_format',
							'description' => '',
							'value'       => 'all-default',
							'choices'     => self::get_filename_formats(),
						],
						[
							'label'         => esc_html__( 'Custom Filename', 'gk-gravityexport' ),
							'type'          => 'text',
							'name'          => 'filename',
							'class'         => 'code medium merge-tag-support mt-position-right mt-hide_all_fields',
							'description'   => wpautop( sprintf( esc_html__( 'Enter a custom filename for the export. If a file exists with the same name, it will be overwritten. Use Merge Tags to generate unique filenames. Leave empty to use the default filename.

The following replacements are also available: %s

Filenames will be sanitized using the %s function. Most non-alphanumeric characters will be replaced with hyphens.', 'gk-gravityexport' ),
								'
			<ul class="ul-disc">
				<li><code>{DATE}</code> ' . esc_html__( 'Date in ISO-8601 format (YYYY-MM-DD HH:MM:SS)', 'gk-gravityexport' ) . '</li>
				<li><code>{TIMESTAMP}</code> ' . esc_html__( 'Server timestamp', 'gk-gravityexport' ) . '</li> 
				<li><code>{YYYY}</code> ' . esc_html__( 'A full numeric representation of a year, 4 digits', 'gk-gravityexport' ) . '</li>
				<li><code>{YY}</code> ' . esc_html__( 'A two digit representation of a year', 'gk-gravityexport' ) . '</li>
				<li><code>{MM}</code> ' . esc_html__( 'Numeric representation of a month, with leading zeros', 'gk-gravityexport' ) . '</li>
				<li><code>{MONTH}</code> ' . esc_html__( 'A full numeric representation of a month, such as January or March', 'gk-gravityexport' ) . '</li>
				<li><code>{DD}</code> ' . esc_html__( 'Day of the month, 2 digits with leading zeros', 'gk-gravityexport' ) . '</li>
			</ul>
			',
								'<a href="https://developer.wordpress.org/reference/functions/sanitize_file_name/" target="_blank"><code>sanitize_file_name()</code><span class="dashicons dashicons-external" title="' . esc_attr__( 'This link opens in a new window.', 'gk-gravityexport' ) . '"></span></a>',
							) ),
							'placeholder'   => GFExcel::getFilename( $this->get_current_form() ),
							'save_callback' => function ( $field, $original_value ) {

								$value = $original_value;

								// Remove extensions from the file name; they're not needed.
								$value = preg_replace( '/\.(' . GFExcel::getPluginFileExtensions( true ) . ')$/is', '', $value );

								// Strip Merge Tags and replace with placeholders so they're not stripped during sanitization.
								preg_match_all( '/{(.+?)}/ism', $original_value, $merge_tag_matches, PREG_SET_ORDER );

								foreach ( $merge_tag_matches as $i => $match ) {
									list( $full_match, $match_contents ) = $match;

									$value = str_replace( $full_match, 'gravityexport_merge_tag_' . $i, $value );
								}

								$value = sanitize_file_name( $value );

								// Let's add back in the Merge Tags!
								foreach ( $merge_tag_matches as $i => $match ) {
									list( $full_match, $match_contents ) = $match;

									$value = str_replace( 'gravityexport_merge_tag_' . $i, $full_match, $value );
								}

								return $value;
							},
						],
						[
							'label'   => esc_html__( 'File Extension', 'gk-gravityexport' ),
							'type'    => 'select',
							'name'    => 'file_extension',
							'class'   => 'small-text',
							'choices' => array_map( static function ( $extension ) {
								return
									[
										'name'  => 'file_extension',
										'label' => '.' . $extension,
										'value' => $extension,
									];
							}, GFExcel::getPluginFileExtensions() ),
						],
					]
				]
			],
			[
				[
					'title'  => esc_html__( 'Storage Type', 'gk-gravityexport' ),
					'fields' => [
						[
							'label'    => esc_html__( 'Type', 'gk-gravityexport' ),
							'type'     => 'radio',
							'name'     => self::STORAGE_TYPE,
							'tooltip'  => esc_html__( 'Select the type of storage you wish to add.', 'gk-gravityexport' ),
							'choices'  => $this->storage_types_options(),
							'required' => true,
							'onchange' => 'jQuery("#gform-settings").submit();',
						],
					]
				]
			],
			$this->getStorageTypeFieldSettings(),
			[
				[
					'title'  => esc_html__( 'Filter Settings', 'gk-gravityexport' ),
					'fields' => [
						[
							'name'        => 'download_filters',
							'full_screen' => false,
							'label'       => esc_html__( 'Conditional Logic', 'gk-gravityexport' ),
							'tooltip'     => 'export_conditional_logic',
							'type'        => 'html',
							'html'        => '<div id="gk-query-filters"></div>',
						],
					],
				],
			],
			[
				[
					'fields' => [
						[ 'type' => 'save' ],
					],
				],
			]
		);
	}

	/**
	 * The fields for the general settings.
	 *
	 * @since 1.0
	 * @return mixed[] The fields.
	 */
	private function feed_general_settings_fields(): array {
		return [
			[
				'label'    => esc_html__( 'Title', 'gk-gravityexport' ),
				'type'     => 'text',
				'name'     => self::STORAGE_TITLE,
				'class'    => 'large-text',
				'required' => true,
			],
			[
				'label'       => esc_html__( 'Export Type', 'gk-gravityexport' ),
				'type'        => 'radio',
				'name'        => self::FILE_TYPE,
				'required'    => true,
				'value'       => self::FILE_ENTRIES_ALL,
				'description' => strtr( esc_html__( 'Only entries that meet [url]this feed\'s Filter Settings[/url] will be included in the file.', 'gk-gravityexport' ), [
					'[url]'  => '<a href="#gform_setting_download_filters">',
					'[/url]' => '</a>',
				] ),
				'choices'     => $this->file_types_options(),
			],
		];
	}

	/**
	 * @inheritdoc
	 *
	 * Override base method from \GFExcel\Addon\AddonHelperTrait that adds additional and unnecessary markup
	 *
	 * @since 1.0
	 */
	public function settings_select( $field, $echo = true ): string {
		return parent::settings_select( $field, $echo );
	}

	/**
	 * The available storage type options.
	 *
	 * @since 1.0
	 * @return string[]
	 */
	private function storage_types_options(): array {

		$storageTypes = $this->getStorageTypes();

		$options = [];
		foreach ( $storageTypes as $storageType ) {

			$is_disabled = $storageType->isDisabled();

			$option = [
				'id'    => $storageType->getId(),
				'label' => $is_disabled ? sprintf( esc_html__( 'Configure %s in Gravity Forms Settings', 'gk-gravityexport' ), $storageType->getTitle() ) : $storageType->getTitle(),
				'value' => $storageType->getId(),
				'icon'  => $storageType->getIcon(),
			];

			if ( $is_disabled ) {
				$option['disabled'] = 'disabled';
			}

			$options[] = $option;
		}

		return $options;
	}

	/**
	 * Transforms the storage types into a settings field array for that type.
	 *
	 * @since 1.0
	 * @return mixed[] The storage type options.
	 */
	private function getStorageTypeFieldSettings(): array {
		return array_reduce(
			$this->getStorageTypes(),
			function ( array $settings, StorageTypeInterface $storage ): array {
				$hide_connection_test = $storage->getId() === Local::FILESYSTEM_LOCAL;

				$settings[] = [
					'title'      => sprintf( esc_html_x( '%s Configuration', 'The name of the type of storage (eg: Dropbox, FTP, Local)', 'gk-gravityexport' ), esc_html__( $storage->getTitle(), 'gk-gravityexport' ) ),
					'fields'     => array_merge( $storage->getFeedFields( $this ), [
						[
							'label'    => '',
							'type'     => 'callback',
							'hidden'   => $hide_connection_test,
							'name'     => '',
							'callback' => function () {
								echo '<div id="connection-test-result" aria-live="assertive"><p hidden><!-- Result is dynamically populated by JS --></p></div><button id="connection-test" type="button" class="button button-secondary">' . esc_html__( 'Test Connection', 'gk-gravityexport' ) . '<span class="spinner" hidden></span></button>';
							}
						]
					] ),
					'dependency' => [
						'field'  => self::STORAGE_TYPE,
						'values' => [ $storage->getId() ],
					],
				];

				return $settings;
			},
			[]
		);
	}

	/**
	 * Get all instances of storage types.
	 *
	 * @since 1.0
	 * @return StorageTypeInterface[]
	 */
	private function getStorageTypes(): array {
		return $this->storage_types;
	}

	/**
	 * Process the feed on the correct storage type if available.
	 *
	 * @since 1.0
	 *
	 * @param array $feed  Feed information.
	 * @param array $entry Entry information.
	 * @param array $form  Form information.
	 */
	public function process_feed( $feed, $entry, $form ): void {
		if ( $storageType = $this->getStorageTypeByFeed( $feed ) ) {
			try {
				$storageType->processEntry( $form, $entry, $feed );
				$this->log_debug( sprintf(
					'Entry (%d) processed by storage type "%s".',
					$entry['id'] ?? 0,
					$storageType->getTitle()
				) );
			} catch ( SaveException $e ) {
				$this->log_error( $e->getMessage() );
			}
		}
	}

	/**
	 * Processes a single action.
	 *
	 * @since 1.0
	 *
	 * @param string $action the selected action to preform.
	 */
	protected function processBulkAction( string $action ): void {

		$feeds = rgpost( 'feed_ids' );

		if ( 'process' !== $action || ! is_array( $feeds ) ) {
			return;
		}

		// Reset any unwanted messages.
		\GFCommon::$errors   = [];
		\GFCommon::$messages = [];

		// @todo validate if $feeds is an array
		foreach ( $feeds as $feed_id ) {
			$feed = $this->get_feed( $feed_id );
			if ( $storageType = $this->getStorageTypeByFeed( $feed ) ) {
				try {
					$storageType->processForm(
						$feed['form_id'],
						$feed['meta'],
						$feed
					);
					$this->log_debug( sprintf(
						'Feed (%d) processed by storage type "%s".',
						$feed_id ?? 0,
						$storageType->getTitle()
					) );
				} catch ( SaveException $e ) {
					$this->add_error_message( $e->getMessage() );
					$this->log_error( $e->getMessage() );
				}
			}
		}

		$this->add_message( 'Feeds have been processed.' );

		// Display again since GF has executed this method before we added our changes.
		\GFCommon::display_admin_message();
	}

	/**
	 * Retrieve the correct storage type instance for this feed, if available.
	 *
	 * @since 1.0
	 *
	 * @param array $feed             the feed information
	 * @param bool  $include_inactive Whether to include inactive storage types.
	 *
	 * @return StorageTypeInterface|null the instance
	 */
	private function getStorageTypeByFeed( array $feed, bool $include_inactive = false ): ?StorageTypeInterface {
		if ( ( ! $feed['is_active'] && ! $include_inactive ) ||
		     ! isset( $feed['meta'][ self::STORAGE_TYPE ] ) ||
		     ! array_key_exists( $feed['meta'][ self::STORAGE_TYPE ], $this->getStorageTypes() )
		) {
			return null;
		}

		return $this->getStorageTypes()[ $feed['meta'][ self::STORAGE_TYPE ] ];
	}

	/**
	 * The available file type options.
	 *
	 * @since 1.0
	 * @return string[]
	 */
	private function file_types_options(): array {
		return [
			[ 'label' => esc_html__( 'All Entries', 'gk-gravityexport' ), 'value' => self::FILE_ENTRIES_ALL ],
			[ 'label' => esc_html__( 'Single Entry', 'gk-gravityexport' ), 'value' => self::FILE_ENTRIES_SINGLE ],
		];
	}

	/**
	 * @inheritdoc
	 * Make sure the storage type is in human readable format for the column.
	 * @since 1.0
	 */
	public function get_column_value( $item, $column ) {
		$value = parent::get_column_value( $item, $column );
		if ( ! $storage = $this->getStorageTypeByFeed( $item, true ) ) {
			return $value;
		}

		if ( $column === self::STORAGE_TYPE ) {
			return esc_html__( $storage->getTitle(), 'gk-gravityexport' );
		}

		if ( $column === self::FILE_TYPE ) {
			$options = array_reduce( $this->file_types_options(), static function ( array $options, $option ): array {
				$options[ $option['value'] ] = $option['label'];

				return $options;
			}, [] );

			return $options[ $value ];
		}

		return $value;
	}

	/**
	 * Add multiple storage types.
	 *
	 * @since 1.0
	 *
	 * @param StorageTypeInterface[] $storage_types The provided storage types.
	 */
	public function addStorageTypes( array $storage_types ): void {
		foreach ( $storage_types as $storage_type ) {
			$this->addStorageType( $storage_type );
		}
	}

	/**
	 * Adds a storage type.
	 *
	 * @since 1.0
	 *
	 * @param StorageTypeInterface $storageType The storage type.
	 */
	public function addStorageType( StorageTypeInterface $storageType ): void {
		if ( isset( $this->storage_types[ $id = $storageType->getId() ] ) ) {
			throw new \InvalidArgumentException( sprintf(
				'A storage type with the ID "%s" already exists.',
				$id
			) );
		}

		$this->storage_types[ $id ] = $storageType;
	}

	/**
	 * Parses the file name used when saving the file.
	 *
	 * @see StorageService::renderFile()
	 *
	 * @param array      $feed    Gravity Forms form feed.
	 * @param array|null $form    Gravity Forms form array connected to the feed.
	 * @param array|null $entries Array of entries being processed (single or many)
	 *
	 * @return string
	 */
	public static function get_filename( array $feed, ?array $form, ?array $entries = array() ): string {

		$filename_format = rgars( $feed, 'meta/filename_format', 'custom' );

		$filename = rgars( $feed, 'meta/filename', null );

		if ( 'custom' !== $filename_format ) {
			$default_formats = self::get_filename_formats();

			foreach ( $default_formats as $default_format ) {
				if ( $filename_format === $default_format['value'] ) {
					$filename = $default_format['format'];
					break;
				}
			}
		}

		return self::process_filename( $filename, $form, $entries );
	}

	/**
	 * Returns a filename that has been sanitized with replaced variables.
	 *
	 * @uses \GFCommon::replace_variables()
	 * @uses sanitize_file_name()
	 *
	 * @param string     $filename The starting name of the file, before replacement and sanitization.
	 * @param array|null $form     Gravity Forms form array connected to the feed.
	 * @param array|null $entries  Array of entries being processed (single or many)
	 *
	 * @return string
	 */
	private static function process_filename( string $filename, ?array $form, ?array $entries = array() ): string {

		$filename = strtr( $filename, array(
			'{DATE}'      => date( 'c' ),
			'{TIMESTAMP}' => date( 'U' ),
			'{YYYY}'      => date( 'Y' ),
			'{YY}'        => date( 'y' ),
			'{MM}'        => date( 'm' ),
			'{MONTH}'     => date( 'F' ),
			'{DD}'        => date( 'd' ),
		) );

		$entry = $entries ? $entries[0] : array();

		$filename = \GFCommon::replace_variables( $filename, $form, $entry );

		return sanitize_file_name( $filename );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function get_current_settings(): ?array {
		$settings = parent::get_current_settings();

		// Fix fields to retrieve from database.
		$refreshed_fields = [ 'filename' ];
		$feed             = $this->get_feed( $this->get_current_feed_id() );

		foreach ( $refreshed_fields as $key ) {
			$settings[ $key ] = rgar( $feed['meta'] ?? [], $key );
		}

		if ( empty( $feed ) ) {
			return $settings;
		}

		// Let storage type update their settings too.
		foreach ( $this->getStorageTypes() as $storageType ) {
			$settings = $storageType->getStorageSettings( $settings, $feed );
		}

		return $settings;
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function scripts(): array {
		if ( $this->is_feed_edit_page() ) {
			$this->query_filters->set_form( $this->get_current_form() );
			$this->query_filters->enqueue_scripts( [
				'input_element_name' => $this->getFieldNamePrefix() . 'conditional_logic',
				'conditions'         => rgar( $this->get_current_settings() ?? [], 'conditional_logic' )
			] );
		}

		return array_merge( parent::scripts(), [
			[
				'handle'  => self::NONCE_AND_ASSETS_HANDLE,
				'src'     => plugin_dir_url( GK_GRAVITYEXPORT_PLUGIN_FILE ) . 'assets/js/gravityexport-save.js',
				'strings' => [
					'ajaxAction'                          => self::AJAX_TEST_CONNECTION_ACTION,
					'nonce'                               => wp_create_nonce( self::NONCE_AND_ASSETS_HANDLE ),
					'formInputFieldNamePrefix'            => $this->getFieldNamePrefix(),
					'formInputFieldParentContainerPrefix' => $this->is_gravityforms_supported( '2.5-beta' ) ? 'gform_setting_' : 'gaddon-setting-row-',
					'incompleteTestMessage'               => esc_html__( 'We could not perform the connection test due to a network or server error.', 'gk-gravityexport' ),
				],
				'enqueue' => [
					[ 'admin_page' => 'form_settings', 'tab' => $this->get_slug() ],
				],
			]
		] );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function styles(): array {
		if ( $this->is_feed_edit_page() ) {
			$this->query_filters->enqueue_styles();
		}

		return parent::styles();
	}

	/**
	 * AJAX function to test connection.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function testConnection(): void {
		$defaults = array(
			'_nonce'   => null,
			'service'  => '',
			'settings' => [],
		);

		$request = wp_parse_args( $_REQUEST, $defaults );

		if ( ! check_ajax_referer( self::NONCE_AND_ASSETS_HANDLE, '_nonce', false ) ) {
			wp_die( false, false, array( 'response' => 403 ) );
		}

		if ( 'dropbox' === rgar( $request, 'service' ) ) {
			$request['settings']['auth_key'] = GravityExportAddon::get_instance()->get_plugin_setting( Dropbox::FIELD_AUTH_TOKEN );
		}

		try {
			$this->connection_manager_service->testConnection( $request['service'], $request['settings'] );

			wp_send_json_success( esc_html__( 'Your connection is properly configured.', 'gk-gravityexport' ) );
		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Return version-adjusted GF setting field name prefix
	 *
	 * @return string
	 */
	private function getFieldNamePrefix(): string {
		return $this->is_gravityforms_supported( '2.5-beta' ) ? '_gform_setting_' : '_gaddon_setting_';
	}

	/**
	 * Applies filters and fetches DB entries.
	 *
	 * @since 1.0
	 *
	 * @param int   $form_id         GF form ID.
	 * @param int   $feed_id         GF feed ID.
	 * @param array $search_criteria Search criteria (status, field filters).
	 * @param array $sorting         Sorting options (key, direction).
	 * @param array $paging          Sorting options (offset, page size).
	 *
	 * @return array|null Filtered entries or null.
	 */
	private function getEntries( int $form_id, int $feed_id, array $search_criteria, array $sorting, array $paging ) {
		$feed              = $this->get_current_feed() ?: $this->get_feed( $feed_id );
		$conditional_logic = rgars( $feed, 'meta/conditional_logic', 'null' );

		if ( ! $feed || 'null' === $conditional_logic ) {
			return null;
		}

		try {
			$this->query_filters->set_form( \GFAPI::get_form( $form_id ) );
			$this->query_filters->set_filters( $conditional_logic );

			$conditions = $this->query_filters->get_query_conditions();
		} catch ( \Exception $e ) {
			return null;
		}

		$search_criteria = [
			'status'        => rgar( $search_criteria, 'status', 'active' ),
			'field_filters' => rgar( $search_criteria, 'field_filters', [] )
		];

		$query = new \GF_Query( $feed['form_id'], $search_criteria, $sorting, $paging );

		$query_parts = $query->_introspect();

		$query->where( \GF_Query_Condition::_and( $query_parts['where'], $conditions ) );

		return $query->get();
	}

	/**
	 * Adds UI assets to GF's "no conflict" list.
	 *
	 * @since 1.0.1
	 *
	 * @param array $assets
	 *
	 * @return array
	 */
	private function whitelist_ui_assets( array $assets ): array {
		$assets[] = $this->query_filters::ASSETS_HANDLE;

		return $assets;
	}
}
