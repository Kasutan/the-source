<?php
/**
 * Archive
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Full width
add_filter( 'ea_page_layout', 'ea_return_pleine_largeur' );

/**
 * Body Class
 *
 */
function ea_archive_body_class( $classes ) {
	$classes[] = 'archive';
	return $classes;
}
add_filter( 'body_class', 'ea_archive_body_class' );

/**
 * Archive Header
 *
 */
add_action( 'tha_content_while_before', 'ea_archive_header' );
function ea_archive_header() {
	$title = $subtitle = '';

	$queried_object = get_queried_object();
	
	if(function_exists('kasutan_is_archive_for_product') && $taxonomy=kasutan_is_archive_for_product($queried_object)) {
		$title=$queried_object->name;
		if($queried_object->parent===0) {
			//top category, display cpt name
			$subtitle=kasutan_get_cpt_for_taxonomy($taxonomy);
		} else {
			//category has parent, display closest parent's name
			$parent=get_term($queried_object->parent,$taxonomy);
			if($parent) $subtitle=$parent->name;
		}
	} elseif( is_home() ) {
		$title = __('News','the-source');

	} elseif( is_search() ) {
		$title = __('Search results','the-source');

	} elseif( is_archive() ) {
		$subtitle=__('News','the-source');
		$title = get_the_archive_title();
	}

	if( empty( $title )  )
		return;

	$classes = [ 'archive-header' ];

	do_action ('ea_archive_header_before' );
	echo '<header class="' . join( ' ', $classes ) . '">';
	if(function_exists('kasutan_tax_banniere')) {
		kasutan_tax_banniere();
	}
	if( !empty( $subtitle ) )
	echo '<div class="h2 dots subtitle">' . $subtitle . '</div>';
	if( ! empty( $title ) )
		echo '<h1 class="archive-title">' . $title . '</h1>';
	echo '</header>';
	do_action ('ea_archive_header_after' );

}

// Breadcrumbs
add_action( 'ea_archive_header_after', 'kasutan_fil_ariane', 5 );


//Description
add_action( 'ea_archive_header_after', 'kasutan_archive_description', 10 );
function kasutan_archive_description() {
	if(!kasutan_is_member()) {
		return;
	}
	$description_native=get_the_archive_description();
	if($description_native) {
		printf('<div class="archive-description">%s</div>',$description_native);
	}
}


//Filtre si c'est une catégorie produit et qu'elle a des filles : balises html pour que le filtre avec list.js fonctionne
add_action('kasutan_loop_wrap_before','kasutan_loop_wrap_before');
function kasutan_loop_wrap_before() {
	$direct_children=$taxonomy=false;
	$queried_object = get_queried_object();
	$taxonomy=kasutan_is_archive_for_product($queried_object);
	if($taxonomy) {
		$term_id=$queried_object->term_id;
		$term_slug=$queried_object->slug;
		$direct_children=get_terms(array('taxonomy'=>$taxonomy, 'parent'=>$term_id));
	}

	if($direct_children) {
		printf('<section id="liste-filtrable-%s" class="liste-filtrable products">',$term_slug);
			kasutan_display_product_cat_filter($taxonomy,$direct_children,$term_slug);
			printf('<ul class="list product-grid nb-col-3" id="ul-%s">',$term_slug);
	} else {
		//Simple conteneur pour la mise en page grille
		echo '<section class="products"><ul class="product-grid nb-col-4 last-cat">';
	}
}

//Fermer les balises
add_action('kasutan_loop_wrap_after','kasutan_loop_wrap_after');
function kasutan_loop_wrap_after() {
	$direct_children=$taxonomy=false;
	$queried_object = get_queried_object();
	$taxonomy=kasutan_is_archive_for_product($queried_object);
	if($taxonomy) {
		$term_id=$queried_object->term_id;
		$direct_children=get_terms(array('taxonomy'=>$taxonomy, 'parent'=>$term_id));
	}


		echo '</ul>';

		if($taxonomy && !$direct_children) {
			$parent_id=$queried_object->parent;
			$parent=get_term($parent_id,$taxonomy);
			if($parent && !is_wp_error( $parent )) {
				printf('<div class="text-center"><a href="%s" class="button browse-all">%s <span>%s</span></a></div>',
					get_term_link($parent,$taxonomy),
					__('Browse all','the-source'),
					$parent->name
				);
			}
		}
	echo '</section>';
}
// Build the page
require get_template_directory() . '/index.php';
