<?php 

if ( ! defined( 'ABSPATH' ) ) exit;

//https://www.paidmembershipspro.com/send-additional-user-information-fields-mailchimp/

/*
* Creates a new merge field in Mailchimp if one doesn't already exist.
*/
function kasutan_pmpro_mailchimp_merge_fields( $merge_fields ) {
	// Adds a "COMPANY" merge field to Mailchimp.
	$merge_fields[] = array('name' => 'LANG', 'type' => 'text');
	return $merge_fields;
}
add_filter( 'pmpro_mailchimp_merge_fields', 'kasutan_pmpro_mailchimp_merge_fields' );


/**
* Sync additional user fields to Mailchimp.
* You must create the fields in Mailchimp first.
* Or, you can use the `pmpro_mailchimp_merge_fields` filter to create them through the API.
*
*
*/
function kasutan_pmpro_mailchimp_listsubscribe_fields( $fields, $user ) {
	$user_id=$user->ID;
	$lng=get_user_meta( $user_id, 'zs-lang', true );
	if (!empty($lng)) {
		$new_fields =  array(
			"LANG" => $lng, 
		);

		$fields = array_merge( $fields, $new_fields );
	}
	
	return $fields;
}
add_action( 'pmpro_mailchimp_listsubscribe_fields', 'kasutan_pmpro_mailchimp_listsubscribe_fields', 10, 2 );

/**
* Tell PMPro MailChimp to always synchronize user profile updates. By default it only synchronizes if the user's email has changed (optional).
* Requires PMPro Mailchimp v2.0.3 or higher.
*/
add_filter( 'pmpromc_profile_update', '__return_true' );