<?php

namespace ZionBuilderPro\Elements\WooProductTabs;

use ZionBuilderPro\Elements\WooCommerceElement;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class WooProductTabs
 *
 * @package ZionBuilderPro\Elements
 */
class WooProductTabs extends WooCommerceElement {
	/**
	 * Get type
	 *
	 * Returns the unique id for the element
	 *
	 * @return string The element id/type
	 */
	public function get_type() {
		return 'woo-tabs';
	}

	/**
	 * Get name
	 *
	 * Returns the name for the element
	 *
	 * @return string The element name
	 */
	public function get_name() {
		return __( 'Woo data tabs', 'zionbuilder-pro' );
	}

	/**
	 * Get keywords
	 *
	 * Returns the keywords for this element
	 *
	 * @return array The list of element keywords
	 */
	public function get_keywords() {
		return [ 'tabs', 'data' ];
	}


	/**
	 * Get Element Icon
	 *
	 * Returns the icon used in add elements panel for this element
	 *
	 * @return string The element icon
	 */
	public function get_element_icon() {
		return 'element-woo-breadcrumbs';
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

	public function options( $options ) {
		$options->add_option(
			'delimiter',
			[
				'type'  => 'text',
				'title' => esc_html__( 'Delimiter', 'zionbuilder-pro' ),
			]
		);
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
		\woocommerce_output_product_data_tabs();
	}

	public function server_render( $request ) {
		$product_id = $this->options->get_value( 'product_id', 'current' );
		$product    = $this->get_woocommerce_product(
			$product_id,
			[
				'product',
				'post',
				'wp_query',
			]
		);

		if ( ! $product instanceof \WC_Product ) {
			$this->reset_woocommerce_product_query();
			return;
		}

		parent::server_render( $request );

		$this->reset_woocommerce_product_query();

	}

	public function on_register_styles() {

	}
}
