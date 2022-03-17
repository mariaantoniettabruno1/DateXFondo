<?php

namespace ZionBuilderPro\Elements\Countdown;

use ZionBuilder\Elements\Element;
use ZionBuilderPro\Utils;
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class Countdown
 *
 * @package ZionBuilderPro\Elements
 */
class Countdown extends Element {
	/**
	 * Get type
	 *
	 * Returns the unique id for the element
	 *
	 * @return string The element id/type
	 */
	public function get_type() {
		return 'countdown';
	}

	/**
	 * Get name
	 *
	 * Returns the name for the element
	 *
	 * @return string The element name
	 */
	public function get_name() {
		return __( 'Countdown', 'zionbuilder-pro' );
	}

	/**
	 * Get keywords
	 *
	 * Returns the keywords for this element
	 *
	 * @return array The list of element keywords
	 */
	public function get_keywords() {
		return [ 'timer', 'counter', 'end' ];
	}

	/**
	 * Get Category
	 *
	 * Will return the element category
	 *
	 * @return string
	 */
	public function get_category() {
		return 'pro';
	}

	/**
	 * Get Element Icon
	 *
	 * Returns the icon used in add elements panel for this element
	 *
	 * @return string The element icon
	 */
	public function get_element_icon() {
		return 'element-countdown';
	}

	public function options( $options ) {
		$options->add_option(
			'date',
			[
				'type'          => 'date_input',
				'title'         => esc_html__( 'Date', 'zionbuilder-pro' ),
				'description'   => esc_html__( 'Choose the date to appear', 'zionbuilder-pro' ),
				'default'       => date( 'Y-m-d' ),
				'past-disabled' => true,

			]
		);
		$options->add_option(
			'hour',
			[
				'type'        => 'slider',
				'title'       => esc_html__( 'Hour', 'zionbuilder-pro' ),
				'description' => esc_html__( 'Choose the hour to appear', 'zionbuilder-pro' ),
				'default'     => 0,
				'min'         => 0,
				'max'         => 23,
			]
		);
		$options->add_option(
			'minutes',
			[
				'type'        => 'slider',
				'title'       => esc_html__( 'Minutes', 'zionbuilder-pro' ),
				'description' => esc_html__( 'Choose the minutes to appear', 'zionbuilder-pro' ),
				'default'     => 0,
				'min'         => 0,
				'max'         => 59,
			]
		);

		$options->add_option(
			'block_type',
			[
				'type'             => 'select',
				'default'          => 'separate',
				'title'            => esc_html__( 'Block type', 'zionbuilder-pro' ),
				'description'      => esc_html__( 'Choose if you want to display separate blocks or a single block', 'zionbuilder-pro' ),
				'options'          => [
					[
						'name' => esc_html__( 'Single block', 'zionbuilder-pro' ),
						'id'   => 'single',
					],
					[
						'name' => esc_html__( 'Separate blocks', 'zionbuilder-pro' ),
						'id'   => 'separate',
					],
				],
				'render_attribute' => [
					[
						'tag_id'    => 'wrapper',
						'attribute' => 'class',
						'value'     => 'zb-el-countdown-type--{{VALUE}}',
					],
				],
			]
		);

		$options->add_option(
			'separator_type',
			[
				'type'             => 'select',
				'default'          => 'none',
				'title'            => esc_html__( 'Separator type', 'zionbuilder-pro' ),
				'description'      => esc_html__( 'Choose how you want the separator to be displayed', 'zionbuilder-pro' ),
				'options'          => [
					[
						'name' => esc_html__( 'Colon', 'zionbuilder-pro' ),
						'id'   => 'colon',
					],
					[
						'name' => esc_html__( 'Hyphen', 'zionbuilder-pro' ),
						'id'   => 'hyphen',
					],
					[
						'name' => esc_html__( 'Slash', 'zionbuilder-pro' ),
						'id'   => 'slash',
					],
					[
						'name' => esc_html__( 'None', 'zionbuilder-pro' ),
						'id'   => 'none',
					],
				],
				'render_attribute' => [
					[
						'tag_id'    => 'wrapper',
						'attribute' => 'class',
						'value'     => 'zb-el-countdown-separator-type--{{VALUE}}',
					],
				],
			]
		);

		$options->add_option(
			'label_inside',
			[
				'type'             => 'checkbox_switch',
				'default'          => false,
				'layout'           => 'inline',
				'title'            => esc_html__( 'Add label outside block', 'zionbuilder-pro' ),
				'render_attribute' => [
					[
						'tag_id'    => 'wrapper',
						'attribute' => 'class',
						'value'     => 'zb-el-countdown-has-outsideLabel',
					],
				],
			]
		);

		$options->add_option(
			'days_text',
			[
				'type'        => 'text',
				'title'       => esc_html__( 'Days text', 'zionbuilder-pro' ),
				'description' => esc_html__( 'Set the desired text to appear instead of days.', 'zionbuilder-pro' ),
				'placeholder' => esc_html__( 'day,days', 'zionbuilder-pro' ),
				'dynamic'     => [
					'enabled' => true,
				],
			]
		);

		$options->add_option(
			'hours_text',
			[
				'type'        => 'text',
				'title'       => esc_html__( 'Hours text', 'zionbuilder-pro' ),
				'description' => esc_html__( 'Set the desired text to appear instead of hours.', 'zionbuilder-pro' ),
				'placeholder' => esc_html__( 'hour,hours', 'zionbuilder-pro' ),
				'dynamic'     => [
					'enabled' => true,
				],
			]
		);

		$options->add_option(
			'minutes_text',
			[
				'type'        => 'text',
				'title'       => esc_html__( 'Minutes text', 'zionbuilder-pro' ),
				'description' => esc_html__( 'Set the desired text to appear instead of minutes.', 'zionbuilder-pro' ),
				'placeholder' => esc_html__( 'minute,minutes', 'zionbuilder-pro' ),
				'dynamic'     => [
					'enabled' => true,
				],
			]
		);

		$options->add_option(
			'seconds_text',
			[
				'type'        => 'text',
				'title'       => esc_html__( 'Seconds text', 'zionbuilder-pro' ),
				'description' => esc_html__( 'Set the desired text to appear instead of seconds.', 'zionbuilder-pro' ),
				'placeholder' => esc_html__( 'second, Seconds', 'zionbuilder-pro' ),
				'dynamic'     => [
					'enabled' => true,
				],
			]
		);

		$options->add_option(
			'time_color',
			[
				'type'      => 'colorpicker',
				'title'     => esc_html__( 'Time Color', 'zionbuilder-pro' ),
				'layout'    => 'inline',
				'default'   => '#858585',
				'css_style' => [
					[
						'selector' => '{{ELEMENT}} .zb-el-countdownUnit__value',
						'value'    => 'color: {{VALUE}}',
					],

				],
			]
		);

		$options->add_option(
			'separator_color',
			[
				'type'      => 'colorpicker',
				'title'     => esc_html__( 'Separator Color', 'zionbuilder-pro' ),
				'layout'    => 'inline',
				'default'   => '#cacaca',
				'css_style' => [
					[
						'selector' => '{{ELEMENT}} .zb-el-countdownUnit:not(:last-child)::after',
						'value'    => 'color: {{VALUE}}',
					],
					[
						'selector' => '{{ELEMENT}} .zb-el-countdownUnit:not(:last-child) .zb-el-countdownUnit__value::after',
						'value'    => 'color: {{VALUE}}',
					],
				],
			]
		);
	}

	public function on_register_styles() {
		$this->register_style_options_element(
			'inner_wrapper_styles',
			[
				'title'                   => esc_html__( 'Inner Wrapper', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .zb-el-countdown__wrapper',
				'allow_class_assignments' => false,
			]
		);

		$this->register_style_options_element(
			'single_block_styles',
			[
				'title'                   => esc_html__( 'Single Block', 'zionbuilder-pro' ),
				'selector'                => '{{ELEMENT}} .zb-el-countdownUnit',
				'allow_class_assignments' => false,
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
		wp_enqueue_script( 'countdown-jquery' );

		// Using helper methods will go through caching policy
		$this->enqueue_editor_script( Utils::get_file_url( 'dist/js/elements/Countdown/editor.js' ) );
		$this->enqueue_element_script( Utils::get_file_url( 'dist/js/elements/Countdown/frontend.js' ) );
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
		// Using helper methods will go through caching policy
		$this->enqueue_element_style( Utils::get_file_url( 'dist/css/elements/Countdown/frontend.css' ) );
	}

	public function before_render( $options ) {
		$date       = $options->get_value( 'date' );
		$hour       = $options->get_value( 'hour' );
		$minutes    = $options->get_value( 'minutes' );
		$final_date = wp_sprintf( '%s %s:%s:00', $date, $hour, $minutes );

		$config = [
			'finalDate' => $final_date,
		];

		if ( $options->get_value( 'days_text' ) ) {
			$config['daysString'] = $options->get_value( 'days_text' );
		}
		if ( $options->get_value( 'hours_text' ) ) {
			$config['hoursString'] = $options->get_value( 'hours_text' );
		}
		if ( $options->get_value( 'minutes_text' ) ) {
			$config['minutesString'] = $options->get_value( 'minutes_text' );
		}
		if ( $options->get_value( 'seconds_text' ) ) {
			$config['secondsString'] = $options->get_value( 'seconds_text' );
		}

		$this->render_attributes->add( 'wrapper', 'data-zion-countdown-config', wp_json_encode( $config ) );
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
		// no render function as the HTML is added by JS
	}
}
