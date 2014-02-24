(function ( $ ) {
	"use strict";

	$(function ($) {

		$( ".s2-tab-modal h4" ).click( function() {
			$( ".more-options" ).toggle();
		});


		$("#s2-tab-insert").click(function() {

			var tabGroup  = $( "#s2-tab-select :selected" ).val();

			if( ! tabGroup ) {
				var group = "";
			} else {
				var group = ' group="' + tabGroup + '"';
			};
			
			var aniValue = $( "input[name=animation]:checked" ).val();

			if( ! aniValue ) {
				var animation = "";
			} else {
				var animation = ' animation="false"';
			};

			var mouseValue = $( "input[name=mouseevent]:checked" ).val();
			
			if( ! mouseValue ) {
				var mouseevent = "";
			} else {
				var mouseevent = ' mouseevent="hover"';
			};

			var autoRotate = $( "input[name=autorotate]:checked" ).val();
			var delay = $( "input[name=delay]" ).val();
			
			if( ! autoRotate ) {
				var rotate = "";
			} else {
				var rotate = ' autorotate="true" delay="' + delay + '"';
			};


			send_to_editor( '[simple-tab-groups' + group + animation + mouseevent + rotate + ']' );
			return false;
		});	

		$('.insert-all-tabs').click(function() {
		   	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[simple-tab-groups]');
            tb_remove();
		});

	});

}(jQuery));