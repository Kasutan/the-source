<?php
/**
 * Template Tags
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/


/**
* Entry Title
*
*/
function ea_entry_title() {
	$class='';
	$subtitle='';
	if(kasutan_is_account_page()) {
		$class='has-cyan-color';
	} elseif(kasutan_is_account_child_page()) {
		$class='has-cyan-color no-dots';
		$subtitle=sprintf('<div class="h3 dots has-cyan-color subtitle">%s</div>',esc_html__('My account','the-source'));
	}

	if(!is_front_page()) {
		echo $subtitle;
		printf('<h1 class="entry-title %s">%s</h1>',$class, get_the_title());
	}
}
add_action( 'tha_entry_top', 'ea_entry_title' );

/**
 * Entry Category
 * TODO : supprimer
 */
function ea_entry_category($contexte='archive') {
	$post_type=get_post_type();
	$term=false;
	if($post_type==='post') {
		$term = ea_first_term();
	} elseif($post_type==='producteur') {
		$term=ea_first_term(array('taxonomy'=>'type_producteur'));
	} elseif($post_type==='product') {
		$term=ea_first_term(array('taxonomy'=>'product_cat'));
	}
	if( !empty( $term ) && ! is_wp_error( $term ) )
		if($contexte==='archive') {
			echo '<p class="entry-category">' . $term->name . '</p>';
			//pour le filtre
			printf('<span class="term screen-reader-text">%s</span>',$term->slug);
		} else {
				$name=$term->name;
		
			echo '<p class="entry-category h1 entry-title">' . $name . '</p>';
		}
		
}

/**
 * Post Summary Title
 *
 */
function ea_post_summary_title() {
	global $wp_query;
	$tag = ( is_singular() || -1 === $wp_query->current_post ) ? 'h3' : 'h2';
	echo '<' . $tag . ' class="post-summary__title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></' . $tag . '>';
}

/**
 * Post Summary Image
 *
 */
function ea_post_summary_image( $size = 'thumbnail_medium' ) {
	echo '<a class="post-summary__image" href="' . get_permalink() . '" tabindex="-1" aria-hidden="true">' . wp_get_attachment_image( ea_entry_image_id(), $size ) . '</a>';
}


/**
 * Entry Image ID
 *
 */
function ea_entry_image_id() {
	if(has_post_thumbnail()) {
		return get_post_thumbnail_id();
	}
	if(function_exists('get_field') && !empty($default=get_field('zs_default_image'))) {
		return $default;
	}
	return get_theme_mod( 'custom_logo' ); //défaut : le logo du site
}

/**
 * Entry Author
 *
 */
function ea_entry_author() {
	$id = get_the_author_meta( 'ID' );
	echo '<p class="entry-author"><a href="' . get_author_posts_url( $id ) . '" aria-hidden="true" tabindex="-1">' . get_avatar( $id, 40 ) . '</a><em>by</em> <a href="' . get_author_posts_url( $id ) . '">' . get_the_author() . '</a></p>';
}

/**
* Affiche le fil d'ariane.
*/
function kasutan_fil_ariane() {

	//On n'affiche pas le fil d'ariane sur la page d'accueil
	if(is_front_page()) {
		return;
	}

	$queried_object = get_queried_object();
	$post_type=get_post_type();
	$archive_ID=false;
	$cat_parent=false;

	echo '<p class="fil-ariane">';

	//Pour tous les contenus : afficher en premier le lien vers l'accueil du site
	printf('<a href="/">%s</a> > ',esc_html__('Home','the-source'));

	//Pour les fiches produit
	if(kasutan_is_single_for_product()) {
		$archive_ID=kasutan_get_page_ID($post_type);
		$taxonomy=kasutan_get_taxonomy_slug_for_cpt($post_type);
		$cat_parent=kasutan_get_closest_cat_for_product(get_the_ID(),$post_type);
	}

	//Pour les archives produits
	if(function_exists('kasutan_is_archive_for_product') && $taxonomy=kasutan_is_archive_for_product($queried_object)) {
		$post_type=kasutan_get_cpt_slug_for_taxonomy($taxonomy);
		$archive_ID=kasutan_get_page_ID($post_type);
		if($queried_object->parent!==0) {
			//category has parent, get closest parent's term
			$cat_parent=get_term($queried_object->parent,$taxonomy);
		}
	}

	if($archive_ID) {
		printf('<a href="%s">%s</a> > ',
			get_the_permalink( $archive_ID),
			strip_tags(get_the_title($archive_ID))
		);
	}

	if($cat_parent) {
		$family[]=$cat_parent;
		$cat_parent_1=$cat_parent->parent;
		if($cat_parent->parent!==0) {
			$cat_gdparent=get_term($cat_parent->parent,$taxonomy);
			$family[]=$cat_gdparent;

			if($cat_gdparent->parent!==0) {
				$cat_arrgdparent=get_term($cat_gdparent->parent,$taxonomy);
				$family[]=$cat_arrgdparent;

			}
		}
		$family=array_reverse($family);
		foreach($family as $term) {
			printf('<a href="%s">%s</a> > ',
				get_term_link($term, $taxonomy),
				strip_tags($term->name)
			);
		}
	}



	//Afficher le titre de la page courante
	if(is_page()) : 
		//Afficher le titre et le lien vers la page parente s'il y en a une
		$current=get_post(get_the_ID());
		$parent=$current->post_parent; 
		if($parent) :
			printf('<a href="%s">%s</a> > ',
				get_page_link( $parent),
				strip_tags(get_the_title($parent))
			);
		endif;
		//titre de la page
		printf('<span class="current">%s</span>',
		strip_tags(get_the_title())
	);
	elseif(is_single()): //single articles ou ressources
		printf('<span class="current">%s</span>',
			strip_tags(get_the_title())
		);
	elseif (is_category()) :  //archives catégories d'articles
		echo '<span class="current">'.strip_tags(single_cat_title( '', false )).'</span>';
	elseif (is_tag()) :  //archives tags d'articles
		echo '<span class="current">'.strip_tags(single_tag_title( '', false )).'</span>';
	elseif (is_tax()) : //custom tax archive
		echo '<span class="current">'.strip_tags($queried_object->name).'</span>';
	elseif (is_home()) :
		printf('<span class="current">%s</span>',esc_html__('Blog','the-source'));
	elseif (is_search()) :
		printf('<span class="current">%s '.get_search_query().'</span>',esc_html__('Search:','the-source'));
	elseif (is_404()) :
		printf('<span class="current">%s</span>',esc_html__('Page not found','the-source'));

	endif;

	//Fermer la balise du fil d'ariane
	echo '</p>';

}




/**
* Image banniere pour les pages
*
*/
add_action( 'tha_entry_top', 'kasutan_page_banniere',5 );

function kasutan_page_banniere() {
	if(!function_exists('get_field')) {
		return;
	}
	if(!is_singular() || is_single() || is_home() || is_front_page(  )) {
		return;
	}

	$image_id=esc_attr(get_field('zs_page_banniere'));
	$image_id_mobile=esc_attr(get_field('zs_page_banniere_mobile'));
	if(empty($image_id)) {
		$image_id=esc_attr(get_field('zs_banniere_defaut','option'));
	}
	if(empty($image_id_mobile)) {
		$image_id_mobile=esc_attr(get_field('zs_banniere_mobile_defaut','option'));
	}
	if(!empty($image_id) || !empty($image_id_mobile)) {
		printf('<div class="page-banniere">%s %s</div>',
			wp_get_attachment_image( $image_id, 'banniere',false,array('decoding'=>'async','class'=>'desktop')),
			wp_get_attachment_image( $image_id_mobile, 'medium_large',false,array('decoding'=>'async','class'=>'mobile'))
		);
	}
}



/**
* Image banniere pour les archives produits
*
*/

function kasutan_tax_banniere() {
	if(!function_exists('get_field')) {
		return;
	}
	if(!is_tax()) {
		return;
	}
	$term=get_queried_object(  );

	$image_id=esc_attr(get_field('zs_banniere_archive',$term));
	$image_id_mobile=esc_attr(get_field('zs_banniere_archive_mobile',$term));

	if(empty($image_id)) {
		$image_id=esc_attr(get_field('zs_banniere_defaut','option'));
	}
	if(empty($image_id_mobile)) {
		$image_id_mobile=esc_attr(get_field('zs_banniere_mobile_defaut','option'));
	}
	if(!empty($image_id) || !empty($image_id_mobile)) {
		printf('<div class="page-banniere">%s %s</div>',
			wp_get_attachment_image( $image_id, 'banniere',false,array('decoding'=>'async','class'=>'desktop')),
			wp_get_attachment_image( $image_id_mobile, 'medium_large',false,array('decoding'=>'async','class'=>'mobile'))
		);
	}
}

/**
* Image mise en avant
*
*/
function kasutan_affiche_thumbnail_dans_contenu() {
	if(has_post_thumbnail()) {
		echo '<div class="thumbnail">';
			the_post_thumbnail( 'medium');
		echo '</div>';
	}
}


/**
* Deux colonnes avec texte et image
* Utilisé pour le bloc ACF du même nom et pour la liste de producteurs
* Argument : tableau contenant balise, titre, texte, image, type_producteur
*/

function kasutan_affiche_bloc_deux_colonnes_alternees($args) {
	printf('<%s class="colonnes-alternees">',$args['balise']);
		if(!empty($args['type_producteur'])) {
			printf('<span class="term screen-reader-text">%s</span>',$args['type_producteur']); //pour le filtre
		}
		if(!empty($args['image'])) {
			$classe='avec-image';
		} else {
			$classe='sans-image';
		}
		printf('<div class="col-image %s">%s</div>',$classe,wp_get_attachment_image( $args['image'], 'large'));
		echo '<div class="col-texte">';
			printf('<h2 class="titre">%s</h2>',$args['titre']);
			printf('<div class="texte">%s</div>',$args['texte']);
		echo '</div>';
	printf('</%s">',$args['balise']);
}