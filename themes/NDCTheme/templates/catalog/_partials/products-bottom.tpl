{*
 * Classic theme doesn't use this subtemplate, feel free to do whatever you need here.
 * This template is generated at each ajax calls.
 * See ProductListingFrontController::getAjaxProductSearchVariables()
 *}
<div id="js-product-list-bottom">
   
</div>

<script>
$(document).ready(function() {
    const observer = lozad('.lazy', {
        rootMargin: '10px 0px', // syntax similar to that of CSS Margin
        threshold: 0.1 // ratio of element convergence
    });
    observer.observe();
    $(window).scrollTop(0);
    if ($('#_desktop_search_filters_clear_all').length) {
        $('#subcategories').fadeOut();
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

});
</script>
