<?php

namespace GravityKit\GravityExport\MultiRow;

use GFExcel\GFExcelAdmin;
use GFExcel\GFExcelConfigConstants;
use GravityKit\GravityExport\MultiRow\Transformer\MultipleRowsCombiner;
use GFExcel\Transformer\CombinerInterface;

/**
 * The plugin that enables the multiple rows add-on.
 *
 * @since 1.0
 * @codeCoverageIgnore
 */
class Plugin {
	/**
	 * Name of the setting field.
	 *
	 * @since 1.0
	 */
	private const SETTING_MULTI_ROW = 'gravityexport-multi-row';

	/**
	 * GravityExport Lite plugin.
	 *
	 * @since 1.0
	 * @var GFExcelAdmin
	 */
	private $plugin;

	/**
	 * Registers all hooks.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		$this->plugin = GFExcelAdmin::get_instance();

		add_filter( 'gfexcel_general_settings', \Closure::fromCallable( [ $this, 'addMultiRowSettingField' ] ) );
		add_filter( GFExcelConfigConstants::GFEXCEL_DOWNLOAD_COMBINER, \Closure::fromCallable( [ $this, 'replaceCombiner' ] ), 10, 2 );
	}

	/**
	 * Adds the setting to the general settings page.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $settings The default general settings.
	 *
	 * @return mixed[] The updated settings.
	 */
	private function addMultiRowSettingField( array $settings ): array {
		$form_settings = $this->plugin->get_current_settings();

		$settings = array_merge( $settings, [
			[
				'title'  => 'Multiple Rows',
				'fields' => [
					[
						'name'    => self::SETTING_MULTI_ROW,
						'label'   => esc_html__( 'Enable multi-row splitting', 'gk-gravityexport' ),
						'type'    => 'checkbox',
						'choices' => [
							[
								'name'          => self::SETTING_MULTI_ROW,
								'label'         => esc_html__( 'Yes, split fields with multiple values into multiple rows', 'gk-gravityexport' ),
								'default_value' => $form_settings[ self::SETTING_MULTI_ROW ] ?? 0,
							],
						],
					]
				],
			],
		] );

		return $settings;
	}

	/**
	 * Replaces the default combiner with the multiple rows combiner.
	 *
	 * @since 1.0
	 *
	 * @param CombinerInterface $combiner The current combiner.
	 * @param int|null          $form_id  The form ID.
	 *
	 * @return CombinerInterface The new combiner.
	 */
	private function replaceCombiner( CombinerInterface $combiner, ?int $form_id = null ): CombinerInterface {
		if ( $form_id ) {
			$settings = $this->plugin->get_form_settings( \GFAPI::get_form( $form_id ) );
			if ( $settings[ Plugin::SETTING_MULTI_ROW ] ?? 0 ) {
				$combiner = new MultipleRowsCombiner();
			}
		}

		return $combiner;
	}
}