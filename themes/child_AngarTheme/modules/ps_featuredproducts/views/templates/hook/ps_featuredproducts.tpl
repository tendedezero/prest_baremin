
<section class="featured-products block clearfix">
  <h2 class="h2 products-section-title text-uppercase index_title">
    <a href="{$allProductsLink}">{l s='Popular Products' d='Shop.Theme.Catalog'}</a>
  </h2>
  <div class="products">
    {foreach from=$products item="product"}
      {include file="catalog/_partials/miniatures/product.tpl" product=$product}
    {/foreach}
  </div>
  <a class="all-product-link float-xs-left float-md-right h4" href="{$allProductsLink}">
    {l s='All products' d='Shop.Theme.Catalog'}<i class="material-icons">&#xE315;</i>
  </a>
</section>