<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/***************************************************************
	Custom Taxonomy Type of goods
/***************************************************************/

add_action( 'init', 'kasutan_create_type_goods_tag', 0 );
function kasutan_create_type_goods_tag() {
// Labels part for the GUI
$labels = array(
	'name' => _x( 'Goods categories', 'taxonomy general name' ),
	'singular_name' => _x( 'Goods category', 'taxonomy singular name' ),
	'menu_name' => __( 'Goods categories' ),
); 
register_taxonomy('cat_goods','exceptionnal_goods',array(
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
		'singular_name'         => _x( 'Goods', 'Post Type Singular Name', 'the-source' ),
		'menu_name'             => __( 'Exceptional Goods', 'the-source' ),
		'name_admin_bar'        => __( 'Exceptional Goods', 'the-source' ),
		'archives'              => __( 'Exceptional Goods Archives', 'the-source' ),
		'attributes'            => __( 'Item Attributes', 'the-source' ),
		'parent_item_colon'     => __( 'Parent Item:', 'the-source' ),
		'all_items'             => __( 'All goods', 'the-source' ),
		'add_new_item'          => __( 'Add goods', 'the-source' ),
		'add_new'               => __( 'Add goods', 'the-source' ),
		'new_item'              => __( 'New goods', 'the-source' ),
		'edit_item'             => __( 'Edit goods', 'the-source' ),
		'update_item'           => __( 'Udpdate goods', 'the-source' ),
		'view_item'             => __( 'Display goods', 'the-source' ),
		'view_items'            => __( 'Display goods', 'the-source' ),
		'search_items'          => __( 'Search goods', 'the-source' ),
		'not_found'             => __( 'No goods', 'the-source' ),
		'not_found_in_trash'    => __( 'No goods in trash', 'the-source' ),
		'featured_image'        => __( 'Featured image for this item', 'the-source' ),
	);
	$args = array(
		'label'                 => __( 'Goods', 'the-source' ),
		'description'           => __( 'Goods', 'the-source' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'editor', 'custom-fields', 'thumbnail' ),
		'taxonomies'            => array( 'cat_goods'),
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
	register_post_type( 'exceptionnal_goods', $args );

}

/***************************************************************
	Custom Taxonomy Type of companies
/***************************************************************/

add_action( 'init', 'kasutan_create_type_companies_tag', 0 );
function kasutan_create_type_companies_tag() {
// Labels part for the GUI
$labels = array(
	'name' => _x( 'Companies categories', 'taxonomy general name' ),
	'singular_name' => _x( 'Companies category', 'taxonomy singular name' ),
	'menu_name' => __( 'Companies categories' ),
); 
register_taxonomy('cat_companies','virtuous_companies',array(
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
	Custom Post Type : Virtuous companies
/***************************************************************/
add_action( 'init', 'kasutan_companies_post_type', 0 );
function kasutan_companies_post_type() {

	$labels = array(
		'name'                  => _x( 'Virtuous companies', 'Post Type General Name', 'the-source' ),
		'singular_name'         => _x( 'Company', 'Post Type Singular Name', 'the-source' ),
		'menu_name'             => __( 'Virtuous companies', 'the-source' ),
		'name_admin_bar'        => __( 'Virtuous company', 'the-source' ),
		'archives'              => __( 'Companies Archives', 'the-source' ),
		'attributes'            => __( 'Item Attributes', 'the-source' ),
		'parent_item_colon'     => __( 'Parent Item:', 'the-source' ),
		'all_items'             => __( 'All companies', 'the-source' ),
		'add_new_item'          => __( 'Add a company', 'the-source' ),
		'add_new'               => __( 'Add a company', 'the-source' ),
		'new_item'              => __( 'New company', 'the-source' ),
		'edit_item'             => __( 'Edit company', 'the-source' ),
		'update_item'           => __( 'Udpdate company', 'the-source' ),
		'view_item'             => __( 'Display company', 'the-source' ),
		'view_items'            => __( 'Display companies', 'the-source' ),
		'search_items'          => __( 'Search companies', 'the-source' ),
		'not_found'             => __( 'No companies', 'the-source' ),
		'not_found_in_trash'    => __( 'No companies in trash', 'the-source' ),
		'featured_image'        => __( 'Featured image for this company', 'the-source' ),
	);
	$args = array(
		'label'                 => __( 'Virtuous Companies', 'the-source' ),
		'description'           => __( 'Virtuous Companies', 'the-source' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'editor', 'custom-fields', 'thumbnail' ),
		'taxonomies'            => array( 'cat_companies'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 56,
		'menu_icon'             => 'dashicons-building',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'virtuous_companies', $args );

}

/***************************************************************
	Custom Taxonomy Type of projects
/***************************************************************/

add_action( 'init', 'kasutan_create_type_projects_tag', 0 );
function kasutan_create_type_projects_tag() {
// Labels part for the GUI
$labels = array(
	'name' => _x( 'Projects categories', 'taxonomy general name' ),
	'singular_name' => _x( 'Projects category', 'taxonomy singular name' ),
	'menu_name' => __( 'Projects categories' ),
); 
register_taxonomy('cat_projects','philantropy',array(
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
	Custom Post Type : Philantropic projects
/***************************************************************/
add_action( 'init', 'kasutan_projects_post_type', 0 );
function kasutan_projects_post_type() {

	$labels = array(
		'name'                  => _x( 'Philantropic projects', 'Post Type General Name', 'the-source' ),
		'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'the-source' ),
		'menu_name'             => __( 'Philantropic projects', 'the-source' ),
		'name_admin_bar'        => __( 'Philantropic project', 'the-source' ),
		'archives'              => __( 'Projects Archives', 'the-source' ),
		'attributes'            => __( 'Item Attributes', 'the-source' ),
		'parent_item_colon'     => __( 'Parent Item:', 'the-source' ),
		'all_items'             => __( 'All projects', 'the-source' ),
		'add_new_item'          => __( 'Add a project', 'the-source' ),
		'add_new'               => __( 'Add a project', 'the-source' ),
		'new_item'              => __( 'New project', 'the-source' ),
		'edit_item'             => __( 'Edit project', 'the-source' ),
		'update_item'           => __( 'Udpdate project', 'the-source' ),
		'view_item'             => __( 'Display project', 'the-source' ),
		'view_items'            => __( 'Display projects', 'the-source' ),
		'search_items'          => __( 'Search projects', 'the-source' ),
		'not_found'             => __( 'No projects', 'the-source' ),
		'not_found_in_trash'    => __( 'No projects in trash', 'the-source' ),
		'featured_image'        => __( 'Featured image for this project', 'the-source' ),
	);
	$args = array(
		'label'                 => __( 'Philantropic Projects', 'the-source' ),
		'description'           => __( 'Philantropic Projects', 'the-source' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'editor', 'custom-fields', 'thumbnail' ),
		'taxonomies'            => array( 'cat_projects'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 56,
		'menu_icon'             => 'dashicons-universal-access-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'philantropy', $args );

}


//TODO Référents
//TODO Contact requests