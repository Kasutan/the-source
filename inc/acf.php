<?php
/**
 * ACF Customizations
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

class BE_ACF_Customizations {

	public function __construct() {

		// Only allow fields to be edited on development
		if ( ! defined( 'WP_LOCAL_DEV' ) || ! WP_LOCAL_DEV ) {
			//add_filter( 'acf/settings/show_admin', '__return_false' );
		}

		// Save and sync fields.
		add_filter( 'acf/settings/save_json', array( $this, 'get_local_json_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'add_local_json_path' ) );
		add_action( 'admin_init', array( $this, 'sync_fields_with_json' ) );

		// Register options page
		add_action( 'init', array( $this, 'register_options_page' ) );

		// Register Blocks
		add_filter( 'block_categories', array($this,'kasutan_block_categories'), 10, 2 );
		add_action('acf/init', array( $this, 'register_blocks' ) );

	}

	/**
	 * Define where the local JSON is saved.
	 *
	 * @return string
	 */
	public function get_local_json_path() {
		return get_template_directory() . '/acf-json';
	}

	/**
	 * Add our path for the local JSON.
	 *
	 * @param array $paths
	 *
	 * @return array
	 */
	public function add_local_json_path( $paths ) {
		$paths[] = get_template_directory() . '/acf-json';

		return $paths;
	}

	/**
	 * Automatically sync any JSON field configuration.
	 */
	public function sync_fields_with_json() {
		if ( defined( 'DOING_AJAX' ) || defined( 'DOING_CRON' ) )
			return;

		if ( ! function_exists( 'acf_get_field_groups' ) )
			return;

		$version = get_option( 'ea_acf_json_version' );

		if ( defined( 'KASUTAN_STARTER_VERSION' ) && version_compare( KASUTAN_STARTER_VERSION, $version ) ) {
			update_option( 'ea_acf_json_version', KASUTAN_STARTER_VERSION );
			$groups = acf_get_field_groups();

			if ( empty( $groups ) )
				return;

			$sync = array();
			foreach ( $groups as $group ) {
				$local    = acf_maybe_get( $group, 'local', false );
				$modified = acf_maybe_get( $group, 'modified', 0 );
				$private  = acf_maybe_get( $group, 'private', false );

				if ( $local !== 'json' || $private ) {
					// ignore DB / PHP / private field groups
					continue;
				}

				if ( ! $group['ID'] ) {
					$sync[ $group['key'] ] = $group;
				} elseif ( $modified && $modified > get_post_modified_time( 'U', true, $group['ID'], true ) ) {
					$sync[ $group['key'] ] = $group;
				}
			}

			if ( empty( $sync ) )
				return;

			foreach ( $sync as $key => $v ) {
				if ( acf_have_local_fields( $key ) ) {
					$sync[ $key ]['fields'] = acf_get_local_fields( $key );
				}
				acf_import_field_group( $sync[ $key ] );
			}
		}
	}

	/**
	 * Register Options Page
	 *
	 */
	function register_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(array(
				'page_title' 	=> 'The Source special settings',
				'menu_title'	=> 'The Source settings',
				'menu_slug' 	=> 'site-settings',
				'capability'	=> 'edit_posts',
				'position' 		=> '2.5',
				'icon_url' 		=> 'dashicons-admin-generic',
				'redirect'		=> false,
				'update_button' => 'Update',
				'updated_message' => 'The Source settings updated',
				'capability' => 'manage_options',

			));
		}
	}

	/**
	* Enregistre une catégorie de blocs liée au thème
	*
	*/
	function kasutan_block_categories( $categories, $post ) {
		return array_merge(
			array(
				array(
					'slug' => 'the-source',
					'title' => 'the-source',
					'icon'  => 'admin-generic',
				),
			),
			$categories
		);
	}
	

	/**
	 * Register Blocks
	 * @link https://www.billerickson.net/building-gutenberg-block-acf/#register-block
	 *
	 * Categories: common, formatting, layout, widgets, embed
	 * Dashicons: https://developer.wordpress.org/resource/dashicons/
	 * ACF Settings: https://www.advancedcustomfields.com/resources/acf_register_block/
	 */
	function register_blocks() {

		if( ! function_exists('acf_register_block_type') )
			return;

		/*********Bloc tous producteurs ***************/
		acf_register_block_type( [
			'name'            => 'tous-producteurs',
			'title'           => 'Bloc tous les producteurs',
			'description'     => 'Liste de tous les producteurs, avec filtre et pagination',
			'render_template' => 'partials/blocks/tous-producteurs/tous-producteurs.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/tous-producteurs/tous-producteurs.css',
			'category'        => 'the-source',
			'icon'            => 'buddicons-replies', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>false,
				'anchor' => false,
			),
			'keywords'        => [ 'producteur', 'the-source'],
		] );
		
		/*********Bloc colonnes alternées ***************/
		acf_register_block_type( [
			'name'            => 'colonnes-alternees',
			'title'           => 'Bloc colonnes alternées',
			'description'     => 'Une colonne de texte et une colonne avec une image, alternativement à droite puis à gauche du texte - selon la position du bloc dans la page. La colonne de texte peut contenir un bouton (facultatif).',
			'render_template' => 'partials/blocks/colonnes-alternees/colonnes-alternees.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/colonnes-alternees/colonnes-alternees.css',
			'category'        => 'the-source',
			'icon'            => 'align-right', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>true,
				'anchor' => true,
			),
			'keywords'        => [ 'colonne', 'image','the-source'],
		] );

		/*********Bloc carrousel ***************/
		acf_register_block_type( [
			'name'            => 'carrousel',
			'title'           => 'Bloc carrousel',
			'description'     => 'Carrousel avec images bannières, messages et boutons en option.',
			'render_template' => 'partials/blocks/carrousel/carrousel.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/carrousel/carrousel.css',
			'enqueue_script' => get_stylesheet_directory_uri() . '/partials/blocks/carrousel/carrousel.js',
			'category'        => 'the-source',
			'icon'            => 'slides', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>false,
				'anchor' => false,
			),
			'keywords'        => [ 'slide', 'carrousel','the-source','accueil'],
		] );

		/*********Bloc reassurance ***************/
		acf_register_block_type( [
			'name'            => 'reassurance',
			'title'           => 'Bloc réassurance',
			'description'     => 'Bandeau avec 3 pictos et leurs textes de réassurance.',
			'render_template' => 'partials/blocks/reassurance/reassurance.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/reassurance/reassurance.css',
			'category'        => 'the-source',
			'icon'            => 'format-status', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>true,
				'anchor' => false,
			),
			'keywords'        => [ 'reassurance','the-source','accueil'],
		] );

		/*********Bloc categories de produits ***************/
		acf_register_block_type( [
			'name'            => 'categories-produits',
			'title'           => 'Bloc catégories de produits',
			'description'     => 'Section avec 1 image et un titre cliquable par catégorie de produit.',
			'render_template' => 'partials/blocks/categories-produits/categories-produits.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/categories-produits/categories-produits.css',
			'category'        => 'the-source',
			'icon'            => 'cart', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>false,
				'anchor' => false,
			),
			'keywords'        => [ 'categorie', 'produit','the-source','accueil'],
		] );

		/*********Bloc section animée ***************/
		acf_register_block_type( [
			'name'            => 'section-animee',
			'title'           => 'Bloc section avec décors animés',
			'description'     => 'Section avec 2 images, 2 titres, un texte, un bouton et éléments de décor animés.',
			'render_template' => 'partials/blocks/section-animee/section-animee.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/section-animee/section-animee.css',
			'category'        => 'the-source',
			'icon'            => 'art', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>false,
				'anchor' => false,
			),
			'keywords'        => [ 'animee', 'animée', 'decor', 'décor','the-source','accueil'],
		] );

		/*********Bloc nouveautés produits ***************/
		acf_register_block_type( [
			'name'            => 'nouveautes-produits',
			'title'           => 'Bloc nouveautés produits',
			'description'     => 'Section avec les 4 derniers produits, visibles et en stock (contenu automatique).',
			'render_template' => 'partials/blocks/nouveautes-produits/nouveautes-produits.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/nouveautes-produits/nouveautes-produits.css',
			'category'        => 'the-source',
			'icon'            => 'cart', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>false,
				'anchor' => false,
			),
			'keywords'        => [ 'produit', 'nouveau', 'nouveauté','the-source','accueil'],
		] );

		/*********Bloc section bannière ***************/
		acf_register_block_type( [
			'name'            => 'section-banniere',
			'title'           => 'Bloc section banniere',
			'description'     => 'Section avec titre principal, image bannière et cadre blanc avec titre, texte et bouton optionnel.',
			'render_template' => 'partials/blocks/section-banniere/section-banniere.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/section-banniere/section-banniere.css',
			'category'        => 'the-source',
			'icon'            => 'cover-image', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>true,
				'anchor' => false,
			),
			'keywords'        => [ 'section', 'banniere', 'image','the-source','accueil'],
		] );

		/*********Bloc personnalisations ***************/
		acf_register_block_type( [
			'name'            => 'personnalisations',
			'title'           => 'Bloc vos personnalisations',
			'description'     => 'Section avec titre principal, trois images légendées, titre secondaire, texte et bouton optionnel.',
			'render_template' => 'partials/blocks/personnalisations/personnalisations.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/personnalisations/personnalisations.css',
			'category'        => 'the-source',
			'icon'            => 'format-gallery', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>true,
				'anchor' => false,
			),
			'keywords'        => [ 'personnalisation', 'image','the-source','accueil'],
		] );

		/*********Bloc blog ***************/
		acf_register_block_type( [
			'name'            => 'blog',
			'title'           => 'Bloc blog',
			'description'     => 'Section avec titre principal et les deux derniers articles publiés sur le blog.',
			'render_template' => 'partials/blocks/blog/blog.php',
			'enqueue_style' => get_stylesheet_directory_uri() . '/partials/blocks/blog/blog.css',
			'category'        => 'the-source',
			'icon'            => 'welcome-write-blog', 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>false,
				'anchor' => false,
			),
			'keywords'        => [ 'blog', 'article','the-source','accueil'],
		] );
		
	}
}
new BE_ACF_Customizations();
