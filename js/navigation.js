/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	var volet, button, menus, links, i, len, j, lenJ;


	button = document.getElementById( 'menu-toggle' );
	if ( 'undefined' === typeof button || null === button) {
		return;
	}

	volet = document.getElementById( 'volet-navigation' );

	// Hide menu toggle button if volet is empty and return early.
	if ( 'undefined' === typeof volet || null === volet) {
		button.style.display = 'none';
		return;
	}

	volet.setAttribute( 'aria-expanded', 'false' );
	button.setAttribute( 'aria-expanded', 'false' );


	button.onclick = function() {
		if ( -1 !== volet.className.indexOf( 'toggled' ) ) {
			volet.className = volet.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			volet.setAttribute( 'aria-expanded', 'false' );
		} else {
			volet.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			volet.setAttribute( 'aria-expanded', 'true' );
		}
	};

	// Get all the link elements within the menus.
	menus = document.getElementsByClassName("nav-menu");
	if ( 'undefined' !== typeof menus ) {
		for ( j = 0, lenJ = menus.length; j < lenJ; j++ ) {
			links = menus[j].getElementsByTagName( 'a' );
			// Each time a menu link is focused or blurred, toggle focus.
			for ( i = 0, len = links.length; i < len; i++ ) {
				links[i].addEventListener( 'focus', toggleFocus, true );
				links[i].addEventListener( 'blur', toggleFocus, true );
			}
		}
		
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		var self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				if ( -1 !== self.className.indexOf( 'focus' ) ) {
					self.className = self.className.replace( ' focus', '' );
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}

	
} )();
