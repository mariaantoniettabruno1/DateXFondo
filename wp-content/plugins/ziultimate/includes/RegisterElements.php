<?php
namespace ZiUltimate;

use ZiUltimate\Utils;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class RegisterElements {

	function __construct() {
		add_filter( 'zionbuilder/elements/categories', [ $this, 'add_elements_categories' ] );
		add_action( 'zionbuilder/elements_manager/register_elements', [ $this, 'register_elements' ] );
		add_action( 'zionbuilder/editor/before_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function add_elements_categories( $categories ) {
		$name = __( 'ZiUltimate', 'ziultimate' );
		$zuwl = get_option('zuwl');

		if( $zuwl ) {
			$name = ! empty( $zuwl['plugin_name'] ) ? esc_html( $zuwl['plugin_name'] ) : $name;
		}

		$zu_categories = [
			[
				'id'   => 'ziultimate',
				'name' => $name,
			]
		];

		return array_merge( $categories, $zu_categories );
	}

	public function register_elements( $elements_manager ) {
		$zu_elements = self::get_elements();
		$active_els = (array) get_option('ziultimate_active_els');

		foreach ( $active_els as $key => $element ) {
			if( empty( $element ) )
				return;

			if( is_array( $zu_elements[ $element ] ) ) {
				// Normalize class name
				$class_name = str_replace( '-', '_', $zu_elements[ $element ]['class'] );
				$class_name = __NAMESPACE__ . '\\Elements\\' . $class_name;
				$elements_manager->register_element( new $class_name() );
			}
		}
	}

	public static function get_elements() {
		$link = 'https://ziultimate.com/doc';
		$elements = [
			'burger' => [
				'name' 	=> __('Animated Burger', 'ziultimate'),
				'class' => 'AnimatedBurger\AnimatedBurger',
				'link' 	=> $link . '/animated-burger/'
			],
			'animheading' => [
				'name' 	=> __('Animated Heading', 'ziultimate'),
				'class' => 'AnimatedHeading\AnimatedHeading',
				'link' 	=> $link . '/animated-heading/'
			],
			'acrdmenu' => [
				'name' 	=> __('Accordion Menu', 'ziultimate'),
				'class' => 'AccordionMenu\AccordionMenu',
				'link' 	=> $link . '/accordion-menu/'
			],
			'backtotop' => [
				'name' 	=> __('Back To Top', 'ziultimate'),
				'class' => 'BackToTop\BackToTop',
				'link' 	=> $link . '/back-to-top/'
			],
			'cfstyler' => [
				'name' => __( 'Contact Form 7 Styler', 'ziultimate'),
				'class' => 'ContactForm7\ContactForm7',
				'link' 	=> $link . '/contact-form-7-styler/'
			],
			'tabs' => [
				'name' => __( 'Dynamic Tabs', 'ziultimate'),
				'class' => 'DynamicTabs\DynamicTabs',
				'link' 	=> $link . '/dynamic-tabs/'
			],
			'ffstyler' => [
				'name' 	=> __('Fluent Form Styler', 'ziultimate'),
				'class' => 'FluentForm\FluentForm',
				'link' 	=> $link . '/fluent-form-styler/'
			],
			'gfstyler' => [
				'name' 	=> __('Gravity Form Styler', 'ziultimate'),
				'class' => 'GravityForm\GravityForm',
				'link' 	=> $link . '/gravity-form-styler/'
			],
			'hgheading' => [
				'name' 	=> __('Highlighted Heading', 'ziultimate'),
				'class' => 'HighlightedHeading\HighlightedHeading',
				'link' 	=> $link . '/highlighted-heading/'
			],			
			'imgcomp' => [
				'name' 	=> __('Image Comparison', 'ziultimate'),
				'class' => 'ImageComparison\ImageComparison',
				'link' 	=> $link . '/image-comparison/'
			],
			'infscroll' => [
				'name' 	=> __('Infinite Scroll', 'ziultimate'),
				'class' => 'InfiniteScroll\InfiniteScroll',
				'link' 	=> $link . '/infinite-scroll/'
			],
			/*'lightbox' => [
				'name' 	=> __('Lightbox', 'ziultimate'),
				'class' => 'Lightbox\Lightbox',
				'link' 	=> $link . '/lightbox/'
			],*/
			'offcanvas' => [
				'name' 	=> __('Off Canvas', 'ziultimate'),
				'class' => 'OffCanvas\OffCanvas',
				'link' 	=> $link . '/off-canvas/'
			],
			'pfstyler' => [
				'name' 	=> __('Piotnet Forms Styler', 'ziultimate'),
				'class' => 'PiotnetForm\PiotnetForm',
				'link' 	=> $link . '/piotnet-forms-styler/'
			],
			'readingpgbar' => [
				'name' 	=> __('Reading Progress Bar', 'ziultimate'),
				'class' => 'ReadingProgressBar\ReadingProgressBar',
				'link' 	=> $link . '/reading-progress-bar/'
			],
			'readingtime' => [
				'name' 	=> __('Reading Time', 'ziultimate'),
				'class' => 'ReadingTime\ReadingTime',
				'link' 	=> $link . '/reading-time/'
			],
			'readmore' => [
				'name' 	=> __('Read More / Less', 'ziultimate'),
				'class' => 'ReadMore\ReadMore',
				'link' 	=> $link . '/read-more-less/'
			],
			'sldmenu' => [
				'name' 	=> __('Sliding Menu', 'ziultimate'),
				'class' => 'SlidingMenu\SlidingMenu',
				'link' 	=> $link . '/sliding-menu/'
			],
			'toc' => [
				'name' 	=> __('Table Of Contents', 'ziultimate'),
				'class' => 'TableOfContents\TableOfContents',
				'link' 	=> $link . '/table-of-contents/'
			],
			'wpforms' => [
				'name' 	=> __('WPForms Styler', 'ziultimate'),
				'class' => 'WPForms\WPForms',
				'link' 	=> $link . '/wpforms-styler/'
			],
		];

		return $elements;
	}

	public function enqueue_scripts() {
		wp_enqueue_style(
			'zuicons-editor-styles',
			Utils::get_file_url('assets/css/editor-global.css'),
			[],
			time(),
			'all'
		);
	}
}