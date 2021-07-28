<?php 
/**
* Template pour le bloc home-products-selection
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
	
	$user_id=get_current_user_id(  );
	$post_type=kasutan_get_cpt_slug_for_taxonomy($taxonomy);
	
	$above_title=wp_kses_post(get_field('above_title'));
	$title=wp_kses_post(get_field('title'));

	
	//Simple conteneur pour la mise en page grille
	echo '<section class="acf products home-products">';
	if($above_title) printf('<div class="above-title">%s</div>',$above_title);
	if($title) printf('<h2 class="dots title">%s</h2>',$title);
	echo '<ul class="product-grid nb-col-4">';
	

	$products=get_field('products');
	foreach($products as $product_id) {
		$post_type=get_post_type($product_id);
		$cat=kasutan_get_closest_cat_for_product($product_id,$post_type);
		kasutan_display_readonly_product_card($product_id,$cat);
	}

	echo '</ul>';

	echo '</section>';

endif;