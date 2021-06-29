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


$in_selection=false; //TODO is item already in user's selection ?
if($in_selection) {
	$attr_checked="checked";
	$class_selected="selected";
} else {
	$attr_checked=$class_selected="";
}

$request_sent=false; //TODO has the user already sent a request for this item (less than 6 month ago) ?
if($request_sent) {
	$class_sent="request-sent";
} else {
	$class_sent="";
}

if(function_exists('get_field')) {
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
	$price=$intro=$main_advisor=$details=$iframe_url=false;
}

echo '<article class="' . join( ' ', get_post_class() ) . '">';

	if( ea_has_action( 'tha_entry_top' ) ) {
		echo '<header class="entry-header">';
		tha_entry_top(); //fil d'ariane
		echo '</header>';
	}

	echo '<div class="entry-content">';
		echo '<section class="product-top">';
			echo '<div class="product-gallery">';
			the_post_thumbnail( 'large');
			echo '</div>';

			echo '<div class="product-main">';
				printf('<h1 class="no-dots product-title">%s</h1>',get_the_title());
				if($cat) {
					printf('<a href="%s">%s</a>',get_term_link($cat,$taxonomy),$cat->name);
				}
				if($price) {
					printf('<p class="price"><strong>%s</strong></p>',$price);
				}
				if($intro) {
					$intro.='<a href="#details">More details</a>';
					printf('<div class="details">%s</div>',$details);
				}

				?>
				<formgroup class="to-selection <?php echo $class_selected;?>">
					<input type="checkbox" id="js-to-selection" name="js-to-selection" <?php echo $attr_checked;?> 
						data-product="<?php echo $post_id;?>"
						data-user="<?php echo $user_id;?>"
					>
					<label for="js-to-selection">
						<span class="add">Save this item in my selection</span>
						<span class="remove">Saved in my selection</span>
					</label>
				</formgroup>

				<?php if($main_advisor && function_exists('kasutan_display_advisor')) : ?>
				<formgroup class="contact-request <?php echo $class_sent;?>" >
					<legend>Interested? Let's talk about it</legend>
					<?php 
						kasutan_display_advisor($main_advisor,'product'); 
					?>
					<p class="info send">Send a Contact Request and I will get in touch with you asap.</p>
					<button class="send" id="sent-request"
						data-main-advisor="<?php echo $main_advisor;?>"
						data-backup-advisor="<?php echo $backup_advisor;?>"
						data-product="<?php echo $post_id;?>"
						data-user="<?php echo $user_id;?>"
					>Send <strong> a contact request</strong></button>

					<button class="sent cyan"><span class="check"></span><strong>Contact request sent</strong></button>
					<p class="info sent">Your Contact Request is being treated by our services.</p>

				</formgroup>

				<?php endif;


			echo '</div>';

		echo '</section>';

		if(have_rows('icons') || have_rows('table') || $details) : 
			echo '<section class="product-details">';
					echo '<h2 class="line">Details</h2>';
					
					if(have_rows('icons')) {
						echo '<ul class="icons">';
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
								printf('<strong>%s:</strong> %s',$label,$value);
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
				printf('<h2>In the same category: %s</h2>',$cat->name);
				echo '<ul class="product-grid 3-col">';
					foreach($related as $product_id) {
						kasutan_display_product_card($product_id,$cat,$taxonomy,$user_id,'related');
					}
				echo '</ul>';
			}

			echo '<div clas="browse-buttons">';
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
				echo '<p class="siblings-title">Quick access to</p>';
				echo '<nav class="siblings">';
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