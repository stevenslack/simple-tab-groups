<?php
/**
 * Simple Tab Groups Admin Class
 *
 * @package   Tab_Group_Admin
 * @author    Steven Slack <steven@s2webpress.com>
 * @license   GPL-2.0+
 * @link      http://stevenslack.com
 * @copyright 2015 S2 Web LLC
 */

/**
 * Tabs Admin class.
 */
class Tab_Group_Admin {


	/**
	 * Instance of this class.
	 *
	 * @var      object
	 */
	protected static $instance = null;


	/**
	 * Slug of the plugin screen.
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;


	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 */
	private function __construct() {

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add a button next to the media uploader
		add_action( 'media_buttons', array( $this, 'add_tinymce_tab_button' ), 11 );

		// Add Content to modal window
		add_action( 'admin_footer',  array( $this, 'add_tab_group_modal' ) );

	}

	/**
	 * Return an instance of this class.
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
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->base == 'post' || $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( 'simple-tab-groups-admin', S2_TABS_PATH . '/assets/css/admin.css', array(), S2_TAB_VERSION );
		}

	}


	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->base == 'post' ) {
			wp_enqueue_script( 'simple-tab-groups-admin-script', S2_TABS_PATH . '/assets/js/admin.js', array( 'jquery' ), S2_TAB_VERSION );
		}

	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 */
	public function add_plugin_admin_menu() {

		// Add a settings / documentation page to the Settings menu.
		$this->plugin_screen_hook_suffix = add_submenu_page(
			'edit.php?post_type=s2_simple_tabs',
			__( 'Simple Tab Groups settings and documentation', 'simple-tab-groups' ),
			__( 'Settings & Help', 'simple-tab-groups' ),
			'manage_options',
			'simple-tab-groups',
			array( $this, 'display_plugin_admin_page' )
		);

	}


	/**
	 * Render the settings page
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}


	/**
	 * Adds a button next to the add media button in the TinyMCE editor
	 *
	 */
	public function add_tinymce_tab_button() {

		$screen = get_current_screen();

		if ( $screen->post_type === 's2_simple_tabs' ) {
			return;
		}
		// Add jQuery Thickbox
		add_thickbox();

		?>
		<a title="<?php _e( 'Add Tab Groups' , 'simple-tab-groups' ); ?>" href="#TB_inline?width=auto&amp;inlineId=s2tabs_modal" class="thickbox button add-tab-group"><span class="dashicons dashicons-category" aria-hidden="true"></span><?php _e( 'Add Tabs' , 'simple-tab-groups' ); ?></a>
		<?php

	}

	/**
	 * The header for the modal window
	 * @return string
	 */
	public function modal_header() {
		?>
		<div id="s2tabs_modal" style="display:none;">
			<div class="s2-tab-modal">
				<h2><?php _e( 'Choose a tab group', 'simple-tab-groups' ) ?></h2>
				<p><?php  _e( 'Select which group of tabs you would like to display in the post or page:', 'simple-tab-groups' ) ?></p>
		<?php
	}


	/**
	 * Select field for the tab groups taxonomy terms
	 *
	 * @param  array|string|WP_Error $terms Array of term objects or an empty array if no terms were found.
	 * @return string
	 */
	public function select_tabs( $terms ) {
		?>
			<select id="s2-tab-select">
				<option value="">&#45;&#45;<?php _e( 'All Tabs' , 'simple-tab-groups' ) ?>&#45;&#45;</option>
				<?php foreach ( $terms as $term ) : ?>
					<option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
				<?php endforeach; ?>
			</select>
		<?php
	}


	/**
	 * Show tab notice and modal buttons
	 *
	 * @return string
	 */
	public function show_modal_buttons() {
		?>
		<div id="notice" class="tab-notice"><p><?php _e( 'You have not assigned any tabs to a group. You can still insert tabs, it will just display all of them by default.', 'simple-tab-groups' ); ?></p></div><!--/.tab-notice -->
		<p>
			<a href="<?php echo admin_url( 'edit-tags.php?taxonomy=s2_tab_group&post_type=s2_simple_tabs' ); ?>" class="button-secondary"><?php _e( 'Edit Tab Groups', 'simple-tab-groups' ); ?></a>
			<a id="s2-tab-cancel" class="button-secondary" onclick="tb_remove();" title="Cancel"><?php _e( 'Cancel' , 'simple-tab-groups' ); ?></a>
		</p>
		<?php
	}


	/**
	 * Tab Options | passes values to the shortcode
	 *
	 * @see    admin.js to pass the "name" values to the shortcode
	 * @return string
	 */
	public function tab_options() {
		?>
		<h4><a><?php _e( 'Tab Options', 'simple-tab-groups' ); ?></a><span class="dashicons dashicons-arrow-down-alt2"></span></h4>
		<p class="more-options">
			<input type="checkbox" class="simple-tabs-btn" name="button" value="true"><?php _e( ' Show tabs as standalone buttons?', 'simple-tab-groups' ); ?><br>
			<input type="checkbox" class="simple-tabs-jquery" name="jquery" value="true"><?php _e( ' Use the jQuery version :(. This is useful if you need to support IE8 or older browsers', 'simple-tab-groups' ); ?><br>
		</p>
		<?php
	}


	/**
	 * Adds a modal window for the "Add tabs" button above the TinyMCE editor
	 */
	public function add_tab_group_modal() {

		$tab_groups = get_terms( 's2_tab_group' ); // get all the tab group terms
		$count      = count( $tab_groups );        // Get the number of taxonomy terms for tab groups

		$this->modal_header(); // Display the modal header

		// if there are terms in the tab groups show a select field
		if ( $count > 0 ) {
			$this->select_tabs( $tab_groups ); // display the select tab group terms field
		} else {
			$this->show_modal_buttons(); // display notice and modal buttons
		}

		$this->tab_options(); // display the tab options

		?>
		<p>
			<a id="s2-tab-insert" class="button-primary" title="Insert Tabs"><?php _e( 'Insert Tabs' , 'simple-tab-groups' ); ?></a>
			<a id="s2-tab-cancel" class="button-secondary" onclick="tb_remove();" title="Cancel"><?php _e( 'Cancel' , 'simple-tab-groups' ) ?></a>
		</p>
		<p><?php _e( '', 'simple-tab-groups' ) ?><br>
			<a href="<?php echo admin_url( 'edit.php?post_type=s2_simple_tabs&page=simple-tab-groups' ); ?>">
				<?php _e( 'Visit the simple tab groups documentation page' , 'simple-tab-groups' ) ?>
			</a>
		</p>
		</div><!--/.s2-tab-modal --></div><!--/#s2tabs_modal -->

		<?php

	} // end add_tab_group_modal


} // end of admin class
