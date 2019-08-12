<?php
/**
*  @author ST-themes https://www.sunnytoo.com
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class StLazyLoading extends Module
{
    public $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();
    private $_st_is_16;

    public function __construct()
    {
        $this->name          = 'stlazyloading';
        $this->tab           = 'front_office_features';
        $this->version       = '1.0.2';
        $this->author        = 'sunnytoo.com';
        $this->need_instance = 0;
        $this->bootstrap     = true;
        
        $this->_st_is_16      = Tools::version_compare(_PS_VERSION_, '1.7');
        parent::__construct();
        
        $this->displayName = $this->l('Lazy loading product images by ST-themes');
        $this->description = $this->l('This module is used to lazy load product images.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        $result = true;
        if (!parent::install()
            || !Configuration::updateValue('ST_LAZYLOADING_LOAD_WAYPOINT', false)
            || !$this->registerHook('displayHeader')
        ) {
             $result = false;
        }
        if($result && !$this->_st_is_16)
            $result &= $this->registerHook('actionProductSearchAfter');
        
        return $result;
    }
    
    public function uninstall()
    {
        if (!parent::uninstall()
        ) {
            return false;
        }
        return true;
    }
    public function getContent()
    {
        $this->initFieldsForm();
        if (isset($_POST['savestlazyloading']))
        {
            foreach($this->fields_form as $form)
                foreach($form['form']['input'] as $field)
                    if(isset($field['validation']))
                    {
                        $errors = array();       
                        $value = Tools::getValue($field['name']);
                        if (isset($field['required']) && $field['required'] && $value==false && (string)$value != '0')
                                $errors[] = sprintf(Tools::displayError('Field "%s" is required.'), $field['label']);
                        elseif($value)
                        {
                            $field_validation = $field['validation'];
                            if (!Validate::$field_validation($value))
                                $errors[] = sprintf(Tools::displayError('Field "%s" is invalid.'), $field['label']);
                        }
                        // Set default value
                        if ($value === false && isset($field['default_value']))
                            $value = $field['default_value'];
                        
                        if(count($errors))
                        {
                            $this->validation_errors = array_merge($this->validation_errors, $errors);
                        }
                        elseif($value==false)
                        {
                            switch($field['validation'])
                            {
                                case 'isUnsignedId':
                                case 'isUnsignedInt':
                                case 'isInt':
                                case 'isBool':
                                    $value = 0;
                                break;
                                default:
                                    $value = '';
                                break;
                            }
                            Configuration::updateValue('ST_LAZYLOADING_'.strtoupper($field['name']), $value);
                        }
                        else
                            Configuration::updateValue('ST_LAZYLOADING_'.strtoupper($field['name']), $value);
                    }
                                                 
            if(count($this->validation_errors))
                $this->_html .= $this->displayError(implode('<br/>',$this->validation_errors));
            else 
                $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
        }

        $helper = $this->initForm();
        
        return $this->_html.$helper->generateForm($this->fields_form).'<div class="alert alert-info">This free module was created by <a href="https://www.sunnytoo.com" target="_blank">ST-THEMES</a>, it\'s not allow to sell it, it\'s also not allow to create new modules based on this one. Check more <a href="https://www.sunnytoo.com/blogs?term=743&orderby=date&order=desc" target="_blank">free modules</a>, <a href="https://www.sunnytoo.com/product-category/prestashop-modules" target="_blank">advanced paid modules</a> and <a href="https://www.sunnytoo.com/product-category/prestashop-themes" target="_blank">themes(transformer theme and panda  theme)</a> created by <a href="https://www.sunnytoo.com" target="_blank">ST-THEMES</a>.</div>';
    }

    protected function initFieldsForm()
    {
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->displayName,
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                'load_waypoint' => array(
                    'type' => 'switch',
                    'label' => $this->l('Stop loading waypoint jQuery plugin:'),
                    'name' => 'load_waypoint',
                    'default_value' => 0,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'load_waypoint_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'load_waypoint_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                    'validation' => 'isBool',
                    'desc' => $this->l('If you have installed any other modules from ST-themes which already load the waypoing jquey plugin to your site, then you can enable this option to stop loading it again. The plugin just need to be loaded for once.'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Save   ')
            )
        );
    }
    protected function initForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $helper->module = $this;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'savestlazyloading';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        return $helper;
    }
    
    private function getConfigFieldsValues()
    {
        $fields_values = array(
            'load_waypoint' => Configuration::get('ST_LAZYLOADING_LOAD_WAYPOINT'),
        );
        
        return $fields_values;
    }
    public function getStBasicVals(){
        $vals = array(
            'lang_iso_code' => $this->context->language->iso_code, //For products listing page after ajax search, probably can be removed in future.
            'img_prod_url' => Tools::getCurrentUrlProtocolPrefix().Tools::getMediaServer(_THEME_PROD_DIR_)._THEME_PROD_DIR_, //the same as above, got this from frontController getTemplateVarUrls 
        );
        return $vals;
    }
    public function hookActionProductSearchAfter($params){
        $this->context->smarty->assign('stlazyloading', $this->getStBasicVals());
        return ;
    }
    public function hookDisplayHeader($params)
    {
        $this->context->controller->addCSS($this->_path.'views/css/stlazyloading.css', 'all');
        if (!Configuration::get('ST_LAZYLOADING_LOAD_WAYPOINT')) {
            $this->context->controller->addJS($this->_path.'views/js/jquery.waypoints.min.js');
        }
        $this->context->controller->addJS($this->_path.'views/js/stlazyloading'.($this->_st_is_16 ? '16' : '').'.js');

        $this->context->smarty->assign('stlazyloading', $this->getStBasicVals());
        return;
    }
}
