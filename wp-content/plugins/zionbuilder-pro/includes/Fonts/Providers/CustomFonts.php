<?php

namespace ZionBuilderPro\Fonts\Providers;

use ZionBuilder\Settings;
use ZionBuilder\FontsManager\FontProvider;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class CustomFonts
 *
 * @package ZionBuilder\FontsManager\Fonts
 */
class CustomFonts extends FontProvider {
	public static function get_id() {
		return 'custom-fonts';
	}

	/**
	 * Main class constructor
	 *
	 * Will load the scripts into 'zionbuilder/frontend/after_load_styles'
	 */
	public function __construct() {
		add_action( 'zionbuilder/frontend/after_load_styles', [ $this, 'enqueue_custom_fonts' ] );
	}

	/**
	 * This function will return the custom fonts from options
	 *
	 * @return [] custom fonts
	 */
	public function get_fonts() {
		return Settings::get_value( 'custom_fonts', [] );
	}

	/**
	 * This function will return the custom fonts css
	 *
	 * @return string|bool
	 */
	public function get_fonts_css() {
		$fonts = $this->get_fonts();

		if ( empty( $fonts ) ) {
			return false;
		}

		$custom_fonts = '';
		foreach ( $fonts as $font ) {
			$custom_fonts .= "@font-face { font-family: '" . $font['font_family'] . "';";
			if ( ! empty( $font['eot'] ) ) {
				$custom_fonts .= "src: url('" . $font['eot'] . "'),
				url('" . $font['eot'] . "?#iefix') format('embedded-opentype');";
			}
			if ( ! empty( $font['woff'] ) ) {
				$custom_fonts .= "src: url('" . $font['woff'] . "') format('woff');";
			}
			if ( ! empty( $font['ttf'] ) ) {
				$custom_fonts .= "src: url('" . $font['ttf'] . "') format('truetype');";
			}
			if ( ! empty( $font['svg'] ) ) {
				$custom_fonts .= "src: url('" . $font['svg'] . "') format('svg');";
			}
			if ( ! empty( $font['weight'] ) ) {
				$custom_fonts .= 'font-weight: ' . $font['weight'] . ';';
			}
			$custom_fonts .= '}';
		}
		return $custom_fonts;
	}

	public function get_data_set() {
		$returned_fonts       = [];
		$all_fonts = $this->get_fonts();

		// Get info for each kit and prepare the response
		if ( is_array( $all_fonts ) ) {
			foreach ( $all_fonts as $font ) {
				$returned_fonts[] = [
					'id'   => $font['font_family'],
					'name' => $font['font_family'],
				];
			}
		}

		return $returned_fonts;
	}

	/**
	 * This function will add inline styles for custom fonts
	 *
	 * @return void
	 */
	public function enqueue_custom_fonts() {
		$custom_fonts = $this->get_fonts_css();

		if ( empty( $custom_fonts ) ) {
			return;
		}

		// add inline styles
		wp_add_inline_style( 'zion-frontend-style', $custom_fonts );
	}
}
