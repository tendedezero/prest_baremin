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

<div id="_desktop_cart">

	<div class="cart_top">

		<div class="blockcart cart-preview {if $cart.products_count > 0}active{else}inactive{/if}" data-refresh-url="{$refresh_url}">
			<div class="header">
			
				<div class="cart_index_title">
					<a class="cart_link" rel="nofollow" href="{$cart_url}">
						<i class="material-icons shopping-cart">shopping_cart</i>
						<span class="hidden-sm-down cart_title">{l s='Cart' d='Shop.Theme.Checkout'}:</span>
						<span class="cart-products-count ">
							{$cart.products_count}<span class='hidden-sm-down'> {if $cart.products_count == 1}{l s='Product' d='Shop.Theme.Mytheme'}{else}{l s='Products' d='Shop.Theme.Mytheme'}{/if} - {$cart.subtotals.products.value}</span>
						</span>
					</a>
				</div>


				<div id="subcart">

					<ul class="cart_products">

					{if $cart.products_count == 0}
						<li>{l s='There are no more items in your cart' d='Shop.Theme.Checkout'}</li>
					{/if}

					{foreach from=$cart.products item=product}
						<li>
							{include 'module:ps_shoppingcart/ps_shoppingcart-product-line.tpl' product=$product}
						</li>
					{/foreach}
					</ul>

					<ul class="cart-subtotals">

				{*
						{foreach from=$cart.subtotals item="subtotal"}
						<li class="{$subtotal.type}">
							<span class="text">{$subtotal.label}</span>
							<span class="value">{$subtotal.value}</span>
							<span class="clearfix"></span>
						</li>
						{/foreach}

						<li>
							<span class="text">{$cart.subtotals.products.label}</span>
							<span class="value">{$cart.subtotals.products.value}</span>
							<span class="clearfix"></span>
						</li>
				*}

						<li>
							<span class="text">{$cart.subtotals.shipping.label}</span>
							<span class="value">{$cart.subtotals.shipping.value}</span>
							<span class="clearfix"></span>
						</li>

						<li>
							<span class="text">{$cart.totals.total.label}</span>
							<span class="value">{$cart.totals.total.value}</span>
							<span class="clearfix"></span>
						</li>

					</ul>

					<div class="cart-buttons">
						<a class="btn btn-primary viewcart" href="{$cart_url}">{l s='Check Out' d='Shop.Theme.Mytheme'} <i class="material-icons">&#xE315;</i></a>
						{* <a class="btn btn-primary checkout" href="{$urls.pages.order}">{l s='Check Out' d='Shop.Theme.Mytheme'}</a> *}
					</div>

				</div>

			</div>
		</div>

	</div>

</div>

