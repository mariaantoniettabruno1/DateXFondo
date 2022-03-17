<?php
namespace ZiUltimate\WooElements\YithCompare;

use ZiUltimate\UltimateElements;
use ZiUltimate\Utils;
use ZiUltimate\Admin\License;
use ZionBuilder\Options\BaseSchema;
use ZionBuilderPro\Elements\WooCommerceElement;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! class_exists( 'YITH_Woocompare_Frontend' ) )
	return false;
/**
 * Class YithCompare
 *
 * @package ZiUltimate\WooElements
 */
class YithCompare extends UltimateElements {

    public function get_type() {
		return 'zu_compare';
	}

	public function get_name() {
		return __( 'Yith Compare', 'ziultimate' );
	}

	public function get_keywords() {
		return [ 'compare', 'compare button', 'yith' ];
	}

	/*public function get_label() {
		return [
			'text'  => $this->get_label_text(),
			'color' => $this->get_label_color(),
		];
	}*/

	public function get_category() {
		return $this->zuwoo_elements_category();
	}

	public function options( $options ) 
	{
		$options->add_option(
			'product_id',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Product ID', 'ziultimate'),
				'description' 	=> esc_html__('Leave empty if putting on single product page or repeater.', 'zilultimate'),
				'dynamic' 		=> [
					'enabled' => true
				]
			]
		);

		$options->add_option(
			'button_layout',
			[
				'type' 			=> 'select',
				'title' 		=> esc_html__('Template', 'ziultimate'),
				'default' 		=> 'link_button',
				'options' 		=> [
					[
						'name' 		=> esc_html__('Link or Button'),
						'id' 		=> 'link_button'
					],
					[
						'name' 		=> esc_html__('Icon with Tooltip'),
						'id' 		=> 'icon'
					]
				]
			]
		);

		$options->add_option(
			'button_text',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Text', 'ziultimate'),
				'default' 		=> esc_html__('Compare', 'woocommerce'),
				'dynamic' 		=> [
					'enabled' => true
				],
				'dependency' 	=> [
					[
						'option' 	=> 'button_layout',
						'value' 	=> ['link_button']
					]
				]
			]
		);

		$options->add_option(
			'link_button',
			[
				'type' 			=> 'custom_selector',
				'title' 		=> esc_html__('Link or Button', 'ziultimate'),
				'default' 		=> 'button',
				'options' 		=> [
					[
						'name' 		=> esc_html__('Button'),
						'id' 		=> 'button'
					],
					[
						'name' 		=> esc_html__('Link'),
						'id' 		=> 'link'
					]
				]
			]
		);

		$options->add_option(
			'exclude_products',
			[
				'type' 			=> 'text',
				'title' 		=> esc_html__('Disable button for these products', 'ziultimate'),
				'placeholder' 	=> esc_html__('Enter product ids with comma', 'ziultimate'),
				'dynamic' 	=> [
					'enabled' 	=> true
				],
			]
		);

		$options->add_option(
			'exclude_product_types',
			[
				'type' 			=> 'select',
				'title' 		=> esc_html__('Disable button for these product types', 'ziultimate'),
				'placeholder' 	=> esc_html__('You can select multiple product types', 'ziultimate'),
				'multiple' 		=> true,
				'options' 		=> [
					[
						'name' 	=> esc_html__('Grouped'),
						'id' 	=> 'grouped'
					],
					[
						'name' 	=> esc_html__('Simple'),
						'id' 	=> 'simple'
					],
					[
						'name' 	=> esc_html__('Variable'),
						'id' 	=> 'variable'
					],
					[
						'name' 	=> esc_html__('External'),
						'id' 	=> 'external'
					]
				],
			]
		);

		$options->add_option(
			'button_width',
			[
				'type' 					=> 'dynamic_slider',
				'default_step' 			=> 1,
				'default_shift_step' 	=> 5,
				'title' 				=> esc_html__( 'Width' ),
				'show_responsive_buttons' => true,
				'options' 				=> [
					[
						'min'        => 0,
						'max'        => 900,
						'step'       => 1,
						'shift_step' => 25,
						'unit'       => 'px',
					],
					[
						'min'        => 0,
						'max'        => 100,
						'step'       => 1,
						'shift_step' => 5,
						'unit'       => '%',
					],
					[
						'min'        => 0,
						'max'        => 100,
						'step'       => 1,
						'shift_step' => 5,
						'unit'       => 'vw',
					],
					[
						'unit' => 'auto',
					],
				],
				'sync' 					=> '_styles.wrapper.styles.%%RESPONSIVE_DEVICE%%.default.width'
			]
		);


		$options->add_option(
			'button_bg',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Background' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.button_styles.styles.%%RESPONSIVE_DEVICE%%.default.background-color'
			]
		);

		$options->add_option(
			'button_hbg',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Hover Background' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.button_styles.styles.%%RESPONSIVE_DEVICE%%.:hover.background-color'
			]
		);

		$options->add_option(
			'text_color',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Text Color' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.button_styles.styles.%%RESPONSIVE_DEVICE%%.default.color'
			]
		);

		$options->add_option(
			'text_hcolor',
			[
				'type' 		=> 'colorpicker',
				'title' 	=> esc_html__( 'Text Hover Color' ),
				'width' 	=> 50,
				'sync' 		=> '_styles.button_styles.styles.%%RESPONSIVE_DEVICE%%.:hover.color'
			]
		);

		$options->add_option(
			'font-size',
			[
				'title' 		=> esc_html__( 'Font size', 'zionbuilder' ),
				'description' 	=> esc_html__( 'The font size option sets the size of the font in various units', 'zionbuilder' ),
				'type' 			=> 'number_unit',
				'min' 			=> 0,
				'width' 		=> 50,
				'units' 		=> BaseSchema::get_units(),
				'sync' 			=> '_styles.button_styles.styles.%%RESPONSIVE_DEVICE%%.default.font-size'
			]
		);

		$options->add_option(
			'text-transform',
			[
				'type' 			=> 'custom_selector',
				'title' 		=> esc_html__( 'Text Transform', 'zionbuilder' ),
				'columns' 		=> 3,
				'width' 		=> 50,
				'options' 		=> [
					[
						'id'   => 'uppercase',
						'icon' => 'uppercase',
						'name' => esc_html__( 'uppercase', 'zionbuilder' ),
					],
					[
						'id'   => 'lowercase',
						'icon' => 'lowercase',
						'name' => esc_html__( 'lowercase', 'zionbuilder' ),
					],
					[
						'id'   => 'capitalize',
						'icon' => 'capitalize',
						'name' => esc_html__( 'capitalize', 'zionbuilder' ),
					],
				],
				'sync' 			=> '_styles.wrapper.styles.%%RESPONSIVE_DEVICE%%.default.text-transform'
			]
		);

		/***************
		 * Icon Group
		 **************/
		$icon = $options->add_group(
			'icon_group',
			[
				'type' 			=> 'accordion_menu',
				'title' 		=> esc_html__( 'Icon Options', 'ziultimate' ),
			]
		);

		$icon->add_option(
			'icon',
			[
				'type' 			=> 'icon_library',
				'id' 			=> 'id',
				'title' 		=> __( 'Icon', 'zionbuilder' ),
				'description'	=> __( 'Choose an icon', 'zionbuilder' ),
			]
		);

		$icon->add_option(
			'icon_color',
			[
				'type' 			=> 'colorpicker',
				'title' 		=> esc_html__( 'Color' ),
				'width' 		=> 50,
				'sync' 			=> '_styles.icon_styles.styles.%%RESPONSIVE_DEVICE%%.default.color'
			]
		);

		$icon->add_option(
			'icon_hcolor',
			[
				'type' 			=> 'colorpicker',
				'title' 		=> esc_html__( 'Hover Color' ),
				'width' 		=> 50,
				'sync' 			=> '_styles.icon_styles.styles.%%RESPONSIVE_DEVICE%%.:hover.color',
				'css_style' 	=> [
					[
						'selector' 	=> "{{ELEMENT}} .compare-button:hover .zu-compare-button__icon",
						'value' 	=> 'color: {{VALUE}}'
					]
				]
			]
		);

		$icon->add_option(
			'icon_size',
			[
				'title' 		=> esc_html__( 'Size', 'zionbuilder' ),
				'type' 			=> 'number_unit',
				'width' 		=> 50,
				'units' 		=> BaseSchema::get_units(),
				'sync' 			=> '_styles.icon_styles.styles.%%RESPONSIVE_DEVICE%%.default.font-size',
				'show_responsive_buttons' => true
			]
		);

		$icon->add_option(
			'icon_pos',
			[
				'type' 			=> 'custom_selector',
				'title' 		=> esc_html__('Position', 'ziultimate'),
				'default' 		=> 'row',
				'options' 		=> [
					[
						'name' 		=> esc_html__('Left'),
						'id' 		=> 'row'
					],
					[
						'name' 		=> esc_html__('right'),
						'id' 		=> 'row-reverse'
					]
				],
				'css_style' 	=> [
					[
						'selector' 	=> "{{ELEMENT}} .compare",
						'value' 	=> 'flex-direction: {{VALUE}}'
					]
				]
			]
		);

		$icon->add_option(
			'icon_margin_left',
			[
				'title' 		=> esc_html__( 'Margin Left', 'zionbuilder' ),
				'type' 			=> 'number_unit',
				'width' 		=> 50,
				'units' 		=> BaseSchema::get_units(),
				'css_style' 	=> [
					[
						'selector' 	=> "{{ELEMENT}} .zu-compare-button__icon",
						'value' 	=> 'margin-left: {{VALUE}}'
					]
				]
			]
		);

		$icon->add_option(
			'icon_margin_right',
			[
				'title' 		=> esc_html__( 'Margin Right', 'zionbuilder' ),
				'type' 			=> 'number_unit',
				'width' 		=> 50,
				'units' 		=> BaseSchema::get_units(),
				'css_style' 	=> [
					[
						'selector' 	=> "{{ELEMENT}} .zu-compare-button__icon",
						'value' 	=> 'margin-right: {{VALUE}}'
					]
				]
			]
		);
	}

	/** 
	 * Checks if the element can render.
	 *
	 * @return boolean
	 */
	protected function can_render() {
		if( ! License::has_valid_license() )
			return false;

		global $product;

		$zpwooel 	= new WooCommerceElement();
		$product_id = $this->options->get_value('product_id', 'current');
		$product 	= $zpwooel->get_woocommerce_product( $product_id, [ 'product', 'post' ] );

		if ( ! $product instanceof \WC_Product ) {
			$zpwooel->reset_woocommerce_product_query();
			return false;
		}

		$exclude_products 		= $this->options->get_value('exclude_products', false);
		$exclude_product_types 	= $this->options->get_value('exclude_product_types', false);

		if( ! empty( $exclude_products ) ) {
			$exclude_products = explode( ",", $exclude_products );

			if( in_array( $product->get_id(), $exclude_products) )
				return false;
		}

		if( ! empty( $exclude_product_types ) ) {
			if( in_array( $product->get_type(), $exclude_product_types) )
				return false;
		}

		$zpwooel->reset_woocommerce_product_query();

		return true;
	}

	/**
	 * Loading the styles
	 * 
	 * @return void
	 */
	public function enqueue_styles() {
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/YithCompare/frontend.css' ) );
	}

	/**
	 * Get style elements
	 *
	 * Returns a list of elements/tags that for which you
	 * want to show style options
	 *
	 * @return void
	 */
	public function on_register_styles() {
		$this->register_style_options_element(
			'button_styles',
			[
				'title'      => esc_html__( 'Button styles', 'ziultimate' ),
				'selector'   => '{{ELEMENT}} .compare',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'icon_styles',
			[
				'title'      => esc_html__( 'Icon styles', 'ziultimate' ),
				'selector'   => '{{ELEMENT}} .zu-compare-button__icon',
				'render_tag' => 'icon',
			]
		);
	}

	public function before_render( $options )
	{
		$this->render_attributes->add( 'wrapper', 'class', 'woocommerce product compare-button');
	}

	public function render( $options ) 
	{
		$zpwooel 	= new WooCommerceElement();
		$product_id = $this->options->get_value('product_id', 'current');
		$product 	= $zpwooel->get_woocommerce_product( $product_id, [ 'product', 'post' ] );

		if ( ! $product instanceof \WC_Product ) {
			$zpwooel->reset_woocommerce_product_query();
			return false;
		}

		$icon_html 	= '';
		$icon 		= $options->get_value( 'icon', false );

		$combined_icon_attr   = $this->render_attributes->get_combined_attributes( 'icon_styles', [ 'class' => 'zu-compare-button__icon' ] );

		if ( ! empty( $icon ) ) {
			$this->attach_icon_attributes( 'icon', $icon );
			$icon_html = $this->get_render_tag(
				'span',
				'icon',
				'',
				$combined_icon_attr
			);
		}

		$button_text 	= $options->get_value( 'button_text', esc_html('Compare', 'woocommerce') );
		$button 		= $options->get_value( 'link_button', 'button' );
		$yithfrontend 	= new \YITH_Woocompare_Frontend();

		printf( 
			'<a href="%s" class="%s" data-product_id="%d" rel="nofollow">%s<span class="button-text">%s</span></a>', 
			$yithfrontend->add_product_url( $product->get_id() ), 
			'compare'. ( ( $button == 'button') ? ' button' : '' ), 
			$product->get_id(),
			$icon_html,
			$button_text,
		);

		$zpwooel->reset_woocommerce_product_query();
	}
}