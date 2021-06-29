<?php
/* Function to display advisor card in different contexts */

function kasutan_display_advisor($post_id,$context) {
	if(!function_exists('get_field')) {
		return;
	}
	$name=get_the_title($post_id);
	printf('<div class="advisor advisor-%s">',$context);
	if($context==='product') {
		$portrait=get_the_post_thumbnail( $post_id, 'thumbnail');
		printf('<div class="portrait">%s</div>',$portrait);
		echo '<div class="text"><p class="above-name">Your advisor</p>';
		printf('<p class="name">%s</p></div>',$name);
	}
	echo '</div>';
}