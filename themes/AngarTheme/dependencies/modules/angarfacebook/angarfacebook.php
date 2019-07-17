<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class AngarFacebook extends Module
{
    public function __construct()
    {
        $this->name = 'angarfacebook';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'AngarThemes';

        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('AT - Facebook likebox tab');
        $this->description = $this->l('Displays a block for subscribing to your Facebook Page.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:angarfacebook/views/templates/hook/angarfacebook.tpl';
    }


    public function install()
    {
        return parent::install() &&
            Configuration::updateValue('angarfacebook_url', 'https://www.facebook.com/facebook') &&
            $this->registerHook('angarFacebook') &&
            $this->registerHook('displayHeader') &&
            $this->disableDevice(Context::DEVICE_MOBILE);
    }

    public function uninstall()
    {
        // Delete configuration
        return Configuration::deleteByName('angarfacebook_url') && parent::uninstall();
    }

    public function getContent()
    {
        $html = '';
        // If we try to update the settings
        if (Tools::isSubmit('submitModule')) {
            Configuration::updateValue('angarfacebook_url', Tools::getValue('angarfacebook_url'));
            $html .= $this->displayConfirmation($this->l('Configuration updated'));
            $this->_clearCache($this->templateFile);
            Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
        }

        $html .= $this->renderForm();
        $facebookurl = Configuration::get('angarfacebook_url');
        if (!strstr($facebookurl, "facebook.com")) {
            $facebookurl="https://www.facebook.com/".$facebookurl;
        }
        $this->context->smarty->assign('facebookurl', $facebookurl);
        $this->context->smarty->assign('facebook_js_url', $this->_path.'views/js/angarfacebook.js');
        $this->context->smarty->assign('facebook_css_url', $this->_path.'views/css/angarfacebook.css');
        $html .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/_configure/preview.tpl');
        return $html;
    }

    public function hookAngarFacebook()
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('angarfacebook'))) {
            $facebookurl = Configuration::get('angarfacebook_url');
            if (!strstr($facebookurl, 'facebook.com')) {
                $facebookurl = 'https://www.facebook.com/'.$facebookurl;
            }
            $this->context->smarty->assign('facebookurl', $facebookurl);
        }
        return $this->display(__FILE__, 'views/templates/front/angarfacebook.tpl', $this->getCacheId('angarfacebook'));
    }

    public function hookHeader()
    {
        $this->context->controller->addCss(($this->_path).'views/css/angarfacebook.css');
        $this->context->controller->addJS(($this->_path).'views/js/angarfacebook.js');
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
                        'label' => $this->l('Facebook link (full URL is required)'),
                        'name' => 'angarfacebook_url',
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
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'angarfacebook_url' => Tools::getValue('angarfacebook_url', Configuration::get('angarfacebook_url')),
        );
    }
}
