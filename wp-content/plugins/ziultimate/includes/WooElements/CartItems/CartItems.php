<?php
namespace ZiUltimate\WooElements\CartItems;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class CartItems
 *
 * @package ZiUltimate\WooElements
 */
class CartItems extends UltimateElements {

    public function get_type() {
		return 'zu_cart_items';
	}

	public function get_name() {
		return __( 'Cart Items', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'cart items', 'items' ];
	}

	/*public function get_label() {
		return [
			'text'  => $this->get_label_text(),
			'color' => $this->get_label_color(),
		];
	}*/

	public function get_category() {
		return 'zuwccpb';
	}

	public function is_wrapper() {
		return true;
	}

	protected function can_render() 
	{
		if( ! License::has_valid_license() )
			return false;

		return true;
	}
	
	public function options( $options ) 
	{
		if( ! License::has_valid_license() ) {
			$title = $this->get_name();
			$description = 'With this tool you can create the cart items table.';
			$options->add_option(
				'el',
				[
					'type' 		=> 'html',
					'content' 	=> self::getHTMLContent($title, $description)
				]
			);

			return;
		}
	}

	public function render( $options ) 
	{
		if ( is_null( WC()->cart ) || WC()->cart->is_empty() ) {
			return;
		}

		add_filter( 'woocommerce_is_attribute_in_product_name', '__return_false' );

		// Calc totals.
		WC()->cart->calculate_totals();
		
	}
}