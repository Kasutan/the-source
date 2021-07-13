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
	

	printf('<section class="xxxxx acf %s"%s>',$className,$anchor);
		
		

	echo '</section>';


endif;