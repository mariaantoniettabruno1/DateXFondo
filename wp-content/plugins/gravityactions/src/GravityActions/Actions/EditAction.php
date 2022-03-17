<?php

namespace GravityKit\GravityActions\Actions;

use GF_Field;
use GF_Field_Address;
use GF_Field_Checkbox;
use GF_Field_Date;
use GF_Field_Email;
use GF_Field_Hidden;
use GF_Field_MultiSelect;
use GF_Field_Name;
use GF_Field_Number;
use GF_Field_Phone;
use GF_Field_Radio;
use GF_Field_Select;
use GF_Field_Tag;
use GF_Field_Text;
use GF_Field_Textarea;
use GF_Field_Time;
use GF_Field_Website;
use GFAPI;
use GF_Entry_List_Table;
use GFCommon;

use GFFormsModel;

use GravityKit\GravityActions\Assets;
use GravityKit\GravityActions\Plugin;
use GravityKit\GravityActions\Admin;
use GravityKit\GravityActions\Template;
use GravityKit\GravityActions\Utils\Arrays;
use function GravityKit\GravityActions\get_request_var;
use function GravityKit\GravityActions\is_truthy;

class EditAction extends ActionAbstract {
	const TEXT_FIELDS = [
		GF_Field_Text::class,
		GF_Field_Textarea::class,
		GF_Field_Number::class,
		GF_Field_Hidden::class,
		GF_Field_Name::class,
		GF_Field_Date::class,
		GF_Field_Time::class,
		GF_Field_Phone::class,
		GF_Field_Address::class,
		GF_Field_Website::class,
		GF_Field_Email::class,
	];

	const SELECT_FIELDS = [
		GF_Field_Select::class,
		GF_Field_Checkbox::class,
		GF_Field_Radio::class,
		GF_Field_MultiSelect::class,
	];

	protected $modifiable_field_count = 0;

	/**
	 * @inheritDoc
	 */
	public static function get_key() {
		return 'gk-bulk-edit-entries';
	}

	/**
	 * @inheritDoc
	 */
	public static function get_title() {
		return esc_attr__( 'Bulk Edit Entries', 'gk-gravityactions' );
	}

	/**
	 * @inheritDoc
	 */
	public function get_subtitle( Template $template ) {
		return $template->render( 'edit/modal-subtitle', [], false );
	}

	/**
	 * @inheritDoc
	 */
	public static function has_view() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public static function is_ajax() {
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function hook() {
		parent::hook();
		add_action( 'gk/gravityactions/before_modal_render:edit/step-processing', [ $this, 'process_modifications' ] );
	}

	/**
	 * {@inheritDoc}
	 */
	public function enqueue_assets() {
		if ( ! Assets::instance()->should_enqueue_admin() ) {
			return;
		}
		wp_enqueue_script( 'gk-gravityactions-edit' );
	}

	/**
	 * Specifically to find all the Items based on the search criteria passed, this method looks around quite a bit, so
	 * we should avoid using it anywhere else other then the current usage unless we abstract the code a little further
	 * so we can pass the search criteria.
	 *
	 * @since TBD
	 *
	 * @param int $form_id
	 *
	 * @return array
	 */
	protected function get_all_entries( $form_id ) {
		if ( ! class_exists( 'GF_Entry_List_Table' ) ) {
			require_once( GFCommon::get_base_path() . '/entry_list.php' );
		}

		// All of the hacking below is to avoid having to duplicate code from GF around the search.
		$GLOBALS['hook_suffix'] = 'forms_page_gf_entries';
		set_current_screen( 'forms_page_gf_entries' );

		$query_args = wp_parse_args( get_request_var( 'current_query_args', [] ) );

		// Set the GET vars.
		$_GET = $query_args;

		$form            = GFFormsModel::get_form_meta( $form_id );
		$table           = new GF_Entry_List_Table( [ 'form_id' => $form_id, 'form' => $form ] );
		$search_criteria = $table->get_search_criteria();

		$entries = GFAPI::get_entry_ids( $form_id, $search_criteria );

		return $entries;
	}

	/**
	 * @inheritDoc
	 */
	public function prepare_template_vars( array $template_vars = [] ) {
		$template_vars                   = parent::prepare_template_vars( $template_vars );
		$template_vars['is_all_entries'] = is_truthy( get_request_var( 'is_all_entries', false ) );

		if ( $template_vars['is_all_entries'] ) {
			$entries = $this->get_all_entries( $template_vars['form_id'] );
			if ( ! empty( $entries ) ) {
				$template_vars['entries'] = $entries;
			}
		}

		if ( ! empty( $template_vars['selected_fields'] ) ) {
			$template_vars['selected_fields'] = array_unique( $template_vars['selected_fields'] );
			$template_vars['selected_fields'] = array_filter( $template_vars['selected_fields'] );
			$template_vars['selected_fields'] = array_filter( $template_vars['selected_fields'], [ static::class, 'is_not_sub_field' ] );
			foreach ( $template_vars['selected_fields'] as $field_id ) {
				$field = GFAPI::get_field( $template_vars['form_id'], $field_id );

				if ( empty( $field->inputs ) ) {
					continue;
				}
				foreach ( $field->inputs as $input ) {
					// Ignore inputs that are hidden.
					if ( is_truthy( Arrays::get( $input, 'isHidden', false ) ) ) {
						continue;
					}

					// Dont add the same input twice.
					if ( in_array( $input['id'], $template_vars['selected_fields'] ) ) {
						continue;
					}
					$template_vars['selected_fields'][] = $input['id'];
				}
			}
		}

		$template_vars['reload_window'] = true;

		return $template_vars;
	}

	/**
	 * Process the modifications required by this action.
	 *
	 * @since 1.0
	 *
	 * @param array $template_vars Which template variables were passed down.
	 *
	 */
	public function process_modifications( $template_vars ) {
		global $wpdb;
		$template = Admin::instance()->get_template();

		$insert_sqls = $this->get_insert_sql( $template_vars['form_id'], $template_vars['entries'], $template_vars['selected_fields'], $template_vars['field_values'] );

		// There will be an insert per entry to avoid reaching the limit for the database.
		foreach ( $insert_sqls as $sql ) {
			$return = $wpdb->query( $sql );
		}

		$sql    = $this->get_update_sql( $template_vars['form_id'], $template_vars['entries'], $template_vars['selected_fields'], $template_vars['field_values'] );
		$return = $wpdb->query( $sql );
	}

	/**
	 * Check if the ID is a subfield.
	 *
	 * @since 1.0
	 *
	 * @param int|float|string $field_id
	 *
	 * @return bool
	 */
	public static function is_sub_field( $field_id ) {
		return is_float( $field_id + 0 );
	}

	/**
	 * Check if the ID is not a subfield.
	 *
	 * @since 1.0
	 *
	 * @param int|float|string $field_id
	 *
	 * @return bool
	 */
	public static function is_not_sub_field( $field_id ) {
		return ! static::is_sub_field( $field_id );
	}

	/**
	 * Fetches the Insert SQL given a set of params required.
	 *
	 * @since 1.0
	 *
	 * @param int   $form_id
	 * @param array $entries
	 * @param array $fields
	 * @param array $field_values
	 *
	 * @return array
	 */
	protected function get_insert_sql( $form_id, array $entries, array $fields, array $field_values ) {
		global $wpdb;
		$entry_meta_table = GFFormsModel::get_entry_meta_table_name();
		$form_id          = absint( $form_id );
		$fields           = array_unique( $fields );
		$fields           = array_values( array_filter( $fields, static function ( $field_id ) use ( $form_id ) {
			if ( ! static::is_sub_field( $field_id ) ) {
				$field = GFAPI::get_field( $form_id, $field_id );

				// Fields that have their subfields selected dont get value.
				if ( ! empty( $field->inputs ) ) {
					return false;
				}
			}

			return true;
		} ) );

		$entry_ids     = array_map( 'absint', $entries );
		$entry_ids_sql = implode( ', ', $entry_ids );
		$total_fields  = count( $fields );

		$field_ids     = array_unique( array_map( static function ( $id ) {
			return sprintf( "'%s'", esc_sql( $id ) );
		}, $fields ) );
		$field_ids_sql = implode( ', ', $field_ids );
		$all_sql       = [];

		$sql[]             = "SELECT `entry_id`, `meta_key` FROM `{$entry_meta_table}`";
		$sql[]             = 'WHERE';
		$sql[]             = '1=1';
		$sql[]             = $wpdb->prepare( 'AND `form_id` = %d', $form_id );
		$sql[]             = "AND `entry_id` IN ( {$entry_ids_sql} )";
		$sql[]             = "AND `meta_key` IN ( {$field_ids_sql} )";
		$sql               = implode( " \n ", $sql );
		$to_exclude_insert = $wpdb->get_results( $sql );
		$exclude           = [];
		foreach ( $to_exclude_insert as $result ) {
			$entry_id = (int) $result->entry_id;

			if ( ! isset( $exclude[ $entry_id ] ) ) {
				$exclude[ $entry_id ] = [];
			}
			$exclude[ $entry_id ][] = $result->meta_key;
		}

		foreach ( $entry_ids as $entry_id ) {
			$sql                        = [];
			$_insert_values_sql         = [];

			foreach ( $fields as $i => $field_id ) {
				if ( isset( $exclude[ $entry_id ] ) && in_array( $field_id, $exclude[ $entry_id ], false ) ) {
					continue;
				}
				$value                  = isset( $field_values[ $field_id ] ) ? $field_values[ $field_id ] : '';
				$_insert_values_sql[] = $wpdb->prepare( "( %d, %d, %s, %s, %s )", $form_id, $entry_id, $field_id, $value, '' );
			}

			if( empty( $_insert_values_sql ) ) {
				continue;
			}

			$sql['insert']              = "INSERT INTO `{$entry_meta_table}`";
			$sql['insert_fields']       = "(`form_id`, `entry_id`, `meta_key`, `meta_value`, `item_index`)";
			$sql['insert_values_start'] = 'VALUES';
			$sql['insert_values']       = implode( ", \n ", $_insert_values_sql );

			$all_sql[] = implode( " \n ", $sql ) . ';';
		}

		return $all_sql;
	}

	/**
	 * Fetches the Update SQL given a set of params required.
	 *
	 * @since 1.0
	 *
	 * @param int   $form_id
	 * @param array $entries
	 * @param array $fields
	 * @param array $field_values
	 *
	 * @return string
	 */
	protected function get_update_sql( $form_id, array $entries, array $fields, array $field_values ) {
		global $wpdb;
		$entry_meta_table = GFFormsModel::get_entry_meta_table_name();
		$form_id          = absint( $form_id );
		$fields           = array_unique( $fields );
		$fields           = array_filter( $fields, static function ( $field_id ) use ( $form_id ) {
			if ( ! static::is_sub_field( $field_id ) ) {
				$field = GFAPI::get_field( $form_id, $field_id );

				// Fields that have their subfields selected dont get value.
				if ( ! empty( $field->inputs ) ) {
					return false;
				}
			}

			return true;
		} );

		$entry_ids     = array_map( 'absint', $entries );
		$entry_ids_sql = implode( ', ', $entry_ids );

		$field_ids     = array_unique( array_map( static function ( $id ) {
			return sprintf( "'%s'", esc_sql( $id ) );
		}, $fields ) );
		$field_ids_sql = implode( ', ', $field_ids );

		$_set_sql = $_where_sql = [];

		$sql['update'] = "UPDATE `{$entry_meta_table}`";

		$_set_sql[]  = 'SET';
		$_set_sql[]  = '`meta_value` = CASE `meta_key`';
		foreach ( $fields as $field ) {
			$value        = isset( $field_values[ $field ] ) ? $field_values[ $field ] : '';
			$_set_sql[] = $wpdb->prepare( 'WHEN %s THEN %s', $field, $value );
		}
		$_set_sql[]   = 'END';

		$_where_sql[] = 'WHERE';
		$_where_sql[] = '1=1';
		$_where_sql[] = $wpdb->prepare( 'AND `form_id` = %d', $form_id );
		$_where_sql[] = "AND `entry_id` IN ( {$entry_ids_sql} )";
		$_where_sql[] = "AND `meta_key` IN ( {$field_ids_sql} )";

		$sql['set']   = implode( " \n ", $_set_sql );
		$sql['where'] = implode( " \n ", $_where_sql );
		$sql          = implode( " \n ", $sql ) . ';';

		return $sql;
	}

	/**
	 * Determines which template we should include for this field.
	 *
	 * @since 1.0
	 *
	 * @param GF_Field|false $field
	 *
	 * @return string
	 */
	public function get_template_for_field( $field ) {

		if ( $this->is_complex_field( $field->type ) ) {
			return $this->get_complex_field_template( $field->type );
		}

		return $this->get_simple_field_template( $field->type );
	}

	/**
	 * Which simple fields alongside their template.
	 *
	 * @since 1.0
	 *
	 * @return array[string,string]
	 */
	public function get_simple_fields() {
		$fields = [
			'text'     => 'edit/fields/text',
			'website'  => 'edit/fields/text',
			'phone'    => 'edit/fields/text',
			'date'     => 'edit/fields/text',
			'email'    => 'edit/fields/email',
			'checkbox' => 'edit/fields/checkbox',
			'radio'    => 'edit/fields/radio',
			'select'   => 'edit/fields/select',
		];

		return $fields;
	}

	/**
	 * Determines if a given field is simple and requires it's own template.
	 *
	 * @since 1.0
	 *
	 * @param string $field
	 *
	 * @return bool
	 */
	public function is_simple_field( $field ) {
		return isset( $this->get_simple_fields()[ $field ] );
	}

	/**
	 * Gets the template associated with a given type of field.
	 *
	 * @since 1.0
	 *
	 * @param string $field
	 *
	 * @return string
	 */
	public function get_simple_field_template( $field ) {
		if ( ! $this->is_simple_field( $field ) ) {
			return null;
		}

		return $this->get_simple_fields()[ $field ];
	}


	/**
	 * Which fields are complex, which means they will need a unique template.
	 *
	 * @since 1.0
	 *
	 * @return array[string,string]
	 */
	public function get_complex_fields() {
		$fields = [
			'name'    => 'edit/fields/complex/default',
			'address' => 'edit/fields/complex/default',
			'time'    => 'edit/fields/complex/default',
		];

		return $fields;
	}

	/**
	 * Determines if a given field is complex and requires it's own template.
	 *
	 * @since 1.0
	 *
	 * @param string $field
	 *
	 * @return bool
	 */
	public function is_complex_field( $field ) {
		return isset( $this->get_complex_fields()[ $field ] );
	}

	/**
	 * Gets the template associated with a given type of field.
	 *
	 * @since 1.0
	 *
	 * @param string $field
	 *
	 * @return string
	 */
	public function get_complex_field_template( $field ) {
		if ( ! $this->is_complex_field( $field ) ) {
			return null;
		}

		return $this->get_complex_fields()[ $field ];
	}

	/**
	 * @param GF_Field $field
	 *
	 * @return bool
	 */
	public static function is_valid_field_type( $field ) {
		$available_field_classes = array_merge( static::TEXT_FIELDS, static::SELECT_FIELDS );

		return in_array( get_class( $field ), $available_field_classes, true );
	}

	/**
	 * @param GF_Field $field
	 *
	 * @return bool
	 */
	public static function is_select_field_type( $field ) {
		return in_array( get_class( $field ), static::SELECT_FIELDS, true );
	}
}
