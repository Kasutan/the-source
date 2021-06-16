<?php
/**
 * WordPress Cleanup
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
* Dont Update the Theme
*
* If there is a theme in the repo with the same name, this prevents WP from prompting an update.
*
* @since  1.0.0
* @author Bill Erickson
* @link   http://www.billerickson.net/excluding-theme-from-updates
* @param  array $r Existing request arguments
* @param  string $url Request URL
* @return array Amended request arguments
*/
function ea_dont_update_theme( $r, $url ) {
	if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) )
		return $r; // Not a theme update request. Bail immediately.
	$themes = json_decode( $r['body']['themes'] );
	$child = get_option( 'stylesheet' );
	unset( $themes->themes->$child );
	$r['body']['themes'] = json_encode( $themes );
	return $r;
}
add_filter( 'http_request_args', 'ea_dont_update_theme', 5, 2 );

/**
 * Header Meta Tags
 *
 */
function ea_header_meta_tags() {
	echo '<meta charset="' . get_bloginfo( 'charset' ) . '">';
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
	echo '<link rel="profile" href="http://gmpg.org/xfn/11">';
	echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '">';
}
add_action( 'wp_head', 'ea_header_meta_tags' );

/**
 * Dequeue jQuery Migrate
 *
 */
function ea_dequeue_jquery_migrate( &$scripts ){
	if( !is_admin() ) {
		$scripts->remove( 'jquery');
		$scripts->add( 'jquery', false, array( 'jquery-core' ), '1.10.2' );
	}
}
add_filter( 'wp_default_scripts', 'ea_dequeue_jquery_migrate' );

/**
 * Singular body class
 *
 */
function ea_singular_body_class( $classes ) {
	if( is_singular() )
		$classes[] = 'singular';
	return $classes;
}
add_filter( 'body_class', 'ea_singular_body_class' );



/**
 * Clean Nav Menu Classes
 *
 */
function ea_clean_nav_menu_classes( $classes ) {
	if( ! is_array( $classes ) )
		return $classes;

	foreach( $classes as $i => $class ) {

		// Remove class with menu item id
		$id = strtok( $class, 'menu-item-' );
		if( 0 < intval( $id ) )
			unset( $classes[ $i ] );

		// Remove menu-item-type-*
		if( false !== strpos( $class, 'menu-item-type-' ) )
			unset( $classes[ $i ] );

		// Remove menu-item-object-*
		if( false !== strpos( $class, 'menu-item-object-' ) )
			unset( $classes[ $i ] );

		// Change page ancestor to menu ancestor
		if( 'current-page-ancestor' == $class ) {
			$classes[] = 'current-menu-ancestor';
			unset( $classes[ $i ] );
		}
	}

	// Remove submenu class if depth is limited
	if( isset( $args->depth ) && 1 === $args->depth ) {
		$classes = array_diff( $classes, array( 'menu-item-has-children' ) );
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'ea_clean_nav_menu_classes', 5 );

/**
 * Clean Post Classes
 *
 */
function ea_clean_post_classes( $classes ) {

	if( ! is_array( $classes ) )
		return $classes;

	$allowed_classes = array(
  		'hentry',
  		'type-' . get_post_type(),
   	);

	return array_intersect( $classes, $allowed_classes );
}
add_filter( 'post_class', 'ea_clean_post_classes', 5 );

/**
 * Archive Title, remove prefix
 *
 */
function ea_archive_title_remove_prefix( $title ) {
	$title_pieces = explode( ': ', $title );
	if( count( $title_pieces ) > 1 ) {
		unset( $title_pieces[0] );
		$title = join( ': ', $title_pieces );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'ea_archive_title_remove_prefix' );

/**
 * Staff comment class
 *
 */
function ea_staff_comment_class( $classes, $class, $comment_id, $comment, $post_id ) {
	if( empty( $comment->user_id ) )
		return $classes;
	$staff_roles = array( 'comment_manager', 'author', 'editor', 'administrator' );
	$staff_roles = apply_filters( 'ea_staff_roles', $staff_roles );
	$user = get_userdata( $comment->user_id );
	if( !empty( array_intersect( $user->roles, $staff_roles ) ) )
		$classes[] = 'staff';
	return $classes;
}
add_filter( 'comment_class', 'ea_staff_comment_class', 10, 5 );

/**
 * Remove avatars from comment list
 *
 */
function ea_remove_avatars_from_comments( $avatar ) {
	global $in_comment_loop;
	return $in_comment_loop ? '' : $avatar;
}
add_filter( 'get_avatar', 'ea_remove_avatars_from_comments' );

/**
 * Comment form, button class
 *
 */
function ea_comment_form_button_class( $args ) {
	$args['class_submit'] = 'submit wp-block-button__link';
	return $args;
}
add_filter( 'comment_form_defaults', 'ea_comment_form_button_class' );

/**
 * Excerpt More
 *
 */
function ea_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'ea_excerpt_more' );

/**
* Excerpt Length
*/
function kasutan_custom_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'kasutan_custom_excerpt_length', 999 );


// Remove inline CSS for emoji
remove_action( 'wp_print_styles', 'print_emoji_styles' );



/***************************************************************
Remove WP compression for images - there's a plugin for that
***************************************************************/
add_filter( 'jpeg_quality', 'smashing_jpeg_quality' );
function smashing_jpeg_quality() {
return 100;
}

/***************************************************************
				Remove image link
***************************************************************/
function wpb_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	
	if ($image_set !=='none') {
		update_option('image_default_link_type', 'none');
	}
}
add_action('admin_init', 'wpb_imagelink_setup', 10);

/***************************************************************
	Enable shortcodes in widgets
/***************************************************************/
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter('widget_text','do_shortcode');

/***************************************************************
						Clean header
/***************************************************************/
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
//Si on n'utilise pas les commentaires :
function clean_header(){ wp_deregister_script( 'comment-reply' ); } add_action('init','clean_header');

/***************************************************************
	Hide admin author page
/***************************************************************/
function bwp_template_redirect()
{
if (is_author())
{
wp_redirect( home_url() ); exit;
}
}
add_action('template_redirect', 'bwp_template_redirect');



//Disable Auto Update Notification Emails for Plugins and themes
add_filter( 'auto_plugin_update_send_email', '__return_false' );
add_filter( 'auto_theme_update_send_email', '__return_false' );


//Disable Gutenberg tips and default full screen mode
function kasutan_remove_gutenberg_tips() {
	$script = "
jQuery(document).ready(function(){
	var is_tip_visible = wp.data.select( 'core/nux' ).areTipsEnabled()
	if (is_tip_visible) {
		wp.data.dispatch( 'core/nux' ).disableTips();
	}
});	
	";
	wp_add_inline_script( 'wp-blocks', $script );
}
add_action( 'enqueue_block_editor_assets', 'kasutan_remove_gutenberg_tips' );

function kasutan_disable_editor_fullscreen_by_default() {
	$script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
	wp_add_inline_script( 'wp-blocks', $script );
}
add_action( 'enqueue_block_editor_assets', 'kasutan_disable_editor_fullscreen_by_default' );