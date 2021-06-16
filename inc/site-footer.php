<?php



/**
* Copyright et liens légaux
*
*/
add_action( 'tha_footer_bottom', 'kasutan_copyright' );
function kasutan_copyright() {
	echo '<div class="copyright">';
		printf('<span class="annee">%s</span>',date('Y'));
		printf('<span class="titre">%s</span>',get_option('blogname'));
		if( has_nav_menu( 'footer-legal' ) ) {
			wp_nav_menu( array( 'theme_location' => 'footer-legal', 'menu_id' => 'footer-legal', 'container_class' => 'nav-footer-legal' ) );
		}
	echo '</div>';
}