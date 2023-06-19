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
		$small_text=wp_kses_post(get_field('small_text'));

		printf('<section class="checkout-header acf %s"%s>',$className,$anchor);
			printf('<p class="title">%s</p>',$title);
			printf('<p class="subtitle">%s</p>',$subtitle);

			if(have_rows('offers')) : 
				echo '<ul class="offers">';
				while(have_rows('offers')) : the_row();
					printf('<li class="offer"><div class="picto"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15">
					<rect  width="14" height="14" transform="translate(1 1)" fill="#173a65"/>
					<g  fill="#fff" stroke="#173a65" stroke-width="1">
						<rect width="14" height="14" stroke="none"/>
						<rect x="0.5" y="0.5" width="13" height="13" fill="none"/>
					</g>
					</svg></div><span>%s</span></li>',
						wp_kses_post(get_sub_field('offer'))
					);
						
				endwhile;
				echo '</ul>';
			endif;

			printf('<p class="text">%s</p>',$text);


			printf('<p class="small-text">%s</p>',$small_text);

			?>
			<div class="decor">
			<svg id="separateur-sun-desktop" xmlns="http://www.w3.org/2000/svg" width="335" height="97.328" viewBox="0 0 335 97.328">
			<g id="Groupe_349" data-name="Groupe 349" transform="translate(-552.5 -4619.672)" opacity="0.5">
				<g id="Groupe_347" data-name="Groupe 347" transform="translate(518.375 3272.473)">
				<path id="Tracé_247" data-name="Tracé 247" d="M167.937,39.88V38.5H189.4a27.444,27.444,0,0,0-1.711-8.782l-19.491,8.1-.528-1.274,19.527-8.12a27.5,27.5,0,0,0-5.133-7.832L167.433,35.988l-1-.954,14.68-15.441a27.57,27.57,0,0,0-8.342-5.6L165.236,33.42l-1.287-.5,7.532-19.414a27.44,27.44,0,0,0-9.543-1.751V33.84h-1.38V11.82a27.461,27.461,0,0,0-8.868,1.9l8.522,20.521-1.274.53-8.513-20.5a27.575,27.575,0,0,0-7.878,5.413l14.846,15.009-.985.968L141.6,20.691a27.535,27.535,0,0,0-5.158,8l19.709,7.849-.513,1.285-19.671-7.836a27.429,27.429,0,0,0-1.618,8.637h20.525v1.378H134.351a27.427,27.427,0,0,0,1.7,8.857l19.6-7.639.5,1.287-19.613,7.647A27.566,27.566,0,0,0,142,58.382l15.419-15,.963.99-15.4,14.984a27.568,27.568,0,0,0,7.181,4.889l8.437-19.968,1.27.537-8.453,20a27.442,27.442,0,0,0,9.136,2V44.876h1.38v22a27.431,27.431,0,0,0,9.856-1.864l-7.84-19.207,1.276-.522,7.841,19.207a27.594,27.594,0,0,0,7.936-5.353L166.1,43.684l.994-.959,14.875,15.421a27.512,27.512,0,0,0,5.271-8.025L167.328,41.5l.55-1.267,19.847,8.595a27.444,27.444,0,0,0,1.691-8.944Z" transform="translate(40.775 1356.841)" fill="#c2d6ee"/>
				<path id="Tracé_248" data-name="Tracé 248" d="M140.2,2.605l-1.274.53,7.741,18.641c.417-.193.837-.377,1.265-.548Z" transform="translate(44.53 1349.336)" fill="#c2d6ee"/>
				<path id="Tracé_249" data-name="Tracé 249" d="M150.129,21.4V0h-1.38V21.462c.441-.022.876-.067,1.321-.067.02,0,.038,0,.058,0" transform="translate(52.585 1347.199)" fill="#c2d6ee"/>
				<path id="Tracé_250" data-name="Tracé 250" d="M163.556,2.567l-1.287-.5L154.75,21.451c.431.16.868.309,1.291.491Z" transform="translate(57.507 1348.895)" fill="#c2d6ee"/>
				<path id="Tracé_251" data-name="Tracé 251" d="M161,23.565,174.76,9.089l-1-.954L160.042,22.564c.329.322.641.663.954,1" transform="translate(61.847 1353.872)" fill="#c2d6ee"/>
				<path id="Tracé_252" data-name="Tracé 252" d="M164.609,27.829h19.866v-1.38H164.6c.007.275.042.541.042.817,0,.189-.025.373-.029.562" transform="translate(65.582 1368.893)" fill="#c2d6ee"/>
				<path id="Tracé_253" data-name="Tracé 253" d="M144.234,26.519H122.3V27.9h21.933c-.005-.231-.035-.457-.035-.69s.029-.459.035-.69" transform="translate(30.892 1368.95)" fill="#c2d6ee"/>
				<path id="Tracé_254" data-name="Tracé 254" d="M148.749,42V62.876h1.38V42.068c-.02,0-.038,0-.058,0-.446,0-.881-.046-1.321-.067" transform="translate(52.585 1381.651)" fill="#c2d6ee"/>
				<path id="Tracé_255" data-name="Tracé 255" d="M146.173,23.062,130.916,7.636l-.985.97,15.3,15.464c.313-.339.615-.684.945-1.008" transform="translate(37.15 1353.462)" fill="#c2d6ee"/>
				<path id="Tracé_256" data-name="Tracé 256" d="M124.876,16.737l-.511,1.283,19.795,7.885c.155-.435.3-.876.475-1.3Z" transform="translate(32.585 1360.927)" fill="#c2d6ee"/>
				<path id="Tracé_257" data-name="Tracé 257" d="M124.368,39.887l.5,1.287,19.853-7.739c-.18-.422-.326-.861-.486-1.292Z" transform="translate(32.587 1373.562)" fill="#c2d6ee"/>
				<path id="Tracé_258" data-name="Tracé 258" d="M145.09,37.372l-14.5,14.114.963.99L146.075,38.34c-.335-.317-.666-.635-.985-.968" transform="translate(37.688 1377.852)" fill="#c2d6ee"/>
				<path id="Tracé_259" data-name="Tracé 259" d="M138.723,59.192l1.271.535,7.843-18.562c-.428-.177-.839-.377-1.254-.573Z" transform="translate(44.361 1380.492)" fill="#c2d6ee"/>
				<path id="Tracé_260" data-name="Tracé 260" d="M162.482,59.767l1.278-.522L156.2,40.728c-.42.187-.848.355-1.278.522Z" transform="translate(57.648 1380.604)" fill="#c2d6ee"/>
				<path id="Tracé_261" data-name="Tracé 261" d="M159.984,38.233,173.81,52.567l.994-.959L160.947,37.243c-.315.337-.632.67-.963.99" transform="translate(61.799 1377.746)" fill="#c2d6ee"/>
				<path id="Tracé_262" data-name="Tracé 262" d="M181.618,41.3l.55-1.267-18.277-7.914c-.16.433-.3.874-.482,1.3Z" transform="translate(64.609 1373.545)" fill="#c2d6ee"/>
				<path id="Tracé_263" data-name="Tracé 263" d="M182.249,18l-.53-1.274-18.333,7.625c.182.42.329.857.491,1.289Z" transform="translate(64.59 1360.919)" fill="#c2d6ee"/>
				<path id="Tracé_264" data-name="Tracé 264" d="M152.691,22.827a7.137,7.137,0,1,1-7.137,7.137,7.137,7.137,0,0,1,7.137-7.137" transform="translate(49.964 1365.922)" fill="#fff"/>
				</g>
				<line id="Ligne_79" data-name="Ligne 79" x2="102" transform="translate(785.5 4668.5)" fill="none" stroke="#c2d6ee" stroke-width="1"/>
				<line id="Ligne_80" data-name="Ligne 80" x2="102" transform="translate(552.5 4668.5)" fill="none" stroke="#c2d6ee" stroke-width="1"/>
			</g>
			</svg>
			</div>
			<?php


		echo '</section>';

	endif;


endif;