<?php

/**
 * Social Links
 *
 */
function kasutan_social_links() {

	$social_urls=array();
	if (function_exists('seopress_social_accounts_facebook_option')) {
		$social_urls['Facebook']=seopress_social_accounts_facebook_option();
	}
	if (function_exists('seopress_social_accounts_instagram_option')) {
		$social_urls['Instagram']=seopress_social_accounts_instagram_option();
	}
	if (function_exists('seopress_social_accounts_pinterest_option')) {
		$social_urls['Pinterest']=seopress_social_accounts_pinterest_option();
	}
	if (function_exists('seopress_social_accounts_twitter_option')) {
		$social_urls['Twitter']=seopress_social_accounts_twitter_option();
	}
	if (function_exists('seopress_social_accounts_linkedin_option')) {
		$social_urls['LinkedIn']=seopress_social_accounts_linkedin_option();
	}
	if (function_exists('seopress_social_accounts_youtube_option')) {
		$social_urls['Youtube']=seopress_social_accounts_youtube_option();
	}

	ob_start();
	if(!empty($social_urls)) {
		echo '<ul class="social-links">';
		foreach($social_urls as $reseau => $url) {
			if(!empty($url)) {
				printf('<li><a href="%s" target="_blank" rel="noopener noreferrer">%s<span class="label">%s</span></a></li>',
					$url,
					kasutan_picto( array( 'icon' => strtolower($reseau), 'label' => $reseau, 'class' => strtolower($reseau) )),
					$reseau
				);
			}
		}
		echo '</ul>';
	}
	return ob_get_clean();
}
add_shortcode( 'social_links', 'kasutan_social_links' );
