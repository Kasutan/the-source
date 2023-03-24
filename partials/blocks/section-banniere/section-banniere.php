<?php 
/**
* Template pour le bloc Section bannière
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

	$titre_2=esc_html( get_field('titre_2') );
	$titre_3=esc_html( get_field('titre_3') );
	$image=esc_attr( get_field('image') );
	$texte=wp_kses_post(get_field('texte'));
	$label_bouton=esc_html( get_field('label_bouton') );
	$cible_bouton=esc_url( get_field('cible_bouton') );
	$onglet_bouton=esc_attr( get_field('onglet_bouton') );
	$target_attr='';
	if($onglet_bouton==='blank') {
		$target_attr=sprintf(' target="_blank" rel="noopener noreferrer" title="%s"',esc_html__('Open a new tab','the-source'));
	}
	

	printf('<section class="section-banniere acf %s js-fade-in-on-visible"%s>',$className,$anchor);
		
		if($titre_2) printf('<h2 class="titre-section h1">%s</h2>',$titre_2);

		echo '<div class="banniere-wrap">';
			if($image) printf('<div class="image">%s</div>',wp_get_attachment_image( $image, 'banniere',false,array('decoding'=>'async')));
			printf('<div class="cadre">');
				if($titre_3) printf('<h3 class="h2">%s</h3>',$titre_3);
				if($texte) printf('<div class="texte">%s</div>',$texte);
				if($cible_bouton && $label_bouton) {
					printf('<a href="%s" class="button"%s>%s</a>',$cible_bouton,$target_attr,$label_bouton);
				}
			echo '</div>'; //fin .cadre 
		echo '</div>'; //fin .banniere-wrap

	echo '</section>';


endif;