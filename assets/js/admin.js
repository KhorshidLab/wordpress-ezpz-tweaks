(function ($) {
	'use strict';
	$(function () {
		$('.cmb-type-font select').each(function () {

			$(this).higooglefonts({
				theme: 'default cmb-font-select2',
				selectedCallback: function (e) {
				},
				loadedCallback: function (font) {
				}
			});
		});

		let admin_font = $('#admin-font').data('selected'),
			editor_font = $('#editor-font').data('selected');

		// Set selected
		// $( '#admin-font' ).val(admin_font);

		$('#admin-font').select2({
			triggerChange: true,
			allowClear: true,
			templateResult: function (result) {
				var item = '<style>@import url("https://fonts.googleapis.com/css?family=' + result.text.replace(" ", "+") + '");</style><div style="font-family:' + result.text + '">' + result.text + '</div>';
				var state = $(item);
				return state;
			}
		}).val(admin_font).trigger('change');

		$('#editor-font').select2({
			triggerChange: true,
			allowClear: true,
			templateResult: function (result) {
				var item = '<style>@import url("https://fonts.googleapis.com/css?family=' + result.text.replace(" ", "+") + '");</style><div style="font-family:' + result.text + '">' + result.text + '</div>';
				var state = $(item);
				return state;
			}
		}).val(editor_font).trigger('change');
	});
})(jQuery);
