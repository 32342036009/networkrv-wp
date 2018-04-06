function advanced_ads_sticky_check_position_fixed() {
	var container = document.body;
	if (document.createElement && container && container.appendChild && container.removeChild) {
		var el = document.createElement( 'div' );
		if ( ! el.getBoundingClientRect) {
			return null; }
		el.innerHTML = 'x';
		el.style.cssText = 'position:fixed;top:100px;';
		container.appendChild( el );
		var originalHeight = container.style.height,
				originalScrollTop = container.scrollTop;
		// In IE<=7, the window's upper-left is at 2,2 (pixels) with respect to the true client.
		// surprisely, in IE8, the window's upper-left is at -2, -2 (pixels), but other elements
		// tested is just right, so we need adjust this.
		// https://groups.google.com/forum/?fromgroups#!topic/comp.lang.javascript/zWJaFM5gMIQ
		// https://bugzilla.mozilla.org/show_bug.cgi?id=174397
		var extraTop = document.documentElement.getBoundingClientRect().top;
		extraTop = extraTop > 0 ? extraTop : 0;
		container.style.height = '3000px';
		container.scrollTop = 500;
		var elementTop = el.getBoundingClientRect().top;
		container.style.height = originalHeight;
		var isSupported = (elementTop - extraTop) === 100;
		container.removeChild( el );
		container.scrollTop = originalScrollTop;
		return isSupported;
	}
	return null;
}
;

jQuery( document ).ready(function($) {
	// story scroll enable value so it isn’t checked multiple times per page view
	var advanced_ads_sticky_position_fixed_supported = '';

	// only check if there are any sticky ads
	if ($( '.advads-sticky' ).length) {
		// since the test only works after scrolling the page, check when scrolling stops
		$( window ).scroll(function(e) {
			clearTimeout( $.data( this, 'scrollTimer' ) );
			// wait 100ms when scrolling before checking
			$.data(this, 'scrollTimer', setTimeout(function() {
				// don’t do anything if scroll position is 0 == top
				if($( document ).scrollTop() == 0) { return; }
				// check if position fixed is supported; story result in a variable so test runs only once
				if(advanced_ads_sticky_position_fixed_supported == ''){
					advanced_ads_sticky_position_fixed_supported = advanced_ads_sticky_check_position_fixed();
				}
				// if position fixed is unsupported
				if (advanced_ads_sticky_position_fixed_supported !== true) {
					// rewrite sticky ads
					$( '.advads-sticky' ).each(function(key, value) {
						var stickyad = $( value );
						// remove all position related inline styles
						stickyad.css( 'position', '' ).css( 'top', '' ).css( 'right', '' ).css( 'bottom', '' ).css( 'left', '' ).css( 'margin-left', '' );
						clearTimeout( $.data( this, 'scrollTimer' ) );
					});
				}
			}, 100));
		});
	}
});
