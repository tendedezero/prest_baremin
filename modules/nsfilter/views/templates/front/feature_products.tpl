{*

* 2007-2015 PrestaShop

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

*  @copyright  2007-2015 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)

*  International Registered Trademark & Property of PrestaShop SA

*}



{capture name=path}{l s='Related products'}{/capture}



<h1 class="page-heading product-listing">{l s='Related products'}</h1>



{if $products}

	<div class="content_sortPagiBar">

    	<div class="sortPagiBar clearfix">

			{include file="./product-sort.tpl"}

			{include file="./nbr-product-page.tpl"}

		</div>

	</div>



	{include file="./product-list.tpl" products=$products}



	<div class="content_sortPagiBar">

        <div class="bottom-pagination-content clearfix">

        	{include file="./product-compare.tpl"}

			{include file="./pagination.tpl" no_follow=1 paginationId='bottom'}

        </div>

	</div>

	{else}

	<p class="alert alert-warning">{l s='No related products.'}</p>

{/if}

