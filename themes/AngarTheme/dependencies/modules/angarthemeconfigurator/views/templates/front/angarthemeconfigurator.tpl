{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{if $enableconfig == 1}

<div id="theme_customization_icon">
	<i class="fa"></i>
</div>

<form id="theme_customization" action="" method="post">

<h5>{l s='Theme layout editor' mod='angarthemeconfigurator'}</h5>

{if $demo == 1}
<div class="must_save2">{l s='Remember to often save your changes.' mod='angarthemeconfigurator'}</br>{l s='After saving, click on the shop logo and refresh the store several times to load your changes.' mod='angarthemeconfigurator'}</div>
{/if}

<div id="accordion">
<div class="panel">

	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box0">{l s='Font' mod='angarthemeconfigurator'}</div>
	<div id="customization_box0" class="collapse customization_container">	

	<div class="main_font">
		<div class="must_save"><sup>*</sup> {l s='- You must save this option to see changes' mod='angarthemeconfigurator'}</div>
		<label class="options_title">{l s='Main font:' mod='angarthemeconfigurator'} <sup>*</sup></label></br>
		<label><input type="radio" name="angarconfig_input59" value="Poppins" {if $css59 == Poppins}checked{/if}>{l s='Poppins' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Open_Sans" {if $css59 == Open_Sans}checked{/if}>{l s='Open Sans' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Roboto" {if $css59 == Roboto}checked{/if}>{l s='Roboto' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Lato" {if $css59 == Lato}checked{/if}>{l s='Lato' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Raleway" {if $css59 == Raleway}checked{/if}>{l s='Raleway' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Montserrat" {if $css59 == Montserrat}checked{/if}>{l s='Montserrat' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Bitter" {if $css59 == Bitter}checked{/if}>{l s='Bitter' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Oswald" {if $css59 == Oswald}checked{/if}>{l s='Oswald' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Dosis" {if $css59 == Dosis}checked{/if}>{l s='Dosis' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Nunito" {if $css59 == Nunito}checked{/if}>{l s='Nunito' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Muli" {if $css59 == Muli}checked{/if}>{l s='Muli' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Gothic_A1" {if $css59 == Gothic_A1}checked{/if}>{l s='Gothic A1' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Fira_Sans" {if $css59 == Fira_Sans}checked{/if}>{l s='Fira Sans' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Ubuntu" {if $css59 == Ubuntu}checked{/if}>{l s='Ubuntu' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Lora" {if $css59 == Lora}checked{/if}>{l s='Lora' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Work_Sans" {if $css59 == Work_Sans}checked{/if}>{l s='Work Sans' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Merriweather" {if $css59 == Merriweather}checked{/if}>{l s='Merriweather' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Playfair_Display" {if $css59 == Playfair_Display}checked{/if}>{l s='Playfair Display' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Playfair_Display_SC" {if $css59 == Playfair_Display_SC}checked{/if}>{l s='Playfair Display SC' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Noto_Sans" {if $css59 == Noto_Sans}checked{/if}>{l s='Noto Sans' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Noto_Serif" {if $css59 == Noto_Serif}checked{/if}>{l s='Noto Serif' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Roboto_Slab" {if $css59 == Roboto_Slab}checked{/if}>{l s='Roboto Slab' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Arimo" {if $css59 == Arimo}checked{/if}>{l s='Arimo' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Cabin" {if $css59 == Cabin}checked{/if}>{l s='Cabin' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Titillium_Web" {if $css59 == Titillium_Web}checked{/if}>{l s='Titillium Web' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Source_Sans_Pro" {if $css59 == Source_Sans_Pro}checked{/if}>{l s='Source Sans Pro' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="PT_Sans" {if $css59 == PT_Sans}checked{/if}>{l s='PT Sans' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Oxygen" {if $css59 == Oxygen}checked{/if}>{l s='Oxygen' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Quicksand" {if $css59 == Quicksand}checked{/if}>{l s='Quicksand' mod='angarthemeconfigurator'}</label></br>
		<label><input type="radio" name="angarconfig_input59" value="Karla" {if $css59 == Karla}checked{/if}>{l s='Karla' mod='angarthemeconfigurator'}</label></br>


	</div>

	</div>

	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box1">{l s='Background' mod='angarthemeconfigurator'}</div>
	<div id="customization_box1" class="collapse customization_container">	

		<div class="background_select_box">
			<label class="options_title">{l s='Pattern background:' mod='angarthemeconfigurator'}</label></br>
			<label class="custom_pattern_bg">{l s='Add file custom_pattern.png to themes/AngarTheme/assets/img/patterns next save and clear browser cache.' mod='angarthemeconfigurator'}</label>

			<label class="bg_select no_bg {if $css1 == no_bg}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="no_bg" {if $css1 == no_bg}checked{/if}></label>
			<label class="bg_select color_only {if $css1 == color_only}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="color_only" {if $css1 == color_only}checked{/if}></label>
			<label class="bg_select custom_pattern {if $css1 == custom_pattern}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="custom_pattern" {if $css1 == custom_pattern}checked{/if}></label>

			<label class="bg_select bg1 {if $css1 == bg1}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg1" {if $css1 == bg1}checked{/if}></label>
			<label class="bg_select bg2 {if $css1 == bg2}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg2" {if $css1 == bg2}checked{/if}></label>
			<label class="bg_select bg3 {if $css1 == bg3}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg3" {if $css1 == bg3}checked{/if}></label>
			<label class="bg_select bg4 {if $css1 == bg4}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg4" {if $css1 == bg4}checked{/if}></label>
			<label class="bg_select bg5 {if $css1 == bg5}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg5" {if $css1 == bg5}checked{/if}></label>
			<label class="bg_select bg6 {if $css1 == bg6}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg6" {if $css1 == bg6}checked{/if}></label>
			<label class="bg_select bg7 {if $css1 == bg7}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg7" {if $css1 == bg7}checked{/if}></label>
			<label class="bg_select bg8 {if $css1 == bg8}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg8" {if $css1 == bg8}checked{/if}></label>
			<label class="bg_select bg9 {if $css1 == bg9}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg9" {if $css1 == bg9}checked{/if}></label>
			<label class="bg_select bg10 {if $css1 == bg10}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg10" {if $css1 == bg10}checked{/if}></label>
			<label class="bg_select bg11 {if $css1 == bg11}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg11" {if $css1 == bg11}checked{/if}></label>
			<label class="bg_select bg12 {if $css1 == bg12}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg12" {if $css1 == bg12}checked{/if}></label>
			<label class="bg_select bg13 {if $css1 == bg13}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg13" {if $css1 == bg13}checked{/if}></label>
			<label class="bg_select bg14 {if $css1 == bg14}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg14" {if $css1 == bg14}checked{/if}></label>
			<label class="bg_select bg15 {if $css1 == bg15}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg15" {if $css1 == bg15}checked{/if}></label>
			<label class="bg_select bg16 {if $css1 == bg16}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg16" {if $css1 == bg16}checked{/if}></label>
			<label class="bg_select bg17 {if $css1 == bg17}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg17" {if $css1 == bg17}checked{/if}></label>
			<label class="bg_select bg18 {if $css1 == bg18}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg18" {if $css1 == bg18}checked{/if}></label>
			<label class="bg_select bg19 {if $css1 == bg19}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg19" {if $css1 == bg19}checked{/if}></label>
			<label class="bg_select bg20 {if $css1 == bg20}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg20" {if $css1 == bg20}checked{/if}></label>
			<label class="bg_select bg21 {if $css1 == bg21}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg21" {if $css1 == bg21}checked{/if}></label>
			<label class="bg_select bg22 {if $css1 == bg22}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg22" {if $css1 == bg22}checked{/if}></label>
			<label class="bg_select bg23 {if $css1 == bg23}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg23" {if $css1 == bg23}checked{/if}></label>
			<label class="bg_select bg24 {if $css1 == bg24}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg24" {if $css1 == bg24}checked{/if}></label>
			<label class="bg_select bg25 {if $css1 == bg25}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg25" {if $css1 == bg25}checked{/if}></label>
			<label class="bg_select bg26 {if $css1 == bg26}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg26" {if $css1 == bg26}checked{/if}></label>
			<label class="bg_select bg27 {if $css1 == bg27}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg27" {if $css1 == bg27}checked{/if}></label>
			<label class="bg_select bg28 {if $css1 == bg28}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg28" {if $css1 == bg28}checked{/if}></label>
			<label class="bg_select bg29 {if $css1 == bg29}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg29" {if $css1 == bg29}checked{/if}></label>
			<label class="bg_select bg30 {if $css1 == bg30}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg30" {if $css1 == bg30}checked{/if}></label>
			<label class="bg_select bg31 {if $css1 == bg31}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg31" {if $css1 == bg31}checked{/if}></label>
			<label class="bg_select bg32 {if $css1 == bg32}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg32" {if $css1 == bg32}checked{/if}></label>
			<label class="bg_select bg33 {if $css1 == bg33}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg33" {if $css1 == bg33}checked{/if}></label>
			<label class="bg_select bg34 {if $css1 == bg34}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg34" {if $css1 == bg34}checked{/if}></label>
			<label class="bg_select bg35 {if $css1 == bg35}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg35" {if $css1 == bg35}checked{/if}></label>
			<label class="bg_select bg36 {if $css1 == bg36}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg36" {if $css1 == bg36}checked{/if}></label>
			<label class="bg_select bg37 {if $css1 == bg37}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg37" {if $css1 == bg37}checked{/if}></label>
			<label class="bg_select bg38 {if $css1 == bg38}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="bg38" {if $css1 == bg38}checked{/if}></label>


			</br>
			<label class="options_title">{l s='Texture background:' mod='angarthemeconfigurator'}</label></br>
			<label class="custom_texture_bg">{l s='Add file custom_texture.jpg to themes/AngarTheme/assets/img/textures next save and clear browser cache.' mod='angarthemeconfigurator'}</label>
			<label class="bg_select custom_texture {if $css1 == custom_texture}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="custom_texture" {if $css1 == custom_texture}checked{/if}></label>
			<label class="bg_select texture01 {if $css1 == texture01}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture01" {if $css1 == texture01}checked{/if}></label>
			<label class="bg_select texture02 {if $css1 == texture02}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture02" {if $css1 == texture02}checked{/if}></label>
			<label class="bg_select texture03 {if $css1 == texture03}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture03" {if $css1 == texture03}checked{/if}></label>
			<label class="bg_select texture04 {if $css1 == texture04}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture04" {if $css1 == texture04}checked{/if}></label>
			<label class="bg_select texture05 {if $css1 == texture05}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture05" {if $css1 == texture05}checked{/if}></label>
			<label class="bg_select texture06 {if $css1 == texture06}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture06" {if $css1 == texture06}checked{/if}></label>
			<label class="bg_select texture07 {if $css1 == texture07}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture07" {if $css1 == texture07}checked{/if}></label>
			<label class="bg_select texture08 {if $css1 == texture08}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture08" {if $css1 == texture08}checked{/if}></label>
			<label class="bg_select texture09 {if $css1 == texture09}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture09" {if $css1 == texture09}checked{/if}></label>
			<label class="bg_select texture10 {if $css1 == texture10}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture10" {if $css1 == texture10}checked{/if}></label>
			<label class="bg_select texture11 {if $css1 == texture11}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture11" {if $css1 == texture11}checked{/if}></label>
			<label class="bg_select texture12 {if $css1 == texture12}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture12" {if $css1 == texture12}checked{/if}></label>
			<label class="bg_select texture13 {if $css1 == texture13}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture13" {if $css1 == texture13}checked{/if}></label>
			<label class="bg_select texture14 {if $css1 == texture14}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture14" {if $css1 == texture14}checked{/if}></label>
			<label class="bg_select texture15 {if $css1 == texture15}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture15" {if $css1 == texture15}checked{/if}></label>
			<label class="bg_select texture16 {if $css1 == texture16}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture16" {if $css1 == texture16}checked{/if}></label>
			<label class="bg_select texture17 {if $css1 == texture17}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture17" {if $css1 == texture17}checked{/if}></label>
			<label class="bg_select texture18 {if $css1 == texture18}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture18" {if $css1 == texture18}checked{/if}></label>
			<label class="bg_select texture19 {if $css1 == texture19}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture19" {if $css1 == texture19}checked{/if}></label>
			<label class="bg_select texture20 {if $css1 == texture20}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture20" {if $css1 == texture20}checked{/if}></label>
			<label class="bg_select texture21 {if $css1 == texture21}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture21" {if $css1 == texture21}checked{/if}></label>
			<label class="bg_select texture22 {if $css1 == texture22}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture22" {if $css1 == texture22}checked{/if}></label>
			<label class="bg_select texture23 {if $css1 == texture23}bg_current{/if}"><input type="radio" name="angarconfig_input1" value="texture23" {if $css1 == texture23}checked{/if}></label>


	</div>

		<div class="color_modyficator form-group">
			<label class="options_title">{l s='Pattern background color:' mod='angarthemeconfigurator'}</label></br>
			<input type="text" name="angarconfig_input2" id="background_color" class="form-control" value="{$css2|escape:'htmlall':'UTF-8'}">
		</div>

		<div class="bg_attachment">
			<label class="options_title">{l s='Background attachment:' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_attatchment_fixed"><input type="radio" name="angarconfig_input3" value="bg_attatchment_fixed" {if $css3 == bg_attatchment_fixed}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_attatchment_normal"><input type="radio" name="angarconfig_input3" value="bg_attatchment_normal" {if $css3 == bg_attatchment_normal}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>


		<div class="bg_position">
			<label class="options_title">{l s='Background position:' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_position_tl"><input type="radio" name="angarconfig_input4" value="bg_position_tl" {if $css4 == bg_position_tl}checked{/if}>{l s='Top left' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_position_tc"><input type="radio" name="angarconfig_input4" value="bg_position_tc" {if $css4 == bg_position_tc}checked{/if}>{l s='Top center' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_position_tr"><input type="radio" name="angarconfig_input4" value="bg_position_tr" {if $css4 == bg_position_tr}checked{/if}>{l s='Top right' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_position_cl"><input type="radio" name="angarconfig_input4" value="bg_position_cl" {if $css4 == bg_position_cl}checked{/if}>{l s='Center left' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_position_cc"><input type="radio" name="angarconfig_input4" value="bg_position_cc" {if $css4 == bg_position_cc}checked{/if}>{l s='Center center' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_position_cr"><input type="radio" name="angarconfig_input4" value="bg_position_cr" {if $css4 == bg_position_cr}checked{/if}>{l s='Center right' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_position_bl"><input type="radio" name="angarconfig_input4" value="bg_position_bl" {if $css4 == bg_position_bl}checked{/if}>{l s='Bottom left' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_position_bc"><input type="radio" name="angarconfig_input4" value="bg_position_bc" {if $css4 == bg_position_bc}checked{/if}>{l s='Bottom center' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_position_br"><input type="radio" name="angarconfig_input4" value="bg_position_br" {if $css4 == bg_position_br}checked{/if}>{l s='Bottom right' mod='angarthemeconfigurator'}</label>
		</div>	

		<div class="bg_repeat">
			<label class="options_title">{l s='Background repeat:' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_repeat_no"><input type="radio" name="angarconfig_input5" value="bg_repeat_no" {if $css5 == bg_repeat_no}checked{/if}>{l s='No repeat' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_repeat_x"><input type="radio" name="angarconfig_input5" value="bg_repeat_x" {if $css5 == bg_repeat_x}checked{/if}>{l s='Repeat in axis x' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_repeat_y"><input type="radio" name="angarconfig_input5" value="bg_repeat_y" {if $css5 == bg_repeat_y}checked{/if}>{l s='Repeat in axis y' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_repeat_xy"><input type="radio" name="angarconfig_input5" value="bg_repeat_xy" {if $css5 == bg_repeat_xy}checked{/if}>{l s='Repeat in axis x and y' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="bg_size">
			<label class="options_title">{l s='Background size:' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_size_initial"><input type="radio" name="angarconfig_input6" value="bg_size_initial" {if $css6 == bg_size_initial}checked{/if}>{l s='No scaling' mod='angarthemeconfigurator'}</label></br>
			<label class="bg_size_cover"><input type="radio" name="angarconfig_input6" value="bg_size_cover" {if $css6 == bg_size_cover}checked{/if}>{l s='Scale to 100%' mod='angarthemeconfigurator'}</label>
		</div>

	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box2">{l s='Top' mod='angarthemeconfigurator'}</div>
	<div id="customization_box2" class="collapse customization_container">	

		<div class="header_sep">
			<label class="options_title">{l s='Top separators:' mod='angarthemeconfigurator'}</label></br>
			<label class="header_sep0"><input type="radio" name="angarconfig_input42" value="header_sep0" {if $css42 == header_sep0}checked{/if}>{l s='No separators' mod='angarthemeconfigurator'}</label></br>
			<label class="header_sep1"><input type="radio" name="angarconfig_input42" value="header_sep1" {if $css42 == header_sep1}checked{/if}>{l s='Border bottom' mod='angarthemeconfigurator'}</label></br>
			<label class="header_sep2"><input type="radio" name="angarconfig_input42" value="header_sep2" {if $css42 == header_sep2}checked{/if}>{l s='Separators + border' mod='angarthemeconfigurator'}</label></br>
		</div>

	</div>








{*
	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box3">{l s='Header style' mod='angarthemeconfigurator'}</div>
	<div id="customization_box3" class="collapse customization_container">

		<div class="header_style">
			<label class="options_title">{l s='Header style:' mod='angarthemeconfigurator'}</label></br>
			<label class="header_style1"><input type="radio" name="angarconfig_input64" value="header_style1" {if $css64 == header_style1}checked{/if}>{l s='Header style 1' mod='angarthemeconfigurator'}</label></br>
			<label class="header_style2"><input type="radio" name="angarconfig_input64" value="header_style2" {if $css64 == header_style2}checked{/if}>{l s='Header style 2' mod='angarthemeconfigurator'}</label>
		</div>

	</div>
*}








	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box4">{l s='Logo' mod='angarthemeconfigurator'}</div>
	<div id="customization_box4" class="collapse customization_container">

		<div id="logo_top_comp">
			<div class="options_title">{l s='Logo margin-top:' mod='angarthemeconfigurator'} <span class="value">{$css44|escape:'htmlall':'UTF-8'}</span> <span>px</span></div>
			<input type="range" name="angarconfig_input44" min="0" max="40" value="{$css44|escape:'htmlall':'UTF-8'}">
		</div>

		<div id="logo_bottom_comp">
			<div class="options_title">{l s='Logo margin-bottom:' mod='angarthemeconfigurator'} <span class="value">{$css60|escape:'htmlall':'UTF-8'}</span> <span>px</span></div>
			<input type="range" name="angarconfig_input60" min="0" max="40" value="{$css60|escape:'htmlall':'UTF-8'}">
		</div>

	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box5">{l s='Cart' mod='angarthemeconfigurator'}</div>
	<div id="customization_box5" class="collapse customization_container">	

{*
		<div class="cart_style">
			<label class="options_title">{l s='Cart style:' mod='angarthemeconfigurator'}</label></br>
			<label class="cart_style1"><input type="radio" name="angarconfig_input62" value="cart_style1" {if $css62 == cart_style1}checked{/if}>{l s='Cart style 1' mod='angarthemeconfigurator'}</label></br>
			<label class="cart_style2"><input type="radio" name="angarconfig_input62" value="cart_style2" {if $css62 == cart_style2}checked{/if}>{l s='Cart style 2' mod='angarthemeconfigurator'}</label>
		</div>
*}

		<div class="cart_sticky">
			<label class="options_title">{l s='Sticky cart:' mod='angarthemeconfigurator'}</label></br>
			<label class="stickycart_yes"><input type="radio" name="angarconfig_input53" value="stickycart_yes" {if $css53 == stickycart_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="stickycart_no"><input type="radio" name="angarconfig_input53" value="stickycart_no" {if $css53 == stickycart_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box6">{l s='Menu ' mod='angarthemeconfigurator'}</div>
	<div id="customization_box6" class="collapse customization_container">	

		<div class="menu_sep">
			<label class="options_title">{l s='Menu separators:' mod='angarthemeconfigurator'}</label></br>
			<label class="menu_sep0"><input type="radio" name="angarconfig_input41" value="menu_sep0" {if $css41 == menu_sep0}checked{/if}>{l s='No borders and separators' mod='angarthemeconfigurator'}</label></br>
			<label class="menu_sep1"><input type="radio" name="angarconfig_input41" value="menu_sep1" {if $css41 == menu_sep1}checked{/if}>{l s='Separators' mod='angarthemeconfigurator'}</label></br>
			<label class="menu_sep2"><input type="radio" name="angarconfig_input41" value="menu_sep2" {if $css41 == menu_sep2}checked{/if}>{l s='Separators + border top/bottom' mod='angarthemeconfigurator'}</label></br>
			<label class="menu_sep3"><input type="radio" name="angarconfig_input41" value="menu_sep3" {if $css41 == menu_sep3}checked{/if}>{l s='Border top/bottom 1px' mod='angarthemeconfigurator'}</label></br>
			<label class="menu_sep4"><input type="radio" name="angarconfig_input41" value="menu_sep4" {if $css41 == menu_sep4}checked{/if}>{l s='Border top/bottom 2px' mod='angarthemeconfigurator'}</label></br>
			<label class="menu_sep5"><input type="radio" name="angarconfig_input41" value="menu_sep5" {if $css41 == menu_sep5}checked{/if}>{l s='Border bottom 1px' mod='angarthemeconfigurator'}</label></br>
			<label class="menu_sep6"><input type="radio" name="angarconfig_input41" value="menu_sep6" {if $css41 == menu_sep6}checked{/if}>{l s='Border bottom 2px' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="submenu_type">
			<label class="options_title">{l s='Submenu layout:' mod='angarthemeconfigurator'}</label></br>
			<label class="submenu1"><input type="radio" name="angarconfig_input15" value="submenu1" {if $css15 == submenu1}checked{/if}>{l s='Submenu layout 1' mod='angarthemeconfigurator'}</label></br>
			<label class="submenu2"><input type="radio" name="angarconfig_input15" value="submenu2" {if $css15 == submenu2}checked{/if}>{l s='Submenu layout 2' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="menu_sticky">
			<label class="options_title">{l s='Sticky menu:' mod='angarthemeconfigurator'}</label></br>
			<label class="stickymenu_yes"><input type="radio" name="angarconfig_input54" value="stickymenu_yes" {if $css54 == stickymenu_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="stickymenu_no"><input type="radio" name="angarconfig_input54" value="stickymenu_no" {if $css54 == stickymenu_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="menu_homeicon">
			<label class="options_title">{l s='Hide menu home icon:' mod='angarthemeconfigurator'}</label></br>
			<label class="homeicon_yes"><input type="radio" name="angarconfig_input55" value="homeicon_yes" {if $css55 == homeicon_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="homeicon_no"><input type="radio" name="angarconfig_input55" value="homeicon_no" {if $css55 == homeicon_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box7">{l s='Slider' mod='angarthemeconfigurator'}</div>
	<div id="customization_box7" class="collapse customization_container">	
		
		<div class="must_save"><sup>*</sup> {l s='- You must save this option to see changes' mod='angarthemeconfigurator'}</div>

		<div class="slider_position">
			<label class="options_title">{l s='Slider position:' mod='angarthemeconfigurator'} <sup>*</sup></label></br>
			<label class="slider_position_top"><input type="radio" name="angarconfig_input7" value="slider_position_top" {if $css7 == slider_position_top}checked{/if}>{l s='Slider under menu' mod='angarthemeconfigurator'}</label></br>
			<label class="slider_position_column"><input type="radio" name="angarconfig_input7" value="slider_position_column" {if $css7 == slider_position_column}checked{/if}>{l s='Slider in column' mod='angarthemeconfigurator'}</label></br>
		</div>	

		<div class="slider_size">
			<label class="options_title">{l s='Slider width:' mod='angarthemeconfigurator'} <sup>*</sup></label></br>
			<label class="slider_boxed"><input type="radio" name="angarconfig_input43" value="slider_boxed" {if $css43 == slider_boxed}checked{/if}>{l s='Slider max width 1170px' mod='angarthemeconfigurator'}</label></br>
			<label class="slider_full_width"><input type="radio" name="angarconfig_input43" value="slider_full_width" {if $css43 == slider_full_width}checked{/if}>{l s='Slider full width' mod='angarthemeconfigurator'}</label></br>
		</div>	

		<div class="slider_controls">
			<label class="options_title">{l s='Slider controls color:' mod='angarthemeconfigurator'}</label></br>
			<label class="slider_controls_white"><input type="radio" name="angarconfig_input8" value="slider_controls_white" {if $css8 == slider_controls_white}checked{/if}>{l s='White' mod='angarthemeconfigurator'}</label></br>
			<label class="slider_controls_black"><input type="radio" name="angarconfig_input8" value="slider_controls_black" {if $css8 == slider_controls_black}checked{/if}>{l s='Black' mod='angarthemeconfigurator'}</label></br>
		</div>

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box8">{l s='Banners top' mod='angarthemeconfigurator'}</div>
	<div id="customization_box8" class="collapse customization_container">

		<div id="banners_top">
			<label class="options_title">{l s='Banners per row (computers):' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top1"><input type="radio" name="angarconfig_input9" value="banners_top1" {if $css9 == banners_top1}checked{/if}>{l s='1 banner per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top2"><input type="radio" name="angarconfig_input9" value="banners_top2" {if $css9 == banners_top2}checked{/if}>{l s='2 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top3"><input type="radio" name="angarconfig_input9" value="banners_top3" {if $css9 == banners_top3}checked{/if}>{l s='3 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top4"><input type="radio" name="angarconfig_input9" value="banners_top4" {if $css9 == banners_top4}checked{/if}>{l s='4 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top5"><input type="radio" name="angarconfig_input9" value="banners_top5" {if $css9 == banners_top5}checked{/if}>{l s='5 banners per row' mod='angarthemeconfigurator'}</label></br>
		</div>

		<div id="banners_top_tablets">
			<label class="options_title">{l s='Banners per row (tablets):' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_tablets0"><input type="radio" name="angarconfig_input10" value="banners_top_tablets0" {if $css10 == banners_top_tablets0}checked{/if}>{l s='Hide' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_tablets1"><input type="radio" name="angarconfig_input10" value="banners_top_tablets1" {if $css10 == banners_top_tablets1}checked{/if}>{l s='1 banner per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_tablets2"><input type="radio" name="angarconfig_input10" value="banners_top_tablets2" {if $css10 == banners_top_tablets2}checked{/if}>{l s='2 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_tablets3"><input type="radio" name="angarconfig_input10" value="banners_top_tablets3" {if $css10 == banners_top_tablets3}checked{/if}>{l s='3 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_tablets4"><input type="radio" name="angarconfig_input10" value="banners_top_tablets4" {if $css10 == banners_top_tablets4}checked{/if}>{l s='4 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_tablets5"><input type="radio" name="angarconfig_input10" value="banners_top_tablets5" {if $css10 == banners_top_tablets5}checked{/if}>{l s='5 banners per row' mod='angarthemeconfigurator'}</label></br>
		</div>

		<div id="banners_top_phones">
			<label class="options_title">{l s='Banners per row (phones):' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_phones0"><input type="radio" name="angarconfig_input11" value="banners_top_phones0" {if $css11 == banners_top_phones0}checked{/if}>{l s='Hide' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_phones1"><input type="radio" name="angarconfig_input11" value="banners_top_phones1" {if $css11 == banners_top_phones1}checked{/if}>{l s='1 banner per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_phones2"><input type="radio" name="angarconfig_input11" value="banners_top_phones2" {if $css11 == banners_top_phones2}checked{/if}>{l s='2 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_phones3"><input type="radio" name="angarconfig_input11" value="banners_top_phones3" {if $css11 == banners_top_phones3}checked{/if}>{l s='3 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_phones4"><input type="radio" name="angarconfig_input11" value="banners_top_phones4" {if $css11 == banners_top_phones4}checked{/if}>{l s='4 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_top_phones5"><input type="radio" name="angarconfig_input11" value="banners_top_phones5" {if $css11 == banners_top5}checked{/if}>{l s='5 banners per row' mod='angarthemeconfigurator'}</label></br>
		</div>

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box9">{l s='Banners bottom' mod='angarthemeconfigurator'}</div>
	<div id="customization_box9" class="collapse customization_container">

		<div id="banners_bottom">
			<label class="options_title">{l s='Banners per row (computers):' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom1"><input type="radio" name="angarconfig_input12" value="banners_bottom1" {if $css12 == banners_bottom1}checked{/if}>{l s='1 banner per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom2"><input type="radio" name="angarconfig_input12" value="banners_bottom2" {if $css12 == banners_bottom2}checked{/if}>{l s='2 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom3"><input type="radio" name="angarconfig_input12" value="banners_bottom3" {if $css12 == banners_bottom3}checked{/if}>{l s='3 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom4"><input type="radio" name="angarconfig_input12" value="banners_bottom4" {if $css12 == banners_bottom4}checked{/if}>{l s='4 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom5"><input type="radio" name="angarconfig_input12" value="banners_bottom5" {if $css12 == banners_bottom5}checked{/if}>{l s='5 banners per row' mod='angarthemeconfigurator'}</label></br>		
		</div>

		<div id="banners_bottom_tablets">
			<label class="options_title">{l s='Banners per row (tablets):' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_tablets0"><input type="radio" name="angarconfig_input13" value="banners_bottom_tablets0" {if $css13 == banners_bottom_tablets0}checked{/if}>{l s='Hide' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_tablets1"><input type="radio" name="angarconfig_input13" value="banners_bottom_tablets1" {if $css13 == banners_bottom_tablets1}checked{/if}>{l s='1 banner per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_tablets2"><input type="radio" name="angarconfig_input13" value="banners_bottom_tablets2" {if $css13 == banners_bottom_tablets2}checked{/if}>{l s='2 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_tablets3"><input type="radio" name="angarconfig_input13" value="banners_bottom_tablets3" {if $css13 == banners_bottom_tablets3}checked{/if}>{l s='3 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_tablets4"><input type="radio" name="angarconfig_input13" value="banners_bottom_tablets4" {if $css13 == banners_bottom_tablets4}checked{/if}>{l s='4 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_tablets5"><input type="radio" name="angarconfig_input13" value="banners_bottom_tablets5" {if $css13 == banners_bottom_tablets5}checked{/if}>{l s='5 banners per row' mod='angarthemeconfigurator'}</label></br>
		</div>

		<div id="banners_bottom_phones">
			<label class="options_title">{l s='Banners per row (phones):' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_phones0"><input type="radio" name="angarconfig_input14" value="banners_bottom_phones0" {if $css14 == banners_bottom_phones0}checked{/if}>{l s='Hide' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_phones1"><input type="radio" name="angarconfig_input14" value="banners_bottom_phones1" {if $css14 == banners_bottom_phones1}checked{/if}>{l s='1 banner per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_phones2"><input type="radio" name="angarconfig_input14" value="banners_bottom_phones2" {if $css14 == banners_bottom_phones2}checked{/if}>{l s='2 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_phones3"><input type="radio" name="angarconfig_input14" value="banners_bottom_phones3" {if $css14 == banners_bottom_phones3}checked{/if}>{l s='3 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_phones4"><input type="radio" name="angarconfig_input14" value="banners_bottom_phones4" {if $css14 == banners_bottom_phones4}checked{/if}>{l s='4 banners per row' mod='angarthemeconfigurator'}</label></br>
			<label class="banners_bottom_phones5"><input type="radio" name="angarconfig_input14" value="banners_bottom_phones5" {if $css14 == banners_bottom5}checked{/if}>{l s='5 banners per row' mod='angarthemeconfigurator'}</label></br>
		</div>

	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box10">{l s='Featured categories' mod='angarthemeconfigurator'}</div>
	<div id="customization_box10" class="collapse customization_container">


		<div id="featcat_height">
			<div class="options_title">{l s='Featured categories min-height:' mod='angarthemeconfigurator'} <span class="value">{$css56|escape:'htmlall':'UTF-8'}</span> <span>px</span></div>
			<input type="range" name="angarconfig_input56" min="0" max="500" value="{$css56|escape:'htmlall':'UTF-8'}">
		</div>

		<div id="feat_cat_style">
			<label class="options_title">{l s='Featured categories style:' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_style1"><input type="radio" name="angarconfig_input45" value="feat_cat_style1" {if $css45 == feat_cat_style1}checked{/if}>{l s='Style 1 ' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_style2"><input type="radio" name="angarconfig_input45" value="feat_cat_style2" {if $css45 == feat_cat_style2}checked{/if}>{l s='Style 2 ' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_style3"><input type="radio" name="angarconfig_input45" value="feat_cat_style3" {if $css45 == feat_cat_style3}checked{/if}>{l s='Style 3' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_style4"><input type="radio" name="angarconfig_input45" value="feat_cat_style4" {if $css45 == feat_cat_style4}checked{/if}>{l s='Style 4' mod='angarthemeconfigurator'}</label></br>
		</div>

		<div id="feat_cat">
			<label class="options_title">{l s='Featured categories per row (computers):' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat2"><input type="radio" name="angarconfig_input46" value="feat_cat2" {if $css46 == feat_cat2}checked{/if}>{l s='2 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat3"><input type="radio" name="angarconfig_input46" value="feat_cat3" {if $css46 == feat_cat3}checked{/if}>{l s='3 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat4"><input type="radio" name="angarconfig_input46" value="feat_cat4" {if $css46 == feat_cat4}checked{/if}>{l s='4 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat5"><input type="radio" name="angarconfig_input46" value="feat_cat5" {if $css46 == feat_cat5}checked{/if}>{l s='5 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat6"><input type="radio" name="angarconfig_input46" value="feat_cat6" {if $css46 == feat_cat6}checked{/if}>{l s='6 categories per row' mod='angarthemeconfigurator'}</label></br>	
		</div>

		<div id="feat_cat_bigtablets">
			<label class="options_title">{l s='Featured categories per row (big tablets):' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_bigtablets0"><input type="radio" name="angarconfig_input47" value="feat_cat_bigtablets0" {if $css47 == feat_cat_bigtablets0}checked{/if}>{l s='Hide' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_bigtablets1"><input type="radio" name="angarconfig_input47" value="feat_cat_bigtablets1" {if $css47 == feat_cat_bigtablets1}checked{/if}>{l s='1 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_bigtablets2"><input type="radio" name="angarconfig_input47" value="feat_cat_bigtablets2" {if $css47 == feat_cat_bigtablets2}checked{/if}>{l s='2 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_bigtablets3"><input type="radio" name="angarconfig_input47" value="feat_cat_bigtablets3" {if $css47 == feat_cat_bigtablets3}checked{/if}>{l s='3 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_bigtablets4"><input type="radio" name="angarconfig_input47" value="feat_cat_bigtablets4" {if $css47 == feat_cat_bigtablets4}checked{/if}>{l s='4 categories per row' mod='angarthemeconfigurator'}</label></br>
		</div>

		<div id="feat_cat_tablets">
			<label class="options_title">{l s='Featured categories per row (tablets):' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_tablets0"><input type="radio" name="angarconfig_input48" value="feat_cat_tablets0" {if $css48 == feat_cat_tablets0}checked{/if}>{l s='Hide' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_tablets1"><input type="radio" name="angarconfig_input48" value="feat_cat_tablets1" {if $css48 == feat_cat_tablets1}checked{/if}>{l s='1 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_tablets2"><input type="radio" name="angarconfig_input48" value="feat_cat_tablets2" {if $css48 == feat_cat_tablets2}checked{/if}>{l s='2 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_tablets3"><input type="radio" name="angarconfig_input48" value="feat_cat_tablets3" {if $css48 == feat_cat_tablets3}checked{/if}>{l s='3 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_tablets4"><input type="radio" name="angarconfig_input48" value="feat_cat_tablets4" {if $css48 == feat_cat_tablets4}checked{/if}>{l s='4 categories per row' mod='angarthemeconfigurator'}</label></br>
		</div>

		<div id="feat_cat_phones">
			<label class="options_title">{l s='Featured categories per row (phones):' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_phones0"><input type="radio" name="angarconfig_input49" value="feat_cat_phones0" {if $css49 == feat_cat_phones0}checked{/if}>{l s='Hide' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_phones1"><input type="radio" name="angarconfig_input49" value="feat_cat_phones1" {if $css49 == feat_cat_phones1}checked{/if}>{l s='1 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_phones2"><input type="radio" name="angarconfig_input49" value="feat_cat_phones2" {if $css49 == feat_cat_phones2}checked{/if}>{l s='2 categories per row' mod='angarthemeconfigurator'}</label></br>
			<label class="feat_cat_phones3"><input type="radio" name="angarconfig_input49" value="feat_cat_phones3" {if $css49 == feat_cat_phones3}checked{/if}>{l s='3 categories per row' mod='angarthemeconfigurator'}</label></br>
		</div>

	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box11">{l s='Homepage products layout' mod='angarthemeconfigurator'}</div>
	<div id="customization_box11" class="collapse customization_container">	

		<div class="home_tabs">
			<label class="options_title">{l s='Homepage products in tabs or boxes:' mod='angarthemeconfigurator'}</label></br>
			<label class="home_tabs1"><input type="radio" name="angarconfig_input24" value="home_tabs1" {if $css24 == home_tabs1}checked{/if}>{l s='Tabs' mod='angarthemeconfigurator'}</label></br>
			<label class="home_tabs2"><input type="radio" name="angarconfig_input24" value="home_tabs2" {if $css24 == home_tabs2}checked{/if}>{l s='Boxes' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="all_products">
			<label class="options_title">{l s='Hide link "All products":' mod='angarthemeconfigurator'}</label></br>
			<label class="all_products_yes"><input type="radio" name="angarconfig_input50" value="all_products_yes" {if $css50 == all_products_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="all_products_no"><input type="radio" name="angarconfig_input50" value="all_products_no" {if $css50 == all_products_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box12">{l s='Products per row' mod='angarthemeconfigurator'}</div>
	<div id="customization_box12" class="collapse customization_container">	

		<div class="pl_1col_qty">
			<label class="options_title">{l s='Product per row in one column layout (computers):' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_2"><input type="radio" name="angarconfig_input16" value="pl_1col_qty_2" {if $css16 == pl_1col_qty_2}checked{/if}>{l s='2 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_3"><input type="radio" name="angarconfig_input16" value="pl_1col_qty_3" {if $css16 == pl_1col_qty_3}checked{/if}>{l s='3 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_4"><input type="radio" name="angarconfig_input16" value="pl_1col_qty_4" {if $css16 == pl_1col_qty_4}checked{/if}>{l s='4 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_5"><input type="radio" name="angarconfig_input16" value="pl_1col_qty_5" {if $css16 == pl_1col_qty_5}checked{/if}>{l s='5 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_6"><input type="radio" name="angarconfig_input16" value="pl_1col_qty_6" {if $css16 == pl_1col_qty_6}checked{/if}>{l s='6 products per row' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="pl_2col_qty">
			<label class="options_title">{l s='Product per row in two column layout (computers):' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_2col_qty_2"><input type="radio" name="angarconfig_input17" value="pl_2col_qty_2" {if $css17 == pl_2col_qty_2}checked{/if}>{l s='2 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_2col_qty_3"><input type="radio" name="angarconfig_input17" value="pl_2col_qty_3" {if $css17 == pl_2col_qty_3}checked{/if}>{l s='3 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_2col_qty_4"><input type="radio" name="angarconfig_input17" value="pl_2col_qty_4" {if $css17 == pl_2col_qty_4}checked{/if}>{l s='4 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_2col_qty_5"><input type="radio" name="angarconfig_input17" value="pl_2col_qty_5" {if $css17 == pl_2col_qty_5}checked{/if}>{l s='5 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_2col_qty_6"><input type="radio" name="angarconfig_input17" value="pl_2col_qty_6" {if $css17 == pl_2col_qty_6}checked{/if}>{l s='6 products per row' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="pl_3col_qty">
			<label class="options_title">{l s='Product per row in three column layout (computers):' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_3col_qty_2"><input type="radio" name="angarconfig_input18" value="pl_3col_qty_2" {if $css18 == pl_3col_qty_2}checked{/if}>{l s='2 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_3col_qty_3"><input type="radio" name="angarconfig_input18" value="pl_3col_qty_3" {if $css18 == pl_3col_qty_3}checked{/if}>{l s='3 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_3col_qty_4"><input type="radio" name="angarconfig_input18" value="pl_3col_qty_4" {if $css18 == pl_3col_qty_4}checked{/if}>{l s='4 products per row' mod='angarthemeconfigurator'}</label></br>
			{*
			<label class="pl_3col_qty_5"><input type="radio" name="angarconfig_input18" value="pl_3col_qty_5" {if $css18 == pl_3col_qty_5}checked{/if}>{l s='5 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_3col_qty_6"><input type="radio" name="angarconfig_input18" value="pl_3col_qty_6" {if $css18 == pl_3col_qty_6}checked{/if}>{l s='6 products per row' mod='angarthemeconfigurator'}</label>
			*}
	</div>

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box13">{l s='Products per row on tablets and phones' mod='angarthemeconfigurator'}</div>
	<div id="customization_box13" class="collapse customization_container">	

		<div class="pl_1col_qty_bigtablets">
			<label class="options_title">{l s='Product per row in one column layout (big tablets):' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_bigtablets_2"><input type="radio" name="angarconfig_input19" value="pl_1col_qty_bigtablets_2" {if $css19 == pl_1col_qty_bigtablets_2}checked{/if}>{l s='2 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_bigtablets_3"><input type="radio" name="angarconfig_input19" value="pl_1col_qty_bigtablets_3" {if $css19 == pl_1col_qty_bigtablets_3}checked{/if}>{l s='3 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_bigtablets_4"><input type="radio" name="angarconfig_input19" value="pl_1col_qty_bigtablets_4" {if $css19 == pl_1col_qty_bigtablets_4}checked{/if}>{l s='4 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_bigtablets_5"><input type="radio" name="angarconfig_input19" value="pl_1col_qty_bigtablets_5" {if $css19 == pl_1col_qty_bigtablets_5}checked{/if}>{l s='5 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_bigtablets_6"><input type="radio" name="angarconfig_input19" value="pl_1col_qty_bigtablets_6" {if $css19 == pl_1col_qty_bigtablets_6}checked{/if}>{l s='6 products per row' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="pl_2col_qty_bigtablets">
			<label class="options_title">{l s='Product per row in two column layout (big tablets):' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_2col_qty_bigtablets_2"><input type="radio" name="angarconfig_input20" value="pl_2col_qty_bigtablets_2" {if $css20 == pl_2col_qty_bigtablets_2}checked{/if}>{l s='2 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_2col_qty_bigtablets_3"><input type="radio" name="angarconfig_input20" value="pl_2col_qty_bigtablets_3" {if $css20 == pl_2col_qty_bigtablets_3}checked{/if}>{l s='3 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_2col_qty_bigtablets_4"><input type="radio" name="angarconfig_input20" value="pl_2col_qty_bigtablets_4" {if $css20 == pl_2col_qty_bigtablets_4}checked{/if}>{l s='4 products per row' mod='angarthemeconfigurator'}</label></br>
			{*
			<label class="pl_2col_qty_bigtablets_5"><input type="radio" name="angarconfig_input20" value="pl_2col_qty_bigtablets_5" {if $css20 == pl_2col_qty_bigtablets_5}checked{/if}>{l s='5 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_2col_qty_bigtablets_6"><input type="radio" name="angarconfig_input20" value="pl_2col_qty_bigtablets_6" {if $css20 == pl_2col_qty_bigtablets_6}checked{/if}>{l s='6 products per row' mod='angarthemeconfigurator'}</label>
			*}
		</div>

		<div class="pl_3col_qty_bigtablets">
			<label class="options_title">{l s='Product per row in three column layout (big tablets):' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_3col_qty_bigtablets_2"><input type="radio" name="angarconfig_input21" value="pl_3col_qty_bigtablets_2" {if $css21 == pl_3col_qty_bigtablets_2}checked{/if}>{l s='2 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_3col_qty_bigtablets_3"><input type="radio" name="angarconfig_input21" value="pl_3col_qty_bigtablets_3" {if $css21 == pl_3col_qty_bigtablets_3}checked{/if}>{l s='3 products per row' mod='angarthemeconfigurator'}</label></br>
			{*
			<label class="pl_3col_qty_bigtablets_4"><input type="radio" name="angarconfig_input21" value="pl_3col_qty_bigtablets_4" {if $css21 == pl_3col_qty_bigtablets_4}checked{/if}>{l s='4 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_3col_qty_bigtablets_5"><input type="radio" name="angarconfig_input21" value="pl_3col_qty_bigtablets_5" {if $css21 == pl_3col_qty_bigtablets_5}checked{/if}>{l s='5 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_3col_qty_bigtablets_6"><input type="radio" name="angarconfig_input21" value="pl_3col_qty_bigtablets_6" {if $css21 == pl_3col_qty_bigtablets_6}checked{/if}>{l s='6 products per row' mod='angarthemeconfigurator'}</label>
			*}
		</div>

		<div class="pl_1col_qty_tablets">
			<label class="options_title">{l s='Product per row (tablets):' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_tablets_2"><input type="radio" name="angarconfig_input22" value="pl_1col_qty_tablets_2" {if $css22 == pl_1col_qty_tablets_2}checked{/if}>{l s='2 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_tablets_3"><input type="radio" name="angarconfig_input22" value="pl_1col_qty_tablets_3" {if $css22 == pl_1col_qty_tablets_3}checked{/if}>{l s='3 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_tablets_4"><input type="radio" name="angarconfig_input22" value="pl_1col_qty_tablets_4" {if $css22 == pl_1col_qty_tablets_4}checked{/if}>{l s='4 products per row' mod='angarthemeconfigurator'}</label></br>
			{*
			<label class="pl_1col_qty_tablets_5"><input type="radio" name="angarconfig_input22" value="pl_1col_qty_tablets_5" {if $css22 == pl_1col_qty_tablets_5}checked{/if}>{l s='5 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_tablets_6"><input type="radio" name="angarconfig_input22" value="pl_1col_qty_tablets_6" {if $css22 == pl_1col_qty_tablets_6}checked{/if}>{l s='6 products per row' mod='angarthemeconfigurator'}</label>
			*}
		</div>

		<div class="pl_1col_qty_phones">
			<label class="options_title">{l s='Product per row (phones):' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_phones_1"><input type="radio" name="angarconfig_input23" value="pl_1col_qty_phones_1" {if $css23 == pl_1col_qty_phones_1}checked{/if}>{l s='1 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_phones_2"><input type="radio" name="angarconfig_input23" value="pl_1col_qty_phones_2" {if $css23 == pl_1col_qty_phones_2}checked{/if}>{l s='2 products per row' mod='angarthemeconfigurator'}</label></br>
			{*
			<label class="pl_1col_qty_phones_3"><input type="radio" name="angarconfig_input23" value="pl_1col_qty_phones_3" {if $css23 == pl_1col_qty_phones_3}checked{/if}>{l s='3 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_phones_4"><input type="radio" name="angarconfig_input23" value="pl_1col_qty_phones_4" {if $css23 == pl_1col_qty_phones_4}checked{/if}>{l s='4 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_phones_5"><input type="radio" name="angarconfig_input23" value="pl_1col_qty_phones_5" {if $css23 == pl_1col_qty_phones_5}checked{/if}>{l s='5 products per row' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_1col_qty_phones_6"><input type="radio" name="angarconfig_input23" value="pl_1col_qty_phones_6" {if $css23 == pl_1col_qty_phones_6}checked{/if}>{l s='6 products per row' mod='angarthemeconfigurator'}</label>
			*}
		</div>

	</div>











	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box14">{l s='Product-list options' mod='angarthemeconfigurator'}</div>
	<div id="customization_box14" class="collapse customization_container">

		<div class="pl_border_type">
			<label class="options_title">{l s='Product-list border style:' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_border_type1"><input type="radio" name="angarconfig_input25" value="pl_border_type1" {if $css25 == pl_border_type1}checked{/if}>{l s='Style 1' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_border_type2"><input type="radio" name="angarconfig_input25" value="pl_border_type2" {if $css25 == pl_border_type2}checked{/if}>{l s='Style 2' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_border_type3"><input type="radio" name="angarconfig_input25" value="pl_border_type3" {if $css25 == pl_border_type3}checked{/if}>{l s='Style 3' mod='angarthemeconfigurator'}</label>
		</div>

		<div id="pl_name_height">
			<div class="options_title">{l s='Product-list name height:' mod='angarthemeconfigurator'} <span class="value">{$css26|escape:'htmlall':'UTF-8'}</span> <span>px</span></div>
			<input type="range" name="angarconfig_input26" min="16" max="80" value="{$css26|escape:'htmlall':'UTF-8'}">
		</div>

		<div id="pl_name_fs">
			<div class="options_title">{l s='Product-list name font-size:' mod='angarthemeconfigurator'} <span class="value">{$css27|escape:'htmlall':'UTF-8'}</span> <span>px</span></div>
			<input type="range" name="angarconfig_input27" min="12" max="22" value="{$css27|escape:'htmlall':'UTF-8'}">
		</div>

		<div id="pl_name_lh">
			<div class="options_title">{l s='Product-list name line-height:' mod='angarthemeconfigurator'} <span class="value">{$css28|escape:'htmlall':'UTF-8'}</span> <span>px</span></div>
			<input type="range" name="angarconfig_input28" min="12" max="22" value="{$css28|escape:'htmlall':'UTF-8'}">
		</div>

		<div class="pl_ref">
			<label class="options_title">{l s='Hide reference code:' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_ref_yes"><input type="radio" name="angarconfig_input63" value="pl_ref_yes" {if $css63 == pl_ref_yes}checked{/if}>{l s='Yes (the reference code is always hidden)' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_ref_no"><input type="radio" name="angarconfig_input63" value="pl_ref_no" {if $css63 == pl_ref_no}checked{/if}>{l s='No (the reference code is displayed, if available)' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_ref_empty_line"><input type="radio" name="angarconfig_input63" value="pl_ref_empty_line" {if $css63 == pl_ref_empty_line}checked{/if}>{l s='No (if the reference code is not available, an empty line will be displayed)' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="pl_man">
			<label class="options_title">{l s='Hide manufacturer name:' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_man_yes"><input type="radio" name="angarconfig_input57" value="pl_man_yes" {if $css57 == pl_man_yes}checked{/if}>{l s='Yes (the manufacturer name is always hidden)' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_man_no"><input type="radio" name="angarconfig_input57" value="pl_man_no" {if $css57 == pl_man_no}checked{/if}>{l s='No (the manufacturer name is displayed, if available)' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_man_empty_line"><input type="radio" name="angarconfig_input57" value="pl_man_empty_line" {if $css57 == pl_man_empty_line}checked{/if}>{l s='No (if the manufacturer name is not available, an empty line will be displayed)' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="pl_reviews">
			<label class="options_title">{l s='Hide reviews and stars:' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_reviews_yes"><input type="radio" name="angarconfig_input32" value="pl_reviews_yes" {if $css32 == pl_reviews_yes}checked{/if}>{l s='Yes (hide stars + text)' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_reviews_hide_text"><input type="radio" name="angarconfig_input32" value="pl_reviews_hide_text" {if $css32 == pl_reviews_hide_text}checked{/if}>{l s='Yes (hide only text)' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_reviews_no"><input type="radio" name="angarconfig_input32" value="pl_reviews_no" {if $css32 == pl_reviews_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="pl_desc">
			<label class="options_title">{l s='Hide description in product-list:' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_desc_yes"><input type="radio" name="angarconfig_input31" value="pl_desc_yes" {if $css31 == pl_desc_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_desc_no"><input type="radio" name="angarconfig_input31" value="pl_desc_no" {if $css31 == pl_desc_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

		<div id="pl_desc_height">
			<div class="options_title">{l s='Product-list description height:' mod='angarthemeconfigurator'} <span class="value">{$css61|escape:'htmlall':'UTF-8'}</span> <span>px</span></div>
			<input type="range" name="angarconfig_input61" min="0" max="140" value="{$css61|escape:'htmlall':'UTF-8'}">
		</div>

		<div class="pl_button_qty">
			<label class="options_title">{l s='Product-list button qty:' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_button_qty1"><input type="radio" name="angarconfig_input30" value="pl_button_qty1" {if $css30 == pl_button_qty1}checked{/if}>{l s='2 buttons' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_button_qty2"><input type="radio" name="angarconfig_input30" value="pl_button_qty2" {if $css30 == pl_button_qty2}checked{/if}>{l s='Only "Add to cart"' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_button_qty3"><input type="radio" name="angarconfig_input30" value="pl_button_qty3" {if $css30 == pl_button_qty3}checked{/if}>{l s='Only view button' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_button_qty4"><input type="radio" name="angarconfig_input30" value="pl_button_qty4" {if $css30 == pl_button_qty4}checked{/if}>{l s='Hide buttons' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="pl_button_icon">
			<label class="options_title">{l s='Hide cart icon in button:' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_button_icon_yes"><input type="radio" name="angarconfig_input29" value="pl_button_icon_yes" {if $css29 == pl_button_icon_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_button_icon_no"><input type="radio" name="angarconfig_input29" value="pl_button_icon_no" {if $css29 == pl_button_icon_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="pl_availability">
			<label class="options_title">{l s='Hide availability in product-list:' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_availability_yes"><input type="radio" name="angarconfig_input33" value="pl_availability_yes" {if $css33 == pl_availability_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_availability_no"><input type="radio" name="angarconfig_input33" value="pl_availability_no" {if $css33 == pl_availability_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>		

		<div class="pl_colors">
			<label class="options_title">{l s='Hide variant colors in product-list:' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_colors_yes"><input type="radio" name="angarconfig_input51" value="pl_colors_yes" {if $css51 == pl_colors_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="pl_colors_no"><input type="radio" name="angarconfig_input51" value="pl_colors_no" {if $css51 == pl_colors_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

	</div>











	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box15">{l s='Footer' mod='angarthemeconfigurator'}</div>
	<div id="customization_box15" class="collapse customization_container">

		<div class="newsletter_info">
			<label class="options_title">{l s='Hide newsletter info:' mod='angarthemeconfigurator'}</label></br>
			<label class="newsletter_info_yes"><input type="radio" name="angarconfig_input52" value="newsletter_info_yes" {if $css52 == newsletter_info_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="newsletter_info_no"><input type="radio" name="angarconfig_input52" value="newsletter_info_no" {if $css52 == newsletter_info_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box16">{l s='Product page' mod='angarthemeconfigurator'}</div>
	<div id="customization_box16" class="collapse customization_container">	

{* Delete
		<div class="product_layout">
			<label class="options_title">{l s='Product page layout:' mod='angarthemeconfigurator'}</label></br>
			<label class="product_layout1"><input type="radio" name="angarconfig_input34" value="product_layout1" {if $css34 == product_layout1}checked{/if}>{l s='Layout 1' mod='angarthemeconfigurator'}</label></br>
			<label class="product_layout2"><input type="radio" name="angarconfig_input34" value="product_layout2" {if $css34 == product_layout2}checked{/if}>{l s='Layout 2' mod='angarthemeconfigurator'}</label>
		</div>
*}

		<div class="product_hide_reference">
			<label class="options_title">{l s='Hide reference code in top:' mod='angarthemeconfigurator'}</label></br>
			<label class="hide_reference_yes"><input type="radio" name="angarconfig_input35" value="hide_reference_yes" {if $css35 == hide_reference_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="hide_reference_no"><input type="radio" name="angarconfig_input35" value="hide_reference_no" {if $css35 == hide_reference_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="product_hide_man">
			<label class="options_title">{l s='Hide manufacturer name:' mod='angarthemeconfigurator'}</label></br>
			<label class="product_hide_man_yes"><input type="radio" name="angarconfig_input58" value="product_hide_man_yes" {if $css58 == product_hide_man_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="product_hide_man_no"><input type="radio" name="angarconfig_input58" value="product_hide_man_no" {if $css58 == product_hide_man_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="product_hide_reassurance">
			<label class="options_title">{l s='Hide block reassurance:' mod='angarthemeconfigurator'}</label></br>
			<label class="hide_reassurance_yes"><input type="radio" name="angarconfig_input36" value="hide_reassurance_yes" {if $css36 == hide_reassurance_yes}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="hide_reassurance_no"><input type="radio" name="angarconfig_input36" value="hide_reassurance_no" {if $css36 == hide_reassurance_no}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="product_tabs">
			<label class="options_title">{l s='Product info in boxes or tabs:' mod='angarthemeconfigurator'}</label></br>
			<label class="product_tabs1"><input type="radio" name="angarconfig_input37" value="product_tabs1" {if $css37 == product_tabs1}checked{/if}>{l s='Tabs' mod='angarthemeconfigurator'}</label></br>
			<label class="product_tabs2"><input type="radio" name="angarconfig_input37" value="product_tabs2" {if $css37 == product_tabs2}checked{/if}>{l s='Boxes' mod='angarthemeconfigurator'}</label>
		</div>

	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box17">{l s='Compatibility - choose your PrestaShop version here' mod='angarthemeconfigurator'}</div>
	<div id="customization_box17" class="collapse customization_container">	

	<div class="must_save"><sup>*</sup> {l s='- You must save this option to see changes' mod='angarthemeconfigurator'}</div>

		<div class="prestashop_version">
			<label class="options_title">{l s='Select PrestaShop compatibility:' mod='angarthemeconfigurator'} <sup>*</sup></label></br>
			<label><input type="radio" name="angarconfig_psversion" value="ps_176" {if $psversion == ps_176}checked{/if}>{l s='Prestashop 1.7.6 and higher' mod='angarthemeconfigurator'}</label></br>
			<label><input type="radio" name="angarconfig_psversion" value="ps_175" {if $psversion == ps_175}checked{/if}>{l s='Prestashop 1.7.5' mod='angarthemeconfigurator'}</label></br>
			<label><input type="radio" name="angarconfig_psversion" value="ps_174" {if $psversion == ps_174}checked{/if}>{l s='Prestashop 1.7.4' mod='angarthemeconfigurator'}</label></br>
			<label><input type="radio" name="angarconfig_psversion" value="ps_173" {if $psversion == ps_173}checked{/if}>{l s='Prestashop 1.7.3' mod='angarthemeconfigurator'}</label></br>
			<label><input type="radio" name="angarconfig_psversion" value="ps_172" {if $psversion == ps_172}checked{/if}>{l s='Prestashop 1.7.2' mod='angarthemeconfigurator'}</label></br>
			<label><input type="radio" name="angarconfig_psversion" value="ps_171" {if $psversion == ps_171}checked{/if}>{l s='Prestashop 1.7.1' mod='angarthemeconfigurator'}</label></br>
			<label><input type="radio" name="angarconfig_psversion" value="ps_170" {if $psversion == ps_170}checked{/if}>{l s='Prestashop 1.7.0' mod='angarthemeconfigurator'}</label>
		</div>

	</div>







{* Delete

	<div class="customization_title" data-toggle="collapse" data-parent="#accordion" data-target="#customization_box16">{l s='Order and authetication page' mod='angarthemeconfigurator'}</div>
	<div id="customization_box16" class="collapse customization_container">

		<div class="order_gender">
			<label class="options_title">{l s='Hide gender:' mod='angarthemeconfigurator'}</label></br>
			<label class="order_gender_hide"><input type="radio" name="angarconfig_input38" value="order_gender_hide" {if $css38 == order_gender_hide}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="order_gender_show"><input type="radio" name="angarconfig_input38" value="order_gender_show" {if $css38 == order_gender_show}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

		<div class="order_birthday">
			<label class="options_title">{l s='Hide birthday:' mod='angarthemeconfigurator'}</label></br>
			<label class="order_birthday_hide"><input type="radio" name="angarconfig_input39" value="order_birthday_hide" {if $css39 == order_birthday_hide}checked{/if}>{l s='Yes' mod='angarthemeconfigurator'}</label></br>
			<label class="order_birthday_show"><input type="radio" name="angarconfig_input39" value="order_birthday_show" {if $css39 == order_birthday_show}checked{/if}>{l s='No' mod='angarthemeconfigurator'}</label>
		</div>

	</div>

*}











</div>
</div>


{if $demo == 1}
	<input type="submit" value="{l s='Save' mod='angarthemeconfigurator'}" class="button_at_config" name="submitAngarcss" />
	<input type="hidden" name="action" value="0" />
{else}
	<div class="button_disabled">{l s='Save' mod='angarthemeconfigurator'}</div>
	<div class="must_save">{l s='You can\'\t save the demo version' mod='angarthemeconfigurator'}</div>
{/if}


</form>





<div id="theme_color_icon"> </div>

<form id="theme_color" action="" method="post">

<h5>{l s='Theme color editor' mod='angarthemeconfigurator'}</h5>

{if $demo == 1}
<div class="must_save2">{l s='Remember to often save your changes.' mod='angarthemeconfigurator'}</br>{l s='After saving click on the shop logo and refresh the store several times to load your changes.' mod='angarthemeconfigurator'}</div>
{/if}

<div id="accordion2">
<div class="panel">	

	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box0">{l s='Content black or white' mod='angarthemeconfigurator'}</div>
	<div id="color_box0" class="collapse customization_container">

	<div class="bg_inner">
		<label class="options_title">{l s='Content background:' mod='angarthemeconfigurator'}</label></br>
		<label class="bg_white"><input type="radio" name="angarcolor_input75" value="bg_white" {if $color75 == bg_white}checked{/if}>{l s='White' mod='angarthemeconfigurator'}</label></br>
		<label class="bg_black"><input type="radio" name="angarcolor_input75" value="bg_black" {if $color75 == bg_black}checked{/if}>{l s='Black' mod='angarthemeconfigurator'}</label>
	</div>

	</div>

	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box1">{l s='Top bar' mod='angarthemeconfigurator'}</div>
	<div id="color_box1" class="collapse customization_container">

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Top bar' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input1" id="nav_background" class="form-control" value="{$color1|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Top bar' mod='angarthemeconfigurator'} - {l s='border and separators:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input2" id="nav_border" class="form-control" value="{$color2|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Top bar' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input3" id="top_font_color" class="form-control" value="{$color3|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Top bar' mod='angarthemeconfigurator'} - {l s='font bold color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input4" id="top_font_color2" class="form-control" value="{$color4|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Top bar' mod='angarthemeconfigurator'} - {l s='icons color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input5" id="top_icons" class="form-control" value="{$color5|escape:'htmlall':'UTF-8'}">
	</div>

	</div>







	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box2">{l s='Header' mod='angarthemeconfigurator'}</div>
	<div id="color_box2" class="collapse customization_container">

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Header' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input6" id="header_background" class="form-control" value="{$color6|escape:'htmlall':'UTF-8'}">
	</div>

{*
	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Account icon (tablet and smartfon)' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input59" id="account_background" class="form-control" value="{$color59|escape:'htmlall':'UTF-8'}">
	</div>
*}

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box3">{l s='Search' mod='angarthemeconfigurator'}</div>
	<div id="color_box3" class="collapse customization_container">

	<div class="color_modyficator form-group search_background">
		<label class="options_title">{l s='Search button' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input7" id="search_button" class="form-control" value="{$color7|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group search_color">
		<label class="options_title">{l s='Search button' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input78" id="search_button_color" class="form-control" value="{$color78|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group search_background">
		<label class="options_title">{l s='Search button' mod='angarthemeconfigurator'} - {l s='background hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input8" id="search_button_hover" class="form-control" value="{$color8|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group search_color">
		<label class="options_title">{l s='Search button' mod='angarthemeconfigurator'} - {l s='font color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input79" id="search_button_color_hover" class="form-control" value="{$color79|escape:'htmlall':'UTF-8'}">
	</div>

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box4">{l s='Cart' mod='angarthemeconfigurator'}</div>
	<div id="color_box4" class="collapse customization_container">

	<div class="must_save sticky_cart_color"><sup>*</sup> {l s='- You must save this option to see changes' mod='angarthemeconfigurator'}</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Cart' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input9" id="cart_background" class="form-control" value="{$color9|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group cart_color2">
		<label class="options_title">{l s='Cart' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input80" id="cart_color" class="form-control" value="{$color80|escape:'htmlall':'UTF-8'}">
	</div>

{*
	<div class="color_modyficator form-group sticky_cart_color">
		<label class="options_title">{l s='Sticky cart' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}<sup>*</sup></label></br>
		<input type="text" name="angarcolor_input86" id="sticky_cart_bg1" class="form-control" value="{$color86|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group sticky_cart_color">
		<label class="options_title">{l s='Sticky cart' mod='angarthemeconfigurator'} - {l s='background 2:' mod='angarthemeconfigurator'}<sup>*</sup></label></br>
		<input type="text" name="angarcolor_input87" id="sticky_cart_bg2" class="form-control" value="{$color87|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group sticky_cart_color">
		<label class="options_title">{l s='Sticky cart' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}<sup>*</sup></label></br>
		<input type="text" name="angarcolor_input88" id="sticky_cart_color" class="form-control" value="{$color88|escape:'htmlall':'UTF-8'}">
	</div>
*}

	</div>









	
	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box5">{l s='Menu' mod='angarthemeconfigurator'}</div>
	<div id="color_box5" class="collapse customization_container">


	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Menu' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input11" id="menu_bg" class="form-control" value="{$color11|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Menu' mod='angarthemeconfigurator'} - {l s='border and separators:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input12" id="menu_border_color" class="form-control" value="{$color12|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Menu' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input13" id="menu_font_color" class="form-control" value="{$color13|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Menu' mod='angarthemeconfigurator'} - {l s='font color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input14" id="menu_font_color_hover" class="form-control" value="{$color14|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Menu' mod='angarthemeconfigurator'} - {l s='background hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input15" id="menu_li_hover" class="form-control" value="{$color15|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Submenu layout 2' mod='angarthemeconfigurator'} {l s='color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input81" id="submenu_color" class="form-control" value="{$color81|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Submenu layout 2' mod='angarthemeconfigurator'} {l s='background hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input16" id="submenu_background" class="form-control" value="{$color16|escape:'htmlall':'UTF-8'}">
	</div>


	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box6">{l s='Slider' mod='angarthemeconfigurator'}</div>
	<div id="color_box6" class="collapse customization_container">

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Slider active dot:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input10" id="slider_dot" class="form-control" value="{$color10|escape:'htmlall':'UTF-8'}">
	</div>

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box7">{l s='Featured categories' mod='angarthemeconfigurator'}</div>
	<div id="color_box7" class="collapse customization_container">

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Module header' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input60" id="feat_title" class="form-control" value="{$color60|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Category name' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input61" id="feat_cat_title" class="form-control" value="{$color61|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Subcategory button' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input62" id="feat_button_bg" class="form-control" value="{$color62|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Subcategory button' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input63" id="feat_button_color" class="form-control" value="{$color63|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Subcategory button' mod='angarthemeconfigurator'} - {l s='border-color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input64" id="feat_button_border_color" class="form-control" value="{$color64|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Subcategory button hover' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input65" id="feat_button_bg_hover" class="form-control" value="{$color65|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Subcategory button hover' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input66" id="feat_button_color_hover" class="form-control" value="{$color66|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Subcategory button' mod='angarthemeconfigurator'} - {l s='border-color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input67" id="feat_button_border_color_hover" class="form-control" value="{$color67|escape:'htmlall':'UTF-8'}">
	</div>


	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box8">{l s='Left/right column' mod='angarthemeconfigurator'}</div>
	<div id="color_box8" class="collapse customization_container">


	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Modules header' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input17" id="title_block1" class="form-control" value="{$color17|escape:'htmlall':'UTF-8'}">
	</div>

{*
	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Price color in left or right column:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input18" id="price_column_color" class="form-control" value="{$color18|escape:'htmlall':'UTF-8'}">
	</div>
*}

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box9">{l s='Homepage products layout' mod='angarthemeconfigurator'}</div>
	<div id="color_box9" class="collapse customization_container">


	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Blocks with products header' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input19" id="index_title1" class="form-control" value="{$color19|escape:'htmlall':'UTF-8'}">
	</div>


	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box10">{l s='Product-list' mod='angarthemeconfigurator'}</div>
	<div id="color_box10" class="collapse customization_container">

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='New label background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input20" id="new_label" class="form-control" value="{$color20|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group product_name_white">
		<label class="options_title">{l s='Product name' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input21" id="product_name_color" class="form-control" value="{$color21|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Price color in product-list:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input22" id="price_color_index" class="form-control" value="{$color22|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input23" id="pl_cart_bg" class="form-control" value="{$color23|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input24" id="pl_cart_color" class="form-control" value="{$color24|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='border-color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input25" id="pl_cart_button_border_color" class="form-control" value="{$color25|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='background hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input26" id="pl_cart_bg_hover" class="form-control" value="{$color26|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='font color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input27" id="pl_cart_color_hover" class="form-control" value="{$color27|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='border-color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input28" id="pl_cart_button_border_color_hover" class="form-control" value="{$color28|escape:'htmlall':'UTF-8'}">
	</div>

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box11">{l s='Products from category' mod='angarthemeconfigurator'}</div>
	<div id="color_box11" class="collapse customization_container">

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Module header' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input68" id="catprod_title" class="form-control" value="{$color68|escape:'htmlall':'UTF-8'}">
	</div>

{*
	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Price color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input69" id="catprod_price" class="form-control" value="{$color69|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='New label background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input70" id="catprod_label" class="form-control" value="{$color70|escape:'htmlall':'UTF-8'}">
	</div>
*}

	</div>










	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box12">{l s='Featured manufacturers' mod='angarthemeconfigurator'}</div>
	<div id="color_box12" class="collapse customization_container">

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Module header' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input71" id="featman_title" class="form-control" value="{$color71|escape:'htmlall':'UTF-8'}">
	</div>

	</div>








	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box13">{l s='Footer' mod='angarthemeconfigurator'}</div>
	<div id="color_box13" class="collapse customization_container">

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Icons before footer color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input29" id="footer_cms_icons" class="form-control" value="{$color29|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Footer' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input30" id="footer_background" class="form-control" value="{$color30|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Footer' mod='angarthemeconfigurator'} - {l s='border color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input31" id="footer_border" class="form-control" value="{$color31|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Footer' mod='angarthemeconfigurator'} - {l s='modules header border color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input32" id="footer_border_header" class="form-control" value="{$color32|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Footer' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input33" id="footer_font_color" class="form-control" value="{$color33|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Footer bottom' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input36" id="footer2_background" class="form-control" value="{$color36|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Footer bottom' mod='angarthemeconfigurator'} - {l s='border color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input37" id="footer2_border" class="form-control" value="{$color37|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Footer bottom' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input38" id="footer2_font_color" class="form-control" value="{$color38|escape:'htmlall':'UTF-8'}">
	</div>


	</div>








	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box14">{l s='Footer - newsletter button' mod='angarthemeconfigurator'}</div>
	<div id="color_box14" class="collapse customization_container">

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Newsletter button' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input34" id="newsletter_button" class="form-control" value="{$color34|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Newsletter button' mod='angarthemeconfigurator'} - {l s='color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input82" id="newsletter_color" class="form-control" value="{$color82|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Newsletter button' mod='angarthemeconfigurator'} - {l s='background hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input35" id="newsletter_button_hover" class="form-control" value="{$color35|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Newsletter button' mod='angarthemeconfigurator'} - {l s='color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input83" id="newsletter_color_hover" class="form-control" value="{$color83|escape:'htmlall':'UTF-8'}">
	</div>

	</div>








	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box15">{l s='Product page' mod='angarthemeconfigurator'}</div>
	<div id="color_box15" class="collapse customization_container">


	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Price color product page:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input39" id="price_color_product" class="form-control" value="{$color39|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input40" id="product_cart_button_bg" class="form-control" value="{$color40|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input41" id="product_cart_button_color" class="form-control" value="{$color41|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='border-color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input42" id="product_cart_button_border_color" class="form-control" value="{$color42|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='background hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input43" id="product_cart_button_bg_hover" class="form-control" value="{$color43|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='font color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input44" id="product_cart_button_color_hover" class="form-control" value="{$color44|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='"Add to cart" button' mod='angarthemeconfigurator'} - {l s='border-color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input45" id="product_cart_button_border_color_hover" class="form-control" value="{$color45|escape:'htmlall':'UTF-8'}">
	</div>


	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Selected tab background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input46" id="product_selected_tab" class="form-control" value="{$color46|escape:'htmlall':'UTF-8'}">
	</div>


	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box16">{l s='Other buttons' mod='angarthemeconfigurator'}</div>
	<div id="color_box16" class="collapse customization_container">


	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Primary button' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input47" id="green_button_bg" class="form-control" value="{$color47|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Primary button' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input48" id="green_button_color" class="form-control" value="{$color48|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Primary button' mod='angarthemeconfigurator'} - {l s='border-color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input49" id="green_button_border_color" class="form-control" value="{$color49|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Primary button hover' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input50" id="green_button_bg_hover" class="form-control" value="{$color50|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Primary button hover' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input51" id="green_button_color_hover" class="form-control" value="{$color51|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Primary button' mod='angarthemeconfigurator'} - {l s='border-color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input52" id="green_button_border_color_hover" class="form-control" value="{$color52|escape:'htmlall':'UTF-8'}">
	</div>











	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Secondary button' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input53" id="grey_button_bg" class="form-control" value="{$color53|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Secondary button' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input54" id="grey_button_color" class="form-control" value="{$color54|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Secondary button' mod='angarthemeconfigurator'} - {l s='border-color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input55" id="grey_button_border_color" class="form-control" value="{$color55|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Secondary button hover' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input56" id="grey_button_bg_hover" class="form-control" value="{$color56|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Secondary button hover' mod='angarthemeconfigurator'} - {l s='font color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input57" id="grey_button_color_hover" class="form-control" value="{$color57|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Secondary button' mod='angarthemeconfigurator'} - {l s='border-color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input58" id="grey_button_border_color_hover" class="form-control" value="{$color58|escape:'htmlall':'UTF-8'}">
	</div>










	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box17">{l s='Other elements' mod='angarthemeconfigurator'}</div>
	<div id="color_box17" class="collapse customization_container">

	<div class="must_save"><sup>*</sup> {l s='- You must save this option to see changes' mod='angarthemeconfigurator'}</div>




	<div class="color_modyficator form-group inputs_outline_white">
		<label class="options_title">{l s='Inputs outline:' mod='angarthemeconfigurator'} <sup>*</sup></label></br>
		<input type="text" name="angarcolor_input72" id="inputs_outline" class="form-control" value="{$color72|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Pagination active page:' mod='angarthemeconfigurator'} <sup>*</sup></label></br>
		<input type="text" name="angarcolor_input73" id="pagination_color" class="form-control" value="{$color73|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Icons on my account page:' mod='angarthemeconfigurator'} <sup>*</sup></label></br>
		<input type="text" name="angarcolor_input74" id="account_icon_color" class="form-control" value="{$color74|escape:'htmlall':'UTF-8'}">
	</div>



	</div>









	<div class="customization_title" data-toggle="collapse" data-parent="#accordion2" data-target="#color_box18">{l s='Button scroll to top' mod='angarthemeconfigurator'}</div>
	<div id="color_box18" class="collapse customization_container">


	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Button scroll to top' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input76" id="scroll_button_bg" class="form-control" value="{$color76|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Button scroll to top' mod='angarthemeconfigurator'} - {l s='font-color:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input84" id="scroll_button_color" class="form-control" value="{$color84|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Button scroll to top hover' mod='angarthemeconfigurator'} - {l s='background:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input77" id="scroll_button_bg_hover" class="form-control" value="{$color77|escape:'htmlall':'UTF-8'}">
	</div>

	<div class="color_modyficator form-group">
		<label class="options_title">{l s='Button scroll to top' mod='angarthemeconfigurator'} - {l s='font-color hover:' mod='angarthemeconfigurator'}</label></br>
		<input type="text" name="angarcolor_input85" id="scroll_button_color_hover" class="form-control" value="{$color85|escape:'htmlall':'UTF-8'}">
	</div>

	</div>











</div>
</div>


{if $demo == 1}
	<input type="submit" value="{l s='Save' mod='angarthemeconfigurator'}" class="button_at_config" name="submitAngarcolor" />
	<input type="hidden" name="action" value="0" />
{else}
	<div class="button_disabled">{l s='Save' mod='angarthemeconfigurator'}</div>
	<div class="must_save">{l s='You can\'\t save the demo version' mod='angarthemeconfigurator'}</div>
{/if}


</form>

















{/if}