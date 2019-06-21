<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
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
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class QuotationProduct extends ObjectModel
{
	public $id;	

	public $id_shop;	

	/** @var integer Customer currency ID */
	public $id_currency;

	/** @var integer Customer ID */
	public $id_customer;

   /** @var integer Customer ID */
	public $id_status;	

	/** @var integer Language ID */
	public $id_lang;	

	/** @var string Object creation date */
	public $date_add;
	
	/** @var string Object last modification date */
	public $date_upd;	

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'ns_quotation',
		'primary' => 'id_ns_quotation',
		'fields' => array(
           	
			'id_shop' => 				array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),			
			'id_currency' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_customer' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_status' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),				
			'id_lang' => 				array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),			
			'date_add' => 				array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
			'date_upd' => 				array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
		),
	);
	
	
	
	public static function loadById($id_ns_quotation){
        $result = Db::getInstance()->getRow('
            SELECT *
            FROM `'._DB_PREFIX_.'ns_quotation` n
            WHERE n.`id_ns_quotation` = '.(int)$id_ns_quotation
        );
        
        return new QuotationProduct($result['id_ns_quotation']);
    }
	
	
	/* get customer name*/
	
	public static function getCustomerName($id_customer){
        $result = Db::getInstance()->getRow('
            SELECT c.`firstname`, c.`lastname`
            FROM `'._DB_PREFIX_.'customer` c
            WHERE c.`id_customer` = '.(int)$id_customer
        );
        
        return $result['firstname'].' '.$result['lastname'];
    }
	
	
	/* get customer email*/
	
	public static function getCustomerEmail($id_customer){
        $result = Db::getInstance()->getRow('
            SELECT c.`email`
            FROM `'._DB_PREFIX_.'customer` c
            WHERE c.`id_customer` = '.(int)$id_customer
        );
        
        return $result['email'];
    }
	
	/* get customer phone*/
	
	public static function getCustomerPhone($id_customer){
        $result = Db::getInstance()->getRow('
            SELECT c.`phone`
            FROM `'._DB_PREFIX_.'address` c
            WHERE c.`id_customer` = '.(int)$id_customer
        );
        
        return $result['phone'];
    }
	
	
	public static function getQtyId($id,$id_ns_quotation){
	
	$result = Db::getInstance()->getRow('
            SELECT  sample.`product_qty`
            FROM `'._DB_PREFIX_.'quotation_product` sample
            WHERE sample.`id_product` = '.(int)$id.'
			AND  sample.`id_ns_quotation`='.(int)$id_ns_quotation
        );
        
        return $result['product_qty'];	
	
	}
	
	
	public static function getCover($id_product){
	
	        $result=Image::getCover($id_product);
			
			return $result['id_image'];
			
			
	}		
	
	
	
}
