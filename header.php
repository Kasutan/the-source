<?php
/**
 * Site Header
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

echo '<!DOCTYPE html>';
tha_html_before();
echo '<html ' . get_language_attributes() . '>';

echo '<head>';
	tha_head_top();
	wp_head();
	tha_head_bottom();
echo '</head>';

echo '<body class="' . join( ' ', get_body_class() ) . '">';
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
tha_body_top();
echo '<div class="site-container">';
	echo '<a class="skip-link screen-reader-text" href="#main-content">' . esc_html__( 'Skip to content', 'ea-starter' ) . '</a>';

	tha_header_before();
	echo '<header class="site-header" role="banner">';
		tha_header_left();

		echo '<div class="title-area">';
		$logo_tag='p';
		if(is_front_page()) {
			$logo_tag='h1';
		}
			if(has_custom_logo()) {
				printf('<%s class="site-title">%s<span class="screen-reader-text">%s</span></%s>',
					$logo_tag,
					get_custom_logo(),	
					get_bloginfo( 'name'),
					$logo_tag,	
				);
			} else {
				printf('<%s class="site-title">%s</%s>',
					$logo_tag,
					get_bloginfo( 'name'),
					$logo_tag,	
				);
			}
			
		echo '</div>';

		tha_header_right();
	echo '</header>';
	tha_header_after();
	echo '<div class="site-inner" id="main-content">';
