<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/* Function to display advisor card in different contexts */

function kasutan_display_advisor($post_id,$context) {
	if(!function_exists('get_field')) {
		return;
	}
	$name=get_the_title($post_id);

	if($context==='product') {
		printf('<div class="advisor advisor-%s">',$context);
			$portrait=get_the_post_thumbnail( $post_id, 'thumbnail');
			printf('<div class="portrait">%s</div>',$portrait);
			echo '<div class="text"><p class="above-name">Your advisor</p>';
			printf('<p class="name">%s</p></div>',$name);
		echo '</div>';
	} elseif($context==='about') {
		$portrait=get_the_post_thumbnail( $post_id, 'medium');
		$role=wp_kses_post(get_field('title',$post_id));
		$email=antispambot(esc_attr(get_field('email',$post_id)));
		printf('<li class="advisor advisor-%s">',$context);
			printf('<div class="portrait">%s</div>',$portrait);
			printf('<p class="name">%s</p>',$name);
			if($role) printf('<p class="role">%s</p>',$role);
			printf('<div class="bio">%s</div>',get_the_content(null, false,$post_id));
			printf('<a href="mailto:%s?subject=The Source - contact an expert" title="Send an email to %s">%s</a>',$email,$name,kasutan_picto(array('icon'=>'send-message','size'=>false)));

		echo '</li>';
	} elseif ($context==='faq') {
		$portrait=get_the_post_thumbnail( $post_id, 'medium');
		$area=wp_kses_post(get_field('title',$post_id));
		$email=antispambot(esc_attr(get_field('email',$post_id)));
		printf('<li class="advisor advisor-%s">',$context);
			printf('<div class="portrait">%s</div>',$portrait);
			printf('<p class="name">%s</p>',$name);
			if($area) printf('<p class="area">Your expert for: %s</p>',$area);
			printf('<a href="mailto:%s?subject=The Source - contact an expert" title="Send an email to %s">%s</a>',$email,$name,kasutan_picto(array('icon'=>'send-message','size'=>false)));

		echo '</li>';
	}
}