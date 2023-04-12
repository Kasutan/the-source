<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

//Inclure header et footer dans les templates HTML en français (pas de filtrage possible pour intercepter header.html et footer.html)

//Note : au checkout, la langue du html est enregistrée dans un champ custom puis dans une usermeta

add_filter( 'pmpro_email_filter', 'kasutan_pmpro_change_email_based_on_user_locale', 99);

function kasutan_pmpro_change_email_based_on_user_locale( $email ) {
	
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
		$lng=get_user_meta( $user_id, 'zs-lang', true );


		// Si l'adhérent a fr-FR en user meta, on utilise les templates HTML.
		if (!empty($lng) && $lng=='fr-FR' ) {
			
			// BONUS tout déplacer dans une page options dédiée (si besoin de modif en BO)

			// let's use the following template then.
			$body = file_get_contents( get_stylesheet_directory(  ) . '/paid-memberships-pro/email_fr/' . $email->template . '.html');
			$subject = file_get_contents( get_stylesheet_directory(  ) . '/paid-memberships-pro/email_subject_fr/' . $email->template . '.html');


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