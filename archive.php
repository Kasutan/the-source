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
		$title = 'News';

	} elseif( is_search() ) {
		$title = 'Search results';

	} elseif( is_archive() ) {
		$subtitle='News';
		$title = get_the_archive_title();
	}

	if( empty( $title )  )
		return;

	$classes = [ 'archive-description' ];

	do_action ('ea_archive_header_before' );
	echo '<header class="' . join( ' ', $classes ) . '">';
	if( !empty( $subtitle ) )
	echo '<div class="h2 dots">' . $subtitle . '</div>';
	if( ! empty( $title ) )
		echo '<h1 class="archive-title">' . $title . '</h1>';
	echo '</header>';
	do_action ('ea_archive_header_after' );

}

// Breadcrumbs
add_action( 'ea_archive_header_after', 'kasutan_fil_ariane', 5 );


//Filtre si c'est une catégorie produit et qu'elle a des filles : balises html pour que le filtre avec list.js fonctionne
add_action('kasutan_loop_wrap_before','kasutan_loop_wrap_before');
function kasutan_loop_wrap_before() {

	$direct_children=$taxonomy=false;
	$queried_object = get_queried_object();
	$taxonomy=kasutan_is_archive_for_product($queried_object);
	if($taxonomy) {
		$term_id=$queried_object->term_id;
		$direct_children=get_terms(array('taxonomy'=>$taxonomy, 'parent'=>$term_id));
	}

	if($direct_children) {
		echo '<section id="liste-filtrable" data-pagination="4">'; //Si besoin : pagination = option du thème
			kasutan_display_product_cat_filter($taxonomy,$direct_children);
			echo '<ul class="list loop">';
	} else {
		//Simple conteneur pour la mise en page grille
		echo '<ul class="loop">';
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

	if($direct_children) {
		echo '</ul><ul class="pagination"></ul></section>';
	} else {
		echo '</ul>';
	}
}
// Build the page
require get_template_directory() . '/index.php';
