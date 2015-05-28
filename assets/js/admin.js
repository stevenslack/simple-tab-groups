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

			var btnValue = $( "input[name=button]:checked" ).val();

			if( ! btnValue ) {
				var button = "";
			} else {
				var button = ' buttons="true"';
			};

			var legacy = $( "input[name=jquery]:checked" ).val();

			if( ! legacy ) {
				var jqVersion = "";
			} else {
				var jqVersion = ' jquery="true"';
			};

			send_to_editor( '[simple-tab-groups' + group + button + jqVersion + ']' );
			return false;
		});

		$('.insert-all-tabs').click(function() {
		   	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[simple-tab-groups]');
            tb_remove();
		});

	});

}(jQuery));
