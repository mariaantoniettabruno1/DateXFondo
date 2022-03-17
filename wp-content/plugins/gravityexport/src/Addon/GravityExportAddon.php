<?php

namespace GravityKit\GravityExport\Addon;

use GFExcel\Addon\AddonInterface;
use GFExcel\Addon\AddonTrait;
use Gravity_Forms\Gravity_Forms\Settings\Fields\Base;
use GravityKit\GravityExport\License\GravityExportLicense;
use Spatie\FlysystemDropbox\DropboxAdapter;

class GravityExportAddon extends \GFAddOn implements AddonInterface {
	use AddonTrait;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_min_gravityforms_version = '2.0';

	/**
	 * @inheritDoc
	 * @since 1.0
	 */
	protected $_capabilities_form_settings = 'gravityforms_export_entries';

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_capabilities_settings_page = 'gravityforms_export_entries';

	/**
	 * {@inheritdoc}
	 */
	protected $_path = 'gravityexport/gravityexport.php';

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_full_path = __FILE__;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_url = 'https://gfexcel.com';

	/**
	 * @since 1.0
	 * @var GravityExportLicense Plugin license instance.
	 */
	public $license;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_title = GK_GRAVITYEXPORT_PLUGIN_NAME;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_short_title = GK_GRAVITYEXPORT_PLUGIN_NAME;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_version = GK_GRAVITYEXPORT_PLUGIN_VERSION;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_slug = GK_GRAVITYEXPORT_PLUGIN_SLUG;

	/**
	 * GravityExportAddon constructor.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->set_license_handler();

		parent::__construct();
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function minimum_requirements(): array {
		return [
			'php' => [
				'version'    => '7.1',
				'extensions' => [
					'openssl',
					'json',
				],
			]
		];
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function render_uninstall() {
		return null;
	}

	/**
	 * Set the license handler.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	function set_license_handler() {
		if ( ! empty( $this->license ) ) {
			return;
		}

		$this->license = GravityExportLicense::get_instance( $this );
	}


	/**
	 * Render plugin settings field
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	function plugin_settings_fields() {

		$license_fields = $this->license->plugin_settings_fields();

		return apply_filters( 'gravitykit/gravityexport/settings/sections', [
			$license_fields,
		], $this );
	}

	/**
	 * Update a single setting.
	 *
	 * @since 1.0
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return boolean Whether the settings were updated or not
	 */
	public function update_plugin_setting( $key, $value ): bool {
		if ( ! is_string( $key ) ) {
			return false;
		}

		$settings         = parent::get_plugin_settings();
		$existing_setting = $settings[ $key ] ?? false;

		if ( $existing_setting === $value ) {
			return false;
		}

		$settings[ $key ] = $value;

		parent::update_plugin_settings( $settings );

		return true;
	}

	/**
	 * Register the settings field for the EDD License field type.
	 *
	 * @since 1.0
	 *
	 * @param Base $field
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function settings_edd_license( $field, $echo = true ) {
		$text = self::settings_text( $field, false );

		$activation = $this->license->settings_edd_license_activation( $field, false );

		$return = $text . $activation;

		if ( $echo ) {
			echo $return;
		}

		return $return;
	}

	/***
	 * Render the save button for settings pages.
	 *
	 * Same as GFAddOn::settings_save(), but allows for overriding the button class.
	 *
	 * @since 1.0
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function settings_save( $field, $echo = true ): string {
		$button = parent::settings_save( $field, false );

		// Replace the class
		if ( ! empty( $field['class'] ) ) {
			$button = str_replace( 'button-primary gfbutton', esc_attr( $field['class'] ), $button );
		}

		$button .= wp_nonce_field( GK_GRAVITYEXPORT_PLUGIN_SLUG, '_wpnonce', true, false );

		if ( $echo ) {
			echo $button;
		}

		return $button;
	}

	/***
	 * Render the save button for settings pages.
	 *
	 * @since 1.0
	 *
	 * @param array $field Field array containing the configuration options of this field.
	 * @param bool  $echo  True to echo the output to the screen; false to simply return the contents as a string.
	 *
	 * @return string The HTML
	 */
	public function settings_submit( $field, $echo = true ): string {
		$field['type'] = ( isset( $field['type'] ) && in_array( $field['type'], [
				'submit',
				'reset',
				'button'
			] ) ) ? $field['type'] : 'submit';

		$attributes    = $this->get_field_attributes( $field );
		$default_value = rgar( $field, 'value' ) ? rgar( $field, 'value' ) : rgar( $field, 'default_value' );
		$value         = $this->get_setting( $field['name'], $default_value );

		$attributes['class'] = isset( $field['class'] ) ? esc_attr( $field['class'] ) : $attributes['class'];

		$html       = $field['html_before'] ?? '';
		$html_after = $field['html_after'] ?? '';

		if ( ! rgar( $field, 'value' ) ) {
			$field['value'] = esc_html__( 'Update Settings', 'gk-gravityexport' );
		}

		$attributes = $this->get_field_attributes( $field );

		unset( $attributes['html_before'], $attributes['html_after'], $attributes['tooltip'] );

		$html .= '<input
                    type="' . esc_attr( $field['type'] ) . '"
                    name="' . esc_attr( $field['name'] ) . '"
                    value="' . esc_attr( $value ) . '" ' .
		         implode( ' ', $attributes ) .
		         ' />';

		$html .= $html_after;
		$html .= wp_nonce_field( GK_GRAVITYEXPORT_PLUGIN_SLUG, '_wpnonce', true, false );

		if ( $echo ) {
			echo $html;
		}

		return $html;
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
	 * Uninstall extension.
	 *
	 * @since 1.0
	 *
	 * @return bool
	 */
	public function uninstall(): bool {
		/**
		 * Set the path so that Gravity Forms can de-activate the plugin.
		 *
		 * @see  \GFAddOn::uninstall_addon
		 * @uses deactivate_plugins()
		 */
		$this->_path = GK_GRAVITYEXPORT_PLUGIN_FILE;

		return true;
	}
}