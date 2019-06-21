function setCookie(name, value, options = {}) {

    options = {
        path: '/',
    };


    let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);
    for (let optionKey in options) {
        updatedCookie += "; " + optionKey;
        let optionValue = options[optionKey];
        if (optionValue !== true) {
            updatedCookie += "=" + optionValue;
        }
    }
    document.cookie = updatedCookie;
}

function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
function setVATPref() {
    if ($("#NDCToggle").hasClass("toggle--off")) {
        $("#NDCToggle").removeClass( "toggle--off" );
        $(".inc-vat").show();
        $(".ex-vat").hide();
	setCookie("VATMODE", "true", 365);
    }
    else
    {
        $("#NDCToggle").addClass("toggle--off");
        $(".inc-vat").hide();
        $(".ex-vat").show();
setCookie("VATMODE", "false", 365);
    }
}

if (typeof getCookie("VATMODE") == 'undefined') {
	$(".inc-vat").show();
        $(".ex-vat").hide();
	setCookie("VATMODE", "true", 365);
 }

/* PRODUCT-LIST LIST */
$(document).ready(function(){
    $('.show_list').click(function(){
        document.cookie = "show_list=true; expires=Thu, 30 Jan 2100 12:00:00 UTC; path=/";
        document.cookie = "show_grid=; expires=Thu, 30 Jan 1970 12:00:00 UTC; path=/";
        $('section#products').addClass('product_show_list');
    });

    $('.show_grid').click(function(){
        document.cookie = "show_list=; expires=Thu, 30 Jan 1970 12:00:00 UTC; path=/";
        document.cookie = "show_grid=true; expires=Thu, 30 Jan 2100 12:00:00 UTC; path=/";
        $('section#products').removeClass('product_show_list');
    });

});


