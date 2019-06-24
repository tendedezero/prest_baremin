{if $logged}
 <div  style="width:100%;"  class="product-quantity clearfix">	
{if $quotation->id==''}
 <a  class="btn btn-primary add-to-cart cart-block"  href="{$add_to_quote_link}&nka=add&id_customer={$id_customer}">
 {l s='Add New Quote' mod='nsquotation'} 	
 </a> 
{else}
<a  class="btn btn-primary add-to-cart cart-block"  href="{$add_to_quote_link}&nka=add&id_ns_quotation={$quotation->id}&id_customer={$quotation->id_customer}">
 {l s='Add this Product to quote' mod='nsquotation'} 	
 </a> 
 {/if}
 </div>
 {else}
 {l s='You should login before adding a quote!' mod='nsquotation'} <br/>
 <a style="color:red;" href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Log in to your customer account' mod='shippingprovider'}" class="login" rel="nofollow">
 {l s='Login' mod='shippingprovider'}
 </a>
{/if}

<!--- style="background:#015883;color:#fff;"-->				  

