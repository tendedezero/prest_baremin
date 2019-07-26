<table class="supercheckout-form shipping_update_form" id="" style="margin-top:10px;">
    <input type='hidden' name='kbshipping_update_data' value="1"/>
    {assign var='display_row' value=''}
    {foreach from=$settings['shipping_address'] key='p_address_key' item='p_address_field'}
        {foreach from=$addresses key='key' item='address_value'}
           {if $address_value['id_address'] == $selected_id_address}
        {$display_row = ''}
        {if $settings['shipping_address'][$p_address_key][$user_type]['display'] eq 1 || (isset($settings['shipping_address'][$p_address_key]['conditional']) && $settings['shipping_address'][$p_address_key]['conditional'] eq 1)}
            {* changes by rishabh jain *}
            {$display_row = ''}
            {$google_region_type = ''}
            {if $p_address_key eq 'dni' && !$need_dni}
                {$display_row = 'display:none;'}
            {else if $p_address_key eq 'dni' && $settings['shipping_address'][$p_address_key][$user_type]['display'] == 0}
                {$display_row = 'display:none;'}
            {/if}
            {if $p_address_key eq 'vat_number' && $settings['shipping_address'][$p_address_key][$user_type]['display'] == 0}
                {$display_row = 'display:none;'}
            {/if}
            {if ($p_address_key eq 'postcode' || $p_address_key eq 'id_country' || $p_address_key eq 'id_state' || $p_address_key eq 'alias') && !$settings['shipping_address'][$p_address_key][$user_type]['require'] && !$settings['shipping_address'][$p_address_key][$user_type]['display']}
                {$display_row = 'display:none;'}
            {/if}
            {if $p_address_key eq 'postcode'}
                {$google_region_type='postal_code'}
            {else if $p_address_key eq 'address1'}
                {$google_region_type='street_number'}
            {else if $p_address_key eq 'address2'}
                {$google_region_type='route'}
            {else if $p_address_key eq 'city'}
                {$google_region_type='locality'}
            {else if $p_address_key eq 'id_country'}
                {$google_region_type='country'}
            {else if $p_address_key eq 'id_state'}
                {$google_region_type='administrative_area_level_1'}
            {/if}
            {if $p_address_key eq 'id_state'}
                <script>var show_shipping_state = {$settings['shipping_address'][$p_address_key][$user_type]['display']|intval};</script>
            {/if}
            {if $p_address_key eq 'postcode'}
                <script>var show_shipping_postcode = {$settings['shipping_address'][$p_address_key][$user_type]['display']|intval};</script>

            {/if}
            {* changes over*}
            
            {if $settings['shipping_address'][$p_address_key]['html_format'] == 1}
                    <tr class="sort_data"  data-percentage="{$settings['shipping_address'][$p_address_key]['sort_order']|intval}" style="{$display_row}" >
                        <td>
                            <table>
                                <tr>
                                    <td>{l s={$settings['shipping_address'][$p_address_key]['title']} mod='supercheckout'}:<span style="{if $p_address_key != 'vat_number'}display:{if $settings['shipping_address'][$p_address_key][$user_type]['require'] eq 1}inline{else}none{/if}{else}{if $need_vat}display:inline{else}display:none{/if}{/if};" class="supercheckout-required">*</span>
                                        {if $p_address_key eq 'id_country' || $p_address_key eq 'id_state'}
                                            <select name="shipping_address[{$p_address_key}]" class="supercheckout-large-field">
                                                {if $p_address_key eq 'id_country'}
                                                    {foreach from=$countries item='country'}
                                                        <option value="{$country['id_country']|intval}" {if $country['id_country'] == $address_value[$p_address_key]} selected="selected"{/if}>{$country['name']|escape:'html':'UTF-8'}</option>                                        
                                                    {/foreach}
                                                {else}
                                                    <option value="0">{l s='Select State' mod='supercheckout'}</option>
                                                    <input type='hidden' id='shipping_saved_state' value="{$address_value[$p_address_key]}"/>
                                                {/if}                            
                                            </select>
                                        {else if  $p_address_key eq 'other'}
                                            <textarea autocomplete="off" name="shipping_address[{$p_address_key}]" value="" class="supercheckout-large-field" style="width: 96%;">{$address_value[$p_address_key]}</textarea>
                                        {else}
                                            <input autocomplete="off" type="text" name="shipping_address[{$p_address_key}]" value="{$address_value[$p_address_key]}" class="supercheckout-large-field" />
                                        {/if}

                                    </td>
            {elseif $settings['shipping_address'][$p_address_key]['html_format'] == 2}
                                    <td>{l s={$settings['shipping_address'][$p_address_key]['title']} mod='supercheckout'}:<span style="{if $p_address_key != 'vat_number'}display:{if $settings['shipping_address'][$p_address_key][$user_type]['require'] eq 1}inline{else}none{/if}{else}{if $need_vat}display:inline{else}display:none{/if}{/if};" class="supercheckout-required">*</span>
                                        {if $p_address_key eq 'id_country' || $p_address_key eq 'id_state'}
                                            <select name="shipping_address[{$p_address_key}]" class="supercheckout-large-field">
                                                {if $p_address_key eq 'id_country'}
                                                    {foreach from=$countries item='country'}
                                                        <option value="{$country['id_country']|intval}" {if $country['id_country'] == $address_value[$p_address_key]} selected="selected"{/if}>{$country['name']|escape:'html':'UTF-8'}</option>                                        
                                                    {/foreach}
                                                {else}
                                                    <option value="0">{l s='Select State' mod='supercheckout'}</option>
                                                    <input type='hidden' id='shipping_saved_state' value="{$address_value[$p_address_key]}"/>
                                                {/if}                            
                                            </select>
                                        {else if  $p_address_key eq 'other'}
                                            <textarea autocomplete="off" name="shipping_address[{$p_address_key}]" value="" class="supercheckout-large-field" style="width: 96%;">{$address_value[$p_address_key]}</textarea>
                                        {else}
                                            <input autocomplete="off" type="text" name="shipping_address[{$p_address_key}]" value="{$address_value[$p_address_key]}" class="supercheckout-large-field" />
                                        {/if}

                                    </td>
                                </tr>
                            </table>
                        </tr>
            {else}
            
            {*{if $p_address_key eq 'dni' && !$need_dni}
                {$display_row = 'display:none;'}
            {/if}
            {if $p_address_key eq 'vat_number' && !$need_vat}
                {$display_row = 'display:none;'}
            {/if}
            {if ($p_address_key eq 'postcode' || $p_address_key eq 'id_country' || $p_address_key eq 'id_state' || $p_address_key eq 'alias') && !$settings['shipping_address'][$p_address_key][$user_type]['require'] && !$settings['shipping_address'][$p_address_key][$user_type]['display']}
                {$display_row = 'display:none;'}
            {/if}*}
            <tr class="sort_data"  data-percentage="{$settings['shipping_address'][$p_address_key]['sort_order']|intval}" style="{$display_row}" >
                <td>{l s={$settings['shipping_address'][$p_address_key]['title']} mod='supercheckout'}:<span style="{if $p_address_key != 'vat_number'}display:{if $settings['shipping_address'][$p_address_key][$user_type]['require'] eq 1}inline{else}none{/if}{else}{if $need_vat}display:inline{else}display:none{/if}{/if};" class="supercheckout-required">*</span>
                    {if $p_address_key eq 'id_country' || $p_address_key eq 'id_state'}
                        <select name="shipping_address[{$p_address_key}]" class="supercheckout-large-field">
                            {if $p_address_key eq 'id_country'}
                                {foreach from=$countries item='country'}
                                    <option value="{$country['id_country']|intval}" {if $country['id_country'] == $address_value[$p_address_key]} selected="selected"{/if}>{$country['name']|escape:'html':'UTF-8'}</option>                                        
                                {/foreach}
                            {else}
                                <option value="0">{l s='Select State' mod='supercheckout'}</option>
                                <input type='hidden' id='shipping_saved_state' value="{$address_value[$p_address_key]}"/>
                            {/if}                            
                        </select>
                    {else if  $p_address_key eq 'other'}
                        <textarea autocomplete="off" name="shipping_address[{$p_address_key}]" value="" class="supercheckout-large-field" style="width: 96%;">{$address_value[$p_address_key]}</textarea>
                    {else}
                        <input autocomplete="off" type="text" name="shipping_address[{$p_address_key}]" value="{$address_value[$p_address_key]}" class="supercheckout-large-field" />
                    {/if}
                    
                </td>
            </tr>
            {/if}
        {/if}
        {/if}
        {/foreach}
    {/foreach}    
</table>
<div id="supercheckout_update_address_button">
            <input type="button" value="{l s='Save' mod='supercheckout'}" id="supercheckout_update_address_shipping" class="orangebuttonsmall">
            <input type="button" value="{l s='Cancel' mod='supercheckout'}" id="supercheckout_cancel_address" onclick="$('.shipping_update_form').remove();$('#supercheckout_update_address_button').remove();" class="orangebuttonsmall">
</div>
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2015 Knowband
*}