<?php

namespace ZionBuilderPro\Elements;

use ZionBuilder\Elements\Element;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class WooCommerceElement
 *
 * This is the base class that will bring helper methods to all WooCommerce elements
 *
 * @package ZionBuilderPro\Elements
 */
class WooCommerceElement extends Element {
	/**
	 * Holds a refference to the initial post
	 *
	 * @var WP_Post
	 */
	private $overridden_post = null;

	/**
	 * Holds a refference to the initial product
	 *
	 * @var WP_Product
	 */
	private $overridden_product = null;

	/**
	 * Holds a refference to the page $wp_query
	 *
	 * @var WP_Query
	 */
	private $overridden_wp_query = null;

	/**
	 * Holds a refference to all the overrides made by the current element
	 *
	 * @var array
	 */
	private $woocommerce_overrides = [];

	/**
	 * Holds a refference to the sample product in case the element settings don't return
	 * a valid product
	 *
	 * @var WC_Product|boolean
	 */
	private static $woocommerce_sample_product_id = null;


	/**
	 * Retrieve a product based on id or 'current' ( current will use the page query )
	 *
	 * @param int|string $product_id The product id that we want to setup
	 * @param array $overrides The query overrides
	 *
	 * @return WC_Product|boolean The product object or false on failure
	 */
	public function get_woocommerce_product( $product_id, $overrides = [] ) {
		global $product, $post, $wp_query;

		$global_post_id              = get_the_ID();
		$this->woocommerce_overrides = $overrides;

		if ( 'current' === $product_id || empty( $product_id ) ) {
			$product_id = $global_post_id;
		}

		// Check to see if the current post is a product and fallback to a product id
		if ( 'product' !== get_post_type( $product_id ) ) {
			$product_id = $this->get_woocommerce_sample_product_id();
		}

		// Don't proceed if we do not have a product
		if ( ! $product_id ) {
			return false;
		}

		// Check to see if we need to overwrite global post
		if ( in_array( 'post', $overrides, true ) ) {
			$this->overridden_post = $post;
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride
			$post = get_post( $product_id );
			setup_postdata( $product_id );
		}

		// Check to see if we need to overwrite data
		if ( in_array( 'product', $overrides, true ) && $product_id !== $global_post_id ) {
			$this->overridden_product = $post;
			$product                  = wc_get_product( $product_id );
		}

		// Check to see if we need to overwrite global wp_query
		if ( in_array( 'wp_query', $overrides, true ) ) {
			$this->overridden_wp_query = $wp_query;
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride
			$wp_query = new \WP_Query( array( 'p' => $product_id ) );
		}

		return $product;
	}

	/**
	 * Resets global variables to initial state
	 *
	 * @return void
	 */
	public function reset_woocommerce_product_query() {
		global $product, $post, $wp_query;

		// Check to see if we need to overwrite global post
		if ( in_array( 'post', $this->woocommerce_overrides, true ) && ! empty( $this->overridden_post ) ) {
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride
			$post = $this->overridden_post;
			setup_postdata( $post->ID );
		}

		// Check to see if we need to overwrite data
		if ( in_array( 'product', $this->woocommerce_overrides, true ) ) {
			$product = $this->overridden_product;
		}

		// Check to see if we need to overwrite global wp_query
		if ( in_array( 'wp_query', $this->woocommerce_overrides, true ) ) {
			// phpcs:ignore WordPress.WP.GlobalVariablesOverride
			$wp_query = $this->overridden_wp_query;
		}
	}

	/**
	 * Returns a sample product or false if no product exists
	 *
	 * @return int|boolea The product id or false on failure
	 */
	public function get_woocommerce_sample_product_id() {
		if ( null === self::$woocommerce_sample_product_id ) {
			$args = [
				'posts_per_page' => 1,
			];

			$products = \wc_get_products( $args );

			if ( empty( $products[0] ) ) {
				self::$woocommerce_sample_product_id = false;
			}

			self::$woocommerce_sample_product_id = $products[0]->get_id();
		}

		return self::$woocommerce_sample_product_id;
	}

	public function server_render( $config ) {
		// Load template actions for frontend since they only load in frontend and wp_ajax actions
		// @see WooCommerce::includes()
		if ( function_exists( 'WC' ) ) {
			\WC()->frontend_includes();
			\WC_Template_Loader::init();
		}

		parent::server_render( $config );
	}
}
