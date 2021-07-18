<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function kasutan_is_account_child_page() {
	$response=false;
	$parent_id=wp_get_post_parent_id(get_the_ID());
	if(function_exists('pmpro_getOption')) {
		$account_page_id=pmpro_getOption('account_page_id');
		if($account_page_id==$parent_id) {
			$response=true;
		}
	}
	return $response;
}

function kasutan_is_account_page(){
	$response=false;
	if(function_exists('pmpro_getOption')) {
		$account_page_id=pmpro_getOption('account_page_id');
		if($account_page_id==get_the_ID()){
			$response=true;
		}
	}
	return $response;
}