<?php

if (!defined('_PS_VERSION_'))

 exit; 

class simplequizproductsModuleFrontController extends ModuleFrontController

{
     
	  public function initContent()

	  {

	    $this->display_column_left = false;

        $this->display_column_right =false;

		

		parent::initContent();

		

		$id_lang = (int)Context::getContext()->language->id;

		$id_product=(int)Configuration::get('DEFAULT_PROD_ID');

		$id_feature_value=Tools::getValue('id_order');

			

		$this->context->smarty->assign(array(	

        'products'=>QuizAnswers::getFeatureProducts($id_feature_value)	        

	));		
		
		$this->setTemplate('productscategory.tpl');
		//$this->setTemplate('nka.tpl');
		
		//$this->setTemplate(_PS_THEME_DIR_.'order-detail.tpl');
		
		

		

    }		

	 

	
	
   
	
   public function displayAjax()
	{
		$this->display();
	}
	

    	

   public function setMedia()
	{
		if (Tools::getValue('ajax') != 'true')
		{
			parent::setMedia();
			$this->addCSS(_THEME_CSS_DIR_.'history.css');
			$this->addCSS(_THEME_CSS_DIR_.'addresses.css');
		}
	}
  


}//end of class

























