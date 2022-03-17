<?php

namespace GravityKit\GravityActions;

/**
 * Class AbstractSingleton.
 *
 * @since 1.0
 *
 * @package GravityKit\GravityActions
 */
abstract class AbstractSingleton {
	/**
	 * List of all instances created.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	protected static $instances = [];

	/**
	 * AbstractSingleton constructor
	 * Dont extend this unless very specific usage.
	 *
	 * @since 1.0
	 */
	protected function __construct() {
		// Initializes the singleton, with a method that is always called.
		$this->register();
	}

	/**
	 * Creates the instance of the object that extends this abstract.
	 *
	 * @since 1.0
	 *
	 * @return mixed
	 */
	public static function instance() {
		if ( ! isset( static::$instances[ static::class ] ) ) {
			static::$instances[ static::class ] = new static();
		}

		return static::$instances[ static::class ];
	}

	/**
	 * Every singleton needs to have a register method, which will be called when the singleton is initialized.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	abstract protected function register();

	/**
	 * When dealing with Singletons we cannot clone.
	 *
	 * @since 1.0
	 */
	private function __clone() {

	}

	/**
	 * Singletons cannot be serialized.
	 *
	 * @since 1.0
	 */
	private function __wakeup() {

	}
}