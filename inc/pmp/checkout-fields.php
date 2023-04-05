<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Simplify account creation on checkout page.
*
*/
add_filter("pmpro_checkout_confirm_password", function() {
	return false;
});
add_filter("pmpro_checkout_confirm_email",  function() {
	return false;
});
add_filter("pmpro_default_country",  function() {
	return false;
});

add_filter( "pmpro_required_user_fields", 'kasutan_pmpro_required_user_fields_remove_username' );
function kasutan_pmpro_required_user_fields_remove_username($fields) {
	unset( $fields['username'] );
	return $fields;
}

add_filter( "pmpro_required_billing_fields", 'kasutan_pmpro_required_billing_fields_remove_state' );
function kasutan_pmpro_required_billing_fields_remove_state($fields) {
	unset( $fields['bstate'] );
	return $fields;
}


add_filter( "pmpro_checkout_new_user_array", "kasutan_new_user_email_as_username" );
function kasutan_new_user_email_as_username($new_user_array) {
	$new_user_array["user_login"]=$new_user_array["user_email"];
	return $new_user_array;
}

/*
* Register Helper fields for Company and TVA number.
*
*/

// We have to put everything in a function called on init, so we are sure Register Helper is loaded.
add_action( 'init', 'kasutan_pmprorh_init' );
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
			'label'		=> esc_html__('Company','the-source'),		// custom field label
			'size'		=> 30,				// input size
			'class'		=> 'company',		// custom class
			'profile'	=> true,			// show in user profile
			'required'	=> false,			// make this field required
			'memberslistcsv' => true,
			'showrequired' => true,
			'html_attributes' => array('placeholder' => esc_html__('Company','the-source') )
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

add_action( 'init', 'kasutan_pmprorh_init_canal_fields' );
function kasutan_pmprorh_init_canal_fields() {
	// Don't break if Register Helper is not loaded.
	if ( ! function_exists( 'pmprorh_add_registration_field' ) ) {
		return false;
	}

	// Define the fields.
	$fields = array();
	

	$fields[] = new PMProRH_Field(
		'zs-canal',							// input name, will also be used as meta key
		'select',								// type of field
		array(
			'label'		=> esc_html__('How did you hear about The Source?','the-source'),		// custom field label
			'size'		=> 30,				// input size
			'class'		=> 'canal',		// custom class
			'profile'	=> 'admin',			// show the field on the profile page to admins only + on checkout
			'required'	=> false,			
			'placehoder' => '',
			'options' =>array("null"=> esc_html__('How did you hear about The Source?','the-source'),"social-media" => esc_html__('Social media','the-source'), "online-search"=>esc_html__('Online search','the-source'), "print-advertising"=>esc_html__("Print advertising",'the-source'),'newsletter'=>esc_html__('Newsletter','the-source'),'word-of-mouth'=>esc_html__('Word-of-mouth','the-source'),'other'=>esc_html__('Other:','the-source')),
			'memberslistcsv' => true,
			'html_attributes' => array( ),
			'hint' => ''
		)
	);

	$fields[] = new PMProRH_Field(
		'zs-canal-other',							// input name, will also be used as meta key
		'text',								// type of field
		array(
			'label'		=> esc_html__('Other:','the-source'),		// custom field label
			'size'		=> 30,				// input size
			'class'		=> 'canal-other',		// custom class
			'profile'	=> 'admin',			// show the field on the profile page to admins only + on checkout
			'required'	=> false,			
			'placehoder' => '',
			'memberslistcsv' => true,
			'html_attributes' => array('placeholder' => esc_html__('Other...','the-source')),
			'hint' => ''
		)
	);
	

	// Add the fields inside the checkout page.
	foreach ( $fields as $field ) {
		pmprorh_add_registration_field(
			'after_billing_fields',				// location on checkout page
			$field							// PMProRH_Field object
		);
	}

	// That's it. See the PMPro Register Helper readme for more information and examples.
}

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
	$fields[] = new PMProRH_Field("pmpro_baddress1", "text", array("label"=>esc_html__('Address','the-source'), "size"=>40, "profile"=>true, "required"=>false));
	$fields[] = new PMProRH_Field("pmpro_bcity", "text", array("label"=>esc_html__('City','the-source'), "size"=>40, "profile"=>true, "required"=>false));
	$fields[] = new PMProRH_Field("pmpro_bzipcode", "text", array("label"=>esc_html__('Zip Code','the-source'), "size"=>10, "profile"=>true, "required"=>false));
	$fields[] = new PMProRH_Field("pmpro_bcountry", "select", array("label"=>esc_html__('Country','the-source'), "profile"=>true, "required"=>false, "options"=>array_merge(array(""=>"Country"), $pmpro_countries)));
	$fields[] = new PMProRH_Field("pmpro_bphone", "text", array("label"=>esc_html__('Phone number','the-source'), "size"=>40, "profile"=>true, "required"=>false));	
	
	//add the fields into a new checkout_boxes are of the checkout page
	foreach($fields as $field)
		pmprorh_add_registration_field("profile", $field);
}

/*
* Add password requirements adapté de l'addon Require strong password
* https://www.paidmembershipspro.com/add-ons/require-strong-passwords/
*/
add_filter( 'pmpro_registration_checks', 'kasutan_strong_password_check' );
function kasutan_strong_password_check( $pmpro_continue_registration) {

	// Don't load this script at all if user is logged in.
	if ( is_user_logged_in() ) {
		return $pmpro_continue_registration;
	}

	//only bother checking if there are no errors so far
	if( ! $pmpro_continue_registration )
		return $pmpro_continue_registration;

	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];

	// no password (existing user is checking out)
	if( empty( $password ) )
		return $pmpro_continue_registration;


	// Don't load this script at all if user is logged in.
	if ( is_user_logged_in() ) {
		return $pmpro_continue_registration;
	}

	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];

	// no password (existing user is checking out)
	if( empty( $password ) )
		return $pmpro_continue_registration;

	// Run a custom check
	return kasutan_strong_password_custom_checker( $password, $username );
	
}

function kasutan_strong_password_custom_checker( $password, $username ) {

	$pass_ok = true;
	$msg=array();

	// Check for length (8 characters)
	if ( strlen( $password ) < 12 ) {
		$msg[]=esc_html__('Your password must be at least 12 characters long.','the-source');
		$pass_ok=false;
	}

	// Check for username match	
	if ( strpos( $password, $username ) !== false ) {
		$msg[]=esc_html__('Your password must not contain your username.','the-source');
		$pass_ok=false;
	}

	// Check for lowercase
	if ( ! preg_match( '/[a-z]/', $password ) ) {
		$msg[]=esc_html__('Your password must contain at least 1 lowercase letter.','the-source');
		$pass_ok=false;
	}

	// Check for uppercase
	if ( ! preg_match( '/[A-Z]/', $password ) ) {
		$msg[]=esc_html__('Your password must contain at least 1 uppercase letter.','the-source');
		$pass_ok=false;
	}

	// Check for numbers
	if ( ! preg_match( '/[0-9]/', $password ) ) {
		$msg[]=esc_html__('Your password must contain at least 1 number.','the-source');
		$pass_ok=false;
	}

	// Check for special characters
	if ( ! preg_match( '/[\W]/', $password ) ) {
		$msg[]=esc_html__('Your password must contain at least 1 special character.','the-source');
		$pass_ok=false;
	}

	// Prepare error message (with all errors) if necessary.
	if(!$pass_ok) {
		pmpro_setMessage( implode('</br>',$msg), 'pmpro_error' );
	}
	return $pass_ok;
}

// Show hint after password field. Hook as early as possible in case there are other uses of filter
add_filter( 'pmpro_checkout_after_password', 'kasutan_checkout_after_password', 1 );
function kasutan_checkout_after_password() {
	printf('<small id="pmprosp-password-notice">%s</small>',__('The password should be at least twelve characters long, contain upper and lower case letters, numbers, and symbols like !@#$;%^&*.','the-source'));
}

//SI NECESSAIRE : bloquer la validation si le numéro de téléphone n'est pas au bon format ?

