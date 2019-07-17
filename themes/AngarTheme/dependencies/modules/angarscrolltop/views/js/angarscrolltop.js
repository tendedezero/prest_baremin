/*
* PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
*
* @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
* @copyright 2010-2016 VEKIA
* @license   This program is not free software and you can't resell and redistribute it
*
* CONTACT WITH DEVELOPER http://mypresta.eu
* support@mypresta.eu
*/

$(document).ready(function(){
    $(window).scroll(function(){
        if($(this).scrollTop() > 100){
            $('#scroll_top').fadeIn();
        }else{
            $('#scroll_top').fadeOut();
        }
    });
    $('#scroll_top').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });
});