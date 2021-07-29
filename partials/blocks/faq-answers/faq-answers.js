(function($) {

	$( document ).ready(function() {
		var accordions=$('.accordion');
		if(accordions.length > 0) {
			$.each(accordions, function (indexInArray, accordion) { 
				var items=$(accordion).find('.accordion__item');
				var contents=$(accordion).find('.accordion__content');
				var triggers=$(accordion).find('.accordion__trigger');

				$(contents).hide();

				// Loop through all the passed triggers and attach on click and navigation functions
				for (let i = 0; i < triggers.length; i++) {
					$(triggers[i]).click(function(e) {
						accordion_click(accordion,items,triggers,contents,i);
					});
					
					$(triggers[i]).on('keydown',function(e) {
						accordion_navigate(e, accordion, triggers, triggers[i],i);
					});

					$(triggers[i]).on('focus',function(e) {
						focus_trigger(accordion, items[i]);
					});

					$(triggers[i]).on('blur',function(e) {
						blur_trigger(accordion, items[i]);
					});
				}
			});

			function accordion_click(accordion,items,triggers,contents,index) {
				// Determine if we are opening or closing the accordion item
				let method = ($(triggers[index]).attr('aria-expanded') === 'true') ? 'collapse' : 'expand';

				// Loop through each of the item triggers
				//Collapse all answers
					// Set the current trigger to being not expanded
					$(triggers).attr('aria-expanded', 'false');
					// Remove the open class from the accordion item
					$(items).removeClass('open');
					// Remove the active class from the accordion content
					$(contents).removeClass('active');
					$(contents).slideUp();

				// If the detected movement is to expand the accordion item
				if (method === 'expand') {
					$(triggers[index]).attr('aria-expanded', 'true');
					$(items[index]).addClass('open');
					$(contents[index]).addClass('active');
					$(contents[index]).slideDown();
				}
			
			}

			function accordion_navigate(e, accordion,triggers,trigger,index) {
				// Store key value of keypress
				var key = e.which.toString();

				// 38 = Up, 40 = Down
				// Up/ Down arrow
				if (key.match(/38|40/)) {
					var direction = (key.match(/34|40/)) ? 1 : -1;
					var length = triggers.length;
					var newIndex = (index + length + direction) % length;

					triggers[newIndex].focus();

					e.preventDefault();
				}
				else if (key.match(/35|36/)) {
				// 35 = End, 36 = Home keyboard operations
					switch (key) {
						// Go to first question
						case '36':
						triggers[0].focus();
						break;
						// Go to last question
						case '35':
						triggers[triggers.length - 1].focus();
						break;
					}
					e.preventDefault();
				}
			}

			function focus_trigger(accordion,item) {
				$(item).addClass('focus');
			}

			function blur_trigger(accordion,item) {
				$(item).removeClass('focus');
			}
		}

	}); //fin document ready
})( jQuery );