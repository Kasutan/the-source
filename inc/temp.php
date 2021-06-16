<?php 
/***************************************************************
	Custom Taxonomy Type of projects
/***************************************************************/

add_action( 'init', 'create_type_projects_tag', 0 );
function create_type_projects_tag() {
// Labels part for the GUI
$labels = array(
	'name' => _x( 'Projects categories', 'taxonomy general name' ),
	'singular_name' => _x( 'Projects category', 'taxonomy singular name' ),
	'menu_name' => __( 'Projects categories' ),
); 
register_taxonomy('cat_projects','philantropic-projects',array(
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
		'name_admin_bar'        => __( 'Philantropic projects', 'the-source' ),
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
	register_post_type( 'philantropic-projects', $args );

}