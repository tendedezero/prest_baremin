<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'angarcmsdesc/classes/InfoBlock3.php';

class Angarcmsdesc extends Module
{
    public $html = '';

    public function __construct()
    {
        $this->name = 'angarcmsdesc';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'AngarThemes';
        $this->bootstrap = true;
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('AT - About our store');
        $this->description = $this->l('Adds custom information blocks in your store.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:angarcmsdesc/views/templates/hook/angarcmsdesc.tpl';
    }

    public function install()
    {
        return    parent::install() &&
                $this->installDB() &&
                $this->registerHook('displayHeader') &&
                $this->registerHook('angarCmsDesc') &&
                $this->installFixtures();
    }

    public function installDB()
    {
        $return = true;
        $return &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'info3` (
				`id_info3` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned DEFAULT NULL,
				PRIMARY KEY (`id_info3`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

        $return &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'info3_lang` (
				`id_info3` INT UNSIGNED NOT NULL,
				`id_lang` int(10) unsigned NOT NULL ,
				`text` text NOT NULL,
				PRIMARY KEY (`id_info3`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

        return $return;
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDB();
    }

    public function uninstallDB($drop_table = true)
    {
        $ret = true;
        if ($drop_table) {
            $ret &=  Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'info3`') && Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'info3_lang`');
        }

        return $ret;
    }

    public function getContent()
    {
        $id_info3 = (int)Tools::getValue('id_info3');

        if (Tools::isSubmit('saveangarcmsdesc')) {
            if (!Tools::getValue('text_'.(int)Configuration::get('PS_LANG_DEFAULT'), false)) {
                return $this->html . $this->displayError($this->l('You must fill in all fields.')) . $this->renderForm();
            } elseif ($this->processSaveCmsInfo()) {
                return $this->html . $this->renderList();
            } else {
                return $this->html . $this->renderForm();
            }
        } elseif (Tools::isSubmit('updateangarcmsdesc') || Tools::isSubmit('addangarcmsdesc')) {
            $this->html .= $this->renderForm();
            return $this->html;
        } elseif (Tools::isSubmit('deleteangarcmsdesc')) {
            $info3 = new InfoBlock3((int)$id_info3);
            $info3->delete();
            $this->_clearCache($this->templateFile);
            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } else {
            $this->html .= $this->renderList();
            return $this->html;
        }
    }

    public function processSaveCmsInfo()
    {
        if ($id_info3 = Tools::getValue('id_info3')) {
            $info3 = new InfoBlock3((int)$id_info3);
        } else {
            $info3 = new InfoBlock3();
            if (Shop::isFeatureActive()) {
                $shop_ids = Tools::getValue('checkBoxShopAsso_configuration');
                if (!$shop_ids) {
                    $this->html .= '<div class="alert alert-danger conf error">'.$this->l('You have to select at least one shop.').'</div>';
                    return false;
                }
            } else {
                $info3->id_shop = Shop::getContextShopID();
            }
        }

        $languages = Language::getLanguages(false);
        $text = array();
        foreach ($languages as $lang) {
            $text[$lang['id_lang']] = Tools::getValue('text_'.$lang['id_lang']);
        }
        $info3->text = $text;

        if (Shop::isFeatureActive() && !$info3->id_shop) {
            $saved = true;
            foreach ($shop_ids as $id_shop) {
                $info3->id_shop = $id_shop;
                $saved &= $info3->add();
            }
        } else {
            $saved = $info3->save();
        }

        if ($saved) {
            $this->_clearCache($this->templateFile);
        } else {
            $this->html .= '<div class="alert alert-danger conf error">'.$this->l('An error occurred while attempting to save.').'</div>';
        }

        return $saved;
    }


    protected function renderForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('New custom CMS block'),
            ),
            'input' => array(
                'id_info3' => array(
                    'type' => 'hidden',
                    'name' => 'id_info3'
                ),
                'content' => array(
                    'type' => 'textarea',
                    'label' => $this->l('Text'),
                    'lang' => true,
                    'name' => 'text',
                    'cols' => 40,
                    'rows' => 10,
                    'class' => 'rte',
                    'autoload_rte' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            ),
            'buttons' => array(
                array(
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
                    'title' => $this->l('Back to list'),
                    'icon' => 'process-icon-back'
                )
            )
        );

        if (Shop::isFeatureActive() && Tools::getValue('id_info3') == false) {
            $fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association'),
                'name' => 'checkBoxShopAsso_theme'
            );
        }


        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'angarcmsdesc';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );
        }

        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'saveangarcmsdesc';

        $helper->fields_value = $this->getFormValues();

        return $helper->generateForm(array(array('form' => $fields_form)));
    }

    protected function renderList()
    {
        $this->fields_list = array();
        $this->fields_list['id_info3'] = array(
                'title' => $this->l('Block ID'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            );

        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
            $this->fields_list['shop_name'] = array(
                    'title' => $this->l('Shop'),
                    'type' => 'text',
                    'search' => false,
                    'orderby' => false,
                );
        }

        $this->fields_list['text'] = array(
                'title' => $this->l('Block text'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'id_info3';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->imageType = 'jpg';
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Add new')
        );

        $helper->title = $this->displayName;
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        $content = $this->getListContent($this->context->language->id);

        return $helper->generateList($content, $this->fields_list);
    }

    protected function getListContent($id_lang = null)
    {
        if (is_null($id_lang)) {
            $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        }

        $sql = 'SELECT r.`id_info3`, rl.`text`, s.`name` as shop_name
			FROM `'._DB_PREFIX_.'info3` r
			LEFT JOIN `'._DB_PREFIX_.'info3_lang` rl ON (r.`id_info3` = rl.`id_info3`)
			LEFT JOIN `'._DB_PREFIX_.'shop` s ON (r.`id_shop` = s.`id_shop`)
			WHERE `id_lang` = '.(int)$id_lang.' AND (';

        if ($shop_ids = Shop::getContextListShopID()) {
            foreach ($shop_ids as $id_shop) {
                $sql .= ' r.`id_shop` = '.(int)$id_shop.' OR ';
            }
        }

        $sql .= ' r.`id_shop` = 0 )';

        $content = Db::getInstance()->executeS($sql);

        foreach ($content as $key => $value) {
            $content[$key]['text'] = Tools::substr(strip_tags($value['text']), 0, 200);
        }

        return $content;
    }

    public function getFormValues()
    {
        $fields_value = array();
        $id_info3 = (int)Tools::getValue('id_info3');

        foreach (Language::getLanguages(false) as $lang) {
            if ($id_info3) {
                $info3 = new InfoBlock3((int)$id_info3);
                $fields_value['text'][(int)$lang['id_lang']] = $info3->text[(int)$lang['id_lang']];
            } else {
                $fields_value['text'][(int)$lang['id_lang']] = Tools::getValue('text_'.(int)$lang['id_lang'], '');
            }
        }

        $fields_value['id_info3'] = $id_info3;

        return $fields_value;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCss($this->_path.'views/css/angarcmsdesc.css', 'all');
    }

    public function hookAngarCmsDesc($params)
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('angarcmsdesc'))) {
            $infos = $this->getInfos($this->context->language->id, $this->context->shop->id);
            $this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
        }

        return $this->display(__FILE__, 'views/templates/front/angarcmsdesc.tpl', $this->getCacheId('angarcmsdesc'));
    }

    public function getInfos($id_lang, $id_shop)
    {
        $sql = 'SELECT r.`id_info3`, r.`id_shop`, rl.`text`
			FROM `'._DB_PREFIX_.'info3` r
			LEFT JOIN `'._DB_PREFIX_.'info3_lang` rl ON (r.`id_info3` = rl.`id_info3`)
			WHERE `id_lang` = '.(int)$id_lang.' AND  `id_shop` = '.(int)$id_shop;

        return Db::getInstance()->executeS($sql);
    }

    public function installFixtures()
    {
        $return = true;
        $tab_texts = array(

        array(
        'text' => '
	    <h3>ABOUT OUR STORE</h3>
	    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce non tellus vitae arcu porttitor mattis. Nullam eget pharetra neque. Duis blandit urna nec sapien ultrices, eget luctus velit faucibus. Nunc consequat tellus id est venenatis blandit. Nunc commodo nibh in condimentum pulvinar. Duis erat nibh, aliquet at malesuada quis, ullamcorper id ante. Duis facilisis, massa non blandit porttitor, ipsum ipsum elementum dui, non imperdiet purus mauris sit amet mi.</p>
		<p>Maecenas fermentum leo metus, sed molestie erat lacinia finibus. Nullam eget nibh a metus interdum sollicitudin sed tincidunt justo. Fusce ut nisl nisl. In lobortis vel erat in luctus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In malesuada sapien vel diam scelerisque blandit. Fusce vel mi ligula. Aenean lacus enim, tristique nec laoreet pretium, convallis a neque. Aenean dignissim ut nunc ut pulvinar.</p>
	    '
        ),

        );

        $shops_ids = Shop::getShops(true, null, true);
        $return = true;
        foreach ($tab_texts as $tab) {
            $info3 = new InfoBlock3();
            foreach (Language::getLanguages(false) as $lang) {
                $info3->text[$lang['id_lang']] = $tab['text'];
            }
            foreach ($shops_ids as $id_shop) {
                $info3->id_shop = $id_shop;
                $return &= $info3->add();
            }
        }

        return $return;
    }
}
