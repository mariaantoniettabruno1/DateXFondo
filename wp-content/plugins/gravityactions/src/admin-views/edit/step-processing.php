<?php
/**
 *
 *
 * @var int             $form_id            Which form we are dealing with.
 * @var array           $entries            Which entries we are modifying.
 * @var array           $selected_fields    Which fields were selected.
 * @var string          $view               Which view are in.
 * @var mixed           $form               Form settings.
 * @var ActionInterface $bulk_action_object Which bulk action rendered this modal.
 */

use \GravityKit\GravityActions\Assets;
use \GravityKit\GravityActions\Actions\EditAction;
use GravityKit\GravityActions\Admin;
use \GravityKit\GravityActions\Actions\ActionInterface;

$back_button   = get_submit_button(
	__( 'Close', 'gk-gravityactions' ),
	'secondary featherlight-close',
	'submit',
	false,
	[
		'data-js'         => 'gk-gravityactions-modal-submit',
		'data-modal-view' => 'edit/step-3',
	]
);
$cancel_button = get_submit_button(
	__( 'Cancel', 'gk-gravityactions' ),
	'secondary',
	'submit',
	false,
	[
		'data-js'         => 'gk-gravityactions-modal-submit',
		'data-modal-view' => 'edit/step-complete',
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

		<div class="gk-gravityactions-modal-content">

			<div class="gk-gravityactions-modal-column gk-gravityactions-modal-column-center gk-gravityactions-modal-column-full-width">

				<img width="337" height="161" src="<?php echo Assets::get_url( 'src/assets/images/floaty-processing.svg' ); ?>" alt=""/>

				<span class="gk-gravityactions-modal-help-title">
					<?php esc_html_e( 'Processing your updates...', 'gk-gravityactions' ); ?>
				</span>

				<p class="gk-gravityactions-modal-help-subtitle">
					<?php _e( 'We\'re working hard on making those changes.<br/>Should only take a minute', 'gk-gravityactions' ); ?>
				</p>

				<script>
					setTimeout(
						GravityKit.GravityActions.Actions.Edit.onCompleteUpdating,
						3000,
						<?php echo $form_id; ?>,
						<?php echo wp_json_encode( $entries ); ?>,
						'<?php echo $bulk_action_object::get_key(); ?>',
						'<?php echo wp_create_nonce( Admin::$modal_nonce_action ); ?>',
						'<?php echo Admin::$modal_nonce_name; ?>'
					);
				</script>

			</div>

		</div>

	</div>
	<?php $this->render( 'components/footer', [
		'total_steps'      => false,
		'left_navigation'  => $back_button,
		'right_navigation' => null,
	] ); ?>
</form>