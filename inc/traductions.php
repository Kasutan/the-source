<?php
//TODO supprimer ?

add_filter( 'gettext', function($translated, $original, $domain) {

	if($domain == 'paid-memberships-pro') {
		switch ($original) {
			case 'Level' :
				return esc_html__('Status','the-source');
				break;
			case 'Past Invoices' :
				return 'My invoices';
				break;
			case 'Lost Password?' :
				return esc_html__('Forgot your password?','the-source');
				break;
			case 'Username or Email Address' :
				return 'Email';
				break;
			default:
				break;
		}
	} else if($domain == 'pmpro-auto-renewal-checkbox') {
		switch ($original) {
			case 'Yes, renew at %s' :
				return esc_html__('I activate automatic renewal at %s','the-source');
				break;
			default:
				break;
		}
	}  else  {
		switch ($original) {
			case 'Username or Email Address' :
				return 'Email';
				break;
			default:
				break;
		}
	}
	return $translated;
}, 10, 3);