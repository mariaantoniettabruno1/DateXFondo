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
$next_button           = get_submit_button(
	__( 'Update Entries', 'gk-gravityactions' ),
	'primary',
	'submit',
	false,
	[
		'data-js'         => 'gk-gravityactions-modal-submit',
		'data-modal-view' => 'edit/step-processing',
	]
);
$back_button           = get_submit_button(
	__( 'Back', 'gk-gravityactions' ),
	'secondary',
	'submit',
	false,
	[
		'data-js'         => 'gk-gravityactions-modal-submit',
		'data-modal-view' => 'edit/step-2',
	]
);
?>

<form
	class="gk-gravityactions"
	data-js="gk-gravityactions-modal-form"
>
	<?php $this->render( 'components/modal-fields', [ 'bulk_action' => EditAction::get_key() ] ); ?>
	<?php $this->render( 'components/header' ); ?>

	<?php $this->render( 'edit/fields-hidden' ); ?>

	<?php foreach ( $selected_fields as $field_id ) : // Here to make sure when we back it retains fields. ?>
		<input type="hidden" name="selected_fields" value="<?php echo esc_attr( $field_id ); ?>"/>
	<?php endforeach; ?>

	<div class="gk-gravityactions-modal-body">

		<div class="gk-gravityactions-modal-content">

			<div class="gk-gravityactions-modal-column gk-gravityactions-modal-column-center">

				<img width="206" height="235" src="<?php echo Assets::get_url( 'src/assets/images/edit_step_3_character.svg' ); ?>" alt=""/>

				<span class="gk-gravityactions-modal-help-title">
					<?php esc_html_e( 'Review bulk edit changes', 'gk-gravityactions' ); ?>
				</span>

				<span class="gk-gravityactions-modal-help-subtitle">
					<?php _e( 'Almost Done, we just need you to confirm<br/>your changes before we finalize them.', 'gk-gravityactions' ); ?>
				</span>

				<div class="gk-gravityactions-modal-help-link">
					<img src="<?php echo Assets::get_url( 'src/assets/images/help_question.svg' ); ?>" alt="<?php esc_attr_e( 'Help', 'gk-gravityactions' ); ?>"/>
					<a href='https://docs.gravityview.co/article/802-bulk-edit-old-field-data' data-beacon-article='615c5ccd12c07c18afdda724' target='_blank' rel='noopener noreferrer'><?php esc_html_e( 'What happens after I submit my changes?', 'gk-gravityactions' ); ?></a>
				</div>

			</div>

			<div class="gk-gravityactions-modal-column gk-gravityactions-modal-column-summary">

				<div class="gk-gravityactions-modal-summary-header">
					<h3 class="gk-gravityactions-modal-summary-header-title">
						<?php esc_html_e( 'Change Summary', 'gk-gravityactions' ); ?>
					</h3>
					<p class="gk-gravityactions-modal-summary-header-description">
						<?php esc_html_e( 'Let\'s take one last look at the changes you made.', 'gk-gravityactions' ); ?>
					</p>
				</div>

				<h4 class="gk-gravityactions-modal-summary-entries-heading">
					<?php printf( esc_html__( 'Form: %1$s', 'gk-gravityactions' ), esc_html( $form['title'] ) ); ?>
				</h4>

				<div class="gk-gravityactions-modal-summary-entry">
					<div class="gk-gravityactions-modal-summary-entry-icon">
						<img src="<?php echo Assets::get_url( 'src/assets/images/icons/entries.svg' ); ?>" alt="<?php esc_attr_e( 'Entries icon', 'gk-gravityactions' ); ?>"/>
					</div>
					<div class="gk-gravityactions-modal-summary-entry-content">
						<h5 class="gk-gravityactions-modal-summary-entry-title">
							<?php echo count( $entries ); ?>
						</h5>
						<p class="gk-gravityactions-modal-summary-entry-description">
							<?php echo esc_html( _n( 'Entry updated', 'Entries updated', 'gk-gravityactions' ) ); ?>
						</p>
					</div>
					<div class="gk-gravityactions-modal-summary-entry-action">
					</div>
				</div>
				<div class="gk-gravityactions-modal-summary-entry">
					<div class="gk-gravityactions-modal-summary-entry-icon">
						<img src="<?php echo Assets::get_url( 'src/assets/images/icons/field.svg' ); ?>" alt="<?php esc_attr_e( 'Field icon', 'gk-gravityactions' ); ?>"/>
					</div>
					<div class="gk-gravityactions-modal-summary-entry-content">
						<h5 class="gk-gravityactions-modal-summary-entry-title">
							<?php echo $count_selected_fields; ?>
						</h5>
						<p class="gk-gravityactions-modal-summary-entry-description">
							<?php echo esc_html( _n( 'Field updated', 'Fields updated', 'gk-gravityactions' ) ); ?>
						</p>
					</div>
					<div class="gk-gravityactions-modal-summary-entry-action">
					</div>
				</div>

				<div class="gk-gravityactions-modal-summary-note">
					<img class="gk-gravityactions-modal-summary-note-icon" src="<?php echo Assets::get_url( 'src/assets/images/icons/review-notice-bell.svg' ); ?>" alt="<?php esc_attr_e( 'Alert icon', 'gk-gravityactions' ); ?>"/>

					<?php esc_html_e( 'These changes are permanent. It’s highly recommended you back up your entry data before continuing.', 'gk-gravityactions' ); ?>
				</div>

			</div>

		</div>

	</div>
	<?php $this->render( 'components/footer', [
		'current_step'     => 2,
		'total_steps'      => 3,
		'left_navigation'  => $back_button,
		'right_navigation' => $next_button,
	] ); ?>
</form>
