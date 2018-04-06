window.EAPVM_CALLBACKS.push(function() {
	var _           = this,
		_attributes = _.link.attributes;

	_.link.cart     = ko.observable('');
	_.link.cloak    = ko.observable('');
	_.link.localize = ko.observable('');
	_.link.popups   = ko.observable('');

	_.link.attributes = function() {
		var _     = this;

		return jQuery.extend(_attributes.call(_), {
			cart:     _.cart(),
			cloak:    _.cloak(),
			localize: _.localize(),
			popups:   _.popups()
		});
	};

	_.link.insertRaw = function() {
		var _ = this;

		_.popup.shortcodeRaw(EasyAzon_PopupStates_Link.shortcode, _.attributes(), _.text(), function() {
			_.popup.searchActivate();
		});
	};
});
