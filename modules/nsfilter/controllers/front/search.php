<?php
if (!defined('_PS_VERSION_'))
 exit; 
class  nsfiltersearchModuleFrontController extends ModuleFrontController
{
	  public function initContent()
	  {
	  
	     $this->display_column_left =false;
         $this->display_column_right =false;
		
		
		parent::initContent();		
		
		$id_lang = (int)Context::getContext()->language->id;
		
		$quiz=new Filter();
		//$categories=$quiz->getAllCategories();
		$categories=$this->getCategories($id_lang,true, true);
		
		$manufacturers = Manufacturer::getManufacturers();
		
		$id_feature_value=Tools::getValue('id_fvalue');
		$this->context->smarty->assign(array(
		'logged' => $this->context->customer->isLogged(),
		'customerName' => ($this->context->customer->logged ? $this->context->customer->firstname.' '.$this->context->customer->lastname : false),
		'firstName' => ($this->context->customer->logged ? $this->context->customer->firstname : false),
		'lastName' => ($this->context->customer->logged ? $this->context->customer->lastname : false),		
		'nka_add_voucher_submit_link' => $this->context->link->getModuleLink('nsfilter', 'search'),
		'result_submit_link' => $this->context->link->getModuleLink('nsfilter', 'search'),
        'questions'=>$this->getQuestions(),   
        'categories'=>$categories,	 
		'products'=>FilterAnswers::getFeatureProducts($id_feature_value),
        'allow_oosp' => (int)Configuration::get('PS_ORDER_OUT_OF_STOCK'),
		'comparator_max_item' => (int)Configuration::get('PS_COMPARATOR_MAX_ITEM'),	
        'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
		'categorySize' => Image::getSize(ImageType::getFormatedName('category')),
		'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
		'thumbSceneSize' => Image::getSize(ImageType::getFormatedName('m_scene')),
		'homeSize' => Image::getSize(ImageType::getFormatedName('home')),        	
        'id_fvalue'=>$id_feature_value,
        'manufacturers' => $manufacturers,
        'suppliers' => Supplier::getSuppliers(false, $id_lang),		
        'id_customer'=> ($this->context->customer->logged ? $this->context->customer->id: false)		
		
        
	));
		$this->setTemplate('ns_search.tpl');
		
    }		
	 
   public function getQuestions(){	
     $id_category=(int)Tools::getValue('id_category');
     $category=new Category($id_category);	

      $p=1;
	  $n=100;
	  $orderBy='id_product';
	  $orderWay='DESC';
	 
	  if($id_category!=''){
	  
	  $cat_products =$category->getProducts($this->context->language->id, (int)$p, (int)$n, $orderBy, $orderWay);
	  
     return $cat_products;	  
	 }   
  } 
   
	
	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(array(
				_THEME_CSS_DIR_.'scenes.css' => 'all',
				_THEME_CSS_DIR_.'category.css' => 'all',
				_THEME_CSS_DIR_.'product_list.css' => 'all',
			));
		
		$this->addJS(_THEME_JS_DIR_.'scenes.js');
		$this->addJqueryPlugin(array('scrollTo', 'serialScroll'));
		$this->addJS(_THEME_JS_DIR_.'category.js');
	}

	
	
	public  function getCategories($id_lang = false, $active = true, $order = true, $sql_filter = '', $sql_sort = '', $sql_limit = '')
	{
	 	if (!Validate::isBool($active))
	 		die(Tools::displayError());
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'category` c
			'.Shop::addSqlAssociation('category', 'c').'
			LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON c.`id_category` = cl.`id_category`'.Shop::addSqlRestrictionOnLang('cl').'
			WHERE 1 '.$sql_filter.' '.($id_lang ? 'AND `id_lang` = '.(int)$id_lang : '').'
			'.($active ? 'AND `active` = 1' : '').'
			'.(!$id_lang ? 'GROUP BY c.id_category' : '').'
			'.($sql_sort != '' ? $sql_sort : 'ORDER BY c.`level_depth` ASC, category_shop.`position` ASC').'
			'.($sql_limit != '' ? $sql_limit : '')
		);

		//if (!$order)
			return $result;

		
	}
	
    


}//end of class

