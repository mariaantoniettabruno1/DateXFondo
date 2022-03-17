<?php
/**
 *
 *
 * @var int    $form_id         Which form we are dealing with.
 * @var array  $entries         Which entries we are modifying.
 * @var array  $selected_fields Which fields were selected.
 * @var string $view            Which view are in.
 * @var string $bulk_action           Which bulk action these fields are for.
 * @var mixed  $form            Form settings.
 */

use GravityKit\GravityActions\Admin;
?>
<input type="hidden" name="form_id" value="<?php echo esc_attr( $form_id ); ?>" />
<input type="hidden" name="bulk_action" value="<?php echo esc_attr( $bulk_action ); ?>" />
<?php foreach ( $entries as $entry_id ) : ?>
	<input type="hidden" name="entries" value="<?php echo esc_attr( $entry_id ); ?>" />
<?php endforeach; ?>
<?php wp_nonce_field( Admin::$modal_nonce_action, Admin::$modal_nonce_name ); ?>
