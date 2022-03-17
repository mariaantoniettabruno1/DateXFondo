<?php
namespace ZiUltimate\WooElements\YithWishlistAddButton;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;
use ZionBuilder\Options\BaseSchema;
use ZionBuilderPro\Elements\WooCommerceElement;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! class_exists( 'YITH_Woocompare_Frontend' ) )
	return false;
/**
 * Class YithWishlistAddButton
 *
 * @package ZiUltimate\WooElements
 */
class YithWishlistAddButton extends UltimateElements {

    public function get_type() {
		return 'zu_wishlist_add_button';
	}

	public function get_name() {
		return __( 'Yith Wishlist Add Button', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'wishlist', 'add button', 'yith', 'wishlist add button' ];
	}

	/*public function get_label() {
		return [
			'text'  => $this->get_label_text(),
			'color' => $this->get_label_color(),
		];
	}*/

	public function get_category() {
		return $this->zuwoo_elements_category();
	}

	public function options( $options ) 
	{
		$options->add_option(
			'product_id',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Product ID', 'ziultimate'),
				'description' 	=> esc_html__('Leave empty if putting on single product page or repeater.', 'zilultimate'),
				'dynamic' 		=> [
					'enabled' => true
				]
			]
		);

		$options->add_option(
			'button_text',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Button Text', 'ziultimate'),
				'default' 		=> esc_html__('Add to wishlist', 'yith-woocommerce-wishlist'),
				'dynamic' 		=> [
					'enabled' => true
				]
			]
		);

		$options->add_option(
			'link_button',
			[
				'type' 			=> 'custom_selector',
				'title' 		=> esc_html__('Link or Button', 'ziultimate'),
				'default' 		=> 'button',
				'options' 		=> [
					[
						'name' 		=> esc_html__('Button'),
						'id' 		=> 'button'
					],
					[
						'name' 		=> esc_html__('Link'),
						'id' 		=> 'link'
					]
				]
			]
		);

		$options->add_option(
			'exclude_products',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Disable button for these products', 'ziultimate'),
				'placeholder' 	=> esc_html__('Enter product ids with comma', 'ziultimate'),
				'dynamic' 	=> [
					'enabled' 	=> true
				],
			]
		);

		$options->add_option(
			'exclude_product_types',
			[
				'type' 			=> 'select',
				'title' 		=> esc_html__('Disable button for these product types', 'ziultimate'),
				'placeholder' 	=> esc_html__('You can select multiple product types', 'ziultimate'),
				'multiple' 		=> true,
				'options' 		=> [
					[
						'name' 	=> esc_html__('Grouped'),
						'id' 	=> 'grouped'
					],
					[
						'name' 	=> esc_html__('Simple'),
						'id' 	=> 'simple'
					],
					[
						'name' 	=> esc_html__('Variable'),
						'id' 	=> 'variable'
					],
					[
						'name' 	=> esc_html__('External'),
						'id' 	=> 'external'
					]
				],
			]
		);
	}

	public function render( $options ) {

	}
}