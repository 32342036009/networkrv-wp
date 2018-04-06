var EasyAzonPopupStatesCTAVM = function(popup) {
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
			key:        _.key(),
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
		_.popup.shortcode(EasyAzonPro_PopupStates_CTA.shortcode, _.attributes(), '');
	};

	_.insertRaw = function() {
		_.popup.shortcodeRaw(EasyAzonPro_PopupStates_CTA.shortcode, _.attributes(), '', function() {
			_.popup.searchActivate();
		});
	};
}

window.EAPVM_CALLBACKS.push(function() {
	var _ = this;

	_.ctaButtons = ko.computed(function() {
		var locale = _.locale();

		return EasyAzonPro_PopupStates_CTA.buttons[locale] ? EasyAzonPro_PopupStates_CTA.buttons[locale] : [];
	});

	_.cta = new EasyAzonPopupStatesCTAVM(_);
	_.ctaActive = ko.computed(function() { return 'cta' == _.state(); });
	_.ctaActivate = function(product) {
		var buttons = [], button = '', tags = [], tag = '';

		_.locale(_.search.response.locale);
		_.cta.product(product);

		tags = _.tags();
		tag  = tags.length > 1 ? tags[1].value : '';
		_.cta.tag(tag);

		buttons = _.ctaButtons();
		button  = buttons.length > 0 ? buttons[0].key : '';
		_.cta.key(button);

		_.state('cta');
	};
});

window.EAPVM_RESET_CALLBACKS.push(function() {
	var _ = this;
});
