var EasyAzonPopupStatesInfoBlockVM = function(popup) {
	var _      = this;

	_.popup = popup;

	_.product = ko.observable(new EasyAzonProductVM({}));

	_.align    = ko.observable('none');
	_.cart     = ko.observable('');
	_.cloak    = ko.observable('');
	_.key      = ko.observable('');
	_.localize = ko.observable('');
	_.nw       = ko.observable('');
	_.nf       = ko.observable('');
	_.tag      = ko.observable('');

	_.attributes = function() {
		return {
			align:      _.align(),
			cart:       _.cart(),
			cloak:      _.cloak(),
			identifier: _.product().identifier,
			key:     _.key(),
			locale:     _.popup.locale(),
			localize:   _.localize(),
			nw:         _.nw(),
			nf:         _.nf(),
			tag:        _.tag(),
		}
	};

	_.cancel = function() {
		_.popup.searchActivate();
	};

	_.insert = function() {
		_.popup.searchActivate();
		_.popup.shortcode(EasyAzonPro_PopupStates_InfoBlock.shortcode, _.attributes(), '');
	};

	_.insertRaw = function() {
		_.popup.shortcodeRaw(EasyAzonPro_PopupStates_InfoBlock.shortcode, _.attributes(), '', function() {
			_.popup.searchActivate();
		});
	};
}

window.EAPVM_CALLBACKS.push(function() {
	var _ = this;

	_.infoBlock = new EasyAzonPopupStatesInfoBlockVM(_);
	_.infoBlockActive = ko.computed(function() { return 'infoBlock' == _.state(); });
	_.infoBlockActivate = function(product) {
		var tags = [], tag = '';

		_.locale(_.search.response.locale);
		_.infoBlock.product(product);

		tags = _.tags();
		tag  = tags.length > 1 ? tags[1].value : '';
		_.infoBlock.tag(tag);

		_.state('infoBlock');
	};
});

window.EAPVM_RESET_CALLBACKS.push(function() {
	var _ = this;
});
