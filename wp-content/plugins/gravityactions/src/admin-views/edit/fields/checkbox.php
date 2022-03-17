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

$id      = isset( $input['id'] ) ? $input['id'] : $field->id;
$choices = isset( $input['choices'] ) ? $input['choices'] : $field->choices;
?>

<?php foreach ( $choices as $i => $choice ) : ?>
	<?php
	$sub_id  = isset( $choice['id'] ) ? $choice['id'] : $id . '.' . ( $i + 1 );
	$value   = ! empty( $field_values[ $id ] ) ? $field_values[ $id ] : null;
	$html_id = sanitize_html_class( $html_id . '-' . $choice['value'] );
	?>
	<div class="">
		<input
			id="<?php echo $html_id; ?>"
			type="checkbox"
			class="gk-gravityactions-edit-field"
			name="field_values[<?php echo esc_attr( $sub_id ); ?>]"
			<?php checked( $value === $choice['value'] ); ?>
			value="<?php echo esc_attr( $choice['value'] ); ?>"
		/>
		<label for="<?php echo $html_id; ?>">
			<?php echo esc_html( $choice['text'] ); ?>
		</label>
	</div>
<?php endforeach; ?>
