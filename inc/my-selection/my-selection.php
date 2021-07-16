<?php
/**
* My selection functions
**/

/**
*  Getter
*
* @param int $user_id
* @return array $selected
*/

function kasutan_get_products_in_selection($user_id) {
	$selected=get_user_meta($user_id,'zs_my_selection',true);
	if(empty($selected) || !is_array($selected)) {
		return array();
	} else {
		return $selected;
	}
}

/**
*  Is product in selection?
*
* @param int $product_id
* @param int $user_id
* @return bool
*/
function kasutan_is_product_in_selection($product_id,$user_id) {
	$selected=kasutan_get_products_in_selection($user_id);
	return in_array($product_id,$selected);
}

/**
*  Count
*
* @param int $user_id
* @return int
*/

function kasutan_count_selection($user_id) {
	//TODO
}

/**
*  Setter
*
* @param int $product_id
* @param int $user_id
* @return bool
*/

function kasutan_add_product_to_selection($product_id,$user_id) {
	$selected=kasutan_get_products_in_selection($user_id);
	$selected[]=$product_id;
	update_user_meta($user_id,'zs_my_selection',$selected);

	/*Check*/
	return kasutan_is_product_in_selection($product_id,$user_id);
}


/**
*  Remove
*
* @param int $product_id
* @param int $user_id
* @return bool
*/

function kasutan_remove_product_from_selection($product_id,$user_id) {
	$selected=kasutan_get_products_in_selection($user_id);
	$selected_new=array();
	foreach($selected as $key=>$id) {
		if($id!==$product_id) {
			$selected_new[]=$id;
		}
	}
	
	/*Check*/
	return !kasutan_is_product_in_selection($product_id,$user_id);
}

/**
*  TODO Shortode for display on account page
*
* @param int $user_id
*/



/**************************************************************************
* 						Ajax requête update selection
***************************************************************************/

add_action( 'wp_ajax_kasutan_update_selection_for_user', 'kasutan_update_selection_for_user' );
function kasutan_update_selection_for_user() {
	if ( !wp_verify_nonce($_POST['nonce'], 'thesource_nonce') ){ 
		die('Permission Denied.'); 
	}
	$user_id = sanitize_text_field($_POST['data']['user']);
	$product_id = sanitize_text_field($_POST['data']['product']);
	$action = sanitize_text_field($_POST['data']['action']);

	try {

		$user=get_user_by( 'ID', $user_id);
		if(!$user) {
			error_log('AJAX MY SELECTION invalid user_id');
			echo false;
			die();
		}

		$response=false;
		if($action==='add') {
			$response=kasutan_add_product_to_selection($product_id,$user_id);
		} elseif($action==='remove') {
			$response=kasutan_remove_product_from_selection($product_id,$user_id);
		} else {
			error_log('AJAX MY SELECTION invalid action');
			echo false;
			die();
		}

		if($response) {
			//todo : renvoyer le nombre d'items dans la sélection pour mettre à jour le span dans l'en-tête
			echo true;
			die();
		} else {
			error_log('AJAX MY SELECTION user_meta could not be updated');
			echo false;
			die();
		}
	} catch(Exception $e){
		error_log('AJAX MY SELECTION try/catch raised an exception');
		error_log($e->getMessage());
		echo false;
		die();
	}
}