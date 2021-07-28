<?php 
/**
* Template pour le bloc home-inspiration
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
	
	
		$title=wp_kses_post(get_field('title'));

	printf('<section class="home-inspiration acf %s"%s>',$className,$anchor);
		echo '<div class="fond"></div>';
		printf('<h2 class="h1 dots">%s</h2>',$title);

		if(have_rows('offers')) : 
			echo '<ul class="offers">';
			while(have_rows('offers')) : the_row();
				$link=esc_url(get_sub_field('link'));
				$image=esc_attr(get_sub_field('image'));
				$name=wp_kses_post(get_sub_field('name'));
				printf('<li><a class="offer" href="%s" style="background-image:url(%s)">',$link,wp_get_attachment_url( $image ));
					
					echo '<div class="filtre"></div>';
					echo '<div class="surtitre">Invest in</div>';
					printf('<h3 class="name">%s</h3>',$name);
					printf('<div class="button">Browse</div>');
					printf('<div class="decor">%s</div>',kasutan_picto(array('icon'=>'sunrise-white','size'=>163)));
				echo '</a></li>';
					
			endwhile;
			echo '</ul>';
		endif;

	echo '</section>';
endif;