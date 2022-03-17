<?php

namespace GravityKit\GravityActions;

use GFAddOn;
use Gravity_Forms\Gravity_Forms\Settings\Fields\Base;
use GravityKit\GravityActions\License\License;

/**
 * Class Addon.
 *
 * @since   1.0
 *
 * @package GravityKit\GravityActions
 */
class Addon extends GFAddOn {
	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_min_gravityforms_version = '2.0';

	/**
	 * {@inheritdoc}
	 * @since 1.0
	 */
	protected $_path = 'gravityactions/GravityActions.php';

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_full_path = Plugin::FILE;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_url = 'https://gravityview.co';

	/**
	 * @since 1.0
	 * @var Addon
	 */
	static private $instance;

	/**
	 * @since 1.0
	 *
	 * @return string
	 */

	/**
	 * @since 1.0
	 * @var License Plugin license instance.
	 */
	public $license;

	/**
	 * @since 1.0
	 * @inheritDoc
	 */
	public function __construct() {
		$this->_version     = Plugin::VERSION;
		$this->_title       = __( 'GravityActions', 'gk-gravityactions' );
		$this->_short_title = __( 'GravityActions', 'gk-gravityactions' );
		$this->_slug        = Plugin::SLUG;

		$this->set_license_handler();

		parent::__construct();
	}

	/**
	 * Get an instance of this class.
	 *
	 * @since 1.0
	 *
	 * @return Addon
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Set minimum requirements to prevent bugs when using older versions, or missing dependencies.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function minimum_requirements() {
		return [
			'php' => [
				'version' => '5.6',
			]
		];
	}

	public function render_uninstall() {
		return null;
	}

	/**
	 * Return the plugin's icon for the plugin/form settings menu.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function get_menu_icon() {
		// We use absolute position to allow for the icon to be bigger than the GF max-width.
		// The GF font icons are 24px
		$inline_style = 'height: 24px; width: 24px; position: absolute; max-width: none;';

		return '<svg style="' . $inline_style . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><path class="cls-1" d="M234.814 48.488l-81.174 81.174a8 8 0 0 1-11.314 0l-29.17-29.173a4 4 0 0 1 0-5.656l5.655-5.657a4 4 0 0 1 5.656 0l23.517 23.516 51.48-51.478a7.981 7.981 0 0 0-7.472-5.21h-80.004a8 8 0 0 0-8 8v80a8 8 0 0 0 8 8h80.004a8 8 0 0 0 8-8v-34.75a8.001 8.001 0 0 1 2.342-5.658l10.244-10.243a2 2 0 0 1 3.414 1.415v49.236a24 24 0 0 1-24 24h-80.004a24 24 0 0 1-24-24v-40H63.986a8 8 0 0 0-8 8v80.001a8 8 0 0 0 8 8h80.003a8 8 0 0 0 8-8v-4a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v4a24 24 0 0 1-24.001 24H63.987a24 24 0 0 1-24.001-24v-80.001a24 24 0 0 1 24-24h24.002v-24a24 24 0 0 1 24-24h80.004a23.794 23.794 0 0 1 19.089 9.593l12.421-12.422a4 4 0 0 1 5.657 0l5.655 5.656a4 4 0 0 1 0 5.657z"/></svg>';
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

		$this->license = License::get_instance( $this );
	}


	/**
	 * Render plugin settings field
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	function plugin_settings_fields() {
		$fields = [
			$this->license->plugin_settings_fields()
		];

		return $fields;
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
	 * @since 1.0
	 *
	 * @param array $field Field array containing the configuration options of this field.
	 * @param bool  $echo  True to echo the output to the screen; false to simply return the contents as a string.
	 *
	 * @return string The HTML
	 */
	public function settings_submit( $field, $echo = true ) {
		$field['type'] = ( isset( $field['type'] ) && in_array( $field['type'], [
				'submit',
				'reset',
				'button'
			] ) ) ? $field['type'] : 'submit';

		$attributes    = $this->get_field_attributes( $field );
		$default_value = rgar( $field, 'value' ) ? rgar( $field, 'value' ) : rgar( $field, 'default_value' );
		$value         = $this->get_setting( $field['name'], $default_value );

		$attributes['class'] = isset( $field['class'] ) ? esc_attr( $field['class'] ) : $attributes['class'];

		$html       = ! empty( $field['html_before'] ) ? $field['html_before'] : '';
		$html_after = ! empty( $field['html_after'] ) ? $field['html_after'] : '';

		if ( ! rgar( $field, 'value' ) ) {
			$field['value'] = esc_html__( 'Update Settings', 'gk-gravityactions' );
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
		$html .= wp_nonce_field( $this->_slug, '_wpnonce', true, false );

		if ( $echo ) {
			echo $html;
		}

		return $html;
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function styles() {
		return array_merge( parent::styles(), [
			[
				'handle'  => License::ASSETS_HANDLE . '-css',
				'src'     => plugin_dir_url( Plugin::FILE ) . 'src/assets/css/license.css',
				'enqueue' => [
					[
						'admin_page' => 'plugin_settings',
						'tab'        => $this->_slug,
					],
				],
			],
		] );
	}

}