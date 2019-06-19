{if $product.show_price}
<div class="product-prices">
    {block name='product_discount'}
        {if $product.has_discount}
            <div class="product-discount">
                {hook h='displayProductPriceBlock' product=$product type="old_price"}
                <span class="regular-price">{$product.regular_price}</span>
            </div>
        {/if}
    {/block}
    {block name='product_price'}

        <div
        class="product-price  {if $product.has_discount}has-discount{/if}"
        itemprop="offers"
        itemscope
        itemtype="https://schema.org/Offer"
      >
        <link itemprop="availability" href="https://schema.org/InStock"/>
        <meta itemprop="priceCurrency" content="{$currency.iso_code}">

        <div class="current-price sale-price inc-vat">
            <span class="price" itemprop="price"  content="{$product.price_amount}" {if $smarty.cookies.VATMODE == 'false'} style="display:none"{/if}>{$product.price}</span><span class="tax_display">VAT Included</span>
        </div>

          {if $product.has_discount}
            {if $product.discount_type === 'percentage'}
              <span class="discount discount-percentage">{l s='Save %percentage%' d='Shop.Theme.Catalog' sprintf=['%percentage%' => $product.discount_percentage_absolute]}</span>
            {else}
              <span class="discount discount-amount">
                  {l s='Save %amount%' d='Shop.Theme.Catalog' sprintf=['%amount%' => $product.discount_to_display]}
              </span>
            {/if}
          {/if}
            <div class="current-price sale-price ex-vat" {if $smarty.cookies.VATMODE == 'true'}style="display: none;"{/if}>
                <span class="price" >{Tools::displayPrice($product.price_tax_exc)}</span><span class="tax_display">VAT Excluded</span>
            </div>
        </div>

        {block name='product_unit_price'}
          {if $displayUnitPrice}
            <p class="product-unit-price sub">{l s='(%unit_price%)' d='Shop.Theme.Catalog' sprintf=['%unit_price%' => $product.unit_price_full]}</p>
          {/if}
        {/block}
      </div>
      
    {/block}
     {block name='product_without_taxes'}{/block}
      {block name='product_displaytaxes'}{/block}

    <div class="tax-shipping-delivery-label">
        {if $configuration.display_taxes_label}

        {/if}
        {hook h='displayProductPriceBlock' product=$product type="price"}
        {hook h='displayProductPriceBlock' product=$product type="after_price"}
        {if $product.additional_delivery_times == 1}
            {if $product.delivery_information}
                <span class="delivery-information">{$product.delivery_information}</span>
            {/if}
        {elseif $product.additional_delivery_times == 2}
            {if $product.quantity > 0}
                <span class="delivery-information">{$product.delivery_in_stock}</span>
                {* Out of stock message should not be displayed if customer can't order the product. *}
            {elseif $product.quantity <= 0 && $product.add_to_cart_url}
                <span class="delivery-information">{$product.delivery_out_stock}</span>
            {/if}
        {/if}
    </div>

    <div class="clearfix"></div> {* AngarTheme *}

    </div>
{/if}

