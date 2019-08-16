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
<div class="panel po_main_content" id="advanced_settings">
    <form action="{$request_uri nofilter}" method="post" name="wizard_form" id="wizard_form">
        <input type="hidden" name="aw_id_lang" id="aw_id_lang" value="{$id_lang|intval}">
        <div class="panel_header">
            <div class="panel_title">{l s='Advanced Settings' mod='attributewizard'}</div>
            <div class="panel_info_text">
                
                <div class="switch_block">
                    <span class="switch_label">{l s='Turn on TinyMCE Editor for all' mod='attributewizard'}</span>
                    <span class="switch presto-switch presto-fixed-width-lg">
                        <input type="radio" name="tiny_mce_all" id="tiny_mce_all_on" value="1">
                        <label for="tiny_mce_all_on" class="radioCheck"></label>
                        <input type="radio" name="tiny_mce_all" id="tiny_mce_all_off" value="0" checked="checked">
                        <label for="tiny_mce_all_off" class="radioCheck"></label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
                  
                <div class="expand_all" >
                    <span class="expand" onClick="presto_toggle_all(0)">{l s='Collapse all' mod='attributewizard'}</span>
                    <span class="arrow_up" onClick="presto_toggle_all(0)">
                        
                    </span>
                    <span class="collapse hideADN" onClick="presto_toggle_all(1)">{l s='Expand all' mod='attributewizard'}</span>
                    <span class="arrow_down hideADN" onClick="presto_toggle_all(1)">
                        
                    </span>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <ul id="aw_first-languages">
        {foreach from=$languages key=myId item=language name=languages_list}
            <li id="aw_lang_{$language.id_lang|intval}" {if $language.id_lang == $id_lang}class="selected_language"{/if}>
                <input type="hidden" name="aw_li_lang_{$smarty.foreach.languages_list.index|intval}" id="aw_li_lang_{$smarty.foreach.languages_list.index|intval}" value="{$language.id_lang|intval}" />
                <img onclick="aw_update_lang(true);aw_select_lang({$language.id_lang|intval})" src="{$theme_lang_dir|escape:'htmlall':'UTF-8'}{$language.id_lang|intval}.jpg" alt="{$language.name|escape:'htmlall':'UTF-8'}" />
            </li>
        {/foreach}
        </ul>
        <div class="clear"></div>                      
            
        <div id="sortable" class="attribute_groups_sort table_format">
            
            <div class="row_format row_header">
                <div class="column_format column_1 header_column">
                    
                </div>
                <div class="column_format column_2 header_column">
                    {l s='Group Name' mod='attributewizard'}
                    
                </div>
                <div class="column_format column_3 header_column">
                    {l s='Group Image' mod='attributewizard'}
                    
                </div>
                <div class="column_format column_4 header_column">
                    {l s='Attribute Type' mod='attributewizard'}
                    
                </div>
                <div class="column_format column_5 header_column">
                    {l s='Attribute Order' mod='attributewizard'}
                </div>
                <div class="column_format column_6 header_column">
                </div>
                <div class="clear"></div>
            </div>
                

            {foreach from=$ordered_groups key=myId item=group name=ordered_group}
                <div class="row_format" id="row_{$group.id_attribute_group|intval}">
                    <div class="column_format column_1">
                        <img src="{$path|escape:'htmlall':'UTF-8'}views/img/sort_icon.png" />
                    </div>
                    <div class="column_format column_2">
                        <div class="fixed_height" onclick="presto_toggle({$group.id_attribute_group|intval})">
                            {$group.group_name|escape:'htmlall':'UTF-8'}
                        </div>
                        <div data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" class="fixed_empty_height"></div>
                        <div data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" id="description_{$group.id_attribute_group|intval}_text">
                            {l s='Add Description' mod='attributewizard'}
                        </div>
                        <div data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" id="description_container_{$group.id_attribute_group|intval}" class="awp_tinymce" >
                            <textarea class="autoload_rte" onchange="aw_update_lang(false)" id="description_{$group.id_attribute_group|intval}" name="description_{$group.id_attribute_group|intval}" ></textarea>

                            {foreach $languages as $language}
                                {assign var='tmpDescr' value="group_description_`$language.id_lang`"}
                                {assign var='tmpHeader' value="group_header_`$language.id_lang`"}
                               
                                <input type="hidden" id="description_{$group.id_attribute_group|intval}_{$language.id_lang|intval}" name="description_{$group.id_attribute_group|intval}_{$language.id_lang|intval}" value="{if isset($group.$tmpDescr)}{$group.$tmpDescr nofilter}{/if}">
                                <input type="hidden" class="full_width_input" name="group_header_{$group.id_attribute_group|intval}_{$language.id_lang|intval}" id="group_header_{$group.id_attribute_group|intval}_{$language.id_lang|intval}" value="{if isset($group.$tmpHeader)}{$group.$tmpHeader nofilter}{/if}">
                            {/foreach}
                        </div>
                        <div data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" class="switch_block">
                            <span class="switch_label">{l s='TinyMCE Editor' mod='attributewizard'}</span>
                            <span class="switch presto-switch presto-fixed-width-lg">
                                <input class="tiny_mce_on" data-id="{$group.id_attribute_group|intval}" type="radio" name="tiny_mce_{$group.id_attribute_group|intval}" id="tiny_mce_{$group.id_attribute_group|intval}_on" value="1" >
                                <label for="tiny_mce_{$group.id_attribute_group|intval}_on" class="radioCheck"></label>
                                <input class="tiny_mce_off" data-id="{$group.id_attribute_group|intval}" type="radio" name="tiny_mce_{$group.id_attribute_group|intval}" id="tiny_mce_{$group.id_attribute_group|intval}_off" value="0" checked="checked">
                                <label for="tiny_mce_{$group.id_attribute_group|intval}_off" class="radioCheck"></label>
                                <a class="slide-button btn"></a>
                            </span>
                        </div>
                    </div>
                    <div class="column_format column_3">
                        <input type="hidden" id="id_group_{$smarty.foreach.ordered_group.index|intval}" name="id_group_{$smarty.foreach.ordered_group.index|intval}" value="{$group.id_attribute_group|intval}" />

                        <div id="upload_container_{$smarty.foreach.ordered_group.index|intval}">
                            
                            <div data-order="{$smarty.foreach.ordered_group.index|intval}" data-id="{$group.id_attribute_group|intval}"  class="fixed_height" id="image_container_{$smarty.foreach.ordered_group.index|intval}">
                                {if $group.filename}
                                    <img src="{$group.filename nofilter}" />
                                    
                                {/if}
                            </div>
                            
                            <div data-order="{$smarty.foreach.ordered_group.index|intval}" data-id="{$group.id_attribute_group|intval}" class="fixed_empty_height"></div>
                            {if $group.filename}
                            <div id="edit_new_block_{$smarty.foreach.ordered_group.index|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" data-id="{$group.id_attribute_group|intval}">   
                            {/if}
                                <input id="upload_button_{$smarty.foreach.ordered_group.index|intval}" class="button {if $group.filename}edit_btn{else}upload_btn{/if}" value="{if $group.filename}{l s='Edit' mod='attributewizard'}{else}{l s='Upload Image' mod='attributewizard'}{/if}" type="button">

                                
                                <input id="delete_button_{$smarty.foreach.ordered_group.index|intval}" class="button delete_btn {if !$group.filename}hideADN{/if}" value="{l s='Delete' mod='attributewizard'}" type="button">
                                
                            {if $group.filename}
                            </div>
                            {/if}    
                            {if $group.filename}
                               
                                
                               
                                <div data-order="{$smarty.foreach.ordered_group.index|intval}" data-id="{$group.id_attribute_group|intval}" id="image_url_{$smarty.foreach.ordered_group.index|intval}">
                                    {l s='Image URL:' mod='attributewizard'} 
                                    <input type="text" name="group_url_{$group.id_attribute_group|intval}" value="{if isset($group.group_url)}{$group.group_url nofilter}{else}{/if}">
                                    <br />
                                </div>
                            {else}
                                
                                <div data-order="{$smarty.foreach.ordered_group.index|intval}" data-id="{$group.id_attribute_group|intval}" id="image_url_{$smarty.foreach.ordered_group.index|intval}">
                                    {l s='Image URL:' mod='attributewizard'} 
                                    <input type="text" name="group_url_{$group.id_attribute_group|intval}" value="{if isset($group.group_url)}{$group.group_url nofilter}{/if}">
                                    <br />
                                </div>   
                            
                                
                            {/if}
                        </div>

                    </div>
                    <div class="column_format column_4">
                        <select class="fixed_height attribute_type" style="" name="group_type_{$group.id_attribute_group|intval}" onchange="presto_toggle({$group.id_attribute_group|intval}, true);">
                            <option value="radio" {if $group.group_type == 'radio'}selected{/if}>{l s='Radio Button' mod='attributewizard'}</option>
                            <option value="dropdown" {if $group.group_type == 'dropdown'}selected{/if}>{l s='Dropdown' mod='attributewizard'}</option>
                            {if ($group.attributes|@count == 2)}                               
                                <option value="checkbox" {if $group.group_type == 'checkbox'}selected{/if}>{l s='Checkbox' mod='attributewizard'}</option> 
                            {/if}
                        </select>
                        <div data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" class="fixed_empty_height"></div>
                        <div class="" data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" id="ipr_container_{$group.id_attribute_group|intval}">
                            <div class="two_columns checkboxView {if $group.group_type != 'checkbox'}hideADN{/if}">
                                <div class="columns">
                                    <div class="left_column">
                                        {l s='Group Header' mod='attributewizard'}
                                    </div>
                                    <div class="right_column">
                                        {assign var='tmpGHeader' value="group_header_`$id_lang`"}
                                        <input type="text" class="full_width_input" onchange="aw_update_lang(false)" name="group_header_{$group.id_attribute_group|intval}" id="group_header_{$group.id_attribute_group|intval}" value="{if isset($group.$tmpGHeader)}{$group.$tmpGHeader nofilter}{/if}" />
                                   
                                    </div>
                                </div>

                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="" data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" id="ipr_container_{$group.id_attribute_group|intval}">
                            <div class="two_columns radioView {if $group.group_type != 'radio'}hideADN{/if}">
                                <div class="columns">
                                    <div class="left_column">
                                        {l s='Items Per Row' mod='attributewizard'}
                                    </div>
                                    <div class="right_column">
                                        <input type="text" name="group_per_row_{$group.id_attribute_group|intval}" id="group_per_row_{$group.id_attribute_group|intval}" value="{if isset($group.group_per_row) && $group.group_per_row >= 1}{$group.group_per_row|intval}{else}1{/if}" />
                                    </div>
                                </div>

                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="" data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" id="ipr_container_{$group.id_attribute_group|intval}">
                            <div class="two_columns radioView {if $group.group_type != 'radio'}hideADN{/if}">
                                <div class="columns">
                                    <div class="left_column">
                                        {l s='Items Layout' mod='attributewizard'}
                                    </div>
                                    <div class="right_column">
                                        
                                        <input type="radio" name="group_layout_{$group.id_attribute_group|intval}" id="group_layout_{$group.id_attribute_group|intval}" value="0" {if isset($group.group_layout)}{if !$group.group_layout}checked{/if}{/if}/>
                                        {l s='Horizontal' mod='attributewizard'}
                                        <br/>
                                        <input type="radio" name="group_layout_{$group.id_attribute_group|intval}" id="group_layout_{$group.id_attribute_group|intval}" value="1" {if isset($group.group_layout)}{if $group.group_layout}checked{/if}{/if}/>
                                        {l s='Vertical' mod='attributewizard'}
                                   
                                    </div>
                                </div>

                            </div>
                            <div class="clear"></div>
                        </div>
                                        
                        {if $group.group_color == 1}
                           <div class="" data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" id="ipr_container_{$group.id_attribute_group|intval}">
                                <div class="two_columns dropdownView {if $group.group_type != 'dropdown'}hideADN{/if}">
                                    <div class="columns">
                                        <div class="left_column">
                                            {l s='Display multiple images' mod='attributewizard'}
                                        </div>
                                        <div class="right_column">
                                           <input type="checkbox" name="group_display_multiple_{$group.id_attribute_group|intval}" id="group_display_multiple_{$group.id_attribute_group|intval}" value="1" {if isset($group.group_display_multiple) && $group.group_display_multiple == 1}checked{/if}/>
 
                                        </div>
                                    </div>

                                </div>
                               <div class="clear"></div>
                            </div> 
                           <div data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" id="ipr_container_{$group.id_attribute_group|intval}">
                                <div class="two_columns">
                                    <div class="columns">
                                        <div class="left_column">
                                            {l s='Color & Texture Size' mod='attributewizard'}
                                        </div>
                                        <div class="right_column">
                                           
                                            <input type="text" name="group_width_{$group.id_attribute_group|intval}" id="group_width_{$group.id_attribute_group|intval}" value="{if isset($group.group_width)}{$group.group_width|intval}{/if}" />
                                            {l s='W' mod='attributewizard'}
                                            <br/>
                                            <br/>
                                            <input type="text" name="group_height_{$group.id_attribute_group|intval}" id="group_height_{$group.id_attribute_group|intval}" value="{if isset($group.group_height)}{$group.group_height|intval}{/if}" />
                                            {l s='H' mod='attributewizard'}
                                        </div>
                                    </div>

                                </div>
                                <div class="clear"></div>
                            </div>            
                            <div data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" id="ipr_container_{$group.id_attribute_group|intval}">
                                <div class="two_columns">
                                    <div class="columns">
                                        <div class="left_column">
                                            {l s='Resize Textures' mod='attributewizard'}
                                        </div>
                                        <div class="right_column">
                                           
                                           <input type="checkbox" name="group_resize_{$group.id_attribute_group|intval}" id="group_resize_{$group.id_attribute_group|intval}" value="1" {if isset($group.group_resize) && $group.group_resize == 1}checked{/if}/>
                            
                                        </div>
                                    </div>

                                </div>
                               <div class="clear"></div>
                            </div>   
                            <div class="" data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" id="ipr_container_{$group.id_attribute_group|intval}">
                                <div class="two_columns radio checkbox {if $group.group_type == 'dropdown'}hideADN{/if}">
                                    <div class="columns">
                                        <div class="left_column">
                                            {l s='Hide Item Name' mod='attributewizard'}
                                        </div>
                                        <div class="right_column">
                                           
                                           <input type="checkbox" name="group_hide_name_{$group.id_attribute_group|intval}" id="group_hide_name_{$group.id_attribute_group|intval}" value="1" {if isset($group.group_hide_name) && $group.group_hide_name == 1}checked{/if}/>
                                    
                                        </div>
                                    </div>

                                </div>
                               <div class="clear"></div>
                            </div>         
                                           
                        {/if}
                                                                                       
                        <div data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" id="ipr_container_{$group.id_attribute_group|intval}">
                            <div class="two_columns">
                                <div class="columns">
                                    <div class="left_column">
                                        {l s='Do Not Disable / Hide' mod='attributewizard'}
                                    </div>
                                    <div class="right_column">

                                       <input type="checkbox" alt="{if isset($group.group_no_disable) && $group.group_no_disable == 1}{$group.group_no_disable}{else}0{/if}" name="group_no_disable_{$group.id_attribute_group|intval}" id="group_no_disable_{$group.id_attribute_group|intval}" value="1" {if isset($group.group_no_disable) && $group.group_no_disable == 1}checked{/if}/>
                                    
                                    </div>
                                </div>

                            </div>
                           <div class="clear"></div>
                        </div> 

                    </div>
                    <div class="column_format column_5">
                        <div class="fixed_height">
                        </div>
                        <div data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" class="fixed_empty_height"></div>
                        <div data-id="{$group.id_attribute_group|intval}" data-order="{$smarty.foreach.ordered_group.index|intval}" class="module_display_{$smarty.foreach.ordered_group.index|intval}" id="display_{$smarty.foreach.ordered_group.index|intval}">
                            <div class="attribute_values_sort attribute_{$group.id_attribute_group|intval} table_format table_compact">
                                
                                
                                {foreach from=$group.attributes key=myId item=attribute name=ordered_attribute}
                                    <div id="{$group.id_attribute_group|intval}_{$attribute['id_attribute']|intval}" class="row_format {if $smarty.foreach.ordered_attribute.index > 9} hideADN {/if}">
                                        <div class="column_format column_1">
                                            <img src="{$path|escape:'htmlall':'UTF-8'}views/img/sort_icon.png">
                                        </div>
                                        <div class="column_format column_2">
                                            {$attribute['attribute_name']|escape:'htmlall':'UTF-8'}
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                {/foreach}
                                
                            </div>
                            {if $group.attributes|@count > 10}
                            <div id="display_more_{$smarty.foreach.ordered_group.index|intval}" class="display_more">
                                {l s='Show All' mod='attributewizard'} {$group.attributes|@count|intval} {l s='attributes' mod='attributewizard'}
                                <span class="more_arrow_down"></span>
                            </div>   
                             <div id="hide_more_{$smarty.foreach.ordered_group.index|intval}" class="hide_more display_more">
                                {l s='Hide attributes' mod='attributewizard'}
                                <span class="more_arrow_up"></span>
                            </div>
                            {/if}
                        </div>
                    </div>    
                    <div class="column_format column_6">
                        <div id="expand_{$group.id_attribute_group|intval}" class="expand_collapse" onClick="presto_toggle({$group.id_attribute_group|intval})">                        
                            <span class="arrow_up hideADN">

                            </span>
                            <span class="arrow_down">

                            </span>
                        </div>
                    </div>  
                    <div class="clear"></div>
                </div>
            {/foreach}
            
            
        </div>
        <br/><br/>
        <div class="columns">
            <div class="left_column">
                 <input type="submit" value="{l s='Update' mod='attributewizard'}" name="submitAdvancedChanges" class="submit_button" />
            </div>
            <div class="right_column">
                
            </div>
        </div>    
    </form>            
</div>
