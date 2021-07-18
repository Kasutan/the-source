<?php
/**
 * Single Product partial
 *
**/
$user_id=get_current_user_id(  );
$post_id=get_the_ID();
$post_type=get_post_type();
$taxonomy=kasutan_get_taxonomy_slug_for_cpt($post_type);
$cat=kasutan_get_closest_cat_for_product($post_id,$post_type);
$related=kasutan_get_related_products($post_id,$post_type,$taxonomy,$cat,$number=3);

if($cat->parent!==0) {
	$cat_parent=get_term($cat->parent,$taxonomy);
	$cat_siblings=kasutan_get_cat_siblings($cat->parent,$cat->term_id,$taxonomy);
} else {
	$cat_parent=$cat_siblings=false;
}


$in_selection=kasutan_is_product_in_selection($post_id,$user_id); //Is item already in user's selection ?
if($in_selection) {
	$attr_checked="checked";
	$class_selected="selected";
} else {
	$attr_checked=$class_selected="";
}

$request_sent=false; //TODO has the user already sent a request for this item (less than 6 month ago) ?
//$request_sent=true;
if($request_sent) {
	$class_sent="request-sent";
} else {
	$class_sent="";
}

if(function_exists('get_field')) {
	$images = get_field('gallery');
	$price=wp_kses_post(get_field('price'));
	$intro=wp_kses_post(get_field('intro'));
	$main_advisor=esc_attr(get_field('main_advisor'));
	$backup_advisor=esc_attr(get_field('backup_advisor'));
	$details=wp_kses_post(get_field('details'));
	$video=esc_url(get_field('video'));
	if(strpos($video,'vimeo')<=0) {
		$iframe_url=false;
	} else {
		//https://player.vimeo.com/video/38437356
		$iframe_url=str_replace('vimeo.com','player.vimeo.com/video',$video);
	}
} else {
	$images=$price=$intro=$main_advisor=$details=$iframe_url=false;
}

echo '<article class="single-product ' . join( ' ', get_post_class() ) . '">';

	if( ea_has_action( 'tha_entry_top' ) ) {
		echo '<header class="entry-header">';
		tha_entry_top(); //fil d'ariane
		echo '</header>';
	}

	echo '<div class="entry-content">';
		echo '<section class="product-top">';
			printf('<h1 class="no-dots product-title hide-for-lg">%s</h1>',get_the_title());
			echo '<div class="product-gallery">';
				if($images) {
					echo '<div class="product-carousel owl-carousel">';
					foreach ($images as $image_id) {
						echo '<div class="slide">';
								echo wp_get_attachment_image( $image_id, 'medium_large');
						echo '</div>';
					}
					echo '</div>';
					//TODO loupe qui ouvre une galerie plein écran https://humaan.com/modaal/ ou recalculate owl carousel, taille d'image large
				} else if(has_post_thumbnail()) {
					the_post_thumbnail( 'large');
				} else {
					printf('<img src="%s/icons/default.svg" alt="default image" height="308" width="308" class="default"/>',get_stylesheet_directory_uri(  ));
				}
			echo '</div>';

			echo '<div class="product-main">';
				printf('<h1 class="no-dots product-title show-for-lg">%s</h1>',get_the_title());
				if($cat) {
					printf('<a href="%s">%s</a>',get_term_link($cat,$taxonomy),$cat->name);
				}
				if($price) {
					printf('<p class="price"><strong>%s</strong></p>',$price);
				}
				if($intro) {
					$intro.=' <a href="#product-details">More details↴</a>';
					printf('<div class="intro">%s</div>',$intro);
				}

				?>
				<formgroup class="to-selection <?php echo $class_selected;?>">
					<input type="checkbox" id="js-to-selection" name="js-to-selection" class="js-to-selection" <?php echo $attr_checked;?> 
						data-product="<?php echo $post_id;?>"
						data-user="<?php echo $user_id;?>"
					>
					<label for="js-to-selection">
						<span class="add">Save this item in my selection</span>
						<span class="remove">Saved in my selection</span>
					</label>

					<p class="error" hidden>There was an error, your selection was not updated. Please try again later or contact us.</p>
				</formgroup>

				<?php if($main_advisor && function_exists('kasutan_display_advisor')) : ?>
				<formgroup class="contact-request <?php echo $class_sent;?>" >
					<legend>Interested? Let's talk about it</legend>
					<?php 
						kasutan_display_advisor($main_advisor,'product'); 
					?>
					<p class="info send">Send a Contact Request and I will get in touch with you asap.</p>
					<button class="send js-popup-open" 
					>Send <strong> a contact request</strong></button>
					
					<?php  kasutan_display_contact_popup($user_id,$main_advisor,$backup_advisor,'Product',$post_id);?>

					<button class="sent cyan" disabled><span class="check"></span><strong>Contact request sent</strong></button>
					<p class="info sent">Your Contact Request is being treated by our services.</p>

				</formgroup>

				<?php endif;


			echo '</div>';

		echo '</section>';

		if(have_rows('icons') || have_rows('table') || $details) : 
			echo '<section class="product-details" id="product-details">';
					echo '<div class="sep"></div>';
					echo '<h2 class="line">Details</h2>';
					
					if(have_rows('icons')) {
						echo '<ul class="icons show-for-md">';
						while(have_rows('icons')) {
							the_row();
							echo '<li class="icon-wrap">';
								$legend=wp_kses_post(get_sub_field('legend'));
								$icon=esc_attr(get_sub_field('image'));
								printf('<div class="icon">%s</div>',
									wp_get_attachment_image( $icon, 'thumbnail', true, array('alt'=>$legend))
								);
								printf('<p class="legend">%s</p>',$legend);
							echo '</li>';
						}
						echo '</ul>';
					}

					if($details) {
						echo '<div class="details-text">';
						echo $details;
						echo '</div>';
					}

					if(have_rows('table')) {
						echo '<ul class="table">';
						while(have_rows('table')) {
							the_row();
							echo '<li class="row">';
								$label=wp_kses_post(get_sub_field('label'));
								$value=wp_kses_post(get_sub_field('value'));
								printf('<strong>%s</strong>: %s',$label,$value);
							echo '</li>';
						}
						echo '</ul>';
					}
		
			echo '</section>';
		endif;

		if($iframe_url) : 
		echo '<section class="product-video">';
			echo '<div class="sep"></div>';
			echo '<h2>Video presentation</h2>';
			?>
			<iframe src="<?php echo $iframe_url;?>" width="640" height="316" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
			<?php 
		echo '</section>';
		endif;

		echo '<section class="product-related">';
			echo '<div class="sep"></div>';
			if($related) {
				printf('<h2>In the same category: <span>%s</span></h2>',$cat->name);
				echo '<ul class="product-grid nb-col-3">';
					foreach($related as $product_id) {
						kasutan_display_product_card($product_id,$cat,$taxonomy,$user_id,'related');
					}
				echo '</ul>';
			}

			echo '<div class="browse-buttons">';
				printf('<a class="button" href="%s">Browse <strong>%s</strong></a>',
					get_term_link($cat,$taxonomy),
					$cat->name
				);
				if($cat_parent) {
					printf('<a class="button" href="%s">Browse <strong>%s</strong></a>',
						get_term_link($cat_parent,$taxonomy),
						$cat_parent->name
					);
				}
				
			echo '</div>';

			if($cat_siblings) {
				echo '<p class="siblings-title show-for-md">Quick access to:</p>';
				echo '<nav class="siblings show-for-md">';
					foreach($cat_siblings as $term) {
						printf('<a class="button small" href="%s">%s</a>',
							get_term_link($term,$taxonomy),
							$term->name
						);	
					}
				echo '</nav>';
			}

		echo '</section>';

	

		
	echo '</div>';

	

echo '</article>';
