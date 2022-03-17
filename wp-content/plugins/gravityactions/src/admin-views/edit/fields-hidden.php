<?php
/**
 *
 *
 * @var int    $form_id         Which form we are dealing with.
 * @var array  $entries         Which entries we are modifying.
 * @var array  $selected_fields Which fields were selected.
 * @var string $view            Which view are in.
 * @var mixed  $form            Form settings.
 */
foreach ( $selected_fields as $field_id ) {
	if ( ! is_float( $field_id + 0 ) ) {
		$field = GFAPI::get_field( $form_id, $field_id );

		// Fields that have their subfields selected dont get value.
		if ( ! empty( $field->inputs ) ) {
			continue;
		}
	}

	$this->render( 'edit/fields/hidden', [ 'field_id' => $field_id, 'value' => ! empty( $field_values[ $field_id ] ) ? $field_values[ $field_id ] : false ] );
}