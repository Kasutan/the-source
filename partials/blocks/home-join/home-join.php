<?php 
/**
* Template pour le bloc home-join
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/

if(function_exists('get_field') ) : 

	if(array_key_exists('className',$block)) {
		$className=esc_attr($block["className"]);
	} else $className='';

	$anchor = '';
	if( !empty( $block['anchor'] ) )
		$anchor = ' id="' . sanitize_title( $block['anchor'] ) . '"';
	
	
		$title=wp_kses_post(get_field('title'));
		$subtitle=wp_kses_post(get_field('subtitle'));
		$url=esc_url(get_field('url'));
		$label=wp_kses_post(get_field('label'));
		

		printf('<section class="home-join acf %s"%s>',$className,$anchor);
			printf('<p class="h1 ">%s</p>',$title);
			printf('<p class="subtitle h3">%s</p>',$subtitle);

			if(have_rows('offers')) : 
				echo '<ul class="offers">';
				while(have_rows('offers')) : the_row();
					printf('<li class="offer"><div class="picto"><svg xmlns="http://www.w3.org/2000/svg" width="19.256" height="13.634" viewBox="0 0 19.256 13.634">
					<path d="M18652.3,3162.5a63.8,63.8,0,0,1,4.8,6.4l10.8-6.4" transform="translate(-18861.984 1449.051) rotate(-14)" fill="none" stroke="#fff" stroke-width="3"/>
					</svg></div><span>%s</span></li>',
						wp_kses_post(get_sub_field('offer'))
					);
						
				endwhile;
				echo '</ul>';
			endif;
		
			if($url && $label) {
				printf('<a href="%s" class="button white">%s</a>',$url,$label);
			}

		echo '</section>';



endif;