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

if ( ! defined('_PS_VERSION_')) {
    exit;
}

class send2friend extends Module
{
    private $_html = '';
    private $_postErrors = array();
    public $context;

    function __construct($dontTranslate = false)
    {
        $this->name          = 'send2friend';
        $this->version       = '1.3.2';
        $this->author        = 'MyPresta.eu';
        $this->tab           = 'front_office_features';
        $this->mypresta_link = 'https://mypresta.eu/modules/advertising-and-marketing/send-to-a-friend.html';
        $this->need_instance = 0;
        $this->bootstrap     = 1;
        $this->secure_key    = Tools::encrypt($this->name);

        parent::__construct();

        if ( ! $dontTranslate) {
            $this->displayName = $this->l('Send to a Friend module');
            $this->description = $this->l('Allows customers to send a product link to a friend.');
        }
        $this->ps_versions_compliancy = array(
            'min' => '1.7',
            'max' => '2.0'
        );
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
                        $actual_version = send2friendUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (send2friendUpdate::version($this->version) < send2friendUpdate::version(Configuration::get('updatev_' . $this->name)) && Tools::getValue('ajax','false') == 'false') {
                        $this->context->controller->warnings[] = '<strong>' . $this->displayName . '</strong>: ' . $this->l('New version available, check http://MyPresta.eu for more informations') . ' <a href="' . $this->mypresta_link . '">' . $this->l('More details in changelog') . '</a>';
                        $this->warning                         = $this->context->controller->warnings[0];
                    }
                } else {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = send2friendUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                }
                if ($display_msg == 1) {
                    if (send2friendUpdate::version($this->version) < send2friendUpdate::version(send2friendUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version))) {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    } else {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }


    public function inconsistency($ret = false)
    {
        return;
    }

    public function install()
    {
        return (parent::install() &&
                $this->registerHook('displayProductAdditionalInfo') &&
                $this->registerHook('actionAdminControllerSetMedia') &&
                $this->installMailTranslation() &&
                $this->registerHook('displayHeader'));
    }

    function installMailTranslation(){
        $title = array();
        foreach(Language::getLanguages(true) AS $value) {
            $title[$value['id_lang']] = '%1$s sent you a link to %2$s';
        }
        Configuration::updateValue('SEND2FRIEND_TITLE', $title);
        return true;
    }

    public function uninstall()
    {
        return (parent::uninstall());
    }

    public function hookdisplayProductAdditionalInfo($params)
    {
        /* Product informations */
        $product = new Product((int)Tools::getValue('id_product'), false, $this->context->language->id);
        $image   = Product::getCover((int)$product->id);

        $this->context->smarty->assign(array(
            'link'                => $this->context->link,
            'stf_product'         => $product,
            'stf_product_cover'   => (int)$product->id . '-' . (int)$image['id_image'],
            'SEND2FRIEND_GDPR'    => (int)Configuration::get('SEND2FRIEND_GDPR'),
            'SEND2FRIEND_GDPRCMS' => (int)Configuration::get('SEND2FRIEND_GDPRCMS'),
        ));

        return $this->display(__FILE__, 'views/send2friend-extra.tpl');
    }

    public function hookdisplayHeader($params)
    {
        $this->page_name = Dispatcher::getInstance()->getController();
        if ($this->page_name == 'product') {
            $this->context->controller->addCSS($this->_path . 'views/send2friend.css', 'all');
            $this->context->controller->addJS($this->_path . 'views/send2friend.js');
            $this->context->controller->addJqueryPlugin('fancybox');
            Media::addJsDef(array(
                'send2friend_confirmation'     => $this->l('Your e-mail has been sent successfully'),
                'send2friend_problem'          => $this->l('Your e-mail could not be sent. Please check the name and e-mail address and try again.'),
                'send2friend_title'            => $this->l('Send to a friend'),
                'send2friend_id_lang'          => Context::getContext()->language->id,
                'send2friend_missingFields'    => $this->l('You did not fill required fields'),
                'send2friend_SEND2FRIEND_GDPR' => (int)Configuration::get('SEND2FRIEND_GDPR'),
                'send2friend_secureKey'        => $this->secure_key
            ));
        }
    }

    public function _postProcess()
    {
        if (Tools::isSubmit('submitSettings')) {
            Configuration::updateValue('SEND2FRIEND_GDPRCMS', (int)Tools::getValue('SEND2FRIEND_GDPRCMS'));
            Configuration::updateValue('SEND2FRIEND_GDPR', (int)Tools::getValue('SEND2FRIEND_GDPR'));

            var_Dump(Configuration::get('SEND2FRIEND_GDPRCMS'));
            $title = array();
            foreach(Language::getLanguages(true) AS $value) {
                $title[$value['id_lang']] = Tools::getValue('SEND2FRIEND_TITLE_'.$value['id_lang']);
            }
            Configuration::updateValue('SEND2FRIEND_TITLE', $title);

            $this->context->controller->confirmations[] = $this->l('settings updated');
        }
    }

    public function psversion($part = 1)
    {
        $version = _PS_VERSION_;
        $exp     = $explode = explode(".", $version);
        if ($part == 1) {
            return $exp[1];
        }
        if ($part == 2) {
            return $exp[2];
        }
        if ($part == 3) {
            return $exp[3];
        }
        if ($part == 4) {
            return $exp[3];
        }
    }

    public function advert()
    {
        return '<div class="panel">
                <h3><i class="icon-wrench"></i> ' . $this->l('I am developing this module for free - if you want') . ' <a class="btn button label label-danger" target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7WE8PTH4ZPYZA">' . $this->l('send me a donation') . '</span></a></h3>
                <iframe src="//apps.facepages.eu/somestuff/whatsgoingon.html" width="100%" height="150" border="0" style="border:none;"></iframe>
            </div>';
    }

    public function getContent()
    {
        $this->_postProcess();

        return $this->advert() . $this->renderConfigForm() . $this->checkforupdates(0, 1);
    }

    public function renderConfigForm()
    {
        $fields_form_1 = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuration'),
                    'icon'  => 'icon-cogs',
                ),
                'input'  => array(
                    array(
                        'type'    => 'switch',
                        'is_bool' => true,
                        'label'   => $this->l('GDPR Compliant'),
                        'name'    => 'SEND2FRIEND_GDPR',
                        'values'  => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type'    => 'select',
                        'label'   => $this->l('Privacy policy page'),
                        'desc'    => $this->l('Choose a CMS page with privacy policy details for GDPR purposes'),
                        'name'    => 'SEND2FRIEND_GDPRCMS',
                        'class'   => 't',
                        'options' => array(
                            'query' => CMS::getCmsPages($this->context->language->id, null, false),
                            'id'    => 'id_cms',
                            'name'  => 'meta_title'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Email title'),
                        'name' => 'SEND2FRIEND_TITLE',
                        'lang' => true,
                        'desc' => $this->l('Module will use this as a title of an email that is sent to friend') .
                            '<br/>' .
                            $this->l('%1$s - will be replaced with logged customer name') .
                            '<br/>' .
                            $this->l('%2$s - will be replaced with product name'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name'  => 'submitSettings',
                ),
            ),
        );

        $helper                           = new HelperForm();
        $helper->show_toolbar             = false;
        $helper->default_form_language    = $this->context->language->id;
        $helper->module                   = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->submit_action            = 'submitSend2FriendConfiguration';
        $helper->currentIndex             = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token                    = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars                 = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form_1));
    }


    public function getConfigFieldsValues()
    {
        $title = array();
        foreach (Language::getLanguages(true) AS $value) {
            $title[$value['id_lang']] = Configuration::get('SEND2FRIEND_TITLE', $value['id_lang']);
        }

        return array(
            'SEND2FRIEND_GDPRCMS' => Tools::getValue('SEND2FRIEND_GDPRCMS', Configuration::get('SEND2FRIEND_GDPRCMS')),
            'SEND2FRIEND_GDPR'    => Tools::getValue('SEND2FRIEND_GDPR', Configuration::get('SEND2FRIEND_GDPR')),
            'SEND2FRIEND_TITLE' => $title,
        );
    }

    public function isValidName($name)
    {
        $isName          = Validate::isName($name);
        $isShortName     = $this->isShortName($name);
        $isNameLikeAnUrl = $this->isNameLikeAnUrl($name);
        $isValidName     = $isName && $isShortName && ! $isNameLikeAnUrl;

        return $isValidName;
    }

    public function isShortName($name)
    {
        $isShortName = (strlen($name) <= 50);

        return $isShortName;
    }

    public function isNameLikeAnUrl($name)
    {
        $regex           = "/(https?:[\/]*.*)|([\.]*[[[:alnum:]]+\.[^ ]]*.*)/m";
        $isNameLikeAnUrl = (bool)preg_match_all($regex, $name);

        return $isNameLikeAnUrl;
    }
}


class send2friendUpdate extends send2friend
{
    public static function _isCurl()
    {
        return function_exists('curl_version');
    }

    public static function version($version)
    {
        $version = (int)str_replace(".", "", $version);
        if (strlen($version) == 3) {
            $version = (int)$version . "0";
        }
        if (strlen($version) == 2) {
            $version = (int)$version . "00";
        }
        if (strlen($version) == 1) {
            $version = (int)$version . "000";
        }
        if (strlen($version) == 0) {
            $version = (int)$version . "0000";
        }

        return (int)$version;
    }

    public static function verify($module, $key)
    {
        if (ini_get("allow_url_fopen")) {
            if (function_exists("file_get_contents")) {
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