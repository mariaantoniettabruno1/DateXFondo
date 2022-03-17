<?php

namespace GravityKit\GravityExport\PdfRenderer\Renderer;

use GravityKit\GravityExport\PdfRenderer\Writer\PDF;
use GFExcel\Renderer\PHPExcelRenderer;
use GFExcel\Renderer\RendererInterface;
use PhpOffice\PhpSpreadsheet\Document\Properties;

/**
 * A PDF renderer decorator.
 *
 * @since 1.0
 */
class PdfRenderer implements RendererInterface {
	/**
	 * The decorated renderer.
	 *
	 * @since 1.0
	 * @var PHPExcelRenderer
	 */
	private $renderer;

	/**
	 * The assets directory.
	 *
	 * @since 1.0
	 * @var string
	 */
	private $assets_dir;

	/**
	 * Creates the decorator.
	 *
	 * @since 1.0
	 *
	 * @param PHPExcelRenderer $renderer   The decorated renderer.
	 * @param string           $assets_dir The assets directory.
	 */
	public function __construct( PHPExcelRenderer $renderer, string $assets_dir ) {
		$this->renderer   = $renderer;
		$this->assets_dir = $assets_dir;
	}

	/**
	 * @inheritdoc
	 *
	 * Overwritten to add the correct content-type for the headers.
	 *
	 * @since 1.0
	 */
	public function renderOutput( $extension = 'xlsx', $save = false ): string {
		// Let the response know this is a PDF file.
		if ( 'pdf' === $extension && ! $save ) {
			header( 'Content-Type: application/pdf' );
		}

		return $this->renderer->renderOutput( $extension, $save );
	}

	/**
	 * @inheritdoc
	 *
	 * Overwritten to keep track of the form['id'] inside the spreadsheet, so we can apply our filters with that ID.
	 *
	 * @since 1.0
	 */
	public function handle( $form, $columns, $rows, $save = false ): string {
		$this->renderer->getSpreadsheet()->getProperties()->setCustomProperty(
			'gravityexport_pdf_form_id',
			$form['id'],
			Properties::PROPERTY_TYPE_INTEGER
		);

		$writer = $this->renderer->getWriter( 'pdf' );
		if ( $writer instanceof PDF ) {
			$writer->setAssetsDir( $this->assets_dir );
		}

		return $this->renderer->handle( $form, $columns, $rows, $save );
	}
}
