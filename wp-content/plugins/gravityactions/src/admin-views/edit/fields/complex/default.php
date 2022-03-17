<?php
/**
 * @var \GF_Field $field
 */


/**
 * Allows showing inputs that aren't currently displayed in the form.
 *
 * @since  1.0
 *
 * @param bool $hide_hidden_inputs True: hide inputs hidden by the form. False: show inputs hidden by the form.
 */
$hide_hidden_inputs = apply_filters( 'gk/gravityactions/hide_hidden_inputs', true );

foreach ( $field->inputs as $input ) {

	if( $hide_hidden_inputs && true === rgar( $input, 'isHidden' ) ) {
		continue;
	}
?>
	<div class="gk-gravityactions-field-group">
		<?php
		$html_id = sanitize_html_class( "gk-gravityactions-edit-field-{$input['id']}" );
		?>
		<label
			for="<?php echo $html_id; ?>"
		>
			<?php echo esc_html( $input['label'] ); ?>
		</label>
		<?php
		$template = 'edit/fields/text';
		if ( ! empty( $input['inputType'] ) ) {
			$template = $bulk_action_object->get_simple_field_template( $input['inputType'] );
		}

		$this->render( $template, [
			'field'       => $field,
			'input'       => $input,
			'html_id'     => $html_id,
			'placeholder' => __( 'Leaving blank will empty field.', 'gk-gravityactions' )
		] );
		?>
	</div>
<?php } ?>