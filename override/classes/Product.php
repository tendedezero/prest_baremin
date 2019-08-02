class Product extends ProductCore
{
 
    public $rrp;
  
    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null)
    {
        self::$definition['fields']['rrp'] = array('type' => self::TYPE_FLOAT, 'validate' => 'isCleanHtml');
        parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
    }
 
}