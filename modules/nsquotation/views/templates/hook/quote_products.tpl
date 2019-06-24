{*
* 2007-2016 PrestaShop
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
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<form id="configuration_form" class="defaultForm form-horizontal nsquotation">
<div class="panel">	
<div class="panel-heading">
<i class="icon-edit"></i>{l s='Quote Products' mod='nsquotation'}
<span class="badge"></span>
</div>
<div class="form-wrapper">
</div>
<table class="table product">
<thead>
<tr class="nodrag nodrop">
<th class="center fixed-width-xs"></th>
<th class="">
<span class="title_box">
{l s='ID' mod='nsquotation'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Product Name' mod='nsquotation'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Quantity Requested' mod='nsquotation'}
</span>
</th>
<th class="">
<span class="title_box">
{l s=' Unit  Price ' mod='nsquotation'}
</span>
</th>
<th class="">
<span class="title_box">
{l s=' Total Price ' mod='nsquotation'}
</span>
</th>
<th></th>
</tr>
</thead>
<tbody>
{foreach from=$quotes_products item=product }  
<tr class=" odd">
<td class="row-selector text-center">
<input type="checkbox" name="productBox[]" value="" class="noborder">
</td>
<td class="">{$product.id_product|escape:'html':'UTF-8'}</td>	
<td class="">
<a href="index.php?tab=AdminProducts&id_product={$product.id_product|escape:'html':'UTF-8'}&updateproduct&token={$token|escape:'html':'UTF-8'}">{$product.name|escape:'html':'UTF-8'}</a>   
</td>
<td class="">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$product.product_qty|escape:'html':'UTF-8'}
</td>
<td class="">{Tools::displayPrice($product.price, $currency)}
</td>

<td class="">{Tools::displayPrice(($product.price*$product.product_qty), $currency)}
</td>
<!-- nka -->
<td class="text-right">				
<div class="btn-group-action">				
<div class="btn-group pull-right">
<a href="index.php?controller=AdminModules&configure=nsquotation&id_ns_quotation={$id_ns_quotation}&id_product={$product.id_product}&updateproduct_qty&token={$token}" title="Modifier" class="edit btn btn-default">
	<i class="icon-pencil"></i> {l s='Edit' mod='nsquotation'}
</a>
<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
<i class="icon-caret-down"></i>&nbsp;
</button>
<ul class="dropdown-menu">
<li>
<a href="index.php?controller=AdminModules&configure=nsquotation&id_ns_quotation={$id_ns_quotation}&deleteproduct&id_ns_quotation={$product.id_ns_quotation}&id_product={$product.id_product}&updateproduct_qty&&token={$token|escape:'html':'UTF-8'}" {literal}onclick="if (confirm('Supprimer cet élément ?')){return true;}else{event.stopPropagation(); event.preventDefault();};"{/literal} title="Supprimer" class="delete">
<i class="icon-trash"></i>{l s='Delete' mod='nsquotation'}
</a>
</li>
</ul>
</div>
</div>
</td>
</tr>
{/foreach}
</tbody>
<tfoot>
<tr>
<td>&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;</td>
<td><strong>{l s='Total Products' mod='nsquotation'}</strong></td>
<td><strong>{Tools::displayPrice(($total_products), $currency)}</strong></td>
</tr>
</tfoot>
</table>
<div class="panel-footer">
<a href="{$url_back|escape:'html':'UTF-8'}&id_ns_quotation={$id_ns_quotation}&viewns_quotation" class="btn btn-default">
<i class="process-icon-back"></i>{l s='Back to list' mod='nsquotation'}</a>
</div>
</div>
</form>	
<br/>
<br/>
{include file="./search_product.tpl"}
<br/>