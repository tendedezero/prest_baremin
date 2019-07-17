<?php
/**
* @author      Krzysztof Pecak
* @copyright   2017 Krzysztof Pecak
* @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class AngarParallax extends Module
{
    public function __construct()
    {
        $this->name = 'angarparallax';
        $this->tab = 'front_office_features';
        $this->version = '1.4.1';
        $this->author = 'angarthemes';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('AT - Parallax banner');
        $this->description = $this->l('Displays a parallax banner.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:angarparallax/views/templates/hook/at_parallax.tpl';
    }

    public function install()
    {
        return
            parent::install() &&
            $this->registerHook('angarParallax') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('actionObjectLanguageAddAfter') &&
            $this->installFixtures() &&
            $this->disableDevice(Context::DEVICE_MOBILE);
    }

    public function hookActionObjectLanguageAddAfter($params)
    {
        return $this->installFixture((int)$params['object']->id, Configuration::get('AT_PARALLAX_IMG', (int)Configuration::get('PS_LANG_DEFAULT')));
    }

    protected function installFixtures()
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $this->installFixture((int)$lang['id_lang'], 'parallax_banner.jpg');
        }

        return true;
    }

    protected function installFixture($id_lang, $image = null)
    {
        $values = array();
        $values['AT_PARALLAX_IMG'][(int)$id_lang] = $image;
        $values['AT_PARALLAX_LINK'][(int)$id_lang] = 'http://www.prestashop.com/';
        $values['AT_PARALLAX_DESC'][(int)$id_lang] = '<h2>Parallax banner</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam ac posuere quam. Proin consectetur, ligula nec pellentesque viverra, nisi nulla pellentesque est, ut iaculis orci turpis ut nibh.</p>';
        Configuration::updateValue('AT_PARALLAX_IMG', $values['AT_PARALLAX_IMG']);
        Configuration::updateValue('AT_PARALLAX_LINK', $values['AT_PARALLAX_LINK']);
        Configuration::updateValue('AT_PARALLAX_DESC', $values['AT_PARALLAX_DESC'], true);
    }

    public function uninstall()
    {
        Configuration::deleteByName('AT_PARALLAX_IMG');
        Configuration::deleteByName('AT_PARALLAX_LINK');
        Configuration::deleteByName('AT_PARALLAX_DESC');
        return parent::uninstall();
    }

    public function hookAngarParallax($params)
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('angarparallax'))) {
            $imgname = Configuration::get('AT_PARALLAX_IMG', $this->context->language->id);

            if ($imgname && file_exists(_PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'views/img'.DIRECTORY_SEPARATOR.$imgname)) {
                $this->smarty->assign('parallax_img', $this->context->link->protocol_content.Tools::getMediaServer($imgname).$this->_path.'views/img/'.$imgname);
            }

            $this->smarty->assign(array(
                'parallax_link' => Configuration::get('AT_PARALLAX_LINK', $this->context->language->id),
                'parallax_desc' => Configuration::get('AT_PARALLAX_DESC', $this->context->language->id)
            ));
        }

        return $this->display(__FILE__, 'views/templates/front/at_parallax.tpl', $this->getCacheId('angarparallax'));
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->addCSS($this->_path.'views/css/at_parallax.css', 'all');
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitStoreConf')) {
            $languages = Language::getLanguages(false);
            $values = array();
            $update_images_values = false;

            foreach ($languages as $lang) {
                if (isset($_FILES['AT_PARALLAX_IMG_'.$lang['id_lang']])
                    && isset($_FILES['AT_PARALLAX_IMG_'.$lang['id_lang']]['tmp_name'])
                    && !empty($_FILES['AT_PARALLAX_IMG_'.$lang['id_lang']]['tmp_name'])) {
                    if ($error = ImageManager::validateUpload($_FILES['AT_PARALLAX_IMG_'.$lang['id_lang']], 4000000)) {
                        return $error;
                    } else {
                        $ext = Tools::substr($_FILES['AT_PARALLAX_IMG_'.$lang['id_lang']]['name'], strrpos($_FILES['AT_PARALLAX_IMG_'.$lang['id_lang']]['name'], '.') + 1);
                        $file_name = md5($_FILES['AT_PARALLAX_IMG_'.$lang['id_lang']]['name']).'.'.$ext;

                        if (!move_uploaded_file($_FILES['AT_PARALLAX_IMG_'.$lang['id_lang']]['tmp_name'], dirname(__FILE__).DIRECTORY_SEPARATOR.'views/img'.DIRECTORY_SEPARATOR.$file_name)) {
                            return $this->displayError($this->l('An error occurred while attempting to upload the file.'));
                        } else {
                            if (Configuration::hasContext('AT_PARALLAX_IMG', $lang['id_lang'], Shop::getContext())
                                && Configuration::get('AT_PARALLAX_IMG', $lang['id_lang']) != $file_name) {
                                @unlink(dirname(__FILE__).DIRECTORY_SEPARATOR.'views/img'.DIRECTORY_SEPARATOR.Configuration::get('AT_PARALLAX_IMG', $lang['id_lang']));
                            }

                            $values['AT_PARALLAX_IMG'][$lang['id_lang']] = $file_name;
                        }
                    }

                    $update_images_values = true;
                }

                $values['AT_PARALLAX_LINK'][$lang['id_lang']] = Tools::getValue('AT_PARALLAX_LINK_'.$lang['id_lang']);
                $values['AT_PARALLAX_DESC'][$lang['id_lang']] = Tools::getValue('AT_PARALLAX_DESC_'.$lang['id_lang']);
            }

            if ($update_images_values) {
                Configuration::updateValue('AT_PARALLAX_IMG', $values['AT_PARALLAX_IMG']);
            }

            Configuration::updateValue('AT_PARALLAX_LINK', $values['AT_PARALLAX_LINK']);
            Configuration::updateValue('AT_PARALLAX_DESC', $values['AT_PARALLAX_DESC'], true);

            $this->_clearCache($this->templateFile);
            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }
        return '';
    }

    public function getContent()
    {
        return $this->postProcess().$this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'tinymce' => true,
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'description' => $this->l('This module displays parallax banner on the home page.'),
                'input' => array(
                    array(
                        'type' => 'file_lang',
                        'label' => $this->l('Parallax banner image:'),
                        'name' => 'AT_PARALLAX_IMG',
                        'desc' => $this->l('Upload an image for parallax banner. If your image look bad please add bigger image.'),
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('"See more" button link:'),
                        'name' => 'AT_PARALLAX_LINK',
                        'desc' => $this->l('Enter the link associated to your banner. When clicking on the banner, the link opens in the same window. If no link is entered, it redirects to the homepage.')
                    ),
                    array(
                        'type' => 'textarea',
                        'lang' => true,
                        'autoload_rte' => true,
                        'label' => $this->l('Description:'),
                        'name' => 'AT_PARALLAX_DESC',
                        'desc' => $this->l('Please enter a short but meaningful description for the banner.')
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitStoreConf';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $languages = Language::getLanguages(false);
        $fields = array();

        foreach ($languages as $lang) {
            $fields['AT_PARALLAX_IMG'][$lang['id_lang']] = Tools::getValue('AT_PARALLAX_IMG_'.$lang['id_lang'], Configuration::get('AT_PARALLAX_IMG', $lang['id_lang']));
            $fields['AT_PARALLAX_LINK'][$lang['id_lang']] = Tools::getValue('AT_PARALLAX_LINK_'.$lang['id_lang'], Configuration::get('AT_PARALLAX_LINK', $lang['id_lang']));
            $fields['AT_PARALLAX_DESC'][$lang['id_lang']] = Tools::getValue('AT_PARALLAX_DESC_'.$lang['id_lang'], Configuration::get('AT_PARALLAX_DESC', $lang['id_lang']));
        }

        return $fields;
    }
}
