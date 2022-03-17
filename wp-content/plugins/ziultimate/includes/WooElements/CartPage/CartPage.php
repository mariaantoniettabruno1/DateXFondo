<?php
namespace ZiUltimate\WooElements\CartPage;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class CartPage
 *
 * @package ZiUltimate\WooElements
 */
class CartPage extends UltimateElements {

    public function get_type() {
		return 'zu_cart_page';
	}

	public function get_name() {
		return __( 'Cart Builder', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'cart container', 'cart page', 'cart builder' ];
	}

	/*public function get_label() {
		return [
			'text'  => $this->get_label_text(),
			'color' => $this->get_label_color(),
		];
	}*/

	public function get_element_icon() {
		return 'element-section';
	}

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
			$description = 'With this tool you can create the custom cart page.';
			$options->add_option(
				'el',
				[
					'type' 		=> 'html',
					'content' 	=> self::getHTMLContent($title, $description)
				]
			);

			return;
		}

		$options->add_option(
			'tag',
			[
				'type'        => 'select',
				'description' => esc_html__( 'Select the HTML tag to use for this element. If you want to add a custom tag, make sure to only use letters and numbers', 'zionbuilder' ),
				'title'       => esc_html__( 'HTML tag', 'zionbuilder' ),
				'default'     => 'div',
				'addable'     => true,
				'filterable'  => true,
				'options'     => [
					[
						'id'   => 'section',
						'name' => 'Section',
					],
					[
						'id'   => 'div',
						'name' => 'Div',
					],
					[
						'id'   => 'main',
						'name' => 'Main',
					],
					[
						'id'   => 'aside',
						'name' => 'Aside',
					]
				],
			]
		);

		/*$options->add_option(
			'overwrite_empty_cart',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Overwrite existing empty cart layout?', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

		$options->add_option(
			'zion_template',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Template ID', 'ziultimate'),
				'placeholder' 	=> esc_html__('Enter template ID', 'ziultimate'),
				'dependency' 	=> [
					[
						'option' 	=> 'overwrite_empty_cart',
						'value' 	=> [ true ]
					]
				]
			]
		);*/

		$options->add_option(
			'el_cp',
			[
				'type' 		=> 'text',
				'default' 	=> 'zu' . self::elVal(),
				'css_class' => 'znpb-checkbox-switch-wrapper__checkbox'
			]
		);

		$options->add_option(
			'redirect_url',
			[
				'type' 			=> 'link',
				'title'			=> esc_html__('Redirect URL'),
				'description' 	=> esc_html__('Empty cart page will redirect to this selected URL.', 'ziultimate' ),
				'dynamic' 		=> [
					'enabled' => true
				]
			]
		);
	}

	public function get_wrapper_tag( $options ) {
		return $options->get_value( 'tag', 'div' );
	}

	/**
	 * Loading the js files
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/CartPage/editor.js' ) );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/CartPage/frontend.js' ) );
	}

	public function before_render( $options ) {
		$redirect_url = $options->get_value( 'redirect_url', false );
		if( ! empty( $redirect_url ) ) {
			$this->render_attributes->add( 'wrapper', 'data-ecp-redirect', esc_url( $redirect_url ) );
		}
	}

	public function render( $options ) 
	{
		// Constants.
		wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

		$atts        = shortcode_atts( array(), $atts, 'woocommerce_cart' );
		$nonce_value = wc_get_var( $_REQUEST['woocommerce-shipping-calculator-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); 

		if ( ! empty( $_POST['calc_shipping'] ) && ( wp_verify_nonce( $nonce_value, 'woocommerce-shipping-calculator' ) || wp_verify_nonce( $nonce_value, 'woocommerce-cart' ) ) 
		) {
			self::calculate_shipping();

			// Also calc totals before we check items so subtotals etc are up to date.
			WC()->cart->calculate_totals();
		}

		// Check cart items are valid.
		do_action( 'woocommerce_check_cart_items' );

		do_action( 'woocommerce_before_cart' );

		$this->render_children();

		/*if ( is_null( WC()->cart ) || WC()->cart->is_empty() ) {
			$is_empty_cart 	= $options->get_value( 'overwrite_empty_cart', false );
			$zion_template 	= $options->get_value( 'zion_template', false );
			
			if( ! empty( $is_empty_cart ) && ! empty( $zion_template ) ) {
				do_action( 'woocommerce_cart_is_empty' );

				echo do_shortcode( sprintf( '[zionbuilder id="%s"]', absint( $zion_template ) ) );
			} else {
				wc_get_template( 'cart/cart-empty.php' );
			}
		}*/

		do_action( 'woocommerce_after_cart' );
	}
}