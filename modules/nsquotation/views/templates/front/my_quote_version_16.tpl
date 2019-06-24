{if $logged }

{if  $id_customer==Tools::getValue('id_customer')}



<div class="container">

<h1 class="page-heading">{l s='My Quote' mod='nsquotation'}</h1>

<!-- add new quote-->

{if (Tools::getValue('nka')==add && Tools::getValue('id_product')!='') || Tools::isSubmit('SubmitAddNewQuote') || Tools::isSubmit('SubmitAddNewProduct')}



{if isset($message) && $message==1 && (Tools::isSubmit('SubmitAddNewQuote') || Tools::isSubmit('SubmitAddNewProduct'))}

<h3 style="color:green;">{l s='Product Added Successfully to quote' mod='nsquotation'}</h3>

<p class="info-account">{l s='Please verify the information bellow and confirm your quote.'  mod='nsquotation'}</p>

<h2><a href="{$add_to_quote_link}?nka=products&id_ns_quotation={$last_id}&id_customer={$id_customer}">{l s='View Quote'  mod='nsquotation'}</a></h2> 

 {else} 

{if $message!=''}  

 <h3 style="color:red;">

 {l s='You may have some error during adding to quote process!' mod='nsquotation'}

 </h3>; 

 {/if} 

 <!--include quote-->

<br/>

<form class="form-horizontal" style="background:none repeat scroll 0 0 #f2f2f2 !important;"  action="{$add_to_quote_link}?id_customer={$id_customer}&id_product={Tools::getValue('id_product')}"  method="post" enctype="multipart/form-data">

<input type="hidden" name="id_product" value="{Tools::getValue('id_product')}"> 

<input type="hidden" name="id_ns_quotation" value="{Tools::getValue('id_ns_quotation')}"> 

<table id="cart_summary" class="table table-bordered stock-management-on">

<thead>

<tr class="fees_ct_matrix_head">

<th class="cart_quantity item text-center">{l s='Product'  mod='nsquotation'}</th>

<th class="cart_quantity item text-center">{l s='Qty'  mod='nsquotation'}</th>

<th class="cart_quantity item text-center">{l s='Unit Price'  mod='nsquotation'}</th>

</tr>

</thead>

<tbody>		

{assign var=cover value=Image::getCover($product->id)}																																																								

<tr id="product_2_2_0_5" class="cart_item last_item first_item address_5 odd">		

<td class="cart_product_fees fees-class fees">

<a  title="{$product->name|escape:'html':'UTF-8'}" rel="gal1" href="{$link->getProductLink($product)}" itemprop="url">

<img itemprop="image" src="{$link->getImageLink($product->link_rewrite,$cover['id_image'], 'cart_default')|escape:'html':'UTF-8'}" title="{$product->name|escape:'html':'UTF-8'}" alt="{$product->name|escape:'html':'UTF-8'}"/>

{$product->name|escape:'html':'UTF-8'}

</a>

</td>	

<td class="cart_product_fees fees-class totalplus">

<input type="text" name="product_qty" value="{if isset($smarty.post.product_qty)}{$smarty.post.product_qty}{else}1{/if}">

</td>

<td class="cart_product_fees fees-class totalplus">

{Tools::displayPrice($product->price, $currency)}

</td>			

</tr>					

</tbody>

</table>

<p class="cart_navigation clearfix" >

{if Tools::getValue('id_ns_quotation')=='' }

<button name="SubmitAddNewQuote"  class="btn btn-default standard-checkout btn-md icon-right" title="Send Quote" style="background:#015883;color:#fff;float:right;">

<span>

 {l s='Create new Quote' mod='nsquotation'}

</span>

</button>

{else}

<button name="SubmitAddNewProduct"  class="btn btn-default standard-checkout btn-md icon-right" title="Send Quote" style="background:#015883;color:#fff;float:right;">

<span>

 {l s=' Add to Quote' mod='nsquotation'}

</span>

</button>

{/if}

<a style="background:#015883;color:#fff;float:left;" href="/" class="btn btn-default icon-left" title="Continue shopping">

 <span>{l s='Continue shopping' mod='nsquotation'}</span>

</a>

</p></form>

<!--end include quote--> 

{/if}  

{/if}



 <!-- quote list-->

 {if Tools::getValue('nka')==voir || Tools::getValue('nka')==products }

 

 <!--product list-->

 {if Tools::getValue('nka')==products &&  Tools::getValue('id_ns_quotation')!=''} 

 

 {if Tools::isSubmit('submitConfirmQuote') && Tools::getValue('id_ns_quotation')!=''}

<p class="module_success alert alert-success">{$output}</p>

<h2>

<a href="index.php?controller=order" >

{l s='Continue To Check Out' mod='nsquotation'}

</a>

</h2>

<br/>

{elseif  Tools::isSubmit('submitSendQuote') && Tools::getValue('id_ns_quotation')!=''}



<h3 style="color:green;">{l s='Quote Sent Successfully' mod='nsquotation'}</h3>

<p class="info-account">{l s='We will verify products availability and contact you later.'  mod='nsquotation'}</p>

<h2><a href="{$add_to_quote_link}?nka=voir&id_customer={Tools::getValue('id_customer')}">{l s='Back to quote'  mod='nsquotation'}</a></h2> 



{else}



{if count($isProducts)>0}



<form    action="{$add_to_quote_link}?nka=products&id_ns_quotation={$quotation->id}&id_customer={$quotation->id_customer}"    id="configuration_form" class="defaultForm form-horizontal nsquotation"   method="post" enctype="multipart/form-data"><input type="hidden"  name="id_ns_quotation" value="{$id_ns_quotation}">

<input type="hidden"  name="id_status" value="{$quotation->id_status}">

<input type="hidden"  name="email" value="">

<div class="panel">

<div class="panel-heading">

 {l s='Quote Products:' mod='nsquotation'}

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

{l s='Unit Price' mod='nsquotation'}

</span>

</th>

<th class="">

<span class="title_box">

{l s='Quantity Requested' mod='nsquotation'}

</span>

</th>



<th class="">

<span class="title_box">

{l s='Total Price' mod='nsquotation'}

</span>

</th>



<th></th>

</tr>

</thead>

<tbody>

{foreach from=$quote_products item=product}

<tr class=" odd">

<td class="row-selector text-center">

<img  src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'cart_default')|escape:'html':'UTF-8'}" /></td>

<td class="">

{$product.name} 

</td>

<td class="">{Tools::displayPrice($product.price, $currency)}</td>

<td class="">

{$product.product_qty} 

</td>

<td class="">

{Tools::displayPrice(($product.price*$product.product_qty), $currency)}

</td>

{if $product.id_status==0}

<td class="text-right">

<div class="btn-group-action">

<div class="btn-group pull-right">

<a href="{$add_to_quote_link}?id_ns_quotation={$product.id_ns_quotation}&nka=edit&id_product={$product.id_product}&id_customer={$quotation->id_customer}" title="Edit" class="btn btn-default">

<i class="icon-pencil"></i> {l s='Edit' mod='nsquotation'}

</a>



<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">

<i class="icon-caret-down"></i>&nbsp;

</button>	

<ul  class="dropdown-menu">

<li>

<a href="{$add_to_quote_link}?id_ns_quotation={$product.id_ns_quotation}&nka=edit&id_product={$product.id_product}&id_customer={$quotation->id_customer}" title="Edit" class="edit btn btn-default">

<i class="icon-pencil"></i> {l s='Edit' mod='nsquotation'}

</a>

</li>

<li>

<a href="{$add_to_quote_link}?id_ns_quotation={$product.id_ns_quotation}&nka=deleteproduct&id_product={$product.id_product}&id_customer={$quotation->id_customer}" title="Delete" {literal}onclick="if (confirm('Delete selected item?')){return true;}else{event.stopPropagation(); event.preventDefault();};"{/literal} class="delete btn btn-default">

<i class="icon-trash"></i> {l s='Delete' mod='nsquotation'}

</a>

</li>

</ul>

</div>

</div>

</td>

{/if}

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

<p class="cart_navigation clearfix" >



{if $quotation->id_status==0}

<a href="/" class="btn btn-default">

<i class="process-icon-back"></i>{l s='Add another product' mod='nsquotation'}

</a>

<button type="submit"  name="submitSendQuote" class="btn btn-default standard-checkout btn-md icon-right">

<i class="process-icon-save"></i>{l s='Send Quote'}

</button>

{else}

<a href="{$add_to_quote_link}?nka=voir&id_customer={Tools::getValue('id_customer')}" class="btn btn-default">

<i class="process-icon-back"></i>{l s='Back to Quotes' mod='nsquotation'}

</a>

{/if}



{if $quotation->id_status==2}

<p class="module_error alert alert-warning">{l s='This Quote requests your attention' mod='nsquotation'}</p>



<button type="submit"  name="submitConfirmQuote" class="btn btn-default standard-checkout btn-md icon-right">

<i class="process-icon-save"></i>{l s='Please Confirm your  Quote'}

</button>



{/if}

</p>

</div>

</div>

</form>

{else}<!-- else of count-->

<a href="/" class="btn btn-default">

<i class="process-icon-back"></i>{l s='Continue Shopping' mod='nsquotation'}

</a>

{/if}<!-- end count-->

<br>

{/if}



 

 <!-- end product list-->

 {else} 

<form  class="form-horizontal clearfix" id="ns_quotation">

	<div class="panel col-lg-12">

	<div class="panel-heading">

	</div>

	<div class="table-responsive clearfix">

	<table class="table  ns_quotation">

	<thead>

	<tr class="nodrag nodrop">

	<th class="">

	<span class="title_box ">

	{l s='ID' mod='nsquotation'}

	</span>

	</th>

	<th class="">

	<span class="title_box ">

	  {l s='Status ' mod='nsquotation'}

	</span>

	</th>

	<th class="">

	<span class="title_box ">

	{l s='Date Submitted' mod='nsquotation'}

	</span>

	</th>

	<th class="">

	<span class="title_box ">

	{l s='Updated' mod='nsquotation'}

	</span>

	</th>

	<th></th>

</tr>

	</thead>

<tbody>

{foreach from=$customer_quotes item=quote}

	<tr class=" ">

		<td class="	">

		{$quote.id_ns_quotation}

	    </td>

	    <td class="	">																	



		{if $quote.id_status==0}	



		<p class="module_error alert alert-danger">{l s='Quote not Sent Yet' mod='nsquotation'}</p>



		{/if}	



		{if $quote.id_status==1}	



		<p class="module_error alert alert-warning">{l s='Waiting for Admin Validation' mod='nsquotation'}</p>



		{/if}	



		{if $quote.id_status==2}	



		<p class="module_error alert alert-warning">{l s='Please Complete your Order' mod='nsquotation'}</p>



		{/if}		



		{if $quote.id_status==3}	



		<p class="module_success alert alert-success">{l s='Processed & Completed' mod='nsquotation'}</p>



		{/if}	



		</td>				



		<td class="	">																												



		{$quote.date_add}	



		</td>



        <td class="	">																												



		{$quote.date_upd}	



		</td>    		



	<td class="text-right">																																																																													<div class="btn-group-action">				<div class="btn-group pull-right">

    <div class="btn-group-action">

<div class="btn-group pull-right">

	<a href="{$add_to_quote_link}?nka=products&id_ns_quotation={$quote.id_ns_quotation}&id_customer={$quote.id_customer}" class=" btn btn-default" title="View">

	<i class="icon-search-plus"></i>{l s='View Products' mod='nsquotation'}

    </a>	

{if $quote.id_status==0}	

	<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">

    <i class="icon-caret-down"></i>&nbsp;

    </button> 

		

<ul  class="dropdown-menu">

  <li>

<a href="{$add_to_quote_link}?nka=products&id_ns_quotation={$quote.id_ns_quotation}&id_customer={$quote.id_customer}" title="Edit" class="edit btn btn-default">

<i class="icon-pencil"></i> {l s='Edit' mod='nsquotation'}

</a>

</li>

<li>

<a href="{$add_to_quote_link}?id_ns_quotation={$quote.id_ns_quotation}&nka=deletequote&id_customer={$quote.id_customer}" title="Delete" {literal}onclick="if (confirm('Delete selected item?')){return true;}else{event.stopPropagation(); event.preventDefault();};"{/literal} class="delete btn btn-default">

<i class="icon-trash"></i> {l s='Delete' mod='nsquotation'}

</a>

</li>

</ul>	

 {/if}  

	



</div>



</div>				



</td>



</tr>



{/foreach}



</tbody>



	</table>



</div>



	</div>



</form>



{/if}



 

 {/if}

 <!-- end quote list-->

 </div>

 {if Tools::getValue('nka')==deleteproduct}

 <h3 style="color:green;">{$delete_product_msg}</h3> 

 <a href="{$add_to_quote_link}?nka=products&id_ns_quotation={Tools::getValue('id_ns_quotation')}&id_customer={$quotation->id_customer}" class=" btn btn-default" title="View">



	<i class="icon-search-plus"></i>{l s='View Products' mod='nsquotation'}

 </a> 

 {/if}

 

 {if Tools::getValue('nka')==deletequote}

 <h3 style="color:green;">{$delete_quote_msg}</h3>

 <a href="{$add_to_quote_link}?nka=voir&id_customer={Tools::getValue('id_customer')}" class=" btn btn-default" title="View">



	<i class="icon-search-plus"></i>{l s='Back To List' mod='nsquotation'}



 </a>

 {/if}

 

 

  <!--edit quote-->

  

 {if Tools::isSubmit('SubmitUpdateQuoteProduct')} 

 <h3 style="color:green;">{l s='Product Updated Successfully ' mod='nsquotation'}</h3>

 <h2><a href="{$add_to_quote_link}?nka=products&id_ns_quotation={Tools::getValue('id_ns_quotation')}&id_customer={Tools::getValue('id_customer')}">{l s='Back to Quote'  mod='nsquotation'}</a></h2> 

 {/if}  

  {if Tools::getValue('nka')==edit && Tools::getValue('id_ns_quotation')!='' }

 

 <form class="form-horizontal" style="background:none repeat scroll 0 0 #f2f2f2 !important;"  action="{$add_to_quote_link}?id_ns_quotation={$id_ns_quotation}&nka=edit&id_product={$id_product}&id_customer={$quotation->id_customer}"  method="post" enctype="multipart/form-data">

<input type="hidden" name="id_product" value="{Tools::getValue('id_product')}">

<input type="hidden" name="id_ns_quotation" value="{Tools::getValue('id_ns_quotation')}">  

<input type="hidden" name="SubmitUpdateQuoteProduct" value="1">

<table id="cart_summary" class="table table-bordered stock-management-on">

<thead>

<tr class="fees_ct_matrix_head">

<th class="cart_quantity item text-center">{l s='Product'  mod='nsquotation'}</th>

<th class="cart_quantity item text-center">{l s='Qty'  mod='nsquotation'}</th>

<th class="cart_quantity item text-center">{l s='Unit Price'  mod='nsquotation'}</th>

</tr>

</thead>

<tbody>																																																										

<tr id="product_2_2_0_5" class="cart_item last_item first_item address_5 odd">		

<td class="cart_product_fees fees-class fees">

<a  title="{$product->name|escape:'html':'UTF-8'}" rel="gal1" href="{$link->getProductLink($product)}" itemprop="url">

<img itemprop="image" src="{$link->getImageLink($product->link_rewrite, QuotationProduct::getCover($product->id), 'cart_default')|escape:'html':'UTF-8'}" title="{$product->name|escape:'html':'UTF-8'}" alt="{$product->name|escape:'html':'UTF-8'}"/>

{$product->name|escape:'html':'UTF-8'}

</a>

</td>	

<td class="cart_product_fees fees-class totalplus">

<input type="text" name="product_qty" value="{if isset($smarty.post.product_qty)}{$smarty.post.product_qty}{else}{QuotationProduct::getQtyId($id_product,$id_ns_quotation)}{/if}">

</td>

<td class="cart_product_fees fees-class totalplus">

 {Tools::displayPrice($product->price, $currency)}

</td>			

</tr>					

</tbody>

</table>

<p class="cart_navigation clearfix" >

<button name="SubmitUpdateQuoteProduct"  class="btn btn-default standard-checkout btn-md icon-right" title="Send Quote" style="background:#015883;color:#fff;float:right;"><span>

 {l s='Update Quantity' mod='nsquotation'}

</span>

</button>

<a style="background:#015883;color:#fff;float:left;" href="{$add_to_quote_link}?nka=products&id_ns_quotation={$id_ns_quotation}&id_customer={Tools::getValue('id_customer')}" class="btn btn-default icon-left" title="Continue shopping">

 <span>{l s='Back to Quote' mod='nsquotation'}</span>

</a>

</p>

</form> 

 {/if} 

 

 {else}

 <a style="background:#015883;color:#fff;float:left;" href="/" class="btn btn-default icon-left" title="Continue shopping">

 <span>{l s='Continue Shopping' mod='nsquotation'}</span>

</a>

 {/if}

 <!-- end edit quote--> 

 

 <!-- closing is logged-->

 {else}        

			{l s='You should login before adding a quote!' mod='nsquotation'} <br/><a style="color:red;" href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Log in to your customer account' mod='nsquotation'}" class="login" rel="nofollow">{l s='Login' mod='nsquotation'}</a>       

 {/if}

 <!-- closing--> 

 

 

 

 

