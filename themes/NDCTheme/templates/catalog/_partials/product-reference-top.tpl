{* AngarThemes *}

{if isset($product.reference_to_display)}
	<div class="product-reference_top product-reference">
	  <label class="label">{l s='SKU' d='Shop.Theme.Catalog'} </label>
	  <span>{$product.reference_to_display}</span>
	</div>
{/if}