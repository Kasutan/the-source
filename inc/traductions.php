<?php

add_filter( 'gettext', function($translated, $original, $domain) {

	if($domain == 'woocommerce') {
		switch ($translated) {
			case 'Description courte du produit' :
				return 'Présentation commerciale';
				break;
			case 'Vous aimerez peut-être aussi&hellip;' :
				return 'Produits recommandés';
				break;
			case 'Produits apparentés' :
				return 'Produits recommandés';
				break;
			case 'Valider la commande' :
				return 'Paiement';
				break;
			default:
				break;
		}
	} else if($domain == 'woofc') {
		switch ($original) {
			case 'Checkout' :
				return 'Paiement';
				break;
			case 'Subtotal' : 
				return 'Total (hors frais de livraison)';
				break;
			default:
				break;
		}
	}

	return $translated;
}, 10, 3);