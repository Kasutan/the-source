<?php 
/**
* Template pour le bloc checkout-header
*
* @param   array $block The block settings and attributes.
* @param   string $content The block inner HTML (empty).
* @param   bool $is_preview True during AJAX preview.
* @param   (int|string) $post_id The post ID this block is saved to.
*/

if(function_exists('get_field') && function_exists('pmpro_hasMembershipLevel')) : 

	if(array_key_exists('className',$block)) {
		$className=esc_attr($block["className"]);
	} else $className='';

	$anchor = '';
	if( !empty( $block['anchor'] ) )
		$anchor = ' id="' . sanitize_title( $block['anchor'] ) . '"';
	
	//On vérifie sur ce bloc est celui du level actuellement sélectionné pour le checkout
	global $pmpro_level;
	if( $pmpro_level->id == esc_attr(get_field('level')) ) : 
		$title=wp_kses_post(get_field('title'));
		$subtitle=wp_kses_post(get_field('subtitle'));
		$text=wp_kses_post(get_field('text'));

		printf('<section class="checkout-header acf %s"%s>',$className,$anchor);
			echo '<div class="fond"></div>';
			printf('<p class="h1 dots">%s</p>',$title);
			printf('<p class="subtitle h3">%s</p>',$subtitle);

			if(have_rows('offers')) : 
				echo '<ul class="offers">';
				while(have_rows('offers')) : the_row();
					printf('<li class="offer"><div class="circle"></div>%s</li>',
						wp_kses_post(get_sub_field('offer'))
					);
						
				endwhile;
				echo '</ul>';
			endif;

			printf('<p class="text">%s</p>',$text);

			printf('<div class="decor">%s</div>',kasutan_picto(array('icon'=>'sunrise-white','size'=>163)));

		echo '</section>';

	endif;


endif;