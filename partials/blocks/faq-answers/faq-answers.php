<?php 
/**
* Template pour le bloc faq-answers
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

	if( !empty( $block['anchor'] ) )
		$id=sanitize_title( $block['anchor'] );
	else $id=rand();

	if(have_rows('answers')) : 
		$title=wp_kses_post(get_field('title'));

		printf('<section class="faq-answers acf %s" id="%s">',$className,$id);
			echo '<div class="bloc-titre">';
				printf('<p class="surtitre">%s</p>',esc_html__('About','the-source'));
				printf('<h2 class="titre no-dots">%s</h2>',$title);
			echo '</div>';
			echo '<ul class="accordion" data-open-first="false">';
			$i=1;
			while(have_rows('answers')) : the_row();
				?>
				<li class="accordion__item">
					<h3 class="accordion__title">
						<button aria-expanded="false" class="accordion__trigger" aria-controls="accordion-<?php echo $id;?>-content-<?php echo $i;?>" id="accordion-<?php echo $id;?>-trigger-<?php echo $i;?>">
							<span class="accordion__question">
								<?php echo wp_kses_post(get_sub_field('question'));?>
							</span>
							<span class="accordion__handle">
								<?php echo kasutan_picto(array('icon'=>'arrow-down','size'=>false));?>
							</span>
						</button>
					</h3>
					<div id="accordion-<?php echo $id;?>-content-<?php echo $i;?>" role="region" class="accordion__content" aria-labelledby="accordion-<?php echo $id;?>-trigger-<?php echo $i;?>">
						<?php echo wp_kses_post(get_sub_field('answer'));?>
					</div>
				</li>
				<?php
				$i++;
			endwhile;
			echo '</ul>';
			
		echo '</section>';
		
		echo '<hr class="wp-block-separator">';

	
	endif;

endif;