/* AngarTheme js */

/* CATEGORIES BLOCK */
$(document).ready(function(){
	$('ul.tree.dhtml').hide();

	//to do not execute this script as much as it's called...
	if(!$('ul.tree.dhtml').hasClass('dynamized'))
	{
		//add growers to each ul.tree elements
		$('ul.tree.dhtml ul').prev().before("<span class='grower OPEN'> </span>");
		
		//dynamically add the '.last' class on each last item of a branch
		$('ul.tree.dhtml ul li:last-child, ul.tree.dhtml li:last-child').addClass('last');
		
		//collapse every expanded branch
		$('ul.tree.dhtml span.grower.OPEN').addClass('CLOSE').removeClass('OPEN').parent().find('ul:first').hide();
		$('ul.tree.dhtml').show();
		
		//open the tree for the selected branch
			$('ul.tree.dhtml .selected').parents().each( function() {
				if ($(this).is('ul'))
					toggleBranch($(this).prev().prev(), true);
			});
			toggleBranch( $('ul.tree.dhtml .selected').prev(), true);
		
		//add a fonction on clicks on growers
		$('ul.tree.dhtml span.grower').click(function(){
			toggleBranch($(this));
		});
		//mark this 'ul.tree' elements as already 'dynamized'
		$('ul.tree.dhtml').addClass('dynamized');

		$('ul.tree.dhtml').removeClass('dhtml');
	}
});

//animate the opening of the branch (span.grower jQueryElement)
function openBranch(jQueryElement, noAnimation)
{
		jQueryElement.addClass('OPEN').removeClass('CLOSE');
		if(noAnimation)
			jQueryElement.parent().find('ul:first').show();
		else
			jQueryElement.parent().find('ul:first').slideDown();
}
//animate the closing of the branch (span.grower jQueryElement)
function closeBranch(jQueryElement, noAnimation)
{
	jQueryElement.addClass('CLOSE').removeClass('OPEN');
	if(noAnimation)
		jQueryElement.parent().find('ul:first').hide();
	else
		jQueryElement.parent().find('ul:first').slideUp();
}

//animate the closing or opening of the branch (ul jQueryElement)
function toggleBranch(jQueryElement, noAnimation)
{
	if(jQueryElement.hasClass('OPEN'))
		closeBranch(jQueryElement, noAnimation);
	else
		openBranch(jQueryElement, noAnimation);
}


/* HOME TABS */
$(document).ready(function(){
    $('#home-page-tabs li:first a, #index .tab-content .tab-pane:first').addClass('active in');
});


/* PRODUCT */
/* 1 column layout */
$('#content-wrapper.col-sm-12 .bx_accessories').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 297,
	pager: false,
	nextSelector: '#next_accessories',
	prevSelector: '#prev_accessories',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

$('#content-wrapper.col-sm-12 .bx_prodcat').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 297,
	pager: false,
	nextSelector: '#next_prodcat',
	prevSelector: '#prev_prodcat',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

$('#content-wrapper.col-sm-12 .bx_cross').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 297,
	pager: false,
	nextSelector: '#next_cross',
	prevSelector: '#prev_cross',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

/* 2 column layout - 4 per row */
$('.standard_carusele #content-wrapper.col-md-9 .bx_accessories').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 222,
	pager: false,
	nextSelector: '#next_accessories',
	prevSelector: '#prev_accessories',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

$('.standard_carusele #content-wrapper.col-md-9 .bx_prodcat').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 222,
	pager: false,
	nextSelector: '#next_prodcat',
	prevSelector: '#prev_prodcat',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

$('.standard_carusele #content-wrapper.col-md-9 .bx_cross').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 222,
	pager: false,
	nextSelector: '#next_cross',
	prevSelector: '#prev_cross',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

/* 2 column layout - 3 per row */
$('.pl_2col_qty_3 #content-wrapper.col-md-9 .bx_accessories').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 296,
	pager: false,
	nextSelector: '#next_accessories',
	prevSelector: '#prev_accessories',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

$('.pl_2col_qty_3 #content-wrapper.col-md-9 .bx_prodcat').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 296,
	pager: false,
	nextSelector: '#next_prodcat',
	prevSelector: '#prev_prodcat',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

$('.pl_2col_qty_3 #content-wrapper.col-md-9 .bx_cross').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 296,
	pager: false,
	nextSelector: '#next_cross',
	prevSelector: '#prev_cross',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

/* 3 column layout */
$('#content-wrapper.col-md-6 .bx_accessories').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 197,
	pager: false,
	nextSelector: '#next_accessories',
	prevSelector: '#prev_accessories',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

$('#content-wrapper.col-md-6 .bx_prodcat').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 197,
	pager: false,
	nextSelector: '#next_prodcat',
	prevSelector: '#prev_prodcat',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});

$('#content-wrapper.col-md-6 .bx_cross').bxSlider({
	minSlides: 1,
	maxSlides: 6,
	slideWidth: 197,
	pager: false,
	nextSelector: '#next_cross',
	prevSelector: '#prev_cross',
	nextText: '>',
	prevText: '<',
	moveSlides:1,
	infiniteLoop:false,
	hideControlOnEnd: true
});


/* SCROLL TO COMMENTS */
$(document).on('click', '.comments_advices .reviews', function(e) {
	if ($('.comment_tab').length) {
		$('.comment_tab').trigger('click');
		$('html, body').animate({
			scrollTop: $('.comment_tab').offset().top
		}, 500)
	}
	return !1
});

/* Scroll to comments */
$(document).on('click', '.product_tabs1 .comments_advices .reviews', function(e){
	$('.product_tabs1 .tabs ul.nav-tabs .comment_tab').click();
	$('html, body').animate({
		scrollTop: $('.product_tabs1 .comment_tab').offset().top
	}, 500);
});

$(document).on('click', '.product_tabs2 .comments_advices .reviews', function(e){
	$('html, body').animate({
		scrollTop: $('.product_tabs2 #productCommentsBlock .index_title').offset().top
	}, 500);
});


/* RWD */
var responsiveflag = false;

$(document).ready(function(){
	responsiveResize();
	$(window).resize(responsiveResize);
	if (navigator.userAgent.match(/Android/i))
	{
		var viewport = document.querySelector('meta[name="viewport"]');
		viewport.setAttribute('content', 'initial-scale=1.0,maximum-scale=1.0,user-scalable=0,width=device-width,height=device-height');
		window.scrollTo(0, 1);
	}
});


// Used to compensante Chrome/Safari bug (they don't care about scroll bar for width)
function scrollCompensate()
{
	var inner = document.createElement('p');
	inner.style.width = "100%";
	inner.style.height = "200px";

	var outer = document.createElement('div');
	outer.style.position = "absolute";
	outer.style.top = "0px";
	outer.style.left = "0px";
	outer.style.visibility = "hidden";
	outer.style.width = "200px";
	outer.style.height = "150px";
	outer.style.overflow = "hidden";
	outer.appendChild(inner);

	document.body.appendChild(outer);
	var w1 = inner.offsetWidth;
	outer.style.overflow = 'scroll';
	var w2 = inner.offsetWidth;
	if (w1 == w2) w2 = outer.clientWidth;

	document.body.removeChild(outer);

	return (w1 - w2);
}

function responsiveResize()
{
	compensante = scrollCompensate();
	if (($(window).width()+scrollCompensate()) <= 767 && responsiveflag == false)
	{
		accordion('enable');
		accordionFooter('enable');
		responsiveflag = true;
	}
	else if (($(window).width()+scrollCompensate()) >= 768)
	{
		accordion('disable');
		accordionFooter('disable');
		responsiveflag = false;
		if (typeof bindUniform !=='undefined')
			bindUniform();
	}
}

function accordionFooter(status)
{
	if(status == 'enable')
	{
		$('#footer .footer-block h4').on('click', function(e){
			$(this).toggleClass('active').parent().find('.toggle-footer').stop().slideToggle('medium');
			e.preventDefault();
		})
		$('#footer').addClass('accordion').find('.toggle-footer').slideUp('fast');
	}
	else
	{
		$('.footer-block h4').removeClass('active').off().parent().find('.toggle-footer').removeAttr('style').slideDown('fast');
		$('#footer').removeClass('accordion');
	}
}

function accordion(status)
{
	if(status == 'enable')
	{
		var accordion_selector = '.columns .h6.text-uppercase';

		$(accordion_selector).on('click', function(e){
			$(this).toggleClass('active').parent().find('.block_content').stop().slideToggle('fast');
		});
		$('.columns').addClass('accordion').find('.block_content').slideUp('fast');
		if (typeof(ajaxCart) !== 'undefined')
			ajaxCart.collapse();
	}
	else
	{
		$('.columns .h6.text-uppercase').removeClass('active').off().parent().find('.block_content').removeAttr('style').slideDown('fast');
		$('.columns').removeClass('accordion');
	}
}


/* RWD menu */
$('#menu-icon2').on('click', function() {
	$('#mobile_top_menu_wrapper2').slideToggle('fast');
	$('#mobile_search_wrapper').hide();
	$('#mobile_user_wrapper').hide();
});

$('#search-icon').on('click', function() {
	$('#mobile_search_wrapper').slideToggle('fast');
	$('#mobile_top_menu_wrapper2').hide();
	$('#mobile_user_wrapper').hide();
});

$('#user-icon').on('click', function() {
	$('#mobile_user_wrapper').slideToggle('fast');
	$('#mobile_top_menu_wrapper2').hide();
	$('#mobile_search_wrapper').hide();
});


/* Sticky menu */
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 135) {
            $('.stickymenu_yes').addClass("sticky_menu");
        } else {
            $('.stickymenu_yes').removeClass("sticky_menu");
        }
    });
});

/* Sticky menu rwd */
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 90) {
            $('.stickymenu_yes').addClass("sticky_menu_rwd");
        } else {
            $('.stickymenu_yes').removeClass("sticky_menu_rwd");
        }
    });
});

/* Sticky cart */
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 136) {
            $('.stickycart_yes').addClass("sticky_cart2");
        } else {
            $('.stickycart_yes').removeClass("sticky_cart2");
        }
    });
});

$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 140) {
            $('.stickycart_yes').addClass("sticky_cart");
        } else {
            $('.stickycart_yes').removeClass("sticky_cart");
        }
    });
});


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

