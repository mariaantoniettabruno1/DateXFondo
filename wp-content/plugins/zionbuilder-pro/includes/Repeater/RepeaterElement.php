<?php

namespace ZionBuilderPro\Repeater;

use ZionBuilderPro\Repeater;
use ZionBuilder\Plugin as FreePlugin;
use ZionBuilder\Elements\Style;
use ZionBuilderPro\Plugin;
use ZionBuilder\PageAssets;

class RepeaterElement {
	private $element = null;
	private $repeater_provider = null;

	public function __construct( $element ) {
		$this->element = $element;
	}

	public function render_element( $extra_data ) {
		$element_instance = $this->element;

		// If this is a repeater provider, set the query
		if ( Repeater::is_repeater_provider( $element_instance ) ) {
			$provider_config = Repeater::get_repeater_provider_config( $element_instance );
			Plugin::$instance->repeater->set_active_provider( $provider_config );
		}

		// Check to see if this is the main repeater consumer
		if ( Repeater::is_repeater_consumer( $element_instance ) ) {
			$active_provider = Plugin::$instance->repeater->get_active_provider();
			if ( ! $active_provider ) {
				return;
			}

			// Set current loop
			$consumer_config = Repeater::get_repeater_consumer_config( $element_instance );
			$active_provider->start_loop( $consumer_config );

			$index = 0;

			while ( $active_provider->have_items() ) {
				$active_provider->the_item();

				$cloned_element = $this->setup_repeated_element( $element_instance, $index );
				$cloned_element->do_element_render( $extra_data );

				$active_provider->next();
				$active_provider->reset_item();
				$index++;
			}

			// Reset consumer
			$active_provider->stop_loop();
		} else {
			// This can only be a repeater provider. We just need to set the provider data and render normally
			$this->element->do_element_render( $extra_data );
		}

		// If this is a repeater provider, reset the query
		if ( Repeater::is_repeater_provider( $element_instance ) ) {
			Plugin::$instance->repeater->reset_active_provider();
		}
	}

	/**
	 * Change all repeated element instances and replace HTML ids with css clases
	 *
	 * @param Element $element_instance
	 * @param integer $index
	 *
	 * @return Element
	 */
	private function setup_repeated_element( $element_instance, $index ) {
		$element_css_id = $element_instance->get_element_css_id();
		$css_class = sprintf( '%s_%s', $element_css_id, $index );

		// Create a clone
		$element_data = $element_instance->data;
		$element_data['uid'] = $css_class;
		$cloned_element_instance = FreePlugin::instance()->renderer->register_element_instance( $element_data );

		$clone_children = $cloned_element_instance->get_children();
		if ( is_array( $clone_children ) ) {
			foreach ( $clone_children as $child_index => $child_element ) {
				$child_element_instance = FreePlugin::instance()->renderer->get_element_instance( $child_element['uid'] );
				$cloned_child = $this->setup_repeated_element( $child_element_instance, $index );
				$cloned_element_instance->content[$child_index] = $cloned_child->data;
			}
		}

		// Set CSS class
		$cloned_element_instance->render_attributes->add( 'wrapper', 'class', $element_css_id );
		$cloned_element_instance->render_attributes->add( 'wrapper', 'class', $css_class );
		$cloned_element_instance->render_attributes->add( 'wrapper', 'id', $css_class );

		/**
		 * Generate the CSS for element
		 *
		 * 1. The first item generates two styles. One for all the children and the second one containing only dynamic data
		 * 2. For all the clones, only generate the css for options that have dynamic data
		 */
		if ( FreePlugin::instance()->cache->should_generate_css() ) {
			// CSS style generation
			if ( $index === 0 ) {
				$cloned_element_instance->element_css_selector = '.zb .' . $element_css_id;

				// Remove the dynamic data from style options
				$element_style_options = $cloned_element_instance->options->get_value( '_styles', [] );
				$styles_without_dynamic_data = $this->remove_dynamic_values( $element_style_options );
				$cloned_element_instance->options->set_value( '_styles', $styles_without_dynamic_data );

				// Generate css for element index for dynamic data values
				$element_index_css_class = '.zb .' . $css_class;
				$registered_styles = $cloned_element_instance->get_style_elements_for_editor();
				$only_dynamic_styles = $this->get_only_dynamic_values( $element_style_options );
				$dynamic_data_styles = Plugin::instance()->dynamic_content_manager->apply_dynamic_content( $only_dynamic_styles );

				if ( ! empty( $registered_styles ) && is_array( $registered_styles ) ) {
					foreach ( $registered_styles as $id => $style_config ) {
						if ( ! empty( $dynamic_data_styles[$id] ) ) {
							$css_selector = $element_index_css_class;
							$css_selector = str_replace( '{{ELEMENT}}', $css_selector, $style_config['selector'] );

							PageAssets::add_active_area_raw_css( Style::get_css_from_selector( [ $css_selector ], $dynamic_data_styles[$id] ) );
						}
					}
				}

			} else {
				$cloned_element_instance->element_css_selector = '.zb .' . $css_class;

				// remove non dynamic options from style options. This will generate less duplicated CSS
				$element_style_options = $cloned_element_instance->options->get_value( '_styles', [] );
				$only_dynamic_styles = $this->get_only_dynamic_values( $element_style_options );
				$cloned_element_instance->options->set_value( '_styles', $only_dynamic_styles );
			}
		}


		return $cloned_element_instance;
	}

	/**
	 * Returns only the dynamic data values
	 *
	 * @param array $model
	 *
	 * @return array
	 */
	public function get_only_dynamic_values($model) {
		$model_to_return = [];
		if (is_array($model)) {
			foreach ($model as $key => $value) {
				if ($key === '__dynamic_content__') {
					$model_to_return[$key] = $value;
				} else {
					$dynamic_values = $this->get_only_dynamic_values($value);
					if (null !== $dynamic_values) {
						$model_to_return[$key] = $dynamic_values;
					}
				}
			}
		}

		if (count($model_to_return) > 0) {
			return $model_to_return;
		}

		return null;
	}

	/**
	 * Removes dynamic data values
	 *
	 * @param array $model
	 *
	 * @return array
	 */
	public function remove_dynamic_values( $model ) {
		$model_to_return = [];
		if (is_array($model)) {
			foreach ($model as $key => $value) {
				if ($key === '__dynamic_content__') {
					continue;
				} else {
					if ( is_array( $value ) ) {
						$model_to_return[$key] = $this->remove_dynamic_values($value);
					} else {
						$model_to_return[$key] = $value;
					}
				}
			}
		}

		return $model_to_return;
	}
}