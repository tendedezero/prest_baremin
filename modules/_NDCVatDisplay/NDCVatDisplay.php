<?php
/**
* @author      R Paterson
* @copyright   2019 R Paterson
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

use PrestaShop\PrestaShop\Adapter\ObjectPresenter;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;

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
            . "ADD rrp decimal(20,6) NULL";
        $returnSql = Db::getInstance()->execute($sqlInstall);
        return $returnSql;
    }
    /**
     * Uninstall RRP Fields
     * @return boolean
     */
    protected function _unInstallSql() {
        $sqlInstall = "ALTER TABLE " . _DB_PREFIX_ . "product "
            . "DROP rrp";
        $returnSql = Db::getInstance()->execute($sqlInstall);
        return $returnSql;
    }

public function getContent()
{
    $output = null;
    return $output.$this->displayForm();
}
    public function hookDisplayNav2()
    {
        if (Configuration::isCatalogMode())  {
            return false;
        }

            $vatmode = null;
            $link = $this->context->link;
            $cookieKey = 'VATMODE';
            if (!isset($_COOKIE['VATMODE'])) {
                $this->context->cookie->__set($cookieKey, "true");
                $vatmode = 'true';
            }
            else
            {
                $vatmode = $_COOKIE['VATMODE'];
            }

        $this->context->smarty->assign(array(
            'vlink' => $link,
            'vatmode' => $vatmode,
        ));

        return $this->display(__FILE__, 'views/templates/hook/Front/VAT_DisplayMod.tpl');
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if (Configuration::isCatalogMode())  {
            return false;
        }
        if ($params['type'] !='rrp') {
            return;
        };

        $product = $params['product'];

        $rrp =  $product->rrp;

        $rrp_inc_vat = ($rrp * 1.2);
        $this->context->smarty->assign(array(
                'rrp' => $rrp,
                'rrp_inc_vat' => $rrp_inc_vat,
            )
        );

        return $this->display(__FILE__, 'views/templates/hook/Front/RRP_Display.tpl');
    }
    /**
     * Params for hook
     * @param type $params
     * @return type
     */
    public function hookDisplayAdminProductsExtra($params) {
        $product = new Product($params['id_product']);
        $this->context->smarty->assign(array(
                'rrp' => $product->rrp,
                'default_language' => $this->context->employee->id_lang,
            )
        );

    }

}