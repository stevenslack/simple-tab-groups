<?php
/**
 * Simple Tab Groups Widget
 *
 * @package   S2_Tab_Groups_Widget
 * @author    Steven Slack <steven@s2webpress.com>
 * @license   GPL-2.0+
 * @link      http://s2webpress.com
 * @copyright 2013 S2 Web LLC
 */

/**
 * Plugin Widget class. 
 *
 * @package S2_Tab_Groups_Widget
 * @author  Your Name <email@example.com>
 */

class S2_Tab_Groups_Widget extends WP_Widget {

	// constructor
	function wp_my_plugin() {
		/* ... */
	}

	// widget form creation
	function form( $instance ) {	
	/* ... */
	}

	// widget update
	function update( $new_instance, $old_instance ) {
		/* ... */
	}

	// widget display
	function widget( $args, $instance ) {
		/* ... */
	}

} // end class

// register widget
add_action('widgets_init', create_function('', 'return register_widget("S2_Tab_Groups_Widget");'));