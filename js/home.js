(function($) {
	$( document ).ready(function() {
	var width=$(window).width();
	
	//Slider
	if(width < 768) {
		var owl = $(".home-slider .owl-carousel.mobile");
		owl.owlCarousel({
			loop:true,
			nav : true,
			dots : false,
			autoplay:false,
			navSpeed:2000,
			items: 1,
			checkVisible: false
		});
	} else {
		var owl = $(".home-slider .owl-carousel.desktop");
		owl.owlCarousel({
			loop:true,
			nav : true,
			dots : false,
			autoplay:false,
			navSpeed:2000,
			items: 1,
			checkVisible: false
		});
	}
	

	//Get header height to set slider height to full screen
	let root = document.documentElement;
	let height=$('.site-header').outerHeight();
	root.style.setProperty('--hauteur-header', height+ "px");





	}); //fin document ready
})( jQuery );