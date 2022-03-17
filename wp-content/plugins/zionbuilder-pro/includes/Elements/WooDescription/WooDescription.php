<?php

namespace ZionBuilderPro\Elements\WooDescription;

use ZionBuilderPro\Elements\WooCommerceElement;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class WooDescription
 *
 * @package ZionBuilderPro\Elements
 */
class WooDescription extends WooCommerceElement {
	/**
	 * Get type
	 *
	 * Returns the unique id for the element
	 *
	 * @return string The element id/type
	 */
	public function get_type() {
		return 'woo-description';
	}

	/**
	 * Get name
	 *
	 * Returns the name for the element
	 *
	 * @return string The element name
	 */
	public function get_name() {
		return __( 'Woo product description', 'zionbuilder-pro' );
	}


	/**
	 * Get keywords
	 *
	 * Returns the keywords for this element
	 *
	 * @return array The list of element keywords
	 */
	public function get_keywords() {
		return [ 'description', 'woocommerce' ];
	}


	/**
	 * Get Element Icon
	 *
	 * Returns the icon used in add elements panel for this element
	 *
	 * @return string The element icon
	 */
	public function get_element_icon() {
		return 'element-woo-description';
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
			'description_type',
			[
				'type'        => 'select',
				'default'     => 'short',
				'title'       => esc_html__( 'Description type', 'zionbuilder-pro' ),
				'description' => esc_html__( 'Choose what type of description for this product you want to show', 'zionbuilder-pro' ),
				'options'     => [
					[
						'name' => esc_html__( 'Short', 'zionbuilder-pro' ),
						'id'   => 'short',
					],
					[
						'name' => esc_html__( 'Full', 'zionbuilder-pro' ),
						'id'   => 'full',
					],
				],
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
		$description_type = $options->get_value( 'description_type', 'short' );
		$product_id       = $options->get_value( 'product_id', 'current' );
		$product          = $this->get_woocommerce_product(
			$product_id,
			[
				'product',
				'post',
			]
		);

		// Don't proceed if we do not have a post
		if ( ! $product instanceof \WC_Product ) {
			$this->reset_woocommerce_product_query();
			return;
		}

		if ( 'short' === $description_type ) {
			\woocommerce_template_single_excerpt();
		} else {
			the_content();
		}

		$this->reset_woocommerce_product_query();
	}
}
