<?php

/**
* Header left : menu catégories produits, bouton toggle, volet de navigation 
* version non premium : rien !
*/
add_action('tha_header_left','kasutan_menu_left');
function kasutan_menu_left() {
	if(!kasutan_is_member()) {
		echo '<nav class="header-left">';
			if(!is_front_page(  )) {
			?>
				<svg xmlns="http://www.w3.org/2000/svg" width="8.748" height="15.002" viewBox="0 0 8.748 15.002">
				<path d="M86.983,39.846l5.908-5.908a.473.473,0,0,0,0-.691l-.752-.752a.472.472,0,0,0-.691,0l-7,7a.473.473,0,0,0,0,.692l7,7a.472.472,0,0,0,.691,0l.752-.751a.473.473,0,0,0,0-.692Z" transform="translate(-84.293 -32.345)" fill="#173a65"/>
				</svg>
			<?php
			$home_url = apply_filters( 'wpml_home_url', get_option( 'home' ) );
			printf('<a href="%s" class="retour-home">%s</a>',$home_url,esc_html__('Homepage','the-source'));
			}
		echo '</nav>';		
		return;
	}
	echo '<nav class="header-left">';
		if( has_nav_menu( 'products' ) ) {
			if( class_exists('kasutan_products_menu_walker') ) {
				wp_nav_menu( array(
					'theme_location' => 'products',
					'menu_id'        => 'menu-products',
					'walker' => new kasutan_products_menu_walker,
					'container'=>false, 
					'menu_class' => 'menu-products nav-menu desktop'
				) );
			} else {
				wp_nav_menu( array(
					'theme_location' => 'products',
					'menu_id'        => 'menu-products',
					'container'=>false, 
					'menu_class' => 'menu-products nav-menu desktop'
				) );
			}
		}
		
		?>
		<button class="menu-toggle picto" id="menu-toggle" aria-controls="volet-navigation"  aria-label="Menu">
			<?php echo kasutan_picto(array('icon'=>'menu', 'class'=>'menu', 'size'=>'28'));?>
			<?php echo kasutan_picto(array('icon'=>'close', 'class' => 'fermer-menu','size'=>'23'));?>
		</button>
		<div class="volet-navigation"  id="volet-navigation">
			<?php
			if( has_nav_menu( 'products' ) ) {
				if( class_exists('kasutan_products_menu_walker') ) {
					wp_nav_menu( array(
						'theme_location' => 'products',
						'menu_id'        => 'menu-products-mobile',
						'walker' => new kasutan_products_menu_walker,
						'container'=>false, 
						'menu_class' => 'menu-products nav-menu mobile'
					) );
				} else {
					wp_nav_menu( array(
						'theme_location' => 'products',
						'menu_id'        => 'menu-products-mobile',
						'container'=>false, 
						'menu_class' => 'menu-products nav-menu mobile'
					) );
				}
			}
		
			if( has_nav_menu( 'the-source' ) ) {
				wp_nav_menu( array( 'theme_location' => 'the-source', 'menu_id' => 'menu-the-source-mobile', 'container'=>false, 'menu_class' => 'menu-the-source  nav-menu mobile' ) );
			}
	
			if( has_nav_menu( 'my-account-mobile' ) ) {
				wp_nav_menu( array( 'theme_location' => 'my-account-mobile', 'menu_id' => 'menu-my-account-mobile', 'container'=>false, 'menu_class' => 'menu-my-account  nav-menu mobile' ) );
			}

		echo '</div>'; //Fin volet navigation

	echo '</nav>';
}

/**
* Header right : menu the source, menu mon compte (desktop), liens mon compte et ma sélection (mobile), racourci ma sélection
* version non premium : mon compte si connecté, lien de connexion si non connecté
*/
add_action('tha_header_right','kasutan_header_right');
function kasutan_header_right() {
	if(!kasutan_is_member()) {
		echo '<nav class="header-right">';
			if(is_user_logged_in(  )) {
				printf('<a href="%s">%s</a>',wp_logout_url(get_permalink()),esc_html__('Logout','the-source'));
			} else {
				if(is_front_page() && function_exists('get_field') && !empty($faq=get_field('zs_lien_faq','option'))) {
					printf('<div class="faq-link"><a href="%s">%s</a></div>',$faq['url'],$faq['title']);
				}
				if(function_exists('pmpro_getOption')) {
					$login_url=get_page_link(pmpro_getOption('login_page_id'));
				} else {
					$login_url=wp_login_url(get_permalink());
				}
				printf('<div class="signin"><span class="desktop">%s</span> <a href="%s" class="login-link">%s</a></div>',
					esc_html__('Already a member?','the-source'),
					$login_url,
					esc_html__('Log in','the-source')
				);
			}
			do_action('wpml_add_language_selector');
		echo '</nav>';
		return;
	}
	echo '<nav class="header-right">';
		
		if( has_nav_menu( 'the-source' ) ) {
			wp_nav_menu( array( 'theme_location' => 'the-source', 'menu_id' => 'menu-the-source', 'container'=>false, 'menu_class' => 'menu-the-source nav-menu desktop' ) );
		}

	

		do_action('wpml_add_language_selector');

		echo '<div class="header-right-top">';
		
			$page_selection=kasutan_get_page_ID('selection');
			$user_id=get_current_user_id(  );
			$count=kasutan_count_selection($user_id); //BONUS compter uniquement les items dans la langue actuelle ?
			if($page_selection) {
				printf('<a class="selection" href="%s"><span id="count" class="count">%s</span><span class="label">%s</span></a>',get_page_link($page_selection),$count,esc_html__('Saved items','the-source'));
			}

			if( has_nav_menu( 'my-account' ) ) {
				wp_nav_menu( array( 'theme_location' => 'my-account', 'menu_id' => 'menu-my-account', 'container'=>false, 'menu_class' => 'menu-my-account nav-menu desktop' ) );
			}
		echo '</div>';

		
	echo '</nav>';
}