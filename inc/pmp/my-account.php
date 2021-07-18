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