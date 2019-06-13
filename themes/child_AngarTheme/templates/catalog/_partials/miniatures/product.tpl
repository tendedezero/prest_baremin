{extends file='parent:catalog/_partials/miniatures/product.tpl'}

	
{block name='product_price_and_shipping'}
{if $product.show_price}
<div class="product-price-and-shipping">
	{hook h='displayProductPriceBlock' product=$product type="before_price"}
	<span class="sr-only">{l s='Price' d='Shop.Theme.Catalog'}</span>
	
	<div class='current-price sale-price inc-vat' {if (Context::getContext()->cookie->__get('VATMODE') == 'false')} style='display:none' {/if}> <span class="price">{$product.price}</span> Inc Vat</div>
	<div class='current-price sale-price ex-vat' {if (Context::getContext()->cookie->__get('VATMODE') == 'true')} style='display:none'{/if}><span class="price">{Tools::displayPrice(Product::getPriceStatic($product.id_product,false))}</span> Exc Vat</div>
	  {assign var a value=(Context::getContext()->cookie->__get('VATMODE')=='true') }
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
	
	