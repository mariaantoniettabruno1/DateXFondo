<?php

namespace ZionBuilderPro\Repeater\Providers;

use ZionBuilderPro\Repeater\RepeaterProvider;

class RecentPosts extends RepeaterProvider {
	public static function get_id() {
		return 'recent_posts';
	}

	public static function get_name() {
		return esc_html__( 'Recent posts', 'zionbuilder-pro' );
	}

	public function the_item( $index = null ) {
		global $post;

		$current_item = $this->get_item_by_index( $index );

		if ( $current_item ) {
			$post = get_post( $current_item->ID );
			setup_postdata( $post );
		}
	}

	public function reset_item() {
		wp_reset_postdata();
	}

	public function perform_query() {
		$config              = [];
		$config['post_type'] = 'post';
		$config['orderby']   = 'post_date';
		$config['order']     = 'DESC';

		$this->query = self::perform_custom_query( $config );
	}
}