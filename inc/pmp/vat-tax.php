<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Enqueue script for displaying VAT extra info on checkout page.
*
*/
remove_filter("pmpro_level_cost_text", "pmprovat_pmpro_level_cost_text", 10);


/**
* Show VAT tax info below level cost text.
*/
function kasutan_pmpro_level_cost_text($cost, $level)
{
	global $pmpro_pages;
	if( is_page( $pmpro_pages["checkout"] ) && !pmpro_isLevelFree($level) )
		$cost .= " " . '<span class="js-vat-eu-message">Members in the EU will be charged a VAT tax.</span>';
		$cost .= '<span class="js-vat-eu-rate"></span>';

	return $cost;
}
add_filter("pmpro_level_cost_text", "kasutan_pmpro_level_cost_text", 10, 2);

add_action( 'wp_enqueue_scripts', 'kasutan_vat_script' );
function kasutan_vat_script() {
	if(!function_exists('pmpro_is_checkout') || !pmpro_is_checkout()) {
		//not checkout page
		error_log('not checkout page');
		return;
	}

	wp_enqueue_script(
		'the-source-vat',
		get_stylesheet_directory_uri(  ) . '/js/vat-tax.js',
		array( 'jquery' ),
		'1.0'
	);

	$seller_country=get_option('pmprovt_seller_country');
	if(empty($seller_country)) {
		$seller_country="PT"; 
	}
	global $pmpro_vat_by_country;

	$price=850.00; //TODO dynamic price
	//pmpro_getLevelCost($temp_level, false, true)
	//apply_filters( 'pmpro_level_cost_text', $r, $level, $tags, $short )

	wp_localize_script(
		'the-source-vat',
		'VatVars',
		array(
		//	'ajax_url' => admin_url( 'admin-ajax.php' ),
		//	'nonce' => wp_create_nonce('the_source_vat_nonce'),
			'seller_country' => $seller_country,
			'vat_rates' => json_encode($pmpro_vat_by_country),
			'price' => $price
		)
	);
}