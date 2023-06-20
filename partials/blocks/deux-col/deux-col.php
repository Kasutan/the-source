<?php 
/**
* Template pour le bloc deux-col
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
	
	$inverser=esc_attr(get_field('inverser'));
	if($inverser) $className.=' inverser';

	$title=wp_kses_post( get_field('title') );	
	$text=wp_kses_post( get_field('text') );
	$image_id=esc_attr(get_field('image'));
	$link=esc_url(get_field('link'));

	printf('<section class="deux-col acf %s"%s>',$className,$anchor);
		printf('<h2 class="title"><a href="%s">%s</a></h2>',$link,$title);
		printf('<div class="image">%s</div>',wp_get_attachment_image( $image_id,'large'));		
		printf('<div class="text">%s</div>',$text);
	echo '</section>';

endif;