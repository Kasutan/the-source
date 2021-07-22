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