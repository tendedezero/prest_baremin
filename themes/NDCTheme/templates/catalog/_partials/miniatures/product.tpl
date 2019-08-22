{**
 * 2007-2017 PrestaShop
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
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{* AngarTheme *}

{block name='product_miniature_item'}
	<article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
		<div class="thumbnail-container">

			<div class="product-left">
				<div class="product-image-container">
                    {block name='product_flags'}
						<a href="{$product.url}" class="product-flags">
                            {if $product.reduction != 0}
                                {if $product.discount_type === 'percentage'}
									<span class="product-flag discount-percentage">{$product.discount_percentage}</span>
                                {/if}
                                {if $product.discount_type === 'amount'}
									<span class="product-flag discount-percentage">- {$product.discount_amount}</span>
                                {/if}
                            {/if}
                            {foreach from=$product.flags|@array_reverse item=flag}
								<span class="product-flag {$flag.type}">{$flag.label}</span>
                            {/foreach}
						</a>
                    {/block}

                    {block name='product_thumbnail'}
						<a href="{$product.url}" class="thumbnail product-thumbnail">
                            {* AngarTheme *}
                            {if !empty($product.cover.bySize.home_default.url)}
								<img src="/img/pixel.gif" data-src = "{$product.cover.bySize.home_default.url}" class="lazy"
										alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
										data-full-size-image-url = "{$product.cover.large.url}"
								>
                            {else}
								<img src = "{$urls.img_url}en-default-home_default.jpg" alt = "{$product.name|truncate:30:'...'}" >
                            {/if}
						</a>
                    {/block}

                    {* AngarTheme *}
                    {if $display_quickview == 1}
                        {block name='quick_view'}
							<a class="quick-view" href="#" data-link-action="quickview">
								<i class="material-icons search">&#xE8B6;</i>{l s='Quick view' d='Shop.Theme.Actions'}
							</a>
                        {/block}
                    {/if}
				</div>
			</div>

			<div class="product-right">
				<div class="product-description">
                    {* AngarTheme *}
					<p class="pl_reference">
                        {if isset($product.reference_to_display) && $product.reference_to_display neq ''}
                            {l s='SKU' d='Shop.Theme.Catalog'}:
							<span><strong>{$product.reference_to_display}</strong></span>
                        {/if}
					</p>

					<p class="pl_manufacturer">
                        {if $product.id_manufacturer > 0}
                            {assign var='manufacturer_name' value=Manufacturer::getNameById($product.id_manufacturer)}
                            {l s='Brand' d='Shop.Theme.Catalog'}:
                            {if $psversion > ps_172}
								<a href="{$link->getmanufacturerLink($product.id_manufacturer)}" title="{$manufacturer_name}"><strong>{$manufacturer_name}</strong></a>
                            {else}
								<strong>{$manufacturer_name}</strong>
                            {/if}
                        {/if}
					</p>

                    {block name='product_name'}
						<h3 class="h3 product-title"><a href="{$product.url}">{$product.name|truncate:160:'...'}</a></h3>
                    {/block}

                    {block name='product_reviews'}
                        {hook h='displayProductListReviews' product=$product}
                    {/block}

                    {block name='product_shortdesc'}
						<p class="product-desc">
                            {$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}
						</p>
                    {/block}
				</div>

				<div class="product-bottom">


                    {block name='product_price_and_shipping'}
                        {if $product.show_price}
							<div class="product-price-and-shipping">
                                {hook h='displayProductPriceBlock' product=$product type="before_price"}
                                {hook h='displayProductPriceBlock' product=$product type="rrp"}
                 
								<div class='current-price sale-price inc-vat'  style='display:none' > <span class="price">{$product.price}</span> inc vat</div>
								<div class='current-price sale-price ex-vat' style='display:none'><span class="price">{l sprintf=['%price%' => $product.price_tax_exc] s='&pound;%price%'}</span><span class="tax_display">{l s=' ex vat' d='Shop.Theme.Catalog' }</span></div>

                                {if $product.has_discount}
                                    {hook h='displayProductPriceBlock' product=$product type="old_price"}
									<span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span>
									<span class="regular-price">{$product.regular_price}</span>
                                {/if}

                                {hook h='displayProductPriceBlock' product=$product type='unit_price'}
                                {hook h='displayProductPriceBlock' product=$product type='weight'}
							</div>
                        {/if}

                    {/block}

                    {* AngarTheme *}
					<div class="button-container">
						<form action="{$urls.pages.cart}" method="post" class="add-to-cart-or-refresh">
							<input type="hidden" name="token" value="{$static_token}">
							<input type="hidden" name="id_product" value="{$product.id}" class="product_page_product_id">
							<input type="hidden" name="qty" value="1">

                            {if !$configuration.is_catalog}
                                {if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
									<button class="btn add-to-cart" data-button-action="add-to-cart" type="submit" {if !$product.add_to_cart_url}disabled{/if}>
										<i class="material-icons shopping-cart">?</i>
                                        {l s='Add to cart' d='Shop.Theme.Actions'}
									</button>
                                {else}
									<button class="btn add-to-cart" data-button-action="add-to-cart" type="submit" disabled>
										<i class="material-icons shopping-cart">?</i>
                                        {l s='Add to cart' d='Shop.Theme.Actions'}
									</button>
                                {/if}
                            {/if}
						</form>

						<a class="button lnk_view btn" href="{$product.link|escape:'html':'UTF-8'}" title="{l s='More' d='Shop.Theme.Mytheme'}">
							<span>{l s='More' d='Shop.Theme.Mytheme'}</span>
						</a>
					</div>

					<div class="availability">
                        {block name='product_availability'}
							<span class="pl-availability">
					{if $product.show_availability && $product.availability_message}
                        {if $product.availability == 'available'}
							<i class="material-icons product-available">&#xE5CA;</i>
					  {elseif $product.availability == 'last_remaining_items'}
						<i class="material-icons product-last-items">&#xE002;</i>
					  {else}
						<i class="material-icons product-unavailable">&#xE14B;</i>
                        {/if}
					  {$product.availability_message}
					{else}
						{if $product.allow_oosp == 0}
						<i class="material-icons product-available">&#xE5CA;</i> {l s='In stock' d='Shop.Theme.Catalog'}
                    {/if}

						{if $product.allow_oosp == 1}
                        {* <i class="material-icons product-unavailable">&#xE14B;</i> {l s='Out of stock' d='Shop.Theme.Catalog'} *}
						<i class="material-icons product-available">&#xE5CA;</i> {l s='In stock' d='Shop.Theme.Catalog'}
                    {/if}
                    {/if}
				  </span>
                        {/block}
					</div>

					<div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
                        {block name='product_variants'}
                            {if $product.main_variants}
                                {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
                            {/if}
                        {/block}
					</div>
				</div>
			</div>

			<div class="clearfix"></div>

		</div>
	</article>

{/block}


