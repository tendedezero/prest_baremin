<?php
if (!defined('_PS_VERSION_'))
 exit; 
class  simplequizdisplayModuleFrontController extends ModuleFrontController
{
	  public function initContent()
	  {
	  
	     $this->display_column_left =false;
         $this->display_column_right =false;
		
		
		parent::initContent();		
		
		$quiz=new Quiz();
		$categories=$quiz->getAllCategories();
		
		$id_lang = (int)Context::getContext()->language->id;
		$id_feature_value=Tools::getValue('id_fvalue');
		$this->context->smarty->assign(array(
		'logged' => $this->context->customer->isLogged(),
		'customerName' => ($this->context->customer->logged ? $this->context->customer->firstname.' '.$this->context->customer->lastname : false),
		'firstName' => ($this->context->customer->logged ? $this->context->customer->firstname : false),
		'lastName' => ($this->context->customer->logged ? $this->context->customer->lastname : false),		
		'nka_add_voucher_submit_link' => $this->context->link->getModuleLink('simplequiz', 'display'),
		'result_submit_link' => $this->context->link->getModuleLink('simplequiz', 'result'),
        'questions'=>$this->getQuestions(),   
        'categories'=>$categories,	 
		'products'=>QuizAnswers::getFeatureProducts($id_feature_value),
        'allow_oosp' => (int)Configuration::get('PS_ORDER_OUT_OF_STOCK'),
		'comparator_max_item' => (int)Configuration::get('PS_COMPARATOR_MAX_ITEM'),	
        'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
		'categorySize' => Image::getSize(ImageType::getFormatedName('category')),
		'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
		'thumbSceneSize' => Image::getSize(ImageType::getFormatedName('m_scene')),
		'homeSize' => Image::getSize(ImageType::getFormatedName('home')),        	
        'id_fvalue'=>$id_feature_value,	        		
        'id_customer'=> ($this->context->customer->logged ? $this->context->customer->id: false)		
		
        
	));
		$this->setTemplate('display.tpl');
		
    }		
	 
   public function getQuestions(){	
     $category=Tools::getValue('category');	
	  if(!empty($category)){
	  
	  $res ='SELECT  * FROM `'._DB_PREFIX_.'simplequiz_questions` s
	        WHERE s.`category_id`='.$category.' ORDER BY s.`id_question`  ASC';
       $rows = Db::getInstance()->ExecuteS($res);
	  
     return $rows;	  
	 }   
  } 
   
	
	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(array(
			_THEME_CSS_DIR_.'history.css',
			_THEME_CSS_DIR_.'addresses.css'
		));
		$this->addJS(array(
			_THEME_JS_DIR_.'history.js',
			_THEME_JS_DIR_.'tools.js' // retro compat themes 1.5
		));
		$this->addJqueryPlugin('footable');
		$this->addJqueryPlugin('footable-sort');
		$this->addJqueryPlugin('scrollTo');
	}

	
    


}//end of class

