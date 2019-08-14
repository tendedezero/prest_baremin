<?php
/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2018 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */
class hotinfo extends Module
{
    function __construct()
    {
        $this->name = 'hotinfo';
        $this->tab = 'front_office_features';
        $this->author = 'MyPresta.eu';
        $this->mypresta_link = 'https://mypresta.eu/modules/front-office-features/hot-info-bottom-sidebar.html';
        $this->version = '1.7.2';
        $this->displayName = $this->l('HotInfo');
        $this->description = $this->l('Create personalized bottom sidebar with important informations for your customers');
        parent::__construct();
        $this->checkforupdates(0, 0);
    }

    public function checkforupdates($display_msg = 0, $form = 0)
    {
        // ---------- //
        // ---------- //
        // VERSION 12 //
        // ---------- //
        // ---------- //
        $this->mkey = "nlc";
        if (@file_exists('../modules/' . $this->name . '/key.php'))
        {
            @require_once('../modules/' . $this->name . '/key.php');
        }
        else
        {
            if (@file_exists(dirname(__file__) . $this->name . '/key.php'))
            {
                @require_once(dirname(__file__) . $this->name . '/key.php');
            }
            else
            {
                if (@file_exists('modules/' . $this->name . '/key.php'))
                {
                    @require_once('modules/' . $this->name . '/key.php');
                }
            }
        }

        if ($form == 1)
        {
            return '
            <div class="panel" id="fieldset_myprestaupdates" style="margin-top:20px;">
            ' . ($this->psversion() == 6 || $this->psversion() == 7 ? '<div class="panel-heading"><i class="icon-wrench"></i> ' . $this->l('MyPresta updates') . '</div>' : '') . '
			<div class="form-wrapper" style="padding:0px!important;">
            <div id="module_block_settings">
                    <fieldset id="fieldset_modu\le_block_settings">
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
        }
        else
        {
            if (defined('_PS_ADMIN_DIR_'))
            {
                if (Tools::isSubmit('submit_settings_updates'))
                {
                    Configuration::updateValue('mypresta_updates', Tools::getValue('mypresta_updates'));
                }
                if (Configuration::get('mypresta_updates') != 0 || (bool)Configuration::get('mypresta_updates') == false)
                {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200))
                    {
                        $actual_version = hotinfoUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (hotinfoUpdate::version($this->version) < hotinfoUpdate::version(Configuration::get('updatev_' . $this->name)))
                    {
                        $this->warning = $this->l('New version available, check http://MyPresta.eu for more informations');
                    }
                }
                if ($display_msg == 1)
                {
                    if (hotinfoUpdate::version($this->version) < hotinfoUpdate::version(hotinfoUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version)))
                    {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    }
                    else
                    {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }

    public function updatesForm()
    {
        return '
                <div id="module_block_settings">
                    <fieldset id="fieldset_module_block_settings">
                        <legend><img src="' . $this->_path . 'logo.gif" alt="" title="" />' . $this->l('MyPresta updates') . '</legend>
                        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                            <label>' . $this->l('Check updates') . '</label>
                            <div class="margin-form">' . (Tools::isSubmit('submit_settings_updates_now') ? $this->checkforupdates(1) : '') . '
                                <input style="margin: 0px; top: -3px; position: relative;" type="submit" name="submit_settings_updates_now" value="' . $this->l('Check now') . '" class="button" />
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
                            
                            <center><input type="submit" name="submit_settings_updates" value="' . $this->l('Save Settings') . '" class="button" /></center>
                        </form>
                    </fieldset>
                </div>';

    }


    function trusted()
    {
        if (defined('_PS_ADMIN_DIR_'))
        {
            if (_PS_VERSION_ >= "1.6.0.8")
            {
                if (isset($_GET['controller']))
                {
                    if ($_GET['controller'] == "AdminModules")
                    {
                        if (_PS_VERSION_ >= "1.6.0.8")
                        {
                            if (isset($_GET['controller']))
                            {
                                if ($_GET['controller'] == "AdminModules")
                                {
                                    $this->context->controller->addJS(($this->_path) . 'trusted.js', 'all');
                                }
                            }
                        }
                    }
                }
            }
            if (defined('_PS_HOST_MODE_'))
            {
                if (isset($_GET['controller']))
                {
                    if ($_GET['controller'] == "AdminModules")
                    {
                        if (defined('self::CACHE_FILE_TRUSTED_MODULES_LIST') == true)
                        {
                            $context = Context::getContext();
                            $theme = new Theme($context->shop->id_theme);
                            $xml = simplexml_load_string(file_get_contents(_PS_ROOT_DIR_ . self::CACHE_FILE_TRUSTED_MODULES_LIST));
                            if ($xml)
                            {
                                $css = $xml->modules->addChild('module');
                                $css->addAttribute('name', $this->name);
                                $xmlcode = $xml->asXML();
                                if (!strpos(file_get_contents(_PS_ROOT_DIR_ . self::CACHE_FILE_TRUSTED_MODULES_LIST), $this->name))
                                {
                                    if (file_exists(_PS_ROOT_DIR_ . self::CACHE_FILE_TRUSTED_MODULES_LIST))
                                    {
                                        file_put_contents(_PS_ROOT_DIR_ . self::CACHE_FILE_TRUSTED_MODULES_LIST, $xmlcode);
                                    }
                                }
                            }
                        }
                        if (defined('self::CACHE_FILE_DEFAULT_COUNTRY_MODULES_LIST') == true)
                        {
                            $xml = simplexml_load_string(file_get_contents(_PS_ROOT_DIR_ . self::CACHE_FILE_DEFAULT_COUNTRY_MODULES_LIST));
                            //$xml=new SimpleXMLElement('<modules/>');
                            //$cs=$xml->addChild('modules');
                            if ($xml)
                            {
                                $css = $xml->addChild('module');
                                $css->addChild('id', 0);
                                $css->addChild('name', "<![CDATA[" . $this->name . "]]>");
                                $xmlcode = $xml->asXML();
                                $xmlcode = str_replace('&lt;', "<", $xmlcode);
                                $xmlcode = str_replace('&gt;', ">", $xmlcode);
                                if (!strpos(file_get_contents(_PS_ROOT_DIR_ . self::CACHE_FILE_DEFAULT_COUNTRY_MODULES_LIST), $this->name))
                                {
                                    if (file_exists(_PS_ROOT_DIR_ . self::CACHE_FILE_DEFAULT_COUNTRY_MODULES_LIST))
                                    {
                                        file_put_contents(_PS_ROOT_DIR_ . self::CACHE_FILE_DEFAULT_COUNTRY_MODULES_LIST, $xmlcode);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    function install()
    {
        if (parent::install() == false OR $this->registerHook('header') == false OR !Configuration::updateValue('update_' . $this->name, '0') OR Configuration::updateValue('HotInfo_body', 'Enter here your message to your customers') == false OR Configuration::updateValue('HotInfo_bg_color', 'FFFFFF') == false OR Configuration::updateValue('HotInfo_br_color', 'c0c0c0') == false OR Configuration::updateValue('HotInfo_br_size', '1') == false OR Configuration::updateValue('HotInfo_height', '50') == false)
        {
            return false;
        }
        return true;
    }

    public function psversion()
    {
        $version = _PS_VERSION_;
        $exp = $explode = explode(".", $version);
        return $exp[1];
    }

    public function getconf()
    {
        $array = new StdClass();
        $array->body = Configuration::get('HotInfo_body');
        $array->height = Configuration::get('HotInfo_height');
        $array->bg_color = Configuration::get('HotInfo_bg_color');
        $array->br_color = Configuration::get('HotInfo_br_color');
        $array->br_size = Configuration::get('HotInfo_br_size');

        return $array;
    }

    public function getContent()
    {
        $output = '<h2>' . $this->displayName . '</h2>';
        if (Tools::isSubmit('submithotinfo'))
        {
            Configuration::updateValue('HotInfo_body', Tools::getValue('HotInfo_body'), true);
            Configuration::updateValue('HotInfo_bg_color', Tools::getValue('HotInfo_bg_color'), true);
            Configuration::updateValue('HotInfo_br_color', Tools::getValue('HotInfo_br_color'), true);
            Configuration::updateValue('HotInfo_br_size', Tools::getValue('HotInfo_br_size'), true);
            Configuration::updateValue('HotInfo_height', Tools::getValue('HotInfo_height'), true);


            $output .= '<div class="bootstrap"><div class="conf confirm alert alert-success">' . $this->l('Settings updated') . '</div></div>';
        }
        return $output . $this->displayForm();
    }

    public function displayForm()
    {
        $languages = Language::getLanguages(true);
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        global $cookie;
        $iso = Language::getIsoById((int)($cookie->id_lang));
        $isoTinyMCE = (file_exists(_PS_ROOT_DIR_ . '/js/tiny_mce/langs/' . $iso . '.js') ? $iso : 'en');
        $ad = dirname($_SERVER["PHP_SELF"]);

        $form = '
			<script type="text/javascript" src="' . __PS_BASE_URI__ . 'js/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript">
			var iso = \'' . $isoTinyMCE . '\' ;
			var pathCSS = \'' . _THEME_CSS_DIR_ . '\' ;
			var ad = \'' . $ad . '\' ;';
        if ($this->psversion() == 5)
        {
            $form .= '$(document).ready(function(){
				tinySetup({
				    valid_children : "+body[style]",
					entity_encoding: "raw",
					editor_selector :"rte",
					
					});
				});';
        }
        else
        {
            $form .= '$(document).ready(function(){
			
				});';
        }

        $form .= '
			function addClass(id){
				tinyMCE.execCommand(\'mceRemoveControl\', false, id);
				tinyMCE.execCommand(\'mceAddControl\', true, id);
			}
			
			function removeClass(id){
				tinyMCE.execCommand(\'mceRemoveControl\', false, id);
			}			
		
			</script><script type="text/javascript" src="../modules/hotinfo/tinymce16.js"></script>';

        return $form . '
		<div style="diplay:block; clear:both; margin-bottom:20px;">
		<iframe src="//apps.facepages.eu/somestuff/whatsgoingon.html" width="100%" height="150" border="0" style="border:none;"></iframe>
		</div>        
        <script type="text/javascript" src="../modules/hotinfo/jscolor/jscolor.js"></script>
		<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
			<fieldset>
				<legend><img src="' . $this->_path . 'logo.gif" alt="" title="" />' . $this->l('Settings') . '</legend>
				<label>' . $this->l('Message') . '</label>
					<div class="margin-form">
						<textarea type="text" name="HotInfo_body" class="rte rtepro">' . Configuration::get('HotInfo_body') . '</textarea>
						<p class="clear">' . $this->l('Enter here your message') . '</p>
					</div>
                    <label>' . $this->l('Sidebar settings') . '</label>
                    <div class="margin-form">
						<input type="text"  name="HotInfo_height" value="' . Configuration::get('HotInfo_height') . '"/>
						<p class="clear">' . $this->l('Sidebar height') . '</p>
					</div>                    
					<div class="margin-form">
						<input type="text" class="color" name="HotInfo_bg_color" value="' . Configuration::get('HotInfo_bg_color') . '"/>
						<p class="clear">' . $this->l('Sidebar background color') . '</p>
					</div>    
					<div class="margin-form">
						<input type="text" class="color" name="HotInfo_br_color" value="' . Configuration::get('HotInfo_br_color') . '"/>
						<p class="clear">' . $this->l('Sidebar border color') . '</p>
					</div>
					<div class="margin-form">
						<input type="text" name="HotInfo_br_size" value="' . Configuration::get('HotInfo_br_size') . '"/>
						<p class="clear">' . $this->l('Sidebar border size') . '</p>
					</div>                                                                            		
				<center><input type="submit" name="submithotinfo" value="' . $this->l('Save') . '" class="button" /></center>
			</fieldset>
		</form>' . $this->updatesForm();
    }

    function hookheader($params)
    {
        $array = $this->getconf();
        $this->context->controller->addCSS($this->_path . 'css/hotinfo.css', 'all');
        $this->context->smarty->assign('array', $array);
        return $this->display(__FILE__, 'hotinfo.tpl');
    }
}

class hotinfoUpdate extends hotinfo
{
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

    public static function encrypt($string)
    {
        return base64_encode($string);
    }

    public static function verify($module, $key, $version)
    {
        if (ini_get("allow_url_fopen"))
        {
            if (function_exists("file_get_contents"))
            {
                $actual_version = @file_get_contents('http://dev.mypresta.eu/update/get.php?module=' . $module . "&version=" . self::encrypt($version) . "&lic=$key&u=" . self::encrypt(_PS_BASE_URL_ . __PS_BASE_URI__));
            }
        }
        Configuration::updateValue("update_" . $module, date("U"));
        Configuration::updateValue("updatev_" . $module, $actual_version);
        return $actual_version;
    }
}

?>