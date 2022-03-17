<?php

namespace GravityKit\GravityExport\Save\Service;

use GFExcel\GFExcel;
use GFExcel\GFExcelOutput;
use GravityKit\GravityExport\Save\Addon\SaveAddon;

/**
 * A service that handles the rendering and storing of the files.
 *
 * @since 1.0
 */
class StorageService {
	/**
	 * Holds a reference to the produced files in this instance.
	 *
	 * @since 1.0
	 * @var string[]
	 */
	private $history = [];

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function getForm( string $form_id ): array {
		return \GFAPI::get_form( $form_id );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function renderFile( array $feed, ?array $entries = null ): string {
		$settings       = rgar( $feed, 'meta', [] );
		$form_id        = $feed['form_id'] ?? null;
		$form           = $form_id ? \GFAPI::get_form( $form_id ) : null;
		$file_extension = $settings['file_extension'] ?? null;
		$hash           = md5( implode( '', [
			$form_id,
			$file_extension,
			serialize( $entries )
		] ) );

		// update the file name
		add_filter( 'gfexcel_renderer_filename', static function ( $original_filename ) use ( $feed, $form, $entries ) {

			$filename = SaveAddon::get_filename( $feed, $form, $entries );

			if ( ! $filename ) {
				return $original_filename;
			}

			return $filename;
		} );

		// Update the file extension.
		add_filter( 'gfexcel_file_extension', static function ( $original_file_extension ) use ( $file_extension ) {
			return $file_extension ?: $original_file_extension;
		} );

		if ( ! isset( $this->history[ $hash ] ) ) {
			$renderer = GFExcel::getRenderer( $form_id );

			$output = new GFExcelOutput( $form_id, $renderer, null, $feed['id'] );

			// When a single entry is submitted, update search criteria used when getting entries to only fetch that one entry.
			if ( $entries ) {
				add_filter( sprintf( 'gfexcel_output_search_criteria_%s_%s', $form_id, $feed['id'] ), static function ( $search_criteria ) use ( $entries ) {
					$field_filters                    = rgar( $search_criteria, 'field_filters', [] );
					$search_criteria['field_filters'] = array_merge( $field_filters, [ [ 'key' => 'id', 'value' => $entries[0]['id'] ] ] );

					return $search_criteria;
				} );
			}

			// Store the filename in the history, so we can retrieve it later.
			$this->history[ $hash ] = $output->render( true );
		}

		return $this->history[ $hash ];
	}

	/**
	 * Removes files, and clears history.
	 *
	 * @since 1.0
	 */
	public function clearHistory(): void {
		array_map( static function ( string $file ) {
			if ( file_exists( $file ) ) {
				unlink( $file );
			}
		}, $this->history );

		// Clear history.
		$this->history = [];
	}
}
