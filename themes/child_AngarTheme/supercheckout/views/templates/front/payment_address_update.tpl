<table class="supercheckout-form payment_update_form" id="" style="margin-top:10px;">
    <input type='hidden' name='kbpayment_update_data' value="1"/>
    {assign var='display_row' value=''}
    {assign var='google_region_type' value=''}
    {foreach from=$settings['payment_address'] key='p_address_key' item='p_address_field'}
        {$display_row = ''}
        {foreach from=$addresses key='key' item='address_value'}
            {if $address_value['id_address'] == $selected_id_address}
        {if $settings['payment_address'][$p_address_key][$user_type]['display'] eq 1 || (isset($settings['payment_address'][$p_address_key]['conditional']) && $settings['payment_address'][$p_address_key]['conditional'] eq 1)}
            {if $p_address_key eq 'dni' && !$need_dni}
                {$display_row = 'display:none;'}
            {/if}
            {if $p_address_key eq 'vat_number' && !$need_vat}
                {$display_row = 'display:none;'}
            {/if}
            {if ($p_address_key eq 'postcode' || $p_address_key eq 'id_country' || $p_address_key eq 'id_state' || $p_address_key eq 'alias') && !$settings['payment_address'][$p_address_key][$user_type]['require'] && !$settings['payment_address'][$p_address_key][$user_type]['display']}
                {$display_row = 'display:none;'}
            {/if}
            {if $p_address_key eq 'id_state'}
                        <script>var show_payment_state = {$settings['payment_address'][$p_address_key][$user_type]['display']|intval};</script>                                        
                    {/if}
                    {if $p_address_key eq 'postcode'}
                        <script>var show_payment_postcode = {$settings['payment_address'][$p_address_key][$user_type]['display']|intval};</script>
                    {/if}

                    {if $settings['payment_address'][$p_address_key]['html_format'] == 1}
                        {if $p_address_key eq 'postcode'}
                        <tr class="sort_data" id="payment_post_code" data-percentage="{$settings['payment_address'][$p_address_key]['sort_order']|intval}" style="{$display_row}" >
                        {else}
                        <tr class="sort_data" data-percentage="{$settings['payment_address'][$p_address_key]['sort_order']|intval}" style="{$display_row}" >
                        {/if} <td>
                            <table>
                                    <tr>
                                        <td>
                        {l s={$settings['payment_address'][$p_address_key]['title']} mod='supercheckout'}:<span style="{if $p_address_key != 'vat_number'}display:{if $settings['payment_address'][$p_address_key][$user_type]['require'] eq 1}inline{else}none{/if}{else}{if $need_vat}display:inline{else}display:none{/if}{/if};" class="supercheckout-required">*</span>
                                {if $p_address_key eq 'id_country' || $p_address_key eq 'id_state'}
                                    <input type='hidden' class="{$google_region_type}"/>
                                    <select name="payment_address[{$p_address_key}]"  onchange="restrictAutofillbyCountry(this)" class="supercheckout-large-field">
                                        
                                        {if $p_address_key eq 'id_country'}
                                            <option value="0">--</option>
                                            {foreach from=$countries item='country'}
                                                <option value="{$country['id_country']|intval}" {if $country['id_country'] == $address_value[$p_address_key]} selected="selected"{/if}>{$country['name']|escape:'html':'UTF-8'}</option>                                        
                                            {/foreach}
                                        {else}
                                            <option value="0">{l s='Select State' mod='supercheckout'}</option>
                                             <input type='hidden' id='payment_saved_state' value="{$address_value[$p_address_key]}"/>
                                        {/if}                            
                                    </select>
                                {else if $p_address_key eq 'dob'}
                                    <div class="supercheckout_dob_box supercheckout-large-field">
                                        <select name="payment_address[dob_days]">
                                          <option value="">--</option>
                                          {foreach from=$days item='day'}
                                              <option value="{$day|intval}">{$day|intval}</option>
                                          {/foreach}
                                        </select>
                                        <select name="payment_address[dob_months]">
                                          <option value="">--</option>
                                          {foreach from=$months item='month'}
                                              <option value="{$month}">{$month}</option>
                                          {/foreach}
                                        </select>
                                        <select name="payment_address[dob_years]">
                                          <option value="">--</option>
                                          {foreach from=$years item='year'}
                                              <option value="{$year}">{$year}</option>
                                          {/foreach}
                                        </select>
                                    </div>
                                {else if  $p_address_key eq 'other'}
                                    <textarea autocomplete="off" name="payment_address[{$p_address_key}]" value="{$address_value[$p_address_key]}" class="supercheckout-large-field" style="width: 96%;">{$address_value[$p_address_key]}</textarea>
                                {else}
                                    {if $settings['google_auto_address']['enable'] eq 1}
                                        <input autocomplete="off" {if $p_address_key eq 'address1'|| $p_address_key eq 'address2'} placeholder="{l s='Enter a location' mod='supercheckout'}" id='payment_address_{$p_address_key}' onFocus="geolocate()"{/if} type="text" name="payment_address[{$p_address_key}]" value="{$address_value[$p_address_key]}" class="supercheckout-large-field {$google_region_type} {if $p_address_key eq 'address1'|| $p_address_key eq 'address2'}autocomplete{/if}" />
                                        {else}
                                        <input autocomplete="off" type="text" name="payment_address[{$p_address_key}]" value="{$address_value[$p_address_key]}" class="supercheckout-large-field" />
                                        {/if}
                                {/if}

                            </td>
                        {elseif $settings['payment_address'][$p_address_key]['html_format'] == 2}
                        <td>{l s={$settings['payment_address'][$p_address_key]['title']} mod='supercheckout'}:<span style="{if $p_address_key != 'vat_number'}display:{if $settings['payment_address'][$p_address_key][$user_type]['require'] eq 1}inline{else}none{/if}{else}{if $need_vat}display:inline{else}display:none{/if}{/if};" class="supercheckout-required">*</span>
                                {if $p_address_key eq 'id_country' || $p_address_key eq 'id_state'}
                                    <input type='hidden' class="{$google_region_type}"/>
                                    <select name="payment_address[{$p_address_key}]" onchange="restrictAutofillbyCountry(this)" class="supercheckout-large-field">
                                        {if $p_address_key eq 'id_country'}
                                            <option value="0">--</option>
                                            {foreach from=$countries item='country'}
                                                <option value="{$country['id_country']|intval}" {if $country['id_country'] == $address_value[$p_address_key]} selected="selected"{/if}>{$country['name']|escape:'html':'UTF-8'}</option>                                        
                                            {/foreach}
                                        {else}
                                            <option value="0">{l s='Select State' mod='supercheckout'}</option>
                                             <input type='hidden' id='payment_saved_state' value="{$address_value[$p_address_key]}"/>
                                        {/if}                            
                                    </select>
                                {else if $p_address_key eq 'dob'}
                                    <div class="supercheckout_dob_box supercheckout-large-field">
                                        <select name="payment_address[dob_days]">
                                          <option value="">--</option>
                                          {foreach from=$days item='day'}
                                              <option value="{$day|intval}">{$day|intval}</option>
                                          {/foreach}
                                        </select>
                                        <select name="payment_address[dob_months]">
                                          <option value="">--</option>
                                          {foreach from=$months item='month'}
                                              <option value="{$month}">{$month}</option>
                                          {/foreach}
                                        </select>
                                        <select name="payment_address[dob_years]">
                                          <option value="">--</option>
                                          {foreach from=$years item='year'}
                                              <option value="{$year}">{$year}</option>
                                          {/foreach}
                                        </select>
                                    </div>
                                {else if  $p_address_key eq 'other'}
                                    <textarea autocomplete="off" name="payment_address[{$p_address_key}]" value="" class="supercheckout-large-field" style="width: 96%;">{$address_value[$p_address_key]}</textarea>
                                {else}
                                    
                                    {if $settings['google_auto_address']['enable'] eq 1}
                                        <input autocomplete="off" {if $p_address_key eq 'address1'|| $p_address_key eq 'address2'} placeholder="{l s='Enter a location' mod='supercheckout'}" id='payment_address_{$p_address_key}' onFocus="geolocate()"{/if} type="text" name="payment_address[{$p_address_key}]" value="{$address_value[$p_address_key]}" class="supercheckout-large-field {$google_region_type} {if $p_address_key eq 'address1'|| $p_address_key eq 'address2'}autocomplete{/if}" />
                                        {else}
                                        <input autocomplete="off" type="text" name="payment_address[{$p_address_key}]" value="{$address_value[$p_address_key]}" class="supercheckout-large-field" />
                                        {/if}
                                {/if}

                            </td>
                                                </tr>
                    </table>
                    </td>
                    </tr>
                    
                    {else}
            <tr class="sort_data"  data-percentage="{$settings['payment_address'][$p_address_key]['sort_order']|intval}" style="{$display_row}" >
                <td>{l s={$settings['payment_address'][$p_address_key]['title']} mod='supercheckout'}:<span style="{if $p_address_key != 'vat_number'}display:{if $settings['payment_address'][$p_address_key][$user_type]['require'] eq 1}inline{else}none{/if}{else}{if $need_vat}display:inline{else}display:none{/if}{/if};" class="supercheckout-required">*</span>
                    {if $p_address_key eq 'id_country' || $p_address_key eq 'id_state'}
                        <select name="payment_address[{$p_address_key}]" class="supercheckout-large-field">
                            {if $p_address_key eq 'id_country'}
                                {foreach from=$countries item='country'}
                                    <option value="{$country['id_country']|intval}" {if $country['id_country'] == $address_value[$p_address_key]} selected="selected"{/if}>{$country['name']|escape:'html':'UTF-8'}</option>                                        
                                {/foreach}
                            {else}
                                <option value="0">{l s='Select State' mod='supercheckout'}</option>
                                <input type='hidden' id='payment_saved_state' value="{$address_value[$p_address_key]}"/>
                            {/if}                            
                        </select>
                    {else if $p_address_key eq 'dob'}
                        <div class="supercheckout_dob_box supercheckout-large-field">
                            <select name="payment_address[dob_days]">
                              <option value="">--</option>
                              {foreach from=$days item='day'}
                                  <option value="{$day|intval}">{$day|intval}</option>
                              {/foreach}
                            </select>
                            <select name="payment_address[dob_months]">
                              <option value="">--</option>
                              {foreach from=$months item='month'}
                                  <option value="{$month}">{$month}</option>
                              {/foreach}
                            </select>
                            <select name="payment_address[dob_years]">
                              <option value="">--</option>
                              {foreach from=$years item='year'}
                                  <option value="{$year}">{$year}</option>
                              {/foreach}
                            </select>
                        </div>
                    {else if  $p_address_key eq 'other'}
                        <textarea autocomplete="off" name="payment_address[{$p_address_key}]" value="" class="supercheckout-large-field" style="width: 96%;">{$address_value[$p_address_key]}</textarea>
                    {else}
                        <input autocomplete="off" type="text" name="payment_address[{$p_address_key}]" value="{$address_value[$p_address_key]}" class="supercheckout-large-field" />
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
    <input type="button" value="{l s='Save' mod='supercheckout'}" id="supercheckout_update_address_payment" class="orangebuttonsmall">
    <input type="button" value="{l s='Cancel' mod='supercheckout'}" id="supercheckout_cancel_address" onclick="$('.payment_update_form').remove();$('#supercheckout_update_address_button').remove();" class="orangebuttonsmall">
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