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
* https://www.paidmembershipspro.com/documentation/register-helper-documentation/adding-fields/
*/

// We have to put everything in a function called on init, so we are sure Register Helper is loaded.
add_action( 'init', 'kasutan_pmprorh_init_2' );
function kasutan_pmprorh_init_2() {
	// Don't break if Register Helper is not loaded.
	if ( ! function_exists( 'pmprorh_add_registration_field' ) ) {
		return false;
	}

	$options=kasutan_get_email_options();
	// Define the fields for each email option
	$fields = array();
	foreach($options as $option) {
		$fields[] = new PMProRH_Field(
			'zs-email-'.$option['slug'],		// input name, will also be used as meta key
			'checkbox',								// type of field
			array(
				'label'		=> 'Email preference: '.$option['name'],		// custom field label
				'class'		=> 'email-option',		// custom class
				'profile'	=> 'only_admin',			// show only in admin
				'required'	=> false,			// make this field required
				'memberslistcsv' => true, //export with csv export
				'showrequired' => false
			)
		);
	}

	// Add the fields inside the checkout page.
	foreach ( $fields as $field ) {
		pmprorh_add_registration_field(
			'just_profile',				// location on checkout page
			$field							// PMProRH_Field object
		);
	}

}
