<?php



/**
* Copyright et liens légaux
*
*/
add_action( 'tha_footer_bottom', 'kasutan_copyright' );
function kasutan_copyright() {
	printf('<a class="backtotop" href="#main-content"><span class="screen-reader-text">%s</span>%s</a>',
		esc_html__('Back to top','the-source'),
		kasutan_picto( array( 'icon' => 'backtotop' , 'size' => false) )
	);

	echo '<div class="copyright">';
		printf('<span class="annee">&copy; %s %s</span>',date('Y'), get_option('blogname'));
		if( has_nav_menu( 'footer-legal' ) ) {
			wp_nav_menu( array( 'theme_location' => 'footer-legal', 'menu_id' => 'footer-legal', 'container_class' => 'nav-footer-legal' ) );
		}
	echo '</div>';
}