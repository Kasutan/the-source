<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* This adds {{vat_number}}, {{vat_rate}}, {{vat_country}} to PDF templates.
* Requires VAT Add On installed and active - https://www.paidmembershipspro.com/add-ons/vat-tax/
* https://gist.github.com/andrewlimaza/2451fa80a89eaaf857ca42ee08bd473c
*/
 
function kasutan_pmpro_pdf_vat_add_on_variables( $data_array, $user, $order_data ) {

	$order_notes=$order_data->notes;
	error_log('order notes '.$order_notes);
	$data_array['{{notes}}'] = $order_data->notes;

	$user_id=$user->ID;
	$user_company=get_user_meta($user_id,'zs-company',true);
	error_log('company : '.$user_company);
	$data_array['{{company}}'] =$user_company;

	if ( function_exists( 'pmprovat_iso2vat' ) ) {
		$vat_number=pmpro_getMatches( "/{EU_VAT_NUMBER:([^}]*)}/", $order_data->notes, true );
		$vat_country=pmprovat_iso2vat(pmpro_getMatches( "/{EU_VAT_COUNTRY:([^}]*)}/", $order_data->notes, true ) );
		if($vat_number && $vat_country) {
			$data_array['{{vat_number}}'] = 'VAT '.$vat_country.$vat_number;
			$data_array['{{vat_country}}']=$vat_country;
		} else {
			$data_array['{{vat_number}}'] = '';
			$vat_country='';
		}
		
		$rate=pmpro_getMatches( "/{EU_VAT_TAX_RATE:([^}]*)}/", $order_data->notes, true );
		$rate_display=100*$rate.'%';
		$data_array['{{vat_rate}}'] = $rate_display;
	}
	return $data_array;
}
add_filter( 'pmpro_pdf_invoice_custom_variables', 'kasutan_pmpro_pdf_vat_add_on_variables', 10, 3 );