<?php

/**
* Header left : menu catégories produits, bouton toggle, volet de navigation 
* version non premium : rien !
*/
add_action('tha_header_left','kasutan_menu_left');
function kasutan_menu_left() {
	if(!kasutan_is_premium_member()) {
		echo '<nav class="header-left"></nav>';
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
					'menu_class' => 'menu-products'
				) );
			} else {
				wp_nav_menu( array(
					'theme_location' => 'products',
					'menu_id'        => 'menu-products',
					'container'=>false, 
					'menu_class' => 'menu-products'
				) );
			}
		}
		
		?>
		<button class="menu-toggle picto" id="menu-toggle" aria-controls="volet-navigation"  aria-label="Menu">
			<?php echo kasutan_picto(array('icon'=>'menu', 'class'=>'menu', 'size'=>'28'));?>
			<?php echo kasutan_picto(array('icon'=>'close', 'class' => 'fermer-menu','size'=>'28'));?>
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
						'menu_class' => 'menu-products-mobile'
					) );
				} else {
					wp_nav_menu( array(
						'theme_location' => 'products',
						'menu_id'        => 'menu-products-mobile',
						'container'=>false, 
						'menu_class' => 'menu-products-mobile'
					) );
				}
			}
		
			if( has_nav_menu( 'the-source' ) ) {
				wp_nav_menu( array( 'theme_location' => 'the-source', 'menu_id' => 'menu-the-source-mobile', 'container'=>false, 'menu_class' => 'menu-the-source-mobile' ) );
			}
	
			if( has_nav_menu( 'my-account-mobile' ) ) {
				wp_nav_menu( array( 'theme_location' => 'my-account-mobile', 'menu_id' => 'menu-my-account-mobile', 'container'=>false, 'menu_class' => 'menu-my-account-mobile' ) );
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
	if(!kasutan_is_premium_member()) {
		echo '<nav class="header-right">Connexion</nav>';
		//TODO tester menu "Log In Widget - PMPro"
		return;
	}
	echo '<nav class="header-right">';
		
		if( has_nav_menu( 'the-source' ) ) {
			wp_nav_menu( array( 'theme_location' => 'the-source', 'menu_id' => 'menu-the-source', 'container'=>false, 'menu_class' => 'menu-the-source' ) );
		}

		if( has_nav_menu( 'my-account' ) ) {
			wp_nav_menu( array( 'theme_location' => 'my-account', 'menu_id' => 'menu-my-account', 'container'=>false, 'menu_class' => 'menu-my-account' ) );
		}

		echo '<span>my selection N</span>';
		
	echo '</nav>';
}