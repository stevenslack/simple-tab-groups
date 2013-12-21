/**
 * Theme Customizer for simple tab groups
 *
 */

( function( $ ) {

	wp.customize('s2_tab_styles[tab_bg]',function( value ) {
	    value.bind(function(to) {
	        $('.tab-nav li a').css('background-color', to );
	    });
	});
	wp.customize('s2_tab_styles[tab_color]',function( value ) {
	    value.bind(function(to) {
	        $('.tab-nav li a').css('color', to );
	    });
	});
	wp.customize('s2_tab_styles[tab_active]',function( value ) {
	    value.bind(function(to) {
	        $('.tab-nav li a.active').css('background-color', to );
	    });
	});
	wp.customize('s2_tab_styles[tab_active_color]',function( value ) {
	    value.bind(function(to) {
	        $('.tab-nav li a.active').css('color', to );
	    });
	});
	wp.customize('s2_tab_styles[tab_hover_bg]',function( value ) {
	    value.bind(function(to) {
	        $('.tab-nav li a:hover').css('background-color', to );
	    });
	});
	wp.customize('s2_tab_styles[tab_hover_color]',function( value ) {
	    value.bind(function(to) {
	        $('.tab-nav li a:hover').css('color', to );
	    });
	});
	wp.customize('s2_tab_styles[tab_content_bg]',function( value ) {
	    value.bind(function(to) {
	        $('.tab-content').css('background-color', to );
	    });
	});

	wp.customize('s2_tab_styles[tab_content_color]',function( value ) {
	    value.bind(function(to) {
	        $('.tab-content').css('color', to );
	    });
	});
	
} )( jQuery );