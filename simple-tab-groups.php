<?php
/**
 * Simple Tab Groups
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Tab Groups
 * Plugin URI:        https://github.com/S2web/simple-tab-groups
 * Description:       Create tabs and group them together in tab groups. Display a tab group on any post or page without using opening and closing shortcode brackets. Add as many tabs as you want to a page.
 * Version:           2.0
 * Author:            Steven Slack
 * Author URI:        http://stevenslack.com
 * Text Domain:       simple-tab-groups
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


class Simple_Tab_Groups {


	/**
	 * Instance of this class
	 *
	 * @var object
	 */
	private static $instance = null;


	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 */
	private function __construct() {

		$this->setup_constants();
		// require all dependent files
		$this->load_dependencies();

		// registers the Tabs Custom Post Type
		new Tab_Group_Post_Type();

		// the shortcode and tab display
	    STG_Display::get_instance();
	    STG_Customizer::get_instance();

		if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
			// get the admin functionality
			Tab_Group_Admin::get_instance();
		}

		// Load plugin text domain
		add_action( 'plugins_loaded', array( $this, 'load_tabs_textdomain' ) );

	}

	/**
	 * Define the internationalization functionality
	 *
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 */
	public function load_tabs_textdomain() {

		$domain = 'simple-tab-groups';

		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	}


	/*
	 * Sets up constants used throughout the plugin
	 */
	public function setup_constants() {

		// Plugin version
		if ( ! defined( 'S2_TAB_VERSION' ) ) {
			define( 'S2_TAB_VERSION', '2.0' );
		}

		// the base plugin filepath
		if ( ! defined( 'S2_TABS_PATH' ) ) {
			define( 'S2_TABS_PATH', plugins_url( 'simple-tab-groups' ) );
		}

	}


	/**
	 * Load Plugin Files
	 */
	public function load_dependencies() {

		require_once plugin_dir_path( __FILE__ ) . 'includes/class-tabs-post-type.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-tabs-display.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/template-tags.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/admin/class-tabs-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'includes/customizer/class-customizer.php';

	}


	/**
	 * Return an instance of this class.
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	/**
	 * The Activation function. Runs when the plugin is activated
	 */
	public static function activate() {

		/** post types are registered on
		 *  activation and rewrite rules are flushed.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-tabs-post-type.php';

		$tab_groups = new Tab_Group_Post_Type();
		$tab_groups->register_cpt();

		flush_rewrite_rules();

	}

} // end class Simple_Tab_Groups

/*
 * Plugins Loaded
 *
 */
add_action( 'plugins_loaded', array( 'Simple_Tab_Groups', 'get_instance' ) );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 */
register_activation_hook( __FILE__, array( 'Simple_Tab_Groups', 'activate' ) );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
