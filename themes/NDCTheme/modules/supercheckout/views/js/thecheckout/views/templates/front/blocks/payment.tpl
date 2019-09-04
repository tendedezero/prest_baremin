{**
* NOTICE OF LICENSE
*
* This source file is subject to the Software License Agreement
* that is bundled with this package in the file LICENSE.txt.
*
*  @author    Peter Sliacky (Zelarg)
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}
<div class="error-msg">{l s='Please select a payment method' mod='thecheckout'}</div>

<section id="checkout-payment-step" class="js-current-step">
  <div class="block-header shipping-method-header">{l s='Payment method' d='Shop.Theme.Checkout'}</div>
  {if $shipping_payment_blocks_wait_for_selection}
    <div class="dummy-block-container disallowed"><span>{l s='Please choose delivery country to see payment options' mod='thecheckout'}</span></div>
  {elseif $force_email_wait_for_enter}
    <div class="dummy-block-container disallowed"><span>{l s='Please enter your email to see payment options' mod='thecheckout'}</span></div>
  {else}
    {block name='payment_options'}
      {hook h='displayPaymentTop'}

      {if $is_free}
        <p>{l s='No payment needed for this order' d='Shop.Theme.Checkout'}</p>
      {/if}
      <div class="payment-options {if $is_free}hidden-xs-up{/if}">
        {foreach from=$payment_options key="module_name" item="module_options"}
          {foreach from=$module_options item="option" name="multioptions"}
            <div
              id="{$option.id}-main-title"
              class="tc-main-title {$module_name}"
            >
              <div id="{$option.id}-container" class="payment-option clearfix">
                {* This is the way an option should be selected when Javascript is enabled *}
                <span class="custom-radio float-xs-left">
                <input
                  class="ps-shown-by-js {if $option.binary} binary {/if}"
                  id="{$option.id}"
                  data-module-name="{$option.module_name}{if $smarty.foreach.multioptions.index>0}-{$smarty.foreach.multioptions.index}{/if}"
                  name="payment-option"
                  type="radio"
                  required
                  {if $selected_payment_option == $option.id || $is_free} checked {/if}
                >
                <span></span>
              </span>

                <label for="{$option.id}">
                  <span class="h6">{$option.call_to_action_text}</span>
                  {if $option.logo}
                    <img src="{$option.logo}">
                  {/if}
                </label>

              </div>
            {if $option.additionalInformation}
              <div
                id="{$option.id}-additional-information"
                class="js-additional-information definition-list additional-information {$module_name}{if $option.id != $selected_payment_option} ps-hidden{/if}"
              >
                {$option.additionalInformation nofilter}
              </div>
            {/if}
            <div
              id="pay-with-{$option.id}-form"
              class="js-payment-option-form {if $option.id != $selected_payment_option} ps-hidden {/if}"
            >
              {if $option.form}
                {$option.form nofilter}
              {else}
                <form class="payment-form" method="POST" action="{$option.action nofilter}">
                  {foreach from=$option.inputs item=input}
                    <input type="{$input.type}" name="{$input.name}" value="{$input.value}">
                  {/foreach}
                  <button style="display:none" id="pay-with-{$option.id}" type="submit"></button>
                </form>
              {/if}
            </div>
          </div>
          {/foreach}
          {foreachelse}
          <p
            class="alert alert-danger">{l s='Unfortunately, there are no payment method available.' d='Shop.Theme.Checkout'}</p>
        {/foreach}
      </div>
      {hook h='displayPaymentByBinaries'}
      <div class="modal fade" id="modal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
              <span aria-hidden="true">&times;</span>
            </button>
            <div class="js-modal-content"></div>
          </div>
        </div>
      </div>
      {/block}
   {/if}
</section>
