jQuery( document ).ready(function( $ ){
    $( '.advads-option-group-refresh input:checkbox:checked' ).each( function() {
        var number_option = $( this ).parents( '.advads-ad-group-form' ).find( '.advads-option-group-number' );
        number_option.val( 'all' ).hide();
    });

    $( '.advads-option-group-refresh input:checkbox' ).click( function() {
        var number_option = $( this ).parents( '.advads-ad-group-form' ).find( '.advads-option-group-number' );
        if ( this.checked ) {
            number_option.val( 'all' ).hide();
        } else {
            number_option.show();
        }
    });
});




function advads_cb_check_set_status( status, msg ) {
    if ( status === true ) {
        jQuery( '#advads-cache-busting-possibility' ).val( true );
    } else {
        jQuery( '#advads-cache-busting-possibility' ).val( false );
        jQuery( '#advads-cache-busting-error-result' ).append( msg ? '<br />' + msg : '' ).show();
    }

    jQuery( '#advads-cache-busting-test' ).hide();
}

function advads_cb_check_ad_markup( ad_content ) {
    if ( ! ad_content ) {
        jQuery( '#advads-cache-busting-test' ).remove();
        return;
    }

    // checks whether the ad contains the jQuery.document.ready() and document.write(ln) functions
    if ( ( /\)\.ready\(/.test( ad_content ) || /(\$|jQuery)\(\s*?function\(\)/.test( ad_content ) ) && /document\.write/.test( ad_content ) ) {
        advads_cb_check_set_status( false );
        return;
    }

    var iframe = document.getElementById("advads-cache-busting-test");
    // inject jQuery inside the frame
    var script = iframe.contentWindow.document.createElement("script");
    iframe.contentWindow.alert = iframe.contentWindow.confirm = iframe.contentWindow.prompt = function() {}

    script.innerHTML = 'window.jQuery = window.$ = parent.jQuery;';
    iframe.contentWindow.document.body.appendChild(script);
    // inject div inside the frame
    var div = iframe.contentWindow.document.createElement("div");
    iframe.contentWindow.document.body.appendChild(div);
    // wait until the frame is ready
    var timerId = setInterval(function() {
        if ( jQuery( "#advads-cache-busting-test" ).contents().find( "div" ).length ) {
            clearInterval(timerId);

            var search_str = 'cache_busting_test',
            test_div = jQuery( "#advads-cache-busting-test" ).contents().find( "div" ),
            error = false;
            ad_content += search_str;

            postscribe( test_div, ad_content, {
                error: function( e ){
                    advads_cb_check_set_status( false, e.msg );
                    error = true;
                },
                done: function() {
                    var result = test_div.text();
                    // check if ad content was delivered completely
                    advads_cb_check_set_status( ( ! error && result.substr( - search_str.length ) === search_str ) ? true : false );
                },
            });

        }
    }, 1000);
}


