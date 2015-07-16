<?php
/**
 * Simple Tab Groups.
 *
 * @package   STG_Customizer
 * @author    Steven Slack <steven@s2webpress.com>
 * @license   GPL-2.0+
 * @link      http://s2webpress.com
 * @copyright 2013 S2 Web LLC
 */


/**
 * STG_Customizer. This class handles the style of the tabs using the theme customizer.
 *
 * @package STG_Customizer
 * @author  Steven Slack <steven@s2webpress.com>
 */


class STG_Customizer {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Register the theme customizer and theme customizer preview scripts and styles
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// register the customizer function
	    add_action( 'customize_register', array( $this, 'register_tab_colors' ), 50 );

		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_scripts' ) );

		add_action( 'wp_head', array( $this, 'print_customizer_styles') );

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
	 * Custom Tab Colors and styles using the Wordpress Customizer
	 */
	public function register_tab_colors( $wp_customize ) {

	    $wp_customize->add_section(
	        's2_tab_styles',
	        array(
	            'title' 			=> __( 'Tab Styles', 'simple-tab-groups' ),
	            'description' 		=> __( 'Change the color and settings of your tabs to match your theme.', 'simple-tab-groups' ),
	            'priority' 			=> 300,
	        )
	    );

		// Tab Background Color
	    $wp_customize->add_setting(
	        's2_tab_styles[tab_bg]',
	        array(
				'default'              => '#f1f1f1',
				'type'                 => 'option',
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'transport'            => 'postMessage'
	        )
	    );

	    $wp_customize->add_control(
	        new WP_Customize_Color_Control(
	            $wp_customize,
	            's2_tab_styles[tab_bg]',
	            array(
	                'label'      => __( 'Tab Button Background', 'simple-tab-groups' ),
	                'section'    => 's2_tab_styles',
	                'priority' 	 => 0,
	            )
	        )
	    );

		// Tab text color
	    $wp_customize->add_setting(
	        's2_tab_styles[tab_color]',
	        array(
				'default'              => '#A5A5A5',
				'type'                 => 'option',
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'transport'            => 'postMessage'
	        )
	    );

	    $wp_customize->add_control(
	        new WP_Customize_Color_Control(
	            $wp_customize,
	            's2_tab_styles[tab_color]',
	            array(
	                'label'      => __( 'Tab text color', 'simple-tab-groups' ),
	                'section'    => 's2_tab_styles',
	                'priority' 	 => 1,
	            )
	        )
	    );

		// Tab active state background color
	    $wp_customize->add_setting(
	        's2_tab_styles[tab_active]',
	        array(
				'default'              => '#DBDBDB',
				'type'                 => 'option',
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
	        )
	    );

	    $wp_customize->add_control(
	        new WP_Customize_Color_Control(
	            $wp_customize,
	            's2_tab_styles[tab_active]',
	            array(
	                'label'      => __( 'Active tab background', 'simple-tab-groups' ),
	                'section'    => 's2_tab_styles',
	                'priority' 	 => 2,
	            )
	        )
	    );

		// Tab active state text color
	    $wp_customize->add_setting(
	        's2_tab_styles[tab_active_color]',
	        array(
				'default'              => '#6D6D6D',
				'type'                 => 'option',
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
	        )
	    );

	    $wp_customize->add_control(
	        new WP_Customize_Color_Control(
	            $wp_customize,
	            's2_tab_styles[tab_active_color]',
	            array(
	                'label'      => __( 'Active tab text color', 'simple-tab-groups' ),
	                'section'    => 's2_tab_styles',
	                'priority' 	 => 3,
	            )
	        )
	    );

		// Tab hover state background color
	    $wp_customize->add_setting(
	        's2_tab_styles[tab_hover_bg]',
	        array(
				'default'              => '#CFCFCF',
				'type'                 => 'option',
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
	        )
	    );

	    $wp_customize->add_control(
	        new WP_Customize_Color_Control(
	            $wp_customize,
	            's2_tab_styles[tab_hover_bg]',
	            array(
	                'label'      => __( 'Tab hover background', 'simple-tab-groups' ),
	                'section'    => 's2_tab_styles',
	                'priority' 	 => 4,
	            )
	        )
	    );

		// Tab hover state text color
	    $wp_customize->add_setting(
	        's2_tab_styles[tab_hover_color]',
	        array(
				'default'              => '#4D4D4D',
				'type'                 => 'option',
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
	        )
	    );

	    $wp_customize->add_control(
	        new WP_Customize_Color_Control(
	            $wp_customize,
	            's2_tab_styles[tab_hover_color]',
	            array(
	                'label'      => __( 'Tab hover text color', 'simple-tab-groups' ),
	                'section'    => 's2_tab_styles',
	                'priority' 	 => 5,
	            )
	        )
	    );

		// Tab content background color
	    $wp_customize->add_setting(
	        's2_tab_styles[tab_content_bg]',
	        array(
				'default'              => '',
				'type'                 => 'option',
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'transport'            => 'postMessage'
	        )
	    );

	    $wp_customize->add_control(
	        new WP_Customize_Color_Control(
	            $wp_customize,
	            's2_tab_styles[tab_content_bg]',
	            array(
	                'label'      => __( 'Tab content background color', 'simple-tab-groups' ),
	                'section'    => 's2_tab_styles',
	                'priority' 	 => 6,
	            )
	        )
	    );

		// Tab Content text color
	    $wp_customize->add_setting(
	        's2_tab_styles[tab_content_color]',
	        array(
				'default'              => '',
				'type'                 => 'option',
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'transport'            => 'postMessage'
	        )
	    );

	    $wp_customize->add_control(
	        new WP_Customize_Color_Control(
	            $wp_customize,
	            's2_tab_styles[tab_content_color]',
	            array(
	                'label'      => __( 'Tab content text color', 'simple-tab-groups' ),
	                'section'    => 's2_tab_styles',
	                'priority' 	 => 7,
	            )
	        )
	    );

	    // Tab Content border color
	    $wp_customize->add_setting(
	        's2_tab_styles[tab_content_border_color]',
	        array(
				'default'              => '#f1f1f1',
				'type'                 => 'option',
				'sanitize_callback'    => 'sanitize_hex_color_no_hash',
				'sanitize_js_callback' => 'maybe_hash_hex_color',
				'transport'            => 'postMessage'
	        )
	    );


	    $wp_customize->add_control(
	        new WP_Customize_Color_Control(
	            $wp_customize,
	            's2_tab_styles[tab_content_border_color]',
	            array(
	                'label'      => __( 'Tab content area border color', 'simple-tab-groups' ),
	                'section'    => 's2_tab_styles',
	                'priority' 	 => 8,
	            )
	        )
	    );

	} // end register_tab_colors


	/**
	 * Print Styles to header
	 *
	 * @return styles in the header using wp_head
	 */
	public function print_customizer_styles() {

		// get the options for the tab styles.
		$tab_styles = get_option( 's2_tab_styles' );

		$style_wrap = '<style type="text/css" id="custom-tab-styles">%s</style>';

		$initial_styles = '';
		$active_styles  = '';
		$hover_styles   = '';
		$tab_content    = '';

		// set the tab color and background variables
		$tab_color = ( ! empty( $tab_styles['tab_color'] ) ) ? sprintf( 'color:#%s;', $tab_styles['tab_color'] ) : '';
		$tab_bg    = ( ! empty( $tab_styles['tab_bg'] ) ) ? sprintf( 'background-color:#%s;', $tab_styles['tab_bg'] ) : '';

		if ( ! empty( $tab_styles['tab_bg'] ) || ! empty( $tab_styles['tab_color'] ) ) {
			$initial_styles = sprintf( '.stg-wrap .simple-tab-groups a,.stg-wrap .simple-tab-groups button {%s%s}',
				$tab_bg,
				$tab_color
			);
		}

		// set the tab color and background variables
		$tab_active_color = ( ! empty( $tab_styles['tab_active_color'] ) ) ? sprintf( 'color:#%s;', $tab_styles['tab_active_color'] ) : '';
		$tab_active_bg    = ( ! empty( $tab_styles['tab_active'] ) ) ? sprintf( 'background-color:#%s;', $tab_styles['tab_active'] ) : '';

		if ( ! empty( $tab_styles['tab_active_bg'] ) || ! empty( $tab_styles['tab_active_color'] ) ) {
			$active_styles = sprintf( '.stg-wrap .simple-tab-groups li.active a,.stg-wrap .simple-tab-groups button.active {%s%s}',
				$tab_active_bg,
				$tab_active_color
			);
		}

		// set the tab color and background variables
		$tab_hover       = ( ! empty( $tab_styles['tab_hover_color'] ) ) ? sprintf( 'color:#%s;', $tab_styles['tab_hover_color'] ) : '';
		$tab_hover_color = ( ! empty( $tab_styles['tab_hover_bg'] ) ) ? sprintf( 'background-color:#%s;', $tab_styles['tab_hover_bg'] ) : '';

		if ( ! empty( $tab_styles['tab_hover_bg'] ) || ! empty( $tab_styles['tab_hover_color'] ) ) {
			$hover_styles = sprintf( '.stg-wrap .simple-tab-groups li a:hover,.stg-wrap .simple-tab-groups button:hover {%s%s}',
				$tab_hover_color,
				$tab_hover
			);
		}

		// set the tab color and background variables
		$tab_content_bg     = ( ! empty( $tab_styles['tab_content_color'] ) ) ? sprintf( 'color:#%s;', $tab_styles['tab_content_color'] ) : '';
		$tab_content_color  = ( ! empty( $tab_styles['tab_content_bg'] ) ) ? sprintf( 'background-color:#%s;', $tab_styles['tab_content_bg'] ) : '';
		$tab_content_border = ( ! empty( $tab_styles['tab_content_border_color'] ) ) ? sprintf( 'border-color:#%s;', $tab_styles['tab_content_border_color'] ) : '';

		if ( ! empty( $tab_styles['tab_content_bg'] ) || ! empty( $tab_styles['tab_content_color'] ) || ! empty( $tab_styles['tab_content_border_color'] ) ) {
			$tab_content = sprintf( '.stg-wrap .tab-content {%s%s%s}',
				$tab_content_color,
				$tab_content_bg,
				$tab_content_border
			);
		}

		$properties = $initial_styles . $active_styles . $hover_styles . $tab_content;

		if ( $tab_styles['tab_color'] || $tab_styles['tab_bg'] ) {
			echo sprintf( $style_wrap, $properties );
		}

	}

	/**
	 * [enqueue_customizer_scripts description]
	 * @return [type] [description]
	 */
	public function enqueue_customizer_scripts() {

		wp_enqueue_script( 'simple-tab-groups-customizer-script', S2_TABS_PATH . '/includes/customizer/js/customizer.js', array( 'jquery', 'customize-preview' ), S2_TAB_VERSION, true );

	} // STG_Customizer_preview

}







