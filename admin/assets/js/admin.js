(function ( $ ) {
	"use strict";

	$(function () {

		jQuery("#s2-tab-select").change(function() {
			send_to_editor(jQuery("#s2-tab-select :selected").val());
			return false;
		});

		jQuery('.insert-all-tabs').click(function() {
		   	tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[simple-tab-groups]');
            tb_remove();
		});

	});

}(jQuery));