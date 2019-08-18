
<section class="page-product-box clearfix">
  <div class="page-product-heading">
	<span>
		{if $products|@count == 1}
		  {l s='%s other product in the same category:' sprintf=[$products|@count] d='Shop.Theme.Catalog'}
		{else}
		  {l s='%s other products in the same category:' sprintf=[$products|@count] d='Shop.Theme.Catalog'}
		{/if}
	</span>

	<div id="next_prodcat" class="slider-btn"></div>
	<div id="prev_prodcat" class="slider-btn"></div>
  </div>

  <div class="products bx_prodcat">
      {foreach from=$products item="product"}
          {include file="catalog/_partials/miniatures/product.tpl" product=$product}
      {/foreach}
  </div>
</section>
