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
	public function enqueue_front_end_scripts( $jquery ) {

		// Simple Tab Group CSS
		wp_enqueue_style( 'stg-styles', S2_TABS_PATH . '/assets/css/display.css', array(), S2_TAB_VERSION );

		// Simple Tab Group Javascript
		// If the jquery option is set load the legacy version of tabby
		if ( $jquery !== false ) {

			// The jQuery version for legacy browsers
			wp_enqueue_script(
				'stg-jquery-script',
				S2_TABS_PATH . '/assets/js/tabby-jquery.js',
				array( 'jquery' ),
				S2_TAB_VERSION, true
			);

		} else {

			// Load minified version of ClassList and Tabby
			wp_enqueue_script(
				'stg-script',
				S2_TABS_PATH . '/assets/js/display.js',
				array( 'jquery' ),
				S2_TAB_VERSION,
				true
			);

		}
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
	 * The Tabs Query
	 *
	 * @param  string $group the slug of the taxonomy term to query for
	 * @return object | the tab query object
	 */
	public function tabs_query( $group ) {

		// Checks if the user has entered a tab group attribute
		if ( term_exists( $group, 's2_tab_group') ) {

			$query_args = array(
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

			$query_args = array(
				'post_type' => 's2_simple_tabs',
				'orderby'   => 'menu_order',
				'order'     => 'ASC'
			);
		}

		// Add a filter for the Query Arguments
		$args = apply_filters( 'stg_query_args', $query_args );

		// return the query object as tab_query
		$tab_query = new WP_Query( $args );

		return $tab_query;
	}

	/**
	 * Standalone buttons
	 *
	 * @param  object $tab_query the query object
	 * @return string div with buttons
	 */
	public function standalone_buttons( $tab_query, $jquery ) {

		$output    = '';
		$data_attr = ( $jquery !== false ) ? 'data-target' : 'data-tab';

		// Run the query first to creat an unordered list of tab pages with the queried group
		if ( $tab_query->have_posts() ) :
			$output .= '<div class="simple-tab-groups">';
			while ( $tab_query->have_posts() ) : $tab_query->the_post();

				// the list element
				$output .= sprintf( ( '<button %1$s="#tab-%2$s">%3$s</button>' ),
					$data_attr,
					get_the_ID(),
					get_the_title()
				);

			endwhile;
			$output .= '</div><!--/.simple-tab-groups -->';
		endif;

		return apply_filters( 'stg_button_output', $output );
	}


	/**
	 * List tabs in a Unordered element
	 *
	 * @param  object $tab_query the query object
	 * @return string unordered list of tabs
	 */
	public function list_link_tabs( $tab_query, $jquery ) {

		$output    = '';
		$data_attr = ( $jquery !== false ) ? 'data-target' : 'data-tab';

		// Run the query first to creat an unordered list of tab pages with the queried group
		if ( $tab_query->have_posts() ) :
			$output .= '<ul class="simple-tab-groups">';
			while ( $tab_query->have_posts() ) : $tab_query->the_post();

				// the list element
				$output .= sprintf( ( '<li class="lister"><a href="#" %1$s="#tab-%2$s">%3$s</a></li>' ),
					$data_attr,
					get_the_ID(),
					get_the_title()
				);

			endwhile;
			$output .= '</ul><!--/.simple-tab-groups -->';
		endif;

		return apply_filters( 'stg_list_output', $output );
	}


	/**
	 * The Tab Content
	 * @param  int $tab_count [description]
	 * @return string
	 */
	public function tab_content_output( $tab_count ) {

		$tab_content = ''; // initialize the output variable
		$edit_link   = ''; // set the edit link variable to an empty string

		$active_class = ( $tab_count === 1 ) ? ' active' : '';

		if ( current_user_can( 'edit_pages' ) ) {
			// display an edit tab link
			$edit_link = sprintf( '<a href="%s" class="edit-tabs">%s</a>',
				get_edit_post_link(), // edit tab link URL
				__( 'edit this tab', 'simple-tab-groups' )
			);
		} // end if current user can edit pages

		$tab_content .= sprintf( '<div id="tab-%1$s" class="tab-content%2$s">%3$s %4$s</div><!--/.tab-content -->',
			get_the_ID(),
			$active_class,
			apply_filters( 'the_content', get_the_content() ),
			$edit_link
		); // end .tab-content

		return apply_filters( 'tab_content', $tab_content );
	}


	/**
	 * Display Tabs
	 *
	 * @param  string  $group   The tab group to display
	 * @param  boolean $buttons true to display buttons | false to display list items
	 * @param  boolean $jquery  true to use the jquery version | false to use pure javascript
	 * @return string           The Tabs
	 */
	public function display_tabs( $group = '', $buttons = false, $jquery = false ) {

		// generate a random number ID for each instance of a tab group
		$tab_id = rand( 0, 9999 );

		// enqueue scripts
		$this->enqueue_front_end_scripts( $jquery );

		// Setup the Tabs Query
		// pass the shortcode parameter / argument through the query to
		// return the correct tab group
		$tab_query = $this->tabs_query( $group );

			// display the tabs in either buttons or list elements
			if ( $buttons !== false ) {
				$tab_select = $this->standalone_buttons( $tab_query, $jquery ); // the button tabs
			} else {
				$tab_select = $this->list_link_tabs( $tab_query, $jquery );     // the list tabs
			}

			/**
			 * Run the loop a second time to return the content. It is necessary in this case
			 * to uncouple the titles and the content to display properly. If only one query was run
			 * each tab section would stack on top of each other.
			 */
			$tab_content = ''; // makin sure we have a variable set if there are no posts
			$tab_count = 0;

			if ( $tab_query->have_posts() ) :
				while ( $tab_query->have_posts() ) : $tab_query->the_post();

					$tab_count++; // increase the tab count

					$tab_content .= $this->tab_content_output( $tab_count );

				endwhile;
			endif;

			wp_reset_postdata(); // reset-yer-postdata

		$tabs = sprintf( '<div id="tab-group-%1$s" class="stg-wrap">%2$s %3$s</div><!--/.stg-wrap -->',
			$tab_id,
			$tab_select,
			$tab_content
		);

		return $tabs;

	} // end display_tabs


	/**
	 * Shortcode Attributes and Display
	 *
	 * USAGE: [simple-tab-groups group="Tab Group Name or Slug" buttons="boolen(true or false)" jquery="boolen(true or false)"]
	 *
	 * @param  array $atts shortcode attributes
	 * @return string The Tabs
	 */
	public function shortcode_attributes ( $atts ) {
		// USAGE: [simple-tab-groups group="Tab Group Name or Slug"]

		// One Attribute group which the user will input the queried group with this attribute
		$shortcode = shortcode_atts(
			array(
				'group'   => '',
				'buttons' => false,
				'jquery'  => false,
			), $atts
		);

		// enqueue scripts, styles and display the tabs
		$tabs = $this->display_tabs( $shortcode['group'], $shortcode['buttons'], $shortcode['jquery'] );
		return $tabs;

		//include_once 'views/public.php';

	} // end s2 tab shortcode function


} // end class
