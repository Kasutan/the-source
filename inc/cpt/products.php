<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Check if current page is archive for a product
* @param object $queried_object
* @return bool/string $taxonomy
*/
function kasutan_is_archive_for_product($queried_object) {
	if(!is_tax()) {
		return false;
	}
	$queried_object = get_queried_object();
	$taxonomy=$queried_object->taxonomy;
	$product_taxonomies=['cat_assets','cat_projets','cat_companies'];
	if(!in_array($taxonomy,$product_taxonomies)) {
		return false;
	}
	return $taxonomy;
}

/**
* Check if current post is single for a product
* @return bool
*/
function kasutan_is_single_for_product() {
	if(!is_single()) {
		return false;
	}
	$post_type=get_post_type();
	$product_types=array('exceptional_assets','virtuous_companies','philantropy');
	return in_array($post_type,$product_types);
}


/**
* Get custom post type name for this taxonomy
* @param string $taxonomy 
* @return string $cpt_name
*/
function kasutan_get_cpt_for_taxonomy($taxonomy) {
	$cpt_name='';
	$cpt_objects=get_post_types(array(
		'taxonomies'            => array($taxonomy),
	), $output='objects');
	foreach ($cpt_objects as $cpt) {
		if(array_key_exists('label',$cpt)) {
			$cpt_name=$cpt->label;
		}
	}
	return $cpt_name;
}

/**
* Get custom post type slug for this taxonomy
* @param string $taxonomy 
* @return string $cpt_slug
*/
function kasutan_get_cpt_slug_for_taxonomy($taxonomy) {
	$cpt_slug='';
	$cpt_objects=get_post_types(array(
		'taxonomies'            => array($taxonomy),
	), $output='objects');
	foreach ($cpt_objects as $cpt) {
		if(array_key_exists('name',$cpt)) {
			$cpt_slug=$cpt->name;
		}
	}
	return $cpt_slug;
}

/**
* Get custom taxonomy slug for this post type slug
* @param string $cpt 
* @return string $taxonomy
*/
function kasutan_get_taxonomy_slug_for_cpt($cpt) {
	$taxonomy=false;
	$taxonomies=array(
		'exceptional_assets' =>'cat_assets',
		'virtuous_companies' =>'cat_companies',
		'philantropy' =>'cat_projects',
	);
	if(array_key_exists($cpt,$taxonomies)) {
		$taxonomy= $taxonomies[$cpt];
	}
	return $taxonomy;
}

/**
* Get closest category for this product
* @param int $post_id
* @param string $post_type
* @return object $term
*/
function kasutan_get_closest_cat_for_product($post_id,$post_type) {
	//Tous les terms de la custom tax associée à ce post
	$terms=get_the_terms($post_id,kasutan_get_taxonomy_slug_for_cpt($post_type));
	//Normalement chaque produit n'est rangé que dans une catégorie, la plus basse (boutons radio dans les champs ACF)
	if(!empty($terms)) {
		//return the first one
		return $terms[0];
	} else {
		return false;
	}
}