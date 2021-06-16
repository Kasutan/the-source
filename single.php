<?php
/**
 * Single Post
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/


// Breadcrumbs above page title
add_action( 'tha_entry_top', 'kasutan_fil_ariane', 8 );

// Image bannière au-dessus de la catégorie
add_action( 'tha_entry_top', 'kasutan_page_banniere', 9 );

// Entry category in header
add_action( 'tha_entry_top', 'ea_entry_category', 10 );

//Titre déplacé dans le contenu
remove_action( 'tha_entry_top', 'ea_entry_title' );


//Titre et date insérés avant le contenu pour mise en page grille
add_action('tha_entry_content_before', 'kasutan_single_entry_content_before');
function kasutan_single_entry_content_before() {
	if(get_post_type() === 'product') {
		return;
	}
	//image 
	if(function_exists('kasutan_affiche_thumbnail_dans_contenu')) {
		kasutan_affiche_thumbnail_dans_contenu();
	}
	
	//titre 
	printf('<h1 class="h2 single-title">%s</h1>',get_the_title());

	// Publish date
	echo '<p class="publish-date">'. get_the_date('d/m/Y') . '</p>';

}

//Retour vers la page d'archive
add_action('tha_entry_bottom','kasutan_single_entry_bottom');
function kasutan_single_entry_bottom() {
	if(!function_exists('get_field')) {
		return;
	}
	$post_type=get_post_type();
	$archive_id=$texte='';
	if($post_type==='post') {
		$archive_id=get_option('page_for_posts');
		$texte=esc_html(get_field('texte_retour_blog','option'));
	} elseif($post_type==='producteur') {
		$archive_id=esc_attr(get_field('page_producteurs','option'));
		$texte=esc_html(get_field('texte_retour_producteurs','option'));
	}
	
	if(!empty($archive_id) && !empty($texte)) {
		printf('<a href="%s" class="retour-archive">%s</a>',
			get_the_permalink( $archive_id), 
			$texte
		);
	}
}

// Build the page
require get_template_directory() . '/index.php';
