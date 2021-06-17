(function($) {
	$( document ).ready(function() {
		//Append html divs to show feedback
		$('#vat_confirm_country').after('<p id="vat-info">Info ici</p>');
		var seller=VatVars.seller_country;

		//On country field change, update/show/hide vat tax info
		$('#bcountry').on('change',function(e) {
			//selected country
			var selected=$(this).val();
			console.log(selected);

			//is selected country in list of EU countries ?
			var inEU = 0 != $('#eucountry option[value='+selected+']').length;

			if(!inEU ) {
				$('#eucountry').val('NOTEU');
				$('#show_vat').prop('checked',false);
				$('#vat_number').val('');
				$('#vat_have_number, #vat_number_validation_tr').hide();
				
			} else	if(selected===seller) {
				$('#eucountry').val(selected);
				$('#show_vat').prop('checked',false);
				$('#vat_number').val('');
				$('#vat_have_number, #vat_number_validation_tr').hide();
				//+ message d'info
			} else {
				$('#eucountry').val(selected);
				$('#vat_have_number, #vat_number_validation_tr').show();
				var vatNumber=$('#zs-vat-number').val();
				if(vatNumber.length > 0) {
					$('#show_vat').prop('checked',true);
					$('#vat_number').val($('#zs-vat-number').val());
				} else {
					//message d'info sur le taux de TVA, obtenu en ajax
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

	}); //fin document ready
})( jQuery );