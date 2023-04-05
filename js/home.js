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
			autoplayTimeout:3000,
			autoplaySpeed:1500,
			autoplayHoverPause:true,
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
			autoplayTimeout:3000,
			autoplaySpeed:1500,
			autoplayHoverPause:true,
			items: 1,
			checkVisible: false
		});
	}
	

	//sticky link

	}); //fin document ready
})( jQuery );