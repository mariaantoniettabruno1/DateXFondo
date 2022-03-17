<?php
namespace ZionBuilderPro\DynamicContent\Fields;

use ZionBuilderPro\DynamicContent\BaseField;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class PostExcerpt
 * @package ZionBuilderPro\DynamicContent\Fields
 */
class PostExcerpt extends BaseField
{
	public function get_category() {
		return self::CATEGORY_TEXT;
	}

	public function get_group() {
		return 'post';
	}

	public function get_id() {
		return 'post-excerpt';
	}

	public function get_name() {
		return esc_html__( 'Post excerpt', 'zionbuilder-pro' );
	}

	/**
	 * Get Content
	 *
	 * Render the current post title
	 *
	 * @param mixed $config
	 */
	public function render( $config ) {
		echo wp_kses_post( get_the_excerpt() );
	}

	/**
	 * Return the data for this field used in preview/editor
	 */
	public function get_data() {
		return wp_kses_post( get_the_excerpt() );
	}
}
