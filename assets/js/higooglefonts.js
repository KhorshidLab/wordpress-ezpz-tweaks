/*!
* jQuery HiGoogleFonts Library v0.1.0
* http://asadiqbal.com/
*
* Uses select2 jquery library for creating Picker
*
* Copyright Hiwaas and other contributors
* Released under the Apache license 2.0
* https://github.com/saadqbal/HiGoogleFonts/blob/master/LICENSE
*
* Date: 2016-Feb-20
*/


(function ($) {
	$.fn.higooglefonts = function (options) {

		var settings = $.extend({
			placeholder: 'Choose a font family',
			theme: 'default',
			selectedCallback: function (params) {
			},
			loadedCallback: function (params) {
			}
		}, options);


		var data = data || [];

		fonts.forEach(function (font, index) {

			data.push({
				id: font,
				text: font,
				itemId: index
			});

		});

		var y = 0;
		this.select2({
			placeholder: settings.placeholder,
			theme: settings.theme,
			data: data,
			triggerChange: true,
			allowClear: true,
			templateResult: function (result) {
				var state = $('<div style="background-position:-10px -' + y + 'px !important;" class="li_' + result.itemId + '">' + result.text + '</div>');
				var item = '<style>@import url("https://fonts.googleapis.com/css?family=' + result.text.replace(" ", "+") + '");</style><div style="font-family:' + result.text + '">' + result.text + '</div>';
				var state = $(item);
				y += 29;
				return state;
			}
		});

		this.on("select2:open", function (e) {
			y = 0;
		});
		this.on("select2:close", function (e) {
			y = 0;
		});
		this.on("select2:select", function (e) {
			var font_family = e.params.data.text;

			if (typeof settings.selectedCallback == 'function') { // make sure the callback is a function

				settings.selectedCallback.call(this, e.params.data.text); // brings the scope to the callback
			}


			WebFont.load({
				google: {
					families: [font_family]
				},
				fontactive: function (familyName, fvd) {

					if (typeof settings.loadedCallback == 'function') { // make sure the callback is a function

						settings.loadedCallback.call(this, familyName); // brings the scope to the callback
					}


				}
			});

		});

		// Set empty initial value
		this.val('');
		this.trigger('change');

	}; /// End of function

	var fonts = [
		"Alegreya", "B612", "Muli", "Titillium Web", "Varela", "Vollkorn", "IBM Plex", "Crimson Text", "Cairo", "BioRhyme", "Karla", "Lora", "Frank Ruhl Libre", "Playfair Display", "Archivo", "Spectral", "Fjalla One", "Roboto", "Montserrat", "Rubik", "Source Sans", "Cardo", "Cormorant", "Work Sans", "Rakkas", "Concert One", "Yatra One", "Arvo", "Lato", "Abril FatFace", "Ubuntu", "PT Serif", "Old Standard TT", "Oswald", "PT Sans", "Poppins", "Fira Sans", "Nunito", "Oxygen", "Exo 2", "Open Sans", "Merriweather", "Noto Sans", "Source Sans Pro"
	];

}(jQuery));
