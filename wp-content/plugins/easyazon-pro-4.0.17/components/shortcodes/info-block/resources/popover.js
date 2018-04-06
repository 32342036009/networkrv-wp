jQuery(document).ready(function($) {
	$('.easyazon-price-disclaimer').popover({
		container: 'body',
		html: true,
		placement: 'auto bottom',
		template: '<div class="popover easyazon-popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content easyazon-popover-content"></div></div>',
		trigger: 'hover'
	});
});
