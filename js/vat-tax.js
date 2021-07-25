(function($) {
	$( document ).ready(function() {
		//Append html divs to show feedback
		$('#vat_confirm_country').after('<p id="vat-country-info"></p>');
		var seller=VatVars.seller_country;
		var rates=JSON.parse(VatVars.vat_rates);
		var price=parseFloat(VatVars.price);

		//On page load,hide vat validation form (until a country is selected)
		$('#vat_have_number, #vat_number_validation_tr').hide();

		//On page load, if a billing country is already filled, trigger VAT extra info display
		if($('#bcountry').val() !== null) {
			$('#bcountry').trigger('change');
		}


		//Display price including Tax for EU country
		function displayPriceTTC(selected) {
			var rate=rates[selected];
			var rateDisplay= rate*100 + '%';
			var priceTTC=price*(1+rate);
			var priceDisplay='€'+priceTTC; //TODO nombre de décimales
			return priceDisplay + ' including ' + rateDisplay + ' VAT';
		}


		//On country field change, update/show/hide vat tax info
		$('#bcountry').on('change',function(e) {
			//selected country
			var selected=$(this).val();

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
				var priceTTC=displayPriceTTC(selected);
				$('#vat-country-info').html('Total price is '+priceTTC+'. The VAT number you may optionaly fill in this form will be shown on your invoice. <br>As The Source is also incorporated in Portugal, VAT will be applied in any case.'); // TODO dynamic seller country name

				$('.js-vat-eu-rate').html('Total price is '+priceTTC);

				$('#show_vat').prop('checked',false);
				$('#vat_number').val('');
				$('#vat_have_number, #vat_number_validation_tr').hide();
			} else {
				$('#eucountry').val(selected);
				var priceTTC=displayPriceTTC(selected);
				$('#vat-country-info').html('Total price is '+priceTTC+'.<br> If you have a EU VAT number, VAT will not be added to your membership price. <br>Please fill in the form below to validate your VAT number. ');
				$('.js-vat-eu-rate').html('Total price is '+priceTTC);

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

		//If the VAT number is validated, change info message
		$('#vat_number_message').bind('DOMSubtreeModified', function(event) {
			if($(this).hasClass('pmpro_success')) {
				$('#vat-country-info, .js-vat-eu-rate').html('Total price is €'+price+' including €0 VAT (valid EU VAT number).');
				$('#zs-vat-number').addClass('js-valid');
			} else {
				var selected=$('#eucountry').val();
				var priceTTC=displayPriceTTC(selected);
				$('#vat-country-info').html('Total price is '+priceTTC+'.<br> If you have a EU VAT number, VAT will not be added to your membership price. <br>Please fill in the form below to validate your VAT number. '); //TODO isoler messages
				$('.js-vat-eu-rate').html('Total price is '+priceTTC+'.');
				$('#zs-vat-number').removeClass('js-valid');
			}
		});

	}); //fin document ready
})( jQuery );