<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function kasutan_is_account_child_page() {
	$parent_id=wp_get_post_parent_id(get_the_ID());
	$account_page_id=kasutan_get_page_ID('my_account');

	return $account_page_id==$parent_id;
}

function kasutan_is_account_page(){
	$account_page_id=kasutan_get_page_ID('my_account');
	return $account_page_id==get_the_ID();
}

add_shortcode('my-account-links','kasutan_my_account_links');
function kasutan_my_account_links() {
	ob_start();
		if(has_nav_menu( 'my-account-links' )) {
			wp_nav_menu( array( 'theme_location' => 'my-account-links', 'menu_id' => 'menu-my-account-links', 'container'=>false, 'menu_class' => 'menu-my-account-links' ) );
		}
	return ob_get_clean();
}

add_shortcode( 'change-password', 'kasutan_change_password');
function kasutan_change_password() {
	ob_start();
	if(function_exists('pmpro_change_password_form')) {
		echo '<div class="change-password">';
		echo '<h2 class="custom">My password</h2>';
		pmpro_change_password_form();
		echo '</div>';
	}
	return ob_get_clean();
}

//Ajouter une colonne au tableau My invoices avec le lien vers la facture PDF -> imiter le shortode my account shortcodes\pmpro_account.php (ligne 409 et suivantes)

add_shortcode( 'my-invoices', 'kasutan_my_invoices');
function kasutan_my_invoices() {
	ob_start();

	if(function_exists('pmpro_get_element_class')) {
		echo '<div id="pmpro_account-invoices">';
		global $wpdb, $current_user;
		$invoices = $wpdb->get_results("SELECT *, UNIX_TIMESTAMP(CONVERT_TZ(timestamp, '+00:00', @@global.time_zone)) as timestamp FROM $wpdb->pmpro_membership_orders WHERE user_id = '$current_user->ID' AND status NOT IN('review', 'token', 'error') ORDER BY timestamp DESC LIMIT 6");

		?>
		<div id="pmpro_account-invoices" class="<?php echo pmpro_get_element_class( 'pmpro_box', 'pmpro_account-invoices' ); ?>">

			<h3>My invoices</h3>

			<table class="<?php echo pmpro_get_element_class( 'pmpro_table' ); ?>" width="100%" cellpadding="0" cellspacing="0" border="0">

				<thead>

					<tr>

						<th><?php _e("Date", 'paid-memberships-pro' ); ?></th>

						<th><?php _e("Amount", 'paid-memberships-pro' ); ?></th>

						<th><?php _e("Status", 'paid-memberships-pro'); ?></th>

						<th>Download</th>

					</tr>

				</thead>

				<tbody>

				<?php

					$count = 0;

					foreach($invoices as $invoice)

					{

						if($count++ > 4)

							break;



						//get an member order object

						$invoice_id = $invoice->id;

						$invoice = new MemberOrder;

						$invoice->getMemberOrderByID($invoice_id);

						$invoice->getMembershipLevel();



						if ( in_array( $invoice->status, array( '', 'success', 'cancelled' ) ) ) {

							$display_status = __( 'Paid', 'paid-memberships-pro' );

						} elseif ( $invoice->status == 'pending' ) {

							// Some Add Ons set status to pending.

							$display_status = __( 'Pending', 'paid-memberships-pro' );

						} elseif ( $invoice->status == 'refunded' ) {

							$display_status = __( 'Refunded', 'paid-memberships-pro' );

						}
						
						$order_code = $invoice->code;
						$order_data = pmpropdf_get_order_by_code( $order_code );
						$download_url='#';
						if ( ! empty( $order_data ) ) {	
							if ( $current_user->ID === intval( $order_data[0]->user_id ) || current_user_can( 'manage_options' ) ){
								if( file_exists( pmpropdf_get_invoice_directory_or_url() . pmpropdf_generate_invoice_name( $order_code ) ) ) {
									$invoice_name = pmpropdf_generate_invoice_name( $order_code );
									$download_url = esc_url( pmpropdf_get_invoice_directory_or_url( true ) . $invoice_name );
									$access_key = pmpropdf_get_rewrite_token();

									$download_url .= "?access=$access_key";
								}
							}
						}
						?>

						<tr id="pmpro_account-invoice-<?php echo $invoice->code; ?>">

							<td><?php echo date_i18n(get_option("date_format"), $invoice->getTimestamp())?></a></td>

							<td><?php echo pmpro_escape_price( pmpro_formatPrice($invoice->total) ); ?></td>

							<td><?php echo $display_status; ?></td>

							<td><?php printf('<a href="%s" class="download">Invoice [PDF]</a>',$download_url); ?></td>

						</tr>

						<?php

					}

				?>

				</tbody>

			</table>

			<?php if($count == 6) { ?>

				<div class="<?php echo pmpro_get_element_class( 'pmpro_actionlinks' ); ?>"><a id="pmpro_actionlink-invoices" href="<?php echo pmpro_url("invoice"); ?>"><?php _e("View All Invoices", 'paid-memberships-pro' );?></a></div>

			<?php } ?>

		</div> <!-- end pmpro_account-invoices -->
		</div>
		<?php
	}
	return ob_get_clean();
}