/*
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

$(document).ready(function(){


	// Theme layout edit start
	$('#theme_customization_icon').click(function() {
  
    if($(this).css("margin-left") == "320px")
    {
        $('#theme_customization').animate({"margin-left": '-=320'});
        $('#theme_customization_icon').animate({"margin-left": '-=320'});
    }
    else
    {
        $('#theme_customization').animate({"margin-left": '+=320'});
        $('#theme_customization_icon').animate({"margin-left": '+=320'});
    }

	});

	
	$('.bg_select').on('click',function(){
	   $('.bg_select').removeClass('bg_current');
		$(this).addClass('bg_current');
	});
	// Theme layout edit end
	

	// Theme color edit start
	$('#theme_color_icon').click(function() {
  
    if($(this).css("margin-left") == "320px")
    {
        $('#theme_color').animate({"margin-left": '-=320'});
        $('#theme_color_icon').animate({"margin-left": '-=320'});
    }
    else
    {
        $('#theme_color').animate({"margin-left": '+=320'});
        $('#theme_color_icon').animate({"margin-left": '+=320'});
    }

	});
	// Theme color edit end




	// Live edit start










	// *** BACKGROUND ***

	// BG color
	$('#background_color').minicolors({
		change: function(hex, rgb) {         
			$('body').css('backgroundColor', hex);
			$('#background_color').attr('value', hex);
		}
	});
	// BG color end

	// Bg select
	var removeBg = 'custom_pattern color_only no_bg    bg1 bg2 bg3 bg4 bg5 bg6 bg7 bg8 bg9 bg10 bg11 bg12 bg13 bg14 bg15 bg16 bg17 bg18 bg19 bg20 bg21 bg22 bg23 bg24 bg25 bg26 bg27 bg28 bg29 bg30 bg31 bg32 bg33 bg34 bg35 bg36 bg37 bg38 bg39 bg40 bg41 bg42 bg43 bg44 bg45     custom_texture texture01 texture02 texture03 texture04 texture05 texture06 texture07 texture08 texture09 texture10 texture11 texture12 texture13 texture14 texture15 texture16 texture17 texture18 texture19 texture20 texture21 texture22 texture23';
	
	$('#theme_customization .custom_pattern').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('custom_pattern'); });
	$('#theme_customization .color_only').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('color_only'); });
	$('#theme_customization .no_bg').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('no_bg'); });


	$('#theme_customization .bg1').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg1'); });
	$('#theme_customization .bg2').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg2'); });
	$('#theme_customization .bg3').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg3'); });
	$('#theme_customization .bg4').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg4'); });
	$('#theme_customization .bg5').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg5'); });
	$('#theme_customization .bg6').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg6'); });
	$('#theme_customization .bg7').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg7'); });
	$('#theme_customization .bg8').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg8'); });
	$('#theme_customization .bg9').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg9'); });
	$('#theme_customization .bg10').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg10'); });
	$('#theme_customization .bg11').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg11'); });
	$('#theme_customization .bg12').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg12'); });
	$('#theme_customization .bg13').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg13'); });
	$('#theme_customization .bg14').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg14'); });
	$('#theme_customization .bg15').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg15'); });
	$('#theme_customization .bg16').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg16'); });
	$('#theme_customization .bg17').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg17'); });
	$('#theme_customization .bg18').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg18'); });
	$('#theme_customization .bg19').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg19'); });
	$('#theme_customization .bg20').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg20'); });
	$('#theme_customization .bg21').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg21'); });
	$('#theme_customization .bg22').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg22'); });
	$('#theme_customization .bg23').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg23'); });
	$('#theme_customization .bg24').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg24'); });
	$('#theme_customization .bg25').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg25'); });
	$('#theme_customization .bg26').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg26'); });
	$('#theme_customization .bg27').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg27'); });
	$('#theme_customization .bg28').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg28'); });
	$('#theme_customization .bg29').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg29'); });
	$('#theme_customization .bg30').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg30'); });
	$('#theme_customization .bg31').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg31'); });
	$('#theme_customization .bg32').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg32'); });
	$('#theme_customization .bg33').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg33'); });
	$('#theme_customization .bg34').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg34'); });
	$('#theme_customization .bg35').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg35'); });
	$('#theme_customization .bg36').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg36'); });
	$('#theme_customization .bg37').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg37'); });
	$('#theme_customization .bg38').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg38'); });
	$('#theme_customization .bg39').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg39'); });
	$('#theme_customization .bg40').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('bg40'); });


	$('#theme_customization .custom_texture').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('custom_texture'); });
	$('#theme_customization .texture01').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture01'); });
	$('#theme_customization .texture02').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture02'); });
	$('#theme_customization .texture03').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture03'); });
	$('#theme_customization .texture04').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture04'); });
	$('#theme_customization .texture05').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture05'); });
	$('#theme_customization .texture06').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture06'); });
	$('#theme_customization .texture07').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture07'); });
	$('#theme_customization .texture08').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture08'); });
	$('#theme_customization .texture09').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture09'); });
	$('#theme_customization .texture10').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture10'); });
	$('#theme_customization .texture11').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture11'); });
	$('#theme_customization .texture12').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture12'); });
	$('#theme_customization .texture13').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture13'); });
	$('#theme_customization .texture14').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture14'); });
	$('#theme_customization .texture15').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture15'); });
	$('#theme_customization .texture16').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture16'); });
	$('#theme_customization .texture17').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture17'); });
	$('#theme_customization .texture18').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture18'); });
	$('#theme_customization .texture19').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture19'); });
	$('#theme_customization .texture20').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture20'); });
	$('#theme_customization .texture21').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture21'); });
	$('#theme_customization .texture22').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture22'); });
	$('#theme_customization .texture23').on('click',function(){ $('body').removeClass(removeBg); $('body').addClass('texture23'); });

	// Bg select end

	// Background position
	var removeBgPosition = 'bg_position_tl bg_position_tc bg_position_tr bg_position_cl bg_position_cc bg_position_cr bg_position_bl bg_position_bc bg_position_br';
	$('#theme_customization .bg_position_tl').on('click',function(){ $('body').removeClass(removeBgPosition); $('body').addClass('bg_position_tl'); });
	$('#theme_customization .bg_position_tc').on('click',function(){ $('body').removeClass(removeBgPosition); $('body').addClass('bg_position_tc'); });
	$('#theme_customization .bg_position_tr').on('click',function(){ $('body').removeClass(removeBgPosition); $('body').addClass('bg_position_tr'); });
	$('#theme_customization .bg_position_cl').on('click',function(){ $('body').removeClass(removeBgPosition); $('body').addClass('bg_position_cl'); });
	$('#theme_customization .bg_position_cc').on('click',function(){ $('body').removeClass(removeBgPosition); $('body').addClass('bg_position_cc'); });
	$('#theme_customization .bg_position_cr').on('click',function(){ $('body').removeClass(removeBgPosition); $('body').addClass('bg_position_cr'); });
	$('#theme_customization .bg_position_bl').on('click',function(){ $('body').removeClass(removeBgPosition); $('body').addClass('bg_position_bl'); });
	$('#theme_customization .bg_position_bc').on('click',function(){ $('body').removeClass(removeBgPosition); $('body').addClass('bg_position_bc'); });
	$('#theme_customization .bg_position_br').on('click',function(){ $('body').removeClass(removeBgPosition); $('body').addClass('bg_position_br'); });
	// Background position end

	// Background attachment
	var removeBgAttachment = 'bg_attatchment_normal bg_attatchment_fixed';
	$('#theme_customization .bg_attatchment_normal').on('click',function(){ $('body').removeClass(removeBgAttachment); $('body').addClass('bg_attatchment_normal'); });
	$('#theme_customization .bg_attatchment_fixed').on('click',function(){ $('body').removeClass(removeBgAttachment); $('body').addClass('bg_attatchment_fixed'); });
	// Background attachment end

	// Background repeat
	var removeBgRepeat = 'bg_repeat_no bg_repeat_x bg_repeat_y bg_repeat_xy';
	$('#theme_customization .bg_repeat_no').on('click',function(){ $('body').removeClass(removeBgRepeat); $('body').addClass('bg_repeat_no'); });
	$('#theme_customization .bg_repeat_x').on('click',function(){ $('body').removeClass(removeBgRepeat); $('body').addClass('bg_repeat_x'); });
	$('#theme_customization .bg_repeat_y').on('click',function(){ $('body').removeClass(removeBgRepeat); $('body').addClass('bg_repeat_y'); });
	$('#theme_customization .bg_repeat_xy').on('click',function(){ $('body').removeClass(removeBgRepeat); $('body').addClass('bg_repeat_xy'); });
	// Background repeat end

	// Background size
	var removeBgSize = 'bg_size_initial bg_size_cover';
	$('#theme_customization .bg_size_initial').on('click',function(){ $('body').removeClass(removeBgSize); $('body').addClass('bg_size_initial'); });
	$('#theme_customization .bg_size_cover').on('click',function(){ $('body').removeClass(removeBgSize); $('body').addClass('bg_size_cover'); });
	// Background size end










	// *** TOP ***

	// Header separators
	var removeHeaderSep = 'header_sep0 header_sep1 header_sep2';
	$('#theme_customization .header_sep0').on('click',function(){ $('body').removeClass(removeHeaderSep); $('body').addClass('header_sep0'); });
	$('#theme_customization .header_sep1').on('click',function(){ $('body').removeClass(removeHeaderSep); $('body').addClass('header_sep1'); });
	$('#theme_customization .header_sep2').on('click',function(){ $('body').removeClass(removeHeaderSep); $('body').addClass('header_sep2'); });
	// Header separators








	// *** HEADER ***

	// Header style
	var removeHeaderStyle = 'header_style1 header_style2';
	$('#theme_customization .header_style1').on('click',function(){ $('body').removeClass(removeHeaderStyle); $('body').addClass('header_style1'); });
	$('#theme_customization .header_style2').on('click',function(){ $('body').removeClass(removeHeaderStyle); $('body').addClass('header_style2'); });
	// Header style







	// *** CART ***

	// Cart style
	var removeCartStyle = 'cart_style1 cart_style2';
	$('#theme_customization .cart_style1').on('click',function(){ $('body').removeClass(removeCartStyle); $('body').addClass('cart_style1'); });
	$('#theme_customization .cart_style2').on('click',function(){ $('body').removeClass(removeCartStyle); $('body').addClass('cart_style2'); });
	// Cart style

	// Sticky cart
	var removeStickyCart = 'stickycart_yes stickycart_no';
	$('#theme_customization .stickycart_yes').on('click',function(){ $('body').removeClass(removeStickyCart); $('body').addClass('stickycart_yes'); });
	$('#theme_customization .stickycart_no').on('click',function(){ $('body').removeClass(removeStickyCart); $('body').addClass('stickycart_no'); });
	// Sticky cart end








	// *** MENU ***

	// Menu separators
	var removeMenuSep = 'menu_sep0 menu_sep1 menu_sep2 menu_sep3 menu_sep4 menu_sep5 menu_sep6';
	$('#theme_customization .menu_sep0').on('click',function(){ $('body').removeClass(removeMenuSep); $('body').addClass('menu_sep0'); });
	$('#theme_customization .menu_sep1').on('click',function(){ $('body').removeClass(removeMenuSep); $('body').addClass('menu_sep1'); });
	$('#theme_customization .menu_sep2').on('click',function(){ $('body').removeClass(removeMenuSep); $('body').addClass('menu_sep2'); });
	$('#theme_customization .menu_sep3').on('click',function(){ $('body').removeClass(removeMenuSep); $('body').addClass('menu_sep3'); });
	$('#theme_customization .menu_sep4').on('click',function(){ $('body').removeClass(removeMenuSep); $('body').addClass('menu_sep4'); });
	$('#theme_customization .menu_sep5').on('click',function(){ $('body').removeClass(removeMenuSep); $('body').addClass('menu_sep5'); });
	$('#theme_customization .menu_sep6').on('click',function(){ $('body').removeClass(removeMenuSep); $('body').addClass('menu_sep6'); });
	// Menu separators

	// Submenu
	var removeSubmenuType = 'submenu1 submenu2';
	$('#theme_customization .submenu1').on('click',function(){ $('body').removeClass(removeSubmenuType); $('body').addClass('submenu1'); });
	$('#theme_customization .submenu2').on('click',function(){ $('body').removeClass(removeSubmenuType); $('body').addClass('submenu2'); });
	// Submenu end

	// Sticky menu
	var removeStickyMenu = 'stickymenu_yes stickymenu_no';
	$('#theme_customization .stickymenu_yes').on('click',function(){ $('body').removeClass(removeStickyMenu); $('body').addClass('stickymenu_yes'); });
	$('#theme_customization .stickymenu_no').on('click',function(){ $('body').removeClass(removeStickyMenu); $('body').addClass('stickymenu_no'); });
	// Sticky menu end

	// Menu home icon
	var removeHomeIcon = 'homeicon_yes homeicon_no';
	$('#theme_customization .homeicon_yes').on('click',function(){ $('body').removeClass(removeHomeIcon); $('body').addClass('homeicon_yes'); });
	$('#theme_customization .homeicon_no').on('click',function(){ $('body').removeClass(removeHomeIcon); $('body').addClass('homeicon_no'); });
	// Menu home icon end








	// *** SLIDER ***

	// Slider boxed
	var removeSliderBoxed = 'slider_boxed slider_full_width';
	$('#theme_customization .slider_boxed').on('click',function(){ $('body').removeClass(removeSliderBoxed); $('body').addClass('slider_boxed'); });
	$('#theme_customization .slider_full_width').on('click',function(){ $('body').removeClass(removeSliderBoxed); $('body').addClass('slider_full_width'); });
	// Slider boxed end

	// Slider controls color
	var removeSliderControls = 'slider_controls_white slider_controls_black';
	$('#theme_customization .slider_controls_white').on('click',function(){ $('body').removeClass(removeSliderControls); $('body').addClass('slider_controls_white'); });
	$('#theme_customization .slider_controls_black').on('click',function(){ $('body').removeClass(removeSliderControls); $('body').addClass('slider_controls_black'); });
	// Slider controls color end










	
	// *** BANNERS TOP ***
	
	// Banners top computers number
	var removeBanners_top = 'banners_top1 banners_top2 banners_top3 banners_top4 banners_top5';
	$('#theme_customization .banners_top1').on('click',function(){ $('body').removeClass(removeBanners_top); $('body').addClass('banners_top1'); });
	$('#theme_customization .banners_top2').on('click',function(){ $('body').removeClass(removeBanners_top); $('body').addClass('banners_top2'); });
	$('#theme_customization .banners_top3').on('click',function(){ $('body').removeClass(removeBanners_top); $('body').addClass('banners_top3'); });
	$('#theme_customization .banners_top4').on('click',function(){ $('body').removeClass(removeBanners_top); $('body').addClass('banners_top4'); });
	$('#theme_customization .banners_top5').on('click',function(){ $('body').removeClass(removeBanners_top); $('body').addClass('banners_top5'); });
	//  Banners top computers number end	

	// Banners top tablets number
	var removeBanners_top_tablets = 'banners_top_tablets0 banners_top_tablets1 banners_top_tablets2 banners_top_tablets3 banners_top_tablets4 banners_top_tablets5';
	$('#theme_customization .banners_top_tablets0').on('click',function(){ $('body').removeClass(removeBanners_top_tablets); $('body').addClass('banners_top_tablets0'); });
	$('#theme_customization .banners_top_tablets1').on('click',function(){ $('body').removeClass(removeBanners_top_tablets); $('body').addClass('banners_top_tablets1'); });
	$('#theme_customization .banners_top_tablets2').on('click',function(){ $('body').removeClass(removeBanners_top_tablets); $('body').addClass('banners_top_tablets2'); });
	$('#theme_customization .banners_top_tablets3').on('click',function(){ $('body').removeClass(removeBanners_top_tablets); $('body').addClass('banners_top_tablets3'); });
	$('#theme_customization .banners_top_tablets4').on('click',function(){ $('body').removeClass(removeBanners_top_tablets); $('body').addClass('banners_top_tablets4'); });
	$('#theme_customization .banners_top_tablets5').on('click',function(){ $('body').removeClass(removeBanners_top_tablets); $('body').addClass('banners_top_tablets5'); });
	//  Banners top tablets number end

	// Banners top phones number
	var removeBanners_top_phones = 'banners_top_phones0 banners_top_phones1 banners_top_phones2 banners_top_phones3 banners_top_phones4 banners_top_phones5';
	$('#theme_customization .banners_top_phones0').on('click',function(){ $('body').removeClass(removeBanners_top_phones); $('body').addClass('banners_top_phones0'); });
	$('#theme_customization .banners_top_phones1').on('click',function(){ $('body').removeClass(removeBanners_top_phones); $('body').addClass('banners_top_phones1'); });
	$('#theme_customization .banners_top_phones2').on('click',function(){ $('body').removeClass(removeBanners_top_phones); $('body').addClass('banners_top_phones2'); });
	$('#theme_customization .banners_top_phones3').on('click',function(){ $('body').removeClass(removeBanners_top_phones); $('body').addClass('banners_top_phones3'); });
	$('#theme_customization .banners_top_phones4').on('click',function(){ $('body').removeClass(removeBanners_top_phones); $('body').addClass('banners_top_phones4'); });
	$('#theme_customization .banners_top_phones5').on('click',function(){ $('body').removeClass(removeBanners_top_phones); $('body').addClass('banners_top_phones5'); });
	//  Banners top phones number end










	// *** BANNERS BOTTOM ***

	// Banners bottom computers number
	var removeBanners_bottom = 'banners_bottom1 banners_bottom2 banners_bottom3 banners_bottom4 banners_bottom5';
	$('#theme_customization .banners_bottom1').on('click',function(){ $('body').removeClass(removeBanners_bottom); $('body').addClass('banners_bottom1'); });
	$('#theme_customization .banners_bottom2').on('click',function(){ $('body').removeClass(removeBanners_bottom); $('body').addClass('banners_bottom2'); });
	$('#theme_customization .banners_bottom3').on('click',function(){ $('body').removeClass(removeBanners_bottom); $('body').addClass('banners_bottom3'); });
	$('#theme_customization .banners_bottom4').on('click',function(){ $('body').removeClass(removeBanners_bottom); $('body').addClass('banners_bottom4'); });
	$('#theme_customization .banners_bottom5').on('click',function(){ $('body').removeClass(removeBanners_bottom); $('body').addClass('banners_bottom5'); });
	//  Banners bottom computers number end

	// Banners bottom tablets number
	var removeBanners_bottom_tablets = 'banners_bottom_tablets0 banners_bottom_tablets1 banners_bottom_tablets2 banners_bottom_tablets3 banners_bottom_tablets4 banners_bottom_tablets5';
	$('#theme_customization .banners_bottom_tablets0').on('click',function(){ $('body').removeClass(removeBanners_bottom_tablets); $('body').addClass('banners_bottom_tablets0'); });
	$('#theme_customization .banners_bottom_tablets1').on('click',function(){ $('body').removeClass(removeBanners_bottom_tablets); $('body').addClass('banners_bottom_tablets1'); });
	$('#theme_customization .banners_bottom_tablets2').on('click',function(){ $('body').removeClass(removeBanners_bottom_tablets); $('body').addClass('banners_bottom_tablets2'); });
	$('#theme_customization .banners_bottom_tablets3').on('click',function(){ $('body').removeClass(removeBanners_bottom_tablets); $('body').addClass('banners_bottom_tablets3'); });
	$('#theme_customization .banners_bottom_tablets4').on('click',function(){ $('body').removeClass(removeBanners_bottom_tablets); $('body').addClass('banners_bottom_tablets4'); });
	$('#theme_customization .banners_bottom_tablets5').on('click',function(){ $('body').removeClass(removeBanners_bottom_tablets); $('body').addClass('banners_bottom_tablets5'); });
	//  Banners bottom tablets number end

	// Banners bottom phones number
	var removeBanners_bottom_phones = 'banners_bottom_phones0 banners_bottom_phones1 banners_bottom_phones2 banners_bottom_phones3 banners_bottom_phones4 banners_bottom_phones5';
	$('#theme_customization .banners_bottom_phones0').on('click',function(){ $('body').removeClass(removeBanners_bottom_phones); $('body').addClass('banners_bottom_phones0'); });
	$('#theme_customization .banners_bottom_phones1').on('click',function(){ $('body').removeClass(removeBanners_bottom_phones); $('body').addClass('banners_bottom_phones1'); });
	$('#theme_customization .banners_bottom_phones2').on('click',function(){ $('body').removeClass(removeBanners_bottom_phones); $('body').addClass('banners_bottom_phones2'); });
	$('#theme_customization .banners_bottom_phones3').on('click',function(){ $('body').removeClass(removeBanners_bottom_phones); $('body').addClass('banners_bottom_phones3'); });
	$('#theme_customization .banners_bottom_phones4').on('click',function(){ $('body').removeClass(removeBanners_bottom_phones); $('body').addClass('banners_bottom_phones4'); });
	$('#theme_customization .banners_bottom_phones5').on('click',function(){ $('body').removeClass(removeBanners_bottom_phones); $('body').addClass('banners_bottom_phones5'); });
	//  Banners bottom phones number end









	// *** FEATURED CATEGORIES ***

	// Featured categories styles
	var removeFeat_cat_style = 'feat_cat_style1 feat_cat_style2 feat_cat_style3 feat_cat_style4 feat_cat_style5';
	$('#theme_customization .feat_cat_style1').on('click',function(){ $('body').removeClass(removeFeat_cat_style); $('body').addClass('feat_cat_style1'); });
	$('#theme_customization .feat_cat_style2').on('click',function(){ $('body').removeClass(removeFeat_cat_style); $('body').addClass('feat_cat_style2'); });
	$('#theme_customization .feat_cat_style3').on('click',function(){ $('body').removeClass(removeFeat_cat_style); $('body').addClass('feat_cat_style3'); });
	$('#theme_customization .feat_cat_style4').on('click',function(){ $('body').removeClass(removeFeat_cat_style); $('body').addClass('feat_cat_style4'); });
	//  Featured categories styles end

	// Featured categories computers number
	var removeFeat_cat = 'feat_cat2 feat_cat3 feat_cat4 feat_cat5 feat_cat6';
	$('#theme_customization .feat_cat2').on('click',function(){ $('body').removeClass(removeFeat_cat); $('body').addClass('feat_cat2'); });
	$('#theme_customization .feat_cat3').on('click',function(){ $('body').removeClass(removeFeat_cat); $('body').addClass('feat_cat3'); });
	$('#theme_customization .feat_cat4').on('click',function(){ $('body').removeClass(removeFeat_cat); $('body').addClass('feat_cat4'); });
	$('#theme_customization .feat_cat5').on('click',function(){ $('body').removeClass(removeFeat_cat); $('body').addClass('feat_cat5'); });
	$('#theme_customization .feat_cat6').on('click',function(){ $('body').removeClass(removeFeat_cat); $('body').addClass('feat_cat6'); });
	//  Featured categories computers number end

	// Featured categories bigtablets number
	var removeFeat_cat_bigtablets = 'feat_cat_bigtablets0 feat_cat_bigtablets1 feat_cat_bigtablets2 feat_cat_bigtablets3 feat_cat_bigtablets4';
	$('#theme_customization .feat_cat_bigtablets0').on('click',function(){ $('body').removeClass(removeFeat_cat_bigtablets); $('body').addClass('feat_cat_bigtablets0'); });
	$('#theme_customization .feat_cat_bigtablets1').on('click',function(){ $('body').removeClass(removeFeat_cat_bigtablets); $('body').addClass('feat_cat_bigtablets1'); });
	$('#theme_customization .feat_cat_bigtablets2').on('click',function(){ $('body').removeClass(removeFeat_cat_bigtablets); $('body').addClass('feat_cat_bigtablets2'); });
	$('#theme_customization .feat_cat_bigtablets3').on('click',function(){ $('body').removeClass(removeFeat_cat_bigtablets); $('body').addClass('feat_cat_bigtablets3'); });
	$('#theme_customization .feat_cat_bigtablets4').on('click',function(){ $('body').removeClass(removeFeat_cat_bigtablets); $('body').addClass('feat_cat_bigtablets4'); });
	//  Featured categories bigtablets number end

	// Featured categories tablets number
	var removeFeat_cat_tablets = 'feat_cat_tablets0 feat_cat_tablets1 feat_cat_tablets2 feat_cat_tablets3 feat_cat_tablets4';
	$('#theme_customization .feat_cat_tablets0').on('click',function(){ $('body').removeClass(removeFeat_cat_tablets); $('body').addClass('feat_cat_tablets0'); });
	$('#theme_customization .feat_cat_tablets1').on('click',function(){ $('body').removeClass(removeFeat_cat_tablets); $('body').addClass('feat_cat_tablets1'); });
	$('#theme_customization .feat_cat_tablets2').on('click',function(){ $('body').removeClass(removeFeat_cat_tablets); $('body').addClass('feat_cat_tablets2'); });
	$('#theme_customization .feat_cat_tablets3').on('click',function(){ $('body').removeClass(removeFeat_cat_tablets); $('body').addClass('feat_cat_tablets3'); });
	$('#theme_customization .feat_cat_tablets4').on('click',function(){ $('body').removeClass(removeFeat_cat_tablets); $('body').addClass('feat_cat_tablets4'); });
	//  Featured categories tablets number end

	// Featured categories phones number
	var removeFeat_cat_phones = 'feat_cat_phones0 feat_cat_phones1 feat_cat_phones2 feat_cat_phones3';
	$('#theme_customization .feat_cat_phones0').on('click',function(){ $('body').removeClass(removeFeat_cat_phones); $('body').addClass('feat_cat_phones0'); });
	$('#theme_customization .feat_cat_phones1').on('click',function(){ $('body').removeClass(removeFeat_cat_phones); $('body').addClass('feat_cat_phones1'); });
	$('#theme_customization .feat_cat_phones2').on('click',function(){ $('body').removeClass(removeFeat_cat_phones); $('body').addClass('feat_cat_phones2'); });
	$('#theme_customization .feat_cat_phones3').on('click',function(){ $('body').removeClass(removeFeat_cat_phones); $('body').addClass('feat_cat_phones3'); });
	//  Featured categories phones number end










	// *** PRODUCT-LIST QTY ***

	// Product-list product per row 1 column
	var removePL1col_qty = 'pl_1col_qty_2 pl_1col_qty_3 pl_1col_qty_4 pl_1col_qty_5 pl_1col_qty_6';
	$('#theme_customization .pl_1col_qty_2').on('click',function(){ $('body').removeClass(removePL1col_qty); $('body').addClass('pl_1col_qty_2'); });
	$('#theme_customization .pl_1col_qty_3').on('click',function(){ $('body').removeClass(removePL1col_qty); $('body').addClass('pl_1col_qty_3'); });
	$('#theme_customization .pl_1col_qty_4').on('click',function(){ $('body').removeClass(removePL1col_qty); $('body').addClass('pl_1col_qty_4'); });
	$('#theme_customization .pl_1col_qty_5').on('click',function(){ $('body').removeClass(removePL1col_qty); $('body').addClass('pl_1col_qty_5'); });
	$('#theme_customization .pl_1col_qty_6').on('click',function(){ $('body').removeClass(removePL1col_qty); $('body').addClass('pl_1col_qty_6'); });
	// Product-list product per row 1 column end

	// Product-list product per row 2 column
	var removePL2col_qty = 'pl_2col_qty_2 pl_2col_qty_3 pl_2col_qty_4 pl_2col_qty_5 pl_2col_qty_6';
	$('#theme_customization .pl_2col_qty_2').on('click',function(){ $('body').removeClass(removePL2col_qty); $('body').addClass('pl_2col_qty_2'); });
	$('#theme_customization .pl_2col_qty_3').on('click',function(){ $('body').removeClass(removePL2col_qty); $('body').addClass('pl_2col_qty_3'); });
	$('#theme_customization .pl_2col_qty_4').on('click',function(){ $('body').removeClass(removePL2col_qty); $('body').addClass('pl_2col_qty_4'); });
	$('#theme_customization .pl_2col_qty_5').on('click',function(){ $('body').removeClass(removePL2col_qty); $('body').addClass('pl_2col_qty_5'); });
	$('#theme_customization .pl_2col_qty_6').on('click',function(){ $('body').removeClass(removePL2col_qty); $('body').addClass('pl_2col_qty_6'); });
	// Product-list product per row 2 column end

	// Product-list product per row 3 column
	var removePL3col_qty = 'pl_3col_qty_2 pl_3col_qty_3 pl_3col_qty_4 pl_3col_qty_5 pl_3col_qty_6';
	$('#theme_customization .pl_3col_qty_2').on('click',function(){ $('body').removeClass(removePL3col_qty); $('body').addClass('pl_3col_qty_2'); });
	$('#theme_customization .pl_3col_qty_3').on('click',function(){ $('body').removeClass(removePL3col_qty); $('body').addClass('pl_3col_qty_3'); });
	$('#theme_customization .pl_3col_qty_4').on('click',function(){ $('body').removeClass(removePL3col_qty); $('body').addClass('pl_3col_qty_4'); });
	$('#theme_customization .pl_3col_qty_5').on('click',function(){ $('body').removeClass(removePL3col_qty); $('body').addClass('pl_3col_qty_5'); });
	$('#theme_customization .pl_3col_qty_6').on('click',function(){ $('body').removeClass(removePL3col_qty); $('body').addClass('pl_3col_qty_6'); });
	// Product-list product per row 3 column end


	// Product-list product per row 1 column big tablets
	var removePL1col_qty_bigtablets = 'pl_1col_qty_bigtablets_2 pl_1col_qty_bigtablets_3 pl_1col_qty_bigtablets_4 pl_1col_qty_bigtablets_5 pl_1col_qty_bigtablets_6';
	$('#theme_customization .pl_1col_qty_bigtablets_2').on('click',function(){ $('body').removeClass(removePL1col_qty_bigtablets); $('body').addClass('pl_1col_qty_bigtablets_2'); });
	$('#theme_customization .pl_1col_qty_bigtablets_3').on('click',function(){ $('body').removeClass(removePL1col_qty_bigtablets); $('body').addClass('pl_1col_qty_bigtablets_3'); });
	$('#theme_customization .pl_1col_qty_bigtablets_4').on('click',function(){ $('body').removeClass(removePL1col_qty_bigtablets); $('body').addClass('pl_1col_qty_bigtablets_4'); });
	$('#theme_customization .pl_1col_qty_bigtablets_5').on('click',function(){ $('body').removeClass(removePL1col_qty_bigtablets); $('body').addClass('pl_1col_qty_bigtablets_5'); });
	$('#theme_customization .pl_1col_qty_bigtablets_6').on('click',function(){ $('body').removeClass(removePL1col_qty_bigtablets); $('body').addClass('pl_1col_qty_bigtablets_6'); });
	// Product-list product per row 1 column big tablets end

	// Product-list product per row 2 column tablets
	var removePL2col_qty_bigtablets = 'pl_2col_qty_bigtablets_2 pl_2col_qty_bigtablets_3 pl_2col_qty_bigtablets_4 pl_2col_qty_bigtablets_5 pl_2col_qty_bigtablets_6';
	$('#theme_customization .pl_2col_qty_bigtablets_2').on('click',function(){ $('body').removeClass(removePL2col_qty_bigtablets); $('body').addClass('pl_2col_qty_bigtablets_2'); });
	$('#theme_customization .pl_2col_qty_bigtablets_3').on('click',function(){ $('body').removeClass(removePL2col_qty_bigtablets); $('body').addClass('pl_2col_qty_bigtablets_3'); });
	$('#theme_customization .pl_2col_qty_bigtablets_4').on('click',function(){ $('body').removeClass(removePL2col_qty_bigtablets); $('body').addClass('pl_2col_qty_bigtablets_4'); });
	$('#theme_customization .pl_2col_qty_bigtablets_5').on('click',function(){ $('body').removeClass(removePL2col_qty_bigtablets); $('body').addClass('pl_2col_qty_bigtablets_5'); });
	$('#theme_customization .pl_2col_qty_bigtablets_6').on('click',function(){ $('body').removeClass(removePL2col_qty_bigtablets); $('body').addClass('pl_2col_qty_bigtablets_6'); });
	// Product-list product per row 2 column tablets end

	// Product-list product per row 3 column tablets
	var removePL3col_qty_bigtablets = 'pl_3col_qty_bigtablets_2 pl_3col_qty_bigtablets_3 pl_3col_qty_bigtablets_4 pl_3col_qty_bigtablets_5 pl_3col_qty_bigtablets_6';
	$('#theme_customization .pl_3col_qty_bigtablets_2').on('click',function(){ $('body').removeClass(removePL3col_qty_bigtablets); $('body').addClass('pl_3col_qty_bigtablets_2'); });
	$('#theme_customization .pl_3col_qty_bigtablets_3').on('click',function(){ $('body').removeClass(removePL3col_qty_bigtablets); $('body').addClass('pl_3col_qty_bigtablets_3'); });
	$('#theme_customization .pl_3col_qty_bigtablets_4').on('click',function(){ $('body').removeClass(removePL3col_qty_bigtablets); $('body').addClass('pl_3col_qty_bigtablets_4'); });
	$('#theme_customization .pl_3col_qty_bigtablets_5').on('click',function(){ $('body').removeClass(removePL3col_qty_bigtablets); $('body').addClass('pl_3col_qty_bigtablets_5'); });
	$('#theme_customization .pl_3col_qty_bigtablets_6').on('click',function(){ $('body').removeClass(removePL3col_qty_bigtablets); $('body').addClass('pl_3col_qty_bigtablets_6'); });
	// Product-list product per row 3 column tablets end


	// Product-list product per row 1 column tablets
	var removePL1col_qty_tablets = 'pl_1col_qty_tablets_2 pl_1col_qty_tablets_3 pl_1col_qty_tablets_4 pl_1col_qty_tablets_5 pl_1col_qty_tablets_6';
	$('#theme_customization .pl_1col_qty_tablets_2').on('click',function(){ $('body').removeClass(removePL1col_qty_tablets); $('body').addClass('pl_1col_qty_tablets_2'); });
	$('#theme_customization .pl_1col_qty_tablets_3').on('click',function(){ $('body').removeClass(removePL1col_qty_tablets); $('body').addClass('pl_1col_qty_tablets_3'); });
	$('#theme_customization .pl_1col_qty_tablets_4').on('click',function(){ $('body').removeClass(removePL1col_qty_tablets); $('body').addClass('pl_1col_qty_tablets_4'); });
	$('#theme_customization .pl_1col_qty_tablets_5').on('click',function(){ $('body').removeClass(removePL1col_qty_tablets); $('body').addClass('pl_1col_qty_tablets_5'); });
	$('#theme_customization .pl_1col_qty_tablets_6').on('click',function(){ $('body').removeClass(removePL1col_qty_tablets); $('body').addClass('pl_1col_qty_tablets_6'); });
	// Product-list product per row 1 column tablets end

	// Product-list product per row 1 column phones
	var removePL1col_qty_phones = 'pl_1col_qty_phones_1 pl_1col_qty_phones_2 pl_1col_qty_phones_3 pl_1col_qty_phones_4 pl_1col_qty_phones_5 pl_1col_qty_phones_6';
	$('#theme_customization .pl_1col_qty_phones_1').on('click',function(){ $('body').removeClass(removePL1col_qty_phones); $('body').addClass('pl_1col_qty_phones_1'); });
	$('#theme_customization .pl_1col_qty_phones_2').on('click',function(){ $('body').removeClass(removePL1col_qty_phones); $('body').addClass('pl_1col_qty_phones_2'); });
	$('#theme_customization .pl_1col_qty_phones_3').on('click',function(){ $('body').removeClass(removePL1col_qty_phones); $('body').addClass('pl_1col_qty_phones_3'); });
	$('#theme_customization .pl_1col_qty_phones_4').on('click',function(){ $('body').removeClass(removePL1col_qty_phones); $('body').addClass('pl_1col_qty_phones_4'); });
	$('#theme_customization .pl_1col_qty_phones_5').on('click',function(){ $('body').removeClass(removePL1col_qty_phones); $('body').addClass('pl_1col_qty_phones_5'); });
	$('#theme_customization .pl_1col_qty_phones_6').on('click',function(){ $('body').removeClass(removePL1col_qty_phones); $('body').addClass('pl_1col_qty_phones_6'); });
	// Product-list product per row 1 column phones end










	// *** HOME TABS ***
	var removeHomeTabs = 'home_tabs1 home_tabs2';
	$('#theme_customization .home_tabs1').on('click',function(){ $('body').removeClass(removeHomeTabs); $('body').addClass('home_tabs1'); });
	$('#theme_customization .home_tabs2').on('click',function(){ $('body').removeClass(removeHomeTabs); $('body').addClass('home_tabs2'); });

	// All products link
	var removeAllproducts = 'all_products_yes all_products_no';
	$('#theme_customization .all_products_yes').on('click',function(){ $('body').removeClass(removeAllproducts); $('body').addClass('all_products_yes'); });
	$('#theme_customization .all_products_no').on('click',function(){ $('body').removeClass(removeAllproducts); $('body').addClass('all_products_no'); });
	// All products link end








	// *** PRODUCT-LIST STYLES ***

	// Product-list border type
	var removePLBorderType = 'pl_border_type1 pl_border_type2 pl_border_type3';
	$('#theme_customization .pl_border_type1').on('click',function(){ $('body').removeClass(removePLBorderType); $('body').addClass('pl_border_type1'); });
	$('#theme_customization .pl_border_type2').on('click',function(){ $('body').removeClass(removePLBorderType); $('body').addClass('pl_border_type2'); });
	$('#theme_customization .pl_border_type3').on('click',function(){ $('body').removeClass(removePLBorderType); $('body').addClass('pl_border_type3'); });
	// Product-list border type end

	// Product-list button type
	var removePLButtonType = 'pl_button_type1 pl_button_type2';
	$('#theme_customization .pl_button_type1').on('click',function(){ $('body').removeClass(removePLButtonType); $('body').addClass('pl_button_type1'); });
	$('#theme_customization .pl_button_type2').on('click',function(){ $('body').removeClass(removePLButtonType); $('body').addClass('pl_button_type2'); });
	// Product-list button type end

	// Product-list button qty
	var removePLButtonQty = 'pl_button_qty1 pl_button_qty2 pl_button_qty3 pl_button_qty4';
	$('#theme_customization .pl_button_qty1').on('click',function(){ $('body').removeClass(removePLButtonQty); $('body').addClass('pl_button_qty1'); });
	$('#theme_customization .pl_button_qty2').on('click',function(){ $('body').removeClass(removePLButtonQty); $('body').addClass('pl_button_qty2'); });
	$('#theme_customization .pl_button_qty3').on('click',function(){ $('body').removeClass(removePLButtonQty); $('body').addClass('pl_button_qty3'); });
	$('#theme_customization .pl_button_qty4').on('click',function(){ $('body').removeClass(removePLButtonQty); $('body').addClass('pl_button_qty4'); });
	// Product-list button qty end

	// Product-list reviews
	var removePLReviews = 'pl_reviews_yes pl_reviews_hide_text pl_reviews_no';
	$('#theme_customization .pl_reviews_yes').on('click',function(){ $('body').removeClass(removePLReviews); $('body').addClass('pl_reviews_yes'); });
	$('#theme_customization .pl_reviews_hide_text').on('click',function(){ $('body').removeClass(removePLReviews); $('body').addClass('pl_reviews_hide_text'); });
	$('#theme_customization .pl_reviews_no').on('click',function(){ $('body').removeClass(removePLReviews); $('body').addClass('pl_reviews_no'); });
	// Product-list reviews end

	// Product-list availability
	var removePLAvailability = 'pl_availability_yes pl_availability_no';
	$('#theme_customization .pl_availability_yes').on('click',function(){ $('body').removeClass(removePLAvailability); $('body').addClass('pl_availability_yes'); });
	$('#theme_customization .pl_availability_no').on('click',function(){ $('body').removeClass(removePLAvailability); $('body').addClass('pl_availability_no'); });
	// Product-list availability end

	// Product-list color variants
	var removePLColors = 'pl_colors_yes pl_colors_no';
	$('#theme_customization .pl_colors_yes').on('click',function(){ $('body').removeClass(removePLColors); $('body').addClass('pl_colors_yes'); });
	$('#theme_customization .pl_colors_no').on('click',function(){ $('body').removeClass(removePLColors); $('body').addClass('pl_colors_no'); });
	// Product-list color variants end

	// Product-list ref
	var removePLRef = 'pl_ref_yes pl_ref_no pl_ref_empty_line';
	$('#theme_customization .pl_ref_yes').on('click',function(){ $('body').removeClass(removePLRef); $('body').addClass('pl_ref_yes'); });
	$('#theme_customization .pl_ref_no').on('click',function(){ $('body').removeClass(removePLRef); $('body').addClass('pl_ref_no'); });
	$('#theme_customization .pl_ref_empty_line').on('click',function(){ $('body').removeClass(removePLRef); $('body').addClass('pl_ref_empty_line'); });
	// Product-list ref end

	// Product-list man
	var removePLMan = 'pl_man_yes pl_man_no pl_man_empty_line';
	$('#theme_customization .pl_man_yes').on('click',function(){ $('body').removeClass(removePLMan); $('body').addClass('pl_man_yes'); });
	$('#theme_customization .pl_man_no').on('click',function(){ $('body').removeClass(removePLMan); $('body').addClass('pl_man_no'); });
	$('#theme_customization .pl_man_empty_line').on('click',function(){ $('body').removeClass(removePLMan); $('body').addClass('pl_man_empty_line'); });
	// Product-list man end

	// Product-list desc
	var removePLDesc = 'pl_desc_yes pl_desc_no';
	$('#theme_customization .pl_desc_yes').on('click',function(){ $('body').removeClass(removePLDesc); $('body').addClass('pl_desc_yes'); });
	$('#theme_customization .pl_desc_no').on('click',function(){ $('body').removeClass(removePLDesc); $('body').addClass('pl_desc_no'); });
	// Product-list desc end

	// Product-list button icon
	var removePLButtonIcon = 'pl_button_icon_yes pl_button_icon_no';
	$('#theme_customization .pl_button_icon_yes').on('click',function(){ $('body').removeClass(removePLButtonIcon); $('body').addClass('pl_button_icon_yes'); });
	$('#theme_customization .pl_button_icon_no').on('click',function(){ $('body').removeClass(removePLButtonIcon); $('body').addClass('pl_button_icon_no'); });
	// Product-list button icon end










	// *** FOOTER ***
	// Newsletter hide info
	var removeNewsletter = 'newsletter_info_yes newsletter_info_no';
	$('#theme_customization .newsletter_info_yes').on('click',function(){ $('body').removeClass(removeNewsletter); $('body').addClass('newsletter_info_yes'); });
	$('#theme_customization .newsletter_info_no').on('click',function(){ $('body').removeClass(removeNewsletter); $('body').addClass('newsletter_info_no'); });
	// Newsletter hide info end










	// *** PRODUCT PAGE ***

	// Product layout type
	var removeProductLayout = 'product_layout1 product_layout2';
	$('#theme_customization .product_layout1').on('click',function(){ $('body').removeClass(removeProductLayout); $('body').addClass('product_layout1'); });
	$('#theme_customization .product_layout2').on('click',function(){ $('body').removeClass(removeProductLayout); $('body').addClass('product_layout2'); });
	// Product layout type end

	// Product hide reference
	var removeProductHideReference = 'hide_reference_yes hide_reference_no';
	$('#theme_customization .hide_reference_yes').on('click',function(){ $('body').removeClass(removeProductHideReference); $('body').addClass('hide_reference_yes'); });
	$('#theme_customization .hide_reference_no').on('click',function(){ $('body').removeClass(removeProductHideReference); $('body').addClass('hide_reference_no'); });
	// Product hide reference end

	// Product hide manufacturer
	var removeProductHideMan = 'product_hide_man_yes product_hide_man_no';
	$('#theme_customization .product_hide_man_yes').on('click',function(){ $('body').removeClass(removeProductHideMan); $('body').addClass('product_hide_man_yes'); });
	$('#theme_customization .product_hide_man_no').on('click',function(){ $('body').removeClass(removeProductHideMan); $('body').addClass('product_hide_man_no'); });
	// Product hide manufacturer end

	// Product hide reassurance
	var removeProductHideReassurance = 'hide_reassurance_yes hide_reassurance_no';
	$('#theme_customization .hide_reassurance_yes').on('click',function(){ $('body').removeClass(removeProductHideReassurance); $('body').addClass('hide_reassurance_yes'); });
	$('#theme_customization .hide_reassurance_no').on('click',function(){ $('body').removeClass(removeProductHideReassurance); $('body').addClass('hide_reassurance_no'); });
	// Product hide reassurance end

	// Product tabs
	var removeProductTabs = 'product_tabs1 product_tabs2';
	$('#theme_customization .product_tabs1').on('click',function(){ $('body').removeClass(removeProductTabs); $('body').addClass('product_tabs1'); });
	$('#theme_customization .product_tabs2').on('click',function(){ $('body').removeClass(removeProductTabs); $('body').addClass('product_tabs2'); });
	// Product tabs end










	// *** ORDER AND AUTHETICATION PAGE ***
	
	// Order hide gender
	var removeGender = 'order_gender_hide order_gender_show';
	$('#theme_customization .order_gender_hide').on('click',function(){ $('body').removeClass(removeGender); $('body').addClass('order_gender_hide'); });
	$('#theme_customization .order_gender_show').on('click',function(){ $('body').removeClass(removeGender); $('body').addClass('order_gender_show'); });
	// Order hide gender end

	// Order hide birthday
	var removeBirthday = 'order_birthday_hide order_birthday_show';
	$('#theme_customization .order_birthday_hide').on('click',function(){ $('body').removeClass(removeBirthday); $('body').addClass('order_birthday_hide'); });
	$('#theme_customization .order_birthday_show').on('click',function(){ $('body').removeClass(removeBirthday); $('body').addClass('order_birthday_show'); });
	// Order hide birthday end









	// *** RANGE ***

	// Logo Range
	$('#logo_top_comp input').mousemove( function () {
		var v = $(this).val();
		$('#_desktop_logo').css('padding-top', v + 'px')
		$('#logo_top_comp span.value').html(v);
	});

	$('#logo_bottom_comp input').mousemove( function () {
		var v = $(this).val();
		$('#_desktop_logo').css('padding-bottom', v + 'px')
		$('#logo_bottom_comp span.value').html(v);
	});

	// Featured categories Range
	$('#featcat_height input').mousemove( function () {
		var v = $(this).val();
		$('#home_categories ul li .cat-container').css('min-height', v + 'px')
		$('#featcat_height span.value').html(v);
	});

	// Product-list Range
	$('#pl_name_height input').mousemove( function () {
		var v = $(this).val();
		$('.products .product-miniature .product-title').css('height', v + 'px')
		$('#pl_name_height span.value').html(v);
	});

	$('#pl_name_fs input').mousemove( function () {
		var v = $(this).val();
		$('.products .product-miniature .product-title a').css('font-size', v + 'px')
		$('#pl_name_fs span.value').html(v);
	});

	$('#pl_name_lh input').mousemove( function () {
		var v = $(this).val();
		$('.products .product-miniature .product-title a').css('line-height', v + 'px')
		$('#pl_name_lh span.value').html(v);
	});

	$('#pl_desc_height input').mousemove( function () {
		var v = $(this).val();
		$('#content-wrapper .products .product-miniature .product-desc').css('height', v + 'px')
		$('#pl_desc_height span.value').html(v);
	});

	// *** COLORS - Theme color editor ***


	// Background black
	var removeBgInner = 'bg_white bg_black';
	$('#theme_color .bg_white').on('click',function(){ $('body').removeClass(removeBgInner); $('body').addClass('bg_white'); });
	$('#theme_color .bg_black').on('click',function(){ $('body').removeClass(removeBgInner); $('body').addClass('bg_black'); });

	// Background black end


	// Top

	$('#nav_background').minicolors({
		change: function(hex, rgb) {
			$('nav.header-nav').css('background', hex);
			$('#nav_background').attr('value', hex);
		}
	});

	$('#nav_border').minicolors({
		change: function(hex, rgb) {
			$('nav.header-nav').css('border-color', hex);
			$('#contact-link span.shop-phone').css('border-color', hex);
			$('.lang_currency_top').css('border-color', hex);
			$('#_desktop_user_info').css('border-color', hex);
			$('#nav_border').attr('value', hex);
		}
	});

	$('#top_font_color').minicolors({
		change: function(hex, rgb) {
			$('#contact-link').css('color', hex);
			$('#contact-link a').css('color', hex);
			$('.lang_currency_top span.lang_currency_text').css('color', hex);
			$('.lang_currency_top .dropdown i.expand-more').css('color', hex);
			$('nav.header-nav .user-info span').css('color', hex);
			$('nav.header-nav .user-info a.logout').css('color', hex);
			$('#top_font_color').attr('value', hex);
		}
	});

	$('#top_font_color2').minicolors({
		change: function(hex, rgb) {
			$('#contact-link span.shop-phone strong').css('color', hex);
			$('#contact-link span.shop-phone strong a').css('color', hex);
			$('.lang_currency_top span.expand-more').css('color', hex);
			$('nav.header-nav .user-info a.account').css('color', hex);
			$('#top_font_color2').attr('value', hex);
		}
	});

	$('#top_icons').minicolors({
		change: function(hex, rgb) {
			$('#contact-link span.shop-phone i').css('color', hex);
			$('#top_icons').attr('value', hex);
		}
	});

	$('#header_background').minicolors({
		change: function(hex, rgb) {
			$('.header-top').css('background', hex);
			$('#header_background').attr('value', hex);
		}
	});

	$('#search_button').minicolors({
		change: function(hex, rgb) {
			searchButton = hex;
			$('div#search_widget form button[type=submit]').css('background', hex);
			$('#search_button').attr('value', hex);
		}
	});

	$('#search_button_hover').minicolors({
		change: function(hex, rgb) {
			searchButtonHover = hex;
			$("div#search_widget form button[type=submit]").mouseover(function() {
			  $(this).css('background', hex);
			})
			.mouseout(function() {
				$(this).css('background', searchButton);
			});

			$('#search_button_hover').attr('value', hex);
		}
	});

	$('#search_button_color').minicolors({
		change: function(hex, rgb) {
			searchButtonColor = hex;
			$('div#search_widget form button[type=submit]').css('color', hex);
			$('#search_button_color').attr('value', hex);
		}
	});

	$('#search_button_color_hover').minicolors({
		change: function(hex, rgb) {
			searchButtonColorHover = hex;
			$("div#search_widget form button[type=submit]").mouseover(function() {
			  $(this).css('color', hex);
			})
			.mouseout(function() {
				$(this).css('color', searchButtonColor);
			});

			$('#search_button_color_hover').attr('value', hex);
		}
	});

	$('#cart_background').minicolors({
		change: function(hex, rgb) {
			$('#header div#_desktop_cart .blockcart .header').css('background', hex);
			$('#cart_background').attr('value', hex);
		}
	});

	$('#cart_color').minicolors({
		change: function(hex, rgb) {
			$('#header div#_desktop_cart .blockcart .header a.cart_link').css('color', hex);
			$('#cart_color').attr('value', hex);
		}
	});

	$('#sticky_cart_bg1').minicolors({
		change: function(hex, rgb) {
			$('#sticky_cart_bg1').attr('value', hex);
		}
	});

	$('#sticky_cart_bg2').minicolors({
		change: function(hex, rgb) {
			$('#sticky_cart_bg2').attr('value', hex);
		}
	});

	$('#sticky_cart_color').minicolors({
		change: function(hex, rgb) {
			$('#sticky_cart_color').attr('value', hex);
		}
	});

	$('#account_background').minicolors({
		change: function(hex, rgb) {
			$('a.account_cart_rwd').css('background', hex);
			$('#account_background').attr('value', hex);
		}
	});

	$('#slider_dot').minicolors({
		change: function(hex, rgb) {         
			$('#homepage-slider .bx-wrapper .bx-pager.bx-default-pager a.active').css('background-color', hex);
			$('#slider_dot').attr('value', hex);
		}
	});


	// Menu
	$('#menu_bg').minicolors({
		change: function(hex, rgb) {    
			$('#_desktop_top_menu').css('background', hex);

			$('#rwd_menu').css('background', hex);

			$('#menu_bg').attr('value', hex);
		}
	});

	$('#menu_border_color').minicolors({
		change: function(hex, rgb) {         
			$('#_desktop_top_menu').css('border-color', hex);
			$('#_desktop_top_menu > ul > li').css('border-color', hex);

			$('div#rwd_menu').css('border-color', hex);
			$('div#rwd_menu .rwd_menu_item').css('border-color', hex);
			$('div#rwd_menu .rwd_menu_item:first-child').css('border-color', hex);

			$('#menu_border_color').attr('value', hex);
		}
	});

	$('#menu_font_color').minicolors({
		change: function(hex, rgb) {    
			fontColorMenu = hex;
			$('#_desktop_top_menu > ul > li > a').css('color', hex);

			$('div#rwd_menu').css('color', hex);
			$('div#rwd_menu a').css('color', hex);

			$('#menu_font_color').attr('value', hex);
		}
	});

	$('#menu_font_color_hover').minicolors({
		change: function(hex, rgb) {
			fontColorMenuHover = hex;
			$("#_desktop_top_menu > ul > li > a").mouseover(function() {
			  $(this).css('color', hex);
			})
			.mouseout(function() {
				$(this).css('color', fontColorMenu);
			});


			$('#menu_font_color_hover').attr('value', hex);
		}
	});

	$('#menu_li_hover').minicolors({
		change: function(hex, rgb) {
			$("#_desktop_top_menu > ul > li > a, div#rwd_menu .rwd_menu_item, div#rwd_menu .rwd_menu_item a").mouseover(function() {
			  $(this).css('background', hex);
			})
			.mouseout(function() {
				$(this).css({'background' : 'transparent'});
			});

			$('#menu_li_hover').attr('value', hex);
		}
	});

	$('#submenu_background').minicolors({
		change: function(hex, rgb) {
			$(".submenu1 #_desktop_top_menu .popover.sub-menu ul.top-menu li a, #mobile_top_menu_wrapper2 .top-menu li a, .rwd_menu_open ul.user_info li a").mouseover(function() {
			  $(this).css('background', hex);
			})
			.mouseout(function() {
				$(this).css({'background' : 'transparent'});
			});

			$('#submenu_background').attr('value', hex);
		}
	});

	$('#submenu_color').minicolors({
		change: function(hex, rgb) {         

			$(".submenu1 #_desktop_top_menu .popover.sub-menu ul.top-menu li a, #mobile_top_menu_wrapper2 .top-menu li a, .rwd_menu_open ul.user_info li a").mouseover(function() {
			  $(this).css('color', hex);
			})
			.mouseout(function() {
				$(this).css({'color' : ''});
			});

			$('#submenu_color').attr('value', hex);
		}
	});


	// Featured categories
	$('#feat_title').minicolors({
		change: function(hex, rgb) {
			$('#home_categories .homecat_title span').css('borderColor', hex);
			$('#feat_title').attr('value', hex);
		}
	});

	$('#feat_cat_title').minicolors({
		change: function(hex, rgb) {
			$('#home_categories ul li .homecat_name span').css('background', hex);
			$('#feat_cat_title').attr('value', hex);
		}
	});

	// Featured categories subcategory button
	$('#feat_button_bg').minicolors({
		change: function(hex, rgb) {
			GreenButtonBG = hex;

			$('#home_categories ul li a.view_more').css('background', hex);
			$('#feat_button_bg').attr('value', hex);
		}
	});

	$('#feat_button_bg_hover').minicolors({
		change: function(hex, rgb) {
			$("#home_categories ul li a.view_more").mouseover(function() {
				$(this).css('background', hex);
			})
			.mouseout(function() {
				$(this).css('background', GreenButtonBG);
			});

			$('#feat_button_bg_hover').attr('value', hex);
		}
	});

	$('#feat_button_color').minicolors({
		change: function(hex, rgb) {       
			GreenButtonColor = hex;

			$('#home_categories ul li a.view_more').css('color', hex);	
			$('#feat_button_color').attr('value', hex);
		}
	});

	$('#feat_button_color_hover').minicolors({
		change: function(hex, rgb) {
			$("#home_categories ul li a.view_more").mouseover(function() {
				$(this).css('color', hex);
			})
			.mouseout(function() {
				$(this).css('color', GreenButtonColor);
			});

			$('#feat_button_color_hover').attr('value', hex);
		}
	});

	$('#feat_button_border_color').minicolors({
		change: function(hex, rgb) {
			GreenButtonBorder = hex;

			$('#home_categories ul li a.view_more').css('borderColor', hex);
			$('#feat_button_border_color').attr('value', hex);
		}
	});

	$('#feat_button_border_color_hover').minicolors({
		change: function(hex, rgb) {         
			PLButtonBorder2 = hex;

			$("#home_categories ul li a.view_more").mouseover(function() {
			  $(this).css('borderColor', hex);
			})
			.mouseout(function() {
				$(this).css('borderColor', GreenButtonBorder);
			});

			$('#feat_button_border_color_hover').attr('value', hex);
		}
	});


	// Columns
	$('#title_block1').minicolors({
		change: function(hex, rgb) {       
			$('.columns .text-uppercase span').css('borderColor', hex);
			$('.columns .text-uppercase a').css('borderColor', hex);
			$('.columns div#_desktop_cart .cart_index_title a').css('borderColor', hex);
			$('#home_man_product .catprod_title a span').css('borderColor', hex);
			$('#title_block1').attr('value', hex);
		}
	});

	$('#title_block2').minicolors({
		change: function(hex, rgb) {       
			gradientTitleBlock2 = hex;

			$('#columns .block .title_block').css({'background' : 'linear-gradient(to bottom, ' + gradientTitleBlock1 + ' 0%, ' + gradientTitleBlock2 + ' 100%)'});
			$('#title_block2').attr('value', gradientTitleBlock2);
		}
	});
 
	$('#price_column_color').minicolors({
		change: function(hex, rgb) {         
			$('.columns .product-price-and-shipping .price').css('color', hex);
			$('#price_column_color').attr('value', hex);
		}
	});


	// Tabs or blocks
	$('#index_title1').minicolors({
		change: function(hex, rgb) {       
			$('.index_title a').css('borderColor', hex);
			$('.index_title span').css('borderColor', hex);
			$('.tabs ul.nav-tabs li.nav-item a.active').css('borderColor', hex);
			$('#index_title1').attr('value', hex);
		}
	});

	$('#index_title2').minicolors({
		change: function(hex, rgb) {       
			gradientIndexTitle2 = hex;

			$('.index_title').css({'background' : 'linear-gradient(to bottom, ' + gradientIndexTitle1 + ' 0%, ' + gradientIndexTitle2 + ' 100%)'});
			$('#home-page-tabs > li.active a').css({'background' : 'linear-gradient(to bottom, ' + gradientIndexTitle1 + ' 0%, ' + gradientIndexTitle2 + ' 100%)'});
			$('#home-page-tabs > li').css('color', hex);
			$('#index_title2').attr('value', gradientIndexTitle2);
		}
	});


	// Product-list
	$('#pl_cart_bg').minicolors({
		change: function(hex, rgb) {
			CartButtonBG = hex;

			$('.button-container .add-to-cart').css('background', hex);
			$('#subcart .cart-buttons .viewcart').css('background', hex);
			$('#pl_cart_bg').attr('value', hex);
		}
	});

	$('#pl_cart_bg_hover').minicolors({
		change: function(hex, rgb) {
			$(".button-container .add-to-cart, #subcart .cart-buttons .viewcart").mouseover(function() {
				$(this).css('background', hex);
			})
			.mouseout(function() {
				$(this).css('background', CartButtonBG);
			});

			$('#pl_cart_bg_hover').attr('value', hex);
		}
	});

	$('#pl_cart_color').minicolors({
		change: function(hex, rgb) {       
			CartButtonColor = hex;

			$('.button-container .add-to-cart').css('color', hex);
			$('#subcart .cart-buttons .viewcart').css('color', hex);
			$('#pl_cart_color').attr('value', hex);
		}
	});

	$('#pl_cart_color_hover').minicolors({
		change: function(hex, rgb) {
			$(".button-container .add-to-cart, #subcart .cart-buttons .viewcart").mouseover(function() {
				$(this).css('color', hex);
			})
			.mouseout(function() {
				$(this).css('color', CartButtonColor);
			});

			$('#pl_cart_color_hover').attr('value', hex);
		}
	});

	$('#pl_cart_button_border_color').minicolors({
		change: function(hex, rgb) {
			CartButtonBorder = hex;

			$('.button-container .add-to-cart').css('borderColor', hex);
			$('#subcart .cart-buttons .viewcart').css('borderColor', hex);
			$('#pl_cart_button_border_color').attr('value', hex);
		}
	});

	$('#pl_cart_button_border_color_hover').minicolors({
		change: function(hex, rgb) {         
			PLButtonBorder2 = hex;

			$(".button-container .add-to-cart, #subcart .cart-buttons .viewcart").mouseover(function() {
			  $(this).css('borderColor', hex);
			})
			.mouseout(function() {
				$(this).css('borderColor', CartButtonBorder);
			});

			$('#pl_cart_button_border_color_hover').attr('value', hex);
		}
	});

	$('#price_color_index').minicolors({
		change: function(hex, rgb) {         
			$('.products .product-miniature span.price').css('color', hex);
			$('#home_cat_product ul li .product-price-and-shipping .price').css('color', hex);
			$('#price_color_index').attr('value', hex);
		}
	});

	$('#product_name_color').minicolors({
		change: function(hex, rgb) {         
			$('.products .product-miniature .product-title a').css('color', hex);
			$('#home_cat_product ul li .right-block .name_block a').css('color', hex);
			$('#product_name_color').attr('value', hex);
		}
	});

	$('#new_label').minicolors({
		change: function(hex, rgb) {         
			$('a.product-flags span.product-flag.new').css('background', hex);
			$('#home_cat_product a.product-flags span.product-flag.new').css('background', hex);
			$('#product #content .product-flags .product-flag.new').css('background', hex);
			$('#new_label').attr('value', hex);
		}
	});


	// Products from category
	$('#catprod_title').minicolors({
		change: function(hex, rgb) {       
			$('#home_cat_product .catprod_title span').css('borderColor', hex);
			$('#catprod_title').attr('value', hex);
		}
	});


	// Featured manufacturers
	$('#featman_title').minicolors({
		change: function(hex, rgb) {       
			$('#home_man .man_title span').css('borderColor', hex);
			$('#featman_title').attr('value', hex);
		}
	});


	// Footer
	$('#footer_cms_icons').minicolors({
		change: function(hex, rgb) {         
			$('#angarinfo_block .icon_cms').css('color', hex);
			$('#footer_cms_icons').attr('value', hex);
		}
	});

	$('#footer_background').minicolors({
		change: function(hex, rgb) {       
			$('.footer-container').css('background', hex);
			$('#footer_background').attr('value', hex);
		}
	});

	$('#footer_border').minicolors({
		change: function(hex, rgb) {       
			$('.footer-container').css('borderColor', hex);
			$('.footer-container .h3').css('borderColor', hex);
			$('.row.social_footer').css('borderColor', hex);
			$('#footer_border').attr('value', hex);
		}
	});

	$('#footer_border_header').minicolors({
		change: function(hex, rgb) {       
			$('.footer-container .h3 span').css('borderColor', hex);
			$('.footer-container .h3 a').css('borderColor', hex);
			$('.footer-container .links .title span.h3').css('borderColor', hex);
			$('.footer-container .links .title a.h3').css('borderColor', hex);
			$('#footer_border_header').attr('value', hex);
		}
	});

	$('#footer_font_color').minicolors({
		change: function(hex, rgb) {
			$('.footer-container').css('color', hex);
			$('.footer-container .h3').css('color', hex);
			$('.footer-container a').css('color', hex);
			$('.footer-container li a').css('color', hex);
			$('#footer_font_color').attr('value', hex);
		}
	});

	$('#newsletter_button').minicolors({
		change: function(hex, rgb) {
			newsletterButton = hex;
			$('.block_newsletter .btn-newsletter').css('background', hex);
			$('#newsletter_button').attr('value', hex);
		}
	});

	$('#newsletter_button_hover').minicolors({
		change: function(hex, rgb) {
			newsletterButtonHover = hex;
			$(".block_newsletter .btn-newsletter").mouseover(function() {
				$(this).css('background', hex);
			})
			.mouseout(function() {
				$(this).css('background', newsletterButton);
			});

			$('#newsletter_button_hover').attr('value', hex);
		}
	});

	$('#newsletter_color').minicolors({
		change: function(hex, rgb) {
			newsletterColor = hex;
			$('.block_newsletter .btn-newsletter').css('color', hex);
			$('#newsletter_color').attr('value', hex);
		}
	});

	$('#newsletter_color_hover').minicolors({
		change: function(hex, rgb) {
			newsletterColorHover = hex;
			$(".block_newsletter .btn-newsletter").mouseover(function() {
				$(this).css('color', hex);
			})
			.mouseout(function() {
				$(this).css('color', newsletterColor);
			});

			$('#newsletter_color_hover').attr('value', hex);
		}
	});

	$('#footer2_background').minicolors({
		change: function(hex, rgb) {       
			$('.footer-container .bottom-footer').css('background', hex);
			$('#footer2_background').attr('value', hex);
		}
	});

	$('#footer2_border').minicolors({
		change: function(hex, rgb) {       
			$('.footer-container .bottom-footer').css('borderColor', hex);
			$('#footer2_border').attr('value', hex);
		}
	});

	$('#footer2_font_color').minicolors({
		change: function(hex, rgb) {
			$('.footer-container .bottom-footer').css('color', hex);
			$('#footer2_font_color').attr('value', hex);
		}
	});


	// Product page
	$('#price_color_product').minicolors({
		change: function(hex, rgb) {         
			$('.product-prices .current-price span.price').css('color', hex);
			$('#price_color_product').attr('value', hex);
		}
	});




	$('#product_cart_button_bg').minicolors({
		change: function(hex, rgb) {       
			ProductButtonBG = hex;
			$('.product-add-to-cart button.btn.add-to-cart').css('background', hex);
			$('#product_cart_button_bg').attr('value', hex);
		}
	});

	$('#product_cart_button_bg_hover').minicolors({
		change: function(hex, rgb) {
			$(".product-add-to-cart button.btn.add-to-cart").mouseover(function() {
				$(this).css('background', hex);
			})
			.mouseout(function() {
				$(this).css('background', ProductButtonBG);
			});

			$('#product_cart_button_bg_hover').attr('value', hex);
		}
	});


	$('#product_cart_button_color').minicolors({
		change: function(hex, rgb) {
			ProductButtonColor = hex;

			$('.product-add-to-cart button.btn.add-to-cart').css('color', hex);	
			$('#product_cart_button_color').attr('value', hex);
		}
	});

	$('#product_cart_button_color_hover').minicolors({
		change: function(hex, rgb) {
			$(".product-add-to-cart button.btn.add-to-cart").mouseover(function() {
				$(this).css('color', hex);
			})
			.mouseout(function() {
				$(this).css('color', ProductButtonColor);
			});

			$('#product_cart_button_color_hover').attr('value', hex);
		}
	});


	$('#product_cart_button_border_color').minicolors({
		change: function(hex, rgb) {
			ProductButtonBorder = hex;

			$('.product-add-to-cart button.btn.add-to-cart').css('borderColor', hex);
			$('#product_cart_button_border_color').attr('value', hex);
		}
	});

	$('#product_cart_button_border_color_hover').minicolors({
		change: function(hex, rgb) {         
			$(".product-add-to-cart button.btn.add-to-cart").mouseover(function() {
			  $(this).css('borderColor', hex);
			})
			.mouseout(function() {
				$(this).css('borderColor', ProductButtonBorder);
			});

			$('#product_cart_button_border_color_hover').attr('value', hex);
		}
	});




	$('#product_selected_tab').minicolors({
		change: function(hex, rgb) {         
			$('#product .tabs ul.nav-tabs li.nav-item a.active').css('borderColor', hex);
			$('#product .index_title span').css('borderColor', hex);
			$('.page-product-heading span').css('borderColor', hex);
			$('#product_selected_tab').attr('value', hex);
		}
	});

// Delete below
	$('#product_tab').minicolors({
		change: function(hex, rgb) {         
			$('.product_tabs2 ul#more_info_tabs li a').css('background', hex);
			$('#product_tab').attr('value', hex);
		}
	});

	$('#product_tab_block_background').minicolors({
		change: function(hex, rgb) {         
			$('.product_tabs2 div#more_info_sheets').css('background', hex);
			$('#product_tab_block_background').attr('value', hex);
		}
	});


	// Other buttons

	$('#green_button_bg').minicolors({
		change: function(hex, rgb) {
			GreenButtonBG = hex;

			$('.btn-primary').css('background', hex);
			$('#green_button_bg').attr('value', hex);
		}
	});

	$('#green_button_bg_hover').minicolors({
		change: function(hex, rgb) {
			$(".btn-primary").mouseover(function() {
				$(this).css('background', hex);
			})
			.mouseout(function() {
				$(this).css('background', GreenButtonBG);
			});

			$('#green_button_bg_hover').attr('value', hex);
		}
	});


	$('#green_button_color').minicolors({
		change: function(hex, rgb) {       
			GreenButtonColor = hex;

			$('.btn-primary').css('color', hex);	
			$('#green_button_color').attr('value', hex);
		}
	});

	$('#green_button_color_hover').minicolors({
		change: function(hex, rgb) {
			$(".btn-primary").mouseover(function() {
				$(this).css('color', hex);
			})
			.mouseout(function() {
				$(this).css('color', GreenButtonColor);
			});

			$('#green_button_color_hover').attr('value', hex);
		}
	});


	$('#green_button_border_color').minicolors({
		change: function(hex, rgb) {
			GreenButtonBorder = hex;

			$('.btn-primary').css('borderColor', hex);
			$('#green_button_border_color').attr('value', hex);
		}
	});

	$('#green_button_border_color_hover').minicolors({
		change: function(hex, rgb) {         
			PLButtonBorder2 = hex;

			$(".btn-primary").mouseover(function() {
			  $(this).css('borderColor', hex);
			})
			.mouseout(function() {
				$(this).css('borderColor', GreenButtonBorder);
			});

			$('#green_button_border_color_hover').attr('value', hex);
		}
	});







	$('#grey_button_bg').minicolors({
		change: function(hex, rgb) {
			GreyButtonBG = hex;

			$('.btn-secondary').css('background', hex);
			$('#grey_button_bg').attr('value', hex);
		}
	});

	$('#grey_button_bg_hover').minicolors({
		change: function(hex, rgb) {
			$(".btn-secondary").mouseover(function() {
				$(this).css('background', hex);
			})
			.mouseout(function() {
				$(this).css('background', GreyButtonBG);
			});

			$('#grey_button_bg_hover').attr('value', hex);
		}
	});


	$('#grey_button_color').minicolors({
		change: function(hex, rgb) {       
			GreyButtonColor = hex;

			$('.btn-secondary').css('color', hex);	
			$('#grey_button_color').attr('value', hex);
		}
	});

	$('#grey_button_color_hover').minicolors({
		change: function(hex, rgb) {
			$(".btn-secondary").mouseover(function() {
				$(this).css('color', hex);
			})
			.mouseout(function() {
				$(this).css('color', GreyButtonColor);
			});

			$('#grey_button_color_hover').attr('value', hex);
		}
	});


	$('#grey_button_border_color').minicolors({
		change: function(hex, rgb) {
			GreyButtonBorder = hex;

			$('.btn-secondary').css('borderColor', hex);
			$('#grey_button_border_color').attr('value', hex);
		}
	});

	$('#grey_button_border_color_hover').minicolors({
		change: function(hex, rgb) {         
			PLButtonBorder2 = hex;

			$(".btn-secondary").mouseover(function() {
			  $(this).css('borderColor', hex);
			})
			.mouseout(function() {
				$(this).css('borderColor', GreyButtonBorder);
			});

			$('#grey_button_border_color_hover').attr('value', hex);
		}
	});






	$('#inputs_outline').minicolors({
		change: function(hex, rgb) {
			$('.input-group.focus').css('outline-color', hex);
			$('#inputs_outline').attr('value', hex);
		}
	});

	$('#pagination_color').minicolors({
		change: function(hex, rgb) {
			$('.pagination .page-list .current a').css('color', hex);
			$('#pagination_color').attr('value', hex);
		}
	});

	$('#account_icon_color').minicolors({
		change: function(hex, rgb) {
			$('#account_icon_color').attr('value', hex);
		}
	});






	$('#scroll_button_bg').minicolors({
		change: function(hex, rgb) {
			ScrollButtonBG = hex;

			$('#scroll_top').css('background', hex);
			$('#scroll_button_bg').attr('value', hex);
		}
	});

	$('#scroll_button_bg_hover').minicolors({
		change: function(hex, rgb) {
			$("#scroll_top").mouseover(function() {
				$(this).css('background', hex);
			})
			.mouseout(function() {
				$(this).css('background', ScrollButtonBG);
			});

			$('#scroll_button_bg_hover').attr('value', hex);
		}
	});

	$('#scroll_button_color').minicolors({
		change: function(hex, rgb) {
			ScrollButtonColor = hex;

			$('#scroll_top').css('color', hex);
			$('#scroll_button_color').attr('value', hex);
		}
	});

	$('#scroll_button_color_hover').minicolors({
		change: function(hex, rgb) {
			$("#scroll_top").mouseover(function() {
				$(this).css('color', hex);
			})
			.mouseout(function() {
				$(this).css('color', ScrollButtonColor);
			});

			$('#scroll_button_color_hover').attr('value', hex);
		}
	});
















	// Live edit end

});