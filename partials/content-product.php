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
} else {
	$price=$intro=$main_advisor=$details=$video=false;
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
					<input type="checkbox" id="js-to-selection" name="js-to-selection" <?php echo $attr_checked;?>>
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

		echo '<section class="product-details">';

		echo '</section>';


		echo '<section class="product-video">';

		echo '</section>';

		echo '<section class="product-related">';

		echo '</section>';

	

		
	echo '</div>';

	

echo '</article>';
