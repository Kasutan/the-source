<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/***************************************************************
	Custom Taxonomy Type of goods
/***************************************************************/

add_action( 'init', 'create_type_goods_tag', 0 );
function create_type_goods_tag() {
// Labels part for the GUI
$labels = array(
	'name' => _x( 'Goods categories', 'taxonomy general name' ),
	'singular_name' => _x( 'Goods category', 'taxonomy singular name' ),
	'menu_name' => __( 'Goods categories' ),
); 
register_taxonomy('zs_cat_goods','producteur',array(
	'hierarchical' => true,
	'labels' => $labels,
	'show_ui' => true, 
	'show_admin_column' => true,
	'query_var' => true,
	'public' => true,
	'show_in_rest' => true
));
}




/***************************************************************
	Custom Post Type : Exceptionnal goods
/***************************************************************/
add_action( 'init', 'kasutan_goods_post_type', 0 );
function kasutan_goods_post_type() {

	$labels = array(
		'name'                  => _x( 'Exceptional Goods', 'Post Type General Name', 'the-source' ),
		'singular_name'         => _x( 'Good', 'Post Type Singular Name', 'the-source' ),
		'menu_name'             => __( 'Exceptional Goods', 'the-source' ),
		'name_admin_bar'        => __( 'Goods', 'the-source' ),
		'archives'              => __( 'Exceptional Goods Archives', 'the-source' ),
		'attributes'            => __( 'Item Attributes', 'the-source' ),
		'parent_item_colon'     => __( 'Parent Item:', 'the-source' ),
		'all_items'             => __( 'All goods', 'the-source' ),
		'add_new_item'          => __( 'Add a good', 'the-source' ),
		'add_new'               => __( 'Add a good', 'the-source' ),
		'new_item'              => __( 'New good', 'the-source' ),
		'edit_item'             => __( 'Edit good', 'the-source' ),
		'update_item'           => __( 'Udpdate good', 'the-source' ),
		'view_item'             => __( 'Display good', 'the-source' ),
		'view_items'            => __( 'Display goods', 'the-source' ),
		'search_items'          => __( 'Search goods', 'the-source' ),
		'not_found'             => __( 'No goods', 'the-source' ),
		'not_found_in_trash'    => __( 'No goods in trash', 'the-source' ),
		'featured_image'        => __( 'Featured image for this item', 'the-source' ),
	);
	$args = array(
		'label'                 => __( 'Good', 'the-source' ),
		'description'           => __( 'Goods', 'the-source' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'editor', 'custom-fields', 'thumbnail' ),
		'taxonomies'            => array( 'zs_cat_goods'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 56,
		'menu_icon'             => 'dashicons-format-image',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'zs_goods', $args );

}

//TODO Probono & Investments