<?php
/**
 *
 *
 * @var int    $form_id         Which form we are dealing with.
 * @var int    $field_id        Which field ID we are dealing with.
 * @var string $html_id         Which field html this field has.
 * @var array  $field           Which field object we are dealing with.
 * @var array  $entries         Which entries we are modifying.
 * @var array  $selected_fields Which fields were selected.
 * @var string $view            Which view are in.
 * @var string $placeholder     Place holder for this field.
 * @var mixed  $form            Form settings.
 */

$id = isset( $input['id'] ) ? $input['id'] : $field->id;
?>
<input
	id="<?php echo $html_id; ?>"
	type="text"
	class="gk-gravityactions-edit-field"
	name="field_values[<?php echo esc_attr( $id ); ?>]"
	<?php if ( ! empty( $placeholder ) ) : ?>
		placeholder="<?php echo esc_attr( $placeholder ); ?>"
	<?php endif; ?>
	<?php if ( ! empty( $field_values[ $id ] ) ) : ?>
		value="<?php echo esc_attr( $field_values[ $id ] ); ?>"
	<?php endif; ?>
/>
