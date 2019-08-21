<script type="text/javascript">
    var subtotal_msg = "{l s='I agree to the terms of service and will adhere to them unconditionally.' mod='supercheckout'}";
    
</script>
{if !$is_virtual_cart}
    {if $recyclablePackAllowed}
        <div id="supercheckout_recyclepack_container" class='order-shipping-extra' style="padding-bottom: 0 !important;">
            <input type="checkbox" name="recyclable" class="supercheckout-delivery-extra" id="recyclable" value="1" {if $recyclable == 1}checked="checked"{/if} />                        
            {l s='I would like to receive my order in recycled packaging.' mod='supercheckout'}                        
        </div>
    {/if}
    
    {if $gift.allowed}
        
        <div id="supercheckout-gift_container" class='order-shipping-extra' style="padding-bottom: 0 !important;">
            <input type="checkbox" class="supercheckout-delivery-extra" name="gift" id="gift" value="1" {if $gift.isGift == 1}checked="checked"{/if} />                        
            {$gift.label nofilter}{*escape not required as contains html*}
        </div>
        {if isset($settings['confirm']['gift_message'][$user_type]['display']) && ($settings['confirm']['gift_message'][$user_type]['display'] eq 1)}
        <div id="supercheckout-gift_kb_message_container" class='order-shipping-extra' style="padding-bottom: 0 !important;{if $gift.isGift != 1}display:none;{/if}">
            <input type="checkbox" class="supercheckout-delivery-extra" name="kb_message_gift" id="kb_message_gift" {if $is_kb_gift_msg_already_added == 1}checked="checked"{/if} value="0"/>                        
            {l s='Add a gift message' mod='supercheckout'}{*escape not required as contains html*}
            <span id='edit_kb_gift_message' {if $is_kb_gift_msg_already_added == 1}{else} style="display:none;"{/if}>
                <a href="javascript:void(0)" onclick="showGiftMessagePopup()">{l s='Edit' mod='supercheckout'}</a>
            </span>
        </div>
        {else}
        {* below div commented by rishabh jain *}
        <div id="supercheckout-gift-comments" style="display:{if $gift.isGift == 1}block{else}none{/if}; margin-top: 0; margin-bottom: 15px;">
            <b>{l s='If you would like, you can add a note to the gift' mod='supercheckout'}:</b>
            <textarea id="gift_message" name="gift_comment" rows="8" >{$gift.message}</textarea>
        </div>
        
        {/if}
    {* changes by rishabh jain *}
    
        {if isset($settings['confirm']['gift_message'][$user_type]['display']) && ($settings['confirm']['gift_message'][$user_type]['display'] eq 1)}

            <div id="divKbgiftMessage" style="display:none" title="{l s='Add/Edit gift Message details' mod='supercheckout'}" class="supercheckout-threecolumns divkbmobilelogin">
                <div class="velsof_sc_overlay" style="display: block;"></div>
                <div id="gift_message_update_warning" class="supercheckout-checkout-content"></div>
                <div class="supercheckout-extra-wrap">
                    {l s='From' mod='supercheckout'}<span class="supercheckout-required">*</span><br>
                    <input type="text" style="width: 100%;" id="supercheckout_gift_sender" name="supercheckout_gift_receiver" value="{$sender}" class="supercheckout-large-field">
                    <span id="kb_gift_sender_error"  style="display:none;" class="errorsmall supercheckout-required">{l s='Required Field' mod='supercheckout'}</span>
                </div>
                <div class="supercheckout-extra-wrap">
                    {l s='To' mod='supercheckout'}<span class="supercheckout-required">*</span><br>
                    <input type="text" style="width: 100%;" id="supercheckout_gift_receiver" name="supercheckout_gift_receiver" value="{$receiver}" class="supercheckout-large-field">
                    <span id="kb_gift_receiver_error" style="display:none;" class="errorsmall supercheckout-required">{l s='Required Field' mod='supercheckout'}</span>
                </div>
                <div class="supercheckout-extra-wrap">
                    {l s='Message' mod='supercheckout'}<span class="supercheckout-required">*</span><br>
                    <textarea id="supercheckout_gift_message" style="width: 100%;" name="supercheckout_gift_message" rows="8" >{$kb_gift_msg}</textarea>
                    <span id="kb_gift_msg_error" style="display:none;" class="errorsmall supercheckout-required">{l s='Required Field' mod='supercheckout'}</span>
                </div>
                <div class="supercheckout-extra-wrap">
                    <input id="kb_gift_message_submit" type="button" onclick="updateKbGiftMessage();" class="orangebuttonapply" value="{if $kb_gift_msg != ''} {l s='Update' mod='supercheckout'} {else} {l s='Add ' mod='supercheckout'}{/if}">
                </div>
            </div>
        {/if} 
    
    {* changes over *}
{/if}
{/if}
{if $show_TOS && count($conditions_to_approve) > 0}
    {* GDPR Change*}
    <input type="hidden" value="{l s='I agree to the terms of service and will adhere to them unconditionally. ' mod='supercheckout'}" name="supercheckout_default_policy" />
    {* GDPR Change*}
    <div id="supercheckout-agree">
        {foreach from=$conditions_to_approve item="condition" key="condition_name"}
            <label for="conditions_to_approve[{$condition_name}]">
                <input id="conditions_to_approve[{$condition_name}]" type="checkbox" name="conditions_to_approve[{$condition_name}]" value="1" />
                {$condition nofilter}{*escape not required as contains html*}
            </label>
        {/foreach}
    </div>
{/if}
{* GDPR Change*}
{hook h='customSuperCheckoutGDPRHook'}
{* GDPR Change*}
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
* @copyright 2016 Knowband
*}