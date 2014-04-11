<?php
/**
 * Simple Tab Groups
 *
 * @package   S2_Tab_Groups
 * @author    Steven Slack <steven@s2webpress.com>
 * @license   GPL-2.0+
 * @link      http://s2webpress.com/plugins/s2-tab-groups
 * @copyright 2013 S2 Web LLC
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Tab Groups
 * Plugin URI:        s2webpress.com/plugins/simple-tab-groups
 * Description:       Create tabs and group them together in tab groups. Display a tab group on any post or page without using any confusing shortcode functions. Customize your tab styles with the theme customizer! 
 * Version:           1.0.0
 * Author:            S2 Web
 * Author URI:        http://s2webpress.com
 * Text Domain:       s2-tab-groups
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 * Public facing class
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-s2-tab-groups.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'S2_Tab_Groups', 'activate' ) );
register_activation_hook( __FILE__, 'flush_rewrite_rules', 20 );
register_deactivation_hook( __FILE__, array( 'S2_Tab_Groups', 'deactivate' ) );


/*
 * Plugins Loaded
 *
 */
add_action( 'plugins_loaded', array( 'S2_Tab_Groups', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/


require_once( plugin_dir_path( __FILE__ ) . 'customizer/class-customizer.php' );
add_action( 'plugins_loaded', array( 'S2_Tab_Customizer', 'get_instance' ) );

/*
 * Admin Class
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-s2-tab-groups-admin.php' );
	add_action( 'plugins_loaded', array( 'S2_Tab_Groups_Admin', 'get_instance' ) );

}
