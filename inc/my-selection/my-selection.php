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
	$selected=kasutan_get_products_in_selection($user_id);
	return count($selected);
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
	update_user_meta($user_id,'zs_my_selection',$selected_new);
/*
	ob_start();
		echo 'selection origine';
		print_r($selected);
		echo 'nouvelle selection';
		print_r($selected_new);
	error_log(ob_get_clean());*/
	
	/*Check*/
	return !kasutan_is_product_in_selection($product_id,$user_id);
}

/**
*  TODO Shortode for display on account page
*
* @param int $user_id
*/
add_shortcode('display-selection','kasutan_display_selection');
function kasutan_display_selection() {
	$user_id=get_current_user_id(  );
	$selected=kasutan_get_products_in_selection($user_id);
	if(empty($selected)) {
		return '<p>Your selection is empty;</p>';
	}

	ob_start();

	$product_taxonomies=['cat_assets','cat_projects','cat_companies'];

	foreach($product_taxonomies as $taxonomy) {
		$parent_cats=kasutan_get_all_parent_cats($taxonomy);
		$post_type=kasutan_get_cpt_slug_for_taxonomy($taxonomy);

		foreach($parent_cats as $cat) {
			$args=array(
				'post_type'=>$post_type,
				'numberposts' => -1,
				'tax_query' => array( 
					array( 
						'taxonomy'=>$taxonomy,
						'terms'=> $cat->term_id
					)
					),
				'fields' => 'ids',
				'include' => $selected,
			);
			$posts=get_posts($args);
			if($posts) {
				echo '<section class="selection-by-cat">';
					printf('<h2>Category: <span class="cat-name">%s</span></h2>',$cat->name);
					echo '<ul class="product-grid nb-col-4 last-cat">';
						foreach($posts as $post_id) {
							kasutan_display_product_card($post_id,$cat,$taxonomy,$user_id,'my-selection'); 
						}
					echo '</ul>';
				echo '</section>';
			}
		}
	}
	
	
	return ob_get_clean();
}


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
			echo kasutan_count_selection($user_id);
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