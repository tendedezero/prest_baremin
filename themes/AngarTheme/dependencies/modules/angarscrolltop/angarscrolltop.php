<?php
/**
* @author      Krzysztof Pecak
* @copyright   2018 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class AngarScrollTop extends Module
{
    public function __construct()
    {
        $this->name = 'angarscrolltop';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'AngarThemes';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('AT - Scroll page to top');
        $this->description = $this->l('Display button to scroll page to top');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayFooter');
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->addCSS(($this->_path).'views/css/angarscrolltop.css', 'all');
        $this->context->controller->addJS(($this->_path).'views/js/angarscrolltop.js', 'all');
    }

    public function hookDisplayFooter($params)
    {
        return $this->display(__FILE__, 'views/templates/front/angarscrolltop.tpl');
    }
}
