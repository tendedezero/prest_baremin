<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
// @TODO Find the reason why the blockcart.php is includ multiple time

include_once('./../../config/config.inc.php');
include_once('./../../init.php');
require_once(dirname(__FILE__) . '/classes/QuizAnswers.php');  


$q=intval($_GET['id_order']);

$q=intval($_GET['q']);

    $id_lang=(int)Configuration::get('PS_LANG_DEFAULT');
    	
	//$id_feature_value=(int)$q;  
	

/*	
$searchResults =Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                        SELECT * FROM '._DB_PREFIX_.'feature_product pf	
                        INNER JOIN `'._DB_PREFIX_.'feature_value` f ON (f.`id_feature_value` = pf.`id_feature_value`)	
						INNER JOIN  `'._DB_PREFIX_.'product` p ON p.`id_product`=pf.`id_product`	
                        INNER JOIN  `'._DB_PREFIX_.'product_lang` pl ON pl.`id_product`=p.`id_product`	
                        LEFT JOIN `'._DB_PREFIX_.'image` i
					    ON (i.`id_product` = pl.`id_product`)						
                        WHERE pf.id_feature_value = '.(int)$id_feature_value.'
						AND  pl.id_lang='.$id_lang  
                           );	
*/
 //price 
 /*
 $searchResults= Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
	SELECT *  
	FROM '._DB_PREFIX_.'product p	
	LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product AND pl.id_lang = '.(int)$id_lang.')
	LEFT JOIN `'._DB_PREFIX_.'image` i 	ON (i.`id_product` = pl.`id_product`)'.Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
	LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image`)	
	LEFT JOIN '._DB_PREFIX_.'feature_product pf	 ON (pl.id_product = pf.`id_product`)	
	LEFT JOIN `'._DB_PREFIX_.'feature_value` f ON (f.`id_feature_value` = pf.`id_feature_value`)	
	LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pl.`id_product` = pa.`id_product`)    
	WHERE f.id_feature_value = '.(int)$id_feature_value.'	
    AND  p.`active`=1'			
	);			
 */
 
$currency =Context::getContext()->currency;
$id_feature_value=$_REQUEST['id_order'];
       $searchResults=QuizAnswers::getFeatureProducts($id_feature_value);	

if(!empty($searchResults))	
{ 
  
?>	

<ul class="product_list grid row">
<?php foreach($searchResults as $product){?>
<li class="ajax_block_product col-xs-12 col-sm-6 col-md-6 first-in-line first-item-of-tablet-line first-item-of-mobile-line" >
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

<?php }?>
		



