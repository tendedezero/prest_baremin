<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class AngarBanners extends Module
{
    protected $max_image_size = 1048576;
    protected $default_language;
    protected $languages;

    public function __construct()
    {
        $this->name = 'angarbanners';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->bootstrap = true;
        $this->secure_key = Tools::encrypt($this->name);
        $this->default_language = Language::getLanguage(Configuration::get('PS_LANG_DEFAULT'));
        $this->languages = Language::getLanguages();
        $this->author = 'AngarThemes';
        parent::__construct();
        $this->displayName = $this->l('AT - Banners');
        $this->description = $this->l('Add banners to your shop.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->module_path = _PS_MODULE_DIR_.$this->name.'/';
        $this->uploads_path = _PS_MODULE_DIR_.$this->name.'/views/img/';
        $this->admin_tpl_path = _PS_MODULE_DIR_.$this->name.'/views/templates/admin/';
        $this->hooks_tpl_path = _PS_MODULE_DIR_.$this->name.'/views/templates/hooks/';
    }

    public function createAjaxController()
    {
        $tab = new Tab();
        $tab->active = 1;
        $languages = Language::getLanguages(false);
        if (is_array($languages)) {
            foreach ($languages as $language) {
                $tab->name[$language['id_lang']] = 'angarbanners';
            }
        }
        $tab->class_name = 'AdminAngarBanners';
        $tab->module = $this->name;
        $tab->id_parent = - 1;
        return (bool)$tab->add();
    }

    private function _removeAjaxContoller()
    {
        if ($tab_id = (int)Tab::getIdFromClassName('AdminAngarBanners')) {
            $tab = new Tab($tab_id);
            $tab->delete();
        }
        return true;
    }

    public function install()
    {
        if (!parent::install()
            || !$this->installDB()
            || !$this->installFixtures(Language::getLanguages(true)) ||
            !$this->registerHook('displayHeader') ||
            !$this->registerHook('displayTopColumn') ||
            !$this->registerHook('displayLeftColumn') ||
            !$this->registerHook('displayRightColumn') ||
            !$this->registerHook('displayHome') ||
            !$this->registerHook('angarBannersBottom') ||
            !$this->registerHook('displayFooterAfter') ||
            !$this->registerHook('displayBackOfficeHeader') ||
            !$this->registerHook('actionObjectLanguageAddAfter') ||
            !$this->createAjaxController()
        ) {
            return false;
        }

        return true;
    }

    private function installDB()
    {
        return (
            Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'angarbanners`') &&
            Db::getInstance()->Execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'angarbanners` (
					`id_item` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`id_shop` int(10) unsigned NOT NULL,
					`id_lang` int(10) unsigned NOT NULL,
					`item_order` int(10) unsigned NOT NULL,
					`title` VARCHAR(100),
					`title_use` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					`hook` VARCHAR(100),
					`url` TEXT,
					`target` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
					`image` VARCHAR(100),
					`image_w` VARCHAR(10),
					`image_h` VARCHAR(10),
					`html` TEXT,
					`active` tinyint(1) unsigned NOT NULL DEFAULT \'1\',
					PRIMARY KEY (`id_item`)
			) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;')
        );
    }

    protected function installFixture($hook, $id_image, $id_shop, $id_lang)
    {
        $result = true;

        $sizes = @getimagesize((dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'banner-img'.(int)$id_image.'.jpg'));
        $width = (isset($sizes[0]) && $sizes[0])? (int)$sizes[0] : 0;
        $height = (isset($sizes[1]) && $sizes[1])? (int)$sizes[1] : 0;

        $result &= Db::getInstance()->Execute('
			INSERT INTO `'._DB_PREFIX_.'angarbanners` (
					`id_shop`, `id_lang`, `item_order`, `title`, `title_use`, `hook`, `url`, `target`, `image`, `image_w`, `image_h`, `html`, `active`
			) VALUES (
				\''.(int)$id_shop.'\',
				\''.(int)$id_lang.'\',
				\''.(int)$id_image.'\',
				\'\',
				\'0\',
				\''.pSQL($hook).'\',
				\'\',
				\'0\',
				\'banner-img'.(int)$id_image.'.jpg\',
				'.$width.',
				'.$height.',
				\'\',
				1)
			');

        return $result;
    }

    public function installFixtures($languages = null)
    {
        $result = true;

        if ($languages === null) {
            $languages = Language::getLanguages(true);
        }

        foreach ($languages as $language) {
            for ($i = 1; $i < 4; $i++) {
                $result &= $this->installFixture('top', $i, $this->context->shop->id, $language['id_lang']);
            }

            for ($i = 4; $i < 6; $i++) {
                $result &= $this->installFixture('bottom', $i, $this->context->shop->id, $language['id_lang']);
            }

            for ($i = 6; $i < 7; $i++) {
                $result &= $this->installFixture('left', $i, $this->context->shop->id, $language['id_lang']);
            }
        }

        return $result;
    }


    public function uninstall()
    {
        $images = array();
        if (count(Db::getInstance()->executeS('SHOW TABLES LIKE \''._DB_PREFIX_.'angarbanners\''))) {
            $images = Db::getInstance()->executeS('SELECT image FROM `'._DB_PREFIX_.'angarbanners`');
        }
        foreach ($images as $image) {
            $this->deleteImage($image['image']);
        }

        if (!Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'angarbanners`') || !$this->_removeAjaxContoller() || !parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') != $this->name) {
            return;
        }

        $this->context->controller->addCSS($this->_path.'views/css/admin.css');
        $this->context->controller->addJquery();
        $this->context->controller->addJS($this->_path.'views/js/admin.js');
    }

    protected function checkEnvironment()
    {
        $cookie = new Cookie('psAdmin', '', (int)Configuration::get('PS_COOKIE_LIFETIME_BO'));
        return isset($cookie->id_employee) && isset($cookie->passwd) && Employee::checkPassword($cookie->id_employee, $cookie->passwd);
    }

    public function hookdisplayHeader()
    {
        $this->context->controller->addCss($this->_path.'views/css/hooks.css', 'all');

        if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'category') {
            $this->context->smarty->assign(array(
                'display_subcategories' => (int)Configuration::get('PS_SET_DISPLAY_SUBCATEGORIES')
            ));

            return $this->display(__FILE__, 'hook.tpl');
        }
    }

    public function hookActionObjectLanguageAddAfter($params)
    {
        return $this->installFixtures(array(array('id_lang' => (int)$params['object']->id)));
    }

    public function hookdisplayTopColumn()
    {
        return $this->hookdisplayTop();
    }

    public function hookdisplayTop()
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index') {
            return ;
        }
        $this->context->smarty->assign(array(
            'htmlitems' => $this->getItemsFromHook('top'),
            'hook' => 'top'
        ));

        return $this->display(__FILE__, 'hook.tpl');
    }

    public function hookDisplayHome()
    {
        $this->context->smarty->assign(array(
            'htmlitems' => $this->getItemsFromHook('home'),
            'hook' => 'home'
        ));

        return $this->display(__FILE__, 'hook.tpl');
    }

    public function hookDisplayLeftColumn()
    {
        $this->context->smarty->assign(array(
            'htmlitems' => $this->getItemsFromHook('left'),
            'hook' => 'left'
        ));

        return $this->display(__FILE__, 'hook.tpl');
    }

    public function hookDisplayRightColumn()
    {
        $this->context->smarty->assign(array(
            'htmlitems' => $this->getItemsFromHook('right'),
            'hook' => 'right'
        ));

        return $this->display(__FILE__, 'hook.tpl');
    }

    public function hookDisplayFooterAfter()
    {
        $this->context->smarty->assign(array(
            'htmlitems' => $this->getItemsFromHook('footer'),
            'hook' => 'footer'
        ));

        return $this->display(__FILE__, 'hook.tpl');
    }

    public function hookAngarBannersBottom()
    {
        $this->context->smarty->assign(array(
            'htmlitems' => $this->getItemsFromHook('bottom'),
            'hook' => 'bottom'
        ));

        return $this->display(__FILE__, 'hook.tpl');
    }

    protected function getItemsFromHook($hook)
    {
        if (!$hook) {
            return false;
        }

        return Db::getInstance()->ExecuteS('
		SELECT *
		FROM `'._DB_PREFIX_.'angarbanners`
		WHERE id_shop = '.(int)$this->context->shop->id.' AND id_lang = '.(int)$this->context->language->id.'
		AND hook = \''.pSQL($hook).'\' AND active = 1
		ORDER BY item_order ASC');
    }

    protected function deleteImage($image)
    {
        $file_name = $this->uploads_path.$image;

        if (realpath(dirname($file_name)) != realpath($this->uploads_path)) {
            Tools::dieOrLog(sprintf('Could not find upload directory'));
        }

        if ($image != '' && is_file($file_name) && !strpos($file_name, 'banner-img') && !strpos($file_name, 'bg-theme') && !strpos($file_name, 'footer-bg')) {
            unlink($file_name);
        }
    }

    protected function removeItem()
    {
        $id_item = (int)Tools::getValue('item_id');

        if ($image = Db::getInstance()->getValue('SELECT image FROM `'._DB_PREFIX_.'angarbanners` WHERE id_item = '.(int)$id_item)) {
            $this->deleteImage($image);
        }

        Db::getInstance()->delete('angarbanners', 'id_item = '.(int)$id_item);

        if (Db::getInstance()->Affected_Rows() == 1) {
            Db::getInstance()->execute('
				UPDATE `'._DB_PREFIX_.'angarbanners`
				SET item_order = item_order-1
				WHERE (
					item_order > '.(int)Tools::getValue('item_order').' AND
					id_shop = '.(int)$this->context->shop->id.' AND
					hook = \''.pSQL(Tools::getValue('item_hook')).'\')');
            Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&conf=6&token='.Tools::getAdminTokenLite('AdminModules'));
        } else {
            $this->context->smarty->assign('error', $this->l('Can\'t delete the slide.'));
        }
    }

    protected function updateItem()
    {
        $id_item = (int)Tools::getValue('item_id');
        $title = Tools::getValue('item_title');
        $content = Tools::getValue('item_html');

        if (!Validate::isCleanHtml($title, (int)Configuration::get('PS_ALLOW_HTML_IFRAME')) || !Validate::isCleanHtml($content, (int)Configuration::get('PS_ALLOW_HTML_IFRAME'))) {
            $this->context->smarty->assign('error', $this->l('Invalid content'));
            return false;
        }

        $new_image = '';
        $image_w = (is_numeric(Tools::getValue('item_img_w'))) ? (int)Tools::getValue('item_img_w') : '';
        $image_h = (is_numeric(Tools::getValue('item_img_h'))) ? (int)Tools::getValue('item_img_h') : '';

        if (!empty($_FILES['item_img']['name'])) {
            if ($old_image = Db::getInstance()->getValue('SELECT image FROM `'._DB_PREFIX_.'angarbanners` WHERE id_item = '.(int)$id_item)) {
                if (file_exists(dirname(__FILE__).'/views/img/'.$old_image)) {
                    @unlink(dirname(__FILE__).'/views/img/'.$old_image);
                }
            }

            if (!$image = $this->uploadImage($_FILES['item_img'], $image_w, $image_h)) {
                return false;
            }

            $new_image = 'image = \''.pSQL($image).'\',';
        }

        if (!Db::getInstance()->execute('
			UPDATE `'._DB_PREFIX_.'angarbanners` SET
					title = \''.pSQL($title).'\',
					title_use = '.(int)Tools::getValue('item_title_use').',
					hook = \''.pSQL(Tools::getValue('item_hook')).'\',
					url = \''.pSQL(Tools::getValue('item_url')).'\',
					target = '.(int)Tools::getValue('item_target').',
					'.$new_image.'
					image_w = '.(int)$image_w.',
					image_h = '.(int)$image_h.',
					active = '.(int)Tools::getValue('item_active').',
					html = \''.pSQL($this->filterVar($content), true).'\'
			WHERE id_item = '.(int)Tools::getValue('item_id'))) {
            if ($image = Db::getInstance()->getValue('SELECT image FROM `'._DB_PREFIX_.'angarbanners` WHERE id_item = '.(int)Tools::getValue('item_id'))) {
                $this->deleteImage($image);
            }

            $this->context->smarty->assign('error', $this->l('An error occurred while saving data.'));

            return false;
        }

        $this->context->smarty->assign('confirmation', $this->l('Successfully updated.'));

        return true;
    }

    protected function uploadImage($image, $image_w = '', $image_h = '')
    {
        $res = false;
        if (is_array($image) && (ImageManager::validateUpload($image, $this->max_image_size) === false) && ($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) && move_uploaded_file($image['tmp_name'], $tmp_name)) {
            $salt = sha1(microtime());
            $pathinfo = pathinfo($image['name']);
            $img_name = $salt.'_'.Tools::str2url($pathinfo['filename']).'.'.$pathinfo['extension'];

            if (ImageManager::resize($tmp_name, dirname(__FILE__).'/views/img/'.$img_name, $image_w, $image_h)) {
                $res = true;
            }
        }

        if (!$res) {
            $this->context->smarty->assign('error', $this->l('An error occurred during the image upload.'));
            return false;
        }

        return $img_name;
    }

    public function getContent()
    {
        $html = "";

        if (Tools::isSubmit('submitModule')) {
            Configuration::updateValue('PS_QUICK_VIEW', (int)Tools::getValue('quick_view'));
            Configuration::updateValue('PS_GRID_PRODUCT', (int)Tools::getValue('grid_list'));
            Configuration::updateValue('PS_SET_DISPLAY_SUBCATEGORIES', (int)Tools::getValue('sub_cat'));
            foreach ($this->getConfigurableModules() as $module) {
                if (!isset($module['is_module']) || !$module['is_module'] || !Validate::isModuleName($module['name']) || !Tools::isSubmit($module['name'])) {
                    continue;
                }

                $module_instance = Module::getInstanceByName($module['name']);
                if ($module_instance === false || !is_object($module_instance)) {
                    continue;
                }

                $is_installed = (int)Validate::isLoadedObject($module_instance);
                if ($is_installed) {
                    if (($active = (int)Tools::getValue($module['name'])) == $module_instance->active) {
                        continue;
                    }

                    if ($active) {
                        $module_instance->enable();
                    } else {
                        $module_instance->disable();
                    }
                } elseif ((int)Tools::getValue($module['name'])) {
                    $module_instance->install();
                }
            }
        }

        if (Tools::isSubmit('newItem')) {
            $this->addItem();
        } elseif (Tools::isSubmit('updateItem')) {
            $this->updateItem();
        } elseif (Tools::isSubmit('removeItem')) {
            $this->removeItem();
        }

        $html .= $this->renderAngarBannersForm();

        return $html;
    }

    protected function addItem()
    {
        $title = Tools::getValue('item_title');
        $content = Tools::getValue('item_html');

        if (!Validate::isCleanHtml($title, (int)Configuration::get('PS_ALLOW_HTML_IFRAME'))
            || !Validate::isCleanHtml($content, (int)Configuration::get('PS_ALLOW_HTML_IFRAME'))) {
            $this->context->smarty->assign('error', $this->l('Invalid content'));
            return false;
        }

        if (!$current_order = (int)Db::getInstance()->getValue('
			SELECT item_order + 1
			FROM `'._DB_PREFIX_.'angarbanners`
			WHERE
				id_shop = '.(int)$this->context->shop->id.'
				AND id_lang = '.(int)Tools::getValue('id_lang').'
				AND hook = \''.pSQL(Tools::getValue('item_hook')).'\'
				ORDER BY item_order DESC')) {
            $current_order = 1;
        }

        $image_w = is_numeric(Tools::getValue('item_img_w')) ? (int)Tools::getValue('item_img_w') : '';
        $image_h = is_numeric(Tools::getValue('item_img_h')) ? (int)Tools::getValue('item_img_h') : '';

        if (!empty($_FILES['item_img']['name'])) {
            if (!$image = $this->uploadImage($_FILES['item_img'], $image_w, $image_h)) {
                return false;
            }
        } else {
            $image = '';
            $image_w = '';
            $image_h = '';
        }

        if (!Db::getInstance()->Execute('
			INSERT INTO `'._DB_PREFIX_.'angarbanners` (
					`id_shop`, `id_lang`, `item_order`, `title`, `title_use`, `hook`, `url`, `target`, `image`, `image_w`, `image_h`, `html`, `active`
			) VALUES (
					\''.(int)$this->context->shop->id.'\',
					\''.(int)Tools::getValue('id_lang').'\',
					\''.(int)$current_order.'\',
					\''.pSQL($title).'\',
					\''.(int)Tools::getValue('item_title_use').'\',
					\''.pSQL(Tools::getValue('item_hook')).'\',
					\''.pSQL(Tools::getValue('item_url')).'\',
					\''.(int)Tools::getValue('item_target').'\',
					\''.pSQL($image).'\',
					\''.pSQL($image_w).'\',
					\''.pSQL($image_h).'\',
					\''.pSQL($this->filterVar($content), true).'\',
					1)')) {
            if (!Tools::isEmpty($image)) {
                $this->deleteImage($image);
            }

            $this->context->smarty->assign('error', $this->l('An error occurred while saving data.'));
            return false;
        }

        $this->context->smarty->assign('confirmation', $this->l('New item successfully added.'));
        return true;
    }

    public function renderConfigurationForm()
    {
        $inputs = array();

        foreach ($this->getConfigurableModules() as $module) {
            $desc = '';

            if (isset($module['is_module']) && $module['is_module']) {
                $module_instance = Module::getInstanceByName($module['name']);
                if (Validate::isLoadedObject($module_instance) && method_exists($module_instance, 'getContent')) {
                    $desc = '<a class="btn btn-default" target="_blank" href="'.$this->context->link->getAdminLink('AdminModules', true).'&configure='.urlencode($module_instance->name).'&tab_module='.$module_instance->tab.'&module_name='.urlencode($module_instance->name).'">'.$this->l('Configure').' <i class="icon-external-link"></i></a>';
                }
            }
            if (!$desc && isset($module['desc']) && $module['desc']) {
                $desc = $module['desc'];
            }

            $inputs[] = array(
                'type' => 'switch',
                'label' => $module['label'],
                'name' => $module['name'],
                'desc' => $desc,
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
            );
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'description' => $this->l('Tip: To add banners move the page down.').'<br/>'.
                $this->l('Info: Configuration of selected modules will be opened in new browser tab.').'<br/>'.
                $this->l('Info: Please read the documentation for more informations.'),
                'input' => $inputs,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right'
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

    protected function renderAngarBannersForm()
    {
        $id_shop = (int)$this->context->shop->id;
        $items = array();
        $hooks = array();

        $this->context->smarty->assign('htmlcontent', array(
            'admin_tpl_path' => $this->admin_tpl_path,
            'hooks_tpl_path' => $this->hooks_tpl_path,

            'info' => array(
                'module' => $this->name,
                'name' => $this->displayName,
                'version' => $this->version,
                'psVersion' => _PS_VERSION_,
                'context' => (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 0) ? 1 : ($this->context->shop->getTotalShops() != 1) ? $this->context->shop->getContext() : 1
            )
        ));

        foreach ($this->languages as $language) {
            $hooks[$language['id_lang']] = array(
                'top',
                'bottom',
                'home',
                'left',
                'right',
                'footer'
            );

            foreach ($hooks[$language['id_lang']] as $hook) {
                $items[$language['id_lang']][$hook] = Db::getInstance()->ExecuteS('
					SELECT * FROM `'._DB_PREFIX_.'angarbanners`
					WHERE id_shop = '.(int)$id_shop.'
					AND id_lang = '.(int)$language['id_lang'].'
					AND hook = \''.pSQL($hook).'\'
					ORDER BY item_order ASC');
            }
        }

        $this->context->smarty->assign('htmlitems', array(
            'items' => $items,
            'theme_url' => $this->context->link->getAdminLink('AdminAngarBanners'),
            'lang' => array(
                'default' => $this->default_language,
                'all' => $this->languages,
                'lang_dir' => _THEME_LANG_DIR_,
                'user' => $this->context->language->id
            ),
            'postAction' => 'index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&tab_module=other&module_name='.$this->name.'',
            'id_shop' => $id_shop
        ));

        $this->context->controller->addJqueryUI('ui.sortable');
        return $this->display(__FILE__, 'views/templates/admin/admin.tpl');
    }

    protected function getConfigurableModules()
    {
        return array(

            array(
                'label' => $this->l('Live theme color and layout configurator'),
                'name' => 'angarthemeconfigurator',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarthemeconfigurator')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Menu'),
                'name' => 'ps_mainmenu',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_mainmenu')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Slider'),
                'name' => 'angarslider',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarslider')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Featured categories'),
                'name' => 'angarhomecat',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarhomecat')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Category block'),
                'name' => 'ps_categorytree',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_categorytree')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Manufacturer products in left column'),
                'name' => 'angarmanproduct',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarmanproduct')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('New products'),
                'name' => 'angarnewproducts',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarnewproducts')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Featured products'),
                'name' => 'angarfeatured',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarfeatured')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Bestsellers products'),
                'name' => 'angarbestsellers',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarbestsellers')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Special products'),
                'name' => 'angarspecials',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarspecials')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Parallax banner'),
                'name' => 'angarparallax',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarparallax')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Products from category'),
                'name' => 'angarcatproduct',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarcatproduct')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Featured manufacturers'),
                'name' => 'angarmanufacturer',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarmanufacturer')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('About our store'),
                'name' => 'angarcmsdesc',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarcmsdesc')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Icons before footer (Free shipping, Product return, Support 24/7, Safe Payment)'),
                'name' => 'angarcmsinfo',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarcmsinfo')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Newsletter'),
                'name' => 'ps_emailsubscription',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_emailsubscription')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Display links to your store\'s social accounts (Twitter, Facebook, etc.)'),
                'name' => 'ps_socialfollow',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_socialfollow')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Footer links'),
                'name' => 'angarfooter',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarfooter')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Contact top and footer'),
                'name' => 'angarcontact',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarcontact')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Facebook block'),
                'name' => 'angarfacebook',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('angarfacebook')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Display subcategories'),
                'name' => 'sub_cat',
                'value' => (int)Tools::getValue('PS_SET_DISPLAY_SUBCATEGORIES', Configuration::get('PS_SET_DISPLAY_SUBCATEGORIES')),
            ),

            array(
                'label' => $this->l('Display social sharing buttons on the product\'s page'),
                'name' => 'ps_sharebuttons',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_sharebuttons')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Product and cart customer reassurance'),
                'name' => 'blockreassurance',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('blockreassurance')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Products in the same category'),
                'name' => 'ps_categoryproducts',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('ps_categoryproducts')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

            array(
                'label' => $this->l('Products comments'),
                'name' => 'productcomments',
                'value' => (int)Validate::isLoadedObject($module = Module::getInstanceByName('productcomments')) && $module->isEnabledForShopContext(),
                'is_module' => true,
            ),

        );
    }

    public function getConfigFieldsValues()
    {
        $values = array();
        foreach ($this->getConfigurableModules() as $module) {
            $values[$module['name']] = $module['value'];
        }

        return $values;
    }

    public function getLiveConfiguratorToken()
    {
        return Tools::getAdminToken($this->name.(int)Tab::getIdFromClassName($this->name)
            .(is_object(Context::getContext()->employee) ? (int)Context::getContext()->employee->id :
                Tools::getValue('id_employee')));
    }

    protected function filterVar($value)
    {
        if (version_compare(_PS_VERSION_, '1.6.0.7', '>=') === true) {
            return Tools::purifyHTML($value);
        } else {
            return filter_var($value, FILTER_SANITIZE_STRING);
        }
    }
}
