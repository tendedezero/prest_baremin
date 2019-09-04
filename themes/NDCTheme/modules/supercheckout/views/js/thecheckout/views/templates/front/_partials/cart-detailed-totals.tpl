{**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{block name='cart_detailed_totals'}
<div class="cart-detailed-totals">
  <div class="card-block">
    {foreach from=$cart.subtotals item="subtotal"}
      {if $subtotal.value && $subtotal.type !== 'tax'}
        <div class="cart-summary-line" id="cart-subtotal-{$subtotal.type}">
          <span class="label{if 'products' === $subtotal.type} js-subtotal{/if}">
            {if 'products' == $subtotal.type}
              {$cart.summary_string}
            {else}
              {$subtotal.label}
            {/if}
          </span>
          <span class="value">{$subtotal.value}</span>
          {if $subtotal.type === 'shipping'}
              <div><small class="value">{hook h='displayCheckoutSubtotalDetails' subtotal=$subtotal}</small></div>
          {/if}
        </div>
      {/if}
    {/foreach}
  </div>

  {block name='cart_voucher'}
    {include file='module:thecheckout/views/templates/front/_partials/cart-voucher.tpl'}
  {/block}

  {*<hr class="separator">*}

  <div class="card-block">

    {if isset($cart.subtotals.tax)}
      {* tax displayed in cart summary, we show Total (tax excl.), Tax and Total (tax incl.) *}
      <div class="cart-summary-line cart-total cart-total-tax-excluded">
        <span class="label">{$cart.totals.total_excluding_tax.label}</span>
        <span class="value">{$cart.totals.total_excluding_tax.value}</span>
      </div>

      <div class="cart-summary-line cart-total-tax">
        <span class="label">{$cart.subtotals.tax.label}</span>
        <span class="value">{$cart.subtotals.tax.value}</span>
      </div>
    {else}
      {* tax is not displayed in cart summary, we show Total (tax incl.) *}
    {/if}

    <div class="cart-summary-line cart-total cart-total-tax-included">
      <span class="label">{$cart.totals.total_including_tax.label}</span>
      <span class="value">{$cart.totals.total_including_tax.value}</span>
    </div>

    {*
    <div class="cart-summary-line cart-total">
      <span class="label">{$cart.totals.total.label} {$cart.labels.tax_short}</span>
      <span class="value">{$cart.totals.total.value}</span>
    </div>
    *}

  </div>

  {*<hr class="separator">*}
</div>
{/block}
