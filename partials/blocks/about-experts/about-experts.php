<?php 
/**
* Template pour le bloc about-experts
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/

if(function_exists('get_field')) : 

	if(array_key_exists('className',$block)) {
		$className=esc_attr($block["className"]);
	} else $className='';

	$anchor = '';
	if( !empty( $block['anchor'] ) )
		$anchor = ' id="' . sanitize_title( $block['anchor'] ) . '"';

	$advisors=get_field('advisors');
	if(!empty($advisors)) :

		$title=wp_kses_post(get_field('title'));

		echo '<section class="acf about-experts">';
			if($title) printf('<h2 class="no-dots title text-center">%s</h2>',$title);

			echo '<ul class="advisors grid-2">';

				foreach($advisors as $post_id) {
					kasutan_display_advisor($post_id,'about');
				}

			echo '</ul>';

		echo '</section>';

	endif;

endif;