/**
 * Theme Customizer for simple tab groups
 *
 */

( function( $ ) {

	wp.customize('s2_tab_styles[tab_bg]',function( value ) {
	    value.bind(function(to) {
	        $('.stg-wrap .simple-tab-groups a').css('background-color', to );
	        $('.stg-wrap .simple-tab-groups button').css('background-color', to );
	    });
	});
	wp.customize('s2_tab_styles[tab_color]',function( value ) {
	    value.bind(function(to) {
	        $('.stg-wrap .simple-tab-groups a').css('color', to );
	        $('.stg-wrap .simple-tab-groups button').css('color', to );
	    });
	});
	wp.customize('s2_tab_styles[tab_content_bg]',function( value ) {
	    value.bind(function(to) {
	        $('.stg-wrap .tab-content').css('background-color', to );
	    });
	});

	wp.customize('s2_tab_styles[tab_content_color]',function( value ) {
	    value.bind(function(to) {
	        $('.stg-wrap .tab-content').css('color', to );
	    });
	});
	wp.customize('s2_tab_styles[tab_content_border_color]',function( value ) {
	    value.bind(function(to) {
	        $('.stg-wrap .tab-content').css('border-color', to );
	    });
	});

} )( jQuery );
