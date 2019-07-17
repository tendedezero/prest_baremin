<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class AngarManufacturer extends Module
{
    public function __construct()
    {
        $this->name = 'angarmanufacturer';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'angarthemes';
        $this->spacer_size ='5';
        $this->need_instance = 0;


        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('AT - Featured manufacturers');
        $this->description = $this->l('Displays selected manufacturers on the home page');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:angarmanufacturer/views/templates/hook/at_manufacturer.tpl';
    }

    public function install()
    {
        return parent::install()
            && Configuration::updateValue('AT_MANUFACTURER_ID', implode(',', array(1)))
            && Configuration::updateValue('AT_MANUFACTURER_PAUSE', '3000')
            && Configuration::updateValue('AT_MANUFACTURER_MOVE', '1')
            && Configuration::updateValue('AT_MANUFACTURER_LOOP', '0')
            && Configuration::updateValue('AT_MANUFACTURER_AUTO', '0')
            && $this->registerHook('angarManufacturer')
            && $this->registerHook('displayBeforeBodyClosingTag')
            && $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        // Delete configuration
        return Configuration::deleteByName('AT_MANUFACTURER_ID')
        && Configuration::deleteByName('AT_MANUFACTURER_PAUSE')
        && Configuration::deleteByName('AT_MANUFACTURER_MOVE')
        && Configuration::deleteByName('AT_MANUFACTURER_LOOP')
        && Configuration::deleteByName('AT_MANUFACTURER_AUTO')
        && parent::uninstall();
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->addCSS(($this->_path).'views/css/at_manufacturer.css', 'all');
    }

    public function hookAngarManufacturer($params)
    {
        $manSelected = Configuration::get('AT_MANUFACTURER_ID');
        $manuArray = explode(',', $manSelected);
        $arrayMan = array();
        foreach ($manuArray as $id_manufacturer) {
            $manufacturer = new Manufacturer((int)$id_manufacturer);
            $manufacturer->link_rewrite = Tools::link_rewrite($manufacturer->name);
            
            $imageurl = $this->context->language->iso_code.'-'.ImageType::getFormattedName('default-manufacturer').'.jpg';
            if (file_exists(_PS_MANU_IMG_DIR_.$id_manufacturer.'-'.ImageType::getFormattedName('manufacturer').'.jpg')) {
                $imageurl = $id_manufacturer.'-'.ImageType::getFormattedName('manufacturer').'.jpg';
            }

            $arrayMan[] = array('id' => $id_manufacturer, 'name' => $manufacturer->name, 'link_rewrite' => $manufacturer->link_rewrite, 'image' => $imageurl);
        }

        if (!$this->isCached($this->templateFile, $this->getCacheId('angarmanufacturer'))) {
            $this->smarty->assign(array(
                'homeManufacturers' => $arrayMan
            ));
        }

        return $this->display(__FILE__, 'views/templates/front/at_manufacturer.tpl', $this->getCacheId('angarmanufacturer'));
    }

    public function hookDisplayBeforeBodyClosingTag($params)
    {
        $this->smarty->assign(array(
            'man_pause' => Configuration::get('AT_MANUFACTURER_PAUSE'),
            'man_move' => Configuration::get('AT_MANUFACTURER_MOVE'),
            'man_loop' => Configuration::get('AT_MANUFACTURER_LOOP'),
            'man_auto' => Configuration::get('AT_MANUFACTURER_AUTO')
        ));

        return $this->display(__FILE__, 'views/templates/front/at_manufacturer_script.tpl');
    }

    public function getContent()
    {
        $html = '';
        // If we try to update the settings
        if (Tools::isSubmit('submitModule')) {
            Configuration::updateValue('AT_MANUFACTURER_ID', implode(',', Tools::getValue('at_manufacturer_id')));
            Configuration::updateValue('AT_MANUFACTURER_PAUSE', Tools::getValue('at_manufacturer_pause'));
            Configuration::updateValue('AT_MANUFACTURER_MOVE', Tools::getValue('at_manufacturer_move'));
            Configuration::updateValue('AT_MANUFACTURER_LOOP', Tools::getValue('at_manufacturer_loop'));
            Configuration::updateValue('AT_MANUFACTURER_AUTO', Tools::getValue('at_manufacturer_auto'));
            $this->_clearCache($this->templateFile);
            $html .= $this->displayConfirmation($this->l('Configuration updated'));
        }

        $html .= $this->renderForm();

        return $html;
    }

    private function getManufacturerOption($id_lang = false)
    {
        $id_lang = $id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;
        $data = Manufacturer::getManufacturers(true, $id_lang, true, false, false, false);
        $manuCurrent = Configuration::get($this->name . 'AT_MANUFACTURER_ID');
        $manuCurrent = explode(',', $manuCurrent);
        
        foreach ($data as &$item) {
            $this->man_array[] = array("key"=>(int) $item['id_manufacturer'], "name"=>$item['name']);
        }
    }

    public $man_array;
    
    public function renderForm()
    {
        $this->man_array = array();
        $this->getManufacturerOption((int)Context::getContext()->language->id, (int)Context::getContext()->shop->id);

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'description' => $this->l('This module displays featured manufacturers on the home page'),
                'input' => array(

                    array(
                        'label' => $this->l('Select manufacturers:'),
                        'desc'     => $this->l('Press ctrl to select several manufacturer'),
                        'type' => 'select',
                        'name' => 'at_manufacturer_id[]',
                        'multiple' => true,
                        'size' => 12,
                        'options' => array(
                            'query' => $this->man_array,
                            'id' => 'key',
                            'name' => 'name'
                        )
                    ),

                    array(
                        'type' => 'text',
                        'class'    => 'fixed-width-xl',
                        'label' => $this->l('Manufacturers  moved by click:'),
                        'name' => 'at_manufacturer_move',
                    ),

                    array(
                        'type' => 'text',
                        'class'    => 'fixed-width-xl',
                        'label' => $this->l('Pause (milliseconds):'),
                        'name' => 'at_manufacturer_pause',
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->l('Infinite loop:'),
                        'name' => 'at_manufacturer_loop',
                        'is_bool' => true,
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
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->l('Auto start:'),
                        'name' => 'at_manufacturer_auto',
                        'is_bool' => true,
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
                    ),

                ),
                'submit' => array(
                    'title' => $this->l('Save'),
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

    public function getConfigFieldsValues()
    {
        return array(
            'at_manufacturer_id[]' => Tools::getValue('at_manufacturer_id', explode(',', Configuration::get('AT_MANUFACTURER_ID'))),
            'at_manufacturer_pause' => Tools::getValue('at_manufacturer_pause', Configuration::get('AT_MANUFACTURER_PAUSE')),
            'at_manufacturer_move' => Tools::getValue('at_manufacturer_move', Configuration::get('AT_MANUFACTURER_MOVE')),
            'at_manufacturer_loop' => Tools::getValue('at_manufacturer_loop', Configuration::get('AT_MANUFACTURER_LOOP')),
            'at_manufacturer_auto' => Tools::getValue('at_manufacturer_auto', Configuration::get('AT_MANUFACTURER_AUTO')),
        );
    }
}
