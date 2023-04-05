<?php 
/**
* Template pour le bloc home-slider
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/

if(function_exists('get_field')) : 

	
	wp_enqueue_script( 'thesource-home-scripts', get_template_directory_uri() . '/js/home.js', array('jquery','thesource-owl-carousel'), '', true );

	if(array_key_exists('className',$block)) {
		$className=esc_attr($block["className"]);
	} else $className='';

	$anchor = '';
	if( !empty( $block['anchor'] ) )
		$anchor = ' id="' . sanitize_title( $block['anchor'] ) . '"';
	
	
	$texte=wp_kses_post(get_field('text'));
	$url=esc_url(get_field('url'));
	$label=wp_kses_post(get_field('label'));
	$keys=array('mobile','desktop');

	printf('<section class="home-slider acf %s"%s>',$className,$anchor);
	
		echo '<div class="slider-wrap">';
			foreach($keys as $key) {
				$size="large";
				if($key==="mobile") {
					$size="medium_large";
				}
				$galerie=get_field('galerie_'.$key);
				if($galerie) {
					printf('<div class="owl owl-carousel %s">',$key);
					foreach($galerie as $image) {
						printf('<div class="image">%s</div>',wp_get_attachment_image($image,$size));
					}
					echo '</div>';
				}
			}

		echo '</div>';

		printf('<p class="texte">%s</p>',$texte);

		if($url && $label) {
			//TODO picto
			printf('<a href="%s" class="button join-link">%s</a>',$url,$label);
		}
	

	echo '</section>';

endif;