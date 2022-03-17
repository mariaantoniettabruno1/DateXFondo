<?php
/**
 *
 *
 * @var int    $form_id         Which form we are dealing with.
 * @var int    $field_id        Which field ID we are dealing with.
 * @var array  $entries         Which entries we are modifying.
 * @var array  $selected_fields Which fields were selected.
 * @var string $view            Which view are in.
 * @var mixed  $form            Form settings.
 */

$field   = GFAPI::get_field( $form_id, $field_id );
$html_id = sanitize_html_class( "gk-gravityactions-edit-field-{$field_id}" );
?>
<input
	id="<?php echo $html_id; ?>"
	type="hidden"
	name="field_values[<?php echo esc_attr( $field_id ); ?>]"
	value="<?php echo $value; ?>"
/>