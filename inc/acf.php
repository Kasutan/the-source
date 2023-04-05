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

		//Show custom fields in edit screens
		add_filter('acf/settings/remove_wp_meta_box', '__return_false');

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

	function helper_register_block_type($slug,$titre,$description,$icon='admin-generic',$js=false,$css=true,$keywords=[] ){
		$keywords_from_slug=explode('-',$slug);
		$keywords=array_merge($keywords,$keywords_from_slug, array('the-source'));
		$args=[
			'name'            => $slug,
			'title'           => $titre,
			'description'     => $description,
			'render_template' => 'partials/blocks/'.$slug.'/'.$slug.'.php',
			'category'        => 'the-source',
			'icon'            => $icon, 
			'mode'			=> "edit",
			'supports' => array( 
				'mode' => false,
				'align'=>false,
				'multiple'=>true,
				'anchor' => true,
			),
			'keywords'        => $keywords
		];
		if($js) {
			$args['enqueue_script']=get_stylesheet_directory_uri() . '/partials/blocks/'.$slug.'/'.$slug.'.js';
		}
		if($css) {
			$args['enqueue_style']=get_stylesheet_directory_uri() . '/partials/blocks/'.$slug.'/'.$slug.'.css';
		}
		acf_register_block_type( $args);
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

		/*********Blocs assets***************/
		$this->helper_register_block_type(
			'products',
			'Products block',
			'Display grid of products in selected category.',
			'admin-generic',
			false,
			false
		);

		/*********Blocs checkout***************/
		$this->helper_register_block_type(
			'checkout-header',
			'Block header for checkout',
			'Display header on checkout page for a level. Create as many blocks as there are levels.'
		);

		/*********Blocs Home connecté***************/
		$this->helper_register_block_type(
			'home-inspiration',
			'Block header for premium home page',
			'Display header on home for premium members. "Seek inspiration" with 3 themes. For each theme: name, image and link.'
		);

		$this->helper_register_block_type(
			'home-products',
			'Products block for premium home page',
			'Display row of 4 most recent products in selected category, without filter.',
			'admin-generic',
			false,
			false
		);

		$this->helper_register_block_type(
			'home-products-selection',
			'Products block for free trial home page',
			'Display grid of hand-picked products.',
			'admin-generic',
			false,
			false
		);

		$this->helper_register_block_type(
			'home-upgrade',
			'Block upgrade for free trial home page',
			'Display upgrade incentive on free trial home page.'
		);

		/*********Blocs Home public***************/
		$this->helper_register_block_type(
			'home-liste',
			'Block list for public home page',
			'A list of features with title and text for each feature'
		);

		$this->helper_register_block_type(
			'home-quote',
			'Block quote for public home page',
			'A quote, a picture and the names of the co-founders'
		);

		/*********Blocs Advisors***************/
		$this->helper_register_block_type(
			'about-experts',
			'Experts block for About page',
			'Display a portrait for each selected expert.','admin-generic',
			false,
			false
		);

		$this->helper_register_block_type(
			'faq-experts',
			'Experts block for FAQ page',
			'Display a portrait for each selected expert/advisor, with their area of expertise.',
			'admin-generic',
			false,
			false
		);

		/*********Blocs FAQ***************/
		$this->helper_register_block_type(
			'faq-answers',
			'Questions/answers block for FAQ page',
			'Use one block by theme.',
			'admin-generic',
			true,
			true
		);
		
	}
}
new BE_ACF_Customizations();
