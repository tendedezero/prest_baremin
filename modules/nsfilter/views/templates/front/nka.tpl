

<ul class="product_list row grid">
{foreach from=$products  item=product}
<li class="ajax_block_product col-xs-12 col-sm-6 col-md-6 first-in-line first-item-of-tablet-line first-item-of-mobile-line" >
	<div class="product-container" itemscope="" itemtype="https://schema.org/Product">
	<div class="product-container-img">
	<a class="product_img_link" href="index.php?id_product={$product.id_product}&controller=product" title="{$product.name}" itemprop="url">
	<img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" alt="{$product.name}" title="{$product.name}" " itemprop="image">
	</a>
<div class="wt-label">
	</div>
<div class="prod-hover">
<div class="out-button">
	<a class="quick-view" href="index.php?id_product={$product.id_product}&controller=product" title="{l s='Quick view'}">
		<span>{l s='Quick view'}</span>
	</a>																
<!--<div class="wishlist">
	<a class="addToWishlist wishlistProd_9" href="index.php?id_product={$product.id_product}&controller=product" title="Aggiungi alla lista dei desideri" onclick="WishlistCart('wishlist_block_list', 'add', '9', false, 1); ">
		Aggiungi alla lista dei desideri
	</a>
</div>--->
	<div class="compare">
	<a class="add_to_compare" href="{$product.link_rewrite}" data-id-product="{$product.id_product}" title="Confronta">
	 {l s='Compare'}
	</a>
	</div>
	</div>
<div class="functional-buttons clearfix">
	<a class="button ajax_add_to_cart_button btn btn-default" href="{$product.link}?add=1&amp;id_product={$product.id_product}&amp;token={Tools::getToken(false)}" rel="nofollow" title="{$product.name}" data-id-product="8" data-minimal_quantity="1">
	<span>{l s='Add to cart'}</span>
	</a>
</div>
</div>
</div>
<h5 itemprop="name">
<a class="product-name" href="index.php?id_product={$product.id_product}&controller=product"   title="{$product.name}" itemprop="url">
	{$product.name}
</a>
</h5>
<p class="product-desc" itemprop="description">
{substr($product.description_short, 0, strpos(wordwrap($product.description_short, 70), "\n")) }...
</p> 
	<div class="content_price">
	<span class="price product-price">
	{displayPrice price=$product.price} 			
	</span>
<span class="old-price product-price">
</span>
</div>										
<!--button add cart in-->
<div class="functional-buttons clearfix">
	<a class="button ajax_add_to_cart_button btn btn-default" href="{$product.link_rewrite}?add=1&amp;id_product={$product.id_product}&amp;token={Tools::getToken(false) }" rel="nofollow" title="Aggiungi al carrello" data-id-product="9" data-minimal_quantity="1">
		<span>{l s='Add to cart'}</span>
	</a>																	
</div>
<!--button add cart out-->
<div class="product-flags">	
</div>
<span class="availability">
<span class="label-danger">
{l s='Out of stock'}
</span>
</span>
</div><!-- .product-container> -->
</li>

	{/foreach}		
</ul>
