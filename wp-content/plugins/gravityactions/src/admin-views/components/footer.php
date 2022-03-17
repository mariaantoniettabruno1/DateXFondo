<?php
/**
 *
 *
 * @var int    $current_step     The current step for the navigation.
 * @var int    $total_steps      How many steps we need to render for the navigation.
 * @var string $left_navigation  The left navigation HTML.
 * @var string $right_navigation The right navigation HTML.
 */

use \GravityKit\GravityActions\Assets;
?>
<div class="gk-gravityactions-modal-footer">

	<div class="gk-gravityactions-modal-footer-button-left">
		<?php echo $left_navigation; ?>
	</div>

	<?php if ( $total_steps ) : ?>
	<div class="gk-gravityactions-modal-footer-progress">
		<?php for ( $i = 0; $i < $total_steps; $i++ ) : ?>
			<span class="gk-gravityactions-modal-footer-progress-item <?php echo ( $current_step === $i ? 'active' : false ); ?>"></span>
		<?php endfor; ?>
	</div>
	<?php endif; ?>

	<?php echo $right_navigation; // gk-gravityactions-modal-footer-button-right ?>

</div>