<?php
/**
 * Override Class ProductCore
 */
class Product extends ProductCore {
	public $rrp;	 
	public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null){
	 
			self::$definition['fields']['rrp'] = [
	            'type' => self::TYPE_FLOAT,
	            'required' => false, 'validate' => 'isPrice'
	        ];
	         parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
	}
}