<?php
/**
 * Helper Functions
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Duplicate 'the_content' filters
global $wp_embed;
add_filter( 'ea_the_content', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'ea_the_content', array( $wp_embed, 'autoembed'     ), 8 );
add_filter( 'ea_the_content', 'wptexturize'        );
add_filter( 'ea_the_content', 'convert_chars'      );
add_filter( 'ea_the_content', 'wpautop'            );
add_filter( 'ea_the_content', 'shortcode_unautop'  );
add_filter( 'ea_the_content', 'do_shortcode'       );

/**
 * Get the first term attached to post
 *
 * @param string $taxonomy
 * @param string/int $field, pass false to return object
 * @param int $post_id
 * @return string/object
 */
function ea_first_term( $args = [] ) {

	$defaults = [
		'taxonomy'	=> 'category',
		'field'		=> null,
		'post_id'	=> null,
	];

	$args = wp_parse_args( $args, $defaults );

	$post_id = !empty( $args['post_id'] ) ? intval( $args['post_id'] ) : get_the_ID();
	$field = !empty( $args['field'] ) ? esc_attr( $args['field'] ) : false;
	$term = false;

	$terms = get_the_terms( $post_id, $args['taxonomy'] );

	if( empty( $terms ) || is_wp_error( $terms ) )
		return false;

	// If there's only one term, use that
	if( 1 == count( $terms ) ) {
		$term = array_shift( $terms );

	// If there's more than one...
	} else {

		// Sort by term order if available
		// @uses WP Term Order plugin
		if( isset( $terms[0]->order ) ) {
			$list = array();
			foreach( $terms as $term )
				$list[$term->order] = $term;
			ksort( $list, SORT_NUMERIC );

		// Or sort by post count
		} else {
			$list = array();
			foreach( $terms as $term )
				$list[$term->count] = $term;
			ksort( $list, SORT_NUMERIC );
			$list = array_reverse( $list );
		}

		$term = array_shift( $list );
	}

	// Output
	if( !empty( $field ) && isset( $term->$field ) )
		return $term->$field;

	else
		return $term;
}


/** Types producteurs (slugs séparés par des espaces) pour vignettes producteurs */
function kasutan_types_producteurs_string($producteur_id) {
	$terms = get_the_terms( $producteur_id, 'type_producteur' );
	if( empty( $terms ) || is_wp_error( $terms ) )
	return false;

	$slugs=array();
	foreach($terms as $term) {
		$slugs[]=$term->slug;
	}
	return implode(' ',$slugs);
}

/**
 * Catégories de produits pour fil d'ariane
 * d'après https://wordpress.stackexchange.com/questions/56784/get-main-parent-categories-for-a-product */
function kasutan_categories_produit() {

	$descendant = get_the_terms( get_the_ID(), 'product_cat' );
	$descendant = array_reverse($descendant);
	$descendant = $descendant[0];

	$descendant_id = $descendant->term_id;
	$ancestors = array_reverse(get_ancestors($descendant_id, 'product_cat'));
	if(!empty($ancestors)) {
		$origin_ancestor_term = get_term_by("id", $ancestors[0], "product_cat");
	} else {
		$origin_ancestor_term ='';
	}
	
	return array(
		'categorie_parente' => $origin_ancestor_term,
		'sous_categorie' => $descendant
	);
}

/**
 * Conditional CSS Classes
 *
 * @param string $base_classes, classes always applied
 * @param string $optional_class, additional class applied if $conditional is true
 * @param bool $conditional, whether to add $optional_class or not
 * @return string $classes
 */
function ea_class( $base_classes, $optional_class, $conditional ) {
	return $conditional ? $base_classes . ' ' . $optional_class : $base_classes;
}

/**
 *  Background Image Style
 *
 * @param int $image_id
 * @return string $output
 */
function ea_bg_image_style( $image_id = false, $image_size = 'full' ) {
	if( !empty( $image_id ) )
		return ' style="background-image: url(' . wp_get_attachment_image_url( $image_id, $image_size ) . ');"';
}

/**
 * Get Icon
 * This function is in charge of displaying SVG icons across the site.
 *
 * Place each <svg> source in the /assets/icons/ directory, without adding
 * both `width` and `height` attributes, since these are added dynamically,
 * before rendering the SVG code.
 *
 * All icons are assumed to have equal width and height, hence the option
 * to only specify a `$size` parameter in the svg methods.
 *
 */
function kasutan_picto( $atts = array() ) {

	$atts = shortcode_atts( array(
		'icon'	=> false,
		'size'	=> 16,
		'class'	=> false,
		'label'	=> false,
	), $atts );

	if( empty( $atts['icon'] ) )
		return;

	$icon_path = get_theme_file_path( '/icons/' . $atts['icon'] . '.svg' );
	if( ! file_exists( $icon_path ) )
		return;

		$icon = file_get_contents( $icon_path );

		$class = 'svg-icon';
		if( !empty( $atts['class'] ) )
			$class .= ' ' . esc_attr( $atts['class'] );

		if( false !== $atts['size'] ) {
			$repl = sprintf( '<svg class="' . $class . '" width="%d" height="%d" aria-hidden="true" role="img" focusable="false" ', $atts['size'], $atts['size'] );
			$svg  = preg_replace( '/^<svg /', $repl, trim( $icon ) ); // Add extra attributes to SVG code.
		} else {
			$svg = preg_replace( '/^<svg /', '<svg class="' . $class . '"', trim( $icon ) );
		}
		$svg  = preg_replace( "/([\n\t]+)/", ' ', $svg ); // Remove newlines & tabs.
		$svg  = preg_replace( '/>\s*</', '><', $svg ); // Remove white space between SVG tags.

		if( !empty( $atts['label'] ) ) {
			$svg = str_replace( '<svg class', '<svg aria-label="' . esc_attr( $atts['label'] ) . '" class', $svg );
			$svg = str_replace( 'aria-hidden="true"', '', $svg );
		}

		return $svg;
}

/**
 * Has Action
 *
 */
function ea_has_action( $hook ) {
	ob_start();
	do_action( $hook );
	$output = ob_get_clean();
	return !empty( $output );
}

/***************************************************************
			Vérifier si WooCommerce est activé
***************************************************************/
function kasutan_is_woo_active() {
	$active_plugins=apply_filters( 'active_plugins', get_option( 'active_plugins' ));
	return in_array('woocommerce/woocommerce.php', $active_plugins);
}


/***************************************************************
			Afficher l'adresse mail via un shortcode
***************************************************************/

function mc_adresse_email($atts) {
	extract( shortcode_atts( array(    
		'mail' => ' ',    
		), $atts) );
	
			return sprintf('<a href="mailto:%s">%s</a>',antispambot($mail),antispambot($mail));
		}
		
add_shortcode( 'adresse-email', 'mc_adresse_email' );


/**
* Récupérer l'ID d'une page - stockée dans une option ACF.
*/

function kasutan_get_page_ID($nom) {
	if (!function_exists('get_field')) {
		return;
	}

	$page=get_field($nom,'option');

	return $page;
}

/**
* Enlever le s à la fin d'un mot
*/
function kasutan_enleve_s_final($mot) {
	if(substr($mot,-1)==='s') {
		return substr_replace($mot ,"", -1);
	} else {
		return $mot;
	}
}
