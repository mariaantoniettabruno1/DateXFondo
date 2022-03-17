<?php

namespace ZionBuilderPro\Elements\WooProductMeta;

use ZionBuilderPro\Elements\WooCommerceElement;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class WooProductMeta
 *
 * @package ZionBuilderPro\Elements
 */
class WooProductMeta extends WooCommerceElement {
	/**
	 * Get type
	 *
	 * Returns the unique id for the element
	 *
	 * @return string The element id/type
	 */
	public function get_type() {
		return 'woo-product-meta';
	}

	/**
	 * Get name
	 *
	 * Returns the name for the element
	 *
	 * @return string The element name
	 */
	public function get_name() {
		return __( 'Woo product meta', 'zionbuilder-pro' );
	}


	/**
	 * Get keywords
	 *
	 * Returns the keywords for this element
	 *
	 * @return array The list of element keywords
	 */
	public function get_keywords() {
		return [ 'meta', 'woocommerce' ];
	}


	/**
	 * Get Element Icon
	 *
	 * Returns the icon used in add elements panel for this element
	 *
	 * @return string The element icon
	 */
	public function get_element_icon() {
		return 'element-woo-product-meta';
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

		// Don't proceed if we do not have a post
		if ( ! $product ) {
			return;
		}

		\woocommerce_template_single_meta();

		$this->reset_woocommerce_product_query();
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'sku_wrapper',
			[
				'title'                   => esc_html__( 'SKU wrapper', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .sku_wrapper',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'links',
			[
				'title'                   => esc_html__( 'Link styles', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} a',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}
}
