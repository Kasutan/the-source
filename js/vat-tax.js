(function($) {
	$( document ).ready(function() {
		//Append html divs to show feedback
		$('#vat_confirm_country').after('<p id="vat-country-info">Info ici</p>');
		var seller=VatVars.seller_country;
		var rates=JSON.parse(VatVars.vat_rates);
		var price=parseFloat(VatVars.price);
		console.log(rates);
		console.log(price);

		//Display price including Tax for EU country
		function displayPriceTTC(selected) {
			console.log(rates);
			var rate=rates[selected];
			console.log(rate);
			var rateDisplay= rate*100 + '%';
			var priceTTC=price*(1+rate);
			var priceDisplay='€'+priceTTC; //TODO nombre de décimales
			return priceDisplay + ' including ' + rateDisplay + ' VAT';
		}

		//On country field change, update/show/hide vat tax info
		$('#bcountry').on('change',function(e) {
			//selected country
			var selected=$(this).val();
			console.log(selected);

			$('.js-vat-eu-message').hide();

			//is selected country in list of EU countries ?
			var inEU = 0 != $('#eucountry option[value='+selected+']').length;

			if(!inEU ) {
				$('#eucountry').val('NOTEU');
				$('#show_vat').prop('checked',false);
				$('#vat_number').val('');
				$('#vat_have_number, #vat_number_validation_tr').hide();
				
			} else	if(selected===seller) {
				$('#eucountry').val(selected);
				var price=displayPriceTTC(selected);
				$('#vat-country-info').html('Total price is '+price+'. The VAT number you may optionaly fill in this form will be shown on your invoice. <br>As The Source is also incorporated in Portugal, VAT will be applied in any case.'); // TODO dynamic seller country name

				$('.js-vat-eu-rate').html('Total price is '+price);

				$('#show_vat').prop('checked',false);
				$('#vat_number').val('');
				$('#vat_have_number, #vat_number_validation_tr').hide();
				//+ message d'info
			} else {
				$('#eucountry').val(selected);
				var price=displayPriceTTC(selected);
				$('#vat-country-info').html('Total price is '+price+'.<br> If you have a EU VAT number, VAT will not be added to your membership price. <br>Please fill in the form below to validate your VAT number. ');
				$('.js-vat-eu-rate').html('Total price is '+price);

				$('#vat_have_number, #vat_number_validation_tr').show();
				var vatNumber=$('#zs-vat-number').val();
				if(vatNumber.length > 0) {
					$('#show_vat').prop('checked',true);
					$('#vat_number').val($('#zs-vat-number').val());
				}
			}
		});

		//On changes in VAT table, update billing fields (country and vat number)
		$('#vat_number').on('change',function(e) {
			$('#zs-vat-number').val($(this).val());
		});

		$('#eucountry').on('change',function(e) {
			$('#bcountry').val($(this).val());
			$('#bcountry').trigger('change');
		});

		//TODO If the VAT number is validated, change info message
		$('#vat_number_message').bind('DOMSubtreeModified', function(event) {
			console.log('retour validation');
			if($(this).hasClass('pmpro_success')) {
				console.log('validation réussie');
			} else {
				console.log('validation échouée');
			}
		});

	}); //fin document ready
})( jQuery );