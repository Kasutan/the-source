<?php
/**
* Contact requests functions
**/

/**
*  Get all requests made by this user
*
* @param int $user_id
* @return array $requests
*/

function kasutan_get_requests_for_user($user_id) {
	$requests=get_posts(array(
		'numberposts' => -1,
		'post_type' => 'contact_requests',
		'author' =>$user_id,
		'fields' =>'ids'
	));
	return $requests;
}

/**
*  Get all active requests made by this user
*
* @param int $user_id
* @return array $requests
*/

function kasutan_get_active_requests_for_user($user_id) {
	$requests=kasutan_get_requests_for_user($user_id);
	$active_requests=array();
	foreach($requests as $request_id) {
		if(kasutan_is_request_active($request_id)) {
			$active_requests[]=$request_id;
		}
	}
	return $active_requests;
}

/**
*  Is product in active contact requests?
*
* @param int $product_id
* @param int $user_id
* @return bool $response
*/
function kasutan_is_product_in_requests($product_id,$user_id) {
	$response=false;
	$requests=kasutan_get_active_requests_for_user($user_id);
	foreach($requests as $request_id) {
		$product_of_request=kasutan_get_product_of_request($request_id);
		if($product_of_request==$product_id) {
			return true; //no need to go further - return request id instead ?
		}
	}
	return $response;
}

/**
*  Is request active = less than 6 month old?
*
* @param int $request_id
* @return bool
*/
function kasutan_is_request_active($request_id) {
	$response=false;
	//TODO check date
	//if $response===false, move request to bin
	return $response;
}


/**
*  Get product for this request
*
* @param int $request_id
* @return bool
*/
function kasutan_get_product_of_request($request_id) {
	return get_post_meta($request_id,'product',true);
}


/**
*  Create new contact request
*
* @param int $product_id
* @param int $user_id
* @param int $main_advisor_id
* @param int $backup_advisor_id
* @return bool
*/

function kasutan_new_contact_request($source,$user_id,$main_advisor_id,$backup_advisor_id) {
	//TODO la requête peut aussi être envoyée depuis une page statique : $source est $product_id ou faq-page ou about-page
	//TODO préparer variables
	ob_start();
	//TODO url produit BO + url du profil advisor dans le BO + url profil backup advisor BO
	$content=ob_get_clean();
	$postarr=array(
		'post_author' => $user_id,
		'post_type' => 'contact_requests',
		'post_title' => 'Sent by '.$user_name.' to '.$advisor_name.' about '.$product_name,
		'post_excerpt' => $content
	);
	$new_post_id=wp_insert_post($postarr);
	if($new_post_id) {
		update_post_meta($new_post_id,'product',$product_id);
		update_post_meta($new_post_id,'main_advisor',$main_advisor_id);
		update_post_meta($new_post_id,'backup_advisor',$backup_advisor_id);
		update_post_meta($new_post_id,'main_advisor_email',$main_advisor_email);
		update_post_meta($new_post_id,'backup_advisor_email',$backup_advisor_email);
	}

	//TODO send emails 

	/*Check*/
	return kasutan_is_product_in_requests($product_id,$user_id);
}



/**************************************************************************
* 						Ajax send new contact request
***************************************************************************/

add_action( 'wp_ajax_kasutan_send_new_contact_request', 'kasutan_send_new_contact_request' );
function kasutan_send_new_contact_request() {
	if ( !wp_verify_nonce($_POST['nonce'], 'thesource_nonce') ){ 
		die('Permission Denied.'); 
	}
	$user_id = sanitize_text_field($_POST['data']['user']);
	$source = sanitize_text_field($_POST['data']['source']);
	$main_advisor_id = sanitize_text_field($_POST['data']['mainAdvisor']);
	$backup_advisor_id = sanitize_text_field($_POST['data']['backupAdvisor']);

	try {

		$user=get_user_by( 'ID', $user_id);
		if(!$user) {
			error_log('AJAX CONTACT REQUEST invalid user_id');
			echo false;
			die();
		}

		$response=false;
		//TODO verification source cohérente avec product_id, advisors existent bien
		if($verifications) {
			$response = kasutan_new_contact_request($source,$user_id,$main_advisor_id,$backup_advisor_id);
		} else {
			error_log('AJAX CONTACT REQUEST  invalid action');
			echo false;
			die();
		}


		if($response) {
			echo $main_advisor_name; // TODO préparer contenu popup
			die();
		} else {
			error_log('AJAX CONTACT REQUEST  contact request could not be sent');
			echo false;
			die();
		}
	} catch(Exception $e){
		error_log('AJAX CONTACT REQUEST  try/catch raised an exception');
		error_log($e->getMessage());
		echo false;
		die();
	}
}