<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

if (!defined('_PS_VERSION_')) {
    exit;
}

class AngarSpecials extends Module implements WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'angarspecials';
        $this->tab = 'front_office_features';
        $this->author = 'AngarThemes';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('AT - Specials block', array(), 'Modules.Specials.Admin');
        $this->description = $this->trans('Displays your products that are currently on sale in a dedicated block.', array(), 'Modules.Specials.Admin');

        $this->templateFile = 'module:angarspecials/views/templates/hook/angarspecials.tpl';
    }

    public function install()
    {
        $this->_clearCache('*');

        Configuration::updateValue('ANGAR_SPECIALS_NBR', 9);

        return parent::install()
            && $this->registerHook('actionProductAdd')
            && $this->registerHook('actionProductUpdate')
            && $this->registerHook('actionProductDelete')
            && $this->registerHook('actionObjectSpecificPriceCoreDeleteAfter')
            && $this->registerHook('actionObjectSpecificPriceCoreAddAfter')
            && $this->registerHook('actionObjectSpecificPriceCoreUpdateAfter')
            && $this->registerHook('displayHomeTab')
            && $this->registerHook('displayHomeTabContent')
        ;
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall();
    }

    public function hookdisplayHomeTab($params)
    {
        $products = $this->getSpecialProducts();

        if (!empty($products)) {
            return $this->display(__file__, 'tab.tpl');
        } else {
            return false;
        }
    }

    public function hookActionProductAdd($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionProductUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionProductDelete($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionObjectSpecificPriceCoreDeleteAfter($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionObjectSpecificPriceCoreAddAfter($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionObjectSpecificPriceCoreUpdateAfter($params)
    {
        $this->_clearCache('*');
    }

    public function _clearCache($template, $cache_id = null, $compile_id = null)
    {
        if ($template || $cache_id || $compile_id) {
        }
        parent::_clearCache($this->templateFile);
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submitAngarSpecials')) {
            Configuration::updateValue('ANGAR_SPECIALS_NBR', (int)Tools::getValue('ANGAR_SPECIALS_NBR'));

            $this->_clearCache('*');

            $output .= $this->displayConfirmation($this->trans('The settings have been updated.', array(), 'Admin.Notifications.Success'));
        }
        return $output.$this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Settings', array(), 'Admin.Global'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Products to display', array(), 'Modules.Specials.Admin'),
                        'name' => 'ANGAR_SPECIALS_NBR',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->trans('Define the number of products to be displayed in this block on home page.', array(), 'Modules.Specials.Admin'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Admin.Actions'),
                ),
            ),
        );

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitAngarSpecials';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) .
            '&configure=' . $this->name .
            '&tab_module=' . $this->tab .
            '&module_name=' . $this->name;
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
            'ANGAR_SPECIALS_NBR' => Tools::getValue('ANGAR_SPECIALS_NBR', Configuration::get('ANGAR_SPECIALS_NBR')),
        );
    }

    public function renderWidget($hookName = null, array $configuration = array())
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('angarspecials'))) {
            $variables = $this->getWidgetVariables($hookName, $configuration);

            if (empty($variables)) {
                return false;
            }

            $this->smarty->assign($variables);
        }

        return $this->fetch($this->templateFile, $this->getCacheId('angarspecials'));
    }

    public function getWidgetVariables($hookName = null, array $configuration = array())
    {
        if ($hookName || $configuration) {
        }
        $products = $this->getSpecialProducts();

        if (!empty($products)) {
            return array(
                'products' => $products,
                'allSpecialProductsLink2' => Context::getContext()->link->getPageLink('prices-drop'),
            );
        }
        return false;
    }

    private function getSpecialProducts()
    {
        $products = Product::getPricesDrop(
            (int)Context::getContext()->language->id,
            0,
            (int)Configuration::get('ANGAR_SPECIALS_NBR')
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

        if (is_array($products)) {
            foreach ($products as $rawProduct) {
                $products_for_template[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                );
            }
        }

        return $products_for_template;
    }
}
