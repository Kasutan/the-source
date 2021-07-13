<?php 
/**
* Template pour le bloc products
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/

if(function_exists('get_field')) : 

	if(array_key_exists('className',$block)) {
		$className=esc_attr($block["className"]);
	} else $className='';

	$anchor = '';
	if( !empty( $block['anchor'] ) )
		$anchor = ' id="' . sanitize_title( $block['anchor'] ) . '"';
	

	$cat_assets=get_field('cat_assets');
	$cat_companies=get_field('cat_companies');
	$cat_projects=get_field('cat_projects');
	if($cat_assets) {
		$taxonomy='cat_assets';
		$term_id=$cat_assets;
	} elseif($cat_companies) {
		$taxonomy='cat_companies';
		$term_id=$cat_companies;
	} elseif ($cat_projects){
		$taxonomy='cat_projects';
		$term_id=$cat_projects;
	} else {
		return;
	}
	$term=get_term($term_id,$taxonomy);
	$term_slug=$term->slug;
	$title=$term->name;
	$direct_children=get_terms(array('taxonomy'=>$taxonomy, 'parent'=>$term_id));
	$user_id=get_current_user_id(  );
	$post_type=kasutan_get_cpt_slug_for_taxonomy($taxonomy);

	$mobile_title=sprintf('<h2 class="dots">%s</h2>',$title);

	if($direct_children) {
		printf('<section id="liste-filtrable-%s" class="liste-filtrable acf products" data-pagination="6">',$term_slug);
			echo $mobile_title; 
			kasutan_display_product_cat_filter($taxonomy,$direct_children,$term_slug,$title);
			printf('<ul class="list product-grid nb-col-3" id="ul-%s">',$term_slug);
	} else {
		//Simple conteneur pour la mise en page grille
		echo '<section class="acf products">';
		echo $mobile_title; 
		echo '<ul class="product-grid nb-col-4 last-cat">';
	}

	$products=kasutan_get_all_products($taxonomy,$term,6);
	foreach($products as $product_id) {
		$cat=kasutan_get_closest_cat_for_product($product_id,$post_type);
		kasutan_display_product_card($product_id,$cat,$taxonomy,$user_id,'acf');
	}

	echo '</ul>';
	echo '<ul class="pagination"></ul>'; //caché mais nécessaire pour que list.js fonctionne

	printf('<div class="text-center"><a href="%s" class="button browse-all">Browse <span>%s</span></a></div>',
		get_term_link($term,$taxonomy),
		$term->name
	);

	echo '</section>';

endif;