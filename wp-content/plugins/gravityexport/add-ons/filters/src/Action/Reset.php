<?php

namespace GravityKit\GravityExport\Filters\Action;

use GFExcel\Action\AbstractAction;
use GravityKit\GravityExport\Filters\Addon\FiltersFeedAddon;
use GFExcel\Generator\HashGeneratorInterface;

/**
 * Action to reset the URL of the Filters add-on feed.
 *
 * @since 1.0
 */
class Reset extends AbstractAction {
	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public static $name = 'filter_set_reset';

	/**
	 * @since 1.0
	 * @var HashGeneratorInterface The hash generator.
	 */
	private $generator;

	/**
	 * Creates the reset action.
	 *
	 * @since 1.0
	 *
	 * @param HashGeneratorInterface $generator The hash generator.
	 */
	public function __construct( HashGeneratorInterface $generator ) {
		$this->generator = $generator;
	}

	/**
	 * @inheritdoc
	 *
	 * Generates a hash and stores as a form setting.
	 *
	 * @since 1.0
	 */
	public function fire( \GFAddOn $addon, array $form ): void {
		if ( ! $addon instanceof FiltersFeedAddon ) {
			return;
		}

		try {
			$hash = $this->generator->generate();
		} catch ( \Exception $exception ) {
			$addon->add_error_message( sprintf( esc_html__( 'There was an error generating the URL: %s', 'gk-gravityexport' ), $exception->getMessage() ) );
			return;
		}

		$settings         = $form['meta'] ?? [];
		$settings['hash'] = esc_attr( $hash );

		// Save settings to the feed.
		$addon->save_feed_settings( $form['id'], $form['form_id'], $settings );

		// Update the current and previous settings.
		$addon->set_settings( $settings );
		$addon->set_previous_settings( $settings );

		// Set notification of success.
		$addon->add_message( esc_html__( 'The download URL has been reset.', 'gk-gravityexport' ) );
	}
}
