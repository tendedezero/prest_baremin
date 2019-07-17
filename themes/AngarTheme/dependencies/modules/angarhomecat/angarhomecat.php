<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class AngarHomeCat extends Module
{
    public function __construct()
    {
        $this->name = 'angarhomecat';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'AngarThemes';
        $this->spacer_size ='4';
        $this->need_instance = 0;


        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('AT - Featured categories');
        $this->description = $this->l('Displays selected categories on the home page');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:angarhomecat/views/templates/hook/at_homecat.tpl';
    }

    public function install()
    {
        return parent::install()
            && Configuration::updateValue('AT_HOMECAT_ID', implode(',', array(3,4,8,9)))
            && Configuration::updateValue('AT_HOMECAT_LIMIT', '2')
            && Configuration::updateValue('AT_HOMECAT_TITLE', '1')
            && Configuration::updateValue('AT_HOMECAT_IMG', '1')
            && Configuration::updateValue('AT_HOMECAT_BTN', '1')
            && $this->registerHook('angarHomeCat')
            && $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        // Delete configuration
        return Configuration::deleteByName('AT_HOMECAT_ID')
        && Configuration::deleteByName('AT_HOMECAT_LIMIT')
        && Configuration::deleteByName('AT_HOMECAT_TITLE')
        && Configuration::deleteByName('AT_HOMECAT_IMG')
        && Configuration::deleteByName('AT_HOMECAT_BTN')
        && parent::uninstall();
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->addCSS(($this->_path).'views/css/at_homecat.css', 'all');
    }

    public function hookAngarHomeCat($params)
    {
        $catSelected = Configuration::get('AT_HOMECAT_ID');
        $catArray = explode(',', $catSelected);
        $id_lang = (int) Context::getContext()->language->id;
        $id_shop = (int) Context::getContext()->shop->id;
        $arrayCatProduct = array();
        foreach ($catArray as $id_category) {
            $category = new Category((int) $id_category, (int) $id_lang, (int) $id_shop);
            $sub_cat = Category::getChildren($id_category, $id_lang);
            $arrayCatProduct[] = array('id' => $id_category, 'name'=> $category->name, 'link_rewrite'=> $category->link_rewrite, 'id_image'=> $category->id_image, 'sub_cat'=>$sub_cat);
        }

        if (!$this->isCached($this->templateFile, $this->getCacheId('angarhomecat'))) {
            $this->smarty->assign(array(
                'catProducts' => $arrayCatProduct,
                'homecat_id' => Configuration::get('AT_HOMECAT_ID'),
                'subcat_limit' => Configuration::get('AT_HOMECAT_LIMIT'),
                'show_title' => Configuration::get('AT_HOMECAT_TITLE'),
                'show_img' => Configuration::get('AT_HOMECAT_IMG'),
                'show_btn' => Configuration::get('AT_HOMECAT_BTN')
            ));
        }

        return $this->display(__FILE__, 'views/templates/front/at_homecat.tpl', $this->getCacheId('angarhomecat'));
    }

    public function getContent()
    {
        $html = '';
        // If we try to update the settings
        if (Tools::isSubmit('submitModule')) {
            Configuration::updateValue('AT_HOMECAT_ID', implode(',', Tools::getValue('at_homecat_id')));
            Configuration::updateValue('AT_HOMECAT_LIMIT', Tools::getValue('at_homecat_limit'));
            Configuration::updateValue('AT_HOMECAT_TITLE', Tools::getValue('at_homecat_title'));
            Configuration::updateValue('AT_HOMECAT_IMG', Tools::getValue('at_homecat_img'));
            Configuration::updateValue('AT_HOMECAT_BTN', Tools::getValue('at_homecat_btn'));
            $this->_clearCache($this->templateFile);
            $html .= $this->displayConfirmation($this->l('Configuration updated'));
        }

        $html .= $this->renderForm();

        return $html;
    }

    private function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true)
    {
        $id_lang = $id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;
        $category = new Category((int) $id_category, (int) $id_lang, (int) $id_shop);
        if (is_null($category->id)) {
            return;
        }
        $spacer = null;
        if ($recursive) {
            $children = Category::getChildren((int) $id_category, (int) $id_lang, true, (int) $id_shop);
            $spacer = str_repeat('&nbsp;', $this->spacer_size * (int) $category->level_depth);
        }

        $catCurrent = Configuration::get($this->name . 'AT_HOMECAT_ID');
        $catCurrent = explode(',', $catCurrent);
        $value = (int) $category->id;
        if (in_array($value, $catCurrent)) {
            $this->cat_array[] = array("key"=>(int) $category->id, "name"=>(isset($spacer) ? $spacer : '') . $category->name);
        } else {
            $this->cat_array[] = array("key"=>(int) $category->id, "name"=>(isset($spacer) ? $spacer : '') . $category->name);
        }
        if (isset($children) && count($children)) {
            foreach ($children as $child) {
                $this->getCategoryOption((int) $child['id_category'], (int) $id_lang, (int) $child['id_shop']);
            }
        }
    }

    public $cat_array;
    
    public function renderForm()
    {
        $this->cat_array = array();
        $this->getCategoryOption(1, (int)Context::getContext()->language->id, (int)Context::getContext()->shop->id);
        //print_r($this->cat_array);
        
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'description' => $this->l('This module displays featured categories on the home page.').'<br/>'.
                $this->l('Tip: To add image to category go to tab Catalog->Categories edit category and add image to field "Category Cover Image"').'<br/>'.
                $this->l('Tip: If you dont see new images save tihs module again or clear PrestaShop cache.'),
                'input' => array(

                    array(
                        'label' => $this->l('Select categories:'),
                        'desc'     => $this->l('Press ctrl to select several categories'),
                        'type' => 'select',
                        'name' => 'at_homecat_id[]',
                        'multiple' => true,
                        'size' => 12,
                        'options' => array(
                            'query' => $this->cat_array,
                            'id' => 'key',
                            'name' => 'name'
                        )
                    ),

                    array(
                        'type' => 'text',
                        'class'    => 'fixed-width-xl',
                        'label' => $this->l('Maximum number of subcategories:'),
                        'name' => 'at_homecat_limit',
                    ),

                    array(
                        'type' => 'switch',
                        'label' => $this->l('Show title "Featured categories":'),
                        'name' => 'at_homecat_title',
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
                        'label' => $this->l('Show category image:'),
                        'name' => 'at_homecat_img',
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
                        'label' => $this->l('Show button "See more":'),
                        'name' => 'at_homecat_btn',
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
            'at_homecat_id[]' => Tools::getValue('at_homecat_id', explode(',', Configuration::get('AT_HOMECAT_ID'))),
            'at_homecat_limit' => Tools::getValue('at_homecat_limit', Configuration::get('AT_HOMECAT_LIMIT')),
            'at_homecat_title' => Tools::getValue('at_homecat_title', Configuration::get('AT_HOMECAT_TITLE')),
            'at_homecat_img' => Tools::getValue('at_homecat_img', Configuration::get('AT_HOMECAT_IMG')),
            'at_homecat_btn' => Tools::getValue('at_homecat_btn', Configuration::get('AT_HOMECAT_BTN')),
        );
    }
}
