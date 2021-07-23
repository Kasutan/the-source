<?php
/**
 * Sidebar Layouts
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
 * Layout Options
 *
 */
function ea_page_layout_options() {
	return [
		'largeur-normale',
		'largeur-reduite',
		'pleine-largeur',
		'deux-colonnes'
	];
}



/**
 * Layout Metabox (ACF)
 *
 */
function ea_page_layout_metabox() {

	if( ! function_exists('acf_add_local_field_group') )
		return;

	$choices = [];
	$layouts = ea_page_layout_options();
	foreach( $layouts as $layout ) {
		$label = str_replace( '-', ' ', $layout );
		$choices[ $layout ] = ucwords( $label );
		if($layout==='deux-colonnes') {
			$choices[ $layout ] .=' (image à gauche)';
		}
	}

	acf_add_local_field_group(array(
		'key' => 'group_5dd714b369526',
		'title' => 'Mise en page',
		'fields' => array(
			array(
				'key' => 'field_5dd715a02eaf0',
				'label' => 'Mise en page',
				'name' => 'ea_page_layout',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => $choices,
				'default_value' => array(),
				'allow_null' => 1,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'side',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
}
add_action( 'acf/init', 'ea_page_layout_metabox' );


/**
* Layout Body Class
*
*/
function ea_layout_body_class( $classes ) {
	$classes[] = ea_page_layout();


	if(function_exists('get_field')) {
		$header_option=esc_attr(get_field('hide_regular_header'));
		if($header_option==="hide") {
			$classes[]='hide-header';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'ea_layout_body_class', 5 );


/**
* Page Layout
*
*/
function ea_page_layout( $id = false ) {

	$available_layouts = ea_page_layout_options();
	$layout = 'largeur-normale';

	if(kasutan_is_single_for_product()) {
		$layout = 'pleine-largeur';
	}

	if(is_tax('product_cat')) {
		$layout = 'pleine-largeur';
	}

	if( is_singular() || $id ) {
		$id = $id ? intval( $id ) : get_the_ID();
		$selected = get_post_meta( $id, 'ea_page_layout', true );
		if( !empty( $selected ) && in_array( $selected, $available_layouts ) )
			$layout = $selected;
	}

	$layout = apply_filters( 'ea_page_layout', $layout );
	$layout = in_array( $layout, $available_layouts ) ? $layout : $available_layouts[0];

	return sanitize_title_with_dashes( $layout );
}

/**
* Return Full Width Content
* used when filtering 'ea_page_layout'
*/
function ea_return_pleine_largeur() {
	return 'pleine-largeur';
}

/**
* Return Content Sidebar
* used when filtering 'ea_page_layout'
*/
function ea_return_largeur_reduite() {
	return 'largeur-reduite';
}

/**
* Return Content
* used when filtering 'ea_page_layout'
*/
function ea_return_largeur_normale() {
	return 'largeur-normale';
}
