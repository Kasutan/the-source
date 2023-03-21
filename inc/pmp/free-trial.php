<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//TODO à supprimer

/**
* Only allow users to use the trial level once. 
* https://www.paidmembershipspro.com/offer-trial-memberships-that-can-only-be-used-once/
*/

//record when users gain the trial level
add_action("pmpro_after_change_membership_level", "kasutan_pmpro_after_change_membership_level", 10, 2);
function kasutan_pmpro_after_change_membership_level($level_id, $user_id)
{
	//set this to the id of your trial level
	$trial_levels = array( 2 );
		
	if ( in_array( $level_id, $trial_levels ) ) {	
		//add user meta to record the fact that this user has had this level before
		update_user_meta($user_id, "pmpro_trial_level_used_{$level_id}", "1");
		update_user_meta($user_id, "pmpro_trial_expired_email_sent", "0");
	}	
}

//check at checkout if the user has used the trial level already
add_filter("pmpro_registration_checks", "kasutan_pmpro_registration_checks");
function kasutan_pmpro_registration_checks($value)
{
	global $current_user;
	
	$level_id = intval( $_REQUEST['level'] );

	if ( $current_user->ID ) {
		//check if the current user has already used the trial level
		$already = get_user_meta($current_user->ID, "pmpro_trial_level_used_{$level_id}", true);
		
		//yup, don't let them checkout
		if( $already ) {
			global $pmpro_msg, $pmpro_msgt;
			$pmpro_msg = "You have already used up your trial membership. Please select a premium membership to checkout.";
			$pmpro_msgt = "pmpro_error";
			
			$value = false;
		}
	}
	
	return $value;
}

//swap the expiration text if the user has used the trial
add_filter("pmpro_level_expiration_text", "kasutan_pmpro_level_expiration_text", 10, 2);
function kasutan_pmpro_level_expiration_text($text, $level)
{
	global $current_user;
	
	//has user used trial level already.
	if ( $current_user->ID )	{
		$used_trial = get_user_meta( $current_user->ID, "pmpro_trial_level_used_{$level->id}", true );

		if ( ! empty( $used_trial ) ) {
			$text = "You have already used up your trial membership. Please select a premium membership to checkout.";
		}
	}
	
	return $text;
}


/**
* Do not generate PDF invoices for free orders. 
* https://github.com/Yoohoo-Plugins/pmpro-pdf-invoices/blob/master/pmpro-pdf-invoices.php#L159
* apply_filters( 'pmpropdf_can_generate_pdf_on_added_order', true, $order );
*/
/*
add_filter('pmpropdf_can_generate_pdf_on_added_order','kasutan_can_generate_pdf_on_added_order',10,2);

function kasutan_can_generate_pdf_on_added_order($answer,$order) {
	error_log('generate pdf ?');
	error_log('order total : '.$order->total);
	if($order->total <= 0) {
		error_log('free order, no invoice');
		$answer=0;
	}
	error_log($answer);
	return $answer;
}*/

/**
* Do not attach  PDF invoices to free checkout email. 
* https://github.com/Yoohoo-Plugins/pmpro-pdf-invoices/blob/master/pmpro-pdf-invoices.php#L121
* apply_filters( 'pmpropdf_can_attach_pdf_email', true, $email ) 
*/

add_filter('pmpropdf_can_attach_pdf_email','kasutan_pmpropdf_can_attach_pdf_email',10,2);

function kasutan_pmpropdf_can_attach_pdf_email($answer,$email) {
	//error_log('attach pdf ?');
	//error_log('email template : '.$email->template);
	if($email->template ==="checkout_free") {
		//error_log('free checkout, do not attach pdf');
		$answer=0;
	}
	//error_log($answer);
	return $answer;
}

/**
* Use a special email template when a free trial membership is close to expiring or has expired. 
* apply_filters("pmpro_email_template", "membership_expiring", $this)
* apply_filters("pmpro_email_template", "membership_expired", $this)
* in \plugins\paid-memberships-pro\classes\class.pmproemail.php
*/
add_filter('pmpro_email_template','kasutan_email_templates',10,2);
function kasutan_email_templates($template,$email_instance) {
	//error_log('kasutan_email_templates');
	if($template==='membership_expiring') {
		//error_log('envoi email avec template initial '.$template);
		$data=$email_instance->data;
		ob_start();
		print_r($email_instance->data);
		//error_log(ob_get_clean());
		if($data['membership_id']==2) {
			$new_template='free_'.$template;
			//error_log('level gratuit = envoi à la place du template '.$new_template);
			$template=$new_template;
		}
		
	} else if($template==='membership_expired') {
		//error_log('envoi email avec template initial '.$template);
		$data=$email_instance->data;
		ob_start();
		print_r($email_instance->data);
		//error_log(ob_get_clean());
		$user_email=$data['user_email'];
		//error_log('email envoyé à '.$user_email);
		$user=get_user_by('email',$user_email);
		$used_trial = get_user_meta( $user->ID, "pmpro_trial_level_used_2", true );
		$got_trial_expired_email=get_user_meta( $user->ID, "pmpro_trial_expired_email_sent", true );
		if($used_trial=="1" && $got_trial_expired_email=="0") {
			//this user registered for a trial level and did not yet get the expired email for this trial
			//error_log('this user registered for a trial level and did not yet get the expired email for this trial');
			$new_template='free_'.$template;
			//error_log('envoi à la place du template '.$new_template);
			update_user_meta($user->ID, "pmpro_trial_expired_email_sent", "1");
			$template=$new_template;
		}
		
	} 
	//error_log('template retourné par pmpro_email_template');
	return $template;
}