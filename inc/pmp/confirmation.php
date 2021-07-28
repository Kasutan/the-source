<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_shortcode('confirmation-message','kasutan_confirmation_message');
function kasutan_confirmation_message() {
	global $wpdb, $current_user, $pmpro_invoice, $pmpro_msg, $pmpro_msgt;

	$confirmation_message='';
	ob_start();

	if($pmpro_msg)
	{
	?>
		<div class="<?php echo pmpro_get_element_class( 'pmpro_message ' . $pmpro_msgt, $pmpro_msgt ); ?>"><?php echo wp_kses_post( $pmpro_msg );?></div>
	<?php
	}
	//confirmation message for this level
	$level_message = $wpdb->get_var("SELECT l.confirmation FROM $wpdb->pmpro_membership_levels l LEFT JOIN $wpdb->pmpro_memberships_users mu ON l.id = mu.membership_id WHERE mu.status = 'active' AND mu.user_id = '" . $current_user->ID . "' LIMIT 1");
	if(!empty($level_message))
		$confirmation_message.=$level_message;
	
	$confirmation_message = apply_filters("pmpro_confirmation_message", $confirmation_message, $pmpro_invoice);

	printf('<div class="confirmation-message">%s</div>',wp_kses_post( $confirmation_message ));

	return ob_get_clean();
}