<?php
namespace GravityKit\GravityActions;

/**
 * Determines if the provided value should be regarded as 'true'.
 *
 * @since 1.0
 *
 * @param  mixed  $var  Value to be tested.
 *
 * @return bool
 */
function is_truthy( $var ) {
	if ( is_bool( $var ) ) {
		return $var;
	}

	/**
	 * Provides an opportunity to modify strings that will be
	 * deemed to evaluate to true.
	 *
	 * @since 1.0
	 *
	 * @param array $truthy_strings
	 */
	$truthy_strings = (array) apply_filters(
		'gk/gravityactions/is_truthy_strings',
		[
			'1',
			'enable',
			'enabled',
			'on',
			'y',
			'yes',
			'true',
		]
	);
	// Makes sure we are dealing with lowercase for testing
	if ( is_string( $var ) ) {
		$var = strtolower( $var );
	}

	// If $var is a string, it is only true if it is contained in the above array
	if ( in_array( $var, $truthy_strings, true ) ) {
		return true;
	}

	// All other strings will be treated as false
	if ( is_string( $var ) ) {
		return false;
	}

	// For other types (ints, floats etc) cast to bool
	return (bool) $var;
}

/**
 * Sorting function based on Priority.
 *
 * @since 1.0
 *
 * @param object|array $a First Subject to compare.
 * @param object|array $b Second subject to compare.
 *
 * @return int
 */
function sort_by_priority( $a, $b ) {
	if ( is_array( $a ) ) {
		$a_priority = $a['priority'];
	} else {
		$a_priority = $a->priority;
	}

	if ( is_array( $b ) ) {
		$b_priority = $b['priority'];
	} else {
		$b_priority = $b->priority;
	}

	if ( (int) $a_priority === (int) $b_priority ) {
		return 0;
	}

	return (int) $a_priority < (int) $b_priority ? - 1 : 1;
}


/**
 * Tests to see if the requested variable is set either as a post field or as a URL
 * param and returns the value if so.
 *
 * Post data takes priority over fields passed in the URL query. If the field is not
 * set then $default (null unless a different value is specified) will be returned.
 *
 * The variable being tested for can be an array if you wish to find a nested value.
 *
 * @since 1.0
 *
 * @see   get_global_var()
 *
 * @param string|array $variable
 * @param mixed        $default
 * @param mixed        $filter
 *
 * @return mixed
 */
function get_request_var( $variable, $default = null ) {
	$unsafe = get_global_var( [ INPUT_GET, INPUT_POST ], $variable, $default );
	return $unsafe;
}

/**
 * Tests to see if the requested variable is set either as a post field or as a URL
 * param and returns the value if so.
 *
 * Post data takes priority over fields passed in the URL query. If the field is not
 * set then $default (null unless a different value is specified) will be returned.
 *
 * The variable being tested for can be an array if you wish to find a nested value.
 *
 * @since 1.0
 *
 * @param string|array $global
 * @param string|array $variable
 * @param mixed        $default
 *
 * @return mixed
 */
function get_global_var( $global, $variable, $default = null ) {
	$super         = [];
	$super_globals = [ INPUT_POST, INPUT_GET, INPUT_COOKIE, INPUT_ENV, INPUT_SERVER ];
	$search_in     = array_intersect( $super_globals, (array) $global );

	if ( empty( $search_in ) ) {
		throw new \Exception( __( 'Invalid use, type must be one of INPUT_* family.', 'gk-gravityactions' ) );
	}

	foreach ( $search_in as $super_name ) {
		switch ( $super_name ) {
			case INPUT_POST :
				$super[] = $_POST;
				break;
			case INPUT_GET :
				$super[] = $_GET;
				break;
			case INPUT_COOKIE :
				$super[] = $_COOKIE;
				break;
			case INPUT_ENV :
				$super[] = $_ENV;
				break;
			case INPUT_SERVER :
				$super[] = $_SERVER;
				break;
		}
	}

	$var = Utils\Arrays::get_in_any( $super, $variable );

	return $var;
}
