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
<div class="product-line">
  <!--  product left content: image-->
  <div class="product-line-image">
    <span class="product-image media-middle">
      <img src="{$product.cover.bySize.cart_default.url}" alt="{$product.name|escape:'quotes'}">
    </span>
  </div>

  <!--  product left body: description -->
  <div class="product-line-body">
    <div class="product-line-desc">
      <div class="product-line-info product-title">
        <a class="label" href="{$product.url}"
           data-id_customization="{$product.id_customization|intval}">{$product.name}</a>
      </div>

      <div class="product-line-info product-price h5 {if $product.has_discount}has-discount{/if}">
        {if $product.has_discount}
          <div class="product-discount">
            <span class="regular-price">{$product.regular_price}</span>
            {if $product.discount_type === 'percentage'}
              <span class="discount discount-percentage">
                -{$product.discount_percentage_absolute}
              </span>
            {else}
              <span class="discount discount-amount">
                -{$product.discount_to_display}
              </span>
            {/if}
          </div>
        {/if}
        <div class="current-price">
          <span class="price">{$product.price}</span>
          {if $product.unit_price_full}
            <div class="unit-price-cart">{$product.unit_price_full}</div>
          {/if}
        </div>
      </div>

      <br/>

      {foreach from=$product.attributes key="attribute" item="value"}
        <div class="product-line-info product-attribute">
          <span class="label">{$attribute}:</span>
          <span class="value">{$value}</span>
        </div>
      {/foreach}

      {if $product.customizations|count}
        <br>
        {block name='cart_detailed_product_line_customization'}
          {foreach from=$product.customizations item="customization"}
            <a href="#" data-toggle="modal"
               data-target="#product-customizations-modal-{$customization.id_customization}">{l s='Product customization' d='Shop.Theme.Catalog'}</a>
            <div class="modal fade customization-modal"
                 id="product-customizations-modal-{$customization.id_customization}" tabindex="-1" role="dialog"
                 aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">{l s='Product customization' d='Shop.Theme.Catalog'}</h4>
                  </div>
                  <div class="modal-body">
                    {foreach from=$customization.fields item="field"}
                      <div class="product-customization-line row">
                        <div class="col-sm-3 col-xs-4 label">
                          {$field.label}
                        </div>
                        <div class="col-sm-9 col-xs-8 value">
                          {if $field.type == 'text'}
                            {if (int)$field.id_module}
                              {$field.text nofilter}
                            {else}
                              {$field.text}
                            {/if}
                          {elseif $field.type == 'image'}
                            <img src="{$field.image.small.url}">
                          {/if}
                        </div>
                      </div>
                    {/foreach}
                  </div>
                </div>
              </div>
            </div>
          {/foreach}
        {/block}
      {/if}
    </div>

    <!--  product left body: description -->
    <div class="product-line-actions">

      <div class="product-line-qty"
           data-qty-control="{$product.id_product|escape:'javascript'}-{$product.id_product_attribute|escape:'javascript'}-{$product.id_customization|escape:'javascript'}">
        <div class="qty-container">
          <div class="qty-box">
            {if isset($product.is_gift) && $product.is_gift}
              <span class="gift-quantity">{$product.quantity}</span>
            {else}
              <input
                class="cart-line-product-quantity"
                data-link-action="x-update-cart-quantity"
                data-update-url="{$product.update_quantity_url}"
                data-id-product="{$product.id_product|escape:'javascript'}"
                data-id-product-attribute="{$product.id_product_attribute|escape:'javascript'}"
                data-id-customization="{$product.id_customization|escape:'javascript'}"
                data-qty-orig="{$product.quantity|escape:'javascript'}"
                type="text"
                value="{$product.quantity}"
                name="product-quantity-spin"
                min="{$product.minimal_quantity}"
              />
              <a class="cart-line-product-quantity-up"
                 href="{$product.up_quantity_url}"
                 data-link-action="x-update-cart-quantity-up">{*Up*}</a>
              <a class="cart-line-product-quantity-down"
                 href="{$product.down_quantity_url}"
                 data-link-action="x-update-cart-quantity-down">{*Down*}</a>
            {/if}
          </div>
        </div>
      </div>
      <div class="product-line-price">
            <span class="product-price">
              <strong>
                {if isset($product.is_gift) && $product.is_gift}
                  <span class="gift">{l s='Gift' d='Shop.Theme.Checkout'}</span>
                {else}
                  {$product.total}
                {/if}
              </strong>
            </span>
      </div>
      <div class="product-line-delete">
        <a
          class="remove-from-cart"
          rel="nofollow"
          href="{$product.remove_from_cart_url}"
          data-link-action="x-delete-from-cart"
          data-id-product="{$product.id_product|escape:'javascript'}"
          data-id-product-attribute="{$product.id_product_attribute|escape:'javascript'}"
          data-id-customization="{$product.id_customization|escape:'javascript'}"
          title="{l s='Delete' d='Shop.Theme.Actions'}"
        >
          {if !isset($product.is_gift) || !$product.is_gift}
            <i class="material-icons delete-from-cart float-xs-left">delete</i>
            <span class="non-material-icon delete-from-cart"></span>
          {/if}
        </a>
      </div>
    </div>

    {block name='hook_cart_extra_product_actions'}
      {hook h='displayCartExtraProductActions' product=$product}
    {/block}

  </div>
</div>
