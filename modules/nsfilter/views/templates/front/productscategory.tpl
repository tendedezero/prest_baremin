{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if isset($products) && count($products) > 0 && $products !== false}
<div class="clearfix blockproductscategory">
	<h2 class="productscategory_h2">{$products|@count} {l s='products for the searched criteria:' mod='productscategory'}</h2>
	<div id="{if count($products) > 5}productscategory{else}productscategory_noscroll{/if}">
	{if count($products) > 5}<a id="productscategory_scroll_left" title="{l s='Previous' mod='productscategory'}" href="javascript:{ldelim}{rdelim}">{l s='Previous' mod='productscategory'}</a>{/if}
	<div id="productscategory_list">
		<ul {if count($products) > 5}style="width: {math equation="width * nbImages" width=107 nbImages=$products|@count}px"{/if}>
			{foreach from=$products item='categoryProduct' name=categoryProduct}
			<li {if count($products) < 6}style="width:60px"{/if}>
				<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)}" class="lnk_img" title="{$categoryProduct.name|htmlspecialchars}"><img src="{$link->getImageLink($categoryProduct.link_rewrite, $categoryProduct.id_image, 'medium_default')|escape:'html'}" alt="{$categoryProduct.name|htmlspecialchars}" /></a>
				<p class="product_name">
					<a href="{$link->getProductLink($categoryProduct.id_product, $categoryProduct.link_rewrite, $categoryProduct.category, $categoryProduct.ean13)|escape:'html'}" title="{$categoryProduct.name|htmlspecialchars}">{$categoryProduct.name|truncate:50:'...'|escape:'html':'UTF-8'}</a>
				</p>
				{if !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
				<p class="price_display">
					<span class="price">{convertPrice price=$categoryProduct.price}</span>
				</p>
				{else}
				<br />
				{/if}
			</li>
			{/foreach}
		</ul>
	</div>
	{if count($products) > 5}<a id="productscategory_scroll_right" title="{l s='Next' mod='productscategory'}" href="javascript:{ldelim}{rdelim}">{l s='Next' mod='productscategory'}</a>{/if}
	</div>
	{literal}
	<script type="text/javascript">
	/*	$('#productscategory_list').trigger('goto', [{$middlePosition}-3]);*/
	</script>
	{/literal}
</div>
{/if}
