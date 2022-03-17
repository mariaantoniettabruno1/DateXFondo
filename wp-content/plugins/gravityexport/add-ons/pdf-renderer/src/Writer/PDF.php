<?php

namespace GravityKit\GravityExport\PdfRenderer\Writer;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

/**
 * A Custom DomPDF writer.
 *
 * @since 1.0
 */
class PDF extends Mpdf {
	/**
	 * The form ID provided by the document properties.
	 *
	 * @since 1.0
	 * @var int
	 */
	private $form_id;

	/**
	 * The assets directory.
	 *
	 * @since 1.0
	 * @var string
	 */
	private $assets_dir;

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function __construct( Spreadsheet $spreadsheet ) {
		parent::__construct( $spreadsheet );
		$this->form_id = (int) $spreadsheet->getProperties()->getCustomPropertyValue( 'gravityexport_pdf_form_id' );

		// No inline css.
		$this->setUseInlineCss( false );

		// Top, bottom, left, right.
		$margin = gf_apply_filters( [
			'gravitykit/gravityexport/pdf/page/margin',
			$this->form_id,
		], [ 0.3, 0.3, 0.3, 0.3 ] );

		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd( 1, 1 );
		$sheet->getPageMargins()
		      ->setTop( $margin[0] )
		      ->setBottom( $margin[1] )
		      ->setLeft( $margin[2] )
		      ->setRight( $margin[3] );
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function generateHTMLHeader( $pIncludeStyles = false ): string {
		// The HTML of the header to edit.
		$html = parent::generateHTMLHeader( true );

		// Remove default table styles that prevent setting custom styles.
		$html = preg_replace( '/(td|td|th).style.*?{.*?}\n?/', '', $html );

		// The URL to the plugin's CSS directory.
		$dir = rtrim( $this->assets_dir, '/' );

		// Add custom stylesheets to the HTML.
		$stylesheets = implode( '', array_map( static function ( string $css_file ): string {
			return file_get_contents( $css_file ) . "\n";
		}, gf_apply_filters(
			[ 'gravitykit/gravityexport/pdf/styles', $this->form_id ],
			[ $dir . '/base.css' ],
			$dir
		) ) );

		$html = preg_replace( '/<\/style>/ism', $stylesheets . '</style>', $html );

		return gf_apply_filters(
			[ 'gravitykit/gravityexport/pdf/page/header', $this->form_id ],
			str_replace( [ '</head>' ], [ $stylesheets . '</head>' ], $html ),
			$this->spreadsheet
		);
	}

	/**
	 * Sets the assets directory.
	 *
	 * @since 1.0
	 *
	 * @param string $assets_dir Assets directory.
	 */
	public function setAssetsDir( string $assets_dir ): void {
		$this->assets_dir = $assets_dir;
	}

	/**
	 * @inheritdoc
	 *
	 * Added hook for the generator.
	 *
	 * @since 1.0
	 */
	protected function createExternalWriterInstance( $config ): \Mpdf\Mpdf {
		$config['setAutoBottomMargin'] = 'stretch';
		$config['setAutoTopMargin']    = 'stretch';
		$config['autoMarginPadding']   = 3;

		$config = gf_apply_filters(
			[ 'gravitykit/gravityexport/pdf/config', $this->form_id ],
			$config,
			$this->form_id
		);

		// Add ability to update the generator, but not replace it.
		$generator = parent::createExternalWriterInstance( $config );

		gf_do_action( [ 'gravitykit/gravityexport/pdf/generator/init', $this->form_id ], $generator, $this->form_id );

		return $generator;
	}

	/**
	 * @inheritDoc
	 *
	 * Added hook to overwrite, and landscape by default.
	 *
	 * @since 1.0
	 */
	public function getOrientation(): string {
		return gf_apply_filters(
			[ 'gravitykit/gravityexport/pdf/page/orientation', $this->form_id ],
			PageSetup::ORIENTATION_LANDSCAPE,
			$this->form_id
		);
	}

	/**
	 * @inheritdoc
	 *
	 * Added hook to overwrite, and A4 by default.
	 *
	 * @since 1.0
	 */
	public function getPaperSize(): int {
		return gf_apply_filters(
			[ 'gravitykit/gravityexport/pdf/page/paper-size', $this->form_id ],
			PageSetup::PAPERSIZE_LETTER,
			$this->form_id
		);
	}
}
