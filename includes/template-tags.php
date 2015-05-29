<?php
/**
 * Simple Tab Group Template Tags
 */

if ( ! function_exists( 'simple_tab_groups' ) ) {

    /**
     * Simple Tab Groups Template Tag
     *
     * @param  string  $group   The tab group to query for. Returns all tabs by default
     * @param  boolean $buttons false displays list elements. True returns buttons
     * @param  boolean $jquery  set to true to use the legacy version of the tabby js plugin
     * @param  boolean $echo    echo or return. Echo is true by default
     * @return string  The tabs
     */
    function simple_tab_groups( $group = '', $buttons = false, $jquery = false, $echo = true ) {

        // call display tabs and set defaults
        $simple_tabs = STG_Display::get_instance()->display_tabs( $group, $buttons, $jquery );

        if ( $echo ) {
            echo $simple_tabs;
        } else {
            return $simple_tabs;
        }
    }

}
