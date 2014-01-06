<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   S2_Tab_Groups
 * @author    Steven Slack <steven@s2webpress.com>
 * @license   GPL-2.0+
 * @link      http://s2webpress.com
 * @copyright 2013 S2 Web LLC
 */

		// Checks if the user has entered a tab group attribute
		if ( term_exists( $group, 's2_tab_group') ) {

			$args = array( 
			'post_type' => 's2_simple_tabs',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'tax_query' => array(                     
			    'relation' 	=> 'AND',                   
				      array(
				        'taxonomy' 			=> 's2_tab_group',               
				        'field' 			=> 'slug',                    
				        'terms' 			=> $group,
				        'include_children' 	=> false,
				        'operator' 			=> 'IN'
				      ),
			      ) // end tax query

			);	// end $args array

		// if no attribute is set return all tabs
		} else {
			$args = array( 
				'post_type' => 's2_simple_tabs',
				'orderby' 	=> 'menu_order',
				'order' 	=> 'ASC'
			);	
		}

		$the_query = new WP_Query( $args );	  

		$tabs = ''; // initialize the output variable
		
		$tabs .= '<div id="s2-tab-groups"><ul class="s2-tab-nav">';

			// Run the loop first to creat an unordered list of tab pages with the queried group
			if ( $the_query->have_posts() ) :
				while ( $the_query->have_posts() ) : $the_query->the_post();
				
			        $tabs .= sprintf( ( '<li><a href="#tab-%1$s">%2$s</a></li>' ),
			        		$id = get_the_ID(),
							$title = get_the_title()
						);

				endwhile; 
			endif;	

			$tabs .= '</ul>';

			/**
			 * Run the loop a second time to return the content. It is necessary in this case
			 * to uncouple the titles and the content to display properly. If only one query was run
			 * each tab section would stack on top of each other. Got a better idea? Let me know.
			 */

			if ( $the_query->have_posts() ) :
				while ( $the_query->have_posts() ) : $the_query->the_post();
					$tabs .= sprintf( ( '<div id="tab-%1$s" class="tab-content">%2$s' ),
							$id = get_the_ID(),
							apply_filters( 'the_content', get_the_content() ) // wpautop makes sure the tab content contains the formatting in the TinyMCE WYSIWYG editor									
						);

					if ( current_user_can( 'edit_pages' ) ) {
						$edit_tab = get_edit_post_link();
						// display an edit tab link
						$tabs .= '<a href="'. $edit_tab .'" class="edit-tabs">'. __('edit this tab', $this->plugin_slug ) .'</a>';
						
					} // end if current user can edit pages

					$tabs .= '</div>'; // end .tab-content

				endwhile; 
			endif;	

			wp_reset_postdata();

		$tabs .= '</div>'; // end #s2-tab-groups

		return $tabs;

?>

<!-- This file is used to markup the public facing aspect of the plugin. -->