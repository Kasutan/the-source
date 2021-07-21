<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_shortcode('my-email-preferences','kasutan_my_email_preferences');
function kasutan_my_email_preferences() {
	ob_start();
	echo 'email_preferences';
	$options=kasutan_get_email_options();
	var_dump($options);
	//TODO boucle afficher un input type checkbox pour chaque option
	// TODO helper function pour récupérer la valeur de l'option en user_meta (fonction native PMPro ?)
	return ob_get_clean();
}


/*
* Email options are stored in website settings.
*
*/
function kasutan_get_email_options() {
	if(!function_exists('get_field') || !have_rows('zs_email_options','options')) {
		return false;
	}
	return get_field('zs_email_options','options');
}


/*
* Register Helper fields for Email preferences.
*
*/

// We have to put everything in a function called on init, so we are sure Register Helper is loaded.
//add_action( 'init', 'kasutan_pmprorh_init_2' );
function kasutan_pmprorh_init_2() {
	// Don't break if Register Helper is not loaded.
	if ( ! function_exists( 'pmprorh_add_registration_field' ) ) {
		return false;
	}

	$options=kasutan_get_email_options();
	// Define the fields for each email option
	// checkbox/bool field
	// shown in admin only
	// prefixer le slug 'zs_email_'.$slug
	$fields = array();
	$fields[] = new PMProRH_Field(
		'zs-company',							// input name, will also be used as meta key
		'text',								// type of field
		array(
			'label'		=> 'Company',		// custom field label
			'size'		=> 30,				// input size
			'class'		=> 'company',		// custom class
			'profile'	=> true,			// show in user profile
			'required'	=> false,			// make this field required
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
