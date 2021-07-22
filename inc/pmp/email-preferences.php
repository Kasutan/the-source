<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_shortcode('my-email-preferences','kasutan_my_email_preferences');
function kasutan_my_email_preferences() {
	ob_start();
	$options=kasutan_get_email_options();
	//var_dump($options);
	$user_id=get_current_user_id(  );
	$user_meta=get_user_meta($user_id); //returns data fo all keys
	//var_dump($user_meta);

	echo '<formgroup class="email-preferences">';
	//boucle afficher un bouton pour chaque option
	foreach($options as $option) {
		$slug=$option['slug'];
		if(kasutan_get_email_option_for_user($slug,$user_meta)) {
			$class='checked';
			$label="Deactivate this option";
		} else {
			$class='';
			$label="Activate this option";
		}
		printf('<button class="js-toggle-option %s" data-slug="%s"  data-user="%s" title="%s">',$class,$slug,$user_id, $label);
			printf('<div class="name">%s</div><div class="picto check">%s</div><div class="picto croix">%s</div>',
				$option['name'],
				kasutan_picto(array('icon'=>'check','size'=>16)),
				kasutan_picto(array('icon'=>'close','size'=>15))
			);
		echo '</button>';
	}
	echo '</formgroup>';
	return ob_get_clean();
}


/*
* Get all email options
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
* Get Email option for current user.
*
*/
function kasutan_get_email_option_for_user($slug,$data) {
	$meta_key='zs-email-'.$slug;
	if(array_key_exists($meta_key,$data)) {
		$option=$data[$meta_key][0]; //$data est un tableau de tableaux
		if($option==1) {
			return true;
		} else {
			return false;
		}
	}
	return false;
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


/**************************************************************************
* 						Ajax update email preference
***************************************************************************/

add_action( 'wp_ajax_kasutan_update_email_preference', 'kasutan_update_email_preference' );
function kasutan_update_email_preference() {
	if ( !wp_verify_nonce($_POST['nonce'], 'thesource_nonce') ){ 
		die('Permission Denied.'); 
	}
	$user_id = sanitize_text_field($_POST['data']['user']);
	$slug = sanitize_text_field($_POST['data']['slug']);
	$value = sanitize_text_field($_POST['data']['value']);
	

	try {

		$response = update_user_meta($user_id,'zs-email-'.$slug,$value);
		//returns Meta ID if the key didn't exist, true on successful update, false on failure or if the value passed to the function is the same as the one that is already in the database.
		
		if($response) {
			echo true; 
			die();
		} else {
			error_log('AJAX UPDATE EMAIL PREFERENCE - user meta could not be updated');
			echo false;
			die();
		}
	} catch(Exception $e){
		error_log('AJAX UPDATE EMAIL PREFERENCE  try/catch raised an exception');
		error_log($e->getMessage());
		echo false;
		die();
	}
}