/*
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

$(document).ready(function() {
	initfb(document, 'script', 'facebook-jssdk');
});

function initfb(d, s, id)
{
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) 
		return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.6";

	fjs.parentNode.insertBefore(js, fjs);
}


$(function(){

	$('.likebox_tab').click(function() {
  
    if($(this).css("margin-right") == "280px")
    {
        $('#likebox_content').animate({"margin-right": '-=280'});
        $('.likebox_tab').animate({"margin-right": '-=280'});
    }
    else
    {
        $('#likebox_content').animate({"margin-right": '+=280'});
        $('.likebox_tab').animate({"margin-right": '+=280'});
    }

	});

});