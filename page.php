<?php
/**
 * Page
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

// Breadcrumbs
add_action( 'ea_content_header_after', 'kasutan_fil_ariane', 5 );

// Build the page
require get_template_directory() . '/index.php';





