<?php

namespace ZionBuilderPro\Elements\CustomCode;

use \ZionBuilder\Elements\CustomCode\CustomCode as FreeCustomCode;
use ZionBuilderPro\Utils;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class Text
 *
 * @package ZionBuilder\Elements
 */
class CustomCode extends FreeCustomCode {
	/**
	 * On before init
	 *
	 * Allow the users to add their own initialization process without extending __construct
	 *
	 * @param array<string, mixed> $data The data for the element instance
	 *
	 * @return void
	 */
	public function on_before_init( $data = [] ) {
		$this->on( 'options/schema/set', [ $this, 'change_options' ] );
	}

	public function change_options() {
		$custom_php_group = $this->options->get_option( 'custom_php' );
		$custom_php_group->remove_option( 'upgrade_to_pro' );

		$custom_php_group->add_option(
			'apply_button',
			[
				'type'        => 'element_event_button',
				'event'       => 'apply_php_code',
				'button_text' => esc_html__( 'Apply', 'zionbuilder-pro' ),
			]
		);

		$custom_php_group->add_option(
			'php',
			[
				'type'        => 'code',
				'description' => esc_html__( 'Using this option you can enter you own custom PHP code.The code will apply automatically as soon as you type the ending PHP tag', 'zionbuilder-pro' ),
				'title'       => esc_html__( 'PHP code', 'zionbuilder-pro' ),
				'mode'        => 'application/x-httpd-php',
			]
		);

	}

	/**
	 * Enqueue element scripts for both frontend and editor
	 *
	 * If you want to use the ZionBuilder cache system you must use
	 * the enqueue_editor_script(), enqueue_element_script() functions
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		parent::enqueue_scripts();
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/CustomCode/editor.js' ) );
	}

	/**
	 * Renders the element based on options
	 *
	 * @param \ZionBuilder\Options\Options $options
	 *
	 * @return void
	 */
	public function render( $options ) {
		$custom_php = $options->get_value( 'php' );

		// We won't escape this as this element accepts user generated content
		echo $options->get_value( 'content' ); // phpcs:ignore WordPress.Security.EscapeOutput

		try {
			eval( ' ?>' . $custom_php );
		} catch ( \ParseError $e ) {
			echo $e->getMessage();
		}
	}
}
