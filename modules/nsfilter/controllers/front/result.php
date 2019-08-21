<?php
if (!defined('_PS_VERSION_'))
 exit; 
class simplequizresultModuleFrontController extends ModuleFrontController
{     
	  public function initContent()
	  {
	    $this->display_column_left = false;
        $this->display_column_right =false;
		
		parent::initContent();
		
		$id_lang = (int)Context::getContext()->language->id;
		$id_product=(int)Configuration::get('DEFAULT_PROD_ID');
		$id_feature_value=Tools::getValue('id_fvalue');
			
		$this->context->smarty->assign(array(
		'logged' => $this->context->customer->isLogged(),
		'customerName' => ($this->context->customer->logged ? $this->context->customer->firstname.' '.$this->context->customer->lastname : false),
		'firstName' => ($this->context->customer->logged ? $this->context->customer->firstname : false),
		'lastName' => ($this->context->customer->logged ? $this->context->customer->lastname : false),		
		'nka_update_voucher_submit_link' => $this->context->link->getModuleLink('simplequiz', 'display'),
        'results'=>$this->getResults(),        
        'id_customer'=> ($this->context->customer->logged ? $this->context->customer->id: false),
        'products'=>QuizAnswers::getFeatureProducts($id_feature_value)		
		
        
	));					$this->setTemplate('result.tpl');		
    }		
	 
	public function getResults(){	
	
    $id_customer=($this->context->customer->logged ? $this->context->customer->id: false);
	
	if(!empty($id_customer)){
   $features=array();
   $keys=array_keys($_POST);
   $order=join(",",$keys);
    
/*
   $response=Db::getInstance()->ExecuteS('SELECT id_answer,answer_point 
             FROM `'._DB_PREFIX_.'simplequiz_answers` 
			 WHERE id_answer IN('.$order.') ORDER BY FIELD(id_answer,'.$order.')') ;
  
   foreach($response as $result){   
    if($result['id_answer']==$_POST[$result['id_answer']]){
               $features['id_answer']=$result['answer_point'];   
        }	    
    } */
   $this->context->smarty->assign(
					array(
						'features' =>$features,						)
				      );	    
	}
	   }
	   
	   public function displayAjax()	{		$this->display();	}
	
    	
   public function setMedia()	{		if (Tools::getValue('ajax') != 'true')		{			parent::setMedia();			$this->addCSS(_THEME_CSS_DIR_.'history.css');			$this->addCSS(_THEME_CSS_DIR_.'addresses.css');		}	}

}//end of class












