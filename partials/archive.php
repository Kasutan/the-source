<?php
/**
 * Archive partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

echo '<li class="post-summary">';

	ea_post_summary_image('woocommerce_thumbnail');

	echo '<div class="post-summary__content">';
		echo $context;
		ea_entry_category('archive');
		printf('<p class="entry-date">%s</p>',get_the_date('d/m/Y'));
		ea_post_summary_title();
		the_excerpt();
		printf('<a href="%s" class="suite">Lire la suite<span class="screen-reader-text">de %s</span><span class="chevrons-suite">>>></span></a>',get_the_permalink(),get_the_title());
	echo '</div>';

echo '</li>';
