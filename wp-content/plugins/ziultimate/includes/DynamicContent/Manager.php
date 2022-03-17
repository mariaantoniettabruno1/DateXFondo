<?php
namespace ZiUltimate\DynamicContent;

// Fields
use ZiUltimate\DynamicContent\Fields\TermTitle;
use ZiUltimate\DynamicContent\Fields\TermImage;
use ZiUltimate\DynamicContent\Fields\GetQueryString;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class Manager
 *
 * @package ZiUltimate\DynamicContent
 */
class Manager {

	/**
	 * Main class constructor
	 *
	 * @return void
	 */
	function __construct()
	{
		add_action( 'zionbuilderpro/dynamic_content_manager/register_fields', [ $this, 'zu_register_dynamic_content_fields' ] );
	}

	/**
	 * Register default fields
	 *
	 * Will register our default strings
	 */
	public function zu_register_dynamic_content_fields( $promanager ) {
		
		// Taxonomy
		$promanager->register_field( new TermTitle() );
		$promanager->register_field( new TermImage() );

		//Other
		$promanager->register_field( new GetQueryString() );
	}

}