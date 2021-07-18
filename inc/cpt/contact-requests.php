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
*  Helper get user full name
*
* @param int $user_id
* @return string $name
*/
function kasutan_get_user_name($user_id) {
	$name=get_user_meta( $user_id, 'first_name', true);
	$name.=' ';
	$name.=get_user_meta( $user_id, 'last_name', true);
	return $name;
}

/**
*  Create new contact request
*
* @param int $user_id
* @param int $main_advisor_id
* @param int $backup_advisor_id
* @param string $source ('FAQ', 'Product', 'About')
* @param optionnal int $product_id
* @return bool
*/

function kasutan_new_contact_request($user_id,$main_advisor_id,$backup_advisor_id=0,$source,$product_id=0) {

	//Préparer variables pour la création du post et pour l'email
	$user=get_user_by('id', $user_id );
	if(!$user) {
		error_log('NEW CONTACT REQUEST - user id is invalid');
		return false;
	}

	$main_advisor_email=get_post_meta($main_advisor_id,'email',true);
	if(!$main_advisor_email) {
		error_log('NEW CONTACT REQUEST - no email found for main advisor '.$main_advisor_id);
		return false;
	}
	$main_advisor_page=sprintf('<a href="https://thesourceworldconnections.com/wp-admin/post.php?post=%s&action=edit">Link to main advisor page</a>',$main_advisor_id);

	$data=array(
		'date' => date('d/m/Y'),
		'user_name' =>kasutan_get_user_name($user_id),
		'user_email'=>$user->user_email,
		'main_advisor_name' => get_the_title($main_advisor_id),
		'main_advisor_email'=>$main_advisor_email,
		'main_advisor_page'=>$main_advisor_page,
		'source' => $source.' page',
	);

	if($source==="Product") {
		$product_title=get_the_title($product_id);
		$product_url=get_the_permalink( $product_id);
		if(!$product_url || empty($product_title)) {
			error_log('NEW CONTACT REQUEST - product id is invalid');
			return false;
		}
		$data['product_name']=$product_title;
		$data['product_url']=sprintf('<a href="%s">Link to product page</a>',$product_url);
	}

	//Backup advisor is optional
	$backup_advisor_email=get_post_meta($backup_advisor_id,'email',true);
	if(!$backup_advisor_email) {
		error_log('NEW CONTACT REQUEST - no email found for backup advisor '.$backup_advisor_id);
	} else {
		$data['backup_advisor_name']=get_the_title($backup_advisor_id);
		$data['backup_advisor_email']=$backup_advisor_email;
		$data['backup_advisor_page']=sprintf('<a href="https://thesourceworldconnections.com/wp-admin/post.php?post=%s&action=edit">Link to backup advisor page</a>',$backup_advisor_id);
	}

	//Préparer titre du post 
	$request_title=sprintf('Sent by %s to %s from %s',$data['user_name'],$data['main_advisor_name'],$data['source']);
	if(array_key_exists('product_name',$data)) {
		$request_title.=' '.$data['product_name'];
	}

	//Préparer contenu du post et de l'email
	ob_start();
		echo '<p>Contact request details:</p><ul>';
		foreach($data as $key=>$value) {
			printf('<li><strong>%s:</strong> %s</li>',$key, $value);
		}
		echo '</ul>';
	$request_content=ob_get_clean();

	//Création du post
	$postarr=array(
		'post_author' => $user_id,
		'post_type' => 'contact_requests',
		'post_title' => $request_title,
		'post_content' => $request_content,
		'post_status' => 'publish'
	);
	$new_post_id=wp_insert_post($postarr);
	if($new_post_id) {
		update_post_meta($new_post_id,'source',$source);
		update_post_meta($new_post_id,'main_advisor',$main_advisor_id);
		if($product_id) update_post_meta($new_post_id,'product',$product_id);
		if($backup_advisor_id) update_post_meta($new_post_id,'backup_advisor',$backup_advisor_id);
	}

	//TODO send emails 

	return $new_post_id;
	/*Check*/
	//return kasutan_is_product_in_requests($product_id,$user_id);
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
	$product_id = sanitize_text_field($_POST['data']['product']);
	$main_advisor_id = sanitize_text_field($_POST['data']['mainAdvisor']);
	$backup_advisor_id = sanitize_text_field($_POST['data']['backupAdvisor']);

	try {

		$response = kasutan_new_contact_request($user_id,$main_advisor_id,$backup_advisor_id,$source,$product_id);
		
		if($response) {
			echo true; 
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



/**************************************************************************
* 					Prepare popup (including success/failure message)
***************************************************************************/
function kasutan_display_contact_popup($user_id,$main_advisor,$backup_advisor,$source,$product_id=0) {
	$advisor_name=get_the_title($main_advisor);
	
	if($source==='Product') {
		$send_message="You are about to send <br>a contact request <br>for this item";
	} else {
		$send_message="You are about to send <br>a contact request <br>to ".$advisor_name;
	}

	$email='';
	if(function_exists('get_field')) {
		$email=sanitize_email(get_field('zs_general_email','option'));
	}
	if(empty($email)) {
		$email='team@thesourceworldconnections.com';
	}
	
	$email=antispambot($email);


	//always display close button
	echo '<div id="popup-contact" class="popup">';
		printf('<button class="popup-close picto">%s<span class="screen-reader-text">Close ppopup</span></button>',kasutan_picto(array('icon'=>'close-popup','size'=>false)));

		//Message and action to send request
		echo '<div class="popup-send">';
			echo '<div class="popup-header">';
				printf('<p>%s</p>',$send_message);
			echo '</div>';
			echo '<div class="popup-actions">';
				?>
				<button class="send js-send-request cyan" 
					data-main-advisor="<?php echo $main_advisor;?>"
					data-backup-advisor="<?php echo $backup_advisor;?>"
					data-product="<?php echo $product_id;?>"
					data-user="<?php echo $user_id;?>"
					data-source="<?php echo $source;?>"
				>Confirm</button>

				<button class="popup-close rouge">Cancel</button>
				<?php
			echo '</div>';

		echo '</div>';

		//Message on success
		?>
		<div class="popup-success">
			<p>Thank you, <br>your contact request is on its way.<br>
			<span class="cyan"><?php echo $advisor_name;?></span><br>
			will contact you soon!</p>
			<div class="popup-actions"><button class="popup-close">Close</button></div>
		</div>
		<?php

		//Message on failure
		?>
		<div class="popup-failure">
			<p>Sorry, <br>something went wrong with this contact request.<br>
			<span class="cyan">Please send us an email<br><a href="mailto:<?php echo $email;?>"><?php echo $email;?></a>
			<div class="popup-actions"><button class="popup-close">Close</button></div>
		</div>
		<?php

	echo '</div>';
}