<?php
/**
 * Simple Tab Groups
 *
 * @package   S2_Tab_Groups_Admin
 * @author    Steven Slack <steven@s2webpress.com>
 * @license   GPL-2.0+
 * @link      http://s2webpress.com
 * @copyright 2013 S2 Web LLC
 */

/**
 * Plugin Admin class. 
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
		 * Call $plugin_slug from public plugin class.
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

		// add dashicon to admin header
		add_action( 'admin_head', array( $this, 'add_dashicon_style' ) );

		// Add a button next to the media uploader
		add_action( 'media_buttons', array( $this, 'add_tinymce_tab_button' ), 11 );

		// Add Content to modal window
		add_action( 'admin_footer',  array( $this, 'add_tab_group_modal' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
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
	 * Register and enqueue admin-specific style sheet.
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
		if ( $screen->base == 'post' || $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), S2_Tab_Groups::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
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
		$this->plugin_screen_hook_suffix = add_submenu_page( 
			'edit.php?post_type=s2_simple_tabs', 
			__( 'Simple Tab Groups settings and documentation', $this->plugin_slug ),
			__( 'Settings & Help', $this->plugin_slug ),
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
				'settings' => '<a href="' . admin_url( 'edit.php?post_type=s2_simple_tabs&page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
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
	 * 
	 * @since    1.0.0
	 */
	public function add_tinymce_tab_button() {

		$screen = get_current_screen();
		if ( $screen->post_type != 's2_simple_tabs' ) {

			add_thickbox();
			//append the icon
			$context = '<a title="' . __( 'Add Tab Groups' , $this->plugin_slug ) . '" href="#TB_inline?width=auto&inlineId=s2tabs_modal" class="thickbox button add-tab-group"><span class="dashicons dashicons-category"></span>' . __( 'Add Tabs' , $this->plugin_slug ) . '</a>';

			echo $context;

		} // current screen
	}

	/**
	 * Adds a modal window for the "Add tabs" button above the TinyMCE editor
	 */
	public function add_tab_group_modal() {

		$tab_groups = get_terms( 's2_tab_group' );
		$count  = count( $tab_groups );
		$output = '';

			$output .= '<div id="s2tabs_modal" style="display:none;"><div class="s2-tab-modal">
							<h2>' . __( 'Choose a tab group', $this->plugin_slug ) . '</h2>
							<p>' . __( 'Select which group of tabs you would like to display in the post or page:', $this->plugin_slug ) . '</p>';

		// if there are terms in the tab groups show a select field
		if ( $count > 0 ) {

		    $output .= '<select id="s2-tab-select">
		    <option value="">&#45;&#45;' . __( 'All Tabs' , $this->plugin_slug ) . '&#45;&#45;</option>';

			foreach ( $tab_groups as $tab_group ) {
				$output .= '<option value="' . $tab_group->slug .'">' . $tab_group->name . '</option>';				
			}
		
		    $output .= '</select>
		    ';
		
		} else {

		 	// display an insert shortcode button
		 	$output .= '<div id="notice" class="tab-notice"><p>' . __( 'You have not assigned any tabs to a group.', $this->plugin_slug ) . '</p></div>
		 		<p>
			 		<a href="' . admin_url( 'edit-tags.php?taxonomy=s2_tab_group&post_type=s2_simple_tabs' ) . '" class="button-secondary">' . __( 'Edit Tab Groups', $this->plugin_slug ) . '</a>
			 		<a id="s2-tab-cancel" class="button-secondary" onclick="tb_remove();" title="Cancel">' . __( 'Cancel' , $this->plugin_slug ) . '</a>
		 		</p>';
		}

		$output .= '<h4><a>' . __( 'More Options', $this->plugin_slug ) . '</a><span class="dashicons dashicons-arrow-down-alt2"></span></h4>
				<p class="more-options"><input type="checkbox" class="s2-tab-animation" name="animation" value="false">' . __( ' Remove tab fade effect?', $this->plugin_slug ) . '<br>
				   <input type="checkbox" class="s2-tab-mouseevent" name="mouseevent" value="click">' . __( ' Make tabs change on hover?', $this->plugin_slug ) . '<br>
				   <input type="checkbox" class="s2-tab-autorotate" name="autorotate" value="false">' . __( ' Auto rotate the tabs?', $this->plugin_slug ) . '<br>'
				    . __( 'Enter the delay between tab transitions: ', $this->plugin_slug ) . '<input type="text" class="s2-tab-delay" name="delay" value="6000">
				</p>
				<p>
			    	<a id="s2-tab-insert" class="button-primary" title="Insert Tabs">' . __( 'Insert Tabs' , $this->plugin_slug ) . '</a>
					<a id="s2-tab-cancel" class="button-secondary" onclick="tb_remove();" title="Cancel">' . __( 'Cancel' , $this->plugin_slug ) . '</a>
				</p>
					<p>' . __( 'If you would like to change the behavior of your tabs you can add additional options to your shortcode. Learn more about these options on the documentation page.', $this->plugin_slug ) . '<br>
					<a href="' . admin_url( 'edit.php?post_type=s2_simple_tabs&page=' . $this->plugin_slug ) . '" target="_blank">' . __( 'Visit the documentation page' , $this->plugin_slug ) . '</a>
					</p></div></div>';

		echo $output;
	
	} // end add_tab_group_modal


} // end of class
