<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Software License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 *
 * @author    Peter Sliacky (Zelarg)
 * @copyright Peter Sliacky (Zelarg)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Handler\ChromePHPHandler;
use module\thecheckout\Config;

class TheCheckout extends Module
{

    /**
     * @var array $module_settings An array of settings provided on configuration page
     */
    public $conf_prefix = "opc_";
    /**
     * @var Config
     */
    public $config;
    public $debug = false;
    public $deepDebug = false;
    public $debugJsController = false;
    private $logger;

    public function __construct()
    {
        $this->name       = 'thecheckout';
        $this->tab        = 'checkout';
        $this->version    = '3.1.7';
        $this->author     = 'Zelarg';
        $this->module_key = "2e602e0a1021555e3d85311cd8ef756d";
        //$this->moduleTHECHECKOUT_key = "2e602e0a1021555e3d85311cd8ef756d";
        //$this->moduleOPC_key = "38254238bedae1ccc492a65148109fdd";

        $this->need_instance          = 1;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => '1.7');
        $this->bootstrap              = true;

        parent::__construct(); // The parent construct is required for translations

        $this->page             = basename(__FILE__, '.php');
        $this->displayName      = $this->l('The Checkout');
        $this->description      = $this->l('Powerful and intuitive checkout process.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->controllers = array('front');

        $this->initTheCheckout();
    }

    private function checkInstallation()
    {
        // Check common options and permissions
        $writePermissions = array(
            _PS_MODULE_DIR_ . $this->name . '/log/',
            _PS_MODULE_DIR_ . $this->name . '/views/css/',
        );
        $permissionsError = '[permissions error] ';

        foreach ($writePermissions as $file) {
            if (!is_writable($file)) {
                return "$permissionsError: $file is not writable!";
            }
        }

        // Check hooks
        $hooksList = array('actionDispatcher', 'displayOrderConfirmation', 'displayBackOfficeHeader');
        foreach ($hooksList as $hookName) {
            if (!$this->isRegisteredInHook($hookName)) {
                return "[hook error] Missing hook registration for $hookName!";
            }
        }

        // Check DB required fields (Customer and Address objects)
        $tmpCustomer    = new Customer();
        $requiredFields = $tmpCustomer->getFieldsRequiredDatabase();
        foreach ($requiredFields as $field) {
            return "[required fields error] " . $field['object_name'] . ':' . $field['field_name'];
        }
        if (class_exists('CustomerAddress')) {
            $tmpAddress     = new CustomerAddress();
            $requiredFields = $tmpAddress->getFieldsRequiredDatabase();
            foreach ($requiredFields as $field) {
                return "[required fields error] " . $field['object_name'] . ':' . $field['field_name'];
            }
        }

        return '';
    }

    private function initTheCheckout()
    {
        if (null == $this->config) {
            $this->setupLogger();
            $this->setConfigOptions();
        }
    }

    public function includeDependency($path)
    {
        if (file_exists(_PS_MODULE_DIR_ . $this->name . '/' . $path)) {
            include_once(_PS_MODULE_DIR_ . $this->name . '/' . $path);
            return true;
        } else {
            return false;
        }
    }

    public function getTranslation($key)
    {
        // These comments are required here, so that PS core translation parser could offer them in
        // BO / International / Translations / Module translations

        // $this->l('Extra field No.1');
        // $this->l('Extra field No.2');
        // $this->l('Extra field No.3');
        // $this->l('Extra field No.4');
        // $this->l('Extra field No.5');
        // $this->l('Payment fee');
        return $this->l($key);
    }

    private function setConfigOptions()
    {
        $this->includeDependency('classes/Config.php');
        $this->config = new Config();
    }

    private function setupLogger()
    {
        $this->logger = new Logger(get_class($this));

        if (is_writable(_PS_MODULE_DIR_ . $this->name . '/log/')) {
            $this->logger->pushHandler(
                new StreamHandler(
                    _PS_MODULE_DIR_ . $this->name . '/log/debug.log',
                    ($this->debug) ? Logger::DEBUG : Logger::WARNING
                )
            );
        }

        // Line formatter without empty brackets in the end
        //$formatter = new LineFormatter(null, null, false, true);
        //$debugHandler->setFormatter($formatter);

        if ($this->debug) {
            $this->logger->pushHandler(
                new ChromePHPHandler(Logger::DEBUG)
            );
            $this->logger->pushProcessor(new WebProcessor());
        }
        if ($this->debug && $this->deepDebug) {
            $this->logger->pushProcessor(
                new IntrospectionProcessor(Logger::DEBUG, array(), 1) // 1=skip top-most level in stack
            );
            $self = $this;
            $this->logger->pushProcessor(function ($record) use ($self) {
                $record['extra']['id_cart']             = $self->context->cart->id;
                $record['extra']['id_customer']         = $self->context->cart->id_customer;
                $record['extra']['id_address_delivery'] = $self->context->cart->id_address_delivery;
                $record['extra']['id_address_invoice']  = $self->context->cart->id_address_invoice;
                $dateObj                                = DateTime::createFromFormat('U.u', microtime(true));
                $dateObj->setTimezone(new DateTimeZone('Europe/Amsterdam'));
                $executionTime                = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
                $record['extra']['timestamp'] = $dateObj->format('H:i:s.') .
                    sprintf('%03d', floor($dateObj->format('u') / 1000)) .
                    sprintf(' (+%.03f)', $executionTime);
                return $record;
            });
        }
    }

    public function logInfo($msg)
    {
        $this->logger->info($msg);
    }

    public function logDebug($msg)
    {
        $this->logger->debug($msg);
    }

    public function logWarning($msg)
    {
        $this->logger->warn($msg);
    }

    public function logError($msg)
    {
        $this->logger->error($msg);
    }

    public function hookDisplayBackOfficeHeader()
    {
        Media::addJsDefL('thecheckout_video_tutorial', $this->l('Video Tutorial'));
        Media::addJsDefL('thecheckout_video_tutorial_sub1', $this->l('How to create Facebook App ID and Secret?'));
        Media::addJsDefL('thecheckout_video_tutorial_sub2', $this->l('How to create Google Client ID and Secret?'));
        Media::addJsDefL('thecheckout_reset_conf_for', $this->l('Reset default configuration for'));
        Media::addJsDefL('thecheckout_init_html_editor', $this->l('Use HTML editor'));
    }

    public function install()
    {
        if (!parent::install()
//            || !$this->registerHook('moduleRoutes')
            || !$this->registerHook('actionDispatcher')
            || !$this->registerHook('displayOrderConfirmation')
            || !$this->registerHook('header')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('additionalCustomerFormFields')
        ) {
            return false;
        }


        $reassurance_sample_html = array();

        foreach (Language::getLanguages() as $language) {
            $existingReassuranceHtml = Configuration::get('TC_html_box_1', $language['id_lang']);
            if (!$existingReassuranceHtml || "" == trim($existingReassuranceHtml)) {
                $reassurance_sample_html[$language['id_lang']] =
                    '<' . 'div class="thecheckout-reassurance"' . '>
                <' . 'div class="reassurance-section security">' . '<' . 'span class="icon"' . '>' . '<' . '/' . 'span' . '>
                <' . 'h3' . '>Security policy<' . '/' . 'h3' . '>
                We use modern SSL to ' . '<' . 'b' . '>' . 'secure payment<' . '/' . 'b>' . '<' . '/' . 'div' . '>
                <' . 'div class="reassurance-section delivery"' . '>' . '<' . 'span class="icon"' . '>' . '<' . '/' . 'span' . '>
                <' . 'h3' . '>Delivery policy<' . '/' . 'h3' . '>
                Orders made on workdays, until 13:00 are <' . 'b' . '>shipped same day<' . '/' . 'b' . '>' . ' (if all goods are in stock)<' . '/' . 'div' . '>
                <' . 'div class="reassurance-section return"' . '><' . 'span class="icon"' . '>' . '<' . '/' . 'span' . '>
                <' . 'h3' . '>Return policy<' . '/' . 'h3' . '>
                Purchases can be <' . 'b' . '>returned<' . '/' . 'b' . '> within 14 days, without any explanation<' . '/' . 'div' . '>
                <' . '/' . 'div' . '>
                <' . 'p' . '>*please edit this in TheCheckout module configuration, HTML Box No.1  <' . 'b' . '>[ ' . $language['name'] . ' ]<' . '/' . 'b>' . '<' . '/' . 'p' . '>';
            } else {
                $reassurance_sample_html[$language['id_lang']] = Configuration::get('TC_html_box_1',
                    $language['id_lang']);
            }
        }

        Configuration::updateValue('TC_html_box_1', $reassurance_sample_html, true);

        // Remove DB required fields (pre-caution)
        $tmpCustomer = new Customer();
        $tmpCustomer->addFieldsRequiredDatabase(array());
        if (class_exists('CustomerAddress')) {
            $tmpAddress = new CustomerAddress();
            $tmpAddress->addFieldsRequiredDatabase(array());
        }

        return true;
    }

    private function resetConfigBlocksLayout()
    {
        Configuration::deleteByName('TC_blocks_layout');
    }

    private function resetConfigAccountFields()
    {
        Configuration::deleteByName('TC_customer_fields');
    }

    private function resetConfigInvoiceFields()
    {
        Configuration::deleteByName('TC_invoice_fields');
    }

    private function resetConfigDeliveryFields()
    {
        Configuration::deleteByName('TC_delivery_fields');
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }

    public function hookModuleRoutes($params = null)
    {
        // prepared for future
    }

    private function shallStartTestMode()
    {
        $shallStart = ("1" == Tools::getIsset(Config::TEST_MODE_KEY_NAME));

        if ($shallStart) {
            $this->context->cookie->test_mode_session = true;

            // output cookie back to client if it doesn't exist yet
            if (!$this->context->cookie->exists()) {
                $this->context->cookie->write();
            }
            return true;
        } else {
            return false;
        }
    }

    private function isTestModeSession()
    {
        return "1" == $this->context->cookie->test_mode_session;
    }

    public function hookHeader()
    {
        $language_iso      = $this->context->language->iso_code;
        $default_countries = array(
            'pl' => 'PL',
            'sk' => 'SK',
            'cs' => 'CS',
            'es' => 'ES'
        );
        if (in_array($language_iso, array_keys($default_countries))) {
            $iso = $language_iso . '_' . $default_countries[$language_iso];
        } else {
            $iso = 'en_US';
        }
        $this->context->smarty->assign(array(
            "config" => $this->config,
            "iso"    => $iso
        ));

        $ret = '';
        if (!$this->context->customer->isLogged()) {
            if ($this->config->social_login_fb) {
                $ret .= $this->context->smarty->fetch($this->local_path . 'views/templates/front/_partials/social-login-fb.tpl');
            }
            if ($this->config->social_login_google) {
                $ret .= $this->context->smarty->fetch($this->local_path . 'views/templates/front/_partials/social-login-google.tpl');
            }
        }

        return $ret;
    }

    public function hookActionDispatcher($params = null)
    {
        // Stop-by only for Order and Cart controllers
        if ("OrderController" !== $params['controller_class']
            && "CartController" !== $params['controller_class']
        ) {
            return false;
        }

        // This will be session based test mode, session will be started with simple GET param
        if ($this->config->test_mode && !$this->shallStartTestMode() && !$this->isTestModeSession()) {
            return false;
        }

        // Redirect from cart controller only on cart summary page
        if ("CartController" === $params['controller_class']) {
            if ("show" === Tools::getValue('action') && !$this->config->separate_cart_summary) {
                Tools::redirect('index.php?controller=order');
                exit;
            } else {
                // keep default cart processing, that's necessary e.g. for adding items to cart
                return false;
            }
        }

        $frontControllerDependencies = array(
            'classes/CheckoutFormField.php',
            'classes/CheckoutAddressFormatter.php',
            'classes/CheckoutCustomerFormatter.php',
            'classes/CheckoutAddressForm.php',
            'classes/CheckoutCustomerForm.php',
            'classes/CheckoutCustomerAddressPersister.php',
            'controllers/front/front.php',
            'classes/SocialLogin.php',
            'lib/functions.inc.php'
        );

        foreach ($frontControllerDependencies as $dependency) {
            if (!$this->includeDependency($dependency)) {
                echo "*** ERROR ***  cannot include ($dependency) file, it's missing or corrupted!";
                exit;
            }
        }
        $checkoutController = new TheCheckoutModuleFrontController();
        $checkoutController->run();
        exit;
    }

    public function hookDisplayOrderConfirmation($params)
    {
        if ($this->config->clean_checkout_session_after_confirmation) {
            unset($this->context->cookie->opc_form_checkboxes);
            unset($this->context->cookie->opc_form_radios);
        }
    }

    public function hookAdditionalCustomerFormFields($params)
    {
        $requiredCheckboxes = array();
        if (isset($params['get-tc-required-checkboxes']) && $params['get-tc-required-checkboxes']) {
            if ('' != trim($this->config->required_checkbox_1)) {
                $requiredCheckboxes[] = (new FormField())
                    ->setName('required-checkbox-1')
                    ->setType('checkbox')
                    ->setLabel($this->config->required_checkbox_1)
                    ->setRequired(true);
            }
            if ('' != trim($this->config->required_checkbox_2)) {
                $requiredCheckboxes[] = (new FormField())
                    ->setName('required-checkbox-2')
                    ->setType('checkbox')
                    ->setLabel($this->config->required_checkbox_2)
                    ->setRequired(true);
            }
        }
        return $requiredCheckboxes;
    }

    private function ajaxCall()
    {
        $action = Tools::getValue('action');

        switch ($action) {
            case 'resetBlocksLayout':
                $this->resetConfigBlocksLayout();
                break;
            case 'resetAccountFields':
                $this->resetConfigAccountFields();
                break;
            case 'resetInvoiceFields':
                $this->resetConfigInvoiceFields();
                break;
            case 'resetDeliveryFields':
                $this->resetConfigDeliveryFields();
                break;
        }
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $this->warning = $this->checkInstallation();

        if ("1" == Tools::getIsset('reset-old-config')) {
            $this->resetConfigBlocksLayout();
            $this->resetConfigAccountFields();
            $this->resetConfigInvoiceFields();
            $this->resetConfigDeliveryFields();
        }


        $this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/views/css/admin/back.css');
        $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/lib/html5sortable.min.js');
//        $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/lib/jquery/jquery-ui.min.js');
//        $this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/lib/jquery/jquery-ui.min.css');
        $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/lib/split.min.js');
        //$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/js/admin/progressive-datalist.js');
        $this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/views/js/admin/back.js');


        if (((bool)Tools::getIsset('ajax_request')) == true) {
            $this->ajaxCall();
            die();
        }

        $output = '';
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitTheCheckoutModule')) == true) {
            //echo "re-submit!"; exit;
            $postProcessResult = $this->postProcess();
            if ('' !== $postProcessResult) {
                $postProcessResultCode = 'alert';
            } else {
                $postProcessResultCode = 'ok';
            }
            $this->_clearCache('*');

//            if ('ok' == $postProcessResultCode) {
//                // Satisfy validator with $postProcessResultCode not being used; until we resolve redirect issue
//            }

            Tools::redirect(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_) .
                '/index.php?controller=AdminModules&configure=thecheckout&tab_module=checkout&module_name=thecheckout' .
                '&token=' . Tools::getAdminTokenLite('AdminModules') .
                "&postProcessResultCode=$postProcessResultCode&postProcessResult=" . urlencode($postProcessResult));
            exit();
        }

        if ('alert' == Tools::getValue('postProcessResultCode')) {
            $output .=
                '<' . 'div class="alert alert-danger"' . '>' .
                '<' . 'button type="button" class="close" data-dismiss="alert"' . '>×<' . '/' . 'button' . '>' .
                Tools::getValue('postProcessResult') .
                '<' . '/' . 'div' . '>';
        } elseif ('ok' == Tools::getValue('postProcessResultCode')) {
            $output .= $this->displayConfirmation($this->trans('The settings have been updated.', array(),
                'Admin.Notifications.Success'));
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        if (!empty($this->warning)) {
            $output .=
                '<' . 'div class="alert alert-danger"' . '>' .
                '<' . 'button type="button" class="close" data-dismiss="alert"' . '>×<' . '/' . 'button' . '>' .
                $this->warning .
                '<' . '/' . 'div' . '>';
        }

        $this->context->smarty->assign(array(
            'module_version' => $this->version,
            'module_name'    => $this->name
        ));

        $configure_top    = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure-top.tpl');
        $configure_bottom = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure-bottom.tpl');

        return $configure_top . $output . $this->renderForm() . $configure_bottom;
    }

    private function renderCustomerFields()
    {
        $this->context->smarty->assign(array(
            'label'  => $this->l('Customer Fields'),
            'fields' => $this->config->customer_fields
        ));

        $result = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/customer-fields.tpl');

        return $result;
    }

    private function renderAddressFields()
    {
        $this->context->smarty->assign(array(
            'addressLabel'      => $this->l('Invoice Address Fields'),
            'addressTypeFields' => 'invoice-fields',
            'fields'            => $this->config->invoice_fields
        ));

        $result = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/address-fields.tpl');

        $this->context->smarty->assign(array(
            'addressLabel'      => $this->l('Delivery Address Fields'),
            'addressTypeFields' => 'delivery-fields',
            'fields'            => $this->config->delivery_fields
        ));

        $result .= $this->context->smarty->fetch($this->local_path . 'views/templates/admin/address-fields.tpl');

        return $result;
    }

    private function renderBlocksLayout()
    {
        $additionalCustomerFormFields = Hook::exec('additionalCustomerFormFields', array(), null, true);
        $allSeparateModuleFields      = array(
            'ps_emailsubscription' => 'newsletter',
            'psgdpr'               => 'psgdpr',
            'ps_dataprivacy'       => 'data-privacy'
        );
        $disabledSeparateModuleFields = $allSeparateModuleFields;

        if (is_array($additionalCustomerFormFields)) {
            foreach (array_keys($additionalCustomerFormFields) as $moduleName) {
                unset($disabledSeparateModuleFields[$moduleName]);
            }
        }

        $enabledSeparateModuleFields = array_diff($allSeparateModuleFields, $disabledSeparateModuleFields);

        $this->context->smarty->assign(array(
            'label'                => $this->l('Checkout blocks layout'),
            'blocksLayout'         => $this->config->blocks_layout,
            'disabledModuleFields' => array_values($disabledSeparateModuleFields),
            'enabledModuleFields'  => array_values($enabledSeparateModuleFields)
        ));

        $result = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/blocks-layout.tpl');

        return $result;
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar             = false;
        $helper->table                    = $this->table;
        $helper->module                   = $this;
        $helper->default_form_language    = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier    = $this->identifier;
        $helper->submit_action = 'submitTheCheckoutModule';
        $helper->currentIndex  = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token         = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id,
        );


        $result = $helper->generateForm($this->getConfigForms());

        // SECTION Address fields
        $customerFieldsSortable = $this->renderCustomerFields();

        // Inject our address sortable form in address-fields section
        $re     = '/name="TC_customer_fields.*?<\/div>/s';
        $subst  = '$0 ' . $customerFieldsSortable;
        $result = preg_replace($re, $subst, $result, 1);

        // SECTION Address fields
        $addressSortable = $this->renderAddressFields();

        // Inject our address sortable form in address-fields section
        $re     = '/name="TC_invoice_fields.*?<\/div>/s';
        $subst  = '$0 ' . $addressSortable;
        $result = preg_replace($re, $subst, $result, 1);

        // SECTION Blocks layout
        $blocksLayoutSortable = $this->renderBlocksLayout();

        // Inject in correct position
        $re     = '/name="TC_blocks_layout.*?<\/div>/s';
        $subst  = '$0 ' . $blocksLayoutSortable;
        $result = preg_replace($re, $subst, $result, 1);

        return $result;
    }


    private function generateSwitch($name, $label, $description, $other = array(), $extraDescription = "")
    {
        $other['hint'] = $description;

        return array_merge(array(
            'type'    => 'switch',
            'label'   => $label,
            'name'    => 'TC_' . $name,
            'is_bool' => true,
            'desc'    => $extraDescription,
            'values'  => array(
                array(
                    'id'    => $name . '_on',
                    'value' => true,
                    'label' => $this->l('Enabled')
                ),
                array(
                    'id'    => $name . '_off',
                    'value' => false,
                    'label' => $this->l('Disabled')
                )
            )
        ), $other);
    }

    private function generateText($name, $label, $description, $other = array())
    {
        $other['hint'] = $description;

        return array_merge(array(
            'col'   => 9,
            'class' => 'fixed-width-xxl',
            'type'  => 'text',
            'name'  => 'TC_' . $name,
            'label' => $label,
//            'desc'  => $description,
        ), $other);
    }

    private function generateSelect($name, $label, $description, $values, $other = array())
    {
        $other['hint'] = $description;

        return array_merge(array(
            'col'     => 3,
            'class'   => 'fixed-width-xxl' . (('default_payment_method' === $name) ? ' progressive-datalist' : ''),
            'type'    => 'select',
            'name'    => 'TC_' . $name,
            'label'   => $label,
//            'desc'    => $description,
            'options' => array(
                'id'    => 'id', // <-- key name in $values array (option ID)
                'name'  => 'name', // <-- key name in $values array (option value)
                'query' => $values
            )
        ), $other);
    }

    private function getFieldValue($key, $id_lang = null, $obj = array('id' => '99999'))
    {
        if ($id_lang) {
            $default_value = (isset($obj->id) && $obj->id && isset($obj->{$key}[$id_lang])) ? $obj->{$key}[$id_lang] : false;
        } else {
            $default_value = isset($obj->{$key}) ? $obj->{$key} : false;
        }

        return Tools::getValue($key . ($id_lang ? '_' . $id_lang : ''), $default_value);
    }

    /**
     * Create the structure of your form. CONFIG_OPTIONS
     */
    protected function getConfigForms()
    {
        $paymentOptions        = Hook::getHookModuleExecList('paymentOptions');
        $paymentOptionsCombo   = array();
        $paymentOptionsCombo[] = array('id' => 'none', 'name' => ' - no selection - ');
        foreach ($paymentOptions as $option) {
            $paymentOptionsCombo[] = array('id' => $option['module'], 'name' => $option['module']);
        }

        $fields_form   = array();
        $fields_form[] = array(
            'form' => array(
                'tinymce' => true,
                'legend'  => array(
                    'title' => $this->l('General'),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    $this->generateSwitch(
                        'test_mode',
                        $this->l('Test mode'),
                        $this->l('Checkout module will be enabled only when using URL parameter: ' . Config::TEST_MODE_KEY_NAME),
                        array(),
                        $this->l('When enabled, Checkout is visible only using this URL: ') . '<' . 'a href="' . $this->context->link->getPageLink('order',
                            true, null,
                            Config::TEST_MODE_KEY_NAME) . '">' . $this->l('Checkout-test-URL') . '<' . '/' . 'a>'
                    ),
                    $this->generateSwitch(
                        'separate_cart_summary',
                        $this->l('Separate cart summary'),
                        $this->l('Display cart review step before Checkout. Otherwise, go straight to Checkout')
                    ),
                    $this->generateSwitch(
                        'offer_second_address',
                        $this->l('Offer second address'),
                        $this->l('In primary address (invoice), show checkbox to expand secondary address (delivery)')
                    ),
                    $this->generateSwitch(
                        'expand_second_address',
                        $this->l('Auto-expand second address'),
                        $this->l('Make both addresses (invoice + delivery) visible right away')
                    ),
                    $this->generateSelect(
                        'checkout_substyle',
                        $this->l('Style of checkout form'),
                        $this->l('Pre-defined styles, choose one and make further customizations in custom.css'),
                        array(
                            array(
                                'id'   => 'minimal',
                                'name' => $this->l('Minimal - choose if you do lot of custom CSS')
                            ),
                            array('id' => 'cute', 'name' => $this->l('Cute - rounded corners, flat, no animations')),
                            array('id' => 'modern', 'name' => $this->l('Modern - Materialized 3d styles')),
//                            array('id' => 'style3', 'name' => 'Style no.3'),
                        )
                    ),
                    $this->generateSelect(
                        'font',
                        $this->l('Checkout form font') .
                        '<' . 'input type="hidden" name="font-weight-Montserrat" value="thin 100,extra-light 200,light 300,regular 400,medium 500,semi-bold 600,bold 700,extra-bold 800,black 900">' .
                        '<' . 'input type="hidden" name="font-weight-Open-Sans" value="light 300,regular 400,semi-bold 600,bold 700,extra-bold 800">' .
                        '<' . 'input type="hidden" name="font-weight-Open-Sans-Condensed" value="light 300,bold 700">' .
                        '<' . 'input type="hidden" name="font-weight-Playfair-Display" value="regular 400,bold 700,black 900">' .
                        '<' . 'input type="hidden" name="font-weight-Dosis" value="extra-light 200,light 300,regular 400,medium 500,semi-bold 600,bold 700,extra-bold 800">' .
                        '<' . 'input type="hidden" name="font-weight-Titillium-Web" value="extra-light 200,light 300,regular 400,semi-bold 600,bold 700,black 900">' .
                        '<' . 'input type="hidden" name="font-weight-Indie-Flower" value="regular 400">' .
                        '<' . 'input type="hidden" name="font-weight-Great-Vibes" value="regular 400">' .
                        '<' . 'input type="hidden" name="font-weight-Gloria-Hallelujah" value="regular 400">' .
                        '<' . 'input type="hidden" name="font-weight-Amatic-SC" value="regular 400,bold 700">' .
                        '<' . 'input type="hidden" name="font-weight-Exo-2" value="thin 100,extra-light 200,light 300,regular 400,medium 500,semi-bold 600,bold 700,extra-bold 800,black 900">' .
                        '<' . 'input type="hidden" name="font-weight-Yanone-Kaffeesatz" value="extra-light 200,light 300,regular 400,bold 700">',
                        $this->l('Font-family used on checkout form'),
                        array(
                            array('id' => 'theme-default', 'name' => 'Theme default'),
                            array('id' => 'Montserrat', 'name' => 'Montserrat'),
                            array('id' => 'Open+Sans', 'name' => 'Open Sans'),
                            array('id' => 'Open+Sans+Condensed', 'name' => 'Open Sans Condensed'),
                            array('id' => 'Playfair+Display', 'name' => 'Playfair Display'),
                            array('id' => 'Dosis', 'name' => 'Dosis'),
                            array('id' => 'Titillium+Web', 'name' => 'Titillium Web'),
                            array('id' => 'Indie+Flower', 'name' => 'Indie Flower'),
                            array('id' => 'Great+Vibes', 'name' => 'Great Vibes'),
                            array('id' => 'Gloria+Hallelujah', 'name' => 'Gloria Hallelujah'),
                            array('id' => 'Amatic+SC', 'name' => 'Amatic SC'),
                            array('id' => 'Exo+2', 'name' => 'Exo 2'),
                            array('id' => 'Yanone+Kaffeesatz', 'name' => 'Yanone Kaffeesatz'),
                        )
                    ),
                    $this->generateSelect(
                        'fontWeight',
                        $this->l('... font weight'),
                        $this->l('How "bold" the font shall be'),
                        array(
                            array('id' => '100', 'name' => 'thin 100'),
                            array('id' => '200', 'name' => 'extra-light 200'),
                            array('id' => '300', 'name' => 'light 300'),
                            array('id' => '400', 'name' => 'regular 400'),
                            array('id' => '500', 'name' => 'medium 500'),
                            array('id' => '600', 'name' => 'semi-bold 600'),
                            array('id' => '700', 'name' => 'bold 700'),
                            array('id' => '800', 'name' => 'extra-bold 800'),
                            array('id' => '900', 'name' => 'black 900')
                        )
                    ),
                    $this->generateSwitch(
                        'using_material_icons',
                        $this->l('Using material icons'),
                        $this->l('Disable if your theme DOES NOT use material icons (most PS1.7 themes use it)')
                    ),
                    $this->generateSwitch(
                        'mark_required_fields',
                        $this->l('Mark required fields'),
                        $this->l('Show red star next to required fields label')
                    ),
//                    $this->generateSwitch(
//                        'show_block_reassurance',
//                        $this->l('Show Reassurance Block'),
//                        $this->l('Show PS default Security/Delivery/Return policy details below cart summary')
//                    ),
                    $this->generateSwitch(
                        'show_order_message',
                        $this->l('Show Order Message'),
                        $this->l('Show Textarea for arbitrary order message')
                    ),
                    $this->generateSwitch(
                        'show_i_am_business',
                        $this->l('Show "I am a business" checkbox'),
                        $this->l('Show checkbox on top of Invoice address, which would expand Company and tax fields')
                    ),
                    $this->generateSwitch(
                        'create_account_checkbox',
                        $this->l('"Create account" checkbox'),
                        $this->l('Instead of password field, show checkbox to create account. "password" must not be required in Customer Fields below.')
                    ),
                    $this->generateSelect(
                        'default_payment_method',
                        $this->l('Default payment method'),
                        $this->l('Which payment method shall be selected by default'),
                        $paymentOptionsCombo
                    ),
                    $this->generateText(
                        'business_fields',
                        $this->l('Business Fields'),
                        $this->l('Comma separated list of fields shown in separate section for business customers')
                    ),
                    $this->generateSwitch(
                        'show_shipping_country_in_carriers',
                        $this->l('Show "shipping to" in carriers'),
                        $this->l('Show shipping country name in carriers selection, for better clarity')
                    ),
                    $this->generateSwitch(
                        'force_customer_to_choose_country',
                        $this->l('Force customer to choose country'),
                        $this->l('Hides shipping methods and de-select country at the beginning, so that customer has to choose country manually')
                    ),
                    $this->generateSwitch(
                        'force_email_overlay',
                        $this->l('Force email overlay'),
                        $this->l('Hides checkout form up until customer logs-in or enters email. NB: This will force silent registration!')
                    ),
                    $this->generateSwitch(
                        'register_guest_on_blur',
                        $this->l('Silently register guest account'),
                        $this->l('Register guest account automatically when customer fills in email field. NB: Guest checkout needs to be enabled!')
                    ),
                    $this->generateSwitch(
                        'blocks_update_loader',
                        $this->l('Blocks update loader'),
                        $this->l('Display loading animation whenever blocks on checkout form are updated through Ajax.')
                    ),
                    $this->generateSwitch(
                        'compact_cart',
                        $this->l('Compact cart'),
                        $this->l('If you have cart block in thin column, this option will make cart design better fit small width.')
                    ),
                ),
                'submit'  => array(
                    'title' => $this->l('Save'),
                )
            ),
        );
        $fields_form[] = array(
            'form' => array(
                'tinymce' => true,
                'legend'  => array(
                    'title' => $this->l('Address fields'),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'TC_customer_fields',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'TC_invoice_fields',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'TC_delivery_fields',
                    ),
                ),
                'submit'  => array(
                    'title' => $this->l('Save'),
                )
            ),
        );
        $fields_form[] = array(
            'form' => array(
                'tinymce' => true,
                'legend'  => array(
                    'title' => $this->l('Layout'),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'TC_blocks_layout',
                    ),
                    array(
                        'type'         => 'textarea',
                        'label'        => $this->l('HTML Box No.1'),
                        'name'         => 'TC_html_box_1',
                        'lang'         => true,
                        'autoload_rte' => '', //'rte' = enable TinyMCE editor, empty = not enabled
                        'class'        => 'tinymce-on-demand',
                        'col'          => 9,
                        'hint'         => $this->trans('Invalid characters:', array(),
                                'Admin.Notifications.Info') . ' &lt;&gt;;=#{}'
                    ),
                    array(
                        'type'         => 'textarea',
                        'label'        => $this->l('HTML Box No.2'),
                        'name'         => 'TC_html_box_2',
                        'lang'         => true,
                        'autoload_rte' => '', //'rte' = enable TinyMCE editor, empty = not enabled
                        'class'        => 'tinymce-on-demand',
                        'col'          => 9,
                        'hint'         => $this->trans('Invalid characters:', array(),
                                'Admin.Notifications.Info') . ' &lt;&gt;;=#{}'
                    ),
                    array(
                        'type'         => 'textarea',
                        'label'        => $this->l('HTML Box No.3'),
                        'name'         => 'TC_html_box_3',
                        'lang'         => true,
                        'autoload_rte' => '', //'rte' = enable TinyMCE editor, empty = not enabled
                        'class'        => 'tinymce-on-demand',
                        'col'          => 9,
                        'hint'         => $this->trans('Invalid characters:', array(),
                                'Admin.Notifications.Info') . ' &lt;&gt;;=#{}'
                    ),
                    array(
                        'type'         => 'textarea',
                        'label'        => $this->l('HTML Box No.4'),
                        'name'         => 'TC_html_box_4',
                        'lang'         => true,
                        'autoload_rte' => '', //'rte' = enable TinyMCE editor, empty = not enabled
                        'class'        => 'tinymce-on-demand',
                        'col'          => 9,
                        'hint'         => $this->trans('Invalid characters:', array(),
                                'Admin.Notifications.Info') . ' &lt;&gt;;=#{}'
                    ),
                    array(
                        'type'         => 'textarea',
                        'label'        => $this->l('Required Checkbox No.1'),
                        'name'         => 'TC_required_checkbox_1',
                        'desc'         => 'To enable a required checkbox in checkout page, fill-in the checkbox label here. You can add label also with link, for example: <' . 'br' . '><' . 'b' . '>' . 'I agree with &lt;a href="content/3-privacy-policy"&gt;privacy policy&lt;/a&gt;<' . '/b' . '>',
                        'lang'         => true,
                        'autoload_rte' => '', //'rte' = enable TinyMCE editor, empty = not enabled
                        'class'        => 'tinymce-on-demand',
                        'col'          => 9,
                        'hint'         => $this->trans(
                            'Arbitrary checkbox that user needs to confirm to proceed with order, fill in text to enable.',
                            array(),
                            'Admin.Notifications.Info'
                        )
                    ),
                    array(
                        'type'         => 'textarea',
                        'label'        => $this->l('Required Checkbox No.2'),
                        'name'         => 'TC_required_checkbox_2',
                        'lang'         => true,
                        'autoload_rte' => '', //'rte' = enable TinyMCE editor, empty = not enabled
                        'class'        => 'tinymce-on-demand',
                        'col'          => 9,
                        'hint'         => $this->trans(
                            'Arbitrary checkbox that user needs to confirm to proceed with order, fill in text to enable.',
                            array(),
                            'Admin.Notifications.Info'
                        )
                    ),
                ),

                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );
        $fields_form[] = array(
            'form' => array(
                'tinymce' => true,
                'legend'  => array(
                    'title' => $this->l('Social login'),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    $this->generateSwitch(
                        'social_login_fb',
                        $this->l('Facebook Login'),
                        $this->l('Enable Facebook Login')
                    ),
                    $this->generateText(
                        'social_login_fb_app_id',
                        $this->l('Facebook App ID'),
                        $this->l('App ID from Facebook developers API')
                    ),
                    $this->generateText(
                        'social_login_fb_app_secret',
                        $this->l('Facebook App Secret'),
                        $this->l('App Secret from Facebook developers API')
                    ),
                    $this->generateSwitch(
                        'social_login_google',
                        $this->l('Google+ Login'),
                        $this->l('Enable Google+ Login')
                    ),
                    $this->generateText(
                        'social_login_google_client_id',
                        $this->l('Google Client ID'),
                        $this->l('Client ID from Google developers API')
                    ),
                    $this->generateText(
                        'social_login_google_client_secret',
                        $this->l('Google Client Secret'),
                        $this->l('Client Secret from Google developers API')
                    ),
                    $this->generateSelect(
                        'social_login_btn_style',
                        $this->l('Style of login buttons'),
                        $this->l('Pre-defined styles, choose one and make further customizations in custom.css'),
                        array(
                            array('id' => 'light', 'name' => 'Light theme'),
                            array('id' => 'bootstrap', 'name' => 'Bootstrap, full colors'),
                        )
                    ),
                ),

                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );
        $fields_form[] = array(
            'form' => array(
                'tinymce' => true,
                'legend'  => array(
                    'title' => $this->l('Advanced'),
                    'icon'  => 'icon-cogs',
                ),
                'input'   => array(
                    $this->generateSwitch(
                        'clean_checkout_session_after_confirmation',
                        $this->l('Clean checkout session'),
                        $this->l('Clean remembered status of checkboxes (Terms & conditions, Customer privacy, ...) after order is confirmed')
                    ),
                    $this->generateText(
                        'ps_css_cache_version',
                        $this->l('PS CSS cache version'),
                        $this->l('Increase if changes in CSS files do not reflect on frontend')
                    ),
                    $this->generateText(
                        'ps_js_cache_version',
                        $this->l('PS JS cache version'),
                        $this->l('Increase if changes in JS files do not reflect on frontend')
                    ),
                    array(
                        'type'         => 'textarea',
                        'label'        => $this->l('Custom CSS'),
                        'name'         => 'TC_custom_css',
                        'lang'         => false,
                        'cols'         => 60,
                        'rows'         => 7,
                        'autoload_rte' => false, //Enable TinyMCE editor for short description
                        'col'          => 6,
                        'hint'         => $this->l('Custom CSS used on checkout page'),
                        'class'        => 'max-size'
                    ),
                    array(
                        'type'         => 'textarea',
                        'label'        => $this->l('Custom JS'),
                        'name'         => 'TC_custom_js',
                        'lang'         => false,
                        'cols'         => 60,
                        'rows'         => 7,
                        'autoload_rte' => false, //Enable TinyMCE editor for short description
                        'col'          => 6,
                        'hint'         => $this->l('Custom JS, (!) consider that jQuery might be loaded later, use it only in plain JS DOMready handler!'),
                        'class'        => 'max-size'
                    ),
                ),

                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        return $fields_form;
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        $tc_options = $this->config->getAllOptions('TC_', true);

        // hide sensitive data in demo
        if ('demo@demo.com' == $this->context->employee->email) {
            $tc_options['TC_social_login_fb_app_secret']        = '200775a7d7b096e5d2f12c2b1aab9a87';
            $tc_options['TC_social_login_google_client_secret'] = 'K0hWjDCblEGcMwTqEjr-HbdF';
        }

        $other_options = array(
            'XYZ_LIVE_MODE' => Configuration::get('XYZ_LIVE_MODE', true),
        );

        $languages              = Language::getLanguages(false);
        $fields_localized       = array();
        $fields_localized_names = array(
            'TC_html_box_1',
            'TC_html_box_2',
            'TC_html_box_3',
            'TC_html_box_4',
            'TC_required_checkbox_1',
            'TC_required_checkbox_2'
        );
        foreach ($languages as $lang) {
            foreach ($fields_localized_names as $name) {
                $fields_localized[$name][(int)$lang['id_lang']] = Tools::getValue(
                    $name . (int)$lang['id_lang'],
                    Configuration::get($name, (int)$lang['id_lang'])
                );
            }

        }
        return array_merge($tc_options, $other_options, $fields_localized);
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        if ('demo@demo.com' == $this->context->employee->email) {
            return 'This is DEMO store, set in <' . 'b' . '>read-only mode<' . '/' . 'b' . '>, settings cannot be updated.';
        }
        $errors = '';

        $form_values = array_merge($this->config->getAllOptions(''), array(
            'XYZ_LIVE_MODE' => Configuration::get('XYZ_LIVE_MODE', true),
        ));

        foreach (array_keys($form_values) as $key) {

            $errors .= $this->config->updateByName($key);
            //echo "updating $key with: ".Tools::getValue($key)."\n\n";
        }

        return $errors;
    }
}
