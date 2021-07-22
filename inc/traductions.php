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
	} 
	return $translated;
}, 10, 3);