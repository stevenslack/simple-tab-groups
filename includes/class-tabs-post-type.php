<?php
/**
 * Register all the custom post types and taxonomies
 *
 * @link      http://s2webpress.com
 * @since     1.0.0
 *
 * @package   Tab_Group_Post_Type
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Tab_Group_Post_Type {

	/**
	 * Custom Post Type Construct
	 */
	public function __construct() {

		// register the post type
		add_action( 'init', array( $this, 'register_cpt' ) );

		// register the taxonomy
		add_action( 'init', array( $this, 'register_tax' ) );

	}

	/**
	 * Register s2_simple_tabs custom post type
	 */
	public function register_cpt() {

		$labels = array(
			'name'               => __( 'Tabs', 'simple-tab-groups' ),
			'singular_name'      => __( 'Tab', 'simple-tab-groups' ),
			'all_items'          => __( 'Tabs', 'simple-tab-groups' ),
			'add_new'            => __( 'Add New Tab', 'simple-tab-groups' ),
			'add_new_item'       => __( 'Add New Tab', 'simple-tab-groups' ),
			'edit'               => __( 'Edit Tab Content', 'simple-tab-groups' ),
			'edit_item'          => __( 'Edit Tab Content', 'simple-tab-groups' ),
			'new_item'           => __( 'New Tab', 'simple-tab-groups' ),
			'view_item'          => __( 'View Tab Content', 'simple-tab-groups' ),
			'search_items'       => __( 'Search Tabs', 'simple-tab-groups' ),
			'not_found'          => __( 'Nothing found. Try creating a new tab.', 'simple-tab-groups' ),
			'not_found_in_trash' => __( 'Nothing found in Trash', 'simple-tab-groups' ),
			'parent_item_colon'  => ''
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
		//	'show_in_menu'        => 'options-general.php', // the settings admin menu
			'show_in_nav_menus'   => false,
			'query_var'           => false,
			'menu_position'       => 100,
			'menu_icon'           =>'dashicons-category',
			'rewrite'             => array( 'slug' => 'tabs', 'with_front' => false ),
			'has_archive'         => false,
			'capability_type'     => 'page',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'page-attributes'  )
		);

	   register_post_type( 's2_simple_tabs', $args );

	}

	/**
	 * Register the s2_tab_group taxonomy
	 */
	public function register_tax() {

		$labels = array(
			'name'              => __( 'Tab Groups', 'simple-tab-groups' ),
			'singular_name'     => __( 'Tab Group', 'simple-tab-groups' ),
			'search_items'      => __( 'Search Tab Groups', 'simple-tab-groups' ),
			'all_items'         => __( 'All Tab Groups', 'simple-tab-groups' ),
			'parent_item'       => __( 'Parent Tab Group', 'simple-tab-groups' ),
			'parent_item_colon' => __( 'Parent Tab Group:', 'simple-tab-groups' ),
			'edit_item'         => __( 'Edit Tab Group', 'simple-tab-groups' ),
			'update_item'       => __( 'Update Tab Group', 'simple-tab-groups' ),
			'add_new_item'      => __( 'Add New Tab Group', 'simple-tab-groups' ),
			'new_item_name'     => __( 'New Tab Group Name', 'simple-tab-groups' )
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_ui'           => true,
			'show_in_nav_menus' => false,
			'query_var'         => false,
			'rewrite'           => array( 'slug' => 'tabs' )
		);

		register_taxonomy( 's2_tab_group', array( 's2_simple_tabs' ), $args ); // register taxonomy s2_tab_group

	}

}
