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

{if $product.images}
<div class="cart_left">
<a href="{$product.url}" data-id_customization="{$product.id_customization|intval}">
	<img src="{$product.images.0.bySize.small_default.url}" title="{$product.name}"/>
</a>
</div>
{/if}

<div class="cart_right">
	<a class = "remove-from-cart"
	  rel = "nofollow"
	  href = "{$product.remove_from_cart_url}"
	  data-link-action = "delete-from-cart"
	  data-id-product = "{$product.id_product|escape:'javascript'}"
	  data-id-product-attribute = "{$product.id_product_attribute|escape:'javascript'}"
	  data-id-customization = "{$product.id_customization|escape:'javascript'}">
		<i class="material-icons"></i>
	</a>
	<span class="product-quantity">{$product.quantity}x</span>
	<span class="product-name"><a href="{$product.url}" data-id_customization="{$product.id_customization|intval}">{$product.name}</a></span>
	<span class="product-price inc-vat" style="display: none;">{$product.price} inc vat</span>
	<span class="product-price ex-vat" style="display: none;">{Tools::displayPrice(Product::getPriceStatic($product.id_product,false))}  ex vat</span>

	
</div>

{if $product.customizations|count}
    <div class="customizations">
        <ul>
            {foreach from=$product.customizations item='customization'}
                <li>
                    <span class="product-quantity">{$customization.quantity}</span>
                    <a href="{$customization.remove_from_cart_url}" title="{l s='remove from cart' d='Shop.Theme.Checkout'}" class="remove-from-cart" rel="nofollow"><i class="material-icons"></i></a>
                    <ul>
                        {foreach from=$customization.fields item='field'}
                            <li>
                                <span>{$field.label}</span>
                                {if $field.type == 'text'}
                                    <span>{$field.text nofilter}</span>
                                {else if $field.type == 'image'}
                                    <img src="{$field.image.small.url}">
                                {/if}
                            </li>
                        {/foreach}
                    </ul>
                </li>
            {/foreach}
        </ul>
    </div>
{/if}

<div class="clearfix"></div>

