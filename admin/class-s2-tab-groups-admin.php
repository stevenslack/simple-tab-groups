<?php
/**
 * Plugin Name.
 *
 * @package   S2_Tab_Groups_Admin
 * @author    Steven Slack <steven@s2webpress.com>
 * @license   GPL-2.0+
 * @link      http://s2webpress.com
 * @copyright 2013 S2 Web LLC
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-s2-tab-groups.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package S2_Tab_Groups_Admin
 * @author  Your Name <email@example.com>
 */


class S2_Tab_Groups_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 * @TODO:
		 *
		 * - Rename "S2_Tab_Groups" to the name of your initial plugin class
		 *
		 */
		$plugin = S2_Tab_Groups::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		// add dashicon to admin header
		add_action( 'admin_head', array( $this, 'add_dashicon_style' ) );
		
		// init process for button control
		// add_action( 'init', array( $this, 's2_simple_tabs_shortcode_button' ) );

		// Add a button next to the media uploader
		add_action( 'media_buttons', array( $this, 'add_my_custom_button' ), 11 );

		// Add Content to modal window
		add_action( 'admin_footer',  array( $this, 'add_inline_popup_content' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @TODO:
	 *
	 * - Rename "S2_Tab_Groups" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->base == 'post' ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), S2_Tab_Groups::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @TODO:
	 *
	 * - Rename "S2_Tab_Groups" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->base == 'post' ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), S2_Tab_Groups::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * @TODO:
		 *
		 * - Change 'Page Title' to the title of your plugin admin page
		 * - Change 'Menu Text' to the text for menu item for the plugin settings page
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'Menu Text', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * NOTE:     Adds dashicon to menu icon
	 * Icon used is the category icon which looks like a tab
	 *
	 * @since    1.0.0
	 */
	public function add_dashicon_style() {
		?>
		 
			<style>
			#menu-posts-s2_simple_tabs div.wp-menu-image:before {
			  content: "\f318";
			}
			</style>
		 
		<?php
	}


	/**
	 * Adds a button next to the add media button in the TinyMCE editor
	 * @param [type] $context [description]
	 */
	public function add_my_custom_button() {

		$screen = get_current_screen();
		if ( $screen->post_type != 's2_simple_tabs' ) {

			add_thickbox();
			//append the icon
			$context = '<a title="' . __( 'Add Tab Groups' , $this->plugin_slug ) . '" href="#TB_inline?width=auto&inlineId=popup_container" class="thickbox button add-tab-group"><span class="dashicons dashicons-category"></span>' . __( 'Add Tabs' , $this->plugin_slug ) . '</a>';

			echo $context;

		}
	}

	public function add_inline_popup_content() {

		$tab_groups = get_terms( 's2_tab_group' );
		$count = count( $tab_groups );

			echo '<div id="popup_container" style="display:none;">';
			echo '<div class="s2-tab-modal">';
			echo '<h2>' . __( 'Choose a tab group', $this->plugin_slug ) . '</h2>';
			echo '<p>' . __( 'Select which group of tabs you would like to display in the post or page:', $this->plugin_slug ) . '</p>';

		 if ( $count > 0 ){
		    echo '<select id="s2-tab-select">
		    <option value="">&#45;&#45;' . __( 'Select a tab group' , $this->plugin_slug ) . '&#45;&#45;</option>
		    <option value="[simple-tab-groups]">' . __( 'All Tabs' , $this->plugin_slug ) . '</option>';
			foreach ( $tab_groups as $tab_group ) {

				$tab_group_list .= '<option value="[simple-tab-groups group=&quot;' . $tab_group->slug .'&quot;]">' . $tab_group->name . '</option>';
				
			}
		    echo $tab_group_list;
		    echo '</select>';
		    echo '<p><a id="s2-tab-cancel" class="button-secondary" onclick="tb_remove();" title="Cancel">' . __( 'Cancel' , $this->plugin_slug ) . '</a></p>';
		 } else {
		 	// display an insert shortcode button
		 	echo '<div id="notice" class="tab-notice"><p>' . __( 'You have not assigned any tabs to a group.', $this->plugin_slug ) . '</p></div>';
		 	echo '<p><a href="#" class="insert-all-tabs button-primary">' . __( 'Insert all tabs' , $this->plugin_slug ) . '</a>';
		 	echo '<a href="' . admin_url( 'edit-tags.php?taxonomy=s2_tab_group&post_type=s2_simple_tabs' ) . '" class="button-secondary">' . __( 'Edit Tab Groups', $this->plugin_slug ) . '</a>';
		 	echo '<a id="s2-tab-cancel" class="button-secondary" onclick="tb_remove();" title="Cancel">' . __( 'Cancel' , $this->plugin_slug ) . '</a></p>';
		}

		echo '</div></div>';
	
	}


} // end of class
