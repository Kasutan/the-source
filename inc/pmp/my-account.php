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