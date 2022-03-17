<?php
/**
 *
 *
 * @var int    $form_id         Which form we are dealing with.
 * @var array  $entries         Which entries we are modifying.
 * @var array  $selected_fields Which fields were selected.
 * @var string $view            Which view are in.
 * @var mixed  $form            Form settings.
 * @var bool   $reload_window   Whether to reload the window after the step completes.
 */

use \GravityKit\GravityActions\Assets;
use \GravityKit\GravityActions\Actions\EditAction;

$back_button   = get_submit_button(
	__( 'Close', 'gk-gravityactions' ),
	'secondary featherlight-close',
	'submit',
	false
);
$cancel_button = get_submit_button(
	__( 'Close Editor', 'gk-gravityactions' ),
	'primary featherlight-close',
	'submit',
	false
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

	<?php

	if( ! empty( $reload_window ) ) {
	?>
	<script>
		GravityKit.GravityActions.Modal.featherlight.$instance.on( 'beforeCloseModal.GravityActions/GK', () => {
			window.location.reload();
		} );
	</script>
	<?php
	}
	?>

	<div class="gk-gravityactions-modal-body">

		<div class="gk-gravityactions-modal-content">

			<div class="gk-gravityactions-modal-column gk-gravityactions-modal-column-center gk-gravityactions-modal-column-full-width">

				<img width="201" height="267" src="<?php echo Assets::get_url( 'src/assets/images/floaty-success.svg' ); ?>" alt=""/>

				<span class="gk-gravityactions-modal-help-title">
					<?php echo esc_html( _n( 'Entry successfully updated!', 'Entries successfully updated!', 'gk-gravityactions' ) ); ?>
				</span>

				<p class="gk-gravityactions-modal-help-subtitle">
					<?php
					$subtitle = _n( 'Great work, %1$s entry was successfully updated.', 'Great work, %1$s entries were successfully updated.', count( $entries ), 'gk-gravityactions' );

					printf( esc_html( $subtitle ), '<strong>' . count( $entries ) . '</strong>' );
					?>
				</p>

				<div class="gk-gravityactions-modal-action-button">
					<?php echo $cancel_button; ?>
				</div>

			</div>

		</div>

	</div>
	<?php $this->render( 'components/footer', [
		'total_steps'      => false,
		'left_navigation'  => null,
		'right_navigation' => null,
	] ); ?>
</form>