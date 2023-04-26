<?php
/**
* Example of applying Canadian Tax to Paid Memberships Pro Checkouts. Adjust the code to match the requirements needed. Adapté pour la Suisse avec taux modifiable en BO
* https://www.paidmembershipspro.com/non-us-taxes-paid-memberships-pro/
*/

if ( ! defined( 'ABSPATH' ) ) exit;


// Set the tax  for all swiss customers.
function customtax_pmpro_tax( $tax, $values, $order ) {
	$taxeCH=false;
	if(function_exists('get_field')) {
		$taxeCH=esc_attr(get_field('zs_tax_ch','option'));
	}
	if($taxeCH) {
		$tax = round( (float) $values[ price ] * $taxeCH / 100, 2 );
	}
	return $tax;
}

//Add an information about the tax
function customtax_pmpro_level_cost_text( $cost, $level ) {
	$is_checkout=false;
	
	// only applicable for levels > 1
	$taxeCH=false;
	if(function_exists('get_field')) {
		$taxeCH=esc_attr(get_field('zs_tax_ch','option'));
		$checkout_page_id=esc_attr(get_field('zs_page_checkout','option'));
		if(get_the_ID()==$checkout_page_id) {
			$is_checkout=true;
		}
	}



	if($taxeCH) {
		$taxeCH.='%';
		$info=sprintf(esc_html__('Members in Switzerland will be charged a %s tax.','the-source'),$taxeCH);
		$cost.=sprintf(' <span id="info-tax-CF">%s</span>',$info);
	}

	//Adapter format (quelles que soient les taxes, mais seulement pour la page checkout)
	if($is_checkout) {
		$cost=str_replace('CHF','',$cost);
		$cost=str_replace('.00','',$cost);
		$cost=str_replace('/Year.','CHF<sup>/year</sup>.',$cost);
		$cost=str_replace('par année.','CHF<sup>/an</sup>.',$cost);
	}
	
 
	return $cost;
}
add_filter( 'pmpro_level_cost_text', 'customtax_pmpro_level_cost_text', 20, 2 );

// update tax calculation if buyer is in Switzerland
function customtax_region_tax_check() {
	
	// check  and country
	if ( ! empty( $_REQUEST['bcountry'] ) ) {
		$bcountry = trim( strtolower( $_REQUEST['bcountry'] ) );
		if ( $bcountry == 'ch' ) {
			// billing address is Switzerland
			add_filter( 'pmpro_tax', 'customtax_pmpro_tax', 10, 3 );
		}
	}
	
}
add_action( 'init', 'customtax_region_tax_check' );