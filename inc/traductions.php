<?php

add_filter( 'gettext', function($translated, $original, $domain) {

	if($domain == 'paid-memberships-pro') {
		switch ($original) {
			case 'My Memberships' :
				return 'My membership';
				break;
			case 'Past Invoices' :
				return 'My invoices';
				break;
			default:
				break;
		}
	} else if($domain == 'pmpro-auto-renewal-checkbox') {
		switch ($original) {
			case 'Yes, renew at %s' :
				return 'I activate automatic renewal at %s';
				break;
			default:
				break;
		}
	}
	return $translated;
}, 10, 3);