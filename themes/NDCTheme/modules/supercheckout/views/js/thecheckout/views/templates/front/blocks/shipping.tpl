{**
* NOTICE OF LICENSE
*
* This source file is subject to the Software License Agreement
* that is bundled with this package in the file LICENSE.txt.
*
*  @author    Peter Sliacky (Zelarg)
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

<div class="error-msg">{l s='Please select a shipping method' mod='thecheckout'}</div>

{block name='shipping_options'}
  <div class="block-header shipping-method-header">{l s='Shipping Method' d='Shop.Theme.Checkout'}</div>

  {if $shipping_payment_blocks_wait_for_selection}
    <div class="dummy-block-container disallowed"><span>{l s='Please choose delivery country to see shipping options' mod='thecheckout'}</span></div>
  {elseif $force_email_wait_for_enter}
    <div class="dummy-block-container disallowed"><span>{l s='Please enter your email to see shipping options' mod='thecheckout'}</span></div>
  {else}
    {if isset($shippingCountry) && $shippingCountry}
      <div class="shipping-address-notice">{l s='Shipping Address' d='Shop.Theme.Checkout'}: <span class="country-name">{$shippingCountry}</span></div>
    {/if}
    <div id="hook-display-before-carrier">
      {$hookDisplayBeforeCarrier nofilter}
    </div>
    <div class="delivery-options-list">
      {if $delivery_options|count}
        <form
          class="clearfix"
          id="js-delivery"
          data-url-update="{url entity='order' params=['ajax' => 1, 'action' => 'selectDeliveryOption']}"
          method="post"
        >
          <div class="form-fields">
            {block name='delivery_options'}
              <div class="delivery-options">
                {foreach from=$delivery_options item=carrier key=carrier_id}
                  <div class="delivery-option-row row delivery-option{if "1" === $carrier.is_module} {$carrier.external_module_name}{/if}">
                    <div class="shipping-radio">
                        <span class="custom-radio float-xs-left">
                          <input type="radio" name="delivery_option[{$id_address}]" id="delivery_option_{$carrier.id}"
                                 value="{$carrier_id}"{if $delivery_option == $carrier_id} checked{/if}>
                          <span></span>
                        </span>
                    </div>
                    <label for="delivery_option_{$carrier.id}" class="delivery-option-label delivery-option-2">
                      <div class="row">
                        <div class="delivery-option-detail">
                          <div class="row">
                            {if $carrier.logo}
                              <div class="delivery-option-logo">
                                <img src="{$carrier.logo}" alt="{$carrier.name}"/>
                              </div>
                            {/if}
                            <div class="delivery-option-name {if $carrier.logo}has-logo{else}no-logo{/if}">
                              <span class="h6 carrier-name">{$carrier.name}</span>
                            </div>
                          </div>
                        </div>
                        <div class="delivery-option-delay">
                          <span class="carrier-delay">{$carrier.delay}</span>
                        </div>
                        <div class="delivery-option-price">
                          <span class="carrier-price">{$carrier.price}</span>
                        </div>
                      </div>
                    </label>
                  </div>
                  <div class="row carrier-extra-content{if "1" === $carrier.is_module} {$carrier.external_module_name}{/if}"{if $delivery_option != $carrier_id} style="display:none;"{/if}>
                    {$carrier.extraContent nofilter}
                  </div>
                  <div class="clearfix"></div>
                {/foreach}
              </div>
            {/block}
            <div class="order-options">
              {if $recyclablePackAllowed}
                <span class="custom-checkbox">
                  <input type="checkbox" id="input_recyclable" name="recyclable" value="1" {if $recyclable} checked {/if}>
                  <span><i class="material-icons rtl-no-flip checkbox-checked check-icon">&#xE5CA;</i></span>
                  <label
                    for="input_recyclable">{l s='I would like to receive my order in recycled packaging.' d='Shop.Theme.Checkout'}</label>
                </span>
              {/if}

              {if $gift.allowed}
                <span class="custom-checkbox">
                  <input class="js-gift-checkbox" id="input_gift" name="gift" type="checkbox" value="1"
                         {if $gift.isGift}checked="checked"{/if}>
                  <span><i class="material-icons rtl-no-flip checkbox-checked check-icon">&#xE5CA;</i></span>
                  <label for="input_gift">{$gift.label}</label>
                </span>
                <div id="gift" class="collapse{if $gift.isGift} in show{/if}">
                  <label
                    for="gift_message">{l s='If you\'d like, you can add a note to the gift:' d='Shop.Theme.Checkout'}</label>
                  <textarea rows="2" id="gift_message" name="gift_message">{$gift.message}</textarea>
                </div>
              {/if}

            </div>
          </div>
          {*<button type="submit" class="continue btn btn-primary float-xs-right" name="confirmDeliveryOption" value="1">*}
            {*{l s='Continue' d='Shop.Theme.Actions'}*}
          {*</button> *}
        </form>
      {else}
        <p
          class="alert alert-danger">{l s='Unfortunately, there are no carriers available for your delivery address.' d='Shop.Theme.Checkout'}</p>
      {/if}
    </div>
    <div id="hook-display-after-carrier">
      {$hookDisplayAfterCarrier nofilter}
    </div>
    <div id="extra_carrier"></div>
  {/if}
{/block}
