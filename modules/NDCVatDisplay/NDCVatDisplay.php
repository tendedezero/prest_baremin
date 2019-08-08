<?php
/**
* @author      R Paterson
* @copyright   2019 R Paterson
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\ObjectPresenter;

if (!defined('_PS_VERSION_')) {
    exit;
}

class NDCVatDisplay extends Module implements WidgetInterface
{
     public function __construct()
    {
        $this->name = 'NDCVatDisplay';
        $this->tab = 'front_office_features';
        $this->version = '1.0.3';
        $this->author = 'RPaterson';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('NDC Vat & RRP Display');
        $this->description = $this->l('Displays VAT & RRP.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:NDCVatDisplay/NDCVatDisplay.tpl';
    }
    
   public function install()
{
    if (Shop::isFeatureActive())
        Shop::setContext(Shop::CONTEXT_ALL);

    return parent::install() &&
        $this->registerHook('displayNav2');
}

public function uninstall()
{
    if (!parent::uninstall())
        return false;
    return true;
}
    
    public function displayForm()
{
    // < init fields for form array >
 
    // < load helperForm >
    $helper = new HelperForm();

    // < module, token and currentIndex >
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

    // < title and toolbar >
    $helper->title = $this->displayName;
    $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    

 
}

    public function getContent()
{
    $output = null;


    return $output.$this->displayForm();
}



public function getWidgetVariables($hookName, array $configuration)
    {


    $link = $this->context->link;
   $vatmode='1';

     return array(
        'vlink' => $link,
        'vatmode' => $vatmode,
    );
 }

    public function renderWidget($hookName, array $configuration)
    {
        if (Configuration::isCatalogMode())  {
            return false;
        }

        
$this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

        return $this->fetch($this->templateFile);
    }
}