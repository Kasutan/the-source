<?php 
/**
* Template pour le bloc xxxxx
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
	
	$texte=wp_kses_post(get_field('texte'));
	$name=wp_kses_post(get_field('name'));
	$role=wp_kses_post(get_field('role'));
	$image=esc_attr(get_field('image'));


	printf('<section class="home-quote acf %s"%s>',$className,$anchor);
	
		
		printf('<q class="texte">%s</q>',$texte);

		printf('<div class="image">%s</div>',wp_get_attachment_image($image,'medium'));

		printf('<p class="name"><strong>%s</strong></p>',$name);
		printf('<p class="role">%s</p>',$role);

	

	echo '</section>';
	


endif;