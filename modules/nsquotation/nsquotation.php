<?php
/*
* 2007-2017 PrestaShop
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
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
if (!defined('_PS_VERSION_'))	exit;	
require_once(dirname(__FILE__) . '/classes/QuotationProduct.php');  	

class NsQuotation extends Module 
{
	public function __construct()
	{
		$this->name = 'nsquotation';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'NdiagaSoft';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();	

		$this->displayName = $this->l('NS Quotation');
		$this->description = $this->l('Allow customers to send you quote requests.');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		
		
		$sql = array();
	
        $sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ns_quotation` (
                  `id_ns_quotation` int(10) unsigned NOT NULL AUTO_INCREMENT,				  
				  `id_shop` int(11) NOT NULL,
				  `id_currency` int(11) NOT NULL, 
                  `id_customer` int(11) NOT NULL, 
                  `id_lang` int(11) NOT NULL, 
				  `id_status` int(11) NOT NULL DEFAULT 0, 
                  `date_add` datetime NOT NULL, 
                  `date_upd` datetime NOT NULL, 					 
                  PRIMARY KEY (`id_ns_quotation`)
                ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
				
		$sql[]='CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'quotation_product` (
                  `id_ns_quotation` int(10) unsigned NOT NULL,
                  `id_product` int(10) unsigned NOT NULL,                  
                  `id_shop` int(10) unsigned NOT NULL DEFAULT 1,                  
                  `product_qty` int(10) unsigned NOT NULL DEFAULT 0,
                  `date_add` datetime NOT NULL,
                  KEY `quotation_product_index` (`id_ns_quotation`,`id_product`),
                    KEY `id_product` (`id_product`)
               ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
		
		if (
			parent::install() == false			
			|| $this->registerHook('header') == false			
			||$this->registerHook('productFooter')==false
			||$this->registerHook('leftColumn')==false 
	        ||$this->registerHook('myAccountBlock')==false
	        ||$this->registerHook('customerAccount')==false
            ||$this->registerHook('displayProductAdditionalInfo')==false
			||$this->runSql($sql)==false
			
			)		
			return false;			
			
		return true;
	}
	
	   public function uninstall()
    {
	    
		
        $sql = array();	
        $sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'ns_quotation`';
		$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'quotation_product`';	
		if (!parent::uninstall()||
		    !$this->runSql($sql)||
			!Configuration::deleteByName('QUOTATION_EMPLOYEE_EMAIL') ||
			!Configuration::deleteByName('QUOTATION_ADMIN_DIR') 
           )
                return false;  
           return true;
    }	
	public function runSql($sql) {	
        foreach ($sql as $s) {
			if (!Db::getInstance()->Execute($s)){
				return FALSE;
			}
        }       
        return TRUE;
    }		
	public function getContent()
	{
		$output = '';
		
		
		
		
		if (Tools::isSubmit('deletens_quotation') && Tools::getValue('id_ns_quotation')!='')
		{
		 $id_ns_quotation=(int)Tools::getValue('id_ns_quotation');
		
	         $sql= 'DELETE FROM `'._DB_PREFIX_.'quotation_product` 
	            WHERE id_ns_quotation='.$id_ns_quotation;			  				
                    
					Db::getInstance()->execute($sql); 
					
		      $quote=new QuotationProduct($id_ns_quotation);
		 
		    $quote->delete();
					
		 $output.= $this->displayConfirmation($this->l('Quote successfully Removed from list.'));
      
		
		}
		
		
		if (Tools::isSubmit('submit'.$this->name))
		{
		    $employee_email=Tools::getValue('QUOTATION_EMPLOYEE_EMAIL');
			$admin_dir=Tools::getValue('QUOTATION_ADMIN_DIR');
			
		   Configuration::updateValue('QUOTATION_EMPLOYEE_EMAIL',$employee_email);
		   Configuration::updateValue('QUOTATION_ADMIN_DIR',$admin_dir);
		   
		   
		   $output .= $this->displayConfirmation($this->l('Settings updated'));
		
		}
		
		$id_shop=$this->context->shop->id;	
		$date_add=date('Y-m-d H:i:s');		
		if ((Tools::isSubmit('viewns_quotation') || Tools::isSubmit('updatens_quotation')) && Tools::getValue('id_ns_quotation')!='')
		{
		  $output=$this->viewQuote();
		}	
		
      elseif((Tools::isSubmit('updateproduct_qty')  && Tools::getValue('id_ns_quotation')!=''
              && Tools::getValue('id_product')!='') || Tools::isSubmit('searchProductNka') 
	        )
		{
		  $output=$this->editQuote();
		  $id_ns_quotation=Tools::getValue('id_ns_quotation');
		  $id_product=Tools::getValue('id_product');
		  $product_qty=Tools::getValue('product_qty');
		  
		      if (Tools::isSubmit('submitAddSearchedProduct')  && Tools::getValue('id_product')!='')
			  {
			  
			  if($this->verifyIdProduct($id_product,$id_ns_quotation)['id_product']==Tools::getValue('id_product')){
		  
		  $product_qty_new=$product_qty+$this->verifyIdProduct($id_product,$id_ns_quotation)['product_qty'];
		  
		  $sql='UPDATE `'._DB_PREFIX_.'quotation_product` 
	               SET  `product_qty`='.(int)$product_qty_new.'
				   WHERE `id_ns_quotation`='.(int)$id_ns_quotation.'
				   AND  `id_product`='. (int)$id_product			  
			       ; 	
              Db::getInstance()->execute($sql);
		  
		  $output .= $this->displayConfirmation($this->l('Settings updated'));
		  
		  
		  }		
			else{  
			  
			  $id_product=Tools::getValue('id_product');
		      $sql='INSERT INTO `'._DB_PREFIX_.'quotation_product` 
	          (`id_ns_quotation`, `id_product`, `id_shop`,`product_qty`,`date_add`)
	          VALUES('.(int)$id_ns_quotation.','.(int)$id_product.','.(int)$id_shop.','.(int)$product_qty.',\''.pSQL($date_add).'\')'; 	
              Db::getInstance()->execute($sql);
			  $output .= $this->displayConfirmation($this->l('Settings updated'));
			  
			  //$output .= $this->displayError($this->l('Invalid number.'));
			  }
           }	

          $output.=$this->deleteProduct($id_product,$id_ns_quotation);		   
		  
		}
		
		else{ 	
           $output.='</br>'.$this->displayForm();		
		   $output.='</br>'.$this->renderList();
		  $output.='</br>'.$this->InnovativesLabs().'</br>'.$this->displayAdvertising();
		}
		return $output;
	}	
	
	public function hookHeader()
	{
		$this->context->controller->addCSS(($this->_path).'nsquotation.css', 'all');		
	}      	
	
	public function hookProductFooter($params)	{ 
	$id_product=(int)Tools::getValue('id_product');		
	$add_to_quote=$this->context->link->getModuleLink('nsquotation', 'my_quote');	
	$add_to_quote.='?id_product='.$id_product;	
	$id_customer=(int)$this->context->customer->id;
	$quotation=new QuotationProduct($this->getCustomerQuotes($id_customer));
	$this->context->smarty->assign(array(
	        'logged' => $this->context->customer->isLogged(),	 
        	'default_number'=>Configuration::get('default_number'), 	
			'add_to_quote_link'=>$add_to_quote,      
			'id_product'=>$id_product,
            'quotation'=>$quotation,
            'id_customer'=>$id_customer,			
			)            
			);
			
			return $this->display(__FILE__, 'product_footer.tpl');	  
	}
    public function displayProductAdditionalInfo($params)	{
        $id_product=(int)Tools::getValue('id_product');
        $add_to_quote=$this->context->link->getModuleLink('nsquotation', 'my_quote');
        $add_to_quote.='?id_product='.$id_product;
        $id_customer=(int)$this->context->customer->id;
        $quotation=new QuotationProduct($this->getCustomerQuotes($id_customer));
        $this->context->smarty->assign(array(
                'logged' => $this->context->customer->isLogged(),
                'default_number'=>Configuration::get('default_number'),
                'add_to_quote_link'=>$add_to_quote,
                'id_product'=>$id_product,
                'quotation'=>$quotation,
                'id_customer'=>$id_customer,
            )
        );

        return $this->display(__FILE__, 'display_Product_Addtional_Info.tpl');
    }


    public function hookmyAccountBlock($params)
	{
		$this->smarty->assign(
					array(
					'add_to_quote_link' =>$this->context->link->getModuleLink('nsquotation', 'my_quote'),					
					'id_customer' => ($this->context->customer->logged ? $this->context->customer->id : false),						
					)
				);				
		return $this->display(__FILE__, 'my_account_block.tpl');
	}
	
	
	public function hookcustomerAccount($params)
	{
	
	$id_customer=($this->context->customer->logged ? $this->context->customer->id: false);
	
		$this->smarty->assign(
		        array(	
				'add_to_quote_link' =>$this->context->link->getModuleLink('nsquotation', 'my_quote'),		       
				'id_customer' => ($this->context->customer->logged ? $this->context->customer->id : false),                				
					)
				);		
		return $this->display(__FILE__, 'customer_account.tpl');
	}	

	
	  public function InnovativesLabs(){   
			
		return '<h2>To use this version fully, you will need to edit some files of your themes\'s  templates</h2>';	
  
  }
  
      public function displayAdvertising()
  {
		$html= '
		<br/>
		<fieldset>
			<legend><img src="'.$this->_path.'img/more.png" alt="" title="" /> '.$this->l('More Modules & Themes ').'</legend>	
			<iframe src="http://prestatuts.com/advertising/prestashop_advertising.html" width="100%" height="420px;" border="0" style="border:none;"></iframe>
			</fieldset>';
			
	   return $html;		
  }
	public function editQuote(){     
	  $id=(int)Tools::getValue('id_ns_quotation');
	  $id_product=(int)Tools::getValue('id_product');
      $quotation=new QuotationProduct($id);	
	  $product_qty=(int)Tools::getValue('product_qty');	  
     $output='';		
  if(Tools::isSubmit('submitUpdateQuote') && !empty($id) && !empty($id_product))
	  {        
		$sql= 'UPDATE`'._DB_PREFIX_.'quotation_product` SET `product_qty`='.$product_qty.'					  
			    
				WHERE `id_ns_quotation`='.$id.'
				 AND `id_product`='.$id_product;				
                    Db::getInstance()->execute($sql);  
	   
	   $output=$this->displayConfirmation($this->l('Your quote has been successfully updated.'));
	   
  } 	 
     $currency = $this->context->currency;
	 $this->smarty->assign(
		        array(	
				'quotation' =>$quotation,
				'output'=>$output,
				'id_product'=>$id_product,
				'currency'=>$currency,
				'id_ns_quotation'=>$id,
				'total_products'=>$this->getTotalProducts($id),
				'quotes_products'=>$this->getQuotationDetails($id),
				 'searched_products' => $this->getSearchedProducts(),
				'update_token'=>Tools::getAdminTokenLite('AdminModules'),
                'url_back'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),				
				                				
					)
				);		
		return $this->display(__FILE__, 'edit_quotation.tpl');
	
	}
	
	public function viewQuote(){
	  $output='';
	  $id=(int)Tools::getValue('id_ns_quotation');	 	
	  $id_customer=(int)Tools::getValue('id_customer');  
	  $email=Tools::getValue('email');	  
      $quotationDetails=$this->getQuotationDetails($id);
      $quotation=new QuotationProduct($id);	  
	  $id_status=Tools::getValue('id_status');
	  $link=new Link();
	  
	  $currency = $this->context->currency;
	
	if(Tools::isSubmit('submitValidateQuote') && Tools::getValue('id_ns_quotation')!=''	&& $id_status!='')
	  {
	     if($id_status==1) $id_status=$id_status+1;         
		$sql= 'UPDATE`'._DB_PREFIX_.'ns_quotation` SET `id_status`='.$id_status.'			    
			   WHERE `id_ns_quotation`='.$id;					
                    Db::getInstance()->execute($sql); 	   		NsQuotation::emailCustomer($email,$id,$id_customer);			
	   $output=$this->displayConfirmation($this->l('Quote has been successfully Validated.'));	   
      } 	      
	 $this->smarty->assign(
		        array(	
				'quotationDetails' =>$quotationDetails,
				'quotation'=>$quotation,
				'link'=>$link,
				'id_ns_quotation'=>$id,
				'total_products'=>$this->getTotalProducts($id),
				'output'=>$output,
				'currency'=>$currency,
                'token'=>Tools::getAdminTokenLite('AdminModules'),
                'url_back'=>AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),          				
				                				
				)
				);		
		return $this->display(__FILE__, 'view_quotation.tpl');
	
	}	
	// list
      	public function renderList()
	{
		$fields_list = array(
		
		   'id_ns_quotation' => array(
				'title' => $this->l('ID'),
				'search' => false,
			),
			'firstname' => array(
				'title' => $this->l('First Name'),
				'search' => false,
			),
            'lastname' => array(
				'title' => $this->l('Last Name'),
				'search' => false,
			),				
			'id_status' => array(
				'title' => $this->l('Status'),
				'search' => false,
			),
			'date_add' => array(
				'title' => $this->l('Date Submitted'),
				'search' => false,
			)	
		);

		if (!Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE'))
			unset($fields_list['shop_name']);
		$helper_list = New HelperList();
		$helper_list->module = $this;
		$helper_list->title = $this->l('Quotation: Last requests');
		$helper_list->shopLinkType = '';
		$helper_list->no_link = true;
		$helper_list->show_toolbar = true;
		$helper_list->simple_header = false;
		$helper_list->identifier = 'id_ns_quotation';
		$helper_list->table = 'ns_quotation';
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
		
		$orders=$this->getAllQuotes();
		$helper_list->listTotal = count($this->getAllQuotes());

		/* Paginate the result */
		$page = ($page = Tools::getValue('submitFilter'.$helper_list->table)) ? $page : 1;
		$pagination = ($pagination = Tools::getValue($helper_list->table.'_pagination')) ? $pagination : 50;
		$orders = $this->paginateOrderProducts($orders, $page, $pagination);

		return $helper_list->generateList($orders, $fields_list);
		
	}
	
	
	 public function paginateOrderProducts($orders, $page = 1, $pagination = 50)
	{
		if(count($orders) > $pagination)
			$orders= array_slice($orders, $pagination * ($page - 1), $pagination);

		return $orders;
	 }  
	
	
	public function getAllQuotes(){	
	
	 $sql='SELECT * FROM `'._DB_PREFIX_.'ns_quotation` n
	       LEFT JOIN `'._DB_PREFIX_.'customer` c ON c.id_customer=n.id_customer
	       WHERE  n.id_status>=1 ORDER BY n.id_ns_quotation  DESC 
	      
		   ';
		   
	 $result =Db::getInstance()->executeS($sql);
	 
	 return $result;	
	
	
	}
	
	
	public function getQuotationDetails($id)
	{
	   $id_lang=$this->context->language->id;
	
	 $sql='SELECT * FROM `'._DB_PREFIX_.'ns_quotation` n 	      
	       LEFT JOIN `'._DB_PREFIX_.'quotation_product`  qp ON qp.id_ns_quotation=n.id_ns_quotation
		   LEFT JOIN `'._DB_PREFIX_.'product`  p ON p.id_product=qp.id_product
		   LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON pl.id_product=p.id_product
            LEFT JOIN '._DB_PREFIX_.'image i ON (i.id_product = pl.id_product)'.
			Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
		   LEFT JOIN '._DB_PREFIX_.'image_lang il ON (il.id_image = image_shop.id_image)		   
		   WHERE  qp.id_ns_quotation='.(int)$id.'
		    AND  il.id_lang='.(int)$id_lang.'
			AND  pl.id_lang='.(int)$id_lang;
		   
	 $result =Db::getInstance()->executeS($sql);
	 
	 return $result;	
	
	}
	
	
	public function getSearchedProducts()
    {
        $products = array();
		$id_shop=(int)Context::getContext()->shop->id;
        if (Tools::getValue('nka_searched_product') != '' && Tools::isSubmit('searchProductNka')) {
            $searched_product = Tools::getValue('nka_searched_product');
            $dbquery = new DbQuery();
            $dbquery->select('p.`id_product` AS `id_product`, pl.`name` AS `name`,  p.`price`');
            $dbquery->from('product', 'p');
            $dbquery->leftJoin('product_lang', 'pl', 'p.id_product = pl.id_product
            AND pl.id_lang = '.(int)$this->context->employee->id_lang.' AND pl.id_shop='.$id_shop);
            $dbquery->where('pl.`name` LIKE \'%'.pSQL($searched_product).'%\' ');

            $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($dbquery->build());
        }

        return $products;
    }
	
	
	/* get customer quotes*/
  
  public function getCustomerQuotes($id_customer)
	{
	
	 $sql='SELECT * FROM `'._DB_PREFIX_.'ns_quotation` n	       
		   WHERE  n.id_customer='.(int)$id_customer.'
		   AND  n.id_status=0'; 
		   
		   
	 $result =Db::getInstance()->getRow($sql);
	 
	 return $result['id_ns_quotation'];	
	
	}
	
	
	
	public function getTotalProducts($id_ns_quotation){
	
	$totals=$this->getQuotationDetails($id_ns_quotation);
	$total_products=0; 
	
	foreach($totals as $total){
	
	$total_products+=$total['price']*$total['product_qty'];
	
	}
	
	  return $total_products;
	
	
	}
	
	
	  //for employee email 
  
  	   public function displayForm()
  {
    // Get default language
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
    
    // Init Fields form array
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('Settings'),
			'icon' => 'icon-cogs'
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('Employee Email:'),
                'name' => 'QUOTATION_EMPLOYEE_EMAIL',
                'size' => 20,
				'desc' => $this->l('Where to receive notifications'),
                'required' => true
            ),
        array(
                'type' => 'text',
                'label' => $this->l('Admin Directory:'),
                'name' => 'QUOTATION_ADMIN_DIR',
                'size' => 20,
				'desc' => $this->l('copy and paste the admin directory.'),
                'required' => true
            )				
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
    $helper->fields_value['QUOTATION_EMPLOYEE_EMAIL'] = Configuration::get('QUOTATION_EMPLOYEE_EMAIL');
	$helper->fields_value['QUOTATION_ADMIN_DIR'] = Configuration::get('QUOTATION_ADMIN_DIR');
	
	
	
     
    return $helper->generateForm($fields_form);
    } 
  
  
     /* verify id_product to increase qty*/
	
	public function verifyIdProduct($id_product,$id_ns_quotation)
	{
	
	 $sql='SELECT * FROM `'._DB_PREFIX_.'quotation_product` qp	       
		   WHERE  qp.id_product='.(int)$id_product.' 
		     AND qp.id_ns_quotation='.(int)$id_ns_quotation; 
			
		   
	 $result =Db::getInstance()->getRow($sql);
	 
	 return $result;
	
	}
	
	/*delete product*/
	public function deleteProduct($id_product,$id_ns_quotation)
	{
	  $output='';
	  if(Tools::isSubmit('deleteproduct') && $id_ns_quotation!=''	&& $id_product!='')
	  {
	  
	  $sql= 'DELETE FROM `'._DB_PREFIX_.'quotation_product` 
	            WHERE id_product='.$id_product.'			    
			    AND id_ns_quotation='.$id_ns_quotation;					
                    
					Db::getInstance()->execute($sql); 
					
		 $output=$this->displayConfirmation($this->l('Product successfully Removed from Quote.'));
		 
      }	
      
       return $output;	  
	
	}
	
	
		/*sending email to admin*/
	
   public static function emailAdmin($email,$id_ns_quotation)
    {
	    
         $context=Context::getContext();
         $nka_mod=new NsQuotation();		 
	    $username=$nka_mod->l('Dear Admin');		                  
       
          $message_title =$nka_mod->l('New Quote');
          $message_subject =$nka_mod->l('Notification about new Quote');
		  $validate_text_one=$nka_mod->l(' A customer has sent you a new Quote. See details by this link: ');
		  $validate_text_two=$nka_mod->l(' View Quote!');
		  
          $base_dir_nka=Tools::getHttpHost(true).__PS_BASE_URI__;	
          $verification_link=$base_dir_nka.Configuration::get('QUOTATION_ADMIN_DIR');		  
		   
          $token=Tools::getAdminTokenLite('AdminModules');          
           //if (defined('_PS_ADMIN_DIR_'))		  
		 
		  $verification_link.='/index.php?controller=AdminModules&configure=nsquotation&savensquotation';	
		  $verification_link.='&id_ns_quotation='.(int)$id_ns_quotation.'&viewns_quotation&token='.$token;
		  
		 // if(!empty($email) && !empty($id_ns_quotation))
		 // {       	  
           
              $html='';              
              $date = date('d/m/y');
			  
               
              
          
              return  Mail::Send(
                  $context->language->id,
                  'newquotenotification_verif',
                  Mail::l($message_title, $context->language->id),
                  array(
                        				  
						'{html}' => $html,                        
                        '{date}' => $date, 
						'{base_dir_nka}'=>$base_dir_nka,						
                        '{message_title}'=>$message_title, 		
                        '{message_subject}'=>$message_subject, 
						'{valide_link}'=>$verification_link,
						'{validate_text_one}'=>$validate_text_one,
						'{validate_text_two}'=>$validate_text_two,			
						'{user_name}'=>$username,
                        					

                   ),
                  pSQL($email),
                  null,
                  null,
                  null,
                  null,
                  null,
                  dirname(__FILE__).'/mails/',
                  false,
                  $context->shop->id
              );
        
        ///}
	
	
	}		 		
	public static function emailCustomer($email,$id_ns_quotation,$id_customer)    {	    
	$context=Context::getContext();   
	$nka_mod=new NsQuotation();	  
	$username=$nka_mod->l('Dear Customer');   
	$message_title =$nka_mod->l('New Quote');     
	$message_subject =$nka_mod->l('Notification about your Quote');	
	$validate_text_one=$nka_mod->l(' Your Quote has been validated! Please see details by this link: ');		
	$validate_text_two=$nka_mod->l(' View Quote Details!');		
	$verification_link='';		
	$verification_link.=$context->link->getModuleLink('nsquotation', 'my_quote');		
	$verification_link.='?nka=products&id_ns_quotation='.(int)$id_ns_quotation.'&id_customer='.$id_customer;		  	          $html='';                 $date = date('d/m/y');			  $base_dir_nka=Tools::getHttpHost(true).__PS_BASE_URI__;                               return  Mail::Send(                  $context->language->id,                  'newquotenotification_verif',                  Mail::l($message_title, $context->language->id),                  array(                        				  						'{html}' => $html,                                                '{date}' => $date, 						'{base_dir_nka}'=>$base_dir_nka,						                        '{message_title}'=>$message_title, 		                        '{message_subject}'=>$message_subject, 						'{valide_link}'=>$verification_link,						'{validate_text_one}'=>$validate_text_one,						'{validate_text_two}'=>$validate_text_two,						'{user_name}'=>$username,  					                   ),                  pSQL($email),                  null,                  null,                  null,                  null,                  null,                  dirname(__FILE__).'/mails/',                  false,                  $context->shop->id              );	}   
	
	
}
