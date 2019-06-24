<div class="row">
<div class="col-lg-12">
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

{if Tools::isSubmit('deleteproduct') || Tools::isSubmit('submitAddSearchedProduct')}
{$output}
<h2><a href="index.php?controller=AdminModules&configure=nsquotation&token={$token}&id_ns_quotation={$id_ns_quotation}&viewns_quotation">
{l s='Back To Quote' mod='nsquotation'}
</a>
</h2>
{else}
<form id="address_form" class="defaultForm form-horizontal adminaddresses" action="index.php?controller=AdminModules&configure=nsquotation&id_ns_quotation={$quotation->id}&updateproduct_qty&token={$update_token}" method="post" enctype="multipart/form-data" novalidate=""><div class="panel" id="fieldset_0">	
<div class="panel-heading">
<i class="icon-edit"></i>{l s='Edit Product Quantity' mod='nsquotation'}
</div>	
<div class="form-wrapper">	
<div class="form-group">
<input type="hidden" name="id_ns_quotation" value="{$quotation->id}">
<input type="hidden" name="id_product" value="{$id_product}">	
</div>	
<div class="form-group">
<label class="control-label col-lg-3 required">
<span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="Caracteres interdits &amp;lt;&amp;gt;;=#{}">{l s='Product Qty:'}
</span>
</label>
<div class="col-lg-4 ">
<input type="text" name="product_qty" id="product_qty" value="{QuotationProduct::getQtyId($id_product,$id_ns_quotation)}" class="" required="required">																	</div>
</div>	</div><!-- /.form-wrapper -->					<div class="panel-footer"><button type="submit"  name="submitUpdateQuote" class="btn btn-default pull-right"><i class="process-icon-save"></i>{l s='Save'}</button><a href="{$url_back}&id_ns_quotation={$id_ns_quotation}&viewns_quotation" class="btn btn-default" onclick="window.history.back();"><i class="process-icon-cancel"></i>{l s='Cancel'}</a></div>	
<div class="panel-footer">
<a href="{$url_back}&id_ns_quotation={$id_ns_quotation}&viewns_quotation" class="btn btn-default"><i class="process-icon-back"></i>{l s='Back to list' mod='nsquotation'}</a></div>	
</div>
</form>
</div>
</div>
{include file="./quote_products.tpl"}

{/if}