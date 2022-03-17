<?php

namespace GravityKit\GravityActions\Actions;

use GravityKit\GravityActions\AbstractSingleton;
use GravityKit\GravityActions\Plugin;

class Mapper extends AbstractSingleton {
	/**
	 * Map of existing actions initialized..
	 *
	 * @since 1.0
	 *
	 * @var ActionInterface[]
	 */
	protected static $map;

	/**
	 * @inheritDoc
	 */
	protected function register() {

	}

	/**
	 * For all the mapped actions we trigger the register method, which will make sure it's fully loaded for use.
	 *
	 * @since 1.0
	 */
	public function action_register_mapped_actions() {
		foreach ( $this->get_actions_map() as $action ) {
			$action->register();
		}
	}

	/**
	 * Filters the bulk actions group from GV and add the Bulk edit entries option.
	 *
	 * @since 1.0
	 *
	 * @param array      $actions Current group for GV.
	 * @param int|string $form_id ID of the form we are dealing with.
	 *
	 * @return array[]
	 */
	public function filter_include_bulk_actions( $actions, $form_id ) {
		$entries = $this->get_actions_map_for_select( $form_id );

		foreach( $entries as $entry ) {
			$actions[ $entry['value'] ] = $entry['label'];
		}

		return $actions;
	}


	/**
	 * On submission of bulk actions we trigger actions.
	 *
	 * @since 1.0
	 *
	 * @param string $raw_action Which action was triggered, normally it contains the form id appended to the end.
	 * @param array  $entries    Entries that were selected for the bulk action.
	 * @param int    $form_id    Which gravity form we are dealing with.
	 *
	 * @return void
	 */
	public function process_actions( $raw_action, $entries, $form_id ) {
		// If we have the ID appended to the end of the action we remove it.
		$action = preg_replace( '/-' . $form_id . '$/', '', $raw_action );

		// Stop if an action is not in our list
		if ( false === in_array( $action, $this->get_actions_map_keys(), true ) ) {
			return;
		}
		// Process background actions that do not need any foreground interactions
	}

	/**
	 * Gets the actions ready for displaying on bulk actions select.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_actions_map_for_select( $form_id = null ) {
		return array_map( static function ( $action ) use ( $form_id ) {
			return [
				'label' => $action->get_title(),
				'value' => sprintf( '%s-%d', $action->get_key(), $form_id ),
			];
		}, $this->get_actions_map() );
	}

	/**
	 * Fetches and filters the list of actions.
	 *
	 * @since 1.0
	 *
	 * @return ActionInterface[]
	 */
	public function get_actions_map() {
		if ( ! isset( static::$map ) ) {
			static::$map = [
				new EditAction(),
			];
		}

		/**
		 * Filters the bulk actions map included in the GV group.
		 *
		 * @since 1.0
		 *
		 * @param ActionInterface[] $map List of objects for the edit actions.
		 */
		return apply_filters( 'gk/gravityactions/bulk_actions_map', static::$map );
	}

	/**
	 * Gets all the keys for actions mapped.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_actions_map_keys() {
		return array_map( static function ( $action ) {
			return $action::get_key();
		}, $this->get_actions_map() );
	}
	/**
	 * Get a specific action based on it's key.
	 *
	 * @since 1.0
	 *
	 * @param string $key key for the action we are looking for.
	 *
	 * @return ActionInterface|boolean
	 */
	public function get_action_by_key( $key ) {
		$actions = $this->get_actions_map();
		$actions_keys = $this->get_actions_map_keys();
		if ( ! in_array( $key, $actions_keys ) ) {
			return false;
		}

		foreach( $actions as $action ) {
			if ( $key !== $action::get_key() ) {
				continue;
			}

			return $action;
		}
	}

	/**
	 * Gets all the keys for actions mapped.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function get_ajax_actions_map_keys() {
		return array_filter( array_map( static function ( ActionInterface $action ) {
			if ( ! $action::is_ajax() ) {
				return false;
			}

			return $action::get_key();
		}, $this->get_actions_map() ) );
	}
}
