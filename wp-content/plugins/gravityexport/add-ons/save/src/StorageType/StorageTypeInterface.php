<?php

namespace GravityKit\GravityExport\Save\StorageType;

use GravityKit\GravityExport\Save\Exception\SaveException;
use GravityKit\GravityExport\Save\Addon\SaveAddon;

/**
 * Interface a storage type should adhere to.
 *
 * @since 1.0
 */
interface StorageTypeInterface {
	/**
	 * Should return a unique name for the storage type.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function getId(): string;

	/**
	 * Should return the title of the storage type.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function getTitle(): string;

	/**
	 * Should return the icon for the storage method.
	 * In GF 2.4, it must be a FontAwesome CSS class. In GF 2.5, it must be parseable by {@see \GFCommon::get_icon_markup()}.
	 *
	 * @since 1.0
	 * @return string
	 */
	public function getIcon(): string;

	/**
	 * Should return whether the storage method is properly configured in the global settings.
	 * @return bool
	 */
	public function isDisabled(): bool;

	/**
	 * Should return the field section for this storage type.
	 *
	 * @since 1.0
	 *
	 * @param SaveAddon $feed The storage feed addon.
	 *
	 * @return mixed[] The fields for the storage type.
	 */
	public function getFeedFields( SaveAddon $feed ): array;

	/**
	 * Should handle the processing of the form for this feed.
	 *
	 * @since 1.0
	 * @throws SaveException when the form could not be processed.
	 *
	 * @param array  $meta    the useful information for this feed.
	 * @param array  $feed    other feed info like event type.
	 * @param string $form_id the form ID
	 */
	public function processForm( string $form_id, array $meta, array $feed ): void;

	/**
	 * Should handle the processing of a single entry for this feed.
	 *
	 * @since 1.0
	 * @throws SaveException when the form could not be processed.
	 *
	 * @param array|null $entry the entry information.
	 * @param array|null $feed
	 * @param array|null $form  the form information.
	 */
	public function processEntry( ?array $form, ?array $entry, ?array $feed ): void;

	/**
	 * Updates the settings for a storage type.
	 *
	 * @since 1.0
	 *
	 * @param mixed[] $settings The current settings.
	 * @param mixed[] $feed     The fresh feed settings.
	 *
	 * @return mixed[] The updated settings.
	 */
	public function getStorageSettings( array $settings, array $feed ): array;
}
