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
		'name'                  => _x( 'Exceptional Assets', 'Post Type General Name', 'ea-starter' ),
		'singular_name'         => _x( 'Asset', 'Post Type Singular Name', 'ea-starter' ),
		'menu_name'             => __( 'Exceptional Assets', 'ea-starter' ),
		'name_admin_bar'        => __( 'Exceptional Assets', 'ea-starter' ),
		'archives'              => __( 'Exceptional Assets Archives', 'ea-starter' ),
		'attributes'            => __( 'Item Attributes', 'ea-starter' ),
		'parent_item_colon'     => __( 'Parent Item:', 'ea-starter' ),
		'all_items'             => __( 'All assets', 'ea-starter' ),
		'add_new_item'          => __( 'Add asset', 'ea-starter' ),
		'add_new'               => __( 'Add asset', 'ea-starter' ),
		'new_item'              => __( 'New asset', 'ea-starter' ),
		'edit_item'             => __( 'Edit asset', 'ea-starter' ),
		'update_item'           => __( 'Udpdate asset', 'ea-starter' ),
		'view_item'             => __( 'Display asset', 'ea-starter' ),
		'view_items'            => __( 'Display assets', 'ea-starter' ),
		'search_items'          => __( 'Search assets', 'ea-starter' ),
		'not_found'             => __( 'No assets', 'ea-starter' ),
		'not_found_in_trash'    => __( 'No assets in trash', 'ea-starter' ),
		'featured_image'        => __( 'Featured image for this asset', 'ea-starter' ),
	);
	$args = array(
		'label'                 => __( 'Assets', 'ea-starter' ),
		'description'           => __( 'Assets', 'ea-starter' ),
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
		'name'                  => _x( 'Virtuous companies', 'Post Type General Name', 'ea-starter' ),
		'singular_name'         => _x( 'Company', 'Post Type Singular Name', 'ea-starter' ),
		'menu_name'             => __( 'Virtuous companies', 'ea-starter' ),
		'name_admin_bar'        => __( 'Virtuous company', 'ea-starter' ),
		'archives'              => __( 'Companies Archives', 'ea-starter' ),
		'attributes'            => __( 'Item Attributes', 'ea-starter' ),
		'parent_item_colon'     => __( 'Parent Item:', 'ea-starter' ),
		'all_items'             => __( 'All companies', 'ea-starter' ),
		'add_new_item'          => __( 'Add a company', 'ea-starter' ),
		'add_new'               => __( 'Add a company', 'ea-starter' ),
		'new_item'              => __( 'New company', 'ea-starter' ),
		'edit_item'             => __( 'Edit company', 'ea-starter' ),
		'update_item'           => __( 'Udpdate company', 'ea-starter' ),
		'view_item'             => __( 'Display company', 'ea-starter' ),
		'view_items'            => __( 'Display companies', 'ea-starter' ),
		'search_items'          => __( 'Search companies', 'ea-starter' ),
		'not_found'             => __( 'No companies', 'ea-starter' ),
		'not_found_in_trash'    => __( 'No companies in trash', 'ea-starter' ),
		'featured_image'        => __( 'Featured image for this company', 'ea-starter' ),
	);
	$args = array(
		'label'                 => __( 'Virtuous Companies', 'ea-starter' ),
		'description'           => __( 'Virtuous Companies', 'ea-starter' ),
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
		'name'                  => _x( 'Philantropic projects', 'Post Type General Name', 'ea-starter' ),
		'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'ea-starter' ),
		'menu_name'             => __( 'Philantropic projects', 'ea-starter' ),
		'name_admin_bar'        => __( 'Philantropic project', 'ea-starter' ),
		'archives'              => __( 'Projects Archives', 'ea-starter' ),
		'attributes'            => __( 'Item Attributes', 'ea-starter' ),
		'parent_item_colon'     => __( 'Parent Item:', 'ea-starter' ),
		'all_items'             => __( 'All projects', 'ea-starter' ),
		'add_new_item'          => __( 'Add a project', 'ea-starter' ),
		'add_new'               => __( 'Add a project', 'ea-starter' ),
		'new_item'              => __( 'New project', 'ea-starter' ),
		'edit_item'             => __( 'Edit project', 'ea-starter' ),
		'update_item'           => __( 'Udpdate project', 'ea-starter' ),
		'view_item'             => __( 'Display project', 'ea-starter' ),
		'view_items'            => __( 'Display projects', 'ea-starter' ),
		'search_items'          => __( 'Search projects', 'ea-starter' ),
		'not_found'             => __( 'No projects', 'ea-starter' ),
		'not_found_in_trash'    => __( 'No projects in trash', 'ea-starter' ),
		'featured_image'        => __( 'Featured image for this project', 'ea-starter' ),
	);
	$args = array(
		'label'                 => __( 'Philantropic Projects', 'ea-starter' ),
		'description'           => __( 'Philantropic Projects', 'ea-starter' ),
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
		'name'                  => _x( 'Advisors', 'Post Type General Name', 'ea-starter' ),
		'singular_name'         => _x( 'Advisor', 'Post Type Singular Name', 'ea-starter' ),
		'menu_name'             => __( 'Advisors', 'ea-starter' ),
		'name_admin_bar'        => __( 'Advisor', 'ea-starter' ),
		'archives'              => __( 'Advisor', 'ea-starter' ),
		'attributes'            => __( 'Item Attributes', 'ea-starter' ),
		'parent_item_colon'     => __( 'Parent Item:', 'ea-starter' ),
		'all_items'             => __( 'All advisors', 'ea-starter' ),
		'add_new_item'          => __( 'Add advisor', 'ea-starter' ),
		'add_new'               => __( 'Add advisor', 'ea-starter' ),
		'new_item'              => __( 'New advisor', 'ea-starter' ),
		'edit_item'             => __( 'Edit advisor', 'ea-starter' ),
		'update_item'           => __( 'Udpdate advisor', 'ea-starter' ),
		'view_item'             => __( 'Display advisor', 'ea-starter' ),
		'view_items'            => __( 'Display advisors', 'ea-starter' ),
		'search_items'          => __( 'Search advisors', 'ea-starter' ),
		'not_found'             => __( 'No advisors', 'ea-starter' ),
		'not_found_in_trash'    => __( 'No advisors in trash', 'ea-starter' ),
		'featured_image'        => __( 'Profile picture', 'ea-starter' ),
	);
	$args = array(
		'label'                 => __( 'Advisors', 'ea-starter' ),
		'description'           => __( 'Advisors', 'ea-starter' ),
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
		'name'                  => _x( 'Contact requests', 'Post Type General Name', 'ea-starter' ),
		'singular_name'         => _x( 'Contact request', 'Post Type Singular Name', 'ea-starter' ),
		'menu_name'             => __( 'Contact requests', 'ea-starter' ),
		'name_admin_bar'        => __( 'Contact request', 'ea-starter' ),
		'archives'              => __( 'Contact requests archives', 'ea-starter' ),
		'attributes'            => __( 'Item Attributes', 'ea-starter' ),
		'parent_item_colon'     => __( 'Parent Item:', 'ea-starter' ),
		'all_items'             => __( 'All contact requests', 'ea-starter' ),
		'add_new_item'          => __( 'Add contact request', 'ea-starter' ),
		'add_new'               => __( 'Add contact request', 'ea-starter' ),
		'new_item'              => __( 'New contact request', 'ea-starter' ),
		'edit_item'             => __( 'Edit contact request', 'ea-starter' ),
		'update_item'           => __( 'Udpdate contact request', 'ea-starter' ),
		'view_item'             => __( 'Display contact request', 'ea-starter' ),
		'view_items'            => __( 'Display contact requests', 'ea-starter' ),
		'search_items'          => __( 'Search contact requests', 'ea-starter' ),
		'not_found'             => __( 'No contact requests', 'ea-starter' ),
		'not_found_in_trash'    => __( 'No contact requests in trash', 'ea-starter' ),
	);
	$args = array(
		'label'                 => __( 'Contact requests', 'ea-starter' ),
		'description'           => __( 'Contact requests', 'ea-starter' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'revisions', 'custom-fields', 'author', 'editor' ),
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
