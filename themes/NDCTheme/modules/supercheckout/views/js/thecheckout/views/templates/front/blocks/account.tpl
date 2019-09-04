{**
* NOTICE OF LICENSE
*
* This source file is subject to the Software License Agreement
* that is bundled with this package in the file LICENSE.txt.
*
*  @author    Peter Sliacky (Zelarg)
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

<div class="block-header account-header">{l s='Personal Information' d='Shop.Theme.Checkout'}</div>
<form class="account-fields">
  {block name="account_form_fields"}
    <section class="form-fields">
      {block name='form_fields'}
        {include file='module:thecheckout/views/templates/front/_partials/static-customer-info.tpl'}
        {assign parentTplName 'account'}
        {foreach from=$formFieldsAccount item="field"}
          {block name='form_field'}
            {include file='module:thecheckout/views/templates/front/_partials/checkout-form-fields.tpl' checkoutSection='account'}
          {/block}
        {/foreach}
      {/block}
      {$hook_create_account_form nofilter}
    </section>
  {/block}
</form>
