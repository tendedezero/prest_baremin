<?php
if (!defined('_PS_VERSION_'))
 exit; 


class nsquotationmy_quoteModuleFrontController extends ModuleFrontController
{      
	  public function initContent()
	  {	  
	    $this->display_column_left =false;
        $this->display_column_right =false;						
		parent::initContent();							
		$ps_version=_PS_VERSION_;		
		$mod_tpl_dir='module:'.$this->module->name.'/views/templates/front';
		$id_lang=$this->context->language->id;			
		$id_customer=$this->context->customer->id;	
        $id_product=Tools::getValue('id_product');			
        $product=new Product($id_product, true, $id_lang,$this->context->shop->id);			
		$id_ns_quotation=Tools::getValue('id_ns_quotation');
		$quotation=new QuotationProduct($id_ns_quotation);	  
	    $id_status=$quotation->id_status;

        $currency = $this->context->currency;

		
		$this->context->smarty->assign(array(
		'logged' => $this->context->customer->isLogged(),
		'customerName' => ($this->context->customer->logged ? $this->context->customer->firstname.' '.$this->context->customer->lastname : false),
		'firstName' => ($this->context->customer->logged ? $this->context->customer->firstname : false),
		'lastName' => ($this->context->customer->logged ? $this->context->customer->lastname : false),       
        'id_customer'=> ($this->context->customer->logged ? $this->context->customer->id: false),
        'message'=>$this->addToQuote(),  
        'add_to_quote_link' =>$this->context->link->getModuleLink('nsquotation', 'my_quote'),
        'product'=>$product,
		'quotation'=>$quotation,
		'id_ns_quotation'=>$id_ns_quotation,
		'id_product'=>$id_product,
		'output'=>$this->confirmQuote($id_ns_quotation,$id_status),
        'customer_quotes'=>$this->getCustomerQuotes($id_customer),
        'quote_products'=>$this->quoteGetProducts($id_ns_quotation),    
		//'mod_tpl_dir'=>$this->getTemplatePath($mod_tpl_dir),
        'currency'=>$currency,
        'delete_product_msg'=>$this->deleteProduct($id_product,$id_ns_quotation),
		'delete_quote_msg'=>$this->deleteQuote($id_ns_quotation),
        'isProducts'=>$this->isProducts($id_ns_quotation),
        'mod_tpl_dir'=>$mod_tpl_dir,
        'total_products'=>$this->getTotalProducts($id_ns_quotation),	
		'ps_version'=>$ps_version,
        'last_id'=>$this->getLastId($id_customer),		
        
	));			
	
	    if($ps_version>=1.7)
		{
		  $template='module:'.$this->module->name.'/views/templates/front/my_quote.tpl';	
		   $this->setTemplate($template); 		
		} 
		
		if($ps_version<1.7) 		     
		{ 
		 $this->setTemplate('my_quote_version_16.tpl');
		
		}   	  
		
    }
	 
	
   public function addToQuote(){  

          $context=Context::getContext();  

          $message='';
          
		 $product_qty=(int)Tools::getValue('product_qty'); 
         $id_product = Tools::getValue('id_product');
		 $id_ns_quotation = Tools::getValue('id_ns_quotation');
		 $id_customer=$this->context->customer->id;	
		 $id_lang=$this->context->language->id;
		 $id_shop=$this->context->shop->id;	
		 $date_add=date('Y-m-d H:i:s');
	/* submit a new Quote */	  
 if(Tools::isSubmit('SubmitAddNewQuote')  && $id_product!='' && $id_customer!='' && $id_ns_quotation=='')
	    		
		{
				 
		$id_currency=$this->context->currency->id;
        $QuotationProduct=new QuotationProduct();    
        $QuotationProduct->product_qty=$product_qty;
		$QuotationProduct->id_shop=$id_shop;
		$QuotationProduct->id_currency=$id_currency;
        $QuotationProduct->id_product = $id_product; 
        $QuotationProduct->id_customer=$id_customer;       
		$QuotationProduct->id_lang=$id_lang;
		$QuotationProduct->date_add=date('Y-m-d H:i:s');
		$QuotationProduct->date_upd=date('Y-m-d H:i:s');		
		
		if(!$QuotationProduct->add()){
                         
	     $message=$this->displayError($this->l('Can not add this product to quote.'));            
			
			
        } else {
            
			 $id_ns_quotation=$QuotationProduct->id;                             
               //$date_add=date('Y-m-d H:i:s'); 
					  
	      $sql='INSERT INTO `'._DB_PREFIX_.'quotation_product` 
	          (`id_ns_quotation`, `id_product`, `id_shop`,`product_qty`,`date_add`)
	          VALUES('.(int)$id_ns_quotation.','.(int)$id_product.','.(int)$id_shop.','.(int)$product_qty.',\''.pSQL($date_add).'\')'; 	
              Db::getInstance()->execute($sql); 	             
			 $message=1;            
		//$output=$this->displayConfirmation($this->l('Your banner has been successfully added.'));
			
        }
		
        }	

     /* submit add new product */

      if(Tools::isSubmit('SubmitAddNewProduct')  && $id_product!='' && $id_customer!='' && $id_ns_quotation!='')
	    		
		{	
          
		if($this->verifyIdProduct($id_product,$id_ns_quotation)['id_product']==Tools::getValue('id_product')){
		  
		  $product_qty_new=$product_qty+$this->verifyIdProduct($id_product,$id_ns_quotation)['product_qty'];
		  
		  $sql='UPDATE `'._DB_PREFIX_.'quotation_product` 
	               SET  `product_qty`='.(int)$product_qty_new.'
				   WHERE `id_ns_quotation`='.(int)$id_ns_quotation.'
				   AND  `id_product`='. (int)$id_product			  
			       ; 	
              Db::getInstance()->execute($sql);
		  
		  
		  $message=1;
		  
		  }			
			else{
	      $sql='INSERT INTO `'._DB_PREFIX_.'quotation_product` 
	          (`id_ns_quotation`, `id_product`, `id_shop`,`product_qty`,`date_add`)
	          VALUES('.(int)$id_ns_quotation.','.(int)$id_product.','.(int)$id_shop.','.(int)$product_qty.',\''.pSQL($date_add).'\')'; 	
              Db::getInstance()->execute($sql); 	             
			 $message=1;            
			}
			
        } 	 
	         
	
    if(Tools::isSubmit('SubmitUpdateQuoteProduct')  && $id_product!='' && $id_customer!='' && $id_ns_quotation!='')
	    {	
            
			  $sql='UPDATE `'._DB_PREFIX_.'quotation_product` 
	               SET  `product_qty`='.(int)$product_qty.'
				   WHERE `id_ns_quotation`='.(int)$id_ns_quotation.'
				   AND  `id_product`='. (int)$id_product			  
			       ; 	
              Db::getInstance()->execute($sql);
			  
			  

        }		
		
		
		
		
           return $message;
  }
  
  
  /* get customer quotes*/
  
  public function getCustomerQuotes($id_customer)
	{
	
	 $sql='SELECT * FROM `'._DB_PREFIX_.'ns_quotation` n	       
		   WHERE  n.id_customer='.(int)$id_customer.'		      
			ORDER BY n.id_ns_quotation DESC 
		   ';
		   
	 $result =Db::getInstance()->executeS($sql);
	 
	 return $result;	
	
	}
	
	
	public function quoteGetProducts($id)
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
	
	
	
	public function confirmQuote($id_ns_quotation,$id_status){
	
	 $output='';
	 $context=Context::getContext();
     $id_cart=(int)$context->cookie->id_cart;
	 $products=$this->quoteGetProducts($id_ns_quotation);
	 
	 
	 if(Tools::isSubmit('submitSendQuote') && Tools::getValue('id_ns_quotation')!=''	&& $id_status!='')
	  {
	     if($id_status==0) $id_status=$id_status+1; 
        
		$sql= 'UPDATE`'._DB_PREFIX_.'ns_quotation` SET `id_status`='.$id_status.'			    
			   WHERE `id_ns_quotation`='.$id_ns_quotation;					
                    Db::getInstance()->execute($sql);   
	   
	   $output=$this->module->l('Your quote has been successfully Sent.');
	   $email=Configuration::get('QUOTATION_EMPLOYEE_EMAIL');
	   
	    NsQuotation::emailAdmin($email,$id_ns_quotation);
	   
	 
	 
	 }
	 
	 
	
	if(Tools::isSubmit('submitConfirmQuote') && Tools::getValue('id_ns_quotation')!=''	&& $id_status!='')
	  {
	     if($id_status==2) $id_status=$id_status+1; 
        
		$sql= 'UPDATE`'._DB_PREFIX_.'ns_quotation` SET `id_status`='.$id_status.'			    
			   WHERE `id_ns_quotation`='.$id_ns_quotation;					
                    Db::getInstance()->execute($sql);   
	   
	   $output=$this->module->l('Your quote has been successfully Validated.');
	   
	   
	  
	   $cart=new Cart($id_cart);
	   $cart->id_currency=$this->context->currency->id;
       $cart->id_lang=$this->context->language->id;  	   
       
	   
	   
	   foreach($products as $prod){
       $cart->updateQty($prod['product_qty'], $prod['id_product'], null, false);
	   }
	   
	   
	   Tools::redirect('index.php?controller=order');
	   
      } 
	
	
	   return $output;
	
	}
	
	
	public function deleteProduct($id_product,$id_ns_quotation)
	{
	  $output='';
	  if(Tools::getValue('nka')=='deleteproduct' && $id_ns_quotation!=''	&& $id_product!='')
	  {
	  
	  $sql= 'DELETE FROM `'._DB_PREFIX_.'quotation_product` 
	            WHERE id_product='.$id_product.'			    
			    AND id_ns_quotation='.$id_ns_quotation;					
                    
					Db::getInstance()->execute($sql); 
					
		 $output=$this->module->l('Product successfully Removed from Quote.');
      }	
      
       return $output;	  
	
	}
	
	
	public function deleteQuote($id_ns_quotation)
	{
	  $output='';
	  if(Tools::getValue('nka')=='deletequote' && $id_ns_quotation!='')
	  {
	  
	         $sql= 'DELETE FROM `'._DB_PREFIX_.'quotation_product` 
	            WHERE id_ns_quotation='.$id_ns_quotation;			  				
                    
					Db::getInstance()->execute($sql); 
					
		$quote=new QuotationProduct($id_ns_quotation);
		 
		 $quote->delete();
					
		 $output=$this->module->l('Quote successfully Removed from list.');
      }	
      
       return $output;	  
	
	}
	
	
	
	
	public function isProducts($id_ns_quotation){
	   
	    $sql= 'SELECT * FROM `'._DB_PREFIX_.'quotation_product` q	            			    
			    WHERE q.`id_ns_quotation`='.(int)$id_ns_quotation;					
                    
		$result=Db::getInstance()->getRow($sql);
		
		return $result['id_product'];
	
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
	
	/* get total products*/
	
	public function getTotalProducts($id_ns_quotation){
	
	$totals=$this->quoteGetProducts($id_ns_quotation);
	$total_products=0; 
	
	foreach($totals as $total){
	
	$total_products+=$total['price']*$total['product_qty'];
	
	}
	
	  return $total_products;
	
	
	}
	
	
	public function getLastId($id_customer){
	
	$sql='SELECT MAX(id_ns_quotation) as id_ns_quotation FROM `'._DB_PREFIX_.'ns_quotation` 	       
		   WHERE  id_customer='.(int)$id_customer;   
			
		   
	 $result =Db::getInstance()->getRow($sql);
	 
	 return $result['id_ns_quotation'];
	
	
	}
	
	


}


