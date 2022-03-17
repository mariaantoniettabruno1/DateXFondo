<?php
namespace ZiUltimate\Repeater;

use ZiUltimate\Repeater\Providers\ExtendedQueryBuilder;
use ZiUltimate\Repeater\Providers\WCBestSellingProducts;
use ZiUltimate\Repeater\Providers\WCFeaturedProducts;
use ZiUltimate\Repeater\Providers\WCOnSaleProducts;
use ZiUltimate\Repeater\Providers\WCRecentlyViewed;
use ZiUltimate\Repeater\Providers\WCTopRatedProducts;

use ZiUltimate\Repeater\Providers\UltimateQueryBuilder;

class RegisterProviders {

	/**
	 * Main class constructor
	 *
	 * @return void
	 */
	function __construct()
	{
		add_action( 'template_redirect', [ $this, 'zu_woo_track_product_view' ], 21 );
		add_action( 'zionbuilderpro/repeater/register_providers', [ $this, 'zu_register_providers' ], 2 );
	}

	/**
	 * Will register repeater providers
	 *
	 * @return void
	 */
	public function zu_register_providers( $repeater )
	{
		$repeater->register_provider( new ExtendedQueryBuilder() );
		$repeater->register_provider( new UltimateQueryBuilder() );

		if( class_exists( 'WooCommerce' ) ) {
			$repeater->register_provider( new WCBestSellingProducts() );
			$repeater->register_provider( new WCFeaturedProducts() );
			$repeater->register_provider( new WCOnSaleProducts() );
			$repeater->register_provider( new WCRecentlyViewed() );
			$repeater->register_provider( new WCTopRatedProducts() );
		}
	}

	/**
	 * Will track the recently viewed product's ids
	 *
	 * @return void
	 */
	public function zu_woo_track_product_view() {
		if( is_admin() )
			return;

		if ( ! is_singular( 'product' ) )
			return;

		global $post;

		if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
			$viewed_products = array();
		} else {
			$viewed_products = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) );
		}

		$keys = array_flip( $viewed_products );

		if ( isset( $keys[ $post->ID ] ) ) {
			unset( $viewed_products[ $keys[ $post->ID ] ] );
		}

		$viewed_products[] = $post->ID;

		if ( count( $viewed_products ) > 15 ) {
			array_shift( $viewed_products );
		}

		// Store for session only.
		wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ) );
	}
}