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
	$product_taxonomies=['cat_assets','cat_projects','cat_companies'];
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

/**
* Get related products in category
* @param int $post_id
* @param string $post_type
* @param string $taxonomy
* @param int $number
* @param object $term
* @return array $related //array of product ids
*/

function kasutan_get_related_products($post_id,$post_type,$taxonomy,$term,$number=4) {
	$args=array(
		'post_type'=>$post_type,
		'numberposts' => $number,
		'exclude' => $post_id,
		'tax_query' => array( 
			array( 
				'taxonomy'=>$taxonomy,
				'terms'=> $term->term_id
			 )
			),
		'fields' => 'ids'
	);
	return get_posts($args);
}

/**
* Get sibling categories (with same parent)
* @param int $parent_id
* @param int $term_id
* @param string $taxonomy
* @return array $siblings // array of Terms
*/
function kasutan_get_cat_siblings($parent_id,$term_id,$taxonomy) {
	$args=array(
		'taxonomy' => $taxonomy, 
		'orderby' => 'name', //default
		'hide_empty' => false, //TODO changer en prod
		'exclude' => $term_id,
		'parent' => $parent_id
	);
	return get_terms($args);
}
/**
* Display product card
* @param int $post_id
* @param object $term
* @param string $taxonomy
* @param string $context
*/
function kasutan_display_product_card($post_id,$term,$taxonomy,$user_id,$context) {
	$in_selection=false; //TODO is item already in user's selection ?
	if($in_selection) {
		$attr_checked="checked";
		$class_selected="selected";
	} else {
		$attr_checked=$class_selected="";
	}

	$link=get_the_permalink( $post_id);
	echo '<li class="product">';
		printf('<a href="%s" class="card-image">%s</a>',$link,get_the_post_thumbnail( $post_id, 'medium'));

		echo '<div class="card-info">';
			printf('<a href="%s" class="card-title"><h3>%s</h3></a>',$link,get_the_title( $post_id ));
			?>
				<formgroup class="to-selection <?php echo $class_selected;?>">
				<input type="checkbox" id="js-to-selection-<?php echo $post_id;?>" name="js-to-selection" <?php echo $attr_checked;?> 
						data-product="<?php echo $post_id;?>"
						data-user="<?php echo $user_id;?>"
					><label for="js-to-selection-<?php echo $post_id;?>">
					<span class="add">Save</span>
					<span class="remove">Saved</span>
					<span class="screen-reader-text"><?php echo get_the_title( $post_id );?> to my selection</span>
				</label>
			</formgroup>
			<?php
			
			printf('<a href="%s" class="card-cat">%s</p></a>',get_term_link($term,$taxonomy),$term->name);
			if($context==="filtre") {
				//TODO ajouter span hidden pour filtre par catégorie et tri alphabétique
			}
		echo '</div>'; //fin .card-info

		
	echo '</li>';
}


/**
* Get categories for menu
* @param string $taxonomy
* @return string $output
*/
function kasutan_get_categories_for_menu($taxonomy) {
	$output='';
	$args=array(
		'taxonomy' => $taxonomy, 
		'hide_empty' => false, //TODO changer en prod
		'parent' => 0
	);
	$terms=get_terms($args);
	if(!empty($terms)) {
		$output.='<ul class="sub-menu">';
		foreach ($terms as $term) {
			$link=get_term_link($term,$taxonomy);
			
			$output.=sprintf('<li><a href="%s">%s</a>',$link,$term->name);
				if($term->slug==='mechanical-dreams') {
					$output .=sprintf('<button class="ouvrir-sous-menu picto"><span class="screen-reader-text">Montrer ou masquer le sous-menu</span><span class="picto-angle">%s</span></button>',kasutan_picto(array('icon'=>'triangle', 'size'=>false)) );
					$args=array(
						'taxonomy' => $taxonomy, 
						'hide_empty' => false, //TODO changer en prod
						'parent' => $term->term_id
					);
					$children=get_terms($args);
					if(!empty($children)) {
						$output.='<ul class="sub-menu-2">';
						foreach($children as $child) {
							$link=get_term_link($child,$taxonomy);
							$output.=sprintf('<li><a href="%s">%s</a>',$link,$child->name);
						}
						$output.='</ul>';
					}
				}
			$output.='</li>';
			
		}
		$output.='</ul>';

	}
	return $output;
}