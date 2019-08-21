<?php
include_once('./../../config/config.inc.php');
include_once('./../../init.php');
require_once(dirname(__FILE__) . '/classes/QuizAnswers.php');  
$q=intval($_GET['id_order']);
//$vote= $_REQUEST['id_order'];
?>

<input type="hidden"  name='id_fvalue' value="<?php echo $q;?>" class="form-control" />
<link rel="stylesheet" href="/themes/greylook/css/product_list.css" type="text/css" media="all" />
<link rel="stylesheet" href="/themes/greylook/css/category.css" type="text/css" media="all" />
<link rel="stylesheet" href="/themes/greylook/css/scenes.css" type="text/css" media="all" />
<?php

$currency =Context::getContext()->currency;
$id_feature_value=$_REQUEST['id_order'];
       $searchResults=QuizAnswers::getFeatureProducts($id_feature_value);	

if(!empty($searchResults))	
{   
?>	
<ul class="product_list grid row">		
<?php foreach($searchResults as $product){?>
<li class="ajax_block_product col-xs-12 col-sm-6 col-md-6 col-lg-4 first-in-line first-item-of-tablet-line first-item-of-mobile-line">
<div class="product-container" itemscope="" itemtype="https://schema.org/Product">
<div class="left-block">
<div class="product-image-container">
<a class="product_img_link" href="index.php?id_product=<?php echo $product['id_product'];?>&controller=product" title="<?php echo $product['name'];?>" itemprop="url">
	<img class="replace-2x img-responsive" src="<?php echo $link->getImageLink($product['link_rewrite'], $product['id_image'], 'home_default');?>" alt="<?php echo $product['name'];?>" title="<?php echo $product['name'];?>" width="250" height="250" itemprop="image">
</a>
<div class="content_price" itemprop="offers" itemscope="" itemtype="https://schema.org/Offer">
<span itemprop="price" class="price product-price" style="color: rgb(0, 0, 0);"><?php echo  Tools::displayPrice($product['price'],$currency);?> </span>
<div class="dpdSecondPrice">
<span style="font-size:13.333333333333334px;color: rgb(61, 61, 61);"><?php echo  Tools::displayPrice($product['price'],$currency);?>  incl. BTW</span>
</div>
<meta itemprop="priceCurrency" content="EUR">																		                                                                             
<span class="unvisible">
<span class="tm_avaibility">
<link itemprop="availability" href="https://schema.org/InStock">Levertijd 2 werkdagen
</span>
 </span>
 </div>
 <div class="hoverimage">
    <div class="functional-buttons clearfix">                                   
   <div class="compare">
 <a class="add_to_compare" href="index.php?id_product=<?php echo $product['id_product'];?>&controller=product" data-id-product="<?php echo $product['id_product'];?>">
 Toevoegen aan vergelijken
 </a>
  </div>                                                                        
  </div>
 </div>
</div>
										
</div>
<div class="right-block">			
<div class="hook-reviews">						
<div class="clear"></div>
<div class="reviews_list_stars ">
    <span class="star_content clearfix">
    <img src="http://dbfootwear-dev.nl.172-28-5-132.uccloud.nl/modules/productrating/views/img/rstar2.png" class="list-img-star-category" alt="0">
     <img src="http://dbfootwear-dev.nl.172-28-5-132.uccloud.nl/modules/productrating/views/img/rstar2.png" class="list-img-star-category" alt="1">
    <img src="http://dbfootwear-dev.nl.172-28-5-132.uccloud.nl/modules/productrating/views/img/rstar2.png" class="list-img-star-category" alt="2">
    <img src="http://dbfootwear-dev.nl.172-28-5-132.uccloud.nl/modules/productrating/views/img/rstar2.png" class="list-img-star-category" alt="3">
     <img src="http://dbfootwear-dev.nl.172-28-5-132.uccloud.nl/modules/productrating/views/img/rstar2.png" class="list-img-star-category" alt="4">
                    <!---------<span>(0)</span>----->
    </span>

</div>
</div>
	<h5 itemprop="name">
<a class="product-name" href="index.php?id_product=<?php echo $product['id_product'];?>&controller=product" title="<?php echo $product['name'];?>" itemprop="url">
		<?php echo $product['name'];?>
</a>
</h5>
<p class="product-desc" itemprop="description">
<?php echo substr($product['description_short'], 0, strpos(wordwrap($product['description_short'], 50), "\n")) ;?>
</p>
<div class="content_price">
						                                        
<span class="price product-price" style="color: rgb(0, 0, 0);"><?php echo  Tools::displayPrice($product['price'],$currency);?> </span>
<div class="dpdSecondPrice">
<span style="font-size:13.333333333333334px;color: rgb(61, 61, 61);"><?php echo  Tools::displayPrice($product['price'],$currency);?> incl. BTW</span>
</div>
</div>
<div class="product-flags">
																														
</div>
  <span class="availability">
<span class=" label-success">
	Levertijd 2 werkdagen									
</span>
</span>
</div>
	<div class="button-container">
 <a class="button lnk_view btn btn-default" href="index.php?id_product=<?php echo $product['id_product'];?>&controller=product" title="Toon">
    <span>Meer</span>
   </a>
  </div>
			</div><!-- .product-container> -->
</li>
			
		
		


	<?php }
	?>			
</ul>


<?php }
	?>	
	
	
	
	
	
	
	
	
	
	
	
	
