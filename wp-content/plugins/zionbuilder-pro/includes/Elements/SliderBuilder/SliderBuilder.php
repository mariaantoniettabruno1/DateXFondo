<?php

namespace ZionBuilderPro\Elements\SliderBuilder;

use ZionBuilder\Elements\Element;
use ZionBuilderPro\Utils;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class Image
 *
 * @package ZionBuilder\Elements
 */
class SliderBuilder extends Element {

	/**
	 * Get type
	 *
	 * Returns the unique id for the element
	 *
	 * @return string The element id/type
	 */
	public function get_type() {
		return 'slider_builder';
	}

	/**
	 * Is wrapper
	 *
	 * Returns true if the element can contain other elements ( f.e. section, column )
	 *
	 * @return boolean The element icon
	 */
	public function is_wrapper() {
		return true;
	}

	public function get_sortable_content_orientation() {
		return 'horizontal';
	}

	/**
	 * Get name
	 *
	 * Returns the name for the element
	 *
	 * @return string The element name
	 */
	public function get_name() {
		return __( 'Slider Builder', 'zionbuilder' );
	}

	/**
	 * Get Category
	 *
	 * Will return the element category
	 *
	 * @return string
	 */
	public function get_category() {
		return 'media';
	}

	/**
	 * Get keywords
	 *
	 * Returns the keywords for this element
	 *
	 * @return array<string> The list of element keywords
	 */
	public function get_keywords() {
		return [ 'image', 'media', 'carousell', 'slider', 'picture', 'transition', 'slides', 'gallery', 'portfolio', 'photo', 'sld' ];
	}

	/**
	 * Registers the element options
	 *
	 * @param \ZionBuilder\Options\Options $options The Options instance
	 *
	 * @return void
	 */
	public function options( $options ) {
		$options->add_option(
			'items',
			[
				'type'         => 'child_adder',
				'title'        => __( 'Slides', 'zionbuilder' ),
				'child_type'   => 'slider_builder_slide',
				'min'          => 1,
				'add_template' => [
					'element_type' => 'slider_builder_slide',
				],
				'default'      => [
					[
						'element_type' => 'slider_builder_slide',
					],
					[
						'element_type' => 'slider_builder_slide',
					],
				],
			]
		);

		$options->add_option(
			'arrows',
			[
				'type'    => 'checkbox_switch',
				'default' => true,
				'title'   => esc_html__( 'Show arrows', 'zionbuilder' ),
				'layout'  => 'inline',
			]
		);

		$options->add_option(
			'dots',
			[
				'type'    => 'checkbox_switch',
				'default' => false,
				'title'   => esc_html__( 'Show dots', 'zionbuilder' ),
				'layout'  => 'inline',
			]
		);

		$options->add_option(
			'infinite',
			[
				'type'    => 'checkbox_switch',
				'default' => true,
				'title'   => esc_html__( 'Infinite', 'zionbuilder' ),
				'layout'  => 'inline',
			]
		);

		$options->add_option(
			'autoplay',
			[
				'type'    => 'checkbox_switch',
				'default' => true,
				'title'   => esc_html__( 'Autoplay', 'zionbuilder' ),
				'layout'  => 'inline',
			]
		);

		$options->add_option(
			'slides_to_show',
			[
				'type'    => 'number',
				'title'   => __( 'Slides to show', 'zionbuilder' ),
				'min'     => 1,
				'max'     => 15,
				'default' => 1,
				'layout'  => 'inline',
				'responsive_options' => true
			]
		);

		$options->add_option(
			'slides_to_scroll',
			[
				'type'    => 'number',
				'title'   => __( 'Slides to scroll', 'zionbuilder' ),
				'min'     => 1,
				'max'     => 5,
				'default' => 1,
				'layout'  => 'inline',
			]
		);

		$options->add_option(
			'autoplay_delay',
			[
				'type'    => 'number',
				'title'   => __( 'Autoplay speed', 'zionbuilder' ),
				'min'     => 1,
				'max'     => 15000,
				'default' => 3000,
				'layout'  => 'inline',
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
		wp_enqueue_script( 'swiper' );

		// Using helper methods will go through caching policy
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/SliderBuilder/editor.js' ) );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/SliderBuilder/frontend.js' ) );
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
		$this->enqueue_editor_style( Utils::get_file_url( 'dist/css/elements/SliderBuilder/editor.css' ) );
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/SliderBuilder/frontend.css' ) );
		wp_enqueue_style( 'swiper' );
	}

	/**
	 * Sets wrapper css classes
	 *
	 * @param \ZionBuilder\Options\Options $options
	 *
	 * @return void
	 */
	public function before_render( $options ) {
		$autoplay = $options->get_value( 'autoplay' );

		$config   = [
			'arrows'     => $options->get_value( 'arrows' ),
			'pagination' => $options->get_value( 'dots' ),
			'slides_to_show' => $options->get_value( 'slides_to_show' ),
			'rawConfig'  => [
				'loop'           => $options->get_value( 'infinite' ),
				'slidesPerGroup' => $options->get_value( 'slides_to_scroll' ),
				'autoplay'       => $autoplay
			],
		];

		if ( $autoplay ) {
			$config['rawConfig']['autoplay'] = [
				'delay' => $options->get_value( 'autoplay_delay' ),
			];
		}

		$this->render_attributes->add( 'wrapper', 'data-zion-slider-config', wp_json_encode( $config ) );
		$this->render_attributes->add( 'wrapper', 'class', 'swiper-container' );
	}

	/**
	 * Renders the element based on options
	 *
	 * @param \ZionBuilder\Options\Options $options
	 *
	 * @return void
	 */
	public function render( $options ) {
		$pagination = $options->get_value( 'dots' );
		$arrows     = $options->get_value( 'arrows' ); ?>
		<div class="swiper-wrapper">
			<?php
			$this->render_children();
			?>
		</div>

		<!-- Add Pagination -->
		<?php if ( $pagination ) : ?>
			<div class="swiper-pagination"></div>
		<?php endif; ?>

		<!-- Arrows -->
		<?php if ( $arrows ) : ?>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		<?php endif; ?>
		<?php
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
			'slide_styles',
			[
				'title'    => esc_html__( 'Slide styles', 'zionbuilder' ),
				'selector' => '{{ELEMENT}} .swiper-slide',
			]
		);

		$this->register_style_options_element(
			'slider_nav_prev',
			[
				'title'    => esc_html__( 'Previous button styles', 'zionbuilder' ),
				'selector' => '{{ELEMENT}} .swiper-button-prev',
			]
		);

		$this->register_style_options_element(
			'slider_nav_next',
			[
				'title'    => esc_html__( 'Next button styles', 'zionbuilder' ),
				'selector' => '{{ELEMENT}} .swiper-button-next',
			]
		);

		$this->register_style_options_element(
			'slider_pagination_wrapper',
			[
				'title'    => esc_html__( 'Pagination wrapper styles', 'zionbuilder' ),
				'selector' => '{{ELEMENT}} .swiper-pagination',
			]
		);

		$this->register_style_options_element(
			'slider_pagination_dot',
			[
				'title'    => esc_html__( 'Pagination bullet styles', 'zionbuilder' ),
				'selector' => '{{ELEMENT}} .swiper-pagination-bullet',
			]
		);
	}

}
