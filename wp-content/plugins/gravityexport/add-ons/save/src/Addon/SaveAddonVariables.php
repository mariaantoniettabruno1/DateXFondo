<?php

namespace GravityKit\GravityExport\Save\Addon;

use GFExcel\GFExcelAdmin;

/**
 * This class holds the default variables for the GFFeedAddOn
 *
 * @since 1.0
 */
abstract class SaveAddonVariables extends \GFFeedAddOn {
	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_min_gravityforms_version = '2.0';

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_slug = 'gravityexport-save';

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_full_path = __FILE__;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_title = 'GravityExport Save';

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_short_title = 'GravityExport Save';

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $_capabilities = [ 'gravityforms_export_entries' ];

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function minimum_requirements() {
		return [
			'php'     => [
				'version' => '7.1',
			],
			'add-ons' => [
				'gf-entries-in-excel' => [
					'version' => '1.9',
				]
			],
		];
	}

	/**
	 * The columns for the feed list.
	 *
	 * @since 1.0
	 * @return string[]
	 */
	public function feed_list_columns(): array {
		return [
			SaveAddon::STORAGE_TITLE => esc_html__( 'Title', 'gk-gravityexport' ),
			SaveAddon::STORAGE_TYPE  => esc_html__( 'Type', 'gk-gravityexport' ),
			SaveAddon::FILE_TYPE     => esc_html__( 'Contains', 'gk-gravityexport' ),
		];
	}
}
