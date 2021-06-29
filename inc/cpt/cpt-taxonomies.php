<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/***************************************************************
	Custom Taxonomy Type of assets
/***************************************************************/

add_action( 'init', 'kasutan_create_type_assets_tag', 0 );
function kasutan_create_type_assets_tag() {
// Labels part for the GUI
$labels = array(
	'name' => _x( 'Assets categories', 'taxonomy general name' ),
	'singular_name' => _x( 'Assets category', 'taxonomy singular name' ),
	'menu_name' => __( 'Assets categories' ),
); 
register_taxonomy('cat_assets','exceptional_assets',array(
	'hierarchical' => true,
	'labels' => $labels,
	'show_ui' => true, 
	'show_admin_column' => true,
	'query_var' => true,
	'public' => true,
	'show_in_rest' => true,
	'rewrite' => array('slug'=>'exceptional-assets','hierarchical'=>'true')
));
}




/***************************************************************
	Custom Post Type : Exceptional assets
/***************************************************************/
add_action( 'init', 'kasutan_assets_post_type', 0 );
function kasutan_assets_post_type() {

	$labels = array(
		'name'                  => _x( 'Exceptional Assets', 'Post Type General Name', 'the-source' ),
		'singular_name'         => _x( 'Asset', 'Post Type Singular Name', 'the-source' ),
		'menu_name'             => __( 'Exceptional Assets', 'the-source' ),
		'name_admin_bar'        => __( 'Exceptional Assets', 'the-source' ),
		'archives'              => __( 'Exceptional Assets Archives', 'the-source' ),
		'attributes'            => __( 'Item Attributes', 'the-source' ),
		'parent_item_colon'     => __( 'Parent Item:', 'the-source' ),
		'all_items'             => __( 'All assets', 'the-source' ),
		'add_new_item'          => __( 'Add asset', 'the-source' ),
		'add_new'               => __( 'Add asset', 'the-source' ),
		'new_item'              => __( 'New asset', 'the-source' ),
		'edit_item'             => __( 'Edit asset', 'the-source' ),
		'update_item'           => __( 'Udpdate asset', 'the-source' ),
		'view_item'             => __( 'Display asset', 'the-source' ),
		'view_items'            => __( 'Display assets', 'the-source' ),
		'search_items'          => __( 'Search assets', 'the-source' ),
		'not_found'             => __( 'No assets', 'the-source' ),
		'not_found_in_trash'    => __( 'No assets in trash', 'the-source' ),
		'featured_image'        => __( 'Featured image for this asset', 'the-source' ),
	);
	$args = array(
		'label'                 => __( 'Assets', 'the-source' ),
		'description'           => __( 'Assets', 'the-source' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'custom-fields', 'thumbnail', 'author' ),
		'taxonomies'            => array( 'cat_assets'),
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
	register_post_type( 'exceptional_assets', $args );

}

/***************************************************************
	Custom Taxonomy Type of companies
/***************************************************************/

add_action( 'init', 'kasutan_create_type_companies_tag', 0 );
function kasutan_create_type_companies_tag() {
// Labels part for the GUI
$labels = array(
	'name' => _x( 'Companies categories', 'taxonomy general name' ),
	'singular_name' => _x( 'Companies category', 'taxonomy singular name'),
	'menu_name' => __( 'Companies categories' ),
); 
register_taxonomy('cat_companies','virtuous_companies',array(
	'hierarchical' => true,
	'labels' => $labels,
	'show_ui' => true, 
	'show_admin_column' => true,
	'query_var' => true,
	'public' => true,
	'show_in_rest' => true,
	'rewrite' => array('slug'=>'virtuous-companies','hierarchical'=>'true')
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
		'supports'              => array( 'title', 'revisions', 'editor', 'custom-fields', 'thumbnail', 'author' ),
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
	'show_in_rest' => true,
	'rewrite' => array('slug'=>'philantropic-projects','hierarchical'=>'true')
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
		'supports'              => array( 'title', 'revisions', 'editor', 'custom-fields', 'thumbnail', 'author' ),
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

/***************************************************************
	Custom Post Type : advisors
/***************************************************************/
add_action( 'init', 'kasutan_advisors_post_type', 0 );
function kasutan_advisors_post_type() {

	$labels = array(
		'name'                  => _x( 'Advisors', 'Post Type General Name', 'the-source' ),
		'singular_name'         => _x( 'Advisor', 'Post Type Singular Name', 'the-source' ),
		'menu_name'             => __( 'Advisors', 'the-source' ),
		'name_admin_bar'        => __( 'Advisor', 'the-source' ),
		'archives'              => __( 'Advisor', 'the-source' ),
		'attributes'            => __( 'Item Attributes', 'the-source' ),
		'parent_item_colon'     => __( 'Parent Item:', 'the-source' ),
		'all_items'             => __( 'All advisors', 'the-source' ),
		'add_new_item'          => __( 'Add advisor', 'the-source' ),
		'add_new'               => __( 'Add advisor', 'the-source' ),
		'new_item'              => __( 'New advisor', 'the-source' ),
		'edit_item'             => __( 'Edit advisor', 'the-source' ),
		'update_item'           => __( 'Udpdate advisor', 'the-source' ),
		'view_item'             => __( 'Display advisor', 'the-source' ),
		'view_items'            => __( 'Display advisors', 'the-source' ),
		'search_items'          => __( 'Search advisors', 'the-source' ),
		'not_found'             => __( 'No advisors', 'the-source' ),
		'not_found_in_trash'    => __( 'No advisors in trash', 'the-source' ),
		'featured_image'        => __( 'Profile picture', 'the-source' ),
	);
	$args = array(
		'label'                 => __( 'Advisors', 'the-source' ),
		'description'           => __( 'Advisors', 'the-source' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'editor', 'custom-fields', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 55,
		'menu_icon'             => 'dashicons-nametag',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'advisors', $args );

}


/***************************************************************
	Custom Post Type : Contact requests
/***************************************************************/
add_action( 'init', 'kasutan_contact_requests_post_type', 0 );
function kasutan_contact_requests_post_type() {

	$labels = array(
		'name'                  => _x( 'Contact requests', 'Post Type General Name', 'the-source' ),
		'singular_name'         => _x( 'Contact request', 'Post Type Singular Name', 'the-source' ),
		'menu_name'             => __( 'Contact requests', 'the-source' ),
		'name_admin_bar'        => __( 'Contact request', 'the-source' ),
		'archives'              => __( 'Contact requests archives', 'the-source' ),
		'attributes'            => __( 'Item Attributes', 'the-source' ),
		'parent_item_colon'     => __( 'Parent Item:', 'the-source' ),
		'all_items'             => __( 'All contact requests', 'the-source' ),
		'add_new_item'          => __( 'Add contact request', 'the-source' ),
		'add_new'               => __( 'Add contact request', 'the-source' ),
		'new_item'              => __( 'New contact request', 'the-source' ),
		'edit_item'             => __( 'Edit contact request', 'the-source' ),
		'update_item'           => __( 'Udpdate contact request', 'the-source' ),
		'view_item'             => __( 'Display contact request', 'the-source' ),
		'view_items'            => __( 'Display contact requests', 'the-source' ),
		'search_items'          => __( 'Search contact requests', 'the-source' ),
		'not_found'             => __( 'No contact requests', 'the-source' ),
		'not_found_in_trash'    => __( 'No contact requests in trash', 'the-source' ),
	);
	$args = array(
		'label'                 => __( 'Contact requests', 'the-source' ),
		'description'           => __( 'Contact requests', 'the-source' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'custom-fields', 'author' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 60,
		'menu_icon'             => 'dashicons-email',
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'contact_requests', $args );

}
