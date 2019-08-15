{*
 * 2008 - 2017 Presto-Changeo
 *
 * MODULE Attribute Wizard
 *
 * @version   2.0.0
 * @author    Presto-Changeo <info@presto-changeo.com>
 * @link      http://www.presto-changeo.com
 * @copyright Copyright (c) permanent, Presto-Changeo
 * @license   Addons PrestaShop license limitation
 *
 * NOTICE OF LICENSE
 *
 * Don't use this module on several shops. The license provided by PrestaShop Addons
 * for all its modules is valid only once for a single shop.
*}
<!-- MODULE Attribute Wizard -->
{if isset($aw_groups)}
{if $aw_fade}
<div id="aw_background" style="position: fixed; top:0;opacity:{$aw_opacity_fraction|intval};filter:alpha(opacity={$aw_opacity|intval});left:0;width:100%;height:100%;z-index:19;display:none;background-color:black">
</div>
{/if}
<div id="aw_container" {if $aw_popup}style="position: absolute; z-index:20;top:-5000px;display:block;width:{if $aw_popup_width && ($aw_popup_width != 0)}{$aw_popup_width|intval}px;{else}auto;{/if}"{/if}>
<script type="text/javascript">
var aw_psv = "{$aw_psv|escape:'htmlall':'UTF-8'}";
var aw_ps_version_3 = {$aw_ps_version_3|floatval};
var aw_add_to_cart_display = "{$aw_add_to_cart|escape:'htmlall':'UTF-8'}";
var aw_unavailable_type = '{$aw_unavailable_type|escape:'htmlall':'UTF-8'}';
var aw_no_combination = "{l s='The product does not exist in this model. Please choose another.' mod='attributewizard' js=1}";
var aw_customize = "{l s='Customize Product' mod='attributewizard' js=1}";
var aw_add_cart = "{l s='Add to cart' mod='attributewizard' js=1}";
var aw_ps_version = {$aw_ps_version|floatval};
var aw_add = "{l s='Add' mod='attributewizard' js=1}";
var aw_sub = "{l s='Subtract' mod='attributewizard' js=1}";
var aw_popup = {if $aw_popup}true{else}false{/if};
var aw_pi_display = '{$aw_pi_display|escape:'htmlall':'UTF-8'}';
var aw_first_show_all = {$aw_first_show_all|intval};
var aw_currency = {$aw_currency.id_currency|intval};
var aw_no_disable = new Array();
var aw_fade = {$aw_fade|intval};

var aw_popup_width = {$aw_popup_width|floatval};
var aw_popup_top = {$aw_popup_top|floatval};
var aw_popup_left = {$aw_popup_left|floatval};


if (typeof group_reduction == 'undefined')
	group_reduction = 1;
var aw_group_reduction = group_reduction;

aw_group_reduction = aw_group_reduction == 1?group_reduction:1-group_reduction;

var reduction_percent = {$reduction_percent|floatval};

</script>
<div id="aw_box">

	<div class="aw_content">
<form name="aw_wizard">
<table width="100%" border="0"> 
	{if ($aw_add_to_cart == "both" || $aw_add_to_cart == "bottom") && $aw_groups|@count >= $aw_second_add}
	<tr>
		<td align="center" colspan="6">
			<div id="aw_in_stock">
			<table>
			<tr>
				<td align="right">
                                    {if $aw_popup}

                                        <a itemprop="url" class="button lnk_view btn btn-default" onclick="$('#aw_container').fadeOut(1000);$('#aw_background').fadeOut(1000);aw_customize_func();" title="{l s='Close' mod='attributewizard'}">
                                                <span>{l s='Close' mod='attributewizard'}</span>
                                        </a>

                                    {/if}
				</td>
				<td align="right">
				
                                    <div class="box-info-product buttons_bottom_block no-print">
                                        <button id="aw_top_add_to_cart" {if $PS_CATALOG_MODE} style="display: none;"{/if} type="button" name="Submit" class="btn btn-primary add-to-cart" onclick="aw_add_to_cart()">
                                            <i class="material-icons shopping-cart"></i>    
                                            <span>{l s='Add to cart' mod='attributewizard'}</span>
                                        </button>
                                    </div>
					
				</td>
				<td align="left" class="aw_quantity_additional top_additional" {if $PS_CATALOG_MODE} style="display: none;"{/if}>
					<span style="">
                                            {l s='Quantity' mod='attributewizard'}:
                                        </span>
                                         <input type="text" min="{$product.minimal_quantity|intval}" name="aw_q1" id="aw_q1" onkeyup="$('#quantity_wanted').val(this.value);$('#aw_q2').val(this.value);" value="1" />
				
					
				</td>
				<td align="left">
					<b class="price our_price_display" id="aw_second_price"></b>
				</td>
			</tr>
			</table>
			</div>
                        <div class="top_qty_additional">

                        </div>

			<div id="aw_not_in_stock" style="display:none">
			</div>
		</td>
	</tr>

	{/if}
{foreach from=$aw_groups key=id_attribute_group item=group}
	{assign var='aw_default_impact' value=0}
	{foreach from=$attributeImpacts key=id_attributeImpact item=attributeImpact}
		{if isset($attributeImpact.id_attribute) && $group.default == $attributeImpact.id_attribute}
			{assign var='aw_default_impact' value=$attributeImpact.price|intval}
		{/if}
	{/foreach}
	<script type="text/javascript">
		aw_no_disable[{$group.id_group|floatval}] = {if isset($group.group_no_disable)}{$group.group_no_disable|intval}{else}0{/if};
    </script>
	
	{if !isset($group.checkbox) || ($group.checkbox != "middle" && $group.checkbox != "end")} 
		<tr>
			<td align="left" valign="top" width="0%">
				{if isset($group.group_url) && $group.group_url != ''}<a href="{$group.group_url|escape:'htmlall':'UTF-8'}" target="_blank" alt="{$group.group_url|escape:'htmlall':'UTF-8'}">{/if}{getGroupImageTagAW id_group=$group.id_group alt=$group.name|escape:'htmlall':'UTF-8' v=$group.image_upload}{if isset($group.group_url) && $group.group_url != ''}</a>{/if}
			</td>
			<td align="left" valign="top" width="100%">
				<div id="aw_box">
					<b class="xtop"><b class="xb1"></b><b class="xb2 xbtop"></b><b class="xb3 xbtop"></b><b class="xb4 xbtop"></b></b>
					<div class="aw_header">
						{if isset($group.group_header) && $group.group_header}
							{$group.group_header|escape:'quotes':'UTF-8'}
						{else}
							{$group.name|escape:'quotes':'UTF-8'}
						{/if}
						{if isset($group.group_description) && $group.group_description != ""}
							<br />
							<div class="aw_description">
								{$group.group_description nofilter}
							</div>
						{/if}
					</div>
					<div class="aw_content">
					{if $group.group_type == "dropdown"}
						<table cellpadding="6">
						{if $group.group_color == 1}
							{if isset($group.group_display_multiple) && $group.group_display_multiple == 1}
									<tr style="height:{if isset($group.group_height) && $group.group_height}{$group.group_height|floatval}{else}20{/if}px">
										<td>
											<div id="aw_select_multiple_colors_{$group.id_group|intval}">
												{foreach from=$group.attributes item=aw_group_attribute}
												{assign var='id_attribute' value=$aw_group_attribute.0}
													{if file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
														{assign var="width" value=$group.group_width-2}
														{assign var="height" value=$group.group_height-2}
														<INPUT onClick=" event.returnValue=false; aw_select('{$group.id_group|intval}',{$id_attribute|intval}, {$aw_currency.id_currency|intval}); event.returnValue=false; return false;" id="multiple_aw_group_div_{$id_attribute|intval}" class="aw_multiple_color aw_group_image" style="float: left; {if isset($group.group_width) && $group.group_width}width:{$width|floatval}px;height:{$height|floatval}px;{/if}" type="image" src="{$img_col_dir|escape:'htmlall':'UTF-8'}{$id_attribute|intval}.jpg" value="">


													{else}
														<button onClick=" event.returnValue=false;  aw_select('{$group.id_group|intval}',{$id_attribute|intval}, {$aw_currency.id_currency|intval});return false;" id="multiple_aw_group_div_{$id_attribute|intval}" class="aw_multiple_color aw_group_color" style="float: left;{if isset($group.group_width)}width:{$group.group_width|intval}px;height:{$group.group_height|intval}px;{/if}background-color:{$aw_group_attribute.2|escape:'htmlall':'UTF-8'};">
															{if $aw_group_attribute.2 != ""}
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															{/if}
														</button>
													{/if}
												{/foreach}
											</div>
										</td>
									</tr>
							{/if}
						{/if}
                		<tr style="height:{if isset($group.group_height) && $group.group_height}{$group.group_height|floatval}{else}20{/if}px">
                    		<td align="left">
								{if isset($group.group_color) && $group.group_color == 1 && isset($group.group_display_multiple) && $group.group_display_multiple != 1}
									
										
									
										<div id="aw_select_colors_{$group.id_group|intval}" {if isset($group.group_width) && $group.group_width}style="width:{$group.group_width|intval}px;height:{$group.group_height|intval}px;"{/if}>
											<div></div>
											{foreach from=$group.attributes item=aw_group_attribute}
											{assign var='id_attribute' value=$aw_group_attribute.0}
												{if file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
													<div id="aw_group_div_{$id_attribute|intval}" class="aw_group_image" style="{if !$group.group_resize}overflow: hidden;{/if} {if isset($group.group_width) && $group.group_width}width:{$group.group_width|intval}px;height:{$group.group_height|intval}px;{/if}display:{if $group.default == $id_attribute}block{else}none{/if};">
														<a href="{$img_col_dir|escape:'htmlall':'UTF-8'}{$id_attribute|intval}.jpg" border="0" class="{if $aw_psv < 1.6}thickbox{else}fancybox shown{/if}"><img {if $group.group_resize}style="width:{$group.group_width|intval}px;height:{$group.group_height|intval}px;"{/if} src="{$img_col_dir|escape:'htmlall':'UTF-8'}{$id_attribute|intval}.jpg" alt="" title="{$aw_group_attribute.1|escape:'htmlall':'UTF-8'}" /></a>
													</div>
												{else}
													<div id="aw_group_div_{$id_attribute|intval}" class="aw_group_color" style="{if isset($group.group_width)}width:{$group.group_width|intval}px;height:{$group.group_height|intval}px;{/if}background-color:{$aw_group_attribute.2|escape:'htmlall':'UTF-8'};display:{if $group.default == $id_attribute}block{else}none{/if};">
														{if $aw_group_attribute.2 != ""}
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														{/if}
													</div>
												{/if}
											{/foreach}
											
										</div>
									
               					</td>
               					<td align="left">
									
           						{/if}
								{foreach from=$group.attributes item=aw_group_attribute}
								{assign var='id_attribute' value=$aw_group_attribute.0}
	                    			{if $group.default == $id_attribute}
                   						<input type="hidden" name="pi_default_{$group.id_group|intval}" id="pi_default_{$group.id_group|intval}" value="{$aw_default_impact|escape:'htmlall':'UTF-8'}" />
                   					{/if}
                    			{/foreach}
								
                    			<select class="aw_attribute_selected form-control "  onblur="this.style.position='';" name="aw_group_{$group.id_group|intval}"  id="aw_group_{$group.id_group|intval}" onchange="{if $aw_ps_version >= 1.5 && $group.group_color == 1}colorPickerClickAW(this.options[this.selectedIndex].value, {$group.id_group|intval});{/if}aw_select('{$group.id_group|intval}',  this.options[this.selectedIndex].value, {$aw_currency.id_currency|intval});this.style.position='';">
								{foreach from=$group.attributes item=aw_group_attribute}
								{strip}
									{assign var='id_attribute' value=$aw_group_attribute.0}
                    				<option value="{$id_attribute|intval}"{if $group.default == $id_attribute} selected="selected"{/if} class="aw_group_{$group.id_group|intval}_{$id_attribute|intval}_d">{$aw_group_attribute.1|escape:'htmlall':'UTF-8'}
              						
                    				</option>
                   				{/strip}
	                    		{/foreach}
                    			</select>
								{foreach from=$group.attributes item=aw_group_attribute name=aw_select}
								{strip}
								{assign var='id_attribute' value=$aw_group_attribute.0}
                    			{foreach from=$attributeImpacts key=id_attributeImpact item=attributeImpact}
                       			{if isset($attributeImpact.id_attribute) && $id_attribute == $attributeImpact.id_attribute}
	                       			{math assign="aw_pi" equation='x-y' x=$attributeImpact.price y=$aw_default_impact} 
                       				<input type="hidden" name="pi_{$group.id_group|intval}_{$id_attribute|intval}_{$smarty.foreach.aw_select.index|intval}" id="pi_{$group.id_group|intval}_{$id_attribute|intval}" value="{$attributeImpact.price|floatval}" />
                        		{/if}
	                    		{/foreach}
	                    		{/strip}
	                    		{/foreach}
                    			
                    		</td>
                		</tr>
						</table>
					{elseif $group.group_type == "radio"}
						<table cellpadding="6">
						{assign var="aw_row_count" value="1"}
                		<tr style="height:20px" id="aw_group_{$group.id_group|intval}_{$aw_row_count|intval}_r">
						{foreach from=$group.attributes name=aw_loop item=aw_group_attribute}
						
						{strip}
						{assign var='id_attribute' value=$aw_group_attribute.0}
                    		<td align="left" valign="top">
                    			<div id="aw_cell_{$id_attribute|intval}" alt="aw_group_{$group.id_group|intval}_{$aw_row_count|intval}_r" class="aw_group_{$group.id_group|intval}_{$aw_row_count|intval}_r aw_group_{$group.id_group|intval}_{$id_attribute|intval}_h">
                    			{if file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
                    					<div id="aw_tc_{$id_attribute|intval}" onclick="{if $aw_ps_version >= 1.5 && $group.group_color == 1}colorPickerClickAW({$id_attribute|intval}, {$group.id_group|intval});{/if}aw_select('{$group.id_group|intval}',{$id_attribute|intval}, {$aw_currency.id_currency|intval});" class="aw_group_image" style="margin:auto;margin-left:3px;margin-right:3px;margin-bottom:5px;width:{if isset($group.group_width)}{if $aw_ps_version_3 < 160}{$group.group_width|floatval}px;{/if}height:{$group.group_height|floatval}px;{/if}{if $group.group_per_row == 1 && !$group.group_layout}float:left{/if}">
                    					{if $group.group_per_row > 1}<center>{/if}
		                    				<a href="{$img_col_dir|escape:'htmlall':'UTF-8'}{$id_attribute|intval}.jpg" border="0" class="{if $aw_psv < 1.6}thickbox{else}fancybox shown{/if}"><img {if $group.group_resize}style="width:{$group.group_width|floatval}px;height:{$group.group_height|floatval}px;"{/if} src="{$img_col_dir|escape:'htmlall':'UTF-8'}{$id_attribute|intval}.jpg" alt="{$aw_group_attribute.1|escape:'htmlall':'UTF-8'}" title="{$aw_group_attribute.1|escape:'htmlall':'UTF-8'}" /></a>
                    					{if $group.group_per_row > 1}</center>{/if}
										</div>
                    			{elseif $aw_group_attribute.2 != ""}
                    					{if $group.group_per_row > 1}<center>{/if}
                    					<div id="aw_tc_{$id_attribute|intval}" onclick="{if $aw_ps_version >= 1.5 && $group.group_color == 1}colorPickerClickAW({$id_attribute|intval}, {$group.id_group|intval});{/if}aw_select('{$group.id_group|intval}',{$id_attribute|intval}, {$aw_currency.id_currency|intval});" class="aw_group_color" style="margin:auto;margin-left:3px;margin-right:3px;margin-bottom:5px;{if isset($group.group_width)}width:{$group.group_width|floatval}px;height:{$group.group_height|floatval}px;{/if}background-color:{$aw_group_attribute.2|escape:'htmlall':'UTF-8'}; {if $group.group_per_row == 1 && !$group.group_layout}float:left{/if}">
                    						&nbsp;
                    					</div>
                    					{if $group.group_per_row > 1}</center>{/if}
                    			{/if}
									<div id="aw_radio_cell{$id_attribute|intval}" style="{if !$group.group_layout}float:left; {if $group.group_height}line-height:{$group.group_height|floatval}px;{/if} {else}width:100%;clear:left;{/if}">
										{if $group.group_per_row > 1}<center>{/if}
										
										<input type="radio" style="border: none;margin:0;padding:0" id="aw_radio_group_{$id_attribute|intval}"  class="aw_attribute_selected aw_group_{$group.id_group|intval}_d aw_group_{$group.id_group|intval}_{$id_attribute|intval}_d" name="aw_group_{$group.id_group|intval}" onclick="{if $aw_ps_version >= 1.5 && $group.group_color == 1}colorPickerClickAW({$id_attribute|intval}, {$group.id_group|intval});{/if}aw_select('{$group.id_group|intval}',{$id_attribute|intval}, {$aw_currency.id_currency|intval});" value="{$aw_group_attribute.0|intval}" {if $group.default == $id_attribute} checked="checked"{/if} />
										{if $aw_ps_version < 1.6}&nbsp;{/if}
										{if $group.default == $id_attribute}
											<input type="hidden" name="pi_default_{$group.id_group|intval}" id="pi_default_{$group.id_group|intval}" value="{$aw_default_impact|floatval}" />
										{/if}
										{if $group.group_per_row > 1}</center>{/if}
									</div>
									<div id="aw_impact_cell{$id_attribute|intval}" class="aw_group_{$group.id_group|intval}_{$id_attribute|intval}_h aw_group_resp" style="{if !$group.group_layout}float:left;text-align:center; {if $group.group_height}line-height:{$group.group_height|floatval}px;{/if} {else}width:100%;clear: left;{/if}">
										{if $group.group_per_row > 1}<center>{/if}
										<div {if !$group.group_layout}style="float:left"{/if}  onclick="
										{if $aw_ps_version >= 1.5}
											{if $group.group_color == 1}
												colorPickerClickAW({$id_attribute|intval}, {$group.id_group|intval});
											{/if}
										{/if}
										aw_select('{$group.id_group|intval}',{$aw_group_attribute.0|intval}, {$aw_currency.id_currency|intval})">
											{if !$group.group_hide_name}{$aw_group_attribute.1|escape:'htmlall':'UTF-8'}{/if}
										</div>
										{foreach from=$attributeImpacts key=id_attributeImpact item=attributeImpact}
											{if $id_attribute == $attributeImpact.id_attribute}
												{math assign="aw_pi" equation='x-y' x=$attributeImpact.price y=$aw_default_impact} 
												<input type="hidden" name="pi_{$group.id_group|intval}_{$id_attribute|intval}" id="pi_{$group.id_group|intval}_{$id_attribute|intval}" value="{$attributeImpact.price|floatval}" />
												{if $aw_pi_display}
													<div id="price_change_{$id_attribute|intval}" {if $group.group_layout}class="aw_price_change"{else}class="aw_price_change_hor"{/if}>
													
													</div>
												{/if}
											{/if}
										{/foreach}
										</center>
									</div>
                   					
                    		</div>
                    		</td>
                    		{if $smarty.foreach.aw_loop.iteration < $group.attributes|@count && $group.group_per_row > 0 && $smarty.foreach.aw_loop.iteration % $group.group_per_row == 0}
                    		</tr>
							{assign var="aw_row_count" value=$aw_row_count+1}
                    		<tr style="height:20px;" id="aw_group_{$group.id_group|intval}_{$aw_row_count|intval}_r">
                    		{/if}
                    		{/strip}
						{/foreach}
                		</tr>
						</table>
					{else}
						{assign var='id_attribute' value=$group.attributes.0.0}
						<table cellpadding="6">
                		<tr style="height:20px">
							{if $group.group_color == 1}
               					<td align="left">
								{if file_exists($col_img_dir|cat:$group.attributes.0.0|cat:'.jpg')}
									<div style="{if isset($group.group_width)}width:{$group.group_width|floatval}px;height:{$group.group_height|floatval}px;{/if}">
										<img {if $group.group_resize}style="width:{$group.group_width|floatval}px;height:{$group.group_height|floatval}px;"{/if} src="{$img_col_dir|escape:'htmlall':'UTF-8'}{$group.attributes.0.0|intval}.jpg" alt="" title="{$group.attributes.0.1|escape:'htmlall':'UTF-8'}" />
        							</div>
        						{else}
									<div style="{if isset($group.group_width)}width:{$group.group_width|floatval}px;height:{$group.group_height|floatval}px;{/if}background-color:{$group.attributes.0.2|escape:'htmlall':'UTF-8'};">
									{if $group.attributes.0.2 != ""}
            							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            						{/if}
            						</div>
            					{/if}
               					</td>
       						{/if}
                    		<td align="left" style="width:25px;"> 
   	            				<input style="border: none" name="aw_group_{$group.id_group|intval}" value="{$group.attributes.0.0|intval}" type="checkbox" class="aw_attribute_selected aw_group_{$group.id_group|intval}_{$id_attribute|intval}_d" onclick="if (this.checked){ldelim}
								{if $aw_ps_version >= 1.5}
											
								
									{if $aw_pi_display == "total"}
										$('#price_change_'+{$group.attributes.1.0|intval}).attr('id', 'price_change_'+{$id_attribute|intval});
									{/if}
								
								{/if}
								aw_select('{$group.id_group|intval}', {$group.attributes.0.0|intval}, {$aw_currency.id_currency|intval});
								
								
								{rdelim}else{ldelim}
								{if $aw_ps_version >= 1.5}
											
									
									{if $aw_pi_display == "total"}
										$('#price_change_'+{$group.attributes.0.0|intval}).attr('id', 'price_change_'+{$group.attributes.1.0|intval});
									{/if}
									
								{/if}
								aw_select('{$group.id_group|intval}', {$group.attributes.1.0|intval}, {$aw_currency.id_currency|intval});
								
								{rdelim}" 
								{if $group.default == $group.attributes.0.0}checked{/if}/>&nbsp;
                    			{if $group.default == $id_attribute}
                   					<input type="hidden" name="pi_default_{$group.id_group|intval}" id="pi_default_{$group.id_group|intval}" value="{$aw_default_impact|floatval}" />
                   				{/if}
                    		</td>
   		           			<td align="left">{$group.attributes.0.1|escape:'htmlall':'UTF-8'}
	                    		{foreach from=$attributeImpacts key=id_attributeImpact item=attributeImpact}
               						{if $group.attributes.0.0 == $attributeImpact.id_attribute}
                   						{assign var='aw_pi' value=$attributeImpact.price}
                   								{if $group.group_layout}<br />{/if}
												{if $aw_pi_display == "total"}
				                      				<span id="price_change_{$group.attributes.0.0|intval}">
												{else}
					                      			<span id="price_change_{$group.attributes.0.0|intval}" style="display:none"></span><span>
												{/if}
       	                						{if $aw_pi_display == "" || $aw_pi_display == "total"}
                       								&nbsp;
                   								{elseif $aw_pi > 0}
	                   								{if !$group.group_layout} {elseif $group.group_per_row > 1 && !$group.group_hide_name}<br />{/if}[{l s='Add' mod='attributewizard'} {if $priceDisplay != 1}{$aw_pi+($aw_tax_rate*$aw_pi/100)|escape:'htmlall':'UTF-8'}{else}{$aw_pi|escape:'htmlall':'UTF-8'}{/if}]
                   								{elseif $aw_pi < 0}
	                   								{if !$group.group_layout} {elseif $group.group_per_row > 1 && !$group.group_hide_name}<br />{/if}[{l s='Subtract' mod='attributewizard'} {if $priceDisplay != 1}{$aw_pi|abs+($aw_tax_rate*$aw_pi|abs/100)|escape:'htmlall':'UTF-8'}{else}{$aw_pi|abs|escape:'htmlall':'UTF-8'}{/if}]
                   								{/if}
                     							</span>
		                   				<input type="hidden" name="pi_{$group.id_group|intval}_{$id_attribute|intval}" id="pi_{$group.id_group|intval}_{$id_attribute|intval}" value="{$attributeImpact.price|floatval}" />
                   					{/if}
               					{/foreach}
               				</td>
               			</tr>
							
               			{if !isset($group.checkbox) || $group.checkbox == ""}
							</table>
						{/if}
					{/if}						
                    {if !isset($group.checkbox) || $group.checkbox == ""}
					</div>
					<b class="xbottom"><b class="xb4 xbbot"></b><b class="xb3 xbbot"></b><b class="xb2 xbbot"></b><b class="xb1"></b></b>
				</div>
			</td>
		</tr>
					{/if}
	{else}
   						<tr style="height:20px" class="aw_group_{$group.id_group|intval}_{$id_attribute|intval}_h">
							{if $group.group_color == 1}
               					<td align="left">
								{if file_exists($col_img_dir|cat:$id_attribute|cat:'.jpg')}
									<div style="{if isset($group.group_width)}width:{$group.group_width|floatval}px;height:{$group.group_height|floatval}px;{/if}">
										<img {if $group.group_resize}style="width:{$group.group_width|floatval}px;height:{$group.group_height|floatval}px;"{/if} src="{$img_col_dir|escape:'htmlall':'UTF-8'}{$group.attributes.0.0|intval}.jpg" alt="" title="{$group.attributes.0.1|escape:'htmlall':'UTF-8'}" />
        							</div>
        						{else}
									<div style="{if isset($group.group_width)}width:{$group.group_width|floatval}px;height:{$group.group_height|floatval}px;{/if}background-color:{$group.attributes.0.2|escape:'htmlall':'UTF-8'};">
									{if $group.attributes.0.2 != ""}
            							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            						{/if}
            						</div>
            					{/if}
               					</td>
       						{/if}
       						<td align="left" size="1">
   								<input style="border: none" type="checkbox" class="aw_attribute_selected aw_group_{$group.id_group|intval}_{$id_attribute|intval}_d"  name="aw_group_{$group.id_group|intval}" value="{$group.attributes.0.0|intval}" onclick="if (this.checked){ldelim}
								{if $aw_ps_version >= 1.5}
											{if $group.group_color == 1}
												colorPickerClickAW({$id_attribute|intval}, {$group.id_group|intval});
											{/if}
								{/if}
								aw_select('{$group.id_group|intval}', {$group.attributes.0.0|intval}, {$aw_currency.id_currency|intval})
								{rdelim}else{ldelim}
								{if $aw_ps_version >= 1.5}
											{if $group.group_color == 1}
												colorPickerClickAW({$group.attributes.1.0|intval}, {$group.id_group|intval});
											{/if}
								{/if}
								aw_select('{$group.id_group|intval}', {$group.attributes.1.0|intval}, {$aw_currency.id_currency|intval}){rdelim}"
								{if $group.default == $group.attributes.0.0}checked{/if}/>&nbsp;
                    			{if $group.default == $id_attribute}
                   					<input type="hidden" name="pi_default_{$group.id_group|intval}" id="pi_default_{$group.id_group|intval}" value="{$aw_default_impact|floatval}" />
                   				{/if}
       						</td>
   							<td align="left">{$group.attributes.0.1|escape:'htmlall':'UTF-8'}
								{foreach from=$attributeImpacts key=id_attributeImpact item=attributeImpact}
								{strip}
     								{if $group.attributes.0.0 == $attributeImpact.id_attribute}
           								{assign var='aw_pi' value=$attributeImpact.price}
											{if $aw_pi_display == "total"}
				                      			<span id="price_change_{$group.attributes.0.0|intval}">
											{else}
				                      			<span id="price_change_{$group.attributes.0.0|intval}" style="display:none"></span><span>
											{/if}
                       						{if $aw_pi_display == "" || $aw_pi_display == "total"}
                       							&nbsp;
                   							{elseif $aw_pi > 0}
                   								{if !$group.group_layout} {elseif $group.group_per_row > 1 && !$group.group_hide_name}<br />{/if}[{l s='Add' mod='attributewizard'} {if $priceDisplay != 1}{$aw_pi+($aw_tax_rate*$aw_pi/100)|escape:'htmlall':'UTF-8'}{else}{$aw_pi|escape:'htmlall':'UTF-8'}{/if}]
                   							{elseif $aw_pi < 0}
                   								{if !$group.group_layout} {elseif $group.group_per_row > 1 && !$group.group_hide_name}<br />{/if}[{l s='Subtract' mod='attributewizard'} {if $priceDisplay != 1}{$aw_pi|abs+($aw_tax_rate*$aw_pi|abs/100)|escape:'htmlall':'UTF-8'}{else}{$aw_pi|abs|escape:'htmlall':'UTF-8'}{/if}]
       									{/if}
				       					</span>
                   						<input type="hidden" name="pi_{$group.id_group|intval}_{$id_attribute|intval}" id="pi_{$group.id_group|intval}_{$id_attribute|intval}" value="{$attributeImpact.price|floatval}" />
           							{/if}
           						{/strip}
				       			{/foreach}
               				</td>
               			</tr>
						{if isset($group.checkbox) && $group.checkbox == "end"}
						</table>
					</div>
					<b class="xbottom"><b class="xb4 xbbot"></b><b class="xb3 xbbot"></b><b class="xb2 xbbot"></b><b class="xb1"></b></b>
				</div>
			</td>
		</tr>
						{/if}
	{/if}
{/foreach}
	{if $aw_add_to_cart == "both" || $aw_add_to_cart == "bottom"}
	<tr>
		<td align="center" colspan="6">
			<div id="aw_in_stock">
			<table>
			<tr>
				{if $aw_popup}
					<td align="right">
						
                                            <a itemprop="url" class="button lnk_view btn btn-default" onclick="$('#aw_container').fadeOut(1000);$('#aw_background').fadeOut(1000);aw_customize_func();" title="{l s='Close' mod='attributewizard'}">
                                                    <span>{l s='Close' mod='attributewizard'}</span>
                                            </a>
						
					</td>
				{/if}
				<td align="right">
					
                                    <div class="box-info-product buttons_bottom_block no-print">
                                        <button id="aw_footer_add_to_cart" {if $PS_CATALOG_MODE} style="display: none;"{/if} type="button" name="Submit" class="btn btn-primary add-to-cart" onclick="aw_add_to_cart()">
                                              <i class="material-icons shopping-cart"></i>      
                                            <span>{l s='Add to cart' mod='attributewizard'}</span>
                                        </button>
                                    </div>
					
				</td>
				<td align="left" class="aw_quantity_additional bottom_additional" {if $PS_CATALOG_MODE} style="display: none;"{/if}>
					<span style="">
                                            {l s='Quantity' mod='attributewizard'}:
                                        </span>
                                            <input type="text" min="{$product.minimal_quantity|intval}" name="aw_q2" id="aw_q2" onkeyup="$('#quantity_wanted').val(this.value);$('#aw_q1').val(this.value);" value="1" />
					
					
					
				</td>
				<td align="left">
					&nbsp;&nbsp;<b class="price our_price_display" id="aw_price"></b>
				</td>
			</tr>
			</table>
			</div>
				<div class="bottom_not_in_stock">
				
				</div>
			
			<div id="aw_not_in_stock"  style="display:none">
			</div>
		</td>
	</tr>
	{/if}
</table>
</form>
					</div>
						</div>

</div>


{/if}

<a style="display: none;" id="exampleLink">Enable Follow</a>
<script type="text/javascript">

    productBasePriceTaxExcl = {$productBasePriceTaxIncl|floatval};
    productPriceTaxExcluded = {$productPriceTaxExcluded|floatval};
    noTaxForThisProduct = {if $noTaxForThisProduct}{$noTaxForThisProduct|boolval}{else}false{/if};
    taxRate = '{$taxRate|floatval}';
    default_eco_tax = '{$default_eco_tax|floatval}';
    ecotaxTax_rate = '{$ecotaxTax_rate|floatval}';
    productUnitPriceRatio = '{$productUnitPriceRatio|floatval}';
    displayPrice = {$displayPrice|floatval};
    allowBuyWhenOutOfStock = {$allowBuyWhenOutOfStock|floatval};
    baseDir = '{$base_dir|escape:'htmlall':'UTF-8'}';
    priceDisplayPrecision = {$priceDisplayPrecision|floatval};
    currencyFormat = '{$currencyFormat|escape:'htmlall':'UTF-8'}';
    currencySign = '{$currencySign|escape:'htmlall':'UTF-8'}';
    currencyBlank = '{$currencyBlank|escape:'htmlall':'UTF-8'}';
    roundMode = {$roundMode|intval};
    aw_no_customize = {$aw_no_customize|intval};
    
    var aw_page_name = "{$page_name|escape:'htmlall':'UTF-8'}";
    PS_CATALOG_MODE = '{$PS_CATALOG_MODE|boolval}';
     productAvailableForOrder = {$productAvailableForOrder|intval};
    {if isset($customer_group_without_tax)}
    customerGroupWithoutTax = '{$customer_group_without_tax|boolval}';
    {else}
    customerGroupWithoutTax = false;
    {/if}

    var selectedCombination = [];
</script>
<script type="text/javascript">
combinations = [];
{if isset($combinations) && $combinations}
	
    {function jsadd keypart=''}
        {foreach $data as $key => $item}					
            {if not $item|@is_array}
                {if $keypart eq ''}
                    combinations['{$key|escape:'htmlall':'UTF-8'}'] = '{$item|escape:'htmlall':'UTF-8'}'					
                 {else}
                    combinations{$keypart nofilter}['{$key|escape:'htmlall':'UTF-8'}'] = '{$item|escape:'htmlall':'UTF-8'}'		
                {/if}
            {else}	
                combinations{$keypart nofilter}['{$key}'] = [];
                {jsadd data = $item keypart = "`$keypart`['`$key`']" }
            {/if}
        {/foreach}
    {/function}
   
   {jsadd data=$combinations}
	
	
{/if}
    
    attributesCombinations = [];
{if isset($attributesCombinations) && $attributesCombinations}
	
    {function jsaddAttributeCombinations keypart=''}
        {foreach $data as $key => $item}					
            {if not $item|@is_array}
                {if $keypart eq ''}
                    attributesCombinations['{$key|escape:'htmlall':'UTF-8'}'] = '{$item|escape:'htmlall':'UTF-8'}'					
                 {else}
                    attributesCombinations{$keypart nofilter}['{$key|escape:'htmlall':'UTF-8'}'] = '{$item|escape:'htmlall':'UTF-8'}'		
                {/if}
            {else}	
                attributesCombinations{$keypart nofilter}['{$key|escape:'htmlall':'UTF-8'}'] = [];
                {jsaddAttributeCombinations data = $item keypart = "`$keypart`['`$key`']" }
            {/if}
        {/foreach}
    {/function}
   
   {jsaddAttributeCombinations data=$attributesCombinations}
	
	
{/if}
    product_specific_price = [];
{if isset($product_specific_price) && $product_specific_price}
	
    {function jsaddSpecificPrice keypart=''}
        {foreach $data as $key => $item}					
            {if not $item|@is_array}
                {if $keypart eq ''}
                    product_specific_price['{$key|escape:'htmlall':'UTF-8'}'] = '{$item|escape:'htmlall':'UTF-8'}'					
                 {else}
                    product_specific_price{$keypart nofilter}['{$key|escape:'htmlall':'UTF-8'}'] = '{$item|escape:'htmlall':'UTF-8'}'		
                {/if}
            {else}	
                product_specific_price{$keypart nofilter}['{$key|escape:'htmlall':'UTF-8'}'] = [];
                {jsaddSpecificPrice data = $item keypart = "`$keypart`['`$key`']" }
            {/if}
        {/foreach}
    {/function}
   
   {jsaddSpecificPrice data=$product_specific_price}	
	
{/if}
    
var selectedCombination = [];
</script>

<!-- /MODULE AttributeWizard -->
