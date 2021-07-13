<?php
/**
 * kasutan functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package kasutan
 */

define( 'KASUTAN_STARTER_VERSION', filemtime( get_template_directory() . '/style.css' ) );


// General cleanup
include_once( get_template_directory() . '/inc/wordpress-cleanup.php' );

//Paid membership pro
include_once( get_template_directory() . '/inc/pmp/lock-content.php' );
include_once( get_template_directory() . '/inc/pmp/checkout-fields.php' );
include_once( get_template_directory() . '/inc/pmp/pdf-invoice.php' );
include_once( get_template_directory() . '/inc/pmp/vat-tax.php' );
include_once( get_template_directory() . '/inc/pmp/free-trial.php' );
//include_once( get_template_directory() . '/inc/pmp/my-account.php' );


// Theme
include_once( get_template_directory() . '/inc/tha-theme-hooks.php' );
include_once( get_template_directory() . '/inc/layouts.php' );
include_once( get_template_directory() . '/inc/helper-functions.php' );
include_once( get_template_directory() . '/inc/navigation.php' );
include_once( get_template_directory() . '/inc/loop.php' );
include_once( get_template_directory() . '/inc/template-tags.php' );
include_once( get_template_directory() . '/inc/site-header.php' );
include_once( get_template_directory() . '/inc/site-footer.php' );


// Editor
include_once( get_template_directory() . '/inc/disable-editor.php' );
include_once( get_template_directory() . '/inc/tinymce.php' );

// Functionality
include_once( get_template_directory() . '/inc/login-logo.php' );
include_once( get_template_directory() . '/inc/social-links.php' );
include_once( get_template_directory() . '/inc/traductions.php' );

// Plugin Support
include_once( get_template_directory() . '/inc/acf.php' );

if ( ! function_exists( 'kasutan_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function kasutan_setup() {
		
		// Body open hook
		add_theme_support( 'body-open' );
		
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails', array('post','page','exceptional_assets','virtuous_companies','philantropic_projects','advisors'));

		register_nav_menus( array(
			'products' => 'Product categories',
			'the-source' => 'The Source links',
			'my-account' => 'My account desktop',
			'my-account-mobile' => 'My account mobile',
			'footer-legal' => 'Legal links in footer'
		) );

		//Autoriser les shortcodes dans les widgets
		add_filter( 'widget_text', 'do_shortcode' );


		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );


		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 73,
			'width'       => 330,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// Gutenberg

		// -- Responsive embeds
		add_theme_support( 'responsive-embeds' );

		// -- Wide Images
		add_theme_support( 'align-wide' );

		// -- Disable custom font sizes
		add_theme_support( 'disable-custom-font-sizes' );

		/**
		* Font sizes in editor
		* https://www.billerickson.net/building-a-gutenberg-website/#editor-font-sizes
		*/
		add_theme_support( 'editor-font-sizes', array(
			array(
				'name' => __( 'Small', 'the-source' ),
				'size' => 14,
				'slug' => 'small'
			),
			array(
				'name' => __( 'Normal', 'the-source' ),
				'size' => 20,
				'slug' => 'normal'
			),
			array(
				'name' => __( 'Big', 'the-source' ),
				'size' => 30,
				'slug' => 'big'
			),
			array(
				'name' => __( 'Huge', 'the-source' ),
				'size' => 60,
				'slug' => 'huge'
			)
		) );

		

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'editor-styles.css' );
	}
endif;
add_action( 'after_setup_theme', 'kasutan_setup' );

/**
* Register color palette for Gutenberg editor.
*/
require get_template_directory() . '/inc/colors.php';

// -- Disable Custom Colors
add_theme_support( 'disable-custom-colors' );


/**
 * Enqueue scripts and styles.
 */
function kasutan_scripts() {
	wp_enqueue_style( 'thesource-owl-carousel', get_template_directory_uri() . '/lib/owlcarousel/owl.carousel.min.css',array(),'2.3.4');
	wp_enqueue_style( 'thesource-style', get_stylesheet_uri(),array(), filemtime( get_template_directory() . '/style.css' ) );
	wp_enqueue_style( 'thesource-google-font', 'https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:ital,wght@0,300;0,700;1,300&display=swap');

	// Move jQuery to footer
	if( ! is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', includes_url( '/js/jquery/jquery.js' ), false, NULL, true );
		wp_enqueue_script( 'jquery' );
	}

	wp_enqueue_script( 'thesource-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'thesource-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	wp_enqueue_script( 'thesource-owl-carousel',get_template_directory_uri() . '/lib/owlcarousel/owl.carousel.min.js', array('jquery'), '2.3.4', true );

	wp_register_script( 'thesource-list',get_template_directory_uri() . '/lib/list/list.min.js', array('jquery'), '1.5.0', true );

	wp_enqueue_script( 'thesource-scripts', get_template_directory_uri() . '/js/main.js', array('jquery', 'thesource-owl-carousel','thesource-list'), '', true );
}
add_action( 'wp_enqueue_scripts', 'kasutan_scripts' );

/**
* Register and enqueue a custom stylesheet in the WordPress admin.
*/
function kasutan_enqueue_custom_admin_style() {
	wp_register_style( 'zs_wp_admin_css', get_template_directory_uri() . '/admin-styles.css', false, '1.0.0' );
	wp_enqueue_style( 'zs_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'kasutan_enqueue_custom_admin_style' );


/**
* Image sizes. 
* https://developer.wordpress.org/reference/functions/add_image_size/
*/
/*Toutes tailles réglées en BO : 
	thumbnail 200 pour portraits
	medium 308 large, hauteur libre (masonry) 
	medium_large 768 (product)
	large 1600 large, hauteur libre (product with zoom) 

*/

/**
* CPT, custom fields, custom taxonomies et functions associées
*/
require_once( 'inc/cpt/cpt-taxonomies.php' );
require_once( 'inc/cpt/advisors.php' );
require_once( 'inc/cpt/products.php' );


/**
 * Afficher tous les résultats sans pagination sur page résultats de recherche
 */
function kasutan_remove_pagination( $query ) {
	if ( $query->is_main_query() &&  get_query_var( 's', 0 ) ) {
		$query->query_vars['nopaging'] = 1;
		$query->query_vars['posts_per_page'] = -1;
	}
}
add_action( 'pre_get_posts', 'kasutan_remove_pagination' );


/**
* Template Hierarchy
*
*/
function ea_template_hierarchy( $template ) {

	if( is_home() || is_search() )
		$template = get_query_template( 'archive' );
	return $template;
}
add_filter( 'template_include', 'ea_template_hierarchy' );
