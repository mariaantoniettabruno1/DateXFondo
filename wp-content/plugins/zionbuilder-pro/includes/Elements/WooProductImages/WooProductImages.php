<?php

namespace ZionBuilderPro\Elements\WooProductImages;

use ZionBuilderPro\Elements\WooCommerceElement;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class WooProductImages
 *
 * @package ZionBuilderPro\Elements
 */
class WooProductImages extends WooCommerceElement {
	/**
	 * Get type
	 *
	 * Returns the unique id for the element
	 *
	 * @return string The element id/type
	 */
	public function get_type() {
		return 'woo-product-images';
	}

	/**
	 * Get name
	 *
	 * Returns the name for the element
	 *
	 * @return string The element name
	 */
	public function get_name() {
		return __( 'Woo product images', 'zionbuilder-pro' );
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
	 * Get keywords
	 *
	 * Returns the keywords for this element
	 *
	 * @return array The list of element keywords
	 */
	public function get_keywords() {
		return [ 'image', 'woocommerce' ];
	}


	/**
	 * Get Element Icon
	 *
	 * Returns the icon used in add elements panel for this element
	 *
	 * @return string The element icon
	 */
	public function get_element_icon() {
		return 'element-woo-product-images';
	}

	public function options( $options ) {
		$options->add_option(
			'show_sale',
			[
				'type'    => 'custom_selector',
				'title'   => esc_html__( 'Show Sale Flash', 'zionbuilder-pro' ),
				'default' => 'yes',
				'options' => [
					[
						'name' => esc_html__( 'Yes', 'zionbuilder-pro' ),
						'id'   => 'yes',
					],
					[
						'name' => esc_html__( 'No', 'zionbuilder-pro' ),
						'id'   => 'no',
					],
				],
			]
		);
	}

	/**
	 * Enqueue element scripts for both frontend and editor
	 *
	 * If you want to use the ZionBuilder cache system you must use
	 * the enqueue_editor_script(), enqueue_element_script() functions
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		if ( ! is_singular( 'product' ) ) {
			wp_enqueue_script( 'woocommerce' );

			if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
				wp_enqueue_script( 'zoom' );
			}

			if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
				wp_enqueue_script( 'flexslider' );
			}

			if ( current_theme_supports( 'wc-product-gallery-lightbox' ) ) {
				wp_enqueue_script( 'photoswipe-ui-default' );
				wp_enqueue_style( 'photoswipe-default-skin' );
				add_action( 'wp_footer', 'woocommerce_photoswipe' );
			}

			wp_enqueue_script( 'wc-single-product' );

			wp_enqueue_style( 'photoswipe' );
			wp_enqueue_style( 'photoswipe-default-skin' );
			wp_enqueue_style( 'photoswipe-default-skin' );
			wp_enqueue_style( 'woocommerce_prettyPhoto_css' );
		}
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
		$show_sale_flash = $options->get_value( 'show_sale', 'yes' ) === 'yes';

		$product = $this->get_woocommerce_product(
			null,
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

		// sale flash
		if ( $show_sale_flash ) {
			wc_get_template( 'loop/sale-flash.php' );
		}

		// Render the ratings
		\wc_get_template( 'single-product/product-image.php' );

		if ( defined( 'REST_REQUEST' ) && defined( 'REST_REQUEST' ) ) {
			?>
			<script>
				jQuery( '.woocommerce-product-gallery' ).each( function() {
					jQuery( this ).wc_product_gallery();
				} );
			</script>
			<?php
		}

		$this->reset_woocommerce_product_query();
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'zoom_icon',
			[
				'title'                   => esc_html__( 'Zoom icon style', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .woocommerce-product-gallery__trigger',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'thumbnails',
			[
				'title'                   => esc_html__( 'Thumbnails style', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .flex-control-thumbs > li',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'sale_badge',
			[
				'title'                   => esc_html__( 'Sale badge style', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .onsale',
				'allow_custom_attributes' => false,
				'allow_class_assignments' => false,
			]
		);
	}
}
