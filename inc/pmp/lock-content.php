<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function kasutan_is_premium_member() {
	if(function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel(1)) {
		return true;
	}
	if(user_can(wp_get_current_user(  ),'edit_others_posts')){
		return true;
	}
	return false;
}

function kasutan_is_free_member() {
	if(function_exists('pmpro_hasMembershipLevel') && pmpro_hasMembershipLevel(2)) {
		return true;
	}
	return false;
}

function kasutan_is_member() {
	return (kasutan_is_free_member() || kasutan_is_premium_member());
}

/*
	Redirect to homepage if user is logged out or not a member
	https://www.paidmembershipspro.com/lock-down-everything-but-homepage-for-non-users/
*/
add_action('template_redirect', 'kasutan_lock_content');

function kasutan_lock_content() {
	$redirect=true;

	

	//Ne jamais rediriger la home (même si PMPro et ACF ne sont pas activés)
	if(is_home()) {
		$redirect=false;
	} else if(function_exists('pmpro_getOption') && function_exists('get_field')) {

		$pmpro_pages = array(pmpro_getOption('billing_page_id'), pmpro_getOption('account_page_id'), pmpro_getOption('levels_page_id'), pmpro_getOption('checkout_page_id'), pmpro_getOption('confirmation_page_id'),pmpro_getOption('login_page_id'));

		$public_pages=get_field('zs_public_pages','options');
		//$free_page=get_field('zs_free_page','options'); obsolète - était utilisé pour restreindre l'accès à certaines pages pour les membres avec une adhésion gratuire

		if(is_page($pmpro_pages) || is_page($public_pages)) {
			$redirect=false;
		} else if(kasutan_is_member()) {
			$redirect=false;
		} else if(is_tax()) {
			$term=get_queried_object(  );
			if(empty($term->parent)) {
				//On autorise l'accès aux pages archives des catégories parentes
				$redirect=false;
			}
		}
	
	}
	
	if($redirect) {
		wp_redirect(home_url());
		exit;
	}
}

function kasutan_paywall() {
	if(!function_exists('get_field')) {
		return;
	}
	$message=get_field('zs_paywall_message','option');
	if(empty($message)) {
		return;
	}
	echo '<section class="paywall-message">';
		if(isset($message['phrase_1']) && !empty($message['phrase_1'])) printf('<p class="phrase-1">%s</p>',$message['phrase_1']);	
		
		if(isset($message['lien']) && !empty($message['lien'])) {
			printf('<a href="%s" class="button">%s</a>',$message['lien']['url'],$message['lien']['title']);
		}

		if(isset($message['phrase_2']) && !empty($message['phrase_2'])) printf('<p class="phrase-2">%s</p>',$message['phrase_2']);	

		echo '<div class="form-wrap">';
			echo do_shortcode( '[pmpro_login]');
		echo '</div>';

	echo '</section>';

}