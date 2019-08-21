<?php
if (!defined('_PS_VERSION_')){
  exit;
 } 
 
require_once(dirname(__FILE__) . '/classes/Filter.php');  
require_once(dirname(__FILE__) . '/classes/FilterQuestions.php');  
require_once(dirname(__FILE__) . '/classes/FilterAnswers.php');  


  class nsfilter  extends Module
  
  { 
  
  public function __construct()
  {
    $this->name = 'nsfilter';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'NdiagaSoft';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' =>_PS_VERSION_);
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('Simple Filter');
    $this->description = $this->l('Simple Shop Filter .');
 
    $this->confirmUninstall = $this->l('Are you sure you want to uninstall?'); 
    
  }
  
  
  public function install()
{
  if (Shop::isFeatureActive())
    Shop::setContext(Shop::CONTEXT_ALL); 
  if (!parent::install() ||
    !$this->registerHook('DisplayNav') ||	
	!$this->registerHook('displayTopColumn')||
	!$this->registerHook('myAccountBlock')|| 
	!$this->registerHook('customerAccount')||    		 
    !$this->registerHook('productFooter') ||
    !$this->registerHook('header')||    
    !Configuration::updateValue('DEFAULT_PROD_ID',1) 	
  )
    return false;
	
	
	$sql = array();
        require_once(dirname(__FILE__) . '/sql/install.php');
        foreach ($sql as $sq) :
            if (!Db::getInstance()->Execute($sq))
                return false;
        endforeach;
		
  // $this->installDemo();
  return true;
}
       public function uninstall()
    {      
		
		if (!parent::uninstall()||
		    
            !Configuration::deleteByName('DEFAULT_PROD_ID')				
           )
                return false;  	
    			
     $sql = array();
        require_once(dirname(__FILE__) . '/sql/uninstall.php');
        foreach ($sql as $s) :
            if (!Db::getInstance()->Execute($s))
                return false;
        endforeach;		
				
           return true;
    }  
  
        public function getContent()
    {
          $output = null;
		  
		 $this->addnewCategory();
		 $this->addnewQuestion();
		 $this->addnewAnswer();
		 
		 $output.= '<div class="panel">This is free as an MPV, if you need further customization you can : <a href="http://prestatuts.com/en/">
            <button class="btn btn-default">'.$this->l('Contact Me').'</button>
            </a></div>';
		$search_link=$this->context->link->getModuleLink('nsfilter', 'search');	
		  $output.='<div class="panel"> <a href="'.$search_link.'"  target="_blank">
            <button class="btn btn-default">'.$this->l('Start the Search').'</button>
            </a></div>';
		    if (Tools::isSubmit('submit'.$this->name))
      {
        $my_module_name =Tools::getValue('id_product');
        if (!$my_module_name || empty($my_module_name) || !Validate::isGenericName($my_module_name))
            $output .= $this->displayError($this->l('Invalid Configuration value'));
        else
        {
		   
				
		    
            Configuration::updateValue('DEFAULT_PROD_ID',(int)$my_module_name);
		
            $output.= $this->displayConfirmation($this->l('Settings updated'));
        }
      }
		  
		   if (Tools::isSubmit('deletesimplequiz_categories') && Tools::isSubmit('id'))
		{
		 $quizCat=new Filter((int)Tools::getValue('id'));
         $quizCat->delete();	
               		   
		} 
		// Filter answers
		
		   if (Tools::isSubmit('deletesimplequiz_answers') && Tools::isSubmit('id_answer'))
		{
		 $FilterAnswers=new FilterAnswers((int)Tools::getValue('id_answer'));
         $FilterAnswers->delete();	
               		   
		} 
		
		
		//Filter questions
		   if (Tools::isSubmit('deletesimplequiz_questions') && Tools::isSubmit('id_question'))
		{
		 $FilterQuestions=new FilterQuestions((int)Tools::getValue('id_question'));
         $FilterQuestions->delete();	
               		   
		} 
		
		
		   if (Tools::isSubmit('updatesimplequiz_questions') && Tools::isSubmit('id_question'))
		{
		 
		 //$output=$this->renderUpdateQuestion();
		 
		  
		 $output.='</br>'.$this->InnovativesLabs();
               	 
		}  	
		
		
  elseif ((Tools::isSubmit('viewsimplequiz_questions') || Tools::isSubmit('updatesimplequiz_answers')) && Tools::isSubmit('id_question'))
		{
		 
		 //$output=$this->renderViewAnswers();
		 $output.='</br>'.$this->InnovativesLabs();
               	 
		} 
		
  		
		
         // Filter categories
           elseif (Tools::isSubmit('updatesimplequiz_categories') && Tools::isSubmit('id'))
		{
		 
		 //$output.=$this->renderUpdate();
		 $output.='</br>'.$this->InnovativesLabs();
               	 
		}  		  
		elseif (Tools::isSubmit('viewsimplequiz_categories') && Tools::isSubmit('id'))
		{
		   
		//$output.=$this->renderOrders().'</br>'.$this->InnovativesLabs();	
        $output.='</br>'.$this->InnovativesLabs();		
		}		   
		else
       {		           //$this->displayForm();
		   //$output.='</br>'.$this->renderAddNewCategoryForm().'</br>'.$this->renderList().'</br>'.$this->InnovativesLabs();
           $output.='</br>'.$this->InnovativesLabs();
	   }	
            return $output;
    } 
	
// list
      	public function renderList()
	{
		$fields_list = array(
		
		   'id' => array(
				'title' => $this->l('ID Filter'),
				'search' => false,
			),
			'category_name' => array(
				'title' => $this->l('Category Name'),
				'search' => false,
			)				
		);

		if (!Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE'))
			unset($fields_list['shop_name']);
		$helper_list = New HelperList();
		$helper_list->module = $this;
		$helper_list->title = $this->l('Filter: Last added categories');
		$helper_list->shopLinkType = '';
		$helper_list->no_link = true;
		$helper_list->show_toolbar = true;
		$helper_list->simple_header = false;
		$helper_list->identifier = 'id';
		$helper_list->table = 'nsfilter_categories';
		$helper_list->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name;
		$helper_list->token = Tools::getAdminTokenLite('AdminModules');
		$helper_list->actions = array('view','edit','delete');
		$helper_list->bulk_actions = array(
			'select' => array(
				'text' => $this->l('Change order status'),
				'icon' => 'icon-refresh',				
			)
		); 		

		// This is needed for displayEnableLink to avoid code duplication
		$this->_helperlist = $helper_list;

		/* Retrieve list data */
		$order=new Filter();
		$orders=$order->getAllCategories();
		$helper_list->listTotal = count($order->getAllCategories());

		/* Paginate the result */
		$page = ($page = Tools::getValue('submitFilter'.$helper_list->table)) ? $page : 1;
		$pagination = ($pagination = Tools::getValue($helper_list->table.'_pagination')) ? $pagination : 50;
		$orders = $this->paginateOrderProducts($orders, $page, $pagination);

		return $helper_list->generateList($orders, $fields_list);
		
	}
  
  	public function renderAddNewCategoryForm()
	{  
	  
    $this->context->smarty->assign(
      array(	         		  
		  'url_submit_add'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
		  'message_alert'=>$this->addnewCategory(),         
            )
         );   
	  
		return $this->display(__FILE__, 'submitAddnewCategory.tpl');
	}
	
	public function getConfigFieldsValues()
	{
		return array(
			'innova_card_number_chiffre' => Tools::getValue('innova_card_number_chiffre', Configuration::get('innova_card_number_chiffre')),
			
		);
	} 
  
  //end adding vouchers  
  
   public function paginateOrderProducts($orders, $page = 1, $pagination = 50)
	{
		if(count($orders) > $pagination)
			$orders= array_slice($orders, $pagination * ($page - 1), $pagination);

		return $orders;
	}  
  

//list	
  
  
   public function hookDisplayNav($params)
	{
		return $this->hookTop($params);
	}
  
    
    public function hookTop($params)
    {
        return '';
		
    }
	
	
	public function hookdisplayTopColumn($params)
	{
		// return $this->displayLinkFront();
		
		
	}
   
    
    public function hookDisplayHeader()
   {
        //$this->context->controller->addCSS(($this->_path).'css/dynamic_banner.css', 'all');		
		//$this->context->controller->addCSS($this->_path.'css/productscategory.css', 'all');	
        $this->context->controller->addCSS($this->_path.'css/pop_up.css', 'all');	
        $this->context->controller->addCSS($this->_path.'css/style.css', 'all');			
		//$this->context->controller->addJS($this->_path.'js/productscategory.js');
        //$this->context->controller->addJS($this->_path.'js/hist.js');	
       // $this->context->controller->addJS($this->_path.'js/pop_up.js');			
		//$this->context->controller->addJqueryPlugin(array('scrollTo', 'serialScroll', 'bxslider'));
    }  	
	
	//adding new Filter category
	   public function addnewCategory(){	
	   
	   $message_alert='';
	   
	   $id_lang=(int)Context::getContext()->language->id; 
	   
     if(Tools::isSubmit('submitAddnewCategory')){         	 
		
        $QuizCat =new Filter();
        
		$QuizCat->category_name=Tools::getValue('category_name');
		$Quiz_chiffre=$QuizCat->category_name;
		
		if(Filter::verifyByName($Quiz_chiffre) !=''){
         $message_alert='Please remember duplicated Categories name are not allowed.';
   }	
		
            elseif(!$QuizCat->add()){$messaege_alert="An error has occurred: Can\'t save the current object";	}
       else{
            			
		//$sql= 'UPDATE`'._DB_PREFIX_.'dynamic_banner` SET `banner_img`=\''.pSQL($image_url).'\' 
		       //WHERE `id_banner`='.$QuizCat->id;                        
                 // Db::getInstance()->execute($sql); 
        $message_alert="Your Category has been successfully added.";	
		
		
		}	
		
      }
	  return $message_alert;
	  
	  
   }  

   // adding new question a the current category

     public function addnewQuestion(){	
	   
	   $message_alert='';
	   
	   $id_lang=(int)Context::getContext()->language->id; 
	   $category_id=Tools::getValue('id');
     if(Tools::isSubmit('submitAddnewQuestion')  && !empty($category_id)){    	 
		
        $FilterQuestions=new FilterQuestions();        
		
		$FilterQuestions->question_name=Tools::getValue('question_name');
		$FilterQuestions->more_infos=Tools::getValue('more_infos');
		$Quiz_name=$FilterQuestions->question_name;
		$FilterQuestions->category_id=$category_id;
		
		
		if(FilterQuestions::verifyByName($Quiz_name)!=''){
           $message_alert='Please remember duplicated questions  are not allowed.';
		   }			
		
		 elseif(!$FilterQuestions->add()){			
			$message_alert="All field are required.";
        } else {
            
			$message_alert="Your question has been successfully added.";
        }		
		
		
      }
	  
	  
	  return $message_alert;
	  
	  
   } 

  //adding new answer to current question

       public function addnewAnswer(){	
	   
	   $message_alert='';
	   
	   $id_lang=(int)Context::getContext()->language->id; 
	   $question_id=(int)Tools::getValue('question_id');
	   $answer_point=(int)Tools::getValue('answer_point');
     if(Tools::isSubmit('submitAddnewAnswer') && !empty($question_id)){    	 
		
        $FilterAnswers=FilterAnswers::loadByIdQuestion($question_id);        
		
		$FilterAnswers->answer_name=Tools::getValue('answer_name');
		$Quiz_name=$FilterAnswers->answer_name;
		$FilterAnswers->question_id=$question_id;		
		$FilterAnswers->answer_point=$answer_point;
		
		
		if(FilterAnswers::verifyByName($Quiz_name)!=''){
           $message_alert='Please remember duplicated answers  are not allowed.';
		   }			
		
		 if(!$FilterAnswers->add()){            
			$message_alert="Error can not add answer.";
        } else {			
			$message_alert="Your answer has been successfully added.";
        }		
		
		
      }
	  
	  
	  return $message_alert;
	  
	  
   }    

      
   
   public function displayLinkFront(){
   
   $id_customer=($this->context->customer->logged ? $this->context->customer->id: false);
	$Filter=new Filter();  
   
   $this->context->smarty->assign(
      array(	      
		  'intro_text'=>Configuration::get('DEFAULT_PROD_ID'),          
		  'voucher'=>$Filter, 
          'my_voucher_list_link' => $this->context->link->getModuleLink('nsfilter', 'display'),
          'search_link' => $this->context->link->getModuleLink('nsfilter', 'search') 	 		  
          
            )
               );  	 
         
       return $this->display(__FILE__, 'displayLinkFront.tpl'); 
   
   }
   
     public function renderOrders(){  
    
	    $category_id=(int)Tools::getValue('id');
        $questions=Filter::loadByIdCategory($category_id);
	
    $this->context->smarty->assign(
      array(
	      
		  'id_customer'=>Tools::getValue('id'),		  
          'questions'=>$questions,  
          'new_question'=>$this->addnewQuestion(),
          'category_id'=>$category_id,		
          'url_submit_add'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),		  
		  'url_back'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')
		  
          
            )
               );  
  
  return $this->display(__FILE__, 'view_questions.tpl');
  }
   
  public function  renderUpdate(){  
  
        $category_id=(int)Tools::getValue('id');
        $QuizCat=new Filter($category_id);
		$category_name=Tools::getValue('category_name');
  if(Tools::isSubmit('submitUpdateCategory') && !empty($category_id) && !empty($category_name)){
        
		$QuizCat->category_name=$category_name;        	
	   $QuizCat->update();
  } 
       	
	   
    $this->context->smarty->assign(
      array(	      
		  'category_id'=>$category_id,
          'quiz_cat'=>$QuizCat,	
          'mod_name'=>$this->name,		  
          'update_token'=>Tools::getAdminTokenLite('AdminModules'),
          'url_back'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')          
            )
            );  
   
    return $this->display(__FILE__, 'edit_category.tpl');
	
   }
    
  
  
  //for employee email 
  
  	   public function displayForm()
  {
    // Get default language
	 $productObj = new Product();
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
	$id_shop=Context::getContext()->shop->id;;
	$id_lang=(int)Context::getContext()->language->id; 
    $products = $productObj -> getProducts($id_lang, 0, 0, 'id_product', 'DESC' );
    // Init Fields form array
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('Settings'),
			'icon' => 'icon-cogs'
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('Default product id is:'),
                'name' => 'DEFAULT_PROD_ID',
                'size' => 20,
                'required' => true,
				'desc' => $this->l('Select a value bellow if you wish to change it.')
            ),			
			array(
					'type' => 'select',
					'label' => $this->l('Select a Product'),
					'name' => 'id_product',
					
					'options' => array(
						'query' =>$products,
						'id' => 'id_product',
						'name' => 'name'
						
					),
					'desc' => $this->l('Select the Product  you want to redirect to after success.')
                      ),	
        ),
        'submit' => array(
            'title' => $this->l('Save'),           
        )
    );
     
    $helper = new HelperForm();
     
    // Module, token and currentIndex
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
     
    // Language
    $helper->default_form_language = $default_lang;
    $helper->allow_employee_form_lang = $default_lang;
     
    // Title and toolbar
    $helper->title = $this->displayName;
    $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit'.$this->name;
    $helper->toolbar_btn = array(
        'save' =>
        array(
            'desc' => $this->l('Save'),
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
            '&token='.Tools::getAdminTokenLite('AdminModules'),
        ),
        'back' => array(
            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Back to list')
        )
    );
     
    // Load current value
    $helper->fields_value['DEFAULT_PROD_ID'] = Configuration::get('DEFAULT_PROD_ID');
	$helper->fields_value['id_product'] =Tools::getValue('id_product');
     
    return $helper->generateForm($fields_form);
    } 
  

  
  public function innovativesLabs(){
  
  $html= '
		<br/>
		<fieldset>
			<legend><img src="'.$this->_path.'img/more.png" alt="" title="" /> '.$this->l('More Modules & Themes ').'</legend>	
			<iframe src="http://prestatuts.com/advertising/prestashop_advertising.html" width="100%" height="420px;" border="0" style="border:none;"></iframe>
			</fieldset>';
			
	   return $html;	
  
  }
  	
	
	public function hookProductFooter($params)
	{
			 			
			return $this->displayLinkFront();
			
	}
	
	
	
	
	 public function installDemo(){ 
  $sql = array();
        require_once(dirname(__FILE__) . '/sql/install_demo.php');
        foreach ($sql as $sq) :
            if (!Db::getInstance()->Execute($sq))
                return false;
        endforeach;
 
 
 }	


   //view answers

  public function renderViewAnswers(){  
    $id_lang = (int)Context::getContext()->language->id;
	    $id_question=(int)Tools::getValue('id_question');
		$FilterQuestions=new FilterQuestions($id_question);
        $answers=$FilterQuestions->getAnswers($id_question);
		$category_id=(int)$FilterQuestions->category_id;   //;
		
  if(Tools::isSubmit('updatesimplequiz_answers') && Tools::getValue('id_answer')!='')
  {
   $id_answer=(int)Tools::getValue('id_answer');
   $FilterAnswers=new FilterAnswers($id_answer);
   
   $this->context->smarty->assign('answerObj',$FilterAnswers);  
   
    if(Tools::isSubmit('submitUpdateAnswer') && !empty($id_answer))
	
	{  
	$FilterAnswers->question_id=(int)Tools::getValue('question_id');
	$FilterAnswers->answer_name=Tools::getValue('answer_name');
	$FilterAnswers->answer_point=(int)Tools::getValue('answer_point');
	$FilterAnswers->update();
	}
  
	  
  }  
	
    $this->context->smarty->assign(
      array(
	      'all_features'=>Feature::getFeatures($id_lang,true) , 
		  'id_question'=>$id_question,		  
          'answers'=>$answers,          		  
          'new_answer'=>$this->addnewQuestion(),
          'category_id'=>$category_id, 		  
          'url_submit_add'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),		  
		  'url_back_questions'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')
		  
          
            )
               );  
  
  return $this->display(__FILE__, 'renderViewAnswers.tpl');
  }
   
   
      public function renderUpdateQuestion(){
	  
	  $message_alert='';
	   $id_question=(int)Tools::getValue('id_question');
		$FilterQuestions=new FilterQuestions($id_question);        
		$category_id=(int)$FilterQuestions->category_id;
		$question_name=Tools::getValue('question_name');
		$more_infos=Tools::getValue('more_infos');
   
    if(Tools::isSubmit('submitUpdateQuestion') && !empty($category_id) && !empty($question_name)){
	        $FilterQuestions->question_name=$question_name;
			$FilterQuestions->more_infos=$more_infos;
			$FilterQuestions->category_id=$category_id;            
			$message_alert="Your question has been successfully added.";   
			$FilterQuestions->update();
		}
		$this->context->smarty->assign(
      array(
	      
		  'id_question'=>$id_question,  
          'question'=>$FilterQuestions, 		  
          'category_id'=>$category_id, 
          'new_question'=>$message_alert,		  
          'url_submit_add'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),		  
		  'url_back_questions'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules')
		  
          
            )
               );  
	  
	  return $this->display(__FILE__, 'renderUpdateQuestion.tpl');
	  }
  
  
  }

