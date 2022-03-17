<?php
$entries_selected = '<span class="gk-gravityactions-modal-header-subtitle-entries-selected">' . count( $entries ) . '</span>';
?>

<span class="gk-gravityactions-modal-header-subtitle-form">
	<?php esc_html_e( 'Form: ', 'gk-gravityactions' ); ?>
	<span class="gk-gravityactions-modal-header-subtitle-form-name">
		<?php echo esc_html( $form['title'] ); ?>
	</span>
</span>

<span class="gk-gravityactions-modal-header-subtitle-entries">
	<?php printf( esc_html( _n( '%1$s entry selected', '%1$s entries selected', count( $entries ), 'gk-gravityactions' ) ), $entries_selected ); ?>
</span>

