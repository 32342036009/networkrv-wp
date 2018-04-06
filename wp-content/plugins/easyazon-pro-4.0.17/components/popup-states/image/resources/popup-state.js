var EasyAzonPopupStatesImageVM = function(popup) {
	var _      = this;

	_.popup = popup;

	_.imagesAmazon      = ko.observableArray();
	_.imagesAmazonExist = ko.computed(function() { return _.imagesAmazon().length > 0; });

	_.imagesMedia       = ko.observableArray();
	_.imagesMediaExist  = ko.computed(function() { return _.imagesMedia().length > 0; });

	_.image             = ko.observable([]);

	_.imageTab               = ko.observable('amazon');

	_.imageTabAmazonSelected = ko.computed(function() { return 'amazon' === _.imageTab(); });
	_.imageTabAmazonSelect   = function() { _.imageTab('amazon'); };

	_.imageTabMediaSelected  = ko.computed(function() { return 'media' === _.imageTab(); });
	_.imageTabMediaSelect    = function() { _.imageTab('media'); };

	_.product = ko.observable(new EasyAzonProductVM({}));

	_.align    = ko.observable('none');
	_.cart     = ko.observable('');
	_.cloak    = ko.observable('');
	_.localize = ko.observable('');
	_.nw       = ko.observable('');
	_.nf       = ko.observable('');
	_.tag      = ko.observable('');

	_.attributes = function() {
		return {
			align:      _.align(),
			cart:       _.cart(),
			cloak:      _.cloak(),
			height:     _.image().height,
			identifier: _.product().identifier,
			locale:     _.popup.locale(),
			localize:   _.localize(),
			nw:         _.nw(),
			nf:         _.nf(),
			src:        _.image().url,
			tag:        _.tag(),
			width:      _.image().width
		}
	};

	_.cancel = function() {
		_.popup.searchActivate();
	};

	_.imageSave = function(url, callback) {
		jQuery.post(
			ajaxurl,
			{
				action: 'easyazonpro_save_image',
				url: url
			},
			function(data, status) {
				if('function' === typeof callback) {
					callback.call(_, data.url);
				}
			},
			'json'
		);
	};

	_.insert = function() {
		_.imageSave(_.image().url, function(url) {
			_.popup.searchActivate();
			_.popup.shortcode(EasyAzonPro_PopupStates_Image.shortcode, jQuery.extend(_.attributes(), { src: url }), '');
		});
	};

	_.insertRaw = function() {
		_.imageSave(_.image().url, function(url) {
			_.popup.shortcodeRaw(EasyAzonPro_PopupStates_Image.shortcode, jQuery.extend(_.attributes(), { src: url }), '', function() {
				_.popup.searchActivate();
			});
		});
	};
}

window.EAPVM_CALLBACKS.push(function() {
	var _ = this;

	_.image = new EasyAzonPopupStatesImageVM(_);
	_.imageActive = ko.computed(function() { return 'image' == _.state(); });
	_.imageActivate = function(product) {
		var tags = [], tag = '';

		_.locale(_.search.response.locale);
		_.image.product(product);
		_.image.imagesAmazon(product.images);
		_.image.image(product.images.length ? product.images[0] : {});

		tags = _.tags();
		tag  = tags.length > 1 ? tags[1].value : '';
		_.image.tag(tag);

		_.state('image');
	};
});

window.EAPVM_RESET_CALLBACKS.push(function() {
	var _ = this;
});
