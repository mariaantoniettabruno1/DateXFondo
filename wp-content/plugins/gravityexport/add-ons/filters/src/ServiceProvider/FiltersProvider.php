<?php

namespace GravityKit\GravityExport\Filters\ServiceProvider;

use GravityKit\GravityExport\Filters\Action\Reset;
use GFExcel\Generator\HashGeneratorInterface;
use GFExcel\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * The service provider for the Filters add-on.
 *
 * @since 1.0
 */
class FiltersProvider extends AbstractServiceProvider implements BootableServiceProviderInterface {
	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	protected $provides = [];

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function register(): void {
	}

	/**
	 * @inheritdoc
	 * @since 1.0
	 */
	public function boot(): void {
		$this->addAction( Reset::class )->addArgument( HashGeneratorInterface::class );
	}
}
