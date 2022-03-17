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

$fields_available      = '<span class="gv-bulk-edit-all-fields-counter">' . count( $form['fields'] ) . '</span>';
$count_selected_fields = count( $selected_fields );
?>

<form
	data-js="gk-gravityactions-modal-form"
	id="gk-gravityactions-edit-step-1"
>
	<?php $this->render( 'components/modal-fields', [ 'bulk_action' => EditAction::get_key() ] ); ?>
	<?php $this->render( 'components/header' ); ?>
	<?php $this->render( 'edit/fields-hidden' ); ?>

	<div class="gk-gravityactions-modal-body">

		<div class="gv-bulk-edit-step gv-bulk-edit-step-1">

			<div class="gv-bulk-edit-step-help-side">

				<img width="288" height="236" src="<?php echo Assets::get_url( 'src/assets/images/edit_step_1_character.svg' ); ?>" alt=""/>

				<span class="gk-gravityactions-modal-help-title">
					<?php esc_html_e( 'Select fields to edit', 'gk-gravityactions' ); ?>
				</span>

				<span class="gk-gravityactions-modal-help-subtitle">
					<?php esc_html_e( 'Let’s select some fields from these entries to edit.', 'gk-gravityactions' ); ?>
				</span>

				<div class="gk-gravityactions-modal-help-link">
					<img src="<?php echo Assets::get_url( 'src/assets/images/help_question.svg' ); ?>" alt="Help"/>
					<a href='https://docs.gravityview.co/article/801-bulk-edit-step-what-are-these-fields' data-beacon-article='615c5acde5648623c88e19b5' target='_blank' rel='noopener noreferrer'><?php esc_html_e( 'What are these fields?', 'gk-gravityactions' ); ?></a>
				</div>

			</div>

			<div class="gv-bulk-edit-step-content-side">

				<div class="gv-bulk-edit-step-content-side-header">
					<div class="gv-bulk-edit-step-1-fields-title">
						<?php printf( _n( 'Available Field (%1$s)', 'Available Fields (%1$s)', count( $form['fields'] ), 'gk-gravityactions' ), $fields_available ); ?>
					</div>

					<div
						class="gv-bulk-edit-selected-fields-counter gk-gravityactions-edit-selected-field-count"
						data-count-selected="<?php echo esc_attr( $count_selected_fields ); ?>"
						data-none-selected="<?php esc_attr_e( 'None selected', 'gk-gravityactions' ); ?>"
						data-one-selected="<?php esc_attr_e( 'One selected', 'gk-gravityactions' ); ?>"
						data-multiple-selected="<?php esc_attr_e( '%s selected', 'gk-gravityactions' ); ?>"
					>
						<?php if ( 0 === $count_selected_fields ) : ?>
							<?php esc_html_e( 'None selected', 'gk-gravityactions' ); ?>
						<?php elseif ( 1 === $count_selected_fields ) : ?>
							<?php esc_html_e( 'One selected', 'gk-gravityactions' ); ?>
						<?php else : ?>
							<?php echo esc_html( sprintf( __( '%1$d selected', 'gk-gravityactions' ), $count_selected_fields ) ); ?>
						<?php endif; ?>
					</div>
				</div>

				<div class="gv-bulk-edit-search-box">
					<input type="text" placeholder="<?php esc_attr_e( 'Search Fields...', 'gk-gravityactions' ); ?>" class="gv-bulk-edit-search"/>

					<img class="gv-bulk-edit-search-icon" src="<?php echo Assets::get_url( 'src/assets/images/search.svg' ); ?>" alt="<?php esc_attr_e( 'Search', 'gk-gravityactions' ); ?>"/>
				</div>

				<ul class="gv-bulk-edit-step-1-fields">
					<?php foreach ( $form['fields'] as $field ) : ?>
						<?php
						if ( ! EditAction::is_valid_field_type( $field ) ) {
							continue;
						}
						?>
						<li>
							<label
								class="gv-bulk-edit-step-1-field"
								data-filter-name="<?php esc_attr( mb_strtolower( $field->label ) ); ?>"
								for="gk-gravityactions-edit-field-<?php echo esc_attr( $field->id ); ?>"
							>
								<input
									class="gk-gravityactions-edit-selected-field"
									id="gk-gravityactions-edit-field-<?php echo esc_attr( $field->id ); ?>"
									type="checkbox"
									value="<?php echo esc_attr( $field->id ); ?>"
									<?php checked( in_array( $field->id, $selected_fields ) ); ?>
								/>
								<span
									class="gk-gravityactions-edit-field-name"
								>
									<?php echo esc_html( $field->label ); ?>
								</span>
							</label>
						</li>
					<?php endforeach; ?>
				</ul>

				<?php foreach ( $form['fields'] as $field ) : ?>
					<input
						class="gk-gravityactions-hidden-selected-fields"
						type="hidden"
						name="selected_fields"
						value="<?php echo esc_attr( $field->id ); ?>"
						<?php disabled( ! in_array( $field->id, $selected_fields ) ); ?>
					/>
				<?php endforeach; ?>

			</div>

		</div>
	</div>

	<?php $this->render( 'components/footer', [
		'current_step'     => 0,
		'total_steps'      => 3,
		'left_navigation'  => '<span class="featherlight-close">' . esc_html__( 'Close', 'gk-gravityactions' ) . '</span>',
		'right_navigation' => get_submit_button( __( 'Next', 'gk-gravityactions' ), 'primary', 'submit', false, [
			'data-js'         => 'gk-gravityactions-modal-submit',
			'data-modal-view' => 'edit/step-2',
		] ),
	] ); ?>

</form>