window.EAPVM_CALLBACKS.push(function() {
	var _ = this;

	_.shortcodeRaw = function(shortcode, attributes, content, callback) {
		jQuery.post(
			ajaxurl,
			{
				action: EasyAzonPro_Popup.ajaxActionShortcodeRaw,
				shortcode: _.generateShortcode(shortcode, attributes, content)
			},
			function(data, status) {
				if('function' === typeof callback) {
					callback.apply(_);
				}

				_.parent.send_to_editor(data.markup);
			},
			'json'
		);
	};
});
