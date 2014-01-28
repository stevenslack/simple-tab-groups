(function ( $ ) {
	"use strict";

	$(function ($) {

		$("#s2-tab-select").change(function() {
			send_to_editor($("#s2-tab-select :selected").val());
			return false;
		});

		$('.insert-all-tabs').click(function() {
		   	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[simple-tab-groups]');
            tb_remove();
		});

	});

}(jQuery));