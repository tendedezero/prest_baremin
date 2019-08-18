<?php
/**
* @author      R Paterson
* @copyright   2019 R Paterson
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

use PrestaShop\PrestaShop\Adapter\ObjectPresenter;

if (!defined('_PS_VERSION_')) {
    exit;
}

class NDCVatDisplay extends Module
{
     public function __construct()
    {
        $this->name = 'NDCVatDisplay';
        $this->tab = 'front_office_features';
        $this->version = '1.0.6';
        $this->author = 'RPaterson';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('NDC Vat  RRP Display');
        $this->description = $this->l('Displays VAT  RRP.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }
    
   public function install()
{
    if (!parent::install() || !$this->_installSql()
        //Pour les hooks suivants regarder le fichier src\PrestaShopBundle\Resources\views\Admin\Product\form.html.twig
        || ! $this->registerHook('displayAdminProductsExtra')
        || ! $this->registerHook('displayNav2')
        || ! $this->registerHook('displayProductPriceBlock')
    ) {
        return false;
    }

    return true;
}

public function uninstall()
{
    return parent::uninstall() && $this->_unInstallSql();
}
    /**
     * Modifications sql du module
     * @return boolean
     */
    protected function _installSql() {
        $sqlInstall = "ALTER TABLE " . _DB_PREFIX_ . "product "
            . "ADD rrp_price_ex decimal(20,6) NULL";
        $returnSql = Db::getInstance()->execute($sqlInstall);
        return $returnSql;
    }
    /**
     * Uninstall RRP Fields
     * @return boolean
     */
    protected function _unInstallSql() {
        $sqlInstall = "ALTER TABLE " . _DB_PREFIX_ . "product "
            . "DROP rrp_price_ex";
        $returnSql = Db::getInstance()->execute($sqlInstall);
        return $returnSql;
    }

public function getContent()
{
    $output = null;
    return $output.$this->displayForm();
}
    public function hookDisplayNav2($params)
    {
        if (Configuration::isCatalogMode())  {
            return false;
        }
        return $this->display(__FILE__, 'views/templates/hook/front/VAT_DisplayMod.tpl');
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if (Configuration::isCatalogMode())  {
            return false;
        }
    }
    /**
     * Params for hook
     * @param type $params
     * @return type
     */
    public function hookDisplayAdminProductsExtra($params) {
        $product = new Product($params['id_product']);
        $this->context->smarty->assign(array(
                'rrp_price_ex' => $product->rrp_price_ex,
                'default_language' => $this->context->employee->id_lang,
            )
        );
        return $this->display(__FILE__, 'views/templates/hook/RRP_Display.tpl');
    }

}