<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This adds {{vat_number}}, {{vat_rate}}, {{vat_country}} to PDF templates.
*/
 
function kasutan_pmpro_pdf_custom_variables( $data_array, $user, $order_data ) {
	error_log('kasutan_pmpro_pdf_custom_variables');
	//$order_notes=$order_data->notes;

	$data_array['{{notes}}'] = $order_data->notes;

	$user_id=$user->ID;
	$user_company=get_user_meta($user_id,'zs-company',true);
	$country=trim( strtolower($order_data->billing_country));
	$data_array['{{company}}'] =$user_company;

	if ( ! empty( $order_data->billing_name ) ) {
		$data_array['{{name}}'] = $order_data->billing_name;

		$billing_details = $order_data->billing_name . "<br>";
		$billing_details .= $order_data->billing_street . "<br>";
		$billing_details .= $order_data->billing_zip . " " . $order_data->billing_city . ", " . $order_data->billing_country . "<br>";
		$billing_details .= $order_data->billing_phone;
		error_log($billing_details);
		$data_array['{{address}}']=$billing_details;
	}

	$date=$data_array['{{invoice_date}}'];
	$date=date('d/m/Y',strtotime($date));
	$data_array['{{invoice_date}}']=$date;

	$taxe_label=esc_attr(get_option('options_zs_tax_label'));
	error_log('taxe_label');
	error_log($taxe_label);
	if($country=='ch' && $taxe_label) {
		$data_array['{{tax_rate}}'] = $taxe_label;
	} else {
		$data_array['{{tax_rate}}'] = 'Franchise de TVA';
	}

	$tax=$data_array['{{tax}}'];
	if($tax=='CHF 0.00') {
		$data_array['{{tax}}']='';
	}



	return $data_array;
}
add_filter( 'pmpro_pdf_invoice_custom_variables', 'kasutan_pmpro_pdf_custom_variables', 10, 3 );