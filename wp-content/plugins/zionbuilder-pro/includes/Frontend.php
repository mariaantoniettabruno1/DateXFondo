<?php

namespace ZionBuilderPro;

use ZionBuilderPro\Utils;
use ZionBuilderPro\Plugin;
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class Frontend {
	public function __construct() {
		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'on_enqueue_scripts' ], 1 );
		} else {
			// Register default scripts so we can use them in edit mode
			add_action( 'zionbuilder/editor/before_scripts', [ $this, 'register_default_scripts' ] );
		}

		add_action( 'wp_enqueue_scripts', [ $this, 'on_enqueue_scripts' ], 1 );
	}

	public function on_enqueue_scripts() {
		if ( is_rtl() ) {
			wp_enqueue_style(
				'zion-pro-frontend-rtl-styles',
				Plugin::instance()->get_root_url() . 'dist/css/rtl-pro.css',
				[],
				Plugin::instance()->get_version()
			);
		};
		$this->register_default_scripts();
	}

	public function register_default_scripts() {
		wp_register_script( 'countdown-jquery', Utils::get_file_url( 'assets/vendors/js/jquery.countdown.js' ), [], false, true );
		wp_localize_script(
			'countdown-jquery',
			'zionjQueryCountdown',
			[
				'day'    => __( 'day,days', 'zionbuilder-pro' ),
				'hour'   => __( 'hour,hours', 'zionbuilder-pro' ),
				'minute' => __( 'minute,minutes', 'zionbuilder-pro' ),
				'second' => __( 'second,seconds', 'zionbuilder-pro' ),
			]
		);
	}
}
