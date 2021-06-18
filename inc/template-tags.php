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
	if(!is_front_page()) {
		echo '<h1 class="entry-title">' . get_the_title() . '</h1>';
	}
}
add_action( 'tha_entry_top', 'ea_entry_title' );

/**
 * Entry Category
 *
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
			//contexte single
			if($post_type==='post') {
				$name =kasutan_enleve_s_final($term->name);
			} else {
				$name=$term->name;
			}
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

	$post_type=get_post_type();

	echo '<p class="fil-ariane">';

	//Afficher la page boutique pour les fiches produits et les archives produits
	if ( (is_single() && 'product' === $post_type) || is_tax('product_cat') || is_tax('product_tag') ) {
		//l'ID de la page est stockée dans les options ACF
		$page_boutique=kasutan_get_page_ID('page_boutique'); ; 
		if($page_boutique) :
			printf('<a href="%s">%s</a> > ',
				get_the_permalink( $page_boutique),
				strip_tags(get_the_title($page_boutique))
			);
		endif;
		//Ajouter la catégorie de produits pour les fiches produits
		if(is_singular()) {
			$categories=kasutan_categories_produit();
			if(is_array($categories)) {
				$parente=$categories['categorie_parente'];
				$sous_categorie=$categories['sous_categorie'];
				
				if($parente) {
					printf('<a href="%s">%s</a> > ',
						get_category_link( $parente ),
						$parente->name
					);
				}
				
				printf('<a href="%s?filtre_cat=%s">%s</a> > ',
					get_category_link( $parente ),
					$sous_categorie->slug,
					$sous_categorie->name
				);
			}
		}
	} else {
		//Pour tous les autres contenus : afficher en premier le lien vers l'accueil du site
		$accueil=get_option('page_on_front');
		printf('<a href="%s">%s</a> > ',
			get_the_permalink( $accueil),
			strip_tags(get_the_title($accueil))
		);
	}


	//Afficher la page des actualités pour les articles (single ou archive de catégorie ou archive des articles ou archive de tag)
	if ( (is_single() && 'post' === $post_type) || is_category() || is_tag() ) :
		//l'ID de la page est stockée dans les options ACF
		$actus=get_option('page_for_posts'); 
		if($actus) :
			printf('<a href="%s">%s</a> > ',
				get_the_permalink( $actus),
				strip_tags(get_the_title($actus))
			);
		endif;
		//Ajouter la catégorie d'article pour les posts single
		if(is_single()) {
			$term=ea_first_term();
			if(!empty($term)) {
				printf('<a href="%s?filtre_cat=%s">%s</a> > ',
				get_the_permalink( $actus),
					$term->slug,
					$term->name
				);
			}
		}
	endif;

	//Afficher la page des producteurs pour les fiches producteurs
	if ( (is_single() && 'producteur' === $post_type) ):
		//l'ID de la page est stockée dans les options ACF
		$page_producteurs=kasutan_get_page_ID('page_producteurs'); ; 
		if($page_producteurs) :
			printf('<a href="%s">%s</a> > ',
				get_the_permalink( $page_producteurs),
				strip_tags(get_the_title($page_producteurs))
			);
		endif;
	endif;

	


	//Afficher le titre de la page courante
	if(is_page()) : 
		//Afficher le titre de la page parente s'il y en a une
		$current=get_post(get_the_ID());
		$parent=$current->post_parent; 
		if($parent) :
			printf('<span class="current">%s : %s</span>',
				strip_tags(get_the_title($parent)),
				strip_tags(get_the_title())
			);
		else :
			printf('<span class="current">%s</span>',
				strip_tags(get_the_title())
			);
		endif;
	elseif(is_single()): //single articles ou ressources
		printf('<span class="current">%s</span>',
			strip_tags(get_the_title())
		);
	elseif (is_category()) :  //archives catégories d'articles
		echo '<span class="current">'.strip_tags(single_cat_title( '', false )).'</span>';
	elseif (is_tag()) :  //archives tags d'articles
		echo '<span class="current">'.strip_tags(single_tag_title( '', false )).'</span>';
	elseif (is_home()) :
		echo '<span class="current">Blog</span>';
	elseif (is_search()) :
		echo '<span class="current">Recherche : '.get_search_query().'</span>';
	elseif (is_404()) :
		echo '<span class="current">Page introuvable</span>';

	endif;

	//Fermer la balise du fil d'ariane
	echo '</p>';

}




/**
* Image banniere
*
*/
function kasutan_page_banniere() {
	if(!function_exists('get_field')) {
		return;
	}
	$image_id=esc_attr(get_field('zs_page_banniere'));
	if(!empty($image_id)) {
		printf('<div class="page-banniere">%s</div>',wp_get_attachment_image( $image_id, 'banniere',false,array('decoding'=>'async')));
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
* Filtre pour une taxonomie
*
*/
function kasutan_affiche_filtre_taxonomy($taxonomy,$child_of=0) {
	$terms = get_terms( array(
		'taxonomy' => $taxonomy,
		'child_of' => $child_of,
		'hide_empty' => true, 
		//Pas d'arguments pour l'ordre : l'ordre est géré par le plugin Taxonomy Order
	) );
	if(empty($terms)) {
		return;
	}
	printf('<form id="filtre-liste" class="filtre %s">',$taxonomy);
		echo '<p class="screen-reader-text">Filtrer par type de contenu</p>';
		echo '<input type="radio" name="filtre" id="tous" value="tous" class="type filtre-input" checked>';
		echo '<label for="tous" class="filtre-label">Tous</label>';
		foreach($terms as $term) : 
			$nom=$term->name;
			$slug=$term->slug;
			echo '<div class="filtre-sep">|</div>';
			printf('<input type="radio" id="%s" name="filtre" value="%s" class="type filtre-input">',
				$slug,
				$slug
			);
			printf('<label for="%s" class="filtre-label">%s</label>',
				$slug,
				$nom
			);
		endforeach;
	echo '</form>';
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