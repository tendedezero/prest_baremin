{**
* NOTICE OF LICENSE
*
* This source file is subject to the Software License Agreement
* that is bundled with this package in the file LICENSE.txt.
*
*  @author    Peter Sliacky (Zelarg)
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

{extends file='page.tpl'}

{block name="page_content"}
  {* necessary here, core's checkout.js script looks for #checkout element and binds handlers only then - setUpCheckout() *}
  {*<div id="checkout" class="fool-js-confirmation-controllers"></div>*}
  <script>
    var debug_js_controller = '{$debugJsController}';
    var static_token = '{$static_token}';
    var config_default_payment_method = '{$config->default_payment_method}';
    var config_show_i_am_business = '{$config->show_i_am_business}';
    var config_force_customer_to_choose_country = '{$config->force_customer_to_choose_country}';
    var config_blocks_update_loader = '{$config->blocks_update_loader}';
    var isEmptyCart = '{$isEmptyCart}';
    var tcModuleBaseUrl = '{$urls.base_url}/modules/thecheckout'
  </script>
  <style>
    {if !$showShipToDifferentAddress && $isInvoiceAddressPrimary}
    {literal}
    #thecheckout-address-delivery {
      display: none;
    }

    {/literal}
    {/if}
    {if !$showBillToDifferentAddress && !$isInvoiceAddressPrimary}
    {literal}
    #thecheckout-address-invoice {
      display: none;
    }

    {/literal}
    {/if}
    {if !$config->offer_second_address}{literal}
    .second-address {
      display: none;
    }

    {/literal}{/if}
    {if !$config->show_block_reassurance}{literal}
    #block-reassurance {
      display: none;
    }

    {/literal}{/if}

    {if !$config->show_order_message}{literal}
    #thecheckout-order-message {
      display: none;
    }

    {/literal}{/if}
    {if !$config->using_material_icons}{literal}
    span.custom-radio input[type=radio] {
      opacity: 1;
    }

    span.custom-radio {
      border: none;
    }

    span.custom-radio input[type=radio]:checked + span {
      display: none;
    }

    i.material-icons.checkbox-checked {
      display: none;
    }

    .custom-checkbox input[type=checkbox] {
      opacity: 1;
    }

    .custom-checkbox input[type=checkbox] + span {
      opacity: 0;
      pointer-events: none;
    }

    {/literal}{/if}

    {*
    {if $config->show_i_am_business}{literal}
    #thecheckout-address-invoice .form-group.business-field {
      order: -1; /* Put business field in front if we have this checkbox */
    }

    {/literal}{/if}
    *}

    /* BEGIN Custom CSS styles from config page */
    {$config->custom_css nofilter}
    /* END Custom CSS styles from config page */
  </style>
  <script>
    /* BEGIN Custom JS code from config page */
    {$config->custom_js nofilter}
    /* END Custom JS code from config page */
  </script>
  {*<div id="checkout">*}
  <!-- this div tag is required due to core.js events registrations -->
  {*</div>*}

  {*{debug}*}
  <div id="empty-cart-notice">
    <h1>{l s='Cart is empty' d='Shop.Notifications.Error'}</h1>
    <a class="label" href="{$urls.pages.index}">
      <span class="laquo">«</span>{l s='Continue shopping' d='Shop.Theme.Actions'}
    </a>
  </div>
  <div id="is-test-mode-notice">
    <div class="notice">{l s='Test mode is enabled, only you can see The Checkout module active.' mod='thecheckout'}</div>
    <a class="close-notice" href="javascript:$('#is-test-mode-notice').fadeOut();">{l s='OK, close' mod='thecheckout'}</a>
  </div>
  {assign 'k' 1}

  {function blockContainer}
    {foreach $data as $key=>$sub_block}
      {if "blocks" === $key}
        <div class="blocks checkout-area-{$k++}{if $data.size<=35} width-below-35pct{/if}{if $data.size<=50} width-below-50pct{/if}{if $data.size<=70} width-below-70pct{/if}" style="flex-basis: {$data.size}%; min-width: {$data.size}%;">
          {foreach $sub_block as $checkout_block}
            {foreach $checkout_block as $blockName=>$classes}
              {if !in_array($blockName, $excludeBlocks)}
                <div class="tc-block-placeholder thecheckout-{$blockName}"></div>
                <div class="checkout-block {$classes}" id="thecheckout-{$blockName}">
                  <div class="inner-area">
                    {if "cart-summary" == $blockName}
                      {*cart-summary block loaded via Ajax, display dummy container only*}
                      <section id="main">
                        <div class="cart-grid row">
                          <div class="card cart-container">
                            <div
                              class="block-header shopping-cart-header">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</div>
                          </div>
                        </div>
                      </section>
                      {include file='module:thecheckout/views/templates/front/_partials/blocks-loader.tpl'}
                      <div class="card cart-summary">
                      </div>
                    {/if}
                    {if "login-form" == $blockName}
                      {*won't be set in front.php for logged-in customers*}
                      {include file='module:thecheckout/views/templates/front/blocks/login-form.tpl'}
                    {/if}
                    {if "account" == $blockName}
                      {include file='module:thecheckout/views/templates/front/blocks/account.tpl'}
                    {/if}
                    {if "address-invoice" == $blockName}
                      {include file='module:thecheckout/views/templates/front/blocks/address-invoice.tpl'}
                    {/if}
                    {if "address-delivery" == $blockName}
                      {include file='module:thecheckout/views/templates/front/blocks/address-delivery.tpl'}
                    {/if}
                    {if "shipping" == $blockName}
                      {*shipping block loaded via Ajax, display dummy container only*}
                      <div
                        class="block-header shipping-method-header">{l s='Shipping Method' d='Shop.Theme.Checkout'}</div>
                      {include file='module:thecheckout/views/templates/front/_partials/blocks-loader.tpl'}
                    {/if}
                    {if "payment" == $blockName}
                      <div class="dynamic-content">
                        {*payment block loaded via Ajax, display dummy container only*}
                        <div
                          class="block-header shipping-method-header">{l s='Payment method' d='Shop.Theme.Checkout'}</div>
                        {include file='module:thecheckout/views/templates/front/_partials/blocks-loader.tpl'}
                      </div>
                      <div class="static-content"></div>
                      <div class="popup-payment-content">
                        <div class="popup-close-icon"></div>
                        <div class="popup-payment-form"></div>
                        <div class="popup-payment-button">
                          {include file='module:thecheckout/views/templates/front/_partials/payment-confirmation-button.tpl'}
                        </div>
                      </div>
                    {/if}
                    {if "order-message" == $blockName}
                      {include file='module:thecheckout/views/templates/front/blocks/order-message.tpl'}
                    {/if}
                    {if "confirm" == $blockName}
                      {include file='module:thecheckout/views/templates/front/blocks/confirm.tpl'}
                    {/if}
                    {if "html-box-1" == $blockName}
                      {$config->html_box_1 nofilter}
                    {/if}
                    {if "html-box-2" == $blockName}
                      {$config->html_box_2 nofilter}
                    {/if}
                    {if "html-box-3" == $blockName}
                      {$config->html_box_3 nofilter}
                    {/if}
                    {if "html-box-4" == $blockName}
                      {$config->html_box_4 nofilter}
                    {/if}
                    {if "required-checkbox-1" == $blockName && isset($separateModuleFields['thecheckout_required-checkbox-1'])}
                      <form class="account-fields module-account-fields {$blockName}">
                        {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' field=$separateModuleFields['thecheckout_required-checkbox-1']}
                      </form>
                    {/if}
                    {if "required-checkbox-2" == $blockName && isset($separateModuleFields['thecheckout_required-checkbox-2'])}
                      <form class="account-fields module-account-fields {$blockName}">
                        {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' field=$separateModuleFields['thecheckout_required-checkbox-2']}
                      </form>
                    {/if}
                    {if "newsletter" == $blockName && isset($separateModuleFields['ps_emailsubscription_newsletter'])}
                      <form class="account-fields module-account-fields {$blockName}">
                        {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' field=$separateModuleFields['ps_emailsubscription_newsletter']}
                      </form>
                    {/if}
                    {if "psgdpr" == $blockName && isset($separateModuleFields['psgdpr_psgdpr'])}
                      <form class="account-fields module-account-fields {$blockName}">
                        {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' field=$separateModuleFields['psgdpr_psgdpr']}
                      </form>
                    {/if}
                    {if "data-privacy" == $blockName && isset($separateModuleFields['ps_dataprivacy_customer_privacy'])}
                      <form class="account-fields module-account-fields {$blockName}">
                        {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' field=$separateModuleFields['ps_dataprivacy_customer_privacy']}
                      </form>
                    {/if}
                  </div>
                </div>
              {/if}
            {/foreach}
          {/foreach}
        </div>
      {elseif "size" === $key} {*intentionally empty*}
      {else}
        {if 0 === $key|strpos:'flex-split'}
          <div class="{$key} checkout-area-{$k++}" style="flex-basis: {$data.size}%">
        {/if}
        {blockContainer data=$sub_block}
        {if 0 === $key|strpos:'flex-split'}
          </div>
        {/if}

      {/if}

    {/foreach}
  {/function}
  <div id="tc-container">

    {blockContainer data=$blocksLayout}

    {* This element will be added by JS script as overlay on binary payment methods *}
    <div class="save-account-overlay hidden">
      <button type="button" class="btn btn-primary center-block" data-link-action="x-save-account-overlay">
        <div class="tc-loader">
          <div class="lds-ellipsis">
            <div>
              <div></div>
            </div>
            <div>
              <div></div>
            </div>
            <div>
              <div></div>
            </div>
            <div>
              <div></div>
            </div>
            <div>
              <div></div>
            </div>
          </div>
        </div>
        {l s='Confirm & Show payment' mod='thecheckout'}
      </button>
    </div>
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
    <div id="payment_forms_persistence"></div>
  </div>
  <div id="tc-container-mobile"></div>
{/block}
