(function($) {

	$( document ).ready(function() {
		var width=$(window).width();
	
		
		/********* Identifier menu-item actif pour un template single ou tax produits*******/
		/******Fonctionne en ajoutant une classe productType et une classe productTax à chaque lien de menu dans le menu "Product categories" */

		var productTypes=['exceptional_assets','virtuous_companies','philantropy'];
		productTypes.forEach(function(value,index) {
			if($('body').hasClass('single-'+value)) {
				console.log('on est sur un single ',value);
				$('body').addClass('js-product-menu-open');
				$('.menu-item.'+value).addClass('current-menu-item');
			}
		});

		var productTaxonomies=['cat_assets','cat_projects','cat_companies'];
		productTaxonomies.forEach(function(value,index) {
			if($('body').hasClass('tax-'+value)) {
				console.log('on est sur une archive ',value);
				$('body').addClass('js-product-menu-open');
				$('.menu-item.'+value).addClass('current-menu-item');
			}
		});
		
		/********* Ouvrir-fermer les sous-menus mobile **********/
		var ouvrirSousMenu=$('.volet-navigation .ouvrir-sous-menu');
		if(ouvrirSousMenu.length>0) {
			ouvrirSousMenu.click(function(e) {
				var sousMenu=$(this).siblings('.sub-menu');
				var menuItem=$(this).parent('.menu-item');

				if($(menuItem).hasClass('js-ouvert')) {
					//le sous-menu était ouvert, on le referme
					$(menuItem).removeClass('js-ouvert');
					$(sousMenu).slideUp();
				} else {
					//on referme tous les sous-menus
					$('.volet-navigation .menu-item').removeClass('js-ouvert');
					$('.volet-navigation .sub-menu').slideUp();

					//on ouvre celui demandé
					$(menuItem).addClass('js-ouvert');
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
		/*
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
		}*/

		


		/****************** Filtre articles, producteurs et produits *************************/	
		if($(".liste-filtrable").length>0) {

			$(".liste-filtrable").each(function(item){
				console.log($(this));
				var section=$(this);
				var filtre=$(this).find('.filtre');
				var liste=$(this).find('ul.list');
				var id=$(this).attr('id');
				console.log(section);
				console.log(filtre);
				console.log(liste);
				console.log(id);

				var page=parseInt($(section).attr('data-pagination'));
				if(typeof(page)!==NaN && page > 0) {
					var optionsListe = {
						valueNames: ['term'],
						page: page, 
						pagination: true
					};
				} else {
					var optionsListe = {
						valueNames: ['term']
					};
					
				}
				var listeFiltrable = new List(id, optionsListe);
				console.log(listeFiltrable);

				//reset filtre au chargement de la page
				$(filtre).find("input:checked").prop('checked',false);
			
				$(filtre).change(function(){
					//quand on clique sur une checkbox
					$(liste).animate(
						{opacity:0},
						400,
						'linear',
						function(){
							//callback de l'animation
							//on récupère le type sélectionné
							var checkedInputs=$(filtre).find("input:checked");

							if(checkedInputs.length==0) {
								//on réinitialise le filtre
								listeFiltrable.filter();
							} else {
								var selectedValues='';
								$(checkedInputs).each(function(){
									selectedValues+=' '+$(this).val();
								});
								console.log(selectedValues);
								//on filtre la liste pour ne garder que les éléments dont le term se trouve dans selectedValues
								listeFiltrable.filter(function(item) {
									return (selectedValues.indexOf(item.values().term) >= 0);
								});
							}
							//la nouvelle liste est prête, nouvelle animation pour réafficher
							$(liste).animate(
								{opacity:1}, 1000, 'linear'	
							);
						}
					);
				
				});

			});
		
		}


		/****************** Product carousel *************************/	
		var owl = $(".owl-carousel");
		owl.owlCarousel({
			loop:true,
			nav : false,
			dots : true,
			autoplay:true,
			autoplayTimeout:3000,
			autoplaySpeed:1500,
			autoplayHoverPause:true,
			items: 1,
			checkVisible: false
		});

		var titreDejaAjoute=false;
		owl.on('changed.owl.carousel', function(event) {
			if(titreDejaAjoute) {
				return;
			}
			var dots= $('.owl-dot');
			$.each(dots, function (indexInArray, valueOfElement) { 
				$(valueOfElement).attr('title','Go to image '+(indexInArray+1));
			});
			titreDejaAjoute=true;
		})

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
