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

?>
<select
	id="<?php echo $html_id; ?>"
	name="field_values[<?php echo esc_attr( $field->id ); ?>]"
	class="gk-gravityactions-edit-field"
>
	<option value="">
		<?php if ( ! empty( $placeholder ) ) : ?>
			<?php echo esc_html( $placeholder ); ?>
		<?php endif; ?>
	</option>
	<?php foreach ( $field->choices as $choice ) : ?>
		<option
			value="<?php echo esc_attr( $choice['value'] ) ?>"
			<?php selected( ! empty( $field_values[ $field_id ] ) && $choice['value'] === $field_values[ $field_id ] ) ?>
		>
			<?php echo esc_html( $choice['text'] ); ?>
		</option>
	<?php endforeach; ?>
</select>