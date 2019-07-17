<?php
/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @copyright  2007-2016 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_CAN_LOAD_FILES_')) {
    exit;
}
    
class Angarcontact extends Module
{
    protected static $contact_fields = array(
        'ANGARCONTACT_COMPANY',
        'ANGARCONTACT_ADDRESS',
        'ANGARCONTACT_PHONE',
        'ANGARCONTACT_EMAIL',
    );

    public function __construct()
    {
        $this->name = 'angarcontact';
        $this->author = 'AngarThemes';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('AT - Contact top and footer');
        $this->description = $this->l('This module will allow you to display your e-store contact information in a customizable block.');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        Configuration::updateValue('ANGARCONTACT_COMPANY', Configuration::get('PS_SHOP_NAME'));
        Configuration::updateValue('ANGARCONTACT_ADDRESS', '42 Puffin street 12345 Puffinville France');
        Configuration::updateValue('ANGARCONTACT_PHONE', '0123-456-789');
        Configuration::updateValue('ANGARCONTACT_EMAIL', Configuration::get('PS_SHOP_EMAIL'));
        $this->_clearCache('module:angarcontact/views/templates/front/nav.tpl');
        $this->_clearCache('module:angarcontact/views/templates/front/angarcontact.tpl');
        return (parent::install() && $this->registerHook('displayNav1') && $this->registerHook('displayFooter'));
    }

    public function uninstall()
    {
        foreach (Angarcontact::$contact_fields as $field) {
            Configuration::deleteByName($field);
        }
        return (parent::uninstall());
    }

    public function getContent()
    {
        $html = '';
        if (Tools::isSubmit('submitModule')) {
            foreach (Angarcontact::$contact_fields as $field) {
                Configuration::updateValue($field, Tools::getValue($field), true);
            }
            $this->_clearCache('module:angarcontact/views/templates/front/angarcontact.tpl');
            $this->_clearCache('module:angarcontact/views/templates/front/nav.tpl');
            $html = $this->displayConfirmation($this->l('Configuration updated'));
        }

        return $html.$this->renderForm();
    }
    
    public function hookDisplayFooter($params)
    {
        if (!$this->isCached('module:angarcontact/views/templates/front/angarcontact.tpl', $this->getCacheId())) {
            foreach (Angarcontact::$contact_fields as $field) {
                $this->smarty->assign(Tools::strtolower($field), Configuration::get($field));
            }
        }
        return $this->display(__FILE__, 'views/templates/front/angarcontact.tpl', $this->getCacheId());
    }

    public function hookDisplayNav1($params)
    {
        if (!$this->isCached('module:angarcontact/views/templates/front/nav.tpl', $this->getCacheId())) {
            foreach (Angarcontact::$contact_fields as $field) {
                $this->smarty->assign(Tools::strtolower($field), Configuration::get($field));
            }
        }
        return $this->display(__FILE__, 'views/templates/front/nav.tpl', $this->getCacheId());
    }
    
    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Company name'),
                        'name' => 'ANGARCONTACT_COMPANY',
                    ),
                    array(
                        'type' => 'textarea',
                        'autoload_rte' => true,
                        'label' => $this->l('Address'),
                        'name' => 'ANGARCONTACT_ADDRESS',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Phone number'),
                        'name' => 'ANGARCONTACT_PHONE',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Email'),
                        'name' => 'ANGARCONTACT_EMAIL',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                )
            ),
        );
        
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        foreach (Angarcontact::$contact_fields as $field) {
            $helper->tpl_vars['fields_value'][$field] = Tools::getValue($field, Configuration::get($field));
        }
        return $helper->generateForm(array($fields_form));
    }
}
