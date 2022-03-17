<?php

namespace ZionBuilderPro\Elements\WooProductRating;

use ZionBuilderPro\Elements\WooCommerceElement;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class WooProductRating
 *
 * @package ZionBuilderPro\Elements
 */
class WooProductRating extends WooCommerceElement {
	/**
	 * Get type
	 *
	 * Returns the unique id for the element
	 *
	 * @return string The element id/type
	 */
	public function get_type() {
		return 'woo-product-rating';
	}

	/**
	 * Get name
	 *
	 * Returns the name for the element
	 *
	 * @return string The element name
	 */
	public function get_name() {
		return __( 'Woo product rating', 'zionbuilder-pro' );
	}

	/**
	 * Get keywords
	 *
	 * Returns the keywords for this element
	 *
	 * @return array The list of element keywords
	 */
	public function get_keywords() {
		return [ 'rating', 'woocommerce' ];
	}


	/**
	 * Get Element Icon
	 *
	 * Returns the icon used in add elements panel for this element
	 *
	 * @return string The element icon
	 */
	public function get_element_icon() {
		return 'element-woo-product-rating';
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

		$are_ratings_available = post_type_supports( 'product', 'comments' );
		// Don't proceed if we do not have a post
		if ( ! $product instanceof \WC_Product || ! $are_ratings_available ) {
			$this->reset_woocommerce_product_query();

			if ( ! $are_ratings_available ) {
				echo esc_html__( 'Comments are disabled for products.', 'zionbuilder-pro' );
			}

			return;
		}

		// Render the ratings
		\woocommerce_template_single_rating();

		$this->reset_woocommerce_product_query();
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'ratings',
			[
				'title'                   => esc_html__( 'Rating star style', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .star-rating',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'review_link',
			[
				'title'                   => esc_html__( 'Review link styles', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .woocommerce-review-link',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}
}
