<?php

namespace GravityKit\GravityExport\PdfRenderer;

use GFExcel\GFExcelAdmin;
use GravityKit\GravityExport\PdfRenderer\Renderer\PdfRenderer;
use GravityKit\GravityExport\PdfRenderer\Writer\PDF;
use GFExcel\Renderer\PHPExcelRenderer;
use GFExcel\Renderer\RendererInterface;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Exception as WriterException;

/**
 * The entry point of the plugin.
 *
 * Configures the necessary hooks.
 *
 * @since 1.0
 */
class Plugin {
	/**
	 * Name of the orientation setting field.
	 *
	 * @since 1.0
	 * @var string
	 */
	private const SETTING_ORIENTATION = 'gfexcel_pdf_orientation';

	/**
	 * Name of the paper size setting field.
	 *
	 * @since 1.0
	 * @var string
	 */
	private const SETTING_PAPER_SIZE = 'gfexcel_pdf_paper_size';

	/**
	 * Name of the settings that shows the page number.
	 *
	 * @since 1.0
	 * @var string
	 */
	private const SETTING_SHOW_PAGE_NUMBER = 'gfexcel_pdf_show_page_number';

	/**
	 * Name of the settings that shows the page title.
	 *
	 * @since 1.0
	 * @var string
	 */
	private const SETTING_SHOW_PAGE_TITLE = 'gfexcel_pdf_show_page_title';

	/**
	 * Page title setting representing no title.
	 *
	 * @since 1.0
	 * @var int
	 */
	private const PAGE_TITLE_NONE = 0;

	/**
	 * Page title setting representing a title on the first page only.
	 *
	 * @since 1.0
	 * @var int
	 */
	private const PAGE_TITLE_FIRST_PAGE = 1;

	/**
	 * Page title setting representing a title on every page.
	 *
	 * @since 1.0
	 * @var int
	 */
	private const PAGE_TITLE_ALL_PAGES = 2;

	/**
	 * Page title setting representing a title on every other page, starting at page 1.
	 *
	 * @since 1.0
	 * @var int
	 */
	private const PAGE_TITLE_ODD_PAGES = 3;

	/**
	 * GravityExport Lite plugin.
	 *
	 * @since 1.0
	 * @var GFExcelAdmin
	 */
	private $plugin;

	/**
	 * Initialize constructor.
	 *
	 * @throws WriterException When the writer could not be created.
	 */
	public function __construct( GFExcelAdmin $plugin ) {
		$this->plugin = $plugin;

		// Register our custom PDF writer as a default Renderer.
		IOFactory::registerWriter( 'Pdf', PDF::class );

		add_filter( 'gfexcel_file_extensions', \Closure::fromCallable( [ $this, 'addPdfExtensionUrl' ] ) );
		add_filter( 'gfexcel_download_renderer', \Closure::fromCallable( [ $this, 'replaceRenderer' ] ) );
		add_filter( 'gfexcel_general_settings', \Closure::fromCallable( [ $this, 'addPdfSettingFields' ] ) );
		add_filter( 'gravitykit/gravityexport/pdf/page/orientation', \Closure::fromCallable( [ $this, 'setPdfOrientation' ] ), 10, 2 );
		add_filter( 'gravitykit/gravityexport/pdf/page/paper-size', \Closure::fromCallable( [ $this, 'setPdfPaperSize' ] ), 10, 2 );

		add_action( 'gravitykit/gravityexport/pdf/generator/init', \Closure::fromCallable( [ $this, 'addHeaderFooter' ] ), 10, 2 );
	}

	/**
	 * Adds .PDF as a valid extension for the download URL.
	 *
	 * @since 1.0
	 *
	 * @param array $extensions The add-ons
	 *
	 * @return string[] The new add-ons.
	 */
	private function addPdfExtensionUrl( array $extensions ): array {
		return array_merge( $extensions, [ 'pdf' ] );
	}

	/**
	 * Decorated a {@see PHPExcelRenderer} instance.
	 *
	 * @since 1.0
	 *
	 * @param RendererInterface $renderer The current renderer.
	 *
	 * @return RendererInterface The decorated renderer.
	 */
	private function replaceRenderer( RendererInterface $renderer ): RendererInterface {
		if ( ! $renderer instanceof PHPExcelRenderer ) {
			return $renderer;
		}

		return new PdfRenderer( $renderer, dirname( __FILE__, 2 ) . '/assets' );
	}

	/**
	 * Adds the PDF specific settings to the settings page.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $settings The settings.
	 *
	 * @return mixed[] The updated settings.
	 */
	private function addPdfSettingFields( array $settings ): array {
		$settings = array_merge( $settings, [
			[
				'title'  => esc_html__( 'PDF Export Settings', 'gk-gravityexport' ),
				'fields' => [
					[
						'name'          => self::SETTING_ORIENTATION,
						'label'         => esc_html__( 'Page Orientation', 'gk-gravityexport' ),
						'type'          => 'radio',
						'default_value' => PageSetup::ORIENTATION_LANDSCAPE,
						'choices'       => [
							[
								'name'  => self::SETTING_ORIENTATION,
								'label' => esc_html__( 'Landscape', 'gk-gravityexport' ),
								'value' => PageSetup::ORIENTATION_LANDSCAPE,
							],
							[
								'name'  => self::SETTING_ORIENTATION,
								'label' => esc_html__( 'Portrait', 'gk-gravityexport' ),
								'value' => PageSetup::ORIENTATION_PORTRAIT,
							],
						],
					],
					[
						'name'          => self::SETTING_PAPER_SIZE,
						'label'         => esc_html__( 'Page Size', 'gk-gravityexport' ),
						'type'          => 'select',
						'default_value' => PageSetup::PAPERSIZE_LETTER,
						'choices'       => [
							[
								'name'  => self::SETTING_PAPER_SIZE,
								'label' => esc_html__( 'Letter', 'gk-gravityexport' ),
								'value' => PageSetup::PAPERSIZE_LETTER,
							],
							[
								'name'  => self::SETTING_PAPER_SIZE,
								'label' => esc_html__( 'A4', 'gk-gravityexport' ),
								'value' => PageSetup::PAPERSIZE_A4,
							],
						],
					],
					[
						'name'    => self::SETTING_SHOW_PAGE_TITLE,
						'label'   => esc_html__( 'Show Title', 'gk-gravityexport' ),
						'type'    => 'select',
						'choices' => [
							[
								'name'  => self::SETTING_SHOW_PAGE_TITLE,
								'label' => esc_html__( 'No title', 'gk-gravityexport' ),
								'value' => self::PAGE_TITLE_NONE,
							],
							[
								'name'  => self::SETTING_SHOW_PAGE_TITLE,
								'label' => esc_html__( 'On first page', 'gk-gravityexport' ),
								'value' => self::PAGE_TITLE_FIRST_PAGE,
							],
							[
								'name'  => self::SETTING_SHOW_PAGE_TITLE,
								'label' => esc_html__( 'On all pages', 'gk-gravityexport' ),
								'value' => self::PAGE_TITLE_ALL_PAGES,
							],
							[
								'name'  => self::SETTING_SHOW_PAGE_TITLE,
								'label' => esc_html__( 'On every other page (double sided printing)', 'gk-gravityexport' ),
								'value' => self::PAGE_TITLE_ODD_PAGES,
							],
						],
					],
					[
						'name'    => self::SETTING_SHOW_PAGE_NUMBER,
						'label'   => esc_html__( 'Show Page Number', 'gk-gravityexport' ),
						'type'    => 'checkbox',
						'choices' => [
							[
								'label'         => esc_html__( 'Yes, show page numbers', 'gk-gravityexport' ),
								'name'          => self::SETTING_SHOW_PAGE_NUMBER,
								'default_value' => 1,
							]
						],
					],
				],
			],
		] );

		return $settings;
	}

	/**
	 * Sets the orientation from the settings page.
	 *
	 * @since 1.0
	 *
	 * @param string $orientation The current orientation.
	 * @param int    $form_id     The form ID.
	 *
	 * @return string The orientation.
	 */
	private function setPdfOrientation( string $orientation, int $form_id ): string {
		$settings = $this->plugin->get_form_settings( \GFAPI::get_form( $form_id ) );

		return $settings[ self::SETTING_ORIENTATION ] ?? $orientation;
	}

	/**
	 * Sets the paper size from the settings page.
	 *
	 * @since 1.0
	 *
	 * @param int $size    The current size.
	 * @param int $form_id The form ID.
	 *
	 * @return int The paper size.
	 */
	private function setPdfPaperSize( int $size, int $form_id ): int {
		$settings = $this->plugin->get_form_settings( \GFAPI::get_form( $form_id ) );

		return $settings[ self::SETTING_PAPER_SIZE ] ?? $size;
	}

	/**
	 * Defines a header and a footer for the page.
	 *
	 * @since 1.0
	 *
	 * @param Mpdf $mpdf    The Mpdf instance.
	 * @param int  $form_id The form ID.
	 */
	private function addHeaderFooter( Mpdf $mpdf, int $form_id ): void {
		$form  = \GFAPI::get_form( $form_id );
		$title = gf_apply_filters( [ 'gfexcel_renderer_title', $form_id ], $form['title'] ?? '', $form );

		if ( $form[ self::SETTING_SHOW_PAGE_NUMBER ] ?? true ) {
			$mpdf->DefHTMLFooterByName( 'Page_number', '<div class="page-number">{PAGENO}</div>' );
		}

		if ( $show_page_title = (int) ( $form[ self::SETTING_SHOW_PAGE_TITLE ] ?? 0 ) ) {
			$name = 'Form_title';
			$html = sprintf( '<div class="page-title">%s</div>', $title );

			if ( $show_page_title === self::PAGE_TITLE_ODD_PAGES ) {
				$mpdf->mirrorMargins = true;
			}

			$mpdf->DefHTMLHeaderByName( $name . '_first', $html );
			if ( $show_page_title !== self::PAGE_TITLE_FIRST_PAGE ) {
				$mpdf->DefHTMLHeaderByName( $name, $html );
			}
		}
	}
}
