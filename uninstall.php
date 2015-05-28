<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Simple_Tab_Groups
 * @author    Steven Slack <steven@s2webpress.com>
 * @license   GPL-2.0+
 * @link      http://s2webpress.com
 * @copyright 2015 S2 Web LLC
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
{
	exit;

}

// Delete all terms in the taxonomy s2_tab_group
function delete_custom_terms( $taxonomy ){
    global $wpdb;

    $query = 'SELECT t.name, t.term_id
            FROM ' . $wpdb->terms . ' AS t
            INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt
            ON t.term_id = tt.term_id
            WHERE tt.taxonomy = "' . $taxonomy . '"';

    $terms = $wpdb->get_results( $query );

    foreach ( $terms as $term ) {
        wp_delete_term( $term->term_id, $taxonomy );
    }
}

function delete_cpt_posts( $post_type ) {
	$args = array (
		'post_type' => $post_type,
		'nopaging' => true
	);

	$posts = get_posts( $args );

	if (is_array( $posts) ) {
	   foreach ( $posts as $post ) {
	       wp_delete_post( $post->ID, true );
	   }
	}
}

if ( !is_multisite() )
{


	// Delete all custom terms for this taxonomy
	delete_custom_terms('s2_tab_group');
	delete_cpt_posts( 's2_simple_tabs' );
}
else
{
    global $wpdb;
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
    $original_blog_id = get_current_blog_id();

    foreach ( $blog_ids as $blog_id )
    {
        switch_to_blog( $blog_id );

		// Delete all custom terms for this taxonomy
		delete_custom_terms( 's2_tab_group' );
		delete_cpt_posts( 's2_simple_tabs' );

    }

    switch_to_blog( $original_blog_id );
}


