<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

//Header et footer vides car on ne pourra pas les traduire

/**
* Adjust email content based on locale used on checkout. Works with WPML plugin.
* This requires a couple of steps to get working.
* 1. Inside your PMPro Customizations Plugin, create a folder called email_fr_FR 
* (You may change fr_FR to the locale your site will be serving besides the default locale - create as many folders that you need).
* 2. Copy the email templates from 'wp-content/plugins/paid-memberships-pro/email' and paste them into your email_fr_FR folder created above.
* 3. Manually edit each template with the translation of the emails or adjust the email template to your liking.
* 4. Repeat these steps for each email folder you created depending on the number of locales your site is using.
* 5. Paste the code below into your PMPro Customizations Plugin - https://www.paidmembershipspro.com/create-a-plugin-for-pmpro-customizations/
*/


//TODO au checkout, enregistrer la langue dans locale (ou un champ custom)

add_filter( 'pmpro_email_filter', 'kasutan_pmpro_change_email_based_on_user_locale', 99);

function kasutan_pmpro_change_email_based_on_user_locale( $email ) {
	error_log('kasutan_pmpro_change_email_based_on_user_locale');
	
	error_log('sujet '.$email->subject);
	error_log('template '.$email->template);
	$data=$email->data;
	$subject=$email->subject;
	/*
	foreach($data as $key=>$value) {
		error_log($key);
		error_log($value);
	}*/
	$user_email=$data['user_email'];
	$user=get_user_by('email',$user_email);
	if($user) {
		$user_id=$user->ID;
		error_log('email envoyé à user id '.$user_id);
		$lng=get_user_meta( $user_id, 'locale', true );
		error_log('lng : '.$lng);


		// If the default locale (in this case en_US) is not loaded, try to switch out the email templates.
		if (!empty($lng) && strpos( $lng, 'en_US' ) == false ) {
			//TODO utiliser aussi des templates html pour les emails en anglais
			// BONUS tout déplacer dans une page options dédiée (si besoin de modif en BO)

			// let's use the following template then.
			$body = file_get_contents( get_stylesheet_directory(  ) . '/paid-memberships-pro/email_fr/' . $email->template . '.html');
			$subject = file_get_contents( get_stylesheet_directory(  ) . '/paid-memberships-pro/email_subject_fr/' . $email->template . '.html');
			error_log('body traduit ? '.$body);


			//swap data into body and subject line
			if(is_array($data))
			{
				foreach($data as $key => $value)
				{
					if ( 'body' != $key ) {
						$body = str_replace("!!" . $key . "!!", $value, $body);
						$subject = str_replace("!!" . $key . "!!", $value, $subject);
					}
				}
			}
			error_log('body filtré ? '.$body);

			if($body) {
				$email->body=$body;
			}
			if($subject) {
				$email->subject=$subject;
			}
		}
	}



	

	return $email;
}