<?php
/**
 * Loop
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
 * Default Loop
 *
 */
function ea_default_loop() {

		$queried_object = get_queried_object();

		tha_content_while_before();

		//Special content for Mechanical dreams
		$special_loop=false;
		if(is_tax('cat_assets') && $queried_object->slug==="mechanical-dreams") {
			if(function_exists('get_field')) {
				$page_mechanical_dreams=get_field('zs_page_mechanical_dreams','option');
				if(!empty($page_mechanical_dreams)) {
					echo apply_filters('the_content',$page_mechanical_dreams->post_content);
					$special_loop=true;
				}
			}
		}

		//special partial for product archive
		$taxonomy=kasutan_is_archive_for_product($queried_object);
		if($taxonomy) {
			$post_type=kasutan_get_cpt_slug_for_taxonomy($taxonomy);
			$user_id=get_current_user_id(  );
		}


		//ne pas montrer les produits aux non-membres pour les archives produits
		if($taxonomy && !kasutan_is_member()) {
			if(function_exists('kasutan_paywall')) {
				kasutan_paywall();
			}
			tha_content_while_after();

			return;
		}

		if(!$special_loop) : 

			
			if ( have_posts() ) :
				do_action('kasutan_loop_wrap_before');
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					tha_entry_before();

					if(kasutan_is_single_for_product()) {
						get_template_part( 'partials/content-product');
					} else if($taxonomy) {
						$post_id=get_the_ID();
						$term=kasutan_get_closest_cat_for_product($post_id,$post_type);
						$context='archive';
						kasutan_display_product_card($post_id,$term,$taxonomy,$user_id,$context);
					} else {
						$partial = apply_filters( 'ea_loop_partial', is_singular() ? 'content' : 'archive' );
						$context = apply_filters( 'ea_loop_partial_context', is_search() ? 'search' : get_post_type() );
						get_template_part( 'partials/' . $partial, $context );
					}
					tha_entry_after();
				endwhile;
				do_action('kasutan_loop_wrap_after');

			else :
				if($taxonomy) {
					//On est sur une page archive produits qui ne contient aucun produit
					printf('<p class="text-center">%s</p>',__(' Exceptional assets will soon be listed on the Source. Please come back later to visit this page again.','the-source'));
				} else {
					get_template_part( 'partials/archive', 'none' );
				}
				

			endif;
		
		endif;

		tha_content_while_after();

}

add_action( 'tha_content_loop', 'ea_default_loop' );

