<?php

namespace ZionBuilderPro\Elements\WooLoopAddToCart;

use ZionBuilderPro\Elements\WooCommerceElement;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class WooLoopAddToCart
 *
 * @package ZionBuilderPro\Elements
 */
class WooLoopAddToCart extends WooCommerceElement {
	/**
	 * Get type
	 *
	 * Returns the unique id for the element
	 *
	 * @return string The element id/type
	 */
	public function get_type() {
		return 'woo-loop-add-to-cart';
	}

	/**
	 * Get name
	 *
	 * Returns the name for the element
	 *
	 * @return string The element name
	 */
	public function get_name() {
		return __( 'Woo loop add to cart', 'zionbuilder-pro' );
	}

	/**
	 * Get keywords
	 *
	 * Returns the keywords for this element
	 *
	 * @return array The list of element keywords
	 */
	public function get_keywords() {
		return [ 'cart', 'woocommerce' ];
	}


	/**
	 * Get Element Icon
	 *
	 * Returns the icon used in add elements panel for this element
	 *
	 * @return string The element icon
	 */
	public function get_element_icon() {
		return 'element-woo-add-to-cart';
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
	 * Enqueue element styles for both frontend and editor
	 *
	 * If you want to use the ZionBuilder cache system you must use
	 * the enqueue_editor_style(), enqueue_element_style() functions
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		// Using helper methods will go through caching policy
		wp_enqueue_style( 'woocommerce-general' );
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

		\woocommerce_template_loop_add_to_cart();

		$this->reset_woocommerce_product_query();
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'add_button',
			[
				'title'                   => esc_html__( 'Add to cart button', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .add_to_cart_button',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'price_ammount',
			[
				'title'                   => esc_html__( 'Added to cart link', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .added_to_cart ',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}
}
