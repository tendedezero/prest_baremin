{if Tools::isSubmit('submitValidateQuote') && Tools::getValue('id_ns_quotation')!=''}{$output}
<h2>
<a href="index.php?controller=AdminModules&configure=nsquotation&id_ns_quotation={$id_ns_quotation}&viewns_quotation&token={$token}" >
{l s='View Details' mod='nsquotation'}
</a>
</h2>
<br/>
{else}

<form id="configuration_form" class="defaultForm form-horizontal nsquotation"  action="index.php?controller=AdminModules&configure=nsquotation&id_ns_quotation={$id_ns_quotation}&viewns_quotation&token={$token}" method="post" enctype="multipart/form-data" >
<input type="hidden"  name="id_ns_quotation" value="{$id_ns_quotation}">
<input type="hidden"  name="id_status" value="{$quotation->id_status}"><input type="hidden"  name="id_customer" value="{$quotation->id_customer}">
<input type="hidden"  name="email" value="{QuotationProduct::getCustomerEmail($quotation->id_customer)}">
<div class="panel">	
<div class="panel-heading">
<i class="icon-edit"></i>{l s='Quote Details:' mod='nsquotation'}
</div>	
<div class="form-wrapper">
</div>
<table class="table product">
<thead>
<tr class="nodrag nodrop">
<th class="center fixed-width-xs">
<span class="title_box">
{l s='Image' mod='nsquotation'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Name' mod='nsquotation'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Price' mod='nsquotation'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Quantity Requested' mod='nsquotation'}
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
{foreach from=$quotationDetails item=product  name=foo} 

<tr class=" odd">
<td class="row-selector text-center">
<img  src="http://{$link->getImageLink($product.link_rewrite, $product.id_image, 'cart_default')|escape:'html':'UTF-8'}" />
</td>
<td class="">
{$product.name} 
</td>
<td class="">{displayPrice price=$product.price}</td>
<td class="">
{$product.product_qty} 
</td>
<td class="">
{Tools::displayPrice(($product.price*$product.product_qty), $currency)}
</td>

<td class="text-right">	

<div class="btn-group-action">				

<div class="btn-group pull-right">
{if $quotation->id_status==1}
<a href="index.php?controller=AdminModules&configure=nsquotation&id_ns_quotation={$product.id_ns_quotation}&id_product={$product.id_product}&updateproduct_qty&token={$token}" title="Edit" class="edit btn btn-default">

<i class="icon-pencil"></i> {l s='Edit' mod='nsquotation'}

</a>
{/if}
</div>

</div></td>
</tr>

{/foreach}	
<strong>{l s='Quote n°:' mod='nsquotation'}</strong>
 000{$quotation->id}
 <br/>
<strong>{l s='Customer:' mod='nsquotation'}</strong>
{QuotationProduct::getCustomerName($quotation->id_customer)} 
<br/> 
<a href="mailto:{QuotationProduct::getCustomerEmail($quotation->id_customer)} ?subject=Liste de demande n°000{$quotation->id}">
{QuotationProduct::getCustomerEmail($quotation->id_customer)}
</a>
<br/>
<strong>{l s='Phone:' mod='nsquotation'}</strong>
{QuotationProduct::getCustomerPhone($quotation->id_customer)}
<br/><br/>
<strong>{l s='Status:' mod='nsquotation'} </strong>

        		
		{if $quotation->id_status==1}	
		 <p class="module_error alert alert-warning">{l s='Waiting for admin validation' mod='nsquotation'}</p>
		{/if}		
		{if $quotation->id_status==2}	
		<p class="module_error alert alert-warning">{l s='Waiting for Customer confirmation' mod='nsquotation'}</p>
		{/if}		
		{if $quotation->id_status==3}	
		<p class="module_success alert alert-success">{l s='Processed & Completed' mod='nsquotation'}</p>
		{/if} 

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

<a href="{$url_back}" class="btn btn-default">

<i class="process-icon-back"></i>{l s='Back to list' mod='nsquotation'}
</a>
{if $quotation->id_status==1}
<button type="submit"  name="submitValidateQuote" class="btn btn-default pull-right">
<i class="process-icon-save"></i>{l s='Validate Quote'}
</button>
{/if}

</div>
</div>
</form>	
<br/>
{/if}
