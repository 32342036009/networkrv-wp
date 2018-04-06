var EasyAzonPopupStatesSearchResultsVM = function(popup) {
	var _      = this;

	_.popup = popup;

	_.cloak    = ko.observable('');
	_.keywords = ko.observable('');
	_.localize = ko.observable('');
	_.nw       = ko.observable('');
	_.nf       = ko.observable('');
	_.tag      = ko.observable('');
	_.text     = ko.observable('');

	_.attributes = function() {
		return {
			keywords: _.keywords(),
			cloak:    _.cloak(),
			locale:   _.popup.locale(),
			localize: _.localize(),
			nw:       _.nw(),
			nf:       _.nf(),
			tag:      _.tag()
		}
	};

	_.cancel = function() {
		_.popup.searchActivate();
	};

	_.insert = function() {
		_.popup.searchActivate();
		_.popup.shortcode(EasyAzon_PopupStates_Link.shortcode, _.attributes(), _.text());
	};

	_.insertRaw = function() {
		_.popup.shortcodeRaw(EasyAzon_PopupStates_Link.shortcode, _.attributes(), _.text(), function() {
			_.popup.searchActivate();
		});
	};
}

window.EAPVM_CALLBACKS.push(function() {
	var _ = this;

	_.searchResults = new EasyAzonPopupStatesSearchResultsVM(_);
	_.searchResultsActive = ko.computed(function() { return 'searchResults' == _.state(); });
	_.searchResultsActivate = function(product) {
		var tags = [], tag = '';

		_.locale(_.search.locale());
		_.searchResults.keywords(_.search.keywords());
		_.searchResults.text(_.parent.easyAzonSelection || _.search.keywords());

		tags = _.tags();
		tag  = tags.length > 1 ? tags[1].value : '';
		_.searchResults.tag(tag);

		_.state('searchResults');
	};
});

window.EAPVM_RESET_CALLBACKS.push(function() {
	var _ = this;
});
