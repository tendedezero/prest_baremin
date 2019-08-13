
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
 } else {
    if (getCookie("VATMODE") == 'true') {
        $("#NDCToggle").removeClass( "toggle--off" );
        $(".inc-vat").show();
        $(".ex-vat").hide();
        setCookie("VATMODE", "true", 365);
    }else{
        $("#NDCToggle").addClass("toggle--off");
        $(".inc-vat").hide();
        $(".ex-vat").show();
        setCookie("VATMODE", "false", 365);
    }

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
$("#leasecalculate").click(function(){populateLeaseFields(parseFloat($("#leaseamount").val())),$(".leasetable.leasepagetable").length>0&&($(".leasetable.leasepagetable").css({display:"table"}),$(".leaseextrainfo").css({display:"block"}))})
$("#leasecalculate").click();
});

function populateLeaseFields(e){if(e<10000){for(var t=[{term:24,rate:.05031,type:"over"},{term:36,rate:.03519,type:"over"},{term:48,rate:.02868,type:"over"},{term:60,rate:.02438,type:"over"},{term:36,rate:.041,type:"under"},{term:60,rate:.029,type:"under"}],i=0;i<t.length;i++){var n=t[i],s=e*n.rate,a=s*n.term*.22,o=$("#m"+n.term+"y"+n.type);o.children(":eq(1)").children(".cellvalue").html("&pound;"+currencyPad(s)),o.children(":eq(2)").children(".cellvalue").html("&pound;"+currencyPad(12*s/52)),60==n.term&&"over"==n.type&&$("#cheapestlease").html(currencyPad(12*s/52)),o.children(":eq(3)").children(".cellvalue").html("&pound;"+currencyPad(s*n.term)),o.children(":eq(4)").children(".cellvalue").html("&pound;"+currencyPad(a)),o.children(":eq(5)").children(".cellvalue").html("&pound;"+currencyPad(s*n.term-a))}}
else{for(var t=[{term:24,rate:.050,type:"over"},{term:36,rate:.03390,type:"over"},{term:48,rate:.02715,type:"over"},{term:60,rate:.02284,type:"over"},{term:36,rate:.041,type:"under"},{term:60,rate:.029,type:"under"}],i=0;i<t.length;i++){var n=t[i],s=e*n.rate,a=s*n.term*.22,o=$("#m"+n.term+"y"+n.type);o.children(":eq(1)").children(".cellvalue").html("&pound;"+currencyPad(s)),o.children(":eq(2)").children(".cellvalue").html("&pound;"+currencyPad(12*s/52)),60==n.term&&"over"==n.type&&$("#cheapestlease").html(currencyPad(12*s/52)),o.children(":eq(3)").children(".cellvalue").html("&pound;"+currencyPad(s*n.term)),o.children(":eq(4)").children(".cellvalue").html("&pound;"+currencyPad(a)),o.children(":eq(5)").children(".cellvalue").html("&pound;"+currencyPad(s*n.term-a))}}}
function currencyPad(e,t){var i=String(e);return i.indexOf(".")<0?i+=".00":(i=String(Math.round(100*e)/100),i.indexOf(".")<0?i+=".00":i.indexOf(".")==i.length-2&&(i+="0")),t?i:i.replace(/\B(?=(\d{3})+(?!\d))/g,",")}
$(function() {
    $('.lazy').lazy();
});
$("div.ets_mm_megamenu").on("mouseover", function() {
    $('.lazymenu').lazy();;
})