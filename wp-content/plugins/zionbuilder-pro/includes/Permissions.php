<?php
namespace ZionBuilderPro;

use ZionBuilder\Settings;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	return;
}

class Permissions {
	public function __construct() {
		add_filter( 'zionbuilder/permissions/allow_edit', [ $this, 'check_if_user_can_edit' ], 10, 3 );
	}

	public function check_if_user_can_edit( $can_edit, $post_id, $post_type ) {
		$user_role_permissions_settings = Settings::get_user_role_permissions_settings();
		$users_permissions_settings     = Settings::get_users_permissions_settings();
		$current_user                   = wp_get_current_user();
		$user_roles                     = (array) $current_user->roles;

		// Check if specific user settings exists
		if ( isset( $users_permissions_settings[ $current_user->ID ] ) ) {
			if ( isset( $users_permissions_settings[ $current_user->ID ]['permissions']['post_types'] ) && in_array( $post_type, $users_permissions_settings[$current_user->ID]['permissions']['post_types'] ) ) {
				return true;
			}
		} else {
			// Check user roles for permissions
			foreach ( $user_roles as $role_id ) {
				if ( isset( $user_role_permissions_settings[$role_id]['permissions']['post_types'] ) && in_array( $post_type, $user_role_permissions_settings[$role_id]['permissions']['post_types'] ) ) {
					return true;
				}
			}
		}

		return $can_edit;
	}

	private function check_permissions( $permissions_setting, $user_roles, $post_type, $default_permission ) {
		foreach ( $user_roles as $role_id ) {
			if ( isset( $permissions_setting[$role_id]['permissions']['post_types'][$post_type] ) && $permissions_setting[$role_id]['permissions']['post_types'][$post_type] ) {
				return true;
			}
		}

		return $default_permission;
	}
}
