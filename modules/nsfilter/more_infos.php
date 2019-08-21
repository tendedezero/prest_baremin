<?php
include_once('./../../config/config.inc.php');
include_once('./../../init.php');
require_once(dirname(__FILE__) . '/classes/Filter.php');  
require_once(dirname(__FILE__) . '/classes/FilterQuestions.php');  

    $currency =Context::getContext()->currency;
	$id_lang = (int)Context::getContext()->language->id;     
	 

     if(isset($_REQUEST['id_category'])&& $_REQUEST['id_category']!='')	 
	 {
	 $id_category=$_REQUEST['id_category'];  
	 
	 $searchResults=Filter::getResults($id_category);
	 }
	 
	 if(isset($_REQUEST['id_manufacturer'])&& $_REQUEST['id_manufacturer']!='')	 
	 {
	 $id_manufacturer=$_REQUEST['id_manufacturer'];  
	 $searchResults=Manufacturer::getProducts($id_manufacturer, $id_lang,1,100,'id_product', 'DESC');
	 }
	 
	 
	 if(isset($_REQUEST['id_supplier'])&& $_REQUEST['id_supplier']!='')	 
	 {
	 $id_supplier=$_REQUEST['id_supplier'];  
	 $searchResults=Manufacturer::getProducts($id_supplier, $id_lang,1,100,'id_product', 'DESC');
	 }
	 
	 
	 if( isset($_REQUEST['id_type']) && $_REQUEST['id_type']!='')	 
	 {
	  $id_type=$_REQUEST['id_type'];  
	  
	  
	   if($id_type==1)
	 $searchResults=Product::getNewProducts($id_lang, 0, (int)Configuration::get('NEW_PRODUCTS_NBR'));
	   if($id_type==2)
	 $searchResults=Filter::getBestSellers();
	 if($id_type==3)
	 $searchResults=Filter::getHomeFeaturedeatured();
	// if($id_type==4)
	// $searchResults=Filter::getResults($id_category);
	 
	 }
	
 
	
if(!empty($searchResults))	
{ 
  
?>	

<ul class="product_list row grid">
<?php foreach($searchResults as $product){?>
<li style="float:left;clear:right;" class="ajax_block_product col-xs-12 col-sm-6 col-md-6 first-in-line first-item-of-tablet-line first-item-of-mobile-line" >
	<div class="product-container" itemscope="" itemtype="https://schema.org/Product">
	<div class="product-container-img">
	<a class="product_img_link" href="index.php?id_product=<?php echo $product['id_product'];?>&controller=product" title="<?php echo $product['name'];?>" itemprop="url">
	<img class="replace-2x img-responsive" src="<?php echo $link->getImageLink($product['link_rewrite'], $product['id_image'], 'home_default');?>" alt="<?php echo $product['name'];?>" title="<?php echo $product['name'];?>" " itemprop="image">
	</a>
<div class="wt-label">
	</div>
<div class="prod-hover">


</div>
</div>
<h5 itemprop="name">
<a class="product-name" href="index.php?id_product=<?php echo $product['id_product'];?>&controller=product" title="<?php echo $product['name'];?>" itemprop="url">
	<?php echo $product['name'];?>
</a>
</h5>
<p class="product-desc" itemprop="description">
<?php echo substr($product['description_short'], 0, strpos(wordwrap($product['description_short'], 70), "\n")) ;?>...
</p> 
	<div class="content_price">
	<span class="price product-price">
	<?php echo  Tools::displayPrice($product['price'],$currency);?> 			
	</span>
<span class="old-price product-price">
</span>
</div>
<div class="product-flags">	
</div>

</div><!-- .product-container> -->
</li>	

	<?php }//end foreach?>			
</ul>
<?php
}
else
{
?>
<p class="alert alert-warning">
No product for this feature   
</p>

<?php 
}



?>
		
