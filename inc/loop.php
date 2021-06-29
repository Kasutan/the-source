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

	if ( have_posts() ) :

		tha_content_while_before();

		//Special content for Mechanical dreams
		$special_loop=false;
		$queried_object = get_queried_object();
		if(is_tax('cat_assets') && $queried_object->slug==="mechanical-dreams") {
			if(function_exists('get_field')) {
				$page_mechanical_dreams=get_field('zs_page_mechanical_dreams','option');
				if(!empty($page_mechanical_dreams)) {
					echo apply_filters('the_content',$page_mechanical_dreams->post_content);
					$special_loop=true;
				}
			}
		}

		if(!$special_loop) : 

			do_action('kasutan_loop_wrap_before');
			/* Start the Loop */
			while ( have_posts() ) : the_post();

					tha_entry_before();

					if(kasutan_is_single_for_product()) {
						get_template_part( 'partials/content-product');
					} else {
						$partial = apply_filters( 'ea_loop_partial', is_singular() ? 'content' : 'archive' );
						$context = apply_filters( 'ea_loop_partial_context', is_search() ? 'search' : get_post_type() );
						get_template_part( 'partials/' . $partial, $context );
					}
					tha_entry_after();
			endwhile;
			
			do_action('kasutan_loop_wrap_after');
		
		endif;

		tha_content_while_after();

	else :

		tha_entry_before();
		$context = apply_filters( 'ea_empty_loop_partial_context', 'none' );
		get_template_part( 'partials/archive', $context );
		tha_entry_after();

	endif;

}

add_action( 'tha_content_loop', 'ea_default_loop' );

