<?php
include_once('./../../config/config.inc.php');
include_once('./../../init.php');
require_once(dirname(__FILE__) . '/classes/QuizAnswers.php');  
$q=intval($_GET['id_order']);
//$vote= $_REQUEST['id_order'];
?>

<input type="hidden"  name='id_fvalue' value="<?php echo $q;?>" class="form-control" />

<?php

$currency =Context::getContext()->currency;
$id_feature_value=$_REQUEST['id_order'];
       $searchResults=QuizAnswers::getFeatureProducts($id_feature_value);	

if(!empty($searchResults))	
{ 
  
?>	
<div class="clearfix blockproductscategory">
<div id="productscategory_list">
<?php $nbImages=count($searchResults);   $width=107*$nbImages;  ?>
<ul   <?php if (count($searchResults) >5){?>style="<?php echo $width;?>px;"<?php }?> >
<?php foreach($searchResults as $product){?>
<li <?php if (count($searchResults) < 6){ ?>style="width:60px;"<?php }?>>
	
	<a class="lnk_img" href="index.php?id_product=<?php echo $product['id_product'];?>&controller=product" title="<?php echo $product['name'];?>" itemprop="url">
	<img class="replace-2x img-responsive" src="<?php echo $link->getImageLink($product['link_rewrite'], $product['id_image'], 'home_default');?>" alt="<?php echo $product['name'];?>" title="<?php echo $product['name'];?>" " itemprop="image">
	</a>

<p class="product_name">
<a href="index.php?id_product=<?php echo $product['id_product'];?>&controller=product" title="<?php echo $product['name'];?>" itemprop="url">
	<?php echo $product['name'];?>
</a>
</p>
<p class="product-desc" itemprop="description">
<?php echo substr($product['description_short'], 0, strpos(wordwrap($product['description_short'], 50), "\n")) ;?>...
</p> 
	
	<p class="price_display">
	<span class="price">
	<?php echo  Tools::displayPrice($product['price'],$currency);?> 			
	</span>
	</p>								

</li>	

	<?php }
	?>			
</ul>
</div>
</div>
</div>

<?php }
	?>	
