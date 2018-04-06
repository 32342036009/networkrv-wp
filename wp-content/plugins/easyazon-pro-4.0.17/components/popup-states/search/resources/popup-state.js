window.EAPVM_CALLBACKS.push(function() {
	var _          = this,
		_arguments = _.search.arguments;

	_.search.index         = ko.observable('All');
	_.search.keywordsExist = ko.computed(function() { return jQuery.trim(_.search.keywords()) != ''; });
	_.search.priceMin      = ko.observable('');
	_.search.priceMax      = ko.observable('');
	_.search.sort          = ko.observable('relevancerank');

	_.search.priceMinAvailable = ko.computed(function() { return 'All' != _.search.index().key && 'Blended' != _.search.index().key; });
	_.search.priceMaxAvailable = ko.computed(function() { return 'All' != _.search.index().key && 'Blended' != _.search.index().key; });

	_.search.indices = ko.computed(function() {
		var locale = _.search.locale();

		return EasyAzonPro_PopupStates_Search.indices[locale] ? EasyAzonPro_PopupStates_Search.indices[locale] : [];
	});
	_.search.indicesExist = ko.computed(function() { return _.search.indices().length > 0; });

	_.search.sorts = ko.computed(function() {
		var index = _.search.index();

		return ko.utils.arrayFilter(EasyAzonPro_PopupStates_Search.sorts, function(item) {
			return jQuery.inArray(item.key, index.sort) >= 0 && '' != jQuery.trim(item.name);
		}).sort(function(a, b) {
			return ((a.name == b.name) ? 0 : ((a.name > b.name) ? 1 : -1));
		});
	});
	_.search.sortsExist = ko.computed(function() { return _.search.sorts().length > 0; });

	_.search.arguments = function(previous) {
		var _     = this,
			args  = {},
			index = _.index(),
			sort  = _.sort();

		if(previous) {
			args.index    = _.response.index;
			args.priceMin = _.response.priceMin;
			args.priceMax = _.response.priceMax;
			args.sort     = _.response.sort;
		} else {
			args.index    = index && index.key ? index.key : '';
			args.priceMin = _.priceMin();
			args.priceMax = _.priceMax();
			args.sort     = sort && sort.key ? sort.key : '';
		}

		return jQuery.extend(_arguments.call(_, previous), args);
	};
});
