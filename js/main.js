(function($) {

	$( document ).ready(function() {
		var width=$(window).width();
	
		
		/*********Afficher/masquer le volet de recherche **********/

		//Au chargement de la page, masquer les volets de recherche
		$('.volet-recherche').css('width',0);
		$('.volet-recherche').hide();

		var boutonRecherche=$("button.recherche");
		if($(boutonRecherche).length>0) {
			$(boutonRecherche).click(function(){
				var voletRecherche=$('#'+$(this).attr('aria-controls'));
				if($(boutonRecherche).attr('aria-expanded')=="false") {
					$(voletRecherche).show();
					$(voletRecherche).css('width','200px');
					$(voletRecherche).css('flex-grow','1');
					$(voletRecherche).attr('aria-expanded','true');
					$(boutonRecherche).attr('aria-expanded','true');
					$('#volet-recherche .search-field').focus();
					$('.nav-main .centreur:first-of-type').hide();
					
				} else {
					$(voletRecherche).css('width','0');
					$(voletRecherche).css('flex-grow','unset');
					$(voletRecherche).fadeOut();
					$(voletRecherche).attr('aria-expanded','false');
					$(boutonRecherche).attr('aria-expanded','false');
					$('.nav-main .centreur:first-of-type').show();
				}

			});
		}
		
		/********* Ouvrir-fermer les sous-menus mobile **********/
		var ouvrirSousMenu=$('.volet-navigation .ouvrir-sous-menu');
		if(ouvrirSousMenu.length>0) {
			ouvrirSousMenu.click(function(e) {
				var sousMenu=$(this).siblings('.sub-menu');

				if($(this).hasClass('js-ouvert')) {
					//le sous-menu était ouvert, on le referme
					$(this).removeClass('js-ouvert');
					$(sousMenu).slideUp();
				} else {
					//on referme tous les sous-menus
					ouvrirSousMenu.removeClass('js-ouvert');
					$('.volet-navigation .sub-menu').slideUp();

					//on ouvre celui demandé
					$(this).addClass('js-ouvert');
					$(sousMenu).slideDown();
				}
			});
		}
		/********* Desktop : neutraliser clic pour lien de menu parent **********/
		/*
		var liensParents=$('.volet-navigation .menu-item-has-children > a');
		if(width>=960 && liensParents.length>0) {
			liensParents.click(function(e) {
				e.preventDefault();
				$(this).blur();
			})
		}*/


		/****************** Sticky header *************************/
		
		var siteHeader=$('.site-header');
		var siteContent=$('.site-main');
		var mainNavigation=$('.nav-main');
		var topbar=$('.topbar');
		
		mainNavigationTop=mainNavigation.offset().top;

		
		if(width>=960) {
			$(window).scroll(function () { // scroll event
				var windowTop = $(window).scrollTop(); // returns number
				if (windowTop > mainNavigationTop ) {
					siteContent.css('margin-top',mainNavigation.outerHeight());
					siteHeader.addClass('js-sticky');
					mainNavigation.addClass('js-sticky');
				} else {
					siteContent.css('margin-top',0);
					siteHeader.removeClass('js-sticky');
					mainNavigation.removeClass('js-sticky');
				}

			});
		} else {
			$(topbar).addClass('js-sticky');
			siteHeader.css('margin-top',topbar.outerHeight());
		}

		


		/****************** Filtre articles, producteurs et produits *************************/	
		if($("#filtre-liste").length>0) {

			var page=parseInt($('#liste-filtrable').attr('data-pagination'));
			if(typeof(page)===NaN || page <=0) {
				page=8;
			}
			var optionsListe = {
				valueNames: ['term'],
				page: page, 
				pagination: true
			};
	
			var listeFiltrable = new List('liste-filtrable', optionsListe);

			var resultats=$('.list, .pagination');
			
			$('#filtre-liste').change(function(){
				//quand on clique sur une checkbox
				$(resultats).animate(
					{opacity:0},
					400,
					'linear',
					function(){
						//callback de l'animation
						//on récupère le type sélectionné
						var selectedValue=$("#filtre-liste input:checked").val();

						if(selectedValue=='tous') {
							//on réinitialise le filtre
							listeFiltrable.filter();
						} else {
							//on filtre la liste pour ne garder que les éléments qui contiennent le type sélectionné
							listeFiltrable.filter(function(item) {
								return (item.values().term.indexOf(selectedValue) >= 0);
							});
						}
						//la nouvelle liste est prête, nouvelle animation pour réafficher
						$(resultats).animate(
							{opacity:1}, 1000, 'linear'	
						);
					}
				);
				
			});



			//Activer filtre si paramètre filtre_cat ds l'url
			const queryString = window.location.search;
			const urlParams = new URLSearchParams(queryString);
			if(urlParams.has('filtre_cat')) {
				//s'il y a un paramètre filtre_cat dans l'url, on coche l'input du filtre correspondant
				$("#filtre-liste input").each(function (index, element) {
					if($(element).val() === urlParams.get('filtre_cat')) {
						$(element).prop("checked", true);
						//on force la mise en oeuvre du filtre
						$('#filtre-liste').trigger('change');
					}
				});
			}

			//Au clic sur un élément de pagination, smooth scroll en haut de la liste
			bindScroll(); // lier les écouteurs au premier affichage

			//lier les écouteurs à chaque fois que la liste est mise à jour + attendre un peu pour que les liens de navigation soient reconstruits
			listeFiltrable.on('updated',function(e) {
				setTimeout(bindScroll,1000);
			})

			function bindScroll() {
				$('.pagination li').click(function(e) {
					$("html, body").animate({
						scrollTop: $('#filtre-liste').offset().top - 60
						}, 500);
				});
			}
		
		}

		/*=================================================
		CART PAGE : changer la quantité avec les boutons + et - puis mettre à jour
		=================================================*/

		var $cart_shop_table = $('table.shop_table.cart');
		if($cart_shop_table.length>0) {
			var timeout;

			kasutan_bind_qty(); // on lie les évènements dès le chargement de la page

			$( document.body ).on( 'updated_wc_div', function(){
				kasutan_bind_qty(); // on recommence après mise à jour du panier
			});
		}
		function kasutan_bind_qty() {

			//Modifier quantité au clic sur un bouton +/-
			$('.change-quantity').click(function(e){
				e.preventDefault();
				var action=$(this).attr("data-value");
				var input=$(this).parents('.quantity').find('input.qty');
				var currentQty=parseInt($(input).val());
				var minQty=parseInt($(input).attr('min'));
				var maxQty=parseInt($(input).attr('max'));
				if(currentQty>minQty && action=="-") {
					$(input).val(currentQty-1);
					kasutan_update_cart();
				} else if (currentQty<maxQty && action=="+") {
					$(input).val(currentQty+1);
					kasutan_update_cart();
				}
			});

			$cart_shop_table.on('change keyup mouseup', 'input.qty', function(){ 
				kasutan_update_cart();
			});
		}

		function kasutan_update_cart() {
			if (timeout != undefined) clearTimeout(timeout); //cancel previously scheduled event
			timeout = setTimeout(function() {
				$('button[name="update_cart"]').prop("disabled", false);
				$('button[name="update_cart"]').trigger('click');
			}, 1000 );
		}

		/*=================================================
		PRODUCT PAGE : changer la quantité avec les boutons + et -
		=================================================*/
		var singleProductButton=$('.single-product .summary .single_add_to_cart_button');
		if(singleProductButton.length>0) {
			kasutan_bind_qty();
		}

		/*=================================================
		Corrections pour l'accessibilité
		=================================================*/
		$(document.body).on('woofc_cart_loaded',function() {
			$('.woofc-menu-item a').attr('title',"Mon panier");
		});
		setTimeout(function(){ 
			$('.woocommerce-product-gallery__trigger').attr('title',"Afficher les images du produit en plein écran");
		}, 1000);		

	}); //fin document ready
})( jQuery );


/*=================================================
Animations
=================================================*/
//Only Use the IntersectionObserver if it is supported

if ('IntersectionObserver' in window) {
	const config = {
		//rootMargin: '50px 20px 75px 30px',
		//threshold: [0, 0.25, 0.75, 1]
		};

		
	observer = new IntersectionObserver((entries) => {
		entries.forEach(entry => {
			if (entry.intersectionRatio > 0) {
			entry.target.classList.add('fade-in');
			observer.unobserve(entry.target);
			} else {
			entry.target.classList.remove('fade-in');
			}
		}, config);
	});

	const fadeInElements=document.querySelectorAll('.js-fade-in-on-visible');
	fadeInElements.forEach(elem => {
		observer.observe(elem);
	});


	observer2 = new IntersectionObserver((entries) => {
		entries.forEach(entry => {
			if (entry.intersectionRatio > 0) {
			jQuery(entry.target).children().addClass('cascade');
			observer.unobserve(entry.target);
			} 
		}, config);
	});

	const cascadeElements=document.querySelectorAll('.js-cascade-on-visible');
	cascadeElements.forEach(item => {
		observer2.observe(item);
	});

	observer3 = new IntersectionObserver((entries) => {
		entries.forEach(entry => {
			if (entry.intersectionRatio > 0) {
				jQuery(entry.target).find('.decor-1, .decor-2, .decor-3, .decor-4, .col-images').addClass('cascade-x');
				jQuery(entry.target).find('.decor-5, .decor-6, .col-texte').addClass('cascade-x-droite');
				jQuery(entry.target).find('.decor-3, .decor-5').addClass('retard-1');
				jQuery(entry.target).find('.decor-2, .decor-6').addClass('retard-2');
			} else {
				jQuery(entry.target).find('.decor-1, .decor-2, .decor-3, .decor-4, .col-images').removeClass('cascade-x');
				jQuery(entry.target).find('.decor-5, .decor-6, .col-texte').removeClass('cascade-x-droite');
			}
		}, config);
	});

	const cascadeElementsX=document.querySelectorAll('.section-animee');
	cascadeElementsX.forEach(item => {
		observer3.observe(item);
	});

} else {
	//if Intersection Observer is not supported, add classes right away
	jQuery('.js-animate-on-visible-cascade').addClass('cascade');
	jQuery('.js-animate-on-visible').addClass('fade-in');
}
