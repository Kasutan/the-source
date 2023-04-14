(function($) {

	$( document ).ready(function() {
		var width=$(window).width();
	
		
		/********* Identifier menu-item actif pour un template single ou tax produits*******/
		/******Fonctionne en ajoutant une classe productType et une classe productTax à chaque lien de menu dans le menu "Product categories" */

		var productTypes=['exceptional_assets','virtuous_companies','philantropy'];
		productTypes.forEach(function(value,index) {
			if($('body').hasClass('single-'+value)) {
				$('body').addClass('js-product-menu-open');
				$('.menu-item.'+value).addClass('current-menu-item');
			}
		});

		var productTaxonomies=['cat_assets','cat_projects','cat_companies'];
		productTaxonomies.forEach(function(value,index) {
			if($('body').hasClass('tax-'+value)) {
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


		/********* Modifier label language switcher dans menu mobile **********/
		var lsItem=$('.volet-navigation .menu-my-account.mobile .wpml-ls-item');
		if(lsItem.length > 0) {
			var lien=$(lsItem).find('a');
			var span=$(lien).find('.wpml-ls-display');
			if($(lien).attr('title')==="FR") {
				$(span).html('Version française');
			} else if($(lien).attr('title')==="EN") {
				$(span).html('English version');
			}
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

		


		/****************** Filtre produits *************************/	
		if($(".liste-filtrable").length>0) {

			$(".liste-filtrable").each(function(item){
				var section=$(this);
				var filtre=$(this).find('.filtre');
				var liste=$(this).find('ul.list');
				var id=$(this).attr('id');

				var page=parseInt($(section).attr('data-pagination'));
				if(typeof(page)!==NaN && page > 0) {
					var optionsListe = {
						valueNames: ['term','published','card-title'],
						page: page, 
						pagination: true
					};
				} else {
					var optionsListe = {
						valueNames: ['term','published','card-title']
					};
				}
				var listeFiltrable = new List(id, optionsListe);

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


				//TODO script tri fonction de l'option choisie dans le menu déroulant
				//https://listjs.com/api/#sort

			});


			/*Boutons mobile*/
			$('.ouvre-filtre').click(function(){
				$(this).attr('aria-hidden','false');
				$(this).parents('.liste-filtrable').find('.filtre').addClass('toggled');
			});
			$('.ferme-filtre, .reset').on('click',function(e){
				e.preventDefault(); //sinon rechargement page déclenché par list.js ?
				var filtre=$(this).parents('.filtre');
				if($(this).hasClass('reset')) {
					$(filtre).find("input:checked").prop('checked',false);
					$(filtre).trigger('change');
				}
				$(this).parents('.liste-filtrable').find('.ouvre-filtre').attr('aria-hidden','true');
				$(filtre).removeClass('toggled');
			});
		}


		/****************** Product carousel *************************/	
		var owl = $(".product-carousel.owl-carousel");
		owl.owlCarousel({
			loop:true,
			nav : false, // TODO flèches pour la version agrandie
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

		var boutonsToggleGallery=$('.product-top .gallery-open, .product-top .gallery-close');
		if(boutonsToggleGallery.length > 0) {
			$(boutonsToggleGallery).click(function(e) {
				$('.product-top').toggleClass('js-gallery-opened');
				//TODO recalculate owl carousel quand on ouvre la galerie + focus trap ? + action bouton fullscreen
			})
		}
		

		/****************** Toggle my selection *************************/	
		$('.js-to-selection').change(function(e){
			var input=$(this);
			$(input).prop('disabled',true);

			var selected=$(input).prop('checked');
			var user=$(input).attr('data-user');
			var product=$(input).attr('data-product');
			var action='';
			if(selected) {
				action='add';
			} else {
				action='remove';
			}

			var isSelectionPage=false;
			if($(input).parent('formgroup').hasClass('my-selection-page')) {
				isSelectionPage=true;
			}

			var errorMessage=$(input).parent('formgroup').find('.error');

			$.ajax({
				type: "POST",
				url: thesourceVars.ajax_url,
				data: {
					nonce: thesourceVars.nonce,
					action: 'kasutan_update_selection_for_user',
					data: {
						product: product,
						user: user,
						action: action
					},
				},
				success: function(response){
					//Success
					if (response) {
						$(input).prop('disabled',false);
						//update span with count in header
						$('.selection #count').html(response);
						//Hide product on selection page
						if(isSelectionPage && action==="remove") {
							$(input).parents('.product').hide();
						}
					} else {
						console.log('le php a renvoyé une réponse false');
						$(errorMessage).show();
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					//Error
					console.log('erreur ajax',errorThrown);
					$(errorMessage).show();				
				},
				timeout: 60000
			});

		});

		/****************** Open/close contact request popup *************************/	
		$('.js-popup-open').modaal({
			content_source: '#popup-contact',
			background : '#173a65',

		});
		$('.popup-close').click(function(e) {
			$('#modaal-close').click();
		})

		/****************** Send contact request *************************/	
		$('.js-send-request').click(function(e){
			var button=$(this);
			$(button).prop('disabled',true);

		
			var user=$(button).attr('data-user');
			var source=$(button).attr('data-source');
			var product=$(button).attr('data-product');
			var mainAdvisor=$(button).attr('data-main-advisor');
			var backupAdvisor=$(button).attr('data-backup-advisor');
			

			var failureMessage=$('.popup-failure');
			var successMessage=$('.popup-success');
			var sendMessage=$('.popup-send');
			var mainPageFormgroup=$('formgroup.contact-request');

			$.ajax({
				type: "POST",
				url: thesourceVars.ajax_url,
				data: {
					nonce: thesourceVars.nonce,
					action: 'kasutan_send_new_contact_request',
					data: {
						product: product,
						user: user,
						source: source,
						mainAdvisor: mainAdvisor,
						backupAdvisor: backupAdvisor
					},
				},
				success: function(response){
					//Success
					if (response) {
						$(sendMessage).hide();
						$(successMessage).show();
						$(mainPageFormgroup).addClass('request-sent');
					} else {
						console.log('le php a renvoyé une réponse false');
						$(sendMessage).hide();
						$(failureMessage).show();
						$('.modaal-inner-wrapper').addClass('failure');
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					//Error
					console.log('erreur ajax',errorThrown);
					//$(errorMessage).show();				
				},
				timeout: 60000
			});

		});

		/****************** Toggle email option *************************/	
		$('.js-toggle-option').click(function(e){
			var button=$(this);
			$(button).prop('disabled',true);

		
			var user=$(button).attr('data-user');
			var slug=$(button).attr('data-slug');
			var checked=$(button).hasClass('checked');
			var value=1;

			if(checked) {
				value=0;	
			}
			
			$.ajax({
				type: "POST",
				url: thesourceVars.ajax_url,
				data: {
					nonce: thesourceVars.nonce,
					action: 'kasutan_update_email_preference',
					data: {
						user: user,
						slug: slug,
						value: value
					},
				},
				success: function(response){
					//Success
					if (response) {
						$(button).toggleClass('checked');
						if(checked) {
							$(button).attr('title','Activate this option');
						} else {
							$(button).attr('title','Deactivate this option');
						}
						$(button).prop('disabled',false);
					} else {
						console.log('le php a renvoyé une réponse false');
						
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					//Error
					console.log('erreur ajax',errorThrown);
					//$(errorMessage).show();				
				},
				timeout: 60000
			});

		});


		/********************************************************************************** 
		 ************************* CHECKOUT PAGE 
		 * ******************************************************************************/	

		/************ Fix status visibility on renewal input label ***************/	
		var renewInput=$('.pmpro_checkout-fields #autorenew');
		if(renewInput.length > 0) {

			var label=$(renewInput).siblings('label');
			$(label).addClass('js-checkbox-label');
			if($(renewInput).prop('checked')) {
				$(label).addClass('js-checked');
			}

			$(renewInput).on('change',function(e) {
				if($(renewInput).prop('checked')) {
					$(label).addClass('js-checked');
				} else {
					$(label).removeClass('js-checked');
				}
			});
			$(renewInput).on('focusin',function(e) {
				$(label).addClass('js-focus');
			});
			$(renewInput).on('focusout',function(e) {
				$(label).removeClass('js-focus');
			});
		}



		if($('body').hasClass('pmpro-checkout')) {

			/************ Autofill language input (hidden with CSS) ***************/	

			var lang=$('html').attr('lang');
			$('#zs-lang').val(lang);

			/************ Show label on input change/focus ***************/	

			var inputs=$('#pmpro_user_fields, #pmpro_billing_address_fields').find('select, input');
			if(inputs.length > 0) {
				$(inputs).on('focus change',function(e){
					var label=$(this).siblings('label');
					$(label).addClass('floating');
				});
			}

			function validateField(input) {
				var val=$(input).val();
				if($(input).attr('type')==='email') {
					return validateEmail(val);
				} else if($(input).attr('type')==='password') {
					return validatePassword(val);
				} else if($(input).attr('id')==='bphone') {
					return validatePhone(val);
				} else if($(input).attr('id')==='zs-canal') {
					return false;
				} else if($(input).attr('id')==='zs-canal-other') {
					return false;
				} else if($(input).val()) {
					return true;
				} else {
					return false;
				}
			}
			
			//Vérifier dès le chargement de la page - au cas où des valeurs soient déjà présentes
			$(inputs).each(function(index,item){
				if(validateField(item)) {
					$(item).addClass('js-valid');
				} else {
					$(item).removeClass('js-valid');
				}
				//Afficher le label s'il y a une valeur (le placeholder n'est pas visible)
				if($(item).val()) {
					$(item).siblings('label').addClass('floating');
				}

			});

			//Masquer ou afficher le champ other canal
			if($('#zs-canal').val()==="other") {
				$('#zs-canal-other_div').show();
			} else {
				$('#zs-canal-other_div').hide();
			}

			//Vérifier à la perte de focus - là on signale aussi si l'input est invalide pour les champs requis
			$(inputs).on('focusout', function(e) {
				//validateField($(this));
				if(validateField($(this))) {
					$(this).removeClass('js-invalid');
					$(this).addClass('js-valid');
				} else if(!$(this).hasClass('canal')){
					$(this).removeClass('js-valid');
					if($(this).attr('required')) {
						$(this).addClass('js-invalid');
					}
				}
			});

			//Disable la première option du select canal (elle sert de label en fait)
			$('#zs-canal').find('option[value="null"]').prop("disabled",true);
			$('#zs-canal').find('option[value="null"]').attr("disabled",true);
			

			//Vérif spéciale pour select canal - on signale invalide dès le changement de valeur
			$('#zs-canal').on('change',function(e){
				let val=$(this).val();
				if(val==="null") {
					$(this).removeClass('js-valid');
					$(this).addClass('js-invalid');
				} else if(val==="other") {
					$('#zs-canal-other_div').show();
					if($('#zs-canal-other').val()=='') {
						$('#zs-canal-other').removeClass('js-valid');
					} else {
						$(this).addClass('js-valid');
					}
				} else {
					$('#zs-canal-other_div').hide();
					$(this).addClass('js-valid');
				}
			})


			//Vérif spéciale pour champ canal-other - sa valeur impacte la validité du champ select
			$('#zs-canal-other').on('focusout',function(e){
				let val=$(this).val();
				let select=$('#zs-canal');
				if(select.val()!=="other") {
					return;
				}
				if(val=="") {
					$(select).removeClass('js-valid');
					$(this).removeClass('js-valid');
				} else {
					$(select).addClass('js-valid');
					$(this).addClass('js-valid');
				}

			})


			function validateEmail(email) {
				const res = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				return res.test(String(email).toLowerCase());
			}

			function validatePassword(text) 
			{ 
				var passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{12,1000}$/;
				return text.match(passw);
			}

			/*--------------------------------------------------------------
			# https://stackoverflow.com/questions/38483885/regex-for-french-telephone-numbers
			# Numéro international https://www.regextester.com/97440
			--------------------------------------------------------------*/
			function validatePhone(text) 
			{ 
				var internationalPhoneRegex=/^(([+][0-9]{1,3}))\s*[-\s\.]?[(]?[0-9]{1,3}[)]?([-\s\.]?[0-9]{2}){3,4}$/;
				return text.match(internationalPhoneRegex);
			}


			//Toggle info tax selon pays choisi
			//au chargement de la page
			toggleInfoTax();
			//Quand on change de pays
			$('#bcountry').change(function() {
				toggleInfoTax();
			})
			function toggleInfoTax() {
				if($('#bcountry').val()=='CH') {
					$('#info-tax-CF').show();
				} else {
					$('#info-tax-CF').hide();
				}
			}


		}
		

		/********************************************************************************** 
		************************* LOGIN PAGE 
		* ******************************************************************************/	

		/************ Fix status visibility on Remember me checkbox label ***************/	
		var rememberInput=$('body.pmpro-login #rememberme');
		if(rememberInput.length > 0) {

			var label=$(rememberInput).parent('label');
			$(label).addClass('js-checkbox-label');
			if($(rememberInput).prop('checked')) {
				$(label).addClass('js-checked');
			}

			$(rememberInput).on('change',function(e) {
				if($(rememberInput).prop('checked')) {
					$(label).addClass('js-checked');
				} else {
					$(label).removeClass('js-checked');
				}
			});
			$(rememberInput).on('focusin',function(e) {
				$(label).addClass('js-focus');
			});
			$(rememberInput).on('focusout',function(e) {
				$(label).removeClass('js-focus');
			});
		}


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
