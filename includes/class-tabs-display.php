<?php
/**
 * Simple Tab Groups Display ( STG_Display )
 *
 * @package   STG_Display
 * @author    Steven Slack <steven@s2webpress.com>
 * @license   GPL-2.0+
 * @link      http://stevenslack.com
 * @copyright 2015 S2 Web LLC
 */

/**
 * STG_Display. Includes Display markup and Shortcode
 */
class STG_Display {


	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;


	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 */
	private function __construct() {

		$this->initiate_shortcode(); // include the shortcode action

	}


	/**
	 * Add the Shortcode Hook
	 */
	public function initiate_shortcode() {
		// add the shortcode
		add_shortcode( 'simple-tab-groups', array( $this, 'shortcode_attributes' ) );
	}


	/**
	 * Enqueue the scripts and styles for the Tabs Display
	 *
	 */
	public function enqueue_front_end_scripts() {

		// Simple Tab Group CSS
		wp_enqueue_style( 'simple-tab-groups-styles', S2_TABS_PATH . '/assets/css/display.css', array(), S2_TAB_VERSION );

		// Simple Tab Group Javascript
		// Load minified version of ClassList and Tabby
		wp_enqueue_script( 'simple-tab-groups-script', S2_TABS_PATH . '/assets/js/display.js', array( 'jquery' ), S2_TAB_VERSION, true  );

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


	public function display_tabs( $group = '', $buttons = false, $jquery = false ) {

		// generate a random number ID for each instance of a tab group
		$tab_id = rand( 0, 9999 );

		// enqueue scripts
		$this->enqueue_front_end_scripts();


		// Checks if the user has entered a tab group attribute
		if ( term_exists( $group, 's2_tab_group') ) {

			$args = array(
			'post_type' => 's2_simple_tabs',
			'orderby'   => 'menu_order',
			'order'     => 'ASC',
			'tax_query' => array(
				'relation'  => 'AND',
					  array(
						'taxonomy'         => 's2_tab_group',
						'field'            => 'slug',
						'terms'            => $group,
						'include_children' => false,
						'operator'         => 'IN'
					  ),
				  ) // end tax query

			);  // end $args array

		// if no attribute is set return all tabs
		} else {
			$args = array(
				'post_type' => 's2_simple_tabs',
				'orderby'   => 'menu_order',
				'order'     => 'ASC'
			);
		}

		$tab_query = new WP_Query( $args );

		$tabs = ''; // initialize the output variable

		$tabs .= '<div id="tab-group-' . $tab_id . '" class="s2-tab-groups"><ul class="s2-tab-nav">';

			// Run the loop first to creat an unordered list of tab pages with the queried group
			if ( $tab_query->have_posts() ) :
				while ( $tab_query->have_posts() ) : $tab_query->the_post();

					$tabs .= sprintf( ( '<li class="lister"><a href="#" data-tab="#tab-%1$s">%2$s</a></li>' ),
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

			if ( $tab_query->have_posts() ) :
				while ( $tab_query->have_posts() ) : $tab_query->the_post();
					$tabs .= sprintf( ( '<div id="tab-%1$s" class="tab-content">%2$s' ),
							$id = get_the_ID(),
							apply_filters( 'the_content', get_the_content() ) // wpautop makes sure the tab content contains the formatting in the TinyMCE WYSIWYG editor
						);

					if ( current_user_can( 'edit_pages' ) ) {
						$edit_tab = get_edit_post_link();
						// display an edit tab link
						$tabs .= '<a href="'. $edit_tab .'" class="edit-tabs">'. __('edit this tab', 'simple-tab-groups' ) .'</a>';

					} // end if current user can edit pages

					$tabs .= '</div>'; // end .tab-content

				endwhile;
			endif;

			wp_reset_postdata();

		$tabs .= '</div>'; // end #s2-tab-groups

		return $tabs;

	} // end display_tabs


	public function shortcode_attributes ( $atts ) {
		// USAGE: [simple-tab-groups group="Tab Group Name or Slug"]

		// One Attribute group which the user will input the queried group with this attribute
		$shortcode = shortcode_atts(
			array(
				'group'   => '',
				'buttons' =>  false,
				'jquery'  =>  false,
			), $atts
		);

		// enqueue scripts, styles and display the tabs
		$tabs = $this->display_tabs( $shortcode['group'], $shortcode['buttons'], $shortcode['jquery'] );
		return $tabs;

		//include_once 'views/public.php';

	} // end s2 tab shortcode function


} // end class


if ( ! function_exists( 'simple_tab_groups' ) ) {

	function simple_tab_groups( $group = '', $mouseevent = "click", $animation = "true", $autorotate = "false", $delay = 6000 ) {
		// call display tabs and set defaults
		$simple_tabs = STG_Display::get_instance()->display_tabs( $group, $mouseevent, $animation, $autorotate, $delay );

		echo $simple_tabs;
	}

}
