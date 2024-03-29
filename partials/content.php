<?php
/**
 * Singular partial
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

$only_members=esc_attr(get_field('zs_only_members'));

echo '<article class="' . join( ' ', get_post_class() ) . '">';

	if( ea_has_action( 'tha_entry_top' ) ) {
		echo '<header class="entry-header">';
		tha_entry_top();
		echo '</header>';
	}

	do_action('ea_content_header_after');
	echo '<div class="entry-content">';
		tha_entry_content_before();

		//Si l'option only_members est cochée et que le visiteur n'est pas membre : cacher le contenu derrière un paywall
		if($only_members && !kasutan_is_member()) {
			if(function_exists('kasutan_paywall')) {
				kasutan_paywall();
			}
		} else {
			the_content();
		}

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ea-starter' ),
			'after'  => '</div>',
		) );

		tha_entry_content_after();
	echo '</div>';

	if( ea_has_action( 'tha_entry_bottom' ) ) {
		echo '<footer class="entry-footer">';
			tha_entry_bottom();
		echo '</footer>';
	}

echo '</article>';
