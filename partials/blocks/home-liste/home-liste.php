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
	

	if(have_rows('list')) {
		printf('<section class="home-liste acf %s"%s>',$className,$anchor);
		while(have_rows('list')) {
			the_row();
			$titre=wp_kses_post(get_sub_field('titre'));
			$texte=wp_kses_post(get_sub_field('texte'));
			printf('<p class="titre">%s</p>',$titre);
			printf('<p class="texte">%s</p>',$texte);
		}
		

		echo '</section>';
	}
	


endif;