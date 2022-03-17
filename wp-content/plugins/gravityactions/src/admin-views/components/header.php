<?php use \GravityKit\GravityActions\Actions\ActionInterface; ?>
<div class="gk-gravityactions-modal-header">
	<div class="gk-gravityactions-modal-header-character"></div>

	<div class="gk-gravityactions-modal-header-title">
		<?php echo wp_kses_post( $modal_title ); ?>

		<?php if ( empty( $modal_subtitle ) && $this->get( 'bulk_action_object' ) instanceof ActionInterface ) : ?>
			<div class="gk-gravityactions-modal-header-subtitle">
				<?php echo $bulk_action_object->get_subtitle( $this ); ?>
			</div>
		<?php else: ?>
			<?php echo wp_kses_post( $modal_subtitle ); ?>
		<?php endif; ?>
	</div>
</div>