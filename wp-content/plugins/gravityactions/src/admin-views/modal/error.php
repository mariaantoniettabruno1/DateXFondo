<?php
/**
 *
 *
 * @var int             $form_id            Which form we are dealing with.
 * @var array           $entries            Which entries we are modifying.
 * @var array           $selected_fields    Which fields were selected.
 * @var string          $view               Which view are in.
 * @var mixed           $form               Form settings.
 * @var WP_Error        $error              Error that occurred.
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
	false
);
?>
<form
	class="gk-gravityactions"
	data-js="gk-gravityactions-modal-form"
>
	<?php $this->render( 'components/header' ); ?>

	<div class="gk-gravityactions-modal-body">

		<div class="gk-gravityactions-modal-content">

			<div class="gk-gravityactions-modal-column gk-gravityactions-modal-column-center gk-gravityactions-modal-column-full-width">

				<?php var_dump( $error ); ?>

			</div>

		</div>

	</div>
	<?php $this->render( 'components/footer', [
		'total_steps'      => false,
		'left_navigation'  => $back_button,
		'right_navigation' => null,
	] ); ?>
</form>
