<?php

namespace GravityKit\GravityExport\PdfRenderer\ServiceProvider;

use GFExcel\GFExcelAdmin;
use GravityKit\GravityExport\PdfRenderer\Plugin;
use GFExcel\ServiceProvider\AbstractServiceProvider;

class PdfProvider extends AbstractServiceProvider {
	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $provides = [
		Plugin::class,
	];

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function register() {
		$this->getLeagueContainer()
		     ->add( Plugin::class )
		     ->addArgument( GFExcelAdmin::get_instance() );
	}
}
