jQuery(document).ready(function($){
	$('.advads-ad-group-type input').click(function(){
	    var slider_options = $(this).parents('.advads-ad-group-form').find('.advads-group-slider-options');
	    var number_option = $(this).parents('.advads-ad-group-form').find('.advads-ad-group-number');
	    slider_options.hide();
	    if( 'slider' === $(this).val() ) {
			slider_options.show();
			// set number to all and hide setting
			number_option.val('all').hide();
	    } else if( 'default' === $(this).val() || 'ordered' === $(this).val() ) {
			slider_options.hide();
			number_option.show();
	    }
	});
	$('.advads-ad-group-form').each(function(){
		if( 'slider' === $(this).find('.advads-ad-group-type input:checked').val()){
			$(this).find('.advads-ad-group-number').val('all').hide();
		}
	});
});