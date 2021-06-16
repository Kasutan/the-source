<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Simplify account creation on checkout page.
*
*/
add_filter("checkout_confirm_password", function() {return false;});
add_filter("checkout_confirm_email", function() {return false;});
add_filter("default_country", function() {return "";});

add_filter( "required_user_fields", 'kasutan_pmpro_required_user_fields_remove_username' );
function kasutan_pmpro_required_user_fields_remove_username($fields) {
	unset( $fields['username'] );
	return $fields;
}


add_filter( "checkout_new_user_array", "kasutan_new_user_email_as_username" );
function kasutan_new_user_email_as_username($new_user_array) {
	$new_user_array["user_login"]=$new_user_array["user_email"];
	return $new_user_array;
}

/*
* Register Helper fields for Company and TVA number.
*
*/

// We have to put everything in a function called on init, so we are sure Register Helper is loaded.
function kasutan_pmprorh_init() {
	// Don't break if Register Helper is not loaded.
	if ( ! function_exists( 'pmprorh_add_registration_field' ) ) {
		return false;
	}

	// Define the fields.
	$fields = array();
	$fields[] = new PMProRH_Field(
		'zs-company',							// input name, will also be used as meta key
		'text',								// type of field
		array(
			'label'		=> 'Company',		// custom field label
			'size'		=> 30,				// input size
			'class'		=> 'company',		// custom class
			'profile'	=> true,			// show in user profile
			'required'	=> true,			// make this field required
			'memberslistcsv' => true,
			'showrequired' => true,
			'html_attributes' => array('placeholder' => 'Company' )
		)
	);
	$fields[] = new PMProRH_Field(
		'zs-vat-number',							// input name, will also be used as meta key
		'text',								// type of field
		array(
			'label'		=> 'VAT number',		// custom field label
			'size'		=> 30,				// input size
			'class'		=> 'vat-number',		// custom class
			'profile'	=> 'admin',			// show the field on the profile page to admins only + on checkout
			'required'	=> false,			
			'placehoder' => 'VAT number',
			'memberslistcsv' => true,
			'html_attributes' => array('placeholder' => 'VAT number' ),
			'hint' => 'Without country code, numbers only '
		)
	);
	

	// Add the fields inside the checkout page.
	foreach ( $fields as $field ) {
		pmprorh_add_registration_field(
			'after_username',				// location on checkout page
			$field							// PMProRH_Field object
		);
	}

	// That's it. See the PMPro Register Helper readme for more information and examples.
}
add_action( 'init', 'kasutan_pmprorh_init' );

/*
* Add PMPro billing fields to the edit user profile page.
* https://github.com/strangerstudios/pmpro-register-helper
* Using exact same fields ids as PMPro o synchronize values
*/
add_action("init", "kasutan_add_billing_fields_to_profile");
function kasutan_add_billing_fields_to_profile()
{
	global $pmpro_countries;
	
	//check for register helper
	if(!function_exists("pmprorh_add_registration_field"))
		return;
	
	//define the fields
	$fields = array();
	$fields[] = new PMProRH_Field("pmpro_baddress1", "text", array("label"=>"Address", "size"=>40, "profile"=>true, "required"=>false));
	$fields[] = new PMProRH_Field("pmpro_bcity", "text", array("label"=>"City", "size"=>40, "profile"=>true, "required"=>false));
	$fields[] = new PMProRH_Field("pmpro_bzipcode", "text", array("label"=>"Zip Code", "size"=>10, "profile"=>true, "required"=>false));
	$fields[] = new PMProRH_Field("pmpro_bcountry", "select", array("label"=>"Country", "profile"=>true, "required"=>false, "options"=>array_merge(array(""=>"Country"), $pmpro_countries)));
	$fields[] = new PMProRH_Field("pmpro_bphone", "text", array("label"=>"Phone number", "size"=>40, "profile"=>true, "required"=>false));	
	
	//add the fields into a new checkout_boxes are of the checkout page
	foreach($fields as $field)
		pmprorh_add_registration_field("profile", $field);
}