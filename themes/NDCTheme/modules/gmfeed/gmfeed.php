<?php

/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2019 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */
class gmfeed extends Module
{
    public function __construct()
    {
        $this->name = 'gmfeed';
        $this->tab = 'administration';
        $this->version = '1.6.0';
        $this->mypresta_link = 'https://mypresta.eu/modules/social-networks/google-merchant-center-feed.html';
        $this->bootstrap = true;
        $this->displayName = 'Google Merchant Center Feed';
        $this->author = 'MyPresta.eu';
        $this->description = $this->l('An easiest way to export your products catalog to .csv file for google merchant center feed purposes');
        parent::__construct();
        $this->checkforupdates(0, 0);
    }

    public function hookactionAdminControllerSetMedia($params)
    {
        //for update feature purposes
    }

    public function checkforupdates($display_msg = 0, $form = 0)
    {
        // ---------- //
        // ---------- //
        // VERSION 16 //
        // ---------- //
        // ---------- //
        $this->mkey = "nlc";
        if (@file_exists('../modules/' . $this->name . '/key.php')) {
            @require_once('../modules/' . $this->name . '/key.php');
        } else {
            if (@file_exists(dirname(__FILE__) . $this->name . '/key.php')) {
                @require_once(dirname(__FILE__) . $this->name . '/key.php');
            } else {
                if (@file_exists('modules/' . $this->name . '/key.php')) {
                    @require_once('modules/' . $this->name . '/key.php');
                }
            }
        }
        if ($form == 1) {
            return '
            <div class="panel" id="fieldset_myprestaupdates" style="margin-top:20px;">
            ' . ($this->psversion() == 6 || $this->psversion() == 7 ? '<div class="panel-heading"><i class="icon-wrench"></i> ' . $this->l('MyPresta updates') . '</div>' : '') . '
			<div class="form-wrapper" style="padding:0px!important;">
            <div id="module_block_settings">
                    <fieldset id="fieldset_module_block_settings">
                         ' . ($this->psversion() == 5 ? '<legend style="">' . $this->l('MyPresta updates') . '</legend>' : '') . '
                        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                            <label>' . $this->l('Check updates') . '</label>
                            <div class="margin-form">' . (Tools::isSubmit('submit_settings_updates_now') ? ($this->inconsistency(0) ? '' : '') . $this->checkforupdates(1) : '') . '
                                <button style="margin: 0px; top: -3px; position: relative;" type="submit" name="submit_settings_updates_now" class="button btn btn-default" />
                                <i class="process-icon-update"></i>
                                ' . $this->l('Check now') . '
                                </button>
                            </div>
                            <label>' . $this->l('Updates notifications') . '</label>
                            <div class="margin-form">
                                <select name="mypresta_updates">
                                    <option value="-">' . $this->l('-- select --') . '</option>
                                    <option value="1" ' . ((int)(Configuration::get('mypresta_updates') == 1) ? 'selected="selected"' : '') . '>' . $this->l('Enable') . '</option>
                                    <option value="0" ' . ((int)(Configuration::get('mypresta_updates') == 0) ? 'selected="selected"' : '') . '>' . $this->l('Disable') . '</option>
                                </select>
                                <p class="clear">' . $this->l('Turn this option on if you want to check MyPresta.eu for module updates automatically. This option will display notification about new versions of this addon.') . '</p>
                            </div>
                            <label>' . $this->l('Module page') . '</label>
                            <div class="margin-form">
                                <a style="font-size:14px;" href="' . $this->mypresta_link . '" target="_blank">' . $this->displayName . '</a>
                                <p class="clear">' . $this->l('This is direct link to official addon page, where you can read about changes in the module (changelog)') . '</p>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="submit_settings_updates"class="button btn btn-default pull-right" />
                                <i class="process-icon-save"></i>
                                ' . $this->l('Save') . '
                                </button>
                            </div>
                        </form>
                    </fieldset>
                    <style>
                    #fieldset_myprestaupdates {
                        display:block;clear:both;
                        float:inherit!important;
                    }
                    </style>
                </div>
            </div>
            </div>';
        } else {
            if (defined('_PS_ADMIN_DIR_')) {
                if (Tools::isSubmit('submit_settings_updates')) {
                    Configuration::updateValue('mypresta_updates', Tools::getValue('mypresta_updates'));
                }
                if (Configuration::get('mypresta_updates') != 0 || (bool)Configuration::get('mypresta_updates') != false) {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = gmfeedUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (gmfeedUpdate::version($this->version) < gmfeedUpdate::version(Configuration::get('updatev_' . $this->name)) && Tools::getValue('ajax','false') == 'false') {
                        $this->context->controller->warnings[] = '<strong>' . $this->displayName . '</strong>: ' . $this->l('New version available, check http://MyPresta.eu for more informations') . ' <a href="' . $this->mypresta_link . '">' . $this->l('More details in changelog') . '</a>';
                        $this->warning                         = $this->context->controller->warnings[0];
                    }
                } else {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = gmfeedUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                }
                if ($display_msg == 1) {
                    if (gmfeedUpdate::version($this->version) < gmfeedUpdate::version(gmfeedUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version))) {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    } else {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->InDelMenu('install', 'AdminExportProductsFeedGoogle', $this->l('Google Merchant Feed'))
            || !$this->installsql()
            )
        {
            return false;
        }
        return true;
    }

    public function installsql()
    {
        $prefix = _DB_PREFIX_;
        $statements = array();
        $statements[] = "CREATE TABLE IF NOT EXISTS `" . $prefix . "pixelgoogle` (
        `id_pixelgoogle` int(11) NOT NULL AUTO_INCREMENT,
        `id_google` int(11) DEFAULT '0',
        `id_category` int(11) DEFAULT '0',
        `id_lang` int(11) DEFAULT '0',
        `value` varchar(254) DEFAULT '0',
        PRIMARY KEY (`id_pixelgoogle`)
        ) DEFAULT CHARSET=utf8;";

        foreach ($statements as $statement)
        {
            if (!Db::getInstance()->Execute($statement))
            {
                return false;
            }
        }
        return true;
    }

    private function InDelMenu($what, $controller, $name = null)
    {
        if ($what == 'install')
        {
            $tab = new Tab();
            $tab->class_name = $controller;
            $tab->id_parent = Tab::getIdFromClassName('AdminCatalog');
            $tab->module = $this->name;
            $languages = Language::getLanguages(false);
            foreach ($languages as $lang)
            {
                $tab->name[$lang['id_lang']] = $name;
            }
            if ($tab->save())
            {
                return true;
            }
        }
        elseif ($what == 'uninstall')
        {
            $tab = new Tab(Tab::getIdFromClassName($controller));
            if ($tab->delete())
            {
                return true;
            }
        }
        return true;
    }

    public function _postProcess()
    {
        if (Tools::isSubmit('submitgmfeedSettings'))
        {
            Configuration::updateValue('pf_tree', Tools::getvalue('pf_tree', 0));
            $this->context->controller->confirmations[] = $this->l('Settings saved properly');
        }
    }

    public function getContent()
    {
        $this->context->controller->addJqueryPlugin('fancybox');
        $this->_postProcess();
        $tree = array(
            array(
                'id_option' => '0',
                'name' => $this->l('No')
            ),
            array(
                'id_option' => '1',
                'name' => $this->l('Yes')
            ),
        );


        $inputs = array(
            array(
                'type' => 'select',
                'label' => $this->l('Show categories tree'),
                'name' => 'pf_tree',
                'class' => 't pf_tree',
                'desc' => '<div class="alert alert-info">'.$this->l('Section where you can generate products feed is available under: ').'<a href="'.$this->context->link->getAdminLink('AdminExportProductsFeedGoogle', true).'">'.$this->l('Catalog > Google Merchant Feed').'</a><br/>'
                    . $this->l('You can pair there your shop categories with "google shopping" categories. When you will turn this option on, module will display parents of category you want to pair, so in effect you will distinct categories easily especially if you have many categories with the same name')
                    . ' [<a href="https://i.imgur.com/enzXRaJ.png" class="fancybox">' . $this->l('See screenshot') . '</a>]'
                    .'</div>',
                'options' => array(
                    'query' => $tree,
                    'id' => 'id_option',
                    'name' => 'name'
                ),
            ),
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Module settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => $inputs,
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;

        $helper->default_form_language = Context::getContext()->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = 'pf_tree_id';
        $helper->submit_action = 'submitgmfeedSettings';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form)).$this->checkforupdates(0, 1)."<script> $(document).ready(function(){
        $('.fancybox').fancybox();
        }); </script>";
    }

    public function getConfigFieldsValues()
    {
        return array(
            'pf_tree' => (int)Configuration::get('pf_tree'),
        );
    }

    public function uninstall()
    {
        $this->InDelMenu('uninstall', 'AdminExportProductsFeedGoogle');
        return parent::uninstall();
    }

    public function inconsistency($ret)
    {
        return;
    }

    public function psversion()
    {
        $version = _PS_VERSION_;
        $exp = $explode = explode(".", $version);
        return $exp[1];
    }

}

class gmfeedUpdate extends gmfeed
{
    public static function _isCurl()
    {
        return function_exists('curl_version');
    }

    public static function version($version)
    {
        $version = (int)str_replace(".", "", $version);
        if (strlen($version) == 3)
        {
            $version = (int)$version . "0";
        }
        if (strlen($version) == 2)
        {
            $version = (int)$version . "00";
        }
        if (strlen($version) == 1)
        {
            $version = (int)$version . "000";
        }
        if (strlen($version) == 0)
        {
            $version = (int)$version . "0000";
        }
        return (int)$version;
    }

    public static function verify($module, $key)
    {
        if (ini_get("allow_url_fopen"))
        {
            if (function_exists("file_get_contents"))
            {
                $actual_version = @file_get_contents('http://dev.mypresta.eu/update/get.php?module=' . $module . "&lic=$key&u=" . self::encrypt(_PS_BASE_URL_ . __PS_BASE_URI__));
            }
        }
        Configuration::updateValue("update_" . $module, date("U"));
        Configuration::updateValue("updatev_" . $module, $actual_version);
        return $actual_version;
    }

    public static function encrypt($string)
    {
        return base64_encode($string);
    }
}