<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function kasutan_is_premium_member() {
	if(function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel('Premium')) {
		return true;
	}
	return false;
}

function kasutan_is_free_member() {
	if(function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel('Free')) {
		return true;
	}
	return false;
}

/*
	Redirect to homepage if user is logged out or not a member
	https://www.paidmembershipspro.com/lock-down-everything-but-homepage-for-non-users/
*/
add_action('template_redirect', 'kasutan_lock_content');

function kasutan_lock_content() {
	$redirect=true;

	//Do not redirect on local
	if(esc_html($_SERVER['HTTP_HOST'])==='thesource.local') {
		$redirect=false;
	};

	if(function_exists('pmpro_getOption') && function_exists('get_field')) :

		$pmpro_pages = array(pmpro_getOption('billing_page_id'), pmpro_getOption('account_page_id'), pmpro_getOption('levels_page_id'), pmpro_getOption('checkout_page_id'), pmpro_getOption('confirmation_page_id'));

		$public_pages=get_field('zs_public_pages','options');
		$free_page=get_field('zs_free_page','options');

		if(is_home() || is_page($pmpro_pages) || is_page($public_pages)) {
			$redirect=false;
		} else if(kasutan_is_free_member() && is_page($free_page)) {
			$redirect=false;
		} else if(kasutan_is_premium_member()) {
			$redirect=false;
		}
	
	endif;
	
	if($redirect) {
		wp_redirect(home_url());
		exit;
	}
}

