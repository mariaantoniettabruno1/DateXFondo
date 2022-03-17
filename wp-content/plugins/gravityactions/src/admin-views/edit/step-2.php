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

use \GravityKit\GravityActions\Assets;
use \GravityKit\GravityActions\Actions\EditAction;

$count_selected_fields = count( array_filter( $selected_fields, [ EditAction::class, 'is_not_sub_field' ] ) );
$next_button_text      = sprintf( _n( 'Review changes to %1$d field', 'Review changes to %1$d fields', $count_selected_fields, 'gk-gravityactions' ), $count_selected_fields );
$next_button           = get_submit_button(
	$next_button_text,
	'primary',
	'submit',
	false,
	[
		'data-js'         => 'gk-gravityactions-modal-submit',
		'data-modal-view' => 'edit/step-3',
	]
);
$back_button           = get_submit_button(
	__( 'Back', 'gk-gravityactions' ),
	'secondary',
	'submit',
	false,
	[
		'data-js'         => 'gk-gravityactions-modal-submit',
		'data-modal-view' => 'edit/step-1',
	]
);
?>
<form
	class="gk-gravityactions"
	data-js="gk-gravityactions-modal-form"
>
	<?php $this->render( 'components/modal-fields', [ 'bulk_action' => EditAction::get_key() ] ); ?>
	<?php $this->render( 'components/header' ); ?>

	<?php foreach ( $selected_fields as $field_id ) : // Here to make sure when we back it retains fields. ?>
		<input type="hidden" name="selected_fields" value="<?php echo esc_attr( $field_id ); ?>"/>
	<?php endforeach; ?>

	<div class="gk-gravityactions-modal-body">

		<div class="gv-bulk-edit-step gv-bulk-edit-step-2">

			<div class="gv-bulk-edit-step-content-side">

				<table>
					<thead>
					<tr>
						<th><?php esc_html_e( 'Field', 'gk-gravityactions' ); ?></th>
						<th><?php esc_html_e( 'Change to', 'gk-gravityactions' ); ?></th>
					</tr>
					</thead>
					<tbody class="gv-bulk-edit-step-2-fields">
					<?php
					foreach ( $selected_fields as $field_id ) {
						$this->render( 'edit/field-row', [ 'field_id' => $field_id ] );
					}
					?>
					</tbody>
				</table>

			</div>

			<div class="gv-bulk-edit-step-help-side">

				<img width="188" height="235" src="<?php echo Assets::get_url( 'src/assets/images/edit_step_2_character.svg' ); ?>" alt=""/>

				<span class="gk-gravityactions-modal-help-title">
					<?php esc_html_e( 'Edit field values', 'gk-gravityactions' ); ?>
				</span>

				<span class="gk-gravityactions-modal-help-subtitle">
					<?php _e( 'Great, looks like you’ve selected your fields.<br/>Let’s edit them with your updated content.', 'gk-gravityactions' ); ?>
				</span>

				<div class="gk-gravityactions-modal-help-link">
					<img src="<?php echo Assets::get_url( 'src/assets/images/help_question.svg' ); ?>" alt="<?php esc_attr_e( 'Help', 'gk-gravityactions' ); ?>"/>
					<a href='https://docs.gravityview.co/article/802-bulk-edit-old-field-data' data-beacon-article='615c5ccd12c07c18afdda724' target='_blank' rel='noopener noreferrer'><?php esc_html_e( 'What will happen to my old field data?', 'gk-gravityactions' ); ?></a>
				</div>

			</div>

		</div>

	</div>
	<?php $this->render( 'components/footer', [
		'current_step'     => 1,
		'total_steps'      => 3,
		'left_navigation'  => $back_button,
		'right_navigation' => $next_button,
	] ); ?>
</form>