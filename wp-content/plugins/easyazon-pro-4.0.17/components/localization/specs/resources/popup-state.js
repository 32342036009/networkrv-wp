window.EAPVM_CALLBACKS.push(function() {
	var _ = this;

	_.selectProductForLocalization = function(product) {
		_.parent.jQuery('.easyazonpro-localization-field[data-locale="' + _.parent.easyAzonLocalizationSpecLocale + '"]').val(product.identifier);
		_.parent.wp.media.frame.close();
	};

	_.search.localizationSpec    = ko.observable(false);
	_.search.localizationSpecNot = ko.computed(function() { return !_.search.localizationSpec(); });

	if(_.parent.easyAzonLocalizationSpec && _.parent.easyAzonLocalizationSpecLocale) {
		// Hide the locale changer because we shouldn't show it anymore
		jQuery('#easyazon-search-locale').parents('tr').hide();

		// Override this so the "insert shortcodes to keywords" doesn't pop up
		_.search.keywordsExist = false;

		// We are indeed choosing a product for this
		_.search.localizationSpec(true);

		// Force the locale to what we need it to be
		_.search.locale(_.parent.easyAzonLocalizationSpecLocale);
	}
});

window.EAPVM_RESET_CALLBACKS.push(function() {
	var _ = this;

	if(_.parent.easyAzonLocalizationSpec && _.parent.easyAzonLocalizationSpecLocale) {
		_.search.searchDone(false);
		_.search.results.removeAll();
		_.search.locale(_.parent.easyAzonLocalizationSpecLocale);
		_.search.initiate();
	}
});
