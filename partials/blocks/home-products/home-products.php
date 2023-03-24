<?php 
/**
* Template pour le bloc home-products
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
	$user_id=get_current_user_id(  );
	$post_type=kasutan_get_cpt_slug_for_taxonomy($taxonomy);
	
	$above_title=wp_kses_post(get_field('above_title'));

	
	//Simple conteneur pour la mise en page grille
	echo '<section class="acf products home-products">';
	if($above_title) printf('<div class="above-title">%s</div>',$above_title);
	if($title) printf('<h2 class="dots title">%s</h2>',$title);
	echo '<ul class="product-grid nb-col-4">';
	

	$products=kasutan_get_all_products($taxonomy,$term,4);
	foreach($products as $product_id) {
		$cat=kasutan_get_closest_cat_for_product($product_id,$post_type);
		kasutan_display_product_card($product_id,$cat,$taxonomy,$user_id,'acf');
	}

	echo '</ul>';

	printf('<div class="text-center"><a href="%s" class="button browse-all">%s <span>%s</span></a></div>',
		get_term_link($term,$taxonomy),
		esc_html__('Browse','the-source'),
		$term->name
	);

	echo '</section>';

endif;