<?php
/**
 * Override Class ProductCore
 */
class Product extends ProductCore
{
    /*
    * module: NDCVatDisplay
    * date: 2019-08-18 21:08:45
    * version: 1.0.6
    */
    public $rrp;
    /*
    * module: NDCVatDisplay
    * date: 2019-08-18 21:08:45
    * version: 1.0.6
    */
    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null)
    {
        self::$definition['fields']['rrp'] = [
            'type' => self::TYPE_FLOAT,
            'required' => false, 'validate' => 'isPrice'
        ];
        parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
    }
}