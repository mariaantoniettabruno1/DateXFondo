<?php

namespace ZionBuilderPro\Elements\WooPrice;

use ZionBuilderPro\Elements\WooCommerceElement;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class WooPrice
 *
 * @package ZionBuilderPro\Elements
 */
class WooPrice extends WooCommerceElement {
	/**
	 * Get type
	 *
	 * Returns the unique id for the element
	 *
	 * @return string The element id/type
	 */
	public function get_type() {
		return 'woo-price';
	}

	/**
	 * Get name
	 *
	 * Returns the name for the element
	 *
	 * @return string The element name
	 */
	public function get_name() {
		return __( 'Woo Product Price', 'zionbuilder-pro' );
	}

	/**
	 * Get Category
	 *
	 * Will return the element category
	 *
	 * @return string
	 */
	public function get_category() {
		return 'woocommerce';
	}

	/**
	 * Get keywords
	 *
	 * Returns the keywords for this element
	 *
	 * @return array The list of element keywords
	 */
	public function get_keywords() {
		return [ 'price', 'woocommerce' ];
	}

	/**
	 * Get Element Icon
	 *
	 * Returns the icon used in add elements panel for this element
	 *
	 * @return string The element icon
	 */
	public function get_element_icon() {
		return 'element-woo-product-price';
	}


	public function options( $options ) {
		// TODO: add option to select the product
	}

	/**
	 * Render
	 *
	 * Will render the element based on options
	 *
	 * @param mixed $options
	 *
	 * @return void
	 */
	public function render( $options ) {
		$product_id = $options->get_value( 'product_id', 'current' );
		$product    = $this->get_woocommerce_product(
			$product_id,
			[
				'product',
				'post',
			]
		);

		if ( ! $product instanceof \WC_Product ) {
			$this->reset_woocommerce_product_query();
			return;
		}

		echo $product->get_price_html();

		$this->reset_woocommerce_product_query();
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'symbol_styles',
			[
				'title'                   => esc_html__( 'Price symbol', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .woocommerce-Price-currencySymbol',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'price_ammount',
			[
				'title'                   => esc_html__( 'Price ammount', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .woocommerce-Price-amount',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
		$this->register_style_options_element(
			'tax_label',
			[
				'title'                   => esc_html__( 'Tax label', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .woocommerce-Price-taxLabel',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}
}
