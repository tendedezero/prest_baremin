<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class AngarCatproduct extends Module
{
    public function __construct()
    {
        $this->name = 'angarcatproduct';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'AngarThemes';
        $this->spacer_size ='4';
        $this->need_instance = 0;


        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('AT - Products from category');
        $this->description = $this->l('Displays products from selected categories on the home page');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:angarcatproduct/views/templates/hook/at_catproduct.tpl';
    }

    public function install()
    {
        return parent::install()
            && Configuration::updateValue('AT_CATPRODUCT_ID', implode(',', array(6,7,8,9)))
            && Configuration::updateValue('AT_CATPRODUCT_LIMIT', '8')
            && Configuration::updateValue('AT_CATPRODUCT_VISIBLE', '4')
            && Configuration::updateValue('AT_CATPRODUCT_MOVE', '1')
            && Configuration::updateValue('AT_CATPRODUCT_LOOP', '0')
            && Configuration::updateValue('AT_CATPRODUCT_AUTO', '0')
            && Configuration::updateValue('AT_CATPRODUCT_PAUSE', '3000')
            && $this->registerHook('angarProductCat')
            && $this->registerHook('displayBeforeBodyClosingTag')
            && $this->registerHook('displayHeader')
            && $this->disableDevice(Context::DEVICE_MOBILE);
    }

    public function uninstall()
    {
        // Delete configuration
        return Configuration::deleteByName('AT_CATPRODUCT_ID')
        && Configuration::deleteByName('AT_CATPRODUCT_LIMIT')
        && Configuration::deleteByName('AT_CATPRODUCT_VISIBLE')
        && Configuration::deleteByName('AT_CATPRODUCT_MOVE')
        && Configuration::deleteByName('AT_CATPRODUCT_LOOP')
        && Configuration::deleteByName('AT_CATPRODUCT_AUTO')
        && Configuration::deleteByName('AT_CATPRODUCT_PAUSE')
        && parent::uninstall();
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->addCSS(($this->_path).'views/css/at_catproduct.css', 'all');
    }

    public function hookAngarProductCat($params)
    {
        $nb = Configuration::get('AT_CATPRODUCT_LIMIT');
        $catSelected = Configuration::get('AT_CATPRODUCT_ID');
        $catArray = explode(',', $catSelected);
        $id_lang = (int) Context::getContext()->language->id;
        $id_shop = (int) Context::getContext()->shop->id;
        $arrayCatProduct = array();
        foreach ($catArray as $id_category) {
            $category = new Category((int) $id_category, (int) $id_lang, (int) $id_shop);
            //$categoryProducts = $category->getProducts($this->context->language->id, 0, ($nb ? $nb : 5));
            
            $categoryProducts = $this->getProducts($id_category);
            
            $arrayCatProduct[] = array('id' => $id_category, 'name'=> $category->name, 'link_rewrite'=> $category->link_rewrite, 'product' => $categoryProducts);
        }

        if (!$this->isCached($this->templateFile, $this->getCacheId('angarcatproduct'))) {
            $this->smarty->assign(array(
                'catProducts' => $arrayCatProduct,
                'catprod_id' => Configuration::get('AT_CATPRODUCT_ID')
            ));
        }

        return $this->display(__FILE__, 'views/templates/front/at_catproduct.tpl', $this->getCacheId('angarcatproduct'));
    }
    
    protected function getProducts($id_category)
    {
        $category = new Category($id_category);

        $searchProvider = new CategoryProductSearchProvider(
            $this->context->getTranslator(),
            $category
        );

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $nProducts = Configuration::get('AT_CATPRODUCT_LIMIT');
        if ($nProducts < 0) {
            $nProducts = 12;
        }

        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1)
        ;

        if (Configuration::get('HOME_FEATURED_RANDOMIZE')) {
            $query->setSortOrder(SortOrder::random());
        } else {
            $query->setSortOrder(new SortOrder('product', 'position', 'asc'));
        }

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        $products_for_template = array();

        foreach ($result->getProducts() as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return $products_for_template;
    }

    public function hookDisplayBeforeBodyClosingTag($params)
    {
        $this->smarty->assign(array(
            'visible' => Configuration::get('AT_CATPRODUCT_VISIBLE'),
            'move' => Configuration::get('AT_CATPRODUCT_MOVE'),
            'loop' => Configuration::get('AT_CATPRODUCT_LOOP'),
            'auto' => Configuration::get('AT_CATPRODUCT_AUTO'),
            'pause' => Configuration::get('AT_CATPRODUCT_PAUSE')
        ));

        return $this->display(__FILE__, 'views/templates/front/at_catproduct_script.tpl');
    }

    public function getContent()
    {
        $html = '';
        // If we try to update the settings
        if (Tools::isSubmit('submitModule')) {
            Configuration::updateValue('AT_CATPRODUCT_ID', implode(',', Tools::getValue('at_catproduct_id')));
            Configuration::updateValue('AT_CATPRODUCT_LIMIT', Tools::getValue('at_catproduct_limit'));
            Configuration::updateValue('AT_CATPRODUCT_VISIBLE', Tools::getValue('at_catproduct_visible'));
            Configuration::updateValue('AT_CATPRODUCT_MOVE', Tools::getValue('at_catproduct_move'));
            Configuration::updateValue('AT_CATPRODUCT_LOOP', Tools::getValue('at_catproduct_loop'));
            Configuration::updateValue('AT_CATPRODUCT_AUTO', Tools::getValue('at_catproduct_auto'));
            Configuration::updateValue('AT_CATPRODUCT_PAUSE', Tools::getValue('at_catproduct_pause'));
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

        $catCurrent = Configuration::get($this->name . 'AT_CATPRODUCT_ID');
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
                'description' => $this->l('This module displays products from the category on the home page.'),

                'description' => $this->l('This module displays products from the category on the home page.').'<br/>'.
                        $this->l('Tip: If "Inifinite loop" does not work properly add more products to the category.'),
                'input' => array(

                    array(
                        'label' => $this->l('Select categories:'),
                        'desc'     => $this->l('Press ctrl to select several categories'),
                        'type' => 'select',
                        'name' => 'at_catproduct_id[]',
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
                        'label' => $this->l('Products quantity:'),
                        'name' => 'at_catproduct_limit',
                    ),

                    array(
                        'type' => 'text',
                        'class'    => 'fixed-width-xl',
                        'label' => $this->l('Products visible:'),
                        'name' => 'at_catproduct_visible',
                    ),

                    array(
                        'type' => 'text',
                        'class'    => 'fixed-width-xl',
                        'label' => $this->l('Products moved by click:'),
                        'name' => 'at_catproduct_move',
                    ),

                    array(
                        'type' => 'text',
                        'class'    => 'fixed-width-xl',
                        'label' => $this->l('Pause (milliseconds):'),
                        'name' => 'at_catproduct_pause',
                    ),

                    array(
                        'type' => 'switch',
                        'desc'     => $this->l('In the category must be more than 4 products or this option will not work properly'),
                        'label' => $this->l('Infinite loop:'),
                        'name' => 'at_catproduct_loop',
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
                        'name' => 'at_catproduct_auto',
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
            'at_catproduct_id[]' => Tools::getValue('at_catproduct_id', explode(',', Configuration::get('AT_CATPRODUCT_ID'))),
            'at_catproduct_limit' => Tools::getValue('at_catproduct_limit', Configuration::get('AT_CATPRODUCT_LIMIT')),
            'at_catproduct_visible' => Tools::getValue('at_catproduct_visible', Configuration::get('AT_CATPRODUCT_VISIBLE')),
            'at_catproduct_move' => Tools::getValue('at_catproduct_move', Configuration::get('AT_CATPRODUCT_MOVE')),
            'at_catproduct_loop' => Tools::getValue('at_catproduct_loop', Configuration::get('AT_CATPRODUCT_LOOP')),
            'at_catproduct_auto' => Tools::getValue('at_catproduct_auto', Configuration::get('AT_CATPRODUCT_AUTO')),
            'at_catproduct_pause' => Tools::getValue('at_catproduct_pause', Configuration::get('AT_CATPRODUCT_PAUSE')),
        );
    }
}
