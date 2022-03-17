<?php

namespace GravityKit\GravityExport\Filters\Addon;

use GFExcel\Action\ActionAware;
use GFExcel\Action\ActionAwareInterface;
use GFExcel\Addon\AddonHelperTrait;
use GFExcel\Addon\AddonInterface;
use GFExcel\Addon\AddonTrait;
use GravityKit\GravityExport\Filters\Action\Reset;
use GFExcel\Generator\HashGeneratorInterface;
use GFExcel\GFExcel;
use GFExcel\Repository\FieldsRepository;
use GFExcel\Repository\FormRepositoryInterface;
use GFExcel\Template\TemplateAware;
use GFExcel\Template\TemplateAwareInterface;
use GravityKit\GravityExport\GravityKit\QueryFilters\QueryFilters;

/**
 * Filters add-on.
 *
 * @since 1.0
 */
class FiltersFeedAddon extends \GFFeedAddOn implements AddonInterface, ActionAwareInterface, TemplateAwareInterface {
	use AddonTrait;
	use AddonHelperTrait;
	use ActionAware;
	use TemplateAware;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_title = 'GravityExport Filters';

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_short_title = 'GravityExport Filters';

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_slug = 'gravityexport-filter-sets';

	/**
	 * @since 1.0
	 * @var FormRepositoryInterface The form repository.
	 */
	private $form_repository;

	/**
	 * @since 1.0
	 * @var null|int Whether the feed download was hit.
	 */
	private $download_feed_id;

	/**
	 * @since 1.0
	 * @var HashGeneratorInterface The hash generator.
	 */
	private $generator;

	/**
	 * @since 1.0
	 * @var string Settings renderer.
	 */
	private $renderer;

	/**
	 * @since 1.0
	 * @var string Query Filters instance.
	 */
	private $query_filters;

	/**
	 * @since 1.0
	 * @var array|null GF form object.
	 */
	private $form = null;

	/**
	 * @since 1.0
	 * @var array|null GF feed object.
	 */
	private $feed = null;

	/**
	 * @since 1.0
	 * @var string Feed settings permissions.
	 */
	protected $_capabilities_form_settings = 'gravityforms_export_entries';

	/**
	 * Creates the add-on instance.
	 *
	 * @since 1.0
	 *
	 * @throws \Exception
	 *
	 * @param HashGeneratorInterface  $generator       The hash generator.
	 * @param FormRepositoryInterface $form_repository The form repository.
	 */
	public function __construct( FormRepositoryInterface $form_repository, HashGeneratorInterface $generator ) {
		$this->form_repository = $form_repository;
		$this->generator       = $generator;

		$this->query_filters = new QueryFilters();

		parent::__construct();
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
	 * @inheritdoc
	 * @since 1.0
	 */
	public function init(): void {
		parent::init();

		add_filter( 'gfexcel_file_extension', \Closure::fromCallable( [ $this, 'setFileExtension' ] ) );
		add_filter( 'gfexcel_output_sort_field', \Closure::fromCallable( [ $this, 'setSortField' ] ) );
		add_filter( 'gfexcel_output_sort_order', \Closure::fromCallable( [ $this, 'setSortOrder' ] ) );
		add_filter( 'gfexcel_renderer_filename', \Closure::fromCallable( [ $this, 'setFilename' ] ) );
		add_filter( 'gfexcel_renderer_subject', \Closure::fromCallable( [ $this, 'setRendererTitle' ] ) );
		add_filter( 'gfexcel_renderer_title', \Closure::fromCallable( [ $this, 'setRendererTitle' ] ) );
		add_filter( 'gfexcel_renderer_transpose', \Closure::fromCallable( [ $this, 'setRendererTranspose' ] ) );
		add_filter( 'gfexcel_renderer_worksheet_title', \Closure::fromCallable( [ $this, 'setRendererTitle' ] ) );

		add_filter( 'gaddon_no_output_field_properties', \Closure::fromCallable( [ $this, 'addNoOutputProperties' ] ) );
		add_action( 'gform_addon_feed_settings_fields', \Closure::fromCallable( [ $this, 'addDownloadForm' ] ) );

		add_filter( 'gform_noconflict_scripts', \Closure::fromCallable( [ $this, 'whitelist_ui_assets' ] ) );
		add_filter( 'gform_noconflict_styles', \Closure::fromCallable( [ $this, 'whitelist_ui_assets' ] ) );

		add_action( 'request', \Closure::fromCallable( [ $this, 'addFiltersForUrlDownload' ] ), 20 );

		$current_form = $this->get_current_form();

		if ( ! $current_form ) {
			return;
		}

		$this->form = $current_form;

		$feeds = $this->get_active_feeds( $current_form['id'] );

		foreach ( $feeds as $feed ) {
			$feed_id           = rgar( $feed, 'id' );
			$form_id           = rgar( $feed, 'form_id' );
			$slug              = rgar( $feed, 'addon_slug' );
			$conditional_logic = rgars( $feed, 'meta/conditional_logic', 'null' );

			if ( $this->_slug !== $slug ) {
				continue;
			}

			$this->addFeedSpecificFilters( $form_id, $feed_id, $conditional_logic );
		}
	}

	/**
	 * Configures filters requiring a form and feed ID.
	 *
	 * @since 1.0
	 *
	 * @param int          $form_id
	 * @param int          $feed_id
	 * @param string|array $conditional_logic
	 */
	private function addFeedSpecificFilters( int $form_id, int $feed_id, $conditional_logic = 'null' ) {
		if ( 'null' !== $conditional_logic ) {
			add_filter( sprintf( 'gfexcel_get_entries_%s_%s', $form_id, $feed_id ), \Closure::fromCallable( [ $this, 'getEntries' ] ), 10, 5 );
		}

		add_filter( sprintf( 'gfexcel_enabled_fields_%s_%s', $form_id, $feed_id ), \Closure::fromCallable( [ $this, 'getEnabledFields' ] ), 10, 3 );
		add_filter( sprintf( 'gfexcel_disabled_fields_%s_%s', $form_id, $feed_id ), \Closure::fromCallable( [ $this, 'getDisabledFields' ] ), 10, 3 );
	}

	/**
	 * Conditionally configures filters when incoming request is for export file.
	 *
	 * @since 1.0
	 *
	 * @param array $query_vars
	 *
	 * @return array
	 */
	private function addFiltersForUrlDownload( array $query_vars ): array {
		$form_id = (int) rgar( $query_vars, 'gfexcel_download_form' );
		$feed_id = (int) rgar( $query_vars, 'gfexcel_download_feed' );

		if ( ! $form_id || ! $feed_id ) {
			return $query_vars;
		}

		// TODO: Use \GFAPI::get_feed when GF minimum version requirement is bumped to ≥2.4.24
		$feeds = \GFAPI::get_feeds( $feed_id, null, null, null );

		$feed = ! is_wp_error( $feeds ) ? $feeds[0] : array();

		$this->feed = $feed;

		$this->form = \GFAPI::get_form( $form_id );

		$conditional_logic = rgars( $this->feed, 'meta/conditional_logic', 'null' );

		$this->addFeedSpecificFilters( $form_id, $feed_id, $conditional_logic );

		return $query_vars;
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function feed_settings_fields(): array {
		$feed = $this->get_current_feed();

		$settings = [
			[
				'title'  => esc_html__( 'General Settings', 'gk-gravityexport' ),
				'fields' => [
					[
						'name'        => 'feedName',
						'label'       => esc_html__( 'Feed Title', 'gk-gravityexport' ),
						'required'    => true,
						'type'        => 'text',
						'class'       => 'medium',
						'description' => esc_html__( 'Will also be used as the export file name.', 'gk-gravityexport' ),
					],
				],
			]
		];

		if ( $feed && (bool) $feed['is_active'] ) {
			$settings = array_merge(
				$settings,
				[
					[
						'title'        => esc_html__( 'Instant Download ⚡️', 'gk-gravityexport' ),
						'id'           => 'file-download-section',
						'description'  => esc_html__( 'Download an export based on the settings configured below. Settings must be saved before they are applied to the downloaded file.', 'gk-gravityexport' ),
						'collapsible'  => true,
						'is_collapsed' => true,
						'fields'       => [
							[
								'form'     => 'download-form',
								'name'     => 'download_file',
								'label'    => esc_html__( 'Date Range', 'gk-gravityexport' ),
								'type'     => 'callback',
								'callback' => function () {
									$data = [
										'description'      => esc_html__( 'Setting a range will limit the export to entries submitted during that date range. If no range is set, all entries will be exported.', 'gk-gravityexport' ),
										'date_placeholder' => esc_html_x( 'YYYY-MM-DD', 'Date input field placeholder', 'gk-gravityexport' ),
										'start_date_value' => $this->get_setting( 'start_date' ),
										'start_date_label' => esc_html__( 'Start Date', 'gk-gravityexport' ),
										'end_date_value'   => $this->get_setting( 'end_date' ),
										'end_date_label'   => esc_html__( 'End Date', 'gk-gravityexport' ),
										'start_date_name'  => 'start_date',
										'end_date_name'    => 'end_date',
									];

									echo join( '', [
										<<<HTML
								<div class="date-selection">
								    <span class="gform-settings-description">${data['description']}</span>
									<div class="date-field">
										<input form="download-form" placeholder="${data['date_placeholder']}" type="text" id="start_date" name="${data['start_date_name']}" class="gaddon-setting gaddon-text" aria-describedby="description-startDate" />
										<label for="start_date" class="gform-settings-description gf_settings_description" id="description-startDate">${data['start_date_label']}</label>										
									</div>
									<div class="date-field">
										<input form="download-form" placeholder="${data['date_placeholder']}" type="text" id="end_date" name="${data['end_date_name']}" class="gaddon-setting gaddon-text" aria-describedby="description-endDate" />
										<label for="end_date" class="gform-settings-description gf_settings_description" id="description-endDate">${data['end_date_label']}</label>										
									</div>
									</div>
HTML,
										'<br>',
										$this->settings_button( [
											'type'  => 'submit',
											'form'  => 'download-form',
											'label' => esc_html__( 'Download', 'gk-gravityexport' ),
										], false )
									] );
								}
							],
						],
					]
				]
			);
		}

		$download_settings_description = '';

		if ( $feed && ! (bool) $feed['is_active'] ) {
			$download_settings_description = strtr( esc_html__( 'The download URL will not work until you [url]activate this feed[/url].', 'gk-gravityexport' ), array(
				'[url]'  => '<a href="' . esc_url( admin_url( sprintf( '/admin.php?page=gf_edit_forms&view=settings&subview=%s&id=%s', $this->_slug, $feed['form_id'] ) ) ) . '">',
				'[/url]' => '</a>',
			) );

			$download_settings_description = sprintf( '<p class="alert warning">%s</p>', $download_settings_description );
		}

		$settings = array_merge(
			$settings, [
				[
					'title'       => esc_html__( 'Download Settings', 'gk-gravityexport' ),
					'description' => $download_settings_description,
					'fields'      => [
						[
							'label'         => esc_html__( 'Download URL', 'gk-gravityexport' ),
							'name'          => 'hash',
							'type'          => 'callback',
							'hidden'        => empty( rgar( $feed['meta'] ?? [], 'hash', '' ) ),
							'callback'      => function () {
								echo join( '', [
									$this->settings_text( [
										'name'     => 'hash',
										'class'    => 'widefat',
										'readonly' => true,
									], false ),
									'<div style="margin: 10px 0;">',
									'<div class="copy-to-clipboard-container alignright" style="margin-top: 0">
                                    <span class="success hidden" style="padding: 0 1em;" aria-hidden="true">' . esc_html__( 'Copied!', 'gk-gravityexport' ) . '</span>
                                    <button type="button" class="button copy-attachment-url" data-clipboard-target="[name=_gform_setting_hash]"><span class="dashicons dashicons-clipboard"></span>
                                    ' . esc_html__( 'Copy URL to Clipboard', 'gk-gravityexport' ) . '</button>
                                </div>',
									$this->settings_button( [
										'type'    => 'submit',
										'name'    => 'gform-settings-save',
										'value'   => Reset::$name,
										'form'    => 'gform-settings',
										'label'   => esc_html__( 'Regenerate URL', 'gk-gravityexport' ),
										'onclick' => 'return confirm("' . esc_html__( "You are about to reset the URL for this form. This can't be undone.", 'gk-gravityexport' ) . '");'
									], false ),
									'</div>',
								] );
							},
							'save_callback' => function () {

								try {
									// Use the previous hash, if set, or generate a new one.
									$hash = rgar( $this->get_previous_settings(), 'hash', $this->generator->generate() );
								} catch ( \Exception $exception ) {
									$this->add_error_message( sprintf( esc_html__( 'There was an error generating the URL: %s', 'gk-gravityexport' ), $exception->getMessage() ) );
									return rgar( $this->get_previous_settings(), 'hash' );
								}

								return basename( $hash );
							},
						],
						[
							'label'         => esc_html__( 'Custom Filename', 'gk-gravityexport' ),
							'type'          => 'text',
							'name'          => 'filename',
							'class'         => 'medium code',
							'description'   => sprintf( esc_html__(
								'Most non-alphanumeric characters will be replaced with hyphens. Leave empty for default (for example: %s).',
								'gk-gravityexport'
							),
								'<code>' . esc_html( GFExcel::getFilename( $this->get_current_form() ) ) . '</code>' ),
							'save_callback' => array( 'GFExcel', 'sanitize_file_name' ),
						],
						[
							'label'   => esc_html__( 'File Extension', 'gk-gravityexport' ),
							'type'    => 'select',
							'name'    => 'file_extension',
							'class'   => 'small-text',
							'choices' => array_map( static function ( $extension ) {
								return [
									'name'  => 'file_extension',
									'label' => '.' . $extension,
									'value' => $extension,
								];
							}, GFExcel::getPluginFileExtensions() ),
						],
					],
				],
				[
					'title'  => esc_html__( 'Field Settings', 'gk-gravityexport' ),
					'class'  => 'sortfields',
					'fields' => [
						[
							'name'     => 'order_by',
							'label'    => esc_html__( 'Order By', 'gk-gravityexport' ),
							'type'     => 'callback',
							'callback' => function () {
								$this->settings_select( [
									'name'    => 'sort_field',
									'choices' => ( new FieldsRepository( $this->get_current_form() ) )->getSortFieldOptions(),
								] );
								$this->settings_select( [
									'name'    => 'sort_order',
									'type'    => 'select',
									'choices' => [
										[ 'value' => 'ASC', 'label' => esc_html__( 'Ascending', 'gk-gravityexport' ) ],
										[ 'value' => 'DESC', 'label' => esc_html__( 'Descending', 'gk-gravityexport' ) ],
									]
								] );
							}
						],
						[
							'name'          => 'transpose',
							'type'          => 'radio',
							'label'         => esc_html__( 'Column Position', 'gk-gravityexport' ),
							'default_value' => 0,
							'choices'       => [
								[
									'name'  => 'transpose',
									'label' => esc_html__( 'Top (Normal)', 'gk-gravityexport' ),
									'value' => 0,
								],
								[
									'name'  => 'transpose',
									'label' => esc_html__( 'Left (Transposed)', 'gk-gravityexport' ),
									'value' => 1,
								]
							]
						],
						[
							'type'    => 'sort_fields',
							'name'    => 'export-fields',
							'choices' => $this->getFields(),
						],
					]
				],
				[
					'title'  => esc_html__( 'Filter Settings', 'gk-gravityexport' ),
					'class'  => 'sortfields',
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
				]
			]
		);

		if ( ! $this->is_gravityforms_supported( '2.5-beta' ) ) {
			$settings = array_merge(
				$settings,
				[
					[
						'fields' => [
							[
								'class' => 'button-primary gfbutton',
								'label' => esc_html__( 'Update Settings', 'gk-gravityexport' ),
								'type'  => 'save'
							]
						]
					]
				]
			);
		}

		return $settings;
	}

	/**
	 * @inheritdoc
	 *
	 * @since 1.0
	 */
	public function settings_button( $field, bool $echo = true ): string {
		$properties = [
			'type'    => esc_attr( rgar( $field, 'type', '' ) ),
			'onclick' => esc_js( rgar( $field, 'onclick', '' ) ),
			'name'    => esc_attr( rgar( $field, 'name', '' ) ),
			'value'   => esc_attr( rgar( $field, 'value', '' ) ),
			'form'    => esc_attr( rgar( $field, 'form', '' ) ),
			'class'   => esc_attr( rgar( $field, 'class', 'button button-secondary' ) ),
		];

		$button_properties = [];
		foreach ( $properties as $property => $value ) {
			if ( ! empty( $value ) ) {
				$button_properties[] = sprintf( '%s="%s"', $property, esc_attr( $value ) );
			}
		}
		$button = sprintf( '<button %s>%s</button>', implode( ' ', $button_properties ), esc_attr( rgar( $field, 'label', '' ) ) );

		if ( $echo ) {
			echo $button;
		}

		return $button;
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

	/***
	 * Renders disabled/enabled fields section.
	 *
	 * @since 1.0
	 *
	 * @param array $field Field array containing the configuration options of this field.
	 * @param bool  $echo  true - true to echo the output to the screen, false to simply return the contents as a string.
	 *
	 * @return void
	 */
	public function settings_sort_fields( $field, $echo = true ): void {

		$field         = (array) $field;
		$field['name'] = $this->prefix_field_name( $field['name'] );

		$enabled_fields  = rgars( $this->get_current_settings(), 'export-fields/enabled', '' );
		$disabled_fields = rgars( $this->get_current_settings(), 'export-fields/disabled', '' );

		/* TODO: Possibly switch to using a template if/when we correct the language strings in the Base repo
        $field['attributes'] = $this->get_field_attributes($field);
        $this->renderTemplate('field/sort-fields', $field);
		*/
		?>
        <div class="gfexcel_field-sort-fields">
            <div>
                <p>
                    <strong>
						<?php esc_html_e( 'Disabled Fields', 'gk-gravityexport' ); ?>
                    </strong>
                </p>
                <input type="hidden" name="<?php echo esc_attr( $field['name'] ); ?>[disabled]" value="<?php echo esc_attr( $disabled_fields ); ?>">
                <ul id="sort-fields-disabled" class="fields-select fields-select--disabled" data-send-to="sort-fields-enabled">
					<?php foreach ( $field['choices']['disabled'] as $choice_field ): ?>
                        <li data-value="<?php echo esc_attr( $choice_field->id ); ?>">
                            <div class="field">
                                <i class="fa fa-bars"></i>
								<?php echo esc_html( $choice_field->get_field_label( true, '' ) ); ?>
                            </div>
                            <div class="move">
                                <i class="fa fa-arrow-right"></i>
                                <i class="fa fa-close"></i>
                            </div>
                        </li>
					<?php endforeach ?>
                </ul>
            </div>
            <div>
                <p>
                    <strong>
						<?php esc_html_e( 'Enabled Fields', 'gk-gravityexport' ); ?>
                    </strong>
                </p>

                <input type="hidden" name="<?php echo esc_attr( $field['name'] ); ?>[enabled]" value="<?php echo esc_attr( $enabled_fields ); ?>">
                <ul id="sort-fields-enabled" class="fields-select fields-select--enabled" data-send-to="sort-fields-disabled">
					<?php foreach ( $field['choices']['enabled'] as $choice_field ) { ?>
                        <li data-value="<?php echo esc_attr( $choice_field->id ); ?>">
                            <div class="field">
                                <i class="fa fa-bars"></i>
								<?php echo esc_attr( $choice_field->get_field_label( true, '' ) ); ?>
                            </div>
                            <div class="move">
                                <i class="fa fa-arrow-right"></i>
                                <i class="fa fa-close"></i>
                            </div>
                        </li>
					<?php } ?>
                </ul>
            </div>
        </div>
		<?php
	}

	/**
	 * The columns to show on the list view.
	 *
	 * @since 1.0
	 */
	public function feed_list_columns(): array {
		return [
			'feedName' => esc_html__( 'Title', 'gk-gravityexport' ),
			'download' => esc_html__( 'Download', 'gk-gravityexport' ),
		];
	}

	/**
	 * Returns all available download add-ons links for this filter feed.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $item The feed item.
	 *
	 * @return string The download links.
	 */
	public function get_column_value_download( $item ): string {
		$settings = $item['meta'] ?? [];

		$links = array_map( function ( string $extension ) use ( $settings ) {
			$url   = sprintf( '%s.%s', $this->form_repository->getDownloadUrl( $settings ), $extension );
			$class = $extension === ( $settings['file_extension'] ?? null ) ? 'button-primary' : 'button-secondary';

			return sprintf( '<a class="%s" href="%s">%s</a>', $class, $url, $extension );
		}, GFExcel::getPluginFileExtensions() );

		$is_active_feed = (bool) $item['is_active'];

		$active_content = sprintf(
			'<div class="active-content" %s>%s</div>',
			! $is_active_feed ? 'hidden' : '',
			implode( ' ', $links )
		);

		$inactive_content = sprintf(
			'<div class="inactive-content" %s>%s</div>',
			$is_active_feed ? 'hidden' : '',
			esc_html__( 'Activate feed to enable download links.', 'gk-gravityexport' )
		);

		return $inactive_content . $active_content;

	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function save_feed_settings( $feed_id, $form_id, $settings ) {
		// In GF 2.5., $_POST must contain 'gform-settings-save' variable no matter what its value is.
		$action = rgpost( 'gform-settings-save' );

		if ( $this->hasAction( $action ) ) {
			// Prevent indefinite loop in case action's fire() method calls save_feed_settings().
			unset( $_POST['gform-settings-save'] );
			$this->getAction( $action )->fire( $this, $this->get_current_feed() );

			return $feed_id;
		}

		return parent::save_feed_settings( $feed_id, $form_id, $settings );
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
		$feed              = $this->get_feed( $feed_id );
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

		$sorting = [
			'key'       => rgars( $feed, 'meta/sort_field' ),
			'direction' => rgars( $feed, 'meta/sort_order' )
		];

		$status = rgar( $search_criteria, 'status', 'active' );

		$query = new \GF_Query( $feed['form_id'], [ 'field_filters' => [], 'status' => $status ], $sorting, $paging );

		$query_parts = $query->_introspect();

		$query->where( \GF_Query_Condition::_and( $query_parts['where'], $conditions ) );

		return $query->get();
	}

	/**
	 * Updates the download filename.
	 *
	 * @since 1.0
	 *
	 * @param string $filename The original filename.
	 *
	 * @return string The updated filename.
	 */
	private function setFilename( string $filename ): string {
		$feed     = $this->get_current_feed();
		$new_name = rgars( $feed ?? [], 'meta/filename' );

		if ( $feed && ! empty( $new_name ) ) {
			$filename = (string) $new_name;
		}

		return $filename;
	}

	/**
	 * Updates the file extension.
	 *
	 * @since 1.0
	 *
	 * @param string $extension The original extension.
	 *
	 * @return string The updated extension.
	 */
	private function setFileExtension( string $extension ): string {
		$feed          = $this->get_current_feed();
		$new_extension = rgars( $feed ?? [], 'meta/file_extension' );

		if ( $feed && ! empty( $new_extension ) ) {
			$extension = (string) $new_extension;
		}

		return $extension;
	}

	/**
	 * Updates the spreadsheet title.
	 *
	 * @since 1.0
	 *
	 * @param string $title The original title.
	 *
	 * @return string The updated title.
	 */
	private function setRendererTitle( string $title ): string {
		$feed      = $this->get_current_feed();
		$new_title = rgars( $feed ?? [], 'meta/feedName' );

		if ( $feed && ! empty( $new_title ) ) {
			$title = (string) $new_title;
		}

		return $title;
	}

	/**
	 * Updates the transpose setting.
	 *
	 * @since 1.0
	 *
	 * @param bool $transposed The original value.
	 *
	 * @return bool The updated value.
	 */
	private function setRendererTranspose( bool $transposed ): bool {
		$feed           = $this->get_current_feed();
		$new_transposed = rgars( $feed ?? [], 'meta/transpose' );

		if ( $feed ) {
			$transposed = (bool) $new_transposed;
		}

		return $transposed;
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function get_current_settings() {
		$settings = parent::get_current_settings();

		return ! empty( $settings ) ? $this->modify_current_settings( $settings ) : $settings;
	}

	/**
	 * Modify current settings that are used to render feed form.
	 *
	 * @param array $settings Current settings.
	 *
	 * @return array Modified settings.
	 */
	public function modify_current_settings( array $settings ): array {
		// Fix fields to retrieve from database.
		$fields = [ 'hash', 'filename' ];
		$feed   = $this->get_feed( $this->get_current_feed_id() );

		foreach ( $fields as $key ) {
			$settings[ $key ] = rgar( $feed['meta'] ?? [], $key );
		}

		// Update hash to have the entire URL.
		if ( ! empty( $settings['hash'] ) ) {
			$settings['hash'] = $this->form_repository->getDownloadUrl( $settings );
		}

		$is_condition_enabled = rgar( $settings, 'feed_condition_conditional_logic' ) == true;
		$logic                = rgars( $settings, $this->is_gravityforms_supported( '2.5-beta' ) ? 'feed_condition_conditional_logic_object' : 'feed_condition_conditional_logic_object/conditionalLogic', [] );

		if ( $is_condition_enabled && ! empty( $logic ) && $this->is_postback() && $this->is_detail_page() ) {
			$download_filters = [ 'f' => [], 'o' => [], 'v' => [] ];
			foreach ( $logic['rules'] as $rule ) {
				$download_filters['f'][] = $rule['fieldId'];
				$download_filters['o'][] = $rule['operator'];
				$download_filters['v'][] = $rule['value'];
			}

			$download_filters['mode'] = $logic['logicType'];

			$settings['download_filters'] = $download_filters;
		}

		return $settings;
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function get_settings_renderer() {
		$renderer = parent::get_settings_renderer();

		if ( ! $renderer ) {
			return $renderer;
		}

		$settings = $renderer->get_current_values();

		$renderer->set_values( $this->modify_current_settings( $settings ) );

		$this->renderer = $renderer;

		return $renderer;
	}

	/**
	 * Replaces the disabled field keys.
	 *
	 * @since 1.0
	 *
	 * @since 1.0
	 *
	 * @param array $fields  Array with disabled field keys.
	 * @param int   $form_id GF form ID.
	 * @param int   $feed_id GF feed ID.
	 *
	 * @return string[] The new disabled field keys.
	 */
	private function getDisabledFields( array $fields, int $form_id, int $feed_id ): array {
		$feed = $this->get_feed( $this->get_current_feed_id() ?: $feed_id );

		if ( $feed ) {
			$meta = ( ! empty( $this->get_posted_settings() ) ) ? $this->get_posted_settings() : $feed['meta'];

			$disabled = rgars( $meta, 'export-fields/disabled', null );

			if ( $disabled !== null ) {
				return explode( ',', $disabled );
			}
		}

		return $fields;
	}

	/**
	 * Replaces the enabled field keys and sorting.
	 *
	 * @since 1.0
	 *
	 * @param array $fields  Array with enabled field keys.
	 * @param int   $form_id GF form ID.
	 * @param int   $feed_id GF feed ID.
	 *
	 * @return string[] The new disabled field keys.
	 */
	private function getEnabledFields( array $fields, int $form_id, int $feed_id ): array {
		$feed = $this->get_feed( $this->get_current_feed_id() ?: $feed_id );

		if ( $feed ) {
			$meta    = ( ! empty( $this->get_posted_settings() ) ) ? $this->get_posted_settings() : $feed['meta'];
			$enabled = rgars( $meta, 'export-fields/enabled', null );

			if ( $enabled !== null ) {
				return explode( ',', $enabled );
			}
		}

		return $fields;
	}

	/**
	 * Overwritten to retrieve the feed ID when downloading the export.
	 *
	 * @since 1.0
	 */
	public function get_current_feed_id(): ?int {
		$id = $this->download_feed_id ?? (int) parent::get_current_feed_id();

		return $id ?: null;
	}

	/**
	 * Add properties that should not be added to the HTML output of a field.
	 *
	 * @since 1.0
	 *
	 * @param string[] $properties The properties.
	 *
	 * @return string[] The updated properties.
	 */
	protected function addNoOutputProperties( array $properties ): array {
		$properties[] = 'fields';

		return $properties;
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
	 * @inheritdoc
	 * @since 1.0
	 */
	public function scripts(): array {
		if ( $this->is_feed_edit_page() ) {
			$this->query_filters->set_form( $this->get_current_form() );
			$this->query_filters->enqueue_scripts( [
				'input_element_name' => $this->prefix_field_name( 'conditional_logic' ),
				'conditions'         => rgar( $this->get_current_settings() ?? [], 'conditional_logic' )
			] );
		}

		return array_merge( parent::scripts(), [
			[
				'handle'  => 'jquery-ui-sortable',
				'enqueue' => [
					[ 'admin_page' => 'form_settings', 'tab' => $this->get_slug() ],
				],
			],
			[
				'handle'   => 'gfexcel-js',
				'callback' => function () {
					$script = sprintf(
						'(function($) { $(document).ready(function() { gfexcel_sortable(\'%s\',\'%s\'); }); })(jQuery);',
						'.gfexcel_field-sort-fields .fields-select',
						'fields-select'
					);

					wp_add_inline_script( 'gfexcel-js', $script );
				},
				'deps'     => [ 'jquery', 'jquery-ui-sortable' ],
				'enqueue'  => [
					[ 'admin_page' => 'form_settings', 'tab' => $this->get_slug() ],
				],
			],
			[
				'handle'   => 'clipboard',
				'callback' => function () {
					$copied_text = esc_attr__( 'URL has been copied to your clipboard.', 'gk-gravityexport' );
					$script      = <<<EOD
(function ( $ ) {
	$( document ).ready( function () {
		var successTimeout, clipboard = new ClipboardJS( '.copy-attachment-url' );

		clipboard.on( 'success', function ( e ) {
			var triggerElement = $( e.trigger ),
				successElement = $( '.success', triggerElement.closest( 'div' ) );

			// Clear the selection and move focus back to the trigger.
			e.clearSelection();

			// Handle ClipboardJS focus bug, see https://github.com/zenorocha/clipboard.js/issues/680
			triggerElement.trigger( 'focus' );

			// Show success visual feedback.
			clearTimeout( successTimeout );

			successElement.removeClass( 'hidden' );

			// Hide success visual feedback after 3 seconds since last success.
			successTimeout = setTimeout( function () {
				successElement.addClass( 'hidden' );
				// Remove the visually hidden textarea so that it isn't perceived by assistive technologies.
				if ( clipboard.clipboardAction.fakeElem && clipboard.clipboardAction.removeFake ) {
					clipboard.clipboardAction.removeFake();
				}
			}, 3000 );

			// Handle success audible feedback.
			wp.a11y.speak( __( 'The file URL has been copied to your clipboard' ) );
		} );
	} );
})( jQuery );
EOD;

					wp_add_inline_script( 'clipboard', $script );
				},
				'deps'     => [ 'jquery', 'wp-a11y', 'wp-i18n' ],
				'enqueue'  => [
					[ 'admin_page' => 'form_settings', 'tab' => $this->get_slug() ],
				],
			]
		] );
	}

	/**
	 * Helper method to retrieve the download filters for the filter hook.
	 *
	 * @since 1.0
	 *
	 * @param bool       $is_query Whether the filters are used for the query. `false` in case of JavaScript use.
	 * @param array|null $settings The provided settings.
	 *
	 * @return mixed[] The download filters.
	 */
	private function getDownloadFilters( bool $is_query, ?array $settings = null ): array {
		if ( ! $settings ) {
			$settings = $this->get_current_settings();
		}

		$is_condition_enabled = rgar( $settings, 'feed_condition_conditional_logic' ) == true;
		$logic                = rgars( $settings, 'feed_condition_conditional_logic_object/conditionalLogic', [] );
		$mode                 = rgar( $logic, 'logicType', 'all' );
		$filters              = [];

		if ( $is_condition_enabled && ! empty( $logic['rules'] ) ) {
			foreach ( $logic['rules'] as $rule ) {
				$filters[] = [
					$is_query ? 'key' : 'field' => $rule['fieldId'],

					'operator' => $rule['operator'],
					'value'    => $rule['value'],
				];
			}
		}

		return compact( 'mode', 'filters' );
	}

	/**
	 * Updates the sort field for this feed.
	 *
	 * @since 1.0
	 *
	 * @param string $field The original sort field.
	 *
	 * @return string The sort field.
	 */
	private function setSortField( string $field ): string {
		$feed       = $this->get_current_feed();
		$sort_field = rgars( $feed ?? [], 'meta/sort_field' );

		if ( $feed && ! empty( $sort_field ) ) {
			return $sort_field;
		}

		return $field;
	}

	/**
	 * Updates the sort order for this feed.
	 *
	 * @since 1.0
	 *
	 * @param string $order The original sort order.
	 *
	 * @return string The sort order.
	 */
	private function setSortOrder( string $order ): string {
		$feed       = $this->get_current_feed();
		$sort_order = rgars( $feed ?? [], 'meta/sort_order' );

		if ( $feed && ! empty( $sort_order ) ) {
			return $sort_order;
		}

		return $order;
	}

	/**
	 * Adds a <form> element that triggers the download on submit.
	 *
	 * @since 1.0
	 *
	 * @param ?array Feed settings.
	 *
	 * @return ?array Feed settings or null.
	 */
	private function addDownloadForm( ?array $feed_settings_fields ) {
		$settings = $this->get_current_settings();
		$hash     = rgar( $settings, 'hash' );
		if ( ! empty( trim( $hash ) ) ) {
			printf(
				'<form id="%s" method="post" action="%s"></form>',
				'download-form',
				$settings['hash']
			);
		}

		return $feed_settings_fields;
	}

	/**
	 * Prefix field name for use in input elements according to the GF version.
	 *
	 * @since 1.0
	 *
	 * @param string $name Field name.
	 *
	 * @return string Prefixed field name.
	 */
	private function prefix_field_name( string $name ): string {
		$prefix = $this->is_gravityforms_supported( '2.5-beta' ) ? '_gform_setting_' : '_gaddon_setting_';

		return $prefix . $name;
	}

	/**
	 * Returns disabled/enabled form fields as configured by the feed.
	 *
	 * @return array
	 */
	private function getFields(): array {
		$repository      = new FieldsRepository( $this->get_current_form(), $this->get_current_feed() ?: [] );
		$disabled_fields = $repository->getDisabledFields();
		$all_fields      = $repository->getFields( true );

		$active_fields = $inactive_fields = [];
		foreach ( $all_fields as $field ) {
			$array_name      = in_array( $field->id, $disabled_fields, false ) ? 'inactive_fields' : 'active_fields';
			${$array_name}[] = $field;
		}

		return [
			'disabled' => $inactive_fields,
			'enabled'  => $repository->sortFields( $active_fields ),
		];
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function get_current_feed() {
		return $this->feed ?? parent::get_current_feed();
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
