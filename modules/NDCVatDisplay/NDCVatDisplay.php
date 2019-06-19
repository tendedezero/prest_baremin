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
        $this->version = '1.0.0';
        $this->author = 'RPaterson';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('NDC Vat Display');
        $this->description = $this->l('Displays VAT.');
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
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('VATDISPLAYMODE'),
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('VATDISPLAYMODE'),
                'name' => 'VATDISPLAYMODE',
                'lang' => true,
                'size' => 20,
                'required' => true
            ),
        ),
        'submit' => array(
            'title' => $this->l('Save'),
            'class' => 'btn btn-default pull-right'
        )
    );

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

    // < load current value >
    $helper->fields_value['VATDISPLAYMODE'] = Configuration::get('VATDISPLAYMODE');

    return $helper->generateForm($fields_form);
}

    public function getContent()
{
    $output = null;


    // < here we check if the form is submited for this module >
    if (Tools::isSubmit('submit'.$this->name)) {
        $NDCVatDisplay = strval(Tools::getValue('VATDISPLAYMODE'));

        // < make some validation, check if we have something in the input >
        if (!isset($NDCVatDisplay))
            $output .= $this->displayError($this->l('Please insert something in this field.'));
        else
        {
            // < this will update the value of the Configuration variable >
            Configuration::updateValue('$VATDISPLAYMODE', $NDCVatDisplay);


            // < this will display the confirmation message >
            $output .= $this->displayConfirmation($this->l('Vat Setting Updated'));
        }
    }
    return $output.$this->displayForm();
}



public function getWidgetVariables($hookName, array $configuration)
    {

    $link = $this->context->link;
   $vatmode=$this->vatmode;

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