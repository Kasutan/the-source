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
	
	$texte_section=wp_kses_post(get_field('texte_section'));
	$titre_section=wp_kses_post(get_field('titre_section'));


	printf('<section class="home-ethics acf %s"%s>',$className,$anchor);
	
		
		printf('<h2 class="titre-section">%s</h2>',$titre_section);

		if(have_rows('items')) {
			echo '<ul class="items">';
			while(have_rows('items')) {
				the_row();
				$titre=wp_kses_post(get_sub_field('titre'));
				$texte=wp_kses_post(get_sub_field('texte'));
				echo '<li class="item">';
					printf('<p class="titre"><strong>%s</strong></p>',$titre);
					printf('<p class="texte">%s</p>',$texte);
				echo '</li>';
			}

			echo '</ul>';
		}

		printf('<p class="texte-section"><strong>%s</strong></p>',$texte_section);

	echo '</section>';

endif;