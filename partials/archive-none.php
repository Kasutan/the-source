<?php
/**
 * 404 / No Results partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/


echo '<section class="no-results not-found">';

	echo '<header class="entry-header"><h1 class="entry-title">' . esc_html__( 'Not found', 'the-source' ) . '</h1></header>';
	echo '<div class="entry-content">';

	if ( is_search() ) {

		echo '<p>' . esc_html__( 'Sorry, no result was found. Please try again with different keywords.', 'the-source' ) . '</p>';
		get_search_form();

	} else {

		echo '<p>' . esc_html__( 'No content was found here. Please try a search.', 'the-source' ) . '</p>';
		get_search_form();
	}

	echo '</div>';
echo '</section>';
