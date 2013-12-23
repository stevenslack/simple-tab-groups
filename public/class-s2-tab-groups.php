<?php
/**
 * Plugin Name.
 *
 * @package   S2_Tab_Groups
 * @author    Steven Slack <steven@s2webpress.com>
 * @license   GPL-2.0+
 * @link      http://s2webpress.com
 * @copyright 2013 S2 Web LLC
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-s2-tab-groups-admin.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package S2_Tab_Groups
 * @author  Your Name <email@example.com>
 */
class S2_Tab_Groups {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * @TODO - Rename "s2-tab-groups" to the name your your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 's2-tab-groups';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		// register the simple tabs post type
		add_action( 'init', array( $this, 's2_tab_post_type' ) );

		// add the shortcode
	    add_shortcode( 'simple-tab-groups', array( $this, 's2_tab_shortcode' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $post;

		// if( has_shortcode( $post->post_content, 'simple-tab-groups') ) {
		//	wp_enqueue_style( $this->plugin_slug . '-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
		// }
		
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		//wp_enqueue_script( $this->plugin_slug . '-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function s2_tab_post_type() {
		
    	register_post_type( 's2_simple_tabs',

			array('labels' => array(

				'name' 					=> __( 'Tabs', $this->plugin_slug ), 
				'singular_name' 		=> __( 'Tab', $this->plugin_slug ), 
				'all_items' 			=> __( 'All Tabs', $this->plugin_slug ), 
				'add_new' 				=> __( 'Add New Tab', $this->plugin_slug ), 
				'add_new_item' 			=> __( 'Add New Tab', $this->plugin_slug ),
				'edit' 					=> __( 'Edit Tab Content', $this->plugin_slug ), 
				'edit_item' 			=> __( 'Edit Tab Content', $this->plugin_slug ), 
				'new_item' 				=> __( 'New Tab', $this->plugin_slug ), 
				'view_item' 			=> __( 'View Tab Content', $this->plugin_slug ), 
				'search_items' 			=> __( 'Search Tabs', $this->plugin_slug ), 
				'not_found' 			=> __( 'Nothing found. Try creating a new tab.', $this->plugin_slug ), 
				'not_found_in_trash' 	=> __( 'Nothing found in Trash', $this->plugin_slug ),
				'parent_item_colon' 	=> ''
				), 

				'description' 			=> __( 'This is a tab', $this->plugin_slug ), /* Custom Type Description */
				'public' 				=> true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'show_ui' 				=> true,
				'show_in_nav_menus'		=> false,
				'query_var'			 	=> true,
				'menu_position' 		=> 100,
				'menu_icon'				=>'dashicons-format-gallery',	
				'rewrite'				=> array( 'slug' => 'tabs', 'with_front' => false ), 	
				'has_archive' 			=> 's2_simple_tabs', 	
				'capability_type' 		=> 'page',
				'hierarchical' 			=> false,
				'supports' 				=> array( 'title', 'editor', 'page-attributes'  ),
				//'register_meta_box_cb'	=> array( $this, 's2_tabs_meta_box_callback' ) // call to register meta box
		 	) 	

		); /* end of register post type */

		register_taxonomy( 's2_tab_group', 
	    	array('s2_simple_tabs'), 
		    	array('hierarchical' 		=> true,            
		    			'labels' 			=> array(
		    			'name' 				=> __( 'Tab Groups', $this->plugin_slug ),
		    			'singular_name' 	=> __( 'Tab Group', $this->plugin_slug ),
		    			'search_items'		=> __( 'Search Tab Groups', $this->plugin_slug ), 
		    			'all_items' 		=> __( 'All Tab Groups', $this->plugin_slug ), 
		    			'parent_item' 		=> __( 'Parent Tab Group', $this->plugin_slug ),
		    			'parent_item_colon' => __( 'Parent Tab Group:', $this->plugin_slug ), 
		    			'edit_item' 		=> __( 'Edit Tab Group', $this->plugin_slug ), 
		    			'update_item' 		=> __( 'Update Tab Group', $this->plugin_slug ), 
		    			'add_new_item' 		=> __( 'Add New Tab Group', $this->plugin_slug ), 
		    			'new_item_name' 	=> __( 'New Tab Group Name', $this->plugin_slug ) 
		    		),
		    	'show_admin_column' => true,
	    		'show_ui' 			=> true,
	    		'show_in_nav_menus'	=> false,
	    		'query_var' 		=> true,
	    		'rewrite' 			=> array( 'slug' => 'tabs' )
	    	)
	    ); // register taxonomy s2_tab_group

	
	} // end register post type

	public function s2_tab_shortcode ( $atts ) {
		// USAGE: [simple-tab-groups group="Tab Group Name or Slug"]
		// 
		wp_enqueue_style( $this->plugin_slug . '-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );

		/** 
		 * Conditionally load javascript inside the shortcode handler 
		 */
		wp_enqueue_script( $this->plugin_slug . '-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION, true );
		
		// One Attribute group which the user will input the queried group with this attribute
		extract( shortcode_atts(
			array(
				'group' => '',
			), $atts )
		);

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

		return $tabs; //Return the HTML

	} // end s2 tab shortcode function
	


}
