<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class AngarFastconfig extends Module
{
    protected $default_language;
    protected $languages;

    public function __construct()
    {
        $this->name = 'angarfastconfig';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->bootstrap = true;
        $this->secure_key = Tools::encrypt($this->name);
        $this->default_language = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
        $this->languages = Language::getLanguages();
        $this->author = 'AngarThemes';
        parent::__construct();
        $this->displayName = $this->l('AT - 1. Fast configuration of modules');
        $this->description = $this->l('Start configuration here! Configure the main elements of your theme.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('displayBackOfficeHeader') ||
            !$this->registerHook('displayHeader') ||
            !Configuration::updateValue('PS_SET_DISPLAY_SUBCATEGORIES', 1) ||
            !Configuration::updateValue('PS_QUICK_VIEW', 1)
        ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') != $this->name) {
            return;
        }

        $this->context->controller->addCSS($this->_path.'views/css/angarfastconfig.css');
    }

    public function hookdisplayHeader()
    {
        $this->context->smarty->assign(array(
            'display_quickview' => (int)Configuration::get('PS_QUICK_VIEW'),
            'display_subcategories' => (int)Configuration::get('PS_SET_DISPLAY_SUBCATEGORIES'),
            'display_list' => (int)Configuration::get('PS_GRID_PRODUCT')
        ));
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitModule')) {
            Configuration::updateValue('PS_QUICK_VIEW', (int)Tools::getValue('quick_view'));
            Configuration::updateValue('PS_GRID_PRODUCT', (int)Tools::getValue('grid_list'));
            Configuration::updateValue('PS_SET_DISPLAY_SUBCATEGORIES', (int)Tools::getValue('sub_cat'));
            foreach ($this->getConfigurableModules() as $module) {
                if (!isset($module['is_module']) || !$module['is_module'] || !Validate::isModuleName($module['name']) || !Tools::isSubmit($module['name'])) {
                    continue;
                }

                $module_instance = Module::getInstanceByName($module['name']);
                if ($module_instance === false || !is_object($module_instance)) {
                    continue;
                }

                $is_installed = (int)Validate::isLoadedObject($module_instance);
                if ($is_installed) {
                    if (($active = (int)Tools::getValue($module['name'])) == $module_instance->active) {
                        continue;
                    }

                    if ($active) {
                        $module_instance->enable();
                    } else {
                        $module_instance->disable();
                    }
                } elseif ((int)Tools::getValue($module['name'])) {
                    $module_instance->install();
                }
            }
        }

        $html = $this->renderConfigurationForm();

        return $html;
    }

    public function renderConfigurationForm()
    {
        $inputs = array();

        foreach ($this->getConfigurableModules() as $module) {
            $desc = '';

            if (isset($module['is_module']) && $module['is_module']) {
                $module_instance = Module::getInstanceByName($module['name']);
                if (Validate::isLoadedObject($module_instance) && method_exists($module_instance, 'getContent')) {
                    $desc = '<a class="btn btn-default" target="_blank" href="'.$this->context->link->getAdminLink('AdminModules', true).'&configure='.urlencode($module_instance->name).'&tab_module='.$module_instance->tab.'&module_name='.urlencode($module_instance->name).'">'.$this->l('Configure').' <i class="icon-external-link"></i></a>';
                }
            }
            if (!$desc && isset($module['desc']) && $module['desc']) {
                $desc = $module['desc'];
            }

            $inputs[] = array(
                'type' => 'switch',
                'label' => $module['label'],
                'name' => $module['name'],
                'desc' => $desc,
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                ),
            );
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'description' => $this->l('Info: Please read the documentation for more informations: https://angarthemes.eu/en/content/21-configuration-of-modules').'<br/>'.
                $this->l('Info: Full documentation: https://angarthemes.eu/en/content/category/5-documentation-prestashop-17').'<br/>'.
                $this->l('Info: Configuration of selected modules will be opened in new browser tab.').'<br/>'.
                $this->l('Info: If product comments module doesn\'\t work properly check this: https://angarthemes.eu/en/content/31-product-comments'),
                'input' => $inputs,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    protected function getConfigurableModules()
    {
        return array(

            array(
                'label' => $this->l('AT - Layout and colors live configurator'),
                'name' => 'angarthemeconfigurator',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarthemeconfigurator')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Main menu'),
                'name' => 'ps_mainmenu',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_mainmenu')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Image slider'),
                'name' => 'angarslider',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarslider')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Banners'),
                'name' => 'angarbanners',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarbanners')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Featured categories'),
                'name' => 'angarhomecat',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarhomecat')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Category block in left column'),
                'name' => 'ps_categorytree',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_categorytree')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Products filter in left column'),
                'name' => 'ps_facetedsearch',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_facetedsearch')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Brand list in left column'),
                'name' => 'ps_brandlist',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_brandlist')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Supplier list in left column'),
                'name' => 'ps_supplierlist',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_supplierlist')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - New products block'),
                'name' => 'angarnewproducts',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarnewproducts')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Featured products'),
                'name' => 'angarfeatured',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarfeatured')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Bestsellers'),
                'name' => 'angarbestsellers',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarbestsellers')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Specials block'),
                'name' => 'angarspecials',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarspecials')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - About our store'),
                'name' => 'angarcmsdesc',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarcmsdesc')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Parallax banner'),
                'name' => 'angarparallax',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarparallax')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Products from category'),
                'name' => 'angarcatproduct',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarcatproduct')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Featured manufacturers'),
                'name' => 'angarmanufacturer',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarmanufacturer')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Icons before footer (Free shipping, Product return, Support 24/7, Safe Payment)'),
                'name' => 'angarcmsinfo',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarcmsinfo')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Link List - Links in footer and \"Information\" block in left column (See in the documentation how to add a block to the left column)'),
                'name' => 'ps_linklist',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_linklist')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Contact top and footer'),
                'name' => 'angarcontact',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarcontact')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Newsletter subscription in footer'),
                'name' => 'ps_emailsubscription',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_emailsubscription')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Social media follow links in footer (Twitter, Facebook, Youtube, Google + etc.)'),
                'name' => 'ps_socialfollow',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_socialfollow')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Facebook likebox tab'),
                'name' => 'angarfacebook',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarfacebook')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Display quick view window on homepage and category pages'),
                'name' => 'quick_view',
                'value' => (int)Tools::getValue('PS_QUICK_VIEW', Configuration::get('PS_QUICK_VIEW'))
            ),

            array(
                'label' => $this->l('Display categories as a list of products instead of the default grid-based display'),
                'name' => 'grid_list',
                'value' => (int)Configuration::get('PS_GRID_PRODUCT'),
                'desc' => $this->l('Works only for first-time users. This setting is overridden by the user\'s choice as soon as the user cookie is set.'),
            ),

            array(
                'label' => $this->l('Display subcategories on category page'),
                'name' => 'sub_cat',
                'value' => (int)Tools::getValue('PS_SET_DISPLAY_SUBCATEGORIES', Configuration::get('PS_SET_DISPLAY_SUBCATEGORIES')),
            ),

            array(
                'label' => $this->l('Social media share buttons (facebook, twitter, google +, pinterest) on the products page'),
                'name' => 'ps_sharebuttons',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_sharebuttons')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Product and cart customer reassurance'),
                'name' => 'blockreassurance',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('blockreassurance')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Products in the same category'),
                'name' => 'ps_categoryproducts',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_categoryproducts')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Product comments'),
                'name' => 'productcomments',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('productcomments')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('AT - Button scroll to top'),
                'name' => 'angarscrolltop',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarscrolltop')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

        );
    }

    public function getConfigFieldsValues()
    {
        $values = array();
        foreach ($this->getConfigurableModules() as $module) {
            $values[$module['name']] = $module['value'];
        }

        return $values;
    }
}
