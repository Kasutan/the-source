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
		if(property_exists($cpt,'name')) {
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
* Get all products in category
* @param string $taxonomy
* @param int $number
* @param object $term
* @return array $products //array of product ids
*/

function kasutan_get_all_products($taxonomy,$term,$number=-1) {
	$post_type=kasutan_get_cpt_slug_for_taxonomy($taxonomy);
	$args=array(
		'post_type'=>$post_type,
		'numberposts' => $number,
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
* Get all 1rst level products categories for a taxonomy
* @param string $taxonomy
* @return array $parent_terms // array of Terms
*/
function kasutan_get_all_parent_cats($taxonomy) {
	$parent_terms=array();
	$terms=get_terms(array('taxonomy'=>$taxonomy));
	foreach($terms as $term) {
		if($term->parent === 0) {
			$parent_terms[]=$term;
		}
	}
	return $parent_terms;
}

/**
* Filtre pour une taxonomie
*
*/
function kasutan_display_product_cat_filter($taxonomy,$terms,$parent_slug,$title='') {

	if(empty($terms)) {
		return;
	}
	//TODO boutons mobile filter = ouvre volet et sort -> quelle action ?
	//TODO si $title on est sur un bloc acf, pas de tri
	printf('<form class="filtre %s" id="filtre-%s">',$taxonomy,$parent_slug);
		if($title) {
			printf('<p class="filtre-titre">%s</p>',$title);
		} else {
			echo '<p class="filtre-titre">Filter<span class="show-for-lg"> by</span></p>';
		}
		foreach($terms as $term) : 
			$nom=$term->name;
			$slug=$term->slug;
			printf('<input type="checkbox" id="%s" name="filtre" value="%s" class="type filtre-input">',
				$slug,
				$slug
			);
			printf('<label for="%s" class="filtre-label">%s</label>',
				$slug,
				$nom
			);
		endforeach;
		//TODO boutons mobile display = fermer volet, reset = monter tout + fermer volet
	echo '</form>';
}


/**
* Display product card
* @param int $post_id
* @param object $term
* @param string $taxonomy
* @param string $context
*/
function kasutan_display_product_card($post_id,$term,$taxonomy,$user_id,$context) {
	$in_selection=kasutan_is_product_in_selection($post_id,$user_id); //Is item already in user's selection ?

	if($in_selection) {
		$attr_checked="checked";
		$class_selected="selected";
		if($context==="my-selection") {
			$class_selected.=" my-selection-page";
		}
	} else {
		$attr_checked=$class_selected="";
	}

	$link=get_the_permalink( $post_id);
	echo '<li class="product">';
		if(has_post_thumbnail( $post_id )) {
			$img=get_the_post_thumbnail( $post_id, 'medium');
		} else {
			$img=sprintf('<img src="%s/icons/default.svg" alt="default image" height="308" width="308"/>',get_stylesheet_directory_uri(  ));
		}
		printf('<a href="%s" class="card-image">%s</a>',$link,$img);

		echo '<div class="card-info">';
			printf('<a href="%s" class="card-title"><h3>%s</h3></a>',$link,get_the_title( $post_id ));
			?>
				<formgroup class="to-selection <?php echo $class_selected;?>">
				<input type="checkbox" id="js-to-selection-<?php echo $post_id;?>" name="js-to-selection-<?php echo $post_id;?>" class="js-to-selection" <?php echo $attr_checked;?> 
						data-product="<?php echo $post_id;?>"
						data-user="<?php echo $user_id;?>"
					><label for="js-to-selection-<?php echo $post_id;?>">
					<span class="add">Save</span>
					<?php 
						if($context==="my-selection") {
							echo '<span class="remove">Remove</span>';
						} else {
							echo '<span class="remove">Saved</span>';
						}
					
					?>
					<span class="screen-reader-text"><?php echo get_the_title( $post_id );?> to my selection</span>
				</label>
				<p class="error" hidden>There was an error, your selection was not updated. Please try again later or contact us.</p>
			</formgroup>
			<?php
			
			printf('<a href="%s" class="card-cat">%s</p></a>',get_term_link($term,$taxonomy),$term->name);
			if($context==="archive" || $context==='acf') {
				//span hidden pour filtre par catégorie et tri alphabétique
				printf('<span class="screen-reader-text term">%s</span>',$term->slug);
			}
		echo '</div>'; //fin .card-info

		
	echo '</li>';
}


/**
* Display product card - read only
* @param int $post_id
* @param object $term
*/
function kasutan_display_readonly_product_card($post_id,$term) {
	
	echo '<li class="product">';
		if(has_post_thumbnail( $post_id )) {
			$img=get_the_post_thumbnail( $post_id, 'medium');
		} else {
			$img=sprintf('<img src="%s/icons/default.svg" alt="default image" height="308" width="308"/>',get_stylesheet_directory_uri(  ));
		}
		printf('<div class="card-image">%s</div>',$img);

		echo '<div class="card-info">';
			printf('<div class="card-title"><h3>%s</h3></div>',get_the_title( $post_id ));
			
			printf('<div class="card-cat">%s</p></a>',$term->name);
			
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