<?php

namespace ZionBuilderPro\Integrations\ACF;

use ZionBuilder\Integrations\IBaseIntegration;
use ZionBuilderPro\DynamicContent\Manager;
use ZionBuilderPro\DynamicContent\BaseField;

// Field types
use ZionBuilderPro\Integrations\ACF\Fields\AcfFieldTypeText;
use ZionBuilderPro\Integrations\ACF\Fields\AcfFieldTypeLink;
use ZionBuilderPro\Integrations\ACF\Fields\AcfFieldTypeImage;

// Repeater provider
use ZionBuilderPro\Integrations\ACF\AcfRepeaterProvider;

class Acf implements IBaseIntegration {
	/**
	 * Retrieve the name of the integration
	 *
	 * @return string
	 */
	public static function get_name() {
		return 'acf';
	}

	/**
	 * Check if we can load this integration or not
	 *
	 * @return boolean If true, the integration will be loaded
	 */
	public static function can_load() {
		return function_exists( 'acf' );
	}

	/**
	 * Acf constructor
	 */
	public function __construct() {
		add_action( 'zionbuilderpro/dynamic_content_manager/register_fields', [ $this, 'register_fields' ] );
		add_action( 'zionbuilderpro/dynamic_content_manager/register_field_groups', [ $this, 'register_field_group' ] );

		// Repeater functionality
		add_action('zionbuilderpro/repeater/register_providers', [ $this, 'register_repeater_provider' ]);

		// Add repeater options to acf repeater
		// add_filter( 'zionbuilder/api/bulk_actions/get_input_select_options/get_acf_repeater_fields', [ $this, 'get_repeater_options_for_select' ], 10, 3 );


		// add_action('zionbuilder/editor/after_scripts', [$this, 'on_editor_before_scripts'] );

		// Testing
		// $field_groups     = \acf_get_field_groups();
		// // var_dump( $field_groups );
		// add_action('wp', function () {
		// 	if( have_rows('repeater') ):
		// 		while( have_rows('repeater') ) : the_row();

		// 			// Get parent value.
		// 			echo get_sub_field('text_1');
		// 			echo get_field('textarea');

		// 			// Loop over sub repeater rows.
		// 			if( have_rows('repeater2') ):
		// 				while( have_rows('repeater2') ) : the_row();

		// 					// Get sub value.
		// 					echo get_sub_field('text2');

		// 				endwhile;
		// 			endif;
		// 		endwhile;
		// 	endif;

		// 	echo '-----------------------';
		// 	$rows = get_field('repeater');
		// 	if (have_rows('repeater')) {
		// 		foreach ($rows as $row) {
		// 			have_rows('repeater');
		// 			the_row();
		// 			echo get_sub_field('text_1');

		// 			$rows2 = get_sub_field('repeater2');
		// 			if (have_rows('repeater2')) {
		// 				foreach ($rows2 as $row) {
		// 					have_rows('repeater2');
		// 					the_row();
		// 					echo get_sub_field('text2');
		// 				}
		// 			}
		// 		}
		// 	}
		// 	var_dump($rows);
		// });
	}

	public function register_repeater_provider ( $manager ) {
		$manager->register_provider( new AcfRepeaterProvider() );
	}

	public function register_field_group( Manager $elements_manager ) {
		$elements_manager->register_field_group(
			[
				'id'   => 'ACF',
				'name' => esc_html__( 'ACF', 'zionbuilder-pro' ),
			]
		);
	}

	/**
	 * Will register all supported elements
	 *
	 * @param Manager $elements_manager
	 */
	public function register_fields( Manager $elements_manager ) {
		$elements_manager->register_field( new AcfFieldTypeText() );
		$elements_manager->register_field( new AcfFieldTypeLink() );
		$elements_manager->register_field( new AcfFieldTypeImage() );
	}



	// public function on_editor_before_scripts() {
	// 	wp_add_inline_script(
	// 		'zb-editor', '
	// 		window.znpb_acf_repeater_filter_options = function(options, element) {
	// 			console.log({options});
	// 			console.log({element});

	// 			return options
	// 		}
	// 	');
	// }

}
