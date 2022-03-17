<?php

namespace ZionBuilderPro\Elements\Accordions;

use \ZionBuilder\Elements\Accordions\Accordions as FreeAccordions;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class Text
 *
 * @package ZionBuilder\Elements
 */
class Accordions extends FreeAccordions {
	/**
	 * Get label
	 *
	 * Sets the label that will appear in element list in edit mode
	 */
	public function get_label() {
		return [
			'text'  => 'PRO',
			'color' => '#eec643',
		];
	}
}
