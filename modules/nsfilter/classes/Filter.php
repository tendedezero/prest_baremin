<?php



class Filter extends ObjectModel
{
	/** @var string Name */
	public $id;
	
	/** @var string Name */
	public $category_name;
	
    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'nsfilter_categories',
        'primary' => 'id',
        'multilang' => FALSE,
        'fields' => array(            		
            'category_name'=>array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => true, 'size' => 228),			
			            	
        ),
    );    
	
	
	public function getAllCategories()
	{
	    $sql='';
	    $id_lang = (int)Context::getContext()->language->id; 
		$dbquery = new DbQuery();
		$dbquery->select('d.`id` AS `id`, d.`category_name` AS `category_name`');
		$dbquery->from('nsfilter_categories', 'd');		
		
		$orders = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbquery->build());		

		return $orders;
		
	}	
	
	
		//get quote details
	
	public static function loadByIdCategory($id){
	$id_lang=(int)Context::getContext()->language->id; 
	if(Tools::getValue('id')){
	$id=Tools::getValue('id');	
	$sql = 'SELECT  * FROM `'._DB_PREFIX_.'nsfilter_questions` q    
	        WHERE  q.`category_id`='.$id;	
    $results = Db::getInstance()->ExecuteS($sql);
    
	return $results;	
	
	}
	
	}
	
	
	//verify duplicated cards number
	
	public static function verifyByName($query)
	{
		return Db::getInstance()->getRow('
			SELECT iv.`id`
			FROM `'._DB_PREFIX_.'nsfilter_categories` iv			
			WHERE iv.`category_name` LIKE \''.pSQL($query).'\'
		');
	}

	
	
   public static  function getResults($id_category){	
     
	 
	 $context = Context::getContext();
	 
     $category=new Category($id_category);	

      $p=1;
	  $n=100;
	  $orderBy='id_product';
	  $orderWay='DESC';
	 
	  if($id_category!=''){
	  
	  $cat_products =$category->getProducts($context->language->id, (int)$p, (int)$n, $orderBy, $orderWay);
	  
     return $cat_products;	  
	 }   
  } 	
	
	
	/*getting home featured products*/
	
	public static function getHomeFeaturedeatured()
	{
		
	    
		$category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);
		$nb = (int)Configuration::get('HOME_FEATURED_NBR');
		$home_products = $category->getProducts((int)Context::getContext()->language->id, 1, ($nb ? $nb : 8), 'position');
		
		
	    return $home_products;
		
	}
   
   
   /*getting best sellers*/
	
	public static  function getBestSellers()
	{
		$id_lang =(int)Context::getContext()->language->id;
		$id_currency=(int)Context::getContext()->currency->id;
		
		if (Configuration::get('PS_CATALOG_MODE'))
			return false;

		if (!($result = ProductSale::getBestSalesLight($id_lang, 0, 8)))
			return (Configuration::get('PS_BLOCK_BESTSELLERS_DISPLAY') ? array() : false);

		$currency = new Currency($id_currency);
		$usetax = (Product::getTaxCalculationMethod((int)Context::getContext()->customer->id) != PS_TAX_EXC);
		foreach ($result as &$row)
			$row['price'] = Tools::displayPrice(Product::getPriceStatic((int)$row['id_product'], $usetax), $currency);

		return $result;
	}



	
	
	
	
	
}

