<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class ndcSharer extends Module implements WidgetInterface
{
    protected static $networks = array('Facebook', 'Twitter', 'Google', 'Pinterest');

    private $templateFile;

    public function __construct()
    {
        $this->name = 'ndcSharer';
        $this->author = 'PrestaShop';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
        $this->_directory = dirname(__FILE__);

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Social media share buttons', array(), 'Modules.ndcSharer.Admin');
        $this->description = $this->trans('Displays social media sharing buttons on every product page.', array(), 'Modules.ndcSharer.Admin');

        $this->templateFile = 'module:ndcSharer/views/templates/hook/ndcSharer.tpl';
    }

    public function install()
    {
        return parent::install()
            && Configuration::updateValue('NDC_SC_TWITTER', 1)
            && Configuration::updateValue('NDC_SC_FACEBOOK', 1)
            && Configuration::updateValue('NDC_SC_GOOGLE', 1)
            && Configuration::updateValue('NDC_SC_PINTEREST', 1)
            && $this->registerHook('displayProductButtons')
        ;
    }

    public function getConfigFieldsValues()
    {
        $values = array();

        foreach (self::$networks as $network) {
            $values['PS_SC_'.Tools::strtoupper($network)] = (int)Tools::getValue('PS_SC_'.Tools::strtoupper($network), Configuration::get('PS_SC_'.Tools::strtoupper($network)));
        }

        return $values;
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitSocialSharing')) {
            foreach (self::$networks as $network) {
                Configuration::updateValue('PS_SC_'.Tools::strtoupper($network), (int)Tools::getValue('PS_SC_'.Tools::strtoupper($network)));
            }

            $this->_clearCache($this->templateFile);

            $output .= $this->displayConfirmation($this->trans('Settings updated.', array(), 'Admin.Notifications.Success'));

            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true).'&conf=6&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name);
        }

        $helper = new HelperForm();
        $helper->submit_action = 'submitSocialSharing';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array('fields_value' => $this->getConfigFieldsValues());

        $fields = array();
        foreach (self::$networks as $network) {
            $fields[] = array(
                'type' => 'switch',
                'label' => $network,
                'name' => 'NDC_SC_'.Tools::strtoupper($network),
                'values' => array(
                    array(
                        'id' => Tools::strtolower($network).'_active_on',
                        'value' => 1,
                        'label' => $this->trans('Enabled', array(), 'Admin.Global')
                    ),
                    array(
                        'id' => Tools::strtolower($network).'_active_off',
                        'value' => 0,
                        'label' => $this->trans('Disabled', array(), 'Admin.Global')
                    )
                )
            );
        }

        return $output.$helper->generateForm(array(
            array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->displayName,
                        'icon' => 'icon-share'
                    ),
                    'input' => $fields,
                    'submit' => array(
                        'title' => $this->trans('Save', array(), 'Admin.Actions')
                    )
                )
            )
        ));
    }

    public function renderWidget($hookName, array $params)
    {
        $key = 'ndcSharer|' . $params['product']['id_product'];
        if (!empty($params['product']['id_product_attribute'])) {
            $key .= '|' . $params['product']['id_product_attribute'];
        }

        if (!$this->isCached($this->templateFile, $this->getCacheId($key))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $params));
        }

        return $this->fetch($this->templateFile, $this->getCacheId($key));
    }

    public function getWidgetVariables($hookName, array $params)
    {
        if (!method_exists($this->context->controller, 'getProduct')) {
            return;
        }

        $product = $this->context->controller->getProduct();

        if (!Validate::isLoadedObject($product)) {
            return;
        }

        $social_share_links = [];
        $sharing_url = addcslashes($this->context->link->getProductLink($product), "'");
        $sharing_name = addcslashes($product->name, "'");

        $image_cover_id = $product->getCover($product->id);
        if (is_array($image_cover_id) && isset($image_cover_id['id_image'])) {
            $image_cover_id = (int)$image_cover_id['id_image'];
        } else {
            $image_cover_id = 0;
        }

        $sharing_img = addcslashes($this->context->link->getImageLink($product->link_rewrite, $image_cover_id), "'");

        if (Configuration::get('NDC_SC_FACEBOOK')) {
            $social_share_links['facebook'] = array(
                'label' => $this->trans('Share', array(), 'Modules.ndcSharer.Shop'),
                'class' => 'facebook',
                'url' => 'http://www.facebook.com/sharer.php?u='.$sharing_url,
            );
        }

        if (Configuration::get('NDC_SC_TWITTER')) {
            $social_share_links['twitter'] = array(
                'label' => $this->trans('Tweet', array(), 'Modules.ndcSharer.Shop'),
                'class' => 'twitter',
                'url' => 'https://twitter.com/intent/tweet?text='.$sharing_name.' '.$sharing_url,
            );
        }

        if (Configuration::get('NDC_SC_GOOGLE')) {
            $social_share_links['googleplus'] = array(
                'label' => $this->trans('Google+', array(), 'Modules.ndcSharer.Shop'),
                'class' => 'googleplus',
                'url' => 'https://plus.google.com/share?url='.$sharing_url,
            );
        }

        if (Configuration::get('NDC_SC_PINTEREST')) {
            $social_share_links['pinterest'] = array(
                'label' => $this->trans('Pinterest', array(), 'Modules.ndcSharer.Shop'),
                'class' => 'pinterest',
                'url' => 'http://www.pinterest.com/pin/create/button/?media='.$sharing_img.'&url='.$sharing_url,
            );
        }

        return array(
            'social_share_links' => $social_share_links,
        );
    }
}
