(function ($) {
	'use strict';
	$(function () {
		if (jQuery.fn.select2) {
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
		}

		$('.wp-tab-bar a').click(function (event) {
			event.preventDefault();

			// Limit effect to the container element.
			var context = $(this).closest('.wp-tab-bar').parent();
			$('.wp-tab-bar li', context).removeClass('wp-tab-active');
			$(this).closest('li').addClass('wp-tab-active');
			$('.wp-tab-panel', context).hide();
			$($(this).attr('href'), context).show();
		});

		// Make setting wp-tab-active optional.
		$('.wp-tab-bar').each(function () {
			if ($('.wp-tab-active', this).length)
				$('.wp-tab-active', this).click();
			else
				$('a', this).first().click();
		});

		jQuery(document).ready(function($) {
			var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
			editorSettings.codemirror = _.extend(
				{},
				editorSettings.codemirror,
				{
					indentUnit: 2,
					tabSize: 2,
					mode: 'css',
				}
			);
			wp.codeEditor.initialize($('#custom_css'),  );
		})
	});
})(jQuery);
