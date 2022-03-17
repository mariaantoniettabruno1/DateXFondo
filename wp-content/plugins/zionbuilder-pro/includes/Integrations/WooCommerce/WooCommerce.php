<?php

namespace ZionBuilderPro\Integrations\WooCommerce;

use ZionBuilder\Integrations\IBaseIntegration;
use ZionBuilder\Plugin;

class WooCommerce implements IBaseIntegration {
	/**
	 * Retrieve the name of the integration
	 *
	 * @return string
	 */
	public static function get_name() {
		return 'woocommerce';
	}

	/**
	 * Check if we can load this integration or not
	 *
	 * @return boolean If true, the integration will be loaded
	 */
	public static function can_load() {
		return class_exists( 'WooCommerce' );
	}


	/**
	 * Main class constructor
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action( 'zionbuilder/elements_manager/register_elements', [ $this, 'register_elements' ] );
		add_filter( 'zionbuilder/elements/categories', [ $this, 'add_elements_categories' ] );
		add_filter( 'zionbuilder/preview/app/css_classes', [ $this, 'add_preview_app_css_classes' ] );
		add_filter( 'zionbuilder/single/area_class', [ $this, 'add_content_area_classes' ], 10, 2 );
	}

	public function add_content_area_classes( $classes, $area_id ) {
		global $product;

		if ( \is_product() && $product && function_exists( 'wc_get_product_class' ) ) {
			$classes = array_merge( $classes, wc_get_product_class( '', $product ) );
		}

		return $classes;
	}

	public function add_preview_app_css_classes( $classes ) {
		global $product;

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( get_the_ID() );
		}

		if ( \is_product() && $product && function_exists( 'wc_get_product_class' ) ) {
			$classes = array_merge( $classes, wc_get_product_class( '', $product ) );
		} else {
			$classes = array_merge( $classes, [ 'woocommerce', 'product', 'single-product' ] );
		}

		return $classes;
	}


	/**
	 * Adds the WooCommerce category to the elements category list
	 *
	 * @since 2.0.0
	 *
	 * @param array $categories
	 * @see zionbuilder/elements/categories filter
	 *
	 * @return array
	 */
	public function add_elements_categories( $categories ) {
		$categories[] = [
			'id'   => 'woocommerce',
			'name' => __( 'WooCommerce', 'zionbuilder-pro' ),
		];

		return $categories;
	}


	/**
	 * Will register all WooCommerce elements
	 *
	 * @since 2.0.0
	 *
	 * @param \ZionBuilder\Elements\Manager $elements_manager
	 * @return void
	 */
	public function register_elements( $elements_manager ) {
		$elements = [
			'WooPrice\WooPrice',
			'WooAddToCart\WooAddToCart',
			'WooLoopAddToCart\WooLoopAddToCart',
			'WooBreadcrumbs\WooBreadcrumbs',
			'WooDescription\WooDescription',
			'WooProductMeta\WooProductMeta',
			'WooProductRating\WooProductRating',
			'WooProductImages\WooProductImages',
			'WooProductStock\WooProductStock',
			'WooUpsells\WooUpsells',
			'WooRelated\WooRelated',
			'WooProductTabs\WooProductTabs',
		];

		foreach ( $elements as $element_name ) {
			// Normalize class name
			$class_name = str_replace( '-', '_', $element_name );
			$class_name = 'ZionBuilderPro\\Elements\\' . $class_name;
			$elements_manager->register_element( new $class_name() );
		}
	}
}
