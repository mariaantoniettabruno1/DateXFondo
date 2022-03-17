<?php
/**
 *
 *
 * @var int        $form_id            Which form we are dealing with.
 * @var int        $field_id           Which field ID we are dealing with.
 * @var array      $entries            Which entries we are modifying.
 * @var array      $selected_fields    Which fields were selected.
 * @var string     $view               Which view are in.
 * @var mixed      $form               Form settings.
 * @var EditAction $bulk_action_object Action we are handling here
 */

use \GravityKit\GravityActions\Actions\EditAction;

// When it's a sub-field we can handle it separately.
if ( is_float( $field_id + 0 ) ) {
	return;
}

$field   = GFAPI::get_field( $form_id, $field_id );
$html_id = sanitize_html_class( "gk-gravityactions-edit-field-{$field->id}" );
?>
<tr class="gk-gravityactions-edit-field-row">
	<th scope="row">
		<label
			for="<?php echo $html_id; ?>"
		>
			<?php echo esc_html( $field->label ); ?>
		</label>
	</th>
	<td>
		<?php
		$template = $bulk_action_object->get_template_for_field( $field );
		$this->render( $template, [
			'field'       => $field,
			'input'       => null, // Here to reset internal input for every field.
			'html_id'     => $html_id,
			'placeholder' => __( 'Leaving blank will empty field.', 'gk-gravityactions' )
		] );
		?>
	</td>
</tr>