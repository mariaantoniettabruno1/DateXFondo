<?php
namespace ZiUltimate\WooElements\AddToCart;

use ZiUltimate\UltimateElements;
use ZiUltimate\Admin\License;
use ZionBuilder\Options\BaseSchema;
use ZionBuilderPro\Elements\WooCommerceElement;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class AddToCart
 *
 * @package ZiUltimate\WooElements
 */
class AddToCart extends UltimateElements {

	public $atc_text;
	public $sp_text;
	public $rm_text;
	public $vp_text;
	
	public function get_type() {
		return 'zu_add_to_cart';
	}

	public function get_name() {
		return __( 'Add To Cart', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'cart', 'add', 'add to cart' ];
	}

	/*public function get_label() {
		return [
			'text'  => $this->get_label_text(),
			'color' => $this->get_label_color(),
		];
	}*/

	public function get_element_icon() {
		return 'element-woo-add-to-cart';
	}

	public function get_category() {
		return $this->zuwoo_elements_category();
	}

	/**
	 * Creating the settings fields
	 * 
	 * @return void
	 */
	public function options( $options ) {
		if( ! License::has_valid_license() ) {
			$title = $this->get_name();
			$description = 'With this tool you can create custom add to cart button.';
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
			'is_loop',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Using in the product query loop?', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline'
			]
		);

		$options->add_option(
			'product_id',
			[
				'type' 		=> 'text',
				'title' 	=> esc_html__('Product ID', 'ziultimate'),
				'description' => esc_html__('Keep it empty if you are using on single product page.', 'zilultimate'),
				'dynamic' 	=> [
					'enabled' => true
				],
				'dependency' => [
					[
						'option' 	=> 'is_loop',
						'value' 	=> [ false ]
					]
				]
			]
		);

		$options->add_option(
			'do_ajax',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Do AJAX Add To Cart Action?', 'ziultimate'),
				'default' 	=> false,
				'layout' 	=> 'inline',
				'dependency' => [
					[
						'option' 	=> 'is_loop',
						'value' 	=> [ false ]
					]
				]
			]
		);

		$options->add_option(
			'redirect_url',
			[
				'type' 		=> 'link',
				'title' 	=> esc_html__('Redirect URL', 'ziultimate'),
				'description' => esc_html__('The visitor will redirect to this URL after adding the product.', 'ziultimate'),
				'dependency' => [
					[
						'option' 	=> 'is_loop',
						'value' 	=> [ false ]
					],
					[
						'option' 	=> 'do_ajax',
						'value' 	=> [ true ]
					]
				]
			]
		);


		$options->add_option(
			'remove_price',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Remove Price?', 'ziultimate'),
				'default' 	=> true,
				'layout' 	=> 'inline',
				'dependency' => [
					[
						'option' 	=> 'is_loop',
						'value' 	=> [ false ]
					]
				]
			]
		);

		$options->add_option(
			'remove_stock_msg',
			[
				'type' 		=> 'checkbox_switch',
				'title' 	=> esc_html__('Remove Stock Message?', 'ziultimate'),
				'default' 	=> true,
				'layout' 	=> 'inline',
				'dependency' => [
					[
						'option' 	=> 'is_loop',
						'value' 	=> [ false ]
					]
				]
			]
		);

		/**
		 * Quantity controls 
		 */
		$qty = $options->add_group(
			'qty_field',
			[
				'type' 		=> 'accordion_menu',
				'title' 	=> esc_html__('Quantity Field', 'ziultimate')
			]
		);


		/**
		 * Button text controls 
		 */
		$btns_text = $options->add_group(
			'btns_text',
			[
				'type' 		=> 'accordion_menu',
				'title' 	=> esc_html__('Button Text & Icon', 'ziultimate')
			]
		);

		$atc_btn = $btns_text->add_group(
			'atc',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Add To Cart Text', 'woocommerce')
			]
		);

		$atc_btn->add_option(
			'atc_text',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Text'),
				'placeholder' 	=> 'Add To Cart'
			]
		);

		$atc_btn->add_option(
			'atc_icon',
			[
				'type'        => 'icon_library',
				'id'          => 'icon',
				'title'       => esc_html__( 'Icon', 'zionbuilder' ),
				'description' => esc_html__( 'Choose an icon', 'zionbuilder' ),
			]
		);


		$variation_btn = $btns_text->add_group(
			'variations',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Select Options Text', 'woocommerce'),
				'collapsed' => true
			]
		);

		$variation_btn->add_option(
			'sp_text',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Text'),
				'placeholder' 	=> 'Select Options'
			]
		);

		$variation_btn->add_option(
			'sp_icon',
			[
				'type'        => 'icon_library',
				'id'          => 'icon',
				'title'       => esc_html__( 'Icon', 'zionbuilder' ),
				'description' => esc_html__( 'Choose an icon', 'zionbuilder' ),
			]
		);

		$rm_btn = $btns_text->add_group(
			'read_more',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Read More Text', 'woocommerce'),
				'collapsed' => true
			]
		);

		$rm_btn->add_option(
			'rm_text',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Text'),
				'placeholder' 	=> 'Read More'
			]
		);

		$rm_btn->add_option(
			'rm_icon',
			[
				'type'        => 'icon_library',
				'id'          => 'icon',
				'title'       => esc_html__( 'Icon', 'zionbuilder' ),
				'description' => esc_html__( 'Choose an icon', 'zionbuilder' ),
			]
		);

		$vp_btn = $btns_text->add_group(
			'view_products',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('View Products Text', 'woocommerce'),
				'collapsed' => true
			]
		);

		$vp_btn->add_option(
			'vp_text',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Text'),
				'placeholder' 	=> 'Read More'
			]
		);

		$vp_btn->add_option(
			'vp_icon',
			[
				'type'        => 'icon_library',
				'id'          => 'icon',
				'title'       => esc_html__( 'Icon', 'zionbuilder' ),
				'description' => esc_html__( 'Choose an icon', 'zionbuilder' ),
			]
		);


		/**
		 * Icon Styles
		 */
		$icon_styles = $btns_text->add_option(
			'icon_styles',
			[
				'type' 		=> 'panel_accordion',
				'title' 	=> esc_html__('Icon Styles', 'woocommerce'),
				'collapsed' => true
			]
		);

		$icon_styles->add_option(
			'icon_position',
			[
				'type'             => 'select',
				'title'            => esc_html__( 'Icon position', 'zionbuilder' ),
				'options'          => [
					[
						'name' => esc_html__( 'Icon on left', 'zionbuilder' ),
						'id'   => 'left',
					],
					[
						'name' => esc_html__( 'Icon on right', 'zionbuilder' ),
						'id'   => 'right',
					],
				],
				'default'          => 'left',
				'render_attribute' => [
					[
						'tag_id'    => 'wrapper',
						'attribute' => 'class',
						'value'     => 'zu-atc-icon-{{VALUE}}',
					],
				],
			]
		);

		$icon_styles->add_option(
			'icon_size',
			[
				'type' 			=> 'number_unit',
				'min' 			=> 16,
				'max' 			=> 100,
				'step' 			=> 1,
				'units'       	=> BaseSchema::get_units(),
				'title'			=> esc_html__( 'Icon Size', 'ziultimate' ),
				'css_style' 	=> [
					[
						'selector' 	=> '{{ELEMENT}} ',
						'value' 	=> 'font-size: {{VALUE}}'
					]
				]
			]
		);

		$icon_styles->add_option(
			'icon_color',
			[
				'title' => esc_html__( 'Icon Color', 'zionbuilder' ),
				'type'  => 'colorpicker',
				'width' => 50,
				'css_style' 	=> [
					[
						'selector' 	=> '{{ELEMENT}} .button',
						'value' 	=> 'color: {{VALUE}}'
					],
				]
			]
		);

		$icon_styles->add_option(
			'icon_hcolor',
			[
				'title' => esc_html__( 'Icon Hover Color', 'zionbuilder' ),
				'type'  => 'colorpicker',
				'width' => 50,
				'css_style' 	=> [
					[
						'selector' 	=> '{{ELEMENT}} .button:hover',
						'value' 	=> 'color: {{VALUE}}'
					],
				]
			]
		);

		$icon_styles->add_option(
			'icon_gap',
			[
				'type' 			=> 'number_unit',
				'min' 			=> 0,
				'max' 			=> 20,
				'step' 			=> 1,
				'units'       	=> BaseSchema::get_units(),
				'title'			=> esc_html__( 'Gap Between Icon and Text', 'ziultimate' ),
				'css_style' 	=> [
					[
						'selector' 	=> '{{ELEMENT}}.zu-atc-icon-left',
						'value' 	=> 'margin-right: {{VALUE}}'
					],
					[
						'selector' 	=> '{{ELEMENT}}.zu-atc-icon-right',
						'value' 	=> 'margin-left: {{VALUE}}'
					]
				]
			]
		);
	}

	/**
	 * Get style elements
	 *
	 * @return void
	 */
	public function on_register_styles() {
		$this->register_style_options_element(
			'price_styles',
			[
				'title'    => esc_html__( 'Price', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .price > span.woocommerce-Price-amount, {{ELEMENT}} .price ins span.woocommerce-Price-amount',
			]
		);

		$this->register_style_options_element(
			'sale_price_styles',
			[
				'title'    => esc_html__( 'Strick Through Price', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .price del span.woocommerce-Price-amount, .price del',
			]
		);

		$this->register_style_options_element(
			'buttons_styles',
			[
				'title'    => esc_html__( 'Buttons', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .button',
			]
		);

		$this->register_style_options_element(
			'vc_button_styles',
			[
				'title'    => esc_html__( 'View Cart Button', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .added_to_cart',
			]
		);

		$this->register_style_options_element(
			'clear_btn_styles',
			[
				'title'    => esc_html__( 'Clear Button', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .reset_variations',
			]
		);

		$this->register_style_options_element(
			'variations_label_styles',
			[
				'title'    => esc_html__( 'Variation Label', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .label, {{ELEMENT}} .label label',
			]
		);

		$this->register_style_options_element(
			'variations_desc_styles',
			[
				'title'    => esc_html__( 'Variation Description', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .woocommerce-variation-description p',
			]
		);

		$this->register_style_options_element(
			'variations_price_styles',
			[
				'title'    => esc_html__( 'Variation Price', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .woocommerce-variation-price,{{ELEMENT}} .woocommerce-variation-price .price > span.woocommerce-Price-amount',
			]
		);

		$this->register_style_options_element(
			'variations_dropdown_styles',
			[
				'title'    => esc_html__( 'Variation Dropdown', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .variations select',
			]
		);

		$this->register_style_options_element(
			'stock_styles',
			[
				'title'    => esc_html__( 'Stock Message', 'ziultimate' ),
				'selector' => '{{ELEMENT}} .stock.in-stock',
			]
		);
	}

	protected function can_render() {
		if( ! License::has_valid_license() )
			return;

		return true;
	}

	/**
	 * Render the Add To Cart button
	 */
	public function render( $options ) {
		$zpwooel = new WooCommerceElement();

		$is_loop = $options->get_value('is_loop', false);
		$product_id = 'current';
		
		if( empty( $is_loop ) || ! $is_loop ) {
			$product_id = $options->get_value('product_id', 'current');
		}

		$product = $zpwooel->get_woocommerce_product( $product_id, [ 'product', 'post' ] );

		if ( ! $product instanceof \WC_Product ) {
			$zpwooel->reset_woocommerce_product_query();
			return;
		}

		if( $product->get_type() != 'external' ) {
			$this->atc_text = $options->get_value('atc_text', __('Add To cart', 'woocommerce'));
			$this->sp_text = $options->get_value('sp_text', __('Select Options', 'woocommerce'));
			$this->rm_text = $options->get_value('rm_text', __('Read More', 'woocommerce'));
			$this->vp_text = $options->get_value('vp_text', __('View Products', 'woocommerce'));

			if( $this->atc_text && ! $is_loop ) {
				add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'zuwoo_single_add_to_cart_text' ), 999 );
			}

			add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'zuwoo_product_add_to_cart_text' ), 1000, 2 );
		}
		
		/**
		 * Creating the layout
		 */
		echo '<div class="woocommerce zu-atc-container product-type-'. $product->get_type() . '">';

		if( empty( $is_loop ) || ! $is_loop ) {

			$availability = $options->get_value('remove_stock_msg', true);
			$remove_price = $options->get_value('remove_price', true);

			if( ! $remove_price && $product->get_type() != 'external' )
				\woocommerce_template_single_price();

			if( $availability ) {
				add_filter( 'woocommerce_get_stock_html', '__return_null' );
			}

			\woocommerce_template_single_add_to_cart();

			if( $availability ) {
				remove_filter( 'woocommerce_get_stock_html', '__return_null' );
			}

		} else {
			if( $product->get_type() == 'simple' && $product->is_purchasable() ) {
				do_action( 'woocommerce_before_add_to_cart_form' );

				echo '<form class="cart">'; 
				
				do_action( 'woocommerce_before_add_to_cart_button' );
				//do_action( 'woocommerce_before_add_to_cart_quantity' );

				echo '<div class="quantity" data-type="' . get_theme_mod('quantity_type', 'type-1') . '">';

					//do_action( 'woocommerce_before_quantity_input_field' );

					echo '<input 
						type="number" 
						id="' . uniqid( 'quantity_' ) . '" 
						class="'.  esc_attr( join( ' ', (array) apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $product ) ) ) . '" 
						step="'. esc_attr( apply_filters( 'woocommerce_quantity_input_step', 1, $product ) ) .'" 
						min="'. esc_attr( apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ) ) . '" 
						max="" 
						name="quantity" 
						value="1" 
						title="Qty" 
						size="4" 
						placeholder="'. esc_attr( apply_filters( 'woocommerce_quantity_input_placeholder', '', $product ) ) .'" 
						inputmode="numeric" 
						onkeyup="JavaScript: ATCUpdateQty(jQuery(this));" 
						onchange="JavaScript: ATCUpdateQty(jQuery(this));">';

					do_action( 'woocommerce_after_quantity_input_field' );

				echo '</div>';

				//do_action( 'woocommerce_after_add_to_cart_quantity' );
			}

			\woocommerce_template_loop_add_to_cart();

			if( $product->get_type() == 'simple' && $product->is_purchasable() ) {
				do_action( 'woocommerce_after_add_to_cart_button' );
				echo '</form>';
				do_action( 'woocommerce_after_add_to_cart_form' );
			}
		}

		echo '</div>';

		$zpwooel->reset_woocommerce_product_query();

		remove_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'zuwoo_single_add_to_cart_text' ), 999 );
		remove_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'zuwoo_product_add_to_cart_text' ), 1000, 2 );
	}

	function zuwoo_single_add_to_cart_text( $text ) {

		if( $this->atc_text )
			$text = $this->atc_text;

		return $text; 
	}

	public function zuwoo_product_add_to_cart_text( $text, $obj ) {

		if( $obj->get_type() == 'simple' && $this->atc_text ) {
			$text = $obj->is_purchasable() && $obj->is_in_stock() ? $this->atc_text : $this->rm_text;
		}

		if( $obj->get_type() == 'variable' && $this->sp_text ) {
			$text = $obj->is_purchasable() ? $this->sp_text : $this->rm_text;
		}

		if( $obj->get_type() == 'grouped' && $this->bp_text ) {
			$text = $this->bp_text;
		}

		/*if( $this->btn_icon_pos == 'left' && $this->btn_icon && $obj->get_type() == 'simple' ) {
			$text = '&times; ' . $text;
		}

		if( $this->btn_icon_pos == 'right' && $this->btn_icon && $obj->get_type() == 'simple' ) {
			$text .= ' &times;';
		}*/
	
		return $text;
	}

	public function server_render( $config ) {
		// Load template actions for frontend since they only load in frontend and wp_ajax actions
		// @see WooCommerce::includes()
		if ( function_exists( 'WC' ) ) {
			\WC()->frontend_includes();
		}

		parent::server_render( $config );
	}
}