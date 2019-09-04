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

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use \PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use module\thecheckout\Config;
use module\thecheckout\SocialLogin;

//include_once(_PS_MODULE_DIR_ . $this->name . '/classes/CheckoutCartController.php');

class TheCheckoutModuleFrontController extends ModuleFrontController
{

    public $php_self = 'order'; // stripe_official needs this to load assets

    private $name = 'thecheckout';
    private $module_root = '';
    private $availableCountries;

    private $selected_payment_option;

    /** @var $module TheCheckout */
    public $module;

    public function __construct()
    {
        $_GET['module'] = $this->name;

        $this->module = Module::getInstanceByName($this->name);

        if (!$this->module->active) {
            Tools::redirect('index');
        }

        parent::__construct();
    }

    public function init()
    {
        parent::init();

        $this->checkAndMakeSubmitReorderRequest();

//        if (0 == $this->context->cart->nbProducts() && empty($this->errors)) {
//            Tools::redirect('index');
//        }

        $this->module_root = _PS_MODULE_DIR_ . $this->name;
    }


    // oyejorge/less.php v1.7.1
    private function autoCompileLess($inputFile, $outputFile)
    {
        require_once $this->module_root . "/lib/less.php_1.7.0.10/Less.php";

        $cacheDir      = _PS_CACHE_DIR_ . 'thecheckout/';
        $less_files    = array($inputFile => '');
        $options       = array(
            'cache_dir'        => $cacheDir,
            'sourceMap'        => true,
            'sourceMapWriteTo' => $outputFile . '.map',
            /*'compress' => true*/
        );
        $css_file_name = Less_Cache::Get($less_files, $options, array());

        if (!file_exists($outputFile) || filemtime($cacheDir . $css_file_name) > filemtime($outputFile)) {
            $compiled = Tools::file_get_contents($cacheDir . $css_file_name);
            file_put_contents($outputFile, $compiled);
        }
    }


//
//   lessc 0.4 implementation
//   private function autoCompileLess($inputFile, $outputFile)
//    {
//        require $this->module_root . "/lib/lessc.inc.php";
//
//        $cacheFile = $inputFile . ".cache";
//
//        if (file_exists($cacheFile)) {
//            $cache = unserialize(file_get_contents($cacheFile));
//        } else {
//            $cache = $inputFile;
//        }
//
//        $less = new lessc;
//        if (!$this->module->debug) {
//            $less->setFormatter("compressed");
//        }
//
//        $forceCompile = ($this->module->debug) ? true : false;
//
//        $newCache = $less->cachedCompile($cache, $forceCompile);
//
//        if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
//            file_put_contents($cacheFile, serialize($newCache));
//            file_put_contents($outputFile, $newCache['compiled']);
//        }
//    }


    private function compileLess()
    {
        try {
            $this->autoCompileLess($this->module_root . "/views/css/front.less",
                $this->module_root . "/views/css/front.less.css");
            $this->autoCompileLess($this->module_root . "/views/css/custom.less",
                $this->module_root . "/views/css/custom.less.css");
            $substyleFileName = $this->module_root . "/views/css/styles/" . $this->module->config->checkout_substyle . ".less";
            if (file_exists($substyleFileName)) {
                $this->autoCompileLess($substyleFileName,
                    $this->module_root . "/views/css/styles/" . $this->module->config->checkout_substyle . ".less.css");
            }
        } catch (Exception $e) {
            if ($this->module->debug) {
                $this->module->logError($e->getMessage());
            } // otherwise, just die gracefully
        }
    }

    public function setMedia()
    {
        parent::setMedia();

        $this->compileLess();

        if ($this->module->debug) {
            $this->module->logInfo('--- DEBUG MODE ACTIVE---');
        }

        // Include font CSS, if custom font is selected
        $i = 0;
        if ("theme-default" !== $this->module->config->font) {
            $this->context->controller->registerStylesheet('modules - thecheckout' . ($i++),
                '//fonts.googleapis.com/css?family=' . $this->module->config->font . ":" . $this->module->config->fontWeight,
                array('media' => 'all', 'priority' => 140, 'server' => 'remote'));
        }

        // Include all views/css/*.css and views/js/*.js files
        foreach (glob(_PS_ROOT_DIR_ . '/modules/' . $this->name . "/views/css/*.css") as $filename) {
            $this->context->controller->registerStylesheet('modules - thecheckout' . ($i++),
                Tools::substr($filename, Tools::strlen(_PS_ROOT_DIR_) + 1),
                array('media' => 'all', 'priority' => 150));
        }

        $this->context->controller->registerJavascript('modules - thecheckout' . ($i++),
            Tools::substr(_PS_ROOT_DIR_ . '/modules/' . $this->name . "/lib/compute-scroll-into-view.min.js",
                Tools::strlen(_PS_ROOT_DIR_) + 1),
            array('position' => 'bottom', 'priority' => 140));

        foreach (glob(_PS_ROOT_DIR_ . '/modules/' . $this->name . "/views/js/*.js") as $filename) {
            $this->context->controller->registerJavascript('modules - thecheckout' . ($i++),
                Tools::substr($filename, Tools::strlen(_PS_ROOT_DIR_) + 1),
                array('position' => 'bottom', 'priority' => 150));
        }
        foreach (glob(_PS_ROOT_DIR_ . '/modules/' . $this->name . "/views/js/parsers/*.js") as $filename) {
            $this->context->controller->registerJavascript('modules - thecheckout' . ($i++),
                Tools::substr($filename, Tools::strlen(_PS_ROOT_DIR_) + 1),
                array('position' => 'bottom', 'priority' => 160));
        }

        $additionalStyles   = array();
        $additionalStyles[] = _PS_ROOT_DIR_ . '/modules/' . $this->name . '/views/css/themes-overrides/' . _THEME_NAME_ . '.css';
        $additionalStyles[] = _PS_ROOT_DIR_ . '/modules/' . $this->name . '/views/css/styles/' . $this->module->config->checkout_substyle . '.less.css';

        foreach ($additionalStyles as $filename) {
            if (file_exists($filename)) {
                $this->context->controller->registerStylesheet('modules - thecheckout' . ($i++),
                    Tools::substr($filename, Tools::strlen(_PS_ROOT_DIR_) + 1),
                    array('media' => 'all', 'priority' => 200));
            }
        }

        // Register CDN's JS - we will not use Vue.js right now
//        $this->context->controller->registerJavascript('modules-thecheckout' . ($i++),
//        'https://cdn.jsdelivr.net/npm/vue',
//        ['position' => 'bottom', 'priority' => 140, 'server' => 'remote']);
    }

    private function usingExtraFields()
    {
        // TODO: Logic need to be changed for 'other' field; in 'other', all other extras will be stored
    }

    private function addExtraFields()
    {
    }

    // PS copied method
    private function getFieldLabel($field)
    {
        // Country:name => Country, Country:iso_code => Country,
        // same label regardless of which field is used for mapping.
        $field = explode(':', $field)[0];

        switch ($field) {
            case 'email':
                return $this->translator->trans('Email', array(), 'Shop.Forms.Labels');
            case 'password':
                return $this->translator->trans('Password', array(), 'Shop.Forms.Labels');
            case 'id_gender':
                return $this->translator->trans('Social title', array(), 'Shop.Forms.Labels');
            case 'company':
                return $this->translator->trans('Company', array(), 'Shop.Forms.Labels');
            case 'siret':
                return $this->translator->trans('Identification number', array(), 'Shop.Forms.Labels');
            case 'birthdate':
                return $this->translator->trans('Birthdate', array(), 'Shop.Forms.Labels');
            case 'optin':
                return $this->translator->trans('Receive offers from our partners', array(),
                    'Shop.Theme.Customeraccount');
            case 'alias':
                return $this->translator->trans('Alias', array(), 'Shop.Forms.Labels');
            case 'firstname':
                return $this->translator->trans('First name', array(), 'Shop.Forms.Labels');
            case 'lastname':
                return $this->translator->trans('Last name', array(), 'Shop.Forms.Labels');
            case 'address1':
                return $this->translator->trans('Address', array(), 'Shop.Forms.Labels');
            case 'address2':
                return $this->translator->trans('Address Complement', array(), 'Shop.Forms.Labels');
            case 'postcode':
                return $this->translator->trans('Zip/Postal Code', array(), 'Shop.Forms.Labels');
            case 'city':
                return $this->translator->trans('City', array(), 'Shop.Forms.Labels');
            case 'Country':
                return $this->translator->trans('Country', array(), 'Shop.Forms.Labels');
            case 'State':
                return $this->translator->trans('State', array(), 'Shop.Forms.Labels');
            case 'phone':
                return $this->translator->trans('Phone', array(), 'Shop.Forms.Labels');
            case 'phone_mobile':
                return $this->translator->trans('Mobile phone', array(), 'Shop.Forms.Labels');
            case 'company':
                return $this->translator->trans('Company', array(), 'Shop.Forms.Labels');
            case 'vat_number':
                return $this->translator->trans('VAT number', array(), 'Shop.Forms.Labels');
            case 'dni':
                return $this->translator->trans('Identification number', array(), 'Shop.Forms.Labels');
            case 'other':
                return $this->translator->trans('Other', array(), 'Shop.Forms.Labels');
            case 'extra1':
                return $this->module->getTranslation('Extra field No.1');// Legacy translations system necessary here
            case 'extra2':
                return $this->module->getTranslation('Extra field No.2');
            case 'extra3':
                return $this->module->getTranslation('Extra field No.3');
            case 'extra4':
                return $this->module->getTranslation('Extra field No.4');
            case 'extra5':
                return $this->module->getTranslation('Extra field No.5');
            default:
                return $field;
        }
    }

    private function generateFormFields($fields, $addressData)
    {
        if (!empty($addressData) && isset($addressData->id_country)) {
            $country = new Country($addressData->id_country);
        } else {
            // When country is not set (first expand of secondary address), we can set context->country,
            // which effectively is invoice country; however, in Carrier::getAvailableCarrierList, when
            // address is not set, country is taken as country_default option, so better adhere to that.
            //$country = $this->context->country;
            $country = new Country(Configuration::get('PS_COUNTRY_DEFAULT'));
        }

        $format = array();
        foreach ($fields as $fieldName => $fieldOptions) {
            $formField = new CheckoutFormField();
            $formField->setName($fieldName);

            $fieldParts = explode(':', $fieldName, 2);

            if (count($fieldParts) === 1) {
                if ($fieldName === 'postcode') {
                    // Postcode is either visible and required or hidden; visible and non-required is not defined
                    if ($country->need_zip_code) {
                        $formField->setRequired(true);
                    } else {
                        $formField->setHidden(true);
                    }
                }
                if ('phone' === Tools::substr($fieldName, 0, Tools::strlen('phone'))) {
                    $formField->setType('tel');
                }
            } elseif (count($fieldParts) === 2) {
                list($entity, $entityField) = $fieldParts;

                // Fields specified using the Entity:field
                // notation are actually references to other
                // entities, so they should be displayed as a select
                $formField->setType('select');

                // Also, what we really want is the id of the linked entity
                $formField->setName('id_' . Tools::strtolower($entity));

                if ($entity === 'Country') {
                    $formField->setType('countrySelect');

                    // Unselect country if we just initiated session and force_customer_to_choose_country is ON
                    if ($this->module->config->force_customer_to_choose_country && empty($addressData)) {
                        $formField->setValue('');
                    } else {
                        $formField->setValue($country->id);
                    }

                    foreach ($this->availableCountries as $countryDetail) {
                        $formField->addAvailableValue(
                            $countryDetail['id_country'],
                            $countryDetail[$entityField]
                        );
                    }
                    $formField->setLive(true);
                } elseif ($entity === 'State') {
                    if ($country->contains_states) {
                        $states = State::getStatesByIdCountry($country->id);
                        foreach ($states as $state) {
                            $formField->addAvailableValue(
                                $state['id_state'],
                                $state[$entityField]
                            );
                        }
                        // State field, if visible, is always required
                        $formField->setRequired(true);
                    }
                    $formField->setLive(true);
                }
            }

            $formField->setLabel($this->getFieldLabel($fieldName));

            // If it's not already required (other reasons than our config)...
            if (!$formField->isRequired()) {
                // Only trust the $required array for fields
                // that are not marked as required.
                // $required doesn't have all the info, and fields
                // may be required for other reasons than what
                // AddressFormat::getFieldsRequired() says.
                $formField->setRequired(
                    $fieldOptions["required"] && $fieldOptions["visible"] && $fieldName != "State:name"
                );
            }

            $formField->setLive(
                $fieldOptions["live"] || $formField->getLive()
            );

            // id_state will be always visible, if assigned enabled for country, regardless of settings
            // in TheCheckout config - even though, state visibility shall not be directly configurable
            // Postcode visibility is also strictly bound to it's 'required' status
            if ('State:name' != $fieldName && 'postcode' != $fieldName) {
                $formField->setHidden(
                    !$fieldOptions["visible"] || $formField->getHidden()
                );
            }


            $formField->setWidth(
                $fieldOptions["width"]
            );

            if (!empty($addressData) && isset($addressData->{$formField->getName()})) {
                $formField->setValue(trim($addressData->{$formField->getName()}));
            }

            $format[$formField->getName()] = $formField;
        }
        return $format;
    }

    private function formatLoginFormFields()
    {
        return array(
            'back'     => (new CheckoutFormField)
                ->setName('back')
                ->setType('hidden'),
            'email'    => (new CheckoutFormField)
                ->setName('email')
                ->setType('email')
                ->setRequired(true)
                ->setLabel($this->translator->trans(
                    'Email', array(), 'Shop.Forms.Labels'
                ))
                ->addConstraint('isEmail'),
            'password' => (new CheckoutFormField)
                ->setName('password')
                ->setType('password')
                ->setRequired(true)
                ->setLabel($this->translator->trans(
                    'Password', array(), 'Shop.Forms.Labels'
                ))
                ->addConstraint('isPasswd'),
        );
    }

    private function setupFormFieldsInvoice()
    {
        $addressData = array();
        // pre-fill address only when invoice address is visible on form - that is in case
        // when it's primary address OR when it's different then shipping address
        if (
            (Config::ADDRESS_TYPE_INVOICE === $this->module->config->primary_address
                || $this->context->cart->id_address_invoice != $this->context->cart->id_address_delivery)
            && $this->context->cart->id_address_invoice > 0
        ) {
            $addressData = new Address($this->context->cart->id_address_invoice);
        }
        // If there's no address and by any chance customer is already logged in, let's use
        // their firstname / lastname as initial values
        elseif ($this->context->customer->isLogged()) {
            if (isset($this->context->customer->firstname) && '' != $this->context->customer->firstname) {
                $addressData['firstname'] = $this->context->customer->firstname;
            }
            if (isset($this->context->customer->lastname) && '' != $this->context->customer->lastname) {
                $addressData['lastname'] = $this->context->customer->lastname;
            }
            $addressData = (object)$addressData;
        }

        return $this->generateFormFields($this->module->config->invoice_fields, $addressData);
    }

    private function setupFormFieldsDelivery()
    {
        $addressData = array();
        if (
            (Config::ADDRESS_TYPE_DELIVERY === $this->module->config->primary_address
                || $this->context->cart->id_address_delivery != $this->context->cart->id_address_invoice)
            && $this->context->cart->id_address_delivery > 0
        ) {
            $addressData = new Address($this->context->cart->id_address_delivery);
        }

        return $this->generateFormFields($this->module->config->delivery_fields, $addressData);
    }


    private function setupFormFieldsAccount()
    {
        $loggedIn = $this->context->customer->isLogged();

        $additionalCustomerFormFields = Hook::exec(
            'additionalCustomerFormFields',
            array('get-tc-required-checkboxes' => 1),
            null,
            true
        );
        $moduleFields                 = array();
        $separateModuleFields         = array();

        $opc_form_checkboxes = json_decode($this->context->cookie->opc_form_checkboxes, true);

        if (is_array($additionalCustomerFormFields)) {
            foreach ($additionalCustomerFormFields as $moduleName => $additionnalFormFields) {

                if (!is_array($additionnalFormFields)) {
                    continue;
                }

                foreach ($additionnalFormFields as $formField) {
                    $checkoutFormField             = new CheckoutFormField($formField);
                    $checkoutFormField->moduleName = $moduleName;

                    // Special treatment for newsletter, if customer ticked it before, in registration form
                    if ("ps_emailsubscription" == $moduleName &&
                        !isset($opc_form_checkboxes['newsletter']) &&
                        $this->context->customer->newsletter
                    ) {
                        $checkoutFormField->setValue(true);
                    } else {
                        $checkoutFormField->setValue(
                            isset($opc_form_checkboxes[$checkoutFormField->getName()]) &&
                            "true" === $opc_form_checkboxes[$checkoutFormField->getName()]
                        );
                    }


                    // For newsletter, psgdpr and customer_privacy modules, we have separate blocks created, so no need
                    // to output them in account hook; but let's return them for further processing
                    if (in_array($moduleName,
                        array("ps_emailsubscription", "psgdpr", "ps_dataprivacy", "thecheckout"))) {
                        $separateModuleFields[$moduleName . '_' . $checkoutFormField->getName()] = $checkoutFormField;
                    } else {
                        $moduleFields[$formField->getName()] = $checkoutFormField;
                    }
                }
            }
        }

        $format = array(
            'back'       => (new CheckoutFormField)
                ->setName('back')
                ->setType('hidden'),
            'id_address' => (new CheckoutFormField)
                ->setName('id_address')
                ->setType('hidden'),
            'token'      => (new CheckoutFormField)
                ->setName('token')
                ->setType('hidden')
                ->setValue($this->makeAddressPersister()->getToken()),
        );

        foreach ($this->module->config->customer_fields as $fieldName => $fieldOptions) {
            $formField = new CheckoutFormField();
            $formField->setName($fieldName);

//            // Third party module's injected fields (by default newsletter, psgdpr, customer_privacy
//            // will be handled separately; for all of them, position will be controlled from
//            // Thecheckout config page, for 'newsletter' (i.e. it's not required) also visibility will be controlled
//            // from Thecheckout config page.
//            if (in_array($fieldName, $this->module->config->module_customer_fields)) {
//                if (isset($moduleFields[$fieldName]) && $fieldOptions['visible']) {
//                    $format[$moduleFields[$fieldName]->moduleName . '_' . $checkoutFormField->getName()] = $moduleFields[$fieldName];
//                }
//                // continue anyway, even when $moduleField is not set; because for module fields, we do not
//                // provide default behavior (e.g. customer_privacy)
//                continue;
//            }

            if ($fieldName === 'email') {
                $formField
                    ->setType('email')
                    ->setHidden($loggedIn)
                    ->addConstraint('isEmail')
                    ->setValue($this->context->customer->email);
            }
            if ($fieldName === 'password') {
                $formField
                    ->setType('password')
                    ->setHidden($loggedIn)
                    ->addConstraint('isPasswd')
                    ->setRequired(!Configuration::get('PS_GUEST_CHECKOUT_ENABLED'))
                    ->setHidden($loggedIn ||
                        (Configuration::get('PS_GUEST_CHECKOUT_ENABLED') && !$fieldOptions["visible"]));
            }

            if ($fieldName === 'id_gender') {
                $formField
                    ->setType('radio-buttons');

                foreach (Gender::getGenders($this->context->language->id) as $gender) {
                    $formField->addAvailableValue($gender->id, $gender->name);
                }

                $opc_form_radios = json_decode($this->context->cookie->opc_form_radios, true);
                if (isset($opc_form_radios['id_gender'])) {
                    $formField->setValue((int)$opc_form_radios['id_gender']);
                } else {
                    $formField->setValue($this->context->customer->id_gender);
                }
            }

            if ($fieldName === 'birthday') {
                $formField
                    ->setValue(("0000-00-00" !== $this->context->customer->birthday) ? $this->context->customer->birthday : '')
                    ->addAvailableValue('placeholder', Tools::getDateFormat())
                    ->addAvailableValue(
                        'comment',
                        $this->translator->trans('(E.g.: %date_format%)',
                            array('%date_format%' => Tools::formatDateStr('31 May 1970')), 'Shop.Forms.Help')
                    );
            }

            if ($fieldName === 'siret') {
                $formField
                    ->setValue($this->context->customer->siret);
            }

            if ($fieldName === 'company') {
                $formField
                    ->setValue($this->context->customer->company);
            }

            if ($fieldName === 'optin') {
                if (isset($opc_form_checkboxes['optin'])) {
                    $optin_value = ("true" === $opc_form_checkboxes['optin']);
                } else {
                    $optin_value = $this->context->customer->optin;
                }
                $formField
                    ->setType('checkbox')
                    ->setValue($optin_value);
            }

            // If it's not already required (other reasons than our config)...
            if (!$formField->isRequired() && $fieldName !== 'password') {
                // Only trust the $required array for fields
                // that are not marked as required.
                // $required doesn't have all the info, and fields
                // may be required for other reasons than what
                // AddressFormat::getFieldsRequired() says.
                $formField->setRequired(
                    $fieldOptions["required"] && $fieldOptions["visible"]
                );
            }

            if ($fieldName !== 'password') {
                $formField->setHidden(
                    !$fieldOptions["visible"] || $formField->getHidden()
                );
            }

            $formField->setLabel($this->getFieldLabel($fieldName));
            $formField->setWidth(
                $fieldOptions["width"]
            );

            $format[$formField->getName()] = $formField;
        }

        // Place any third party checkboxes / fields, at the end of customer fields;
        // e.g. x13privacymanager module
        foreach ($moduleFields as $moduleField) {
            $format[$moduleField->moduleName . '_' . $moduleField->getName()] = $moduleField;
        }

        /*
        $format = [
            'back'       => (new CheckoutFormField)
                ->setName('back')
                ->setType('hidden'),
            'email'      => (new CheckoutFormField)
                ->setName('email')
                ->setType('email')
                ->setRequired($this->module->config->customer_fields['email']['required'])
                ->setLabel($this->translator->trans(
                    'Email', array(), 'Shop.Forms.Labels'
                ))
                ->setHidden($loggedIn)
                ->addConstraint('isEmail')
                ->setValue($this->context->customer->email)
                ->setWidth($this->module->config->customer_fields['email']['width']),
            'password'   => (new CheckoutFormField)
                ->setName('password')
                ->setType('password')
                ->setRequired(!Configuration::get('PS_GUEST_CHECKOUT_ENABLED'))
                ->setLabel($this->translator->trans(
                    'Password', array(), 'Shop.Forms.Labels'
                ))
                ->setHidden($loggedIn ||
                    (Configuration::get('PS_GUEST_CHECKOUT_ENABLED')
                        && !$this->module->config->customer_fields['password']['visible']))
                ->addConstraint('isPasswd')
                ->setWidth($this->module->config->customer_fields['password']['width']),
            'id_address' => (new CheckoutFormField)
                ->setName('id_address')
                ->setType('hidden'),
//            'id_customer' => (new CheckoutFormField)
//                ->setName('id_customer')
//                ->setType('hidden'),
            'token'      => (new CheckoutFormField)
                ->setName('token')
                ->setType('hidden')
                ->setValue($this->makeAddressPersister()->getToken()),
//            'alias'      => (new CheckoutFormField)
//                ->setName('alias')
//                ->setLabel(
//                    $this->getFieldLabel('alias')
//                )
        ];


        if ($this->module->config->customer_fields['gender']['visible']) {
            $genderField = (new CheckoutFormField)
                ->setName('id_gender')
                ->setType('radio-buttons')
                ->setRequired($this->module->config->customer_fields['gender']['required'])
                ->setLabel(
                    $this->translator->trans(
                        'Social title', array(), 'Shop.Forms.Labels'
                    )
                );
            foreach (Gender::getGenders($this->context->language->id) as $gender) {
                $genderField->addAvailableValue($gender->id, $gender->name);
            }

            $opc_form_radios = json_decode($this->context->cookie->opc_form_radios, true);
            if (isset($opc_form_radios['id_gender'])) {
                $genderField->setValue((int)$opc_form_radios['id_gender']);
            } else {
                $genderField->setValue($this->context->customer->id_gender);
            }

            $format[$genderField->getName()] = $genderField;
        }
*/

        /*
        if (Configuration::get('PS_B2B_ENABLE')) {
            $format['company'] = (new CheckoutFormField)
                ->setName('company')
                ->setType('text')
                ->setValue($this->context->customer->company)
                ->setLabel($this->translator->trans(
                    'Company', array(), 'Shop.Forms.Labels'
                ));
            $format['siret']   = (new CheckoutFormField)
                ->setName('siret')
                ->setType('text')
                ->setValue($this->context->customer->siret)
                ->setLabel($this->translator->trans(
                // Please localize this string with the applicable registration number type in your country. For example : "SIRET" in France and "Código fiscal" in Spain.
                    'Identification number', array(), 'Shop.Forms.Labels'
                ));
        }
        */

        /*
        if ($this->module->config->customer_fields['birthdate']['visible']) {
            $format['birthday'] = (new CheckoutFormField)
                ->setName('birthday')
                ->setType('text')
                ->setRequired($this->module->config->customer_fields['birthdate']['required'])
                ->setLabel(
                    $this->translator->trans(
                        'Birthdate', array(), 'Shop.Forms.Labels'
                    )
                )
                ->setWidth($this->module->config->customer_fields['birthdate']['width'])
                ->setValue(("0000-00-00" !== $this->context->customer->birthday) ? $this->context->customer->birthday : '')
                ->addAvailableValue('placeholder', Tools::getDateFormat())
                ->addAvailableValue(
                    'comment',
                    $this->translator->trans('(E.g.: %date_format%)',
                        array('%date_format%' => Tools::formatDateStr('31 May 1970')), 'Shop.Forms.Help')
                );
        }
        */
        /*
                $opc_form_checkboxes = json_decode($this->context->cookie->opc_form_checkboxes, true);

                if ($this->module->config->customer_fields['optin']['visible']) {
                    if (isset($opc_form_checkboxes['optin'])) {
                        $optin_value = ("true" === $opc_form_checkboxes['optin']);
                    } else {
                        $optin_value = $this->context->customer->optin;
                    }
                    $format['optin'] = (new CheckoutFormField)
                        ->setName('optin')
                        ->setType('checkbox')
                        ->setValue($optin_value)
                        ->setRequired($this->module->config->customer_fields['optin']['required'])
                        ->setLabel(
                            $this->translator->trans(
                                'Receive offers from our partners', array(), 'Shop.Theme.Customeraccount'
                            )
                        );
                }
        */

        return array($format, $separateModuleFields);
    }

    public function api_getAddressSelectionTplVars()
    {
        return $this->getAddressesSelectionTplVars();
    }

    private function getAddressesSelectionTplVars()
    {
        $addressesNonZero   = $this->context->cart->id_address_invoice != 0 && $this->context->cart->id_address_delivery != 0;
        $addressesDifferent = $this->context->cart->id_address_invoice !== $this->context->cart->id_address_delivery;

        // show second address only if:
        // - both addresses are different and set
        // - on of address is not yet set and expand option is enabled
        $showSecondAddress = ($this->module->config->expand_second_address && !$addressesNonZero) ||
            ($addressesDifferent && $addressesNonZero);

        $isInvoiceAddressPrimary = (Config::ADDRESS_TYPE_INVOICE === $this->module->config->primary_address);

        if ($isInvoiceAddressPrimary) {
            $showBillToDifferentAddress = false;
            $showShipToDifferentAddress = $showSecondAddress;
            $isInvoiceAddressPrimary    = true;
        } else {
            $showBillToDifferentAddress = $showSecondAddress;
            $showShipToDifferentAddress = false;
        }

        // *** For logged-in customer, prepare deliveryAddressSelection and invoiceAddressSelection comboboxes
        // Same combo for both delivery and invoice addresses; actual cart addresses will be disabled in markup
        $customerAddresses          = array();
        $addressesList              = array();
        $lastOrderInvoiceAddressId  = 0;
        $lastOrderDeliveryAddressId = 0;

        if ($this->context->customer->isLogged()) {
            $customerAddresses = $this->context->customer->getSimpleAddresses();
            foreach ($customerAddresses as &$a) {
                $a['formatted'] = AddressFormat::generateAddress(new Address($a['id']), array(), '<br>');
            }
            $allCustomerUsedAddresses = $this->getAllCustomerUsedAddresses();

            $usedDeliveryAddresses = array();
            $usedInvoiceAddresses  = array();
            foreach ($allCustomerUsedAddresses as $addressPair) {
                if (count($addressPair)) {
                    if (isset($addressPair['id_address_delivery'])) {
                        $usedDeliveryAddresses[] = $addressPair['id_address_delivery'];
                    }
                    if (isset($addressPair['id_address_invoice'])) {
                        $usedInvoiceAddresses[] = $addressPair['id_address_invoice'];
                    }
                }
            }
            foreach ($customerAddresses as $addressId => $addressItem) {

                if (null == $addressItem) {
                    // Do nothing for now; $addressItem object is just prepared here for customization (if any)
                }

                $usedForDelivery = in_array($addressId, $usedDeliveryAddresses);
                $usedForInvoice  = in_array($addressId, $usedInvoiceAddresses);

                // Add to delivery addresses list? All except addresses used exclusively for invoicing
                if ($usedForDelivery || !$usedForInvoice) {
                    $addressesList['delivery'][$addressId] = $customerAddresses[$addressId];
                }
                // Add to invoice addresses list? All except addresses used exclusively for delivery
                if ($usedForInvoice || !$usedForDelivery) {
                    $addressesList['invoice'][$addressId] = $customerAddresses[$addressId];
                }
                // Data preparation for other purposes, e.g. setting up this address filter in PS 'addresses'
                // For that, controllers/front/AddressesController.php needs to include this in initContent():
                //
                //        if (file_exists(_PS_MODULE_DIR_ . 'thecheckout/controllers/front/front.php')) {
                //            include_once(_PS_MODULE_DIR_ . 'thecheckout/controllers/front/front.php');
                //            $tc_frontController = new TheCheckoutModuleFrontController();
                //            $delivery_invoice_addresses = $tc_frontController->api_getAddressSelectionTplVars();
                //
                //            $this->context->smarty->assign('delivery_invoice_addresses', $delivery_invoice_addresses);
                //        }
                //
                // And respective template, /themes/classic/templates/customer/addresses.tpl shall be also updated
                // $addressesList['invoice'] + $addressesList['usedDeliveryExclusive'] make up "full set",
                // as 'invoice' includes also addresses we can't exactly say are invoice or delivery:
                //
                //  {if isset($delivery_invoice_addresses) && isset($delivery_invoice_addresses.addressesList)}
                //    {if isset($delivery_invoice_addresses.addressesList.invoice)}
                //      <div class="invoice-primary-addresses" style="float:left;width:100%">
                //      <h2>{l s='Primary and invoice addresses' d='Shop.Theme.Customeraccount'}</h2>
                //      {foreach $delivery_invoice_addresses.addressesList.invoice as $address}
                //          <div class="col-lg-4 col-md-6 col-sm-6">
                //          {block name='customer_address'}
                //            {include file='customer/_partials/block-address.tpl' address=$address}
                //          {/block}
                //          </div>
                //      {/foreach}
                //      </div>
                //    {/if}
                //  {/if}
                //
                //  {if isset($delivery_invoice_addresses) && isset($delivery_invoice_addresses.addressesList)}
                //    {if isset($delivery_invoice_addresses.addressesList.usedDeliveryExclusive)}
                //      <div class="delivery-addresses" style="float:left;width:100%">
                //      <h2>{l s='Delivery addresses' d='Shop.Theme.Customeraccount'}</h2>
                //      {foreach $delivery_invoice_addresses.addressesList.usedDeliveryExclusive as $address}
                //          <div class="col-lg-4 col-md-6 col-sm-6">
                //          {block name='customer_address'}
                //            {include file='customer/_partials/block-address.tpl' address=$address}
                //          {/block}
                //          </div>
                //      {/foreach}
                //      </div>
                //    {/if}
                //  {/if}
                //
                if ($usedForDelivery && !$usedForInvoice) {
                    $addressesList['usedDeliveryExclusive'][$addressId] = $customerAddresses[$addressId];
                }
                if ($usedForInvoice && !$usedForDelivery) {
                    $addressesList['usedInvoiceExclusive'][$addressId] = $customerAddresses[$addressId];
                }
                if (!$usedForInvoice && !$usedForDelivery) {
                    $addressesList['notUsed'][$addressId] = $customerAddresses[$addressId];
                }
            }

            $lastOrderAddresses = $this->getCustomerLastUsedAddresses($allCustomerUsedAddresses);
            if (count($lastOrderAddresses)) {
                $lastOrderInvoiceAddressId  = $lastOrderAddresses['id_address_invoice'];
                $lastOrderDeliveryAddressId = $lastOrderAddresses['id_address_delivery'];
            }
        }


        return array(
            "addressesList"              => $addressesList,
            "idAddressInvoice"           => $this->context->cart->id_address_invoice,
            "idAddressDelivery"          => $this->context->cart->id_address_delivery,
            "isInvoiceAddressPrimary"    => $isInvoiceAddressPrimary,
            "showBillToDifferentAddress" => $showBillToDifferentAddress,
            "showShipToDifferentAddress" => $showShipToDifferentAddress,
            "lastOrderInvoiceAddressId"  => $lastOrderInvoiceAddressId,
            "lastOrderDeliveryAddressId" => $lastOrderDeliveryAddressId,
        );
    }

    private function getCheckoutFields()
    {
        $formFieldsLogin = $this->formatLoginFormFields();
        list($formFieldsAccount, $separateModuleFields) = $this->setupFormFieldsAccount();
        $formFieldsInvoice  = $this->setupFormFieldsInvoice();
        $formFieldsDelivery = $this->setupFormFieldsDelivery();

        $formFieldsInvoiceMapped = array_map(
            function (CheckoutFormField $field) {
                return $field->toArray();
            },
            $formFieldsInvoice
        );

        // By default, hide business fields; unless, there is some field in invoice address section with non-empty value
        $hideBusinessFields = true;
        foreach (array_map('trim', explode(',', $this->module->config->business_fields)) as $businessFieldName) {
            if (
                '' != $businessFieldName &&
                isset($formFieldsInvoiceMapped[$businessFieldName]) &&
                null != trim($formFieldsInvoiceMapped[$businessFieldName]['value']) &&
                'id_state' !== $formFieldsInvoiceMapped[$businessFieldName]['name'] &&
                'id_country' !== $formFieldsInvoiceMapped[$businessFieldName]['name']
            ) {
                $hideBusinessFields = false;
            }
        }

        // Old code, when business fields were hard-coded
//        $hideBusinessFields =
//            (null == trim($formFieldsInvoiceMapped['company']['value'])) &&
//            (null == trim($formFieldsInvoiceMapped['dni']['value'])) &&
//            (null == trim($formFieldsInvoiceMapped['vat_number']['value']));


        $checkoutFields = array(
            'formFieldsLogin'      => array_map(
                function (CheckoutFormField $field) {
                    return $field->toArray();
                },
                $formFieldsLogin
            ),
            'action'               => $this->getCurrentURL(),
            'urls'                 => $this->getTemplateVarUrls(),
            'formFieldsAccount'    => array_map(
                function (CheckoutFormField $field) {
                    return $field->toArray();
                },
                $formFieldsAccount
            ),
            'formFieldsInvoice'    => $formFieldsInvoiceMapped,
            'hideBusinessFields'   => $hideBusinessFields,
            'formFieldsDelivery'   => array_map(
                function (CheckoutFormField $field) {
                    return $field->toArray();
                },
                $formFieldsDelivery
            ),
            'separateModuleFields' => array_map(
                function (CheckoutFormField $field) {
                    return $field->toArray();
                },
                $separateModuleFields
            ),
        );

        return array_merge($checkoutFields, $this->getAddressesSelectionTplVars());
    }

    public function parentInitContent()
    {
        static $initContent_called = false;
        if ($initContent_called) {
            return;
        } else {
            $initContent_called = true;
        }
        parent::initContent();
    }

    private function checkAndMakeSubmitReorderRequest()
    {
        if (Tools::isSubmit('submitReorder') && $id_order = (int)Tools::getValue('id_order')) {
            $oldCart     = new Cart(Order::getCartIdStatic($id_order, $this->context->customer->id));
            $duplication = $oldCart->duplicate();
            if (!$duplication || !Validate::isLoadedObject($duplication['cart'])) {
                $this->errors[] = $this->trans('Sorry. We cannot renew your order.', array(),
                    'Shop.Notifications.Error');
            } elseif (!$duplication['success']) {
                $this->errors[] = $this->trans(
                    'Some items are no longer available, and we are unable to renew your order.', array(),
                    'Shop.Notifications.Error'
                );
            } else {
                $this->context->cookie->id_cart = $duplication['cart']->id;
                $context                        = $this->context;
                $context->cart                  = $duplication['cart'];
                CartRule::autoAddToCart($context);
                $this->context->cookie->write();
                Tools::redirect('index.php?controller=order');
            }
        }
    }

    public function initContent()
    {

        // Can we skip it for ajax calls? parent::initContent set caches for delivery options,
        // if enabled here, we'd need to flush caches before ajax call
        //parent::initContent();

        if (Configuration::get('PS_RESTRICT_DELIVERED_COUNTRIES')) {
            $this->availableCountries = Carrier::getDeliveredCountries($this->context->language->id, true, true);
        } else {
            $this->availableCountries = Country::getCountries($this->context->language->id, true);
        }

        $this->context->cart->setNoMultishipping();


        if (Tools::getValue('ajax_request')) {
            // $this->parentInitContent(); cannot be called prior to address_id modification in cart!
            // otherwise, cached delivery methods will be used and would not reflect actual address change
            // we need to call then parentInitContent only after address modification
            // E.g. on 'updateQuantity' action, the cache key does not change and Cart::getPackageShippingCost returns
            // same shipping amount for carriers (quantity is not part of cache_key)
            $action = Tools::getValue('action');
            if (!in_array($action,
                array('modifyAccountAndAddress', 'modifyAddressSelection', 'modifyAccount', 'updateQuantity'))) {
                $this->parentInitContent();
            }
            return $this->ajaxCall();
        } else {
            $this->parentInitContent();
            $blocksLayout = $this->module->config->blocks_layout;

            $excludeBlocks = array();

            // Remove html-box-X from layout, if it's empty
            if (empty($this->module->config->html_box_1)) {
                $excludeBlocks[] = "html-box-1";
            }
            if (empty($this->module->config->html_box_2)) {
                $excludeBlocks[] = "html-box-2";
            }
            if (empty($this->module->config->html_box_3)) {
                $excludeBlocks[] = "html-box-3";
            }
            if (empty($this->module->config->html_box_4)) {
                $excludeBlocks[] = "html-box-4";
            }
            if (empty($this->module->config->required_checkbox_1)) {
                $excludeBlocks[] = "required-checkbox-1";
            }
            if (empty($this->module->config->required_checkbox_2)) {
                $excludeBlocks[] = "required-checkbox-2";
            }

            if ($this->context->customer->isLogged()) {
                $excludeBlocks[] = "login-form";

                // If customer is already logged-in AND this is first time he visited checkout form with his session
                // let's reset his addresses to ones used with last order (if any)
                if ($this->context->cart->id != $this->context->cookie->addreses_reset_at_cart_id) {
                    $lastOrderAddresses = $this->getCustomerLastUsedAddresses($this->getAllCustomerUsedAddresses());
                    if (count($lastOrderAddresses)) {
                        $this->context->cart->id_address_invoice  = $lastOrderAddresses['id_address_invoice'];
                        $this->context->cart->id_address_delivery = $lastOrderAddresses['id_address_delivery'];
                        $this->context->cart->update();
                        $this->context->cart->setNoMultishipping();
                        $this->updateAddressIdInDeliveryOptions();
                        $this->context->cookie->addreses_reset_at_cart_id = $this->context->cart->id;
                    }
                }
            }

            $conditionsToApproveFinder = new ConditionsToApproveFinder(
                $this->context,
                $this->getTranslator()
            );

            $conditionsToApprove = $conditionsToApproveFinder->getConditionsToApproveForTemplate();

            $this->context->smarty->assign($this->getCheckoutFields());

            // disable entirely customer fields blocks, if their content will be empty
            // this is just to fix visual issue, so that no block container is rendered in front.tpl
            $separateModuleFields = $this->context->smarty->getTemplateVars('separateModuleFields');
            if (!in_array('ps_emailsubscription_newsletter', array_keys($separateModuleFields))) {
                $excludeBlocks[] = 'newsletter';
            }
            if (!in_array('psgdpr_psgdpr', array_keys($separateModuleFields))) {
                $excludeBlocks[] = 'psgdpr';
            }
            if (!in_array('ps_dataprivacy_customer_privacy', array_keys($separateModuleFields))) {
                $excludeBlocks[] = 'data-privacy';
            }

            $ps_config = array();
            foreach (array('PS_GUEST_CHECKOUT_ENABLED') as $configName) {
                $ps_config[$configName] = Configuration::get($configName);
            }

            $force_email_overlay = false;
            if ($this->module->config->force_email_overlay && !$this->context->customer->isLogged() && !$this->context->customer->id) {
                $force_email_overlay = true;
            }

            $page                                                           = parent::getTemplateVarPage();
            $page["body_classes"]["logged-in"]                              = $this->context->customer->isLogged();
            $page["body_classes"]["mark-required"]                          = $this->module->config->mark_required_fields;
            $page["body_classes"][$this->module->config->checkout_substyle] = true;
            $page["body_classes"]["using-material-icons"]                   = $this->module->config->using_material_icons;
            $page["body_classes"]["font-" . $this->module->config->font]    = true;
            $page["body_classes"]["social-btn-style-"
            . $this->module->config->social_login_btn_style]                = true;
            $page["body_classes"]["is-empty-cart"]                          = (0 == $this->context->cart->nbProducts());
            $page["body_classes"]["is-virtual-cart"]                        = $this->context->cart->isVirtualCart();
            $page["body_classes"]["is-test-mode"]                           = $this->module->config->test_mode;
            $page["body_classes"]["compact-cart"]                           = $this->module->config->compact_cart;
            $page["body_classes"]["force-email-overlay"]                    = $force_email_overlay;

            $this->context->smarty->assign(array(
                "blocksLayout"          => $blocksLayout,
                "excludeBlocks"         => $excludeBlocks,
                "config"                => $this->module->config,
                "businessFieldsList"    => array_map('trim', explode(',', $this->module->config->business_fields)),
                "ps_config"             => $ps_config,
                "debugJsController"     => $this->module->debugJsController,
                'conditions_to_approve' => $conditionsToApprove,

                "loadEmpty"                => true,// Do not "fill" blocks with content, load only container
                "page"                     => $page,
                "hook_create_account_form" => Hook::exec('displayCustomerAccountForm'),
                "opc_form_checkboxes"      => json_decode($this->context->cookie->opc_form_checkboxes, true),
                'delivery_message'         => (version_compare(_PS_VERSION_,
                        '1.7.3') >= 0) ? $this->getCheckoutSession()->getMessage() : '',
                'isEmptyCart'              => (0 == $this->context->cart->nbProducts()),
            ));

            $this->setTemplate('module:' . $this->name . '/views/templates/front/front.tpl');
        }
    }

    // PS copied method
    protected function getCheckoutSession()
    {
        $deliveryOptionsFinder = new DeliveryOptionsFinder(
            $this->context,
            $this->getTranslator(),
            $this->objectPresenter,
            new PriceFormatter()
        );

        $session = new CheckoutSession(
            $this->context,
            $deliveryOptionsFinder
        );

        return $session;
    }


    // PS copied method
    private function getGiftCostForLabel($giftCost, $includeTaxes, $displayTaxLabel)
    {
        if ($giftCost != 0) {
            $taxLabel       = '';
            $priceFormatter = new PriceFormatter();

            if ($includeTaxes && $displayTaxLabel) {
                $taxLabel .= ' tax incl.';
            } elseif ($includeTaxes) {
                $taxLabel .= ' tax excl.';
            }

            return $this->getTranslator()->trans(
                ' (additional cost of %giftcost% %taxlabel%)',
                array(
                    '%giftcost%' => $priceFormatter->convertAndFormat($giftCost),
                    '%taxlabel%' => $taxLabel,
                ),
                'Shop.Theme.Checkout'
            );
        }

        return '';
    }

    private function updateAddressIdInDeliveryOptions()
    {
        if ($this->context->cart->id_address_delivery > 0) {
            if (version_compare(_PS_VERSION_, '1.7.3') >= 0) {
                $actualDeliveryOptions = json_decode($this->context->cart->delivery_option, true);
            } else {
                $actualDeliveryOptions = Tools::unSerialize($this->context->cart->delivery_option);
            }

            if (false !== $actualDeliveryOptions && null !== $actualDeliveryOptions) {
                $newDeliveryOptions = array();
                foreach ($actualDeliveryOptions as $dlvOption) {
                    $newDeliveryOptions[$this->context->cart->id_address_delivery] = $dlvOption;
                }
                if (version_compare(_PS_VERSION_, '1.7.3') >= 0) {
                    $this->context->cart->delivery_option = json_encode($newDeliveryOptions);
                } else {
                    $this->context->cart->delivery_option = serialize($newDeliveryOptions);
                }
            }
            $this->context->cart->autosetProductAddress();
        }
    }

    // PS copied method (partial)
    public function getShippingOptions()
    {

        $recyclablePackAllowed = (bool)Configuration::get('PS_RECYCLABLE_PACK');
        $giftAllowed           = (bool)Configuration::get('PS_GIFT_WRAPPING');

        $includeTaxes = (!Product::getTaxCalculationMethod((int)$this->context->cart->id_customer)
            && (int)Configuration::get('PS_TAX'));

        $displayTaxLabels = (Configuration::get('PS_TAX') && !Configuration::get('AEUC_LABEL_TAX_INC_EXC'));
        $giftCost         = $this->context->cart->getGiftWrappingPrice($includeTaxes);

        $this->context->cart->save();
        $this->context->cart->setNoMultishipping();
        $this->updateAddressIdInDeliveryOptions();

        return
            array(
                'hookDisplayBeforeCarrier' => Hook::exec('displayBeforeCarrier',
                    array('cart' => $this->getCheckoutSession()->getCart())),
                'hookDisplayAfterCarrier'  => Hook::exec('displayAfterCarrier',
                    array('cart' => $this->getCheckoutSession()->getCart())),
                'id_address'               => $this->getCheckoutSession()->getIdAddressDelivery(),
                'delivery_options'         => $this->getCheckoutSession()->getDeliveryOptions(),
                'delivery_option'          => $this->getCheckoutSession()->getSelectedDeliveryOption(),
                'recyclable'               => $this->getCheckoutSession()->isRecyclable(),
                'recyclablePackAllowed'    => $recyclablePackAllowed,
                'delivery_message'         => (version_compare(_PS_VERSION_,
                        '1.7.3') >= 0) ? $this->getCheckoutSession()->getMessage() : '',
                'gift'                     => array(
                    'allowed' => $giftAllowed,
                    'isGift'  => $this->getCheckoutSession()->getGift()['isGift'],
                    'label'   => $this->getTranslator()->trans(
                        'I would like my order to be gift wrapped %cost%',
                        array('%cost%' => $this->getGiftCostForLabel($giftCost, $includeTaxes, $displayTaxLabels)),
                        'Shop.Theme.Checkout'
                    ),
                    'message' => $this->getCheckoutSession()->getGift()['message'],
                ),
            );
    }

//    public function selectPaymentOption(array $requestParams = array())
//    {
//        if (isset($requestParams['select_payment_option'])) {
//            $this->selected_payment_option = $requestParams['select_payment_option'];
//        }
//
//        $this->setTitle(
//            $this->getTranslator()->trans(
//                'Payment',
//                array(),
//                'Shop.Theme.Checkout'
//            )
//        );
//    }

    public function getPaymentOptions()
    {
        $isFree               = 0 == (float)$this->getCheckoutSession()->getCart()->getOrderTotal(true, Cart::BOTH);
        $paymentOptionsFinder = new PaymentOptionsFinder();
        $paymentOptions       = $paymentOptionsFinder->present($isFree);

        return array(
            'is_free'                 => $isFree,
            'payment_options'         => $paymentOptions,
            'selected_payment_option' => $this->selected_payment_option,
            //'selected_delivery_option' => $selectedDeliveryOption,
            'show_final_summary'      => Configuration::get('PS_FINAL_SUMMARY_ENABLED'),
        );
    }

    public function ajaxCall()
    {
        if ($this->module->debug) {
            $this->module->logDebug("[AJAX*Start] " . Tools::getValue('action'));
        }

        $action = Tools::ucfirst(Tools::getValue('action'));

        if (!empty($action) && method_exists($this, 'ajax' . $action)) {
            $this->context->smarty->assign("config", $this->module->config);
            $result = $this->{'ajax' . $action}();
        } else {
            $result = (array('error' => 'Ajax parameter used, but action \'' . Tools::getValue('action') . '\' is not defined'));
        }

        if ($this->module->debug) {
            $this->module->logDebug("[AJAX*End] " . Tools::getValue('action'));
        }

        die(json_encode($result));
    }

    public function updateDelivery(array $requestParams = array())
    {
        if (isset($requestParams['delivery_option'])) {
            $this->getCheckoutSession()->setDeliveryOption(
                $requestParams['delivery_option']
            );
            $this->getCheckoutSession()->setRecyclable(
                isset($requestParams['recyclable']) ? $requestParams['recyclable'] : false
            );
            $this->getCheckoutSession()->setGift(
                isset($requestParams['gift']) ? $requestParams['gift'] : false,
                (isset($requestParams['gift']) && isset($requestParams['gift_message'])) ? $requestParams['gift_message'] : ''
            );
        }

        if (isset($requestParams['delivery_message'])) {
            $this->getCheckoutSession()->setMessage($requestParams['delivery_message']);
        }

        Hook::exec('actionCarrierProcess', array('cart' => $this->getCheckoutSession()->getCart()));
    }

    private function ajaxSelectDeliveryOption()
    {

        $this->updateDelivery(Tools::getAllValues());

        return $this->getDynamicCheckoutBlocks();

    }

    private function ajaxSelectPaymentOption()
    {
        // selects payment option here (may go to cookie?), but most importantly, set fee
        // if fee value has been sent from client

        $result = array();

        $paymentFee = Tools::getValue('payment_fee');
        $result     = $this->getCartSummaryBlock($paymentFee);

        return $result;

    }

    private function ajaxSetDeliveryMessage()
    {
        if (Tools::getIsset('delivery_message')) {
            $this->getCheckoutSession()->setMessage(Tools::getValue('delivery_message'));
        }
        return array('result' => 1);
    }

    private function ajaxSocialLoginFacebook()
    {
        $access_token = Tools::getValue("access_token");

        $social = new SocialLogin(SocialLogin::FACEBOOK,
            $this->module->config->social_login_fb_app_id,
            $this->module->config->social_login_fb_app_secret);

        list($email, $firstname, $lastname) = $social->validateFacebookAccessToken($access_token);

        $errors = $this->loginOrRegister($email, $firstname, $lastname);

        return array('errors' => $errors, 'hasErrors' => !empty($errors), 'email' => $email);
    }

    private function ajaxSocialLoginGoogle()
    {
        $id_token  = Tools::getValue("id_token");
        $firstname = Tools::getValue("firstname");
        $lastname  = Tools::getValue("lastname");

        $social = new SocialLogin(SocialLogin::GOOGLE,
            $this->module->config->social_login_google_client_id,
            $this->module->config->social_login_google_client_secret);

        $email = $social->validateGoogleIdToken($id_token);

        $errors = $this->loginOrRegister($email, $firstname, $lastname);

        return array('errors' => $errors, 'hasErrors' => !empty($errors), 'email' => $email);
    }

    private function loginOrRegister($email, $firstname, $lastname)
    {
        if (!$email || !Validate::isEmail($email)) {
            return $this->translator->trans('Invalid email address.', array(), 'Shop.Notifications.Error');
        }
        $customer       = new Customer();
        $authentication = $customer->getByEmail(
            $email
        );

        $errors = array();

        if (isset($authentication->active) && !$authentication->active) {
            $errors[] = $this->translator->trans('Your account isn\'t available at this time, please contact us',
                array(),
                'Shop.Notifications.Error');
        } elseif (!$authentication || !$customer->id || $customer->is_guest) {
            // Create new account
            //$this->silentRegistration($email);

            // Make sure customer's first and lastname are valid, as the 'login' is automated
            if (Tools::strlen($firstname) < 1) {
                $firstname = ' ';
            }
            if (Tools::strlen($lastname) < 1) {
                $lastname = ' ';
            }

            $this->modifyAccount(
                array('email' => $email, 'password' => sha1(time() . uniqid(rand(), true))),
                $firstname, $lastname, true, false);

        } else {

            $this->context->updateCustomer($authentication);

            // Login information have changed, so we check if the cart rules still apply
            CartRule::autoRemoveFromCart($this->context);
            CartRule::autoAddToCart($this->context);
        }
    }

    // "sanitize" request data before posting to backend; make sure required fields are all set
    private function prepareAddressData($existingAddressId, $formData, $requiredFields, $previousErrors = array())
    {
        $addressData = $formData;

        foreach ($requiredFields as $fieldName) {
            if (!isset($addressData[$fieldName]) || empty($addressData[$fieldName])
                || (isset($previousErrors[$fieldName]) && count($previousErrors[$fieldName]))
            ) {
                if (in_array($fieldName, array('dni'))) {
                    // for 'dni' simulated value is empty string
                    $addressData[$fieldName] = '';
                } else {
                    $addressData[$fieldName] = ' '; // simulated value
                }
            }
        }
        // handle id_state specifically, as it might be not sent by jQuery.serialize() when empty
        if (!key_exists('id_state', $addressData)) {
            $addressData['id_state'] = 0;
        }
        if ($existingAddressId > 0) {
            $addressData['id_address'] = $existingAddressId;
        }
        return $addressData;
    }

    private function getCustomerSignInArea()
    {
        $this->parentInitContent();
        $customerSignInArea = array();
        // Re-render header (Sign-in/out and customer name)
        if ($moduleInstance = Module::getInstanceByName('ps_customersignin')) {
            if ($moduleInstance->active && $moduleInstance instanceof WidgetInterface) {
                $customerSignInArea['displayNav2']        = $moduleInstance->renderWidget('displayNav2', array());
                $customerSignInArea['staticCustomerInfo'] = $this->context->smarty->fetch(
                    'module:' . $this->name . '/views/templates/front/_partials/static-customer-info.tpl');
            }
        }
        return array('customerSignInArea' => $customerSignInArea);
    }

    private function getShippingOptionsBlock()
    {
        $this->parentInitContent();

        // setDeliveryOption() call would flush delivery_options cache (used in cart->getDeliveryOption())
        // Prior to this call, TheCheckout does not set cache, but other modules possibly can, causing
        // delivery options not matching id_address selected on checkout = no carriers available
        if (version_compare(_PS_VERSION_, '1.7.3') >= 0) {
            $actualDeliveryOptions = json_decode($this->context->cart->delivery_option, true);
        } else {
            $actualDeliveryOptions = Tools::unSerialize($this->context->cart->delivery_option);
        }
        if (!empty($actualDeliveryOptions)) {
            $this->getCheckoutSession()->setDeliveryOption(
                array($this->context->cart->id_address_delivery => reset($actualDeliveryOptions))
            );
        }

        $shippingOptions         = $this->getShippingOptions();
        $externalShippingModules = array();
        foreach ($shippingOptions["delivery_options"] as $optionId => $options) {
            if ("1" === $options['is_module'] && "" !== $options['external_module_name']) {
                $externalShippingModules[$options['external_module_name']] = $optionId;
            }
        }

        $this->getCheckoutSession()->setDeliveryOption(
            array($this->context->cart->id_address_delivery => $shippingOptions["delivery_option"])
        );


        $shippingCountryName = '';
        if ($this->module->config->show_shipping_country_in_carriers) {
            // delivery address id
            $tmpDeliveryAddress = new Address($this->context->cart->id_address_delivery);
            if (isset($tmpDeliveryAddress->id_country)) {
                $tmpIdCountry = (int)$tmpDeliveryAddress->id_country;
            } else {
                $tmpIdCountry = Configuration::get('PS_COUNTRY_DEFAULT');
            }

            // localized country name
            $tmpCountry = new Country($tmpIdCountry, $this->context->language->id);
            if (isset($tmpCountry) && isset($tmpCountry->name)) {
                $shippingCountryName = $tmpCountry->name;
                $this->context->smarty->assign('shippingCountry', $shippingCountryName);
            }
        }

        $this->context->smarty->assign($shippingOptions);
        $shippingBlock = $this->context->smarty->fetch('module:' . $this->name . '/views/templates/front/blocks/shipping.tpl');

        return array(
            'externalShippingModules' => $externalShippingModules,
            'shippingBlock'           => $shippingBlock,
            'shippingBlockChecksum'   => md5($shippingBlock),
            'shippingCountry'         => $shippingCountryName,
            'totalWeight'             => $this->context->cart->getTotalWeight()
        );
    }

    private function getPaymentOptionsBlock()
    {
        $this->parentInitContent();
        if (Configuration::get('PS_FINAL_SUMMARY_ENABLED')) {
            // if $this->context->customer->id and addresses assigned.. only then continue
            //$this->parentInitContent();
//            $cart = $this->cart_presenter->present(
//                $this->context->cart
//            );
//
//
//            $this->context->smarty->assign(array('cart' => $cart));
//            $this->context->smarty->assign(array('customer' => $this->getTemplateVarCustomer(2)));
        }

        $paymentMethods = $this->getPaymentOptions();

        $this->context->smarty->assign($paymentMethods);

        // We need to delivery conditions to approve status (ticket by customer earlier in session)
        $this->context->smarty->assign(array(
                'opc_form_checkboxes' => json_decode($this->context->cookie->opc_form_checkboxes,
                    true)
            )
        );

        $paymentBlock =
            $this->context->smarty->fetch('module:' . $this->name . '/views/templates/front/blocks/payment.tpl');


        return array(
            'paymentBlock'         => $paymentBlock,
            'paymentBlockChecksum' => md5($paymentBlock),
            'paymentMethodsList'   => array_keys($paymentMethods['payment_options'])
        );
    }

    private function getDynamicCheckoutBlocks()
    {
        if (!$this->context->cart->nbProducts()) {
            return array('emptyCart' => true);
        }

        $this->context->smarty->assign(
            array(
                'shipping_payment_blocks_wait_for_selection' =>
                    $this->module->config->force_customer_to_choose_country && !$this->context->cart->id_address_delivery,
                'force_email_wait_for_enter'                 =>
                    $this->module->config->force_email_overlay && !$this->context->customer->isLogged() && !$this->context->customer->id,
            )
        );

        return array_merge(
            array(
                'triggerElementName' => Tools::getValue('trigger')
            ),
            $this->getShippingOptionsBlock(),
            $this->getPaymentOptionsBlock(),
            $this->getCartSummaryBlock()
        );
    }

    private function ajaxGetShippingAndPaymentBlocks()
    {
        return $this->getDynamicCheckoutBlocks();
    }

    protected function makeAddressPersister()
    {
        return new CheckoutCustomerAddressPersister(
            $this->context->customer,
            $this->context->cart,
            Tools::getToken(true, $this->context)
        );
    }

    private function getTransientAddressByCartId($cart_id)
    {
        $query = new DbQuery();
        $query->select('id_address');
        $query->from('address');
        $query->where('alias = \'opc_' . (int)$cart_id . '\'');
        $query->where('deleted = 0');
        $query->where('id_address NOT IN(\'' . (int)$this->context->cart->id_address_delivery . '\', \'' . (int)$this->context->cart->id_address_invoice . '\')');
        $query->orderBy('id_address DESC');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    private function getAllCustomerUsedAddresses()
    {
        $result = array();
        if ($this->context->customer->isLogged()) {
            $query = new DbQuery();
            $query->select('id_address_invoice, id_address_delivery');
            $query->from('orders');
            $query->where('id_customer = \'' . (int)$this->context->customer->id . '\'');

            $query->orderBy('id_order DESC');
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        }
        return $result;
    }

    private function getCustomerLastUsedAddresses($allOrdersAddresses)
    {
        $lastOrderAddresses = array();

        if (count($allOrdersAddresses)) {
            $lastOrderAddresses = $allOrdersAddresses[0];

            // What if address IDs used with last order are already deleted? Handle that here:
            if (count($lastOrderAddresses)) {
                if ($lastOrderAddresses['id_address_invoice'] == $lastOrderAddresses['id_address_delivery']) {
                    $invActive = Customer::customerHasAddress((int)$this->context->customer->id,
                        $lastOrderAddresses['id_address_invoice']);
                    if (!$invActive) {
                        $lastOrderAddresses = array();
                    }
                } else {
                    $invActive = Customer::customerHasAddress((int)$this->context->customer->id,
                        $lastOrderAddresses['id_address_invoice']);
                    $dlvActive = Customer::customerHasAddress((int)$this->context->customer->id,
                        $lastOrderAddresses['id_address_delivery']);
                    if (!$invActive && !$dlvActive) {
                        $lastOrderAddresses = array();
                    } elseif ($invActive && !$dlvActive) {
                        $lastOrderAddresses['id_address_delivery'] = $lastOrderAddresses['id_address_invoice'];
                    } elseif (!$invActive && $dlvActive) {
                        $lastOrderAddresses['id_address_invoice'] = $lastOrderAddresses['id_address_delivery'];
                    }
                }
            }//if (count($result))
        }

        return $lastOrderAddresses;
    }

    private function modifyAddress($addressType, $formData, $shallCreateNewAddress, $finalConfirmation = false)
    {
        // firstly, let's disallow address modification, if country is not supplied and
        // 'force_customer_to_choose_country' is ON = we do not do any operations on addresses until we have country ID
        if (!isset($formData['id_country']) && $this->module->config->force_customer_to_choose_country) {
            return array(
                'errors'    => array('country_not_selected'),
                'hasErrors' => true
            );
        }

        // Primary address can be updated freely; secondary only if addresses are not same
        // Note: For now (Nov.2018), isPrimaryAddress won't be used and we'll treat both addresses separately
        // although, there's slight difference, and when shipping address is first, in cart both addresses
        // are set to non-zero once the shipping is updated; whilst when billing address is primary, delivery
        // address remain zero up until it's updated.

        /*
        $primaryAddress = $this->module->config->primary_address;
        // if secondary address is being updated, and ID=primary address id, create new one
        $isPrimaryAddress = strpos($addressType, $primaryAddress) > -1;
        */

        $isAddressTypeInvoice = strpos($addressType, 'invoice') > -1;

        // required fields pushed to validator
        // Invoice and Delivery address have separate treatment, just for the sake of hardcoded customization if necessary
        // -  isset($formData[$key]) means that we'll require fields only when they are sent from client (i.e. they are :visible)
        if ($isAddressTypeInvoice) {
            $theCheckout_requiredFields = array_filter($this->module->config->invoice_fields,
                function ($var, $key) use ($formData) {
                    return (true === $var['required'] && true === $var['visible'] && $key != 'State:name' && isset($formData[$key])); // State is managed automatically
                }, ARRAY_FILTER_USE_BOTH);
        } else {
            $theCheckout_requiredFields = array_filter($this->module->config->delivery_fields,
                function ($var, $key) use ($formData) {
                    return (true === $var['required'] && true === $var['visible'] && $key != 'State:name' && isset($formData[$key]));
                }, ARRAY_FILTER_USE_BOTH);
        }

        // Just to satisfy PS core validation; simulate values if necessary
        $psCore_requiredFields = array_unique(array_merge(
            array('firstname', 'lastname', 'address1', 'city'),
            array()//(new Address())->getCachedFieldsRequiredDatabase() // Is it necessary? ObjectModel doesn't seem to care, probably these extra fields are enforced only on controller levelF
        ));

        $countryId = (isset($formData['id_country'])) ? $formData['id_country'] : 0;
        $country   = ($countryId > 0) ? new Country($countryId) : $this->context->country;

        // Push States/Regions to frontview
        $states = array();
        if ($country->contains_states) {
            $states = State::getStatesByIdCountry($country->id);
        } else {
            // Reset state, so that zones are properly evaluated when switching from with_states country to no_states country
            unset($_POST['id_state']);
            unset($formData['id_state']);
        }

        if (!isset($formData['postcode'])) {
            //$formData['postcode'] = '_DO_NOT_REQUIRE_';
            $this->context->country->need_zip_code = false;
        }

        $theCheckout_addressForm = new CheckoutAddressForm(
            $this->context->smarty,
            $this->context->language,
            $this->getTranslator(),
            $this->makeAddressPersister(),
            new CheckoutAddressFormatter(
                $this->context->country,
                $this->getTranslator(),
                $this->availableCountries,
                array_keys($theCheckout_requiredFields)
            )
        );

        // We need to get validation errors, but also we need to save address despite that
        // Attempt to validate form data first:
        $theCheckout_addressForm->fillWith($formData);
        /*$validateAttempt = */
        $theCheckout_addressForm->validate();

        // regardless of result, we still need to simulate some required fields (psCore required and theCheckout required might differ)
        if ($shallCreateNewAddress) {

            // returns last address ID with alias "opc_CARTID"
            $existingAddressId = $this->getTransientAddressByCartId($this->context->cart->id);

            if (null == $existingAddressId) {
                $existingAddressId = 0;
            }
        } else {
            $existingAddressId = $isAddressTypeInvoice ? $this->context->cart->id_address_invoice : $this->context->cart->id_address_delivery;
        }

        $psCore_addressForm = new CheckoutAddressForm(
            $this->context->smarty,
            $this->context->language,
            $this->getTranslator(),
            $this->makeAddressPersister(),
            new CheckoutAddressFormatter(
                $this->context->country,
                $this->getTranslator(),
                $this->availableCountries,
                array() // required fields; we don't want any validation troubles here
            )
        );

        // Really save address, with simulated values if necessary
        // if $existingAddressId > 0, we will be implicitly updating existing address
        // TODO: handle case, when address is used in already confirmed order (some other, previous order)!
        $addressSaved = $psCore_addressForm->fillWith(
            $this->prepareAddressData($existingAddressId, $formData, $psCore_requiredFields)
        )->submit($finalConfirmation);

        // $psCore_addressForm shall not have errors, unless customer entered wrong values, which might block
        // further processing and address simulation, so we need to override those user-entered values.
        if ($psCore_addressForm->hasErrors()) {
            $addressSaved = $psCore_addressForm->fillWith(
                $this->prepareAddressData($existingAddressId, $formData, $psCore_requiredFields,
                    $psCore_addressForm->getErrors())
            )->submit($finalConfirmation);
        }

        if ($addressSaved) {
            // store address-id
            $addressId = $psCore_addressForm->getAddress()->id;
            if ($isAddressTypeInvoice) {
                $this->context->cart->id_address_invoice = $addressId;
            } else {
                // restore previously selected carrier
                $this->context->cart->id_address_delivery = $addressId;
            }
            $this->context->cart->save();
            $this->context->cart->setNoMultishipping();

            // Update context's country ID, for correct payment methods view
            if (isset($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}) && $this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}) {
                $infos                  = Address::getCountryAndState((int)$this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                $tax_country            = new Country((int)$infos['id_country']);
                $this->context->country = $tax_country;
            }
        }

        $addressResult = array(
            'states'      => $states,
            'needZipCode' => (bool)$country->need_zip_code,
            'needDni'     => true,
            'errors'      => $theCheckout_addressForm->getErrors(),
            'hasErrors'   => $theCheckout_addressForm->hasErrors(),
        );

        return $addressResult;
    }

    private function silentRegistration($email)
    {
        $this->modifyAccount(array("email" => $email), " ", " ", true, false);
    }

    private function modifyAccount(
        $accountFormData,
        $firstname = " ",
        $lastname = " ",
        $silentRegistration = false,
        $passwordRequired = false
    ) {
        // from register form, we receive:
        // =1: only email
        // =2: email + password
        // =3: alternatively, + firstname, lastname
        // 1: register at least guest account (if guests are allowed), later on, we'll turn it into customer, if password is provided

        //$registerForm = $this->makeCustomerForm();

        $guestAllowedCheckout = !$passwordRequired && Configuration::get('PS_GUEST_CHECKOUT_ENABLED');

        $customerFormatter = new CheckoutCustomerFormatter(
            $this->getTranslator(),
            $this->context->language
        );

        $customerFormatter->setBirthdayRequired(
            !$this->context->customer->isLogged()
            && $this->module->config->customer_fields['birthday']['visible']
            && $this->module->config->customer_fields['birthday']['required']
        );

        $customerFormatter->setIdGenderRequired(
            !$this->context->customer->isLogged()
            && $this->module->config->customer_fields['id_gender']['visible']
            && $this->module->config->customer_fields['id_gender']['required']
        );

        $customerFormatter->setPartnerOptinRequired(
            !$this->context->customer->isLogged()
            && $this->module->config->customer_fields['optin']['visible']
            && $this->module->config->customer_fields['optin']['required']
        );

        // Handle optional email field - we need to simulate "some" email
        if (!$this->context->customer->isLogged()) {
            if (!$this->module->config->customer_fields['email']['visible'] ||
                (!$this->module->config->customer_fields['email']['required'] && '' == $accountFormData['email'])) {
                $accountFormData['email'] = $this->context->cart->id . '@autocreated.email';
            }
        }

        $registerForm = new CheckoutCustomerForm(
            $this->context->smarty,
            $this->context,
            $this->getTranslator(),
            $customerFormatter,
            $this->get('hashing'),
            new CustomerPersister(
                $this->context,
                $this->get('hashing'),
                $this->getTranslator(),
                $guestAllowedCheckout
            ),
            $this->getTemplateVarUrls()
        );

        $registerForm->setGuestAllowed($guestAllowedCheckout);
        $registerForm->setAction($this->getCurrentURL());


        $extraParams                = array();
        $extraParams['firstname']   = ("" == $firstname) ? " " : $firstname;
        $extraParams['lastname']    = ("" == $lastname) ? " " : $lastname;
        $extraParams['id_customer'] = $this->context->customer->id;

        // take firstname/lastname from invoice address, if possible AND not provided in parameters
        $origCartIdAddressInvoice = $this->context->cart->id_address_invoice;
        if ($origCartIdAddressInvoice > 0) {
            $invoiceAddress = new Address($origCartIdAddressInvoice);
            if ('' == trim($extraParams['firstname']) && '' != trim($invoiceAddress->firstname)) {
                $extraParams['firstname'] = $invoiceAddress->firstname;
            }
            if ('' == trim($extraParams['lastname']) && '' != trim($invoiceAddress->lastname)) {
                $extraParams['lastname'] = $invoiceAddress->lastname;
            }
        }

        $registerForm->fillWith(array_merge($accountFormData, $extraParams));

        // in $registerForm->submit() .... Context.php, updateCustomer method, cart addresses are being modified
        // so we need to back them up here and restore after this call
        // also delivery option is being modified;
        // updateCustomer method
        //$cartInvoiceAddress  = $this->context->cart->id_address_invoice;
        //$cartDeliveryAddress = $this->context->cart->id_address_delivery;


        if ($registerForm->submit($silentRegistration)) {
        } else {
        }

        // Update id_customer in cart object - that's the place from where Atos payment module reads this
        $this->context->cart->id_customer = $this->context->customer->id;

        //$this->context->cart->id_address_invoice  = $cartInvoiceAddress;
        //$this->context->cart->id_address_delivery = $cartDeliveryAddress;

        //$this->context->cart->update();
        //$this->context->cart->setNoMultishipping();
        //$this->updateAddressIdInDeliveryOptions();


        return array(
            "hasErrors"      => $registerForm->hasErrors(),
            "errors"         => $registerForm->getErrors(),
            "customerId"     => $this->context->customer->id,
            "newToken"       => Tools::getToken(true, $this->context),
            "newStaticToken" => Tools::getToken(false),
            "isGuest"        => (int)$this->context->customer->is_guest
        );
    }

    private function modifyInvoiceAddress($formData, $shallCreateNewAddress)
    {
        $addressResult = $this->modifyAddress('invoice', $formData, $shallCreateNewAddress);

        return array("invoice" => $addressResult);
    }

    private function modifyDeliveryAddress($formData, $shallCreateNewAddress)
    {
        $addressResult = $this->modifyAddress('delivery', $formData, $shallCreateNewAddress);

        return array("delivery" => $addressResult);
    }

    private function confirmAll(
        $accountFormData,
        $invoiceVisible,
        $invoiceFormData,
        $deliveryVisible,
        $deliveryFormData,
        $shallCreateNewAddress,
        $passwordRequired
    ) {

        // Initialization defaults
        $firstname = null;
        $lastname  = null;

        // Try to get customer's first/lastname from address records (first invoice, then delivery):
        if ($invoiceVisible && isset($invoiceFormData['firstname']) && isset($invoiceFormData["lastname"])) {
            $firstname = $invoiceFormData['firstname'];
            $lastname  = $invoiceFormData['lastname'];
        } else {
            if ($deliveryVisible && isset($deliveryFormData['firstname']) && isset($deliveryFormData["lastname"])) {
                $firstname = $deliveryFormData['firstname'];
                $lastname  = $deliveryFormData['lastname'];
            }
        }

        if ($this->context->customer->isLogged()) {
            //$accountResult = $this->modifyAccount($accountFormData, $this->context->customer->firstname,
            //    $this->context->customer->lastname, false, false);

            $accountResult = null; // Don't do anything for logged in customers (no updates possible, only through default PS
            // Update customer's name, if it's empty and now provided withing Invoice or Delivery address
            if ("" == trim($this->context->customer->firstname)) {
                if ("" != trim($invoiceFormData['firstname'])) {
                    $this->context->customer->firstname = $invoiceFormData['firstname'];
                } elseif ("" != trim($deliveryFormData['firstname'])) {
                    $this->context->customer->firstname = $deliveryFormData['firstname'];
                }
            }
            if ("" == trim($this->context->customer->lastname)) {
                if ("" != trim($invoiceFormData['lastname'])) {
                    $this->context->customer->lastname = $invoiceFormData['lastname'];
                } elseif ("" != trim($deliveryFormData['lastname'])) {
                    $this->context->customer->lastname = $deliveryFormData['lastname'];
                }
            }
            $this->context->customer->update();
            $this->context->cart->update();
        } else {
            $accountResult = $this->modifyAccount($accountFormData, $firstname, $lastname, false, $passwordRequired);
        }

        // token might have been updated in modifyAccount
        if (isset($accountResult['newToken'])) {
            $invoiceFormData['token']  = $accountResult['newToken'];
            $deliveryFormData['token'] = $accountResult['newToken'];
        }

        $invoiceAddressResult = $deliveryAddressResult = null;
        $finalConfirmation    = true;

        if ($invoiceVisible) {
            $invoiceAddressResult = $this->modifyAddress('invoice', $invoiceFormData, $shallCreateNewAddress,
                $finalConfirmation);
        }

        if ($deliveryVisible) {
            $deliveryAddressResult = $this->modifyAddress('delivery', $deliveryFormData, $shallCreateNewAddress,
                $finalConfirmation);
        }

        // Only one address visible on checkout, let's unify them
        if ($invoiceVisible && !$invoiceAddressResult['hasErrors'] && !$deliveryVisible) {
            $this->context->cart->id_address_delivery = $this->context->cart->id_address_invoice;
            $this->context->cart->save();
            $this->context->cart->setNoMultishipping();
        }
        if ($deliveryVisible && !$deliveryAddressResult['hasErrors'] && !$invoiceVisible) {
            $this->context->cart->id_address_invoice = $this->context->cart->id_address_delivery;
            $this->context->cart->save();
            $this->context->cart->setNoMultishipping();
        }

        return array_merge(
            array("account" => $accountResult),
            array("invoice" => $invoiceAddressResult),
            array("delivery" => $deliveryAddressResult)
        );
    }

    private function unifyAddresses($invoiceVisible, $deliveryVisible)
    {
        // We need to unify addresses, if only one is visible - so that shipping methods are always reflecting selected zone
        // Do this *after* address modification, because new address ID might have been created
        if ($invoiceVisible && !$deliveryVisible) {
            $this->context->cart->id_address_delivery = $this->context->cart->id_address_invoice;
            $this->context->cart->save();
            $this->context->cart->setNoMultishipping();
            $this->updateAddressIdInDeliveryOptions();
        }
        if ($deliveryVisible && !$invoiceVisible) {
            $this->context->cart->id_address_invoice = $this->context->cart->id_address_delivery;
            $this->context->cart->save();
            $this->context->cart->setNoMultishipping();
            $this->updateAddressIdInDeliveryOptions();
        }
    }

    private function ajaxCheckEmail()
    {
        $errors = array();

        $email       = Tools::getValue('email');
        $id_customer = Customer::customerExists($email, true, true);

        if ($id_customer) {
            if (version_compare(_PS_VERSION_, '1.7.5') >= 0) {
                $errors['email'] = $this->translator->trans(
                    'The email is already used, please choose another one or sign in', array(),
                    'Shop.Notifications.Error'
                );
            } else {
                $errors['email'] = $this->translator->trans(
                        'The email "%mail%" is already used, please choose another one or sign in',
                        array('%mail%' => $email),
                        'Shop.Notifications.Error'
                    ) . '<' . 'span id="sign-in-link"' . '>' . $this->translator->trans('Sign in', array(),
                        'Shop.Theme.Actions') . '<' . '/' . 'span' . '>';
            }

        } elseif (
            ($this->module->config->force_email_overlay || $this->module->config->register_guest_on_blur)
            && Configuration::get('PS_GUEST_CHECKOUT_ENABLED')) {
            $this->silentRegistration($email);
        }

        $result = array(
            "hasErrors"      => (count($errors) > 0),
            "errors"         => $errors,
            "newToken"       => Tools::getToken(true, $this->context),
            "newStaticToken" => Tools::getToken(false),
            //"mustLogIn" => true
        );
        return $result;
    }

    private function ajaxModifyAccountAndAddress()
    {
        parse_str(Tools::getValue('account'), $accountFormData);
        parse_str(Tools::getValue('invoice'), $invoiceFormData);
        parse_str(Tools::getValue('delivery'), $deliveryFormData);

        // Add token to address arrays
        $invoiceFormData["token"]  = Tools::getValue("token");
        $deliveryFormData["token"] = Tools::getValue("token");

        // Copy invoice VAT number and company into customer's siret and company fields
        if (isset($invoiceFormData['vat_number']) && '' != trim($invoiceFormData['vat_number'])) {
            $accountFormData['siret'] = $invoiceFormData['vat_number'];
        }
        if (isset($invoiceFormData['company']) && '' != trim($invoiceFormData['company'])) {
            $accountFormData['company'] = $invoiceFormData['company'];
        }


        // TODO: solve the case, where both addresses are being saved at once (order confirmation), so that carrier
        // and payment blocks are not rendered twice, and so that we display errors respectively to every section

        $passwordVisible = Tools::getValue('passwordVisible');
        if (!$passwordVisible) {
            unset($accountFormData['password']);
        }
        $passwordRequired = Tools::getValue('passwordRequired');
        $invoiceVisible   = Tools::getValue('invoiceVisible');
        $deliveryVisible  = Tools::getValue('deliveryVisible');
        // addressesAreSame = Is only one address box visible on checkout form?
        $addressesAreSame     = !($invoiceVisible && $deliveryVisible);
        $cartAddressesAreSame = (int)$this->context->cart->id_address_invoice === (int)$this->context->cart->id_address_delivery;

        $shallCreateNewAddress = (!$addressesAreSame && $cartAddressesAreSame);

        $triggerSection = Tools::getValue('trigger');

        $result = array();

        switch ($triggerSection) {
            case 'thecheckout-account':
                $result = $this->modifyAccount($accountFormData, $passwordRequired);
                break;
            case 'thecheckout-address-invoice':
                $result = $this->modifyInvoiceAddress($invoiceFormData, $shallCreateNewAddress);
                break;
            case 'thecheckout-address-delivery':
                $result = $this->modifyDeliveryAddress($deliveryFormData, $shallCreateNewAddress);
                break;
            case 'thecheckout-confirm':
            case 'thecheckout-payment': // button clicked on save-account-overlay
                // save account (with password also, not only guest) + save both addresses, based on visibility
                $result = $this->confirmAll(
                    $accountFormData,
                    $invoiceVisible, $invoiceFormData,
                    $deliveryVisible, $deliveryFormData,
                    $shallCreateNewAddress,
                    $passwordRequired
                );
        }

        $this->unifyAddresses($invoiceVisible, $deliveryVisible);

        // get shipping/payment options blocks and cart summary only after addresses are properly set
        // including 'unifyAddresses' call
        switch ($triggerSection) {
            case 'thecheckout-address-invoice':
            case 'thecheckout-address-delivery':
            case 'thecheckout-confirm':
            case 'thecheckout-payment':
                $result = array_merge(
                    $result,
                    $this->getCustomerSignInArea(),
                    $this->getDynamicCheckoutBlocks()
                );
        }

        return $result;
    }

    private function ajaxModifyCheckboxOption()
    {
        $name      = Tools::getValue("name");
        $isChecked = Tools::getValue("isChecked");

        $opc_form_checkboxes        = json_decode($this->context->cookie->opc_form_checkboxes, true);
        $opc_form_checkboxes[$name] = $isChecked;

        $this->context->cookie->opc_form_checkboxes = json_encode($opc_form_checkboxes);

        return array(
            'name'      => "$name",
            'isChecked' => "$isChecked"
        );
    }

    private function ajaxModifyRadioOption()
    {
        $name         = Tools::getValue("name");
        $checkedValue = Tools::getValue("checkedValue");

        $opc_form_radios        = json_decode($this->context->cookie->opc_form_checkboxes, true);
        $opc_form_radios[$name] = $checkedValue;

        $this->context->cookie->opc_form_radios = json_encode($opc_form_radios);

        return array(
            'name'         => "$name",
            'checkedValue' => "$checkedValue"
        );
    }

    private function reverseAddressType($addressType)
    {
        if ('invoice' == $addressType) {
            return 'delivery';
        } else {
            return 'invoice';
        }
    }

    private function assignNewAddressIdToCart($addressType, $addressId)
    {
        $shallGenerateNewAddressBlock = false;
        if ("invoice" === $addressType) {
            if ($this->context->cart->id_address_invoice != $addressId) {
                $this->context->cart->id_address_invoice = $addressId;
                $shallGenerateNewAddressBlock            = true;
            }
        } else {
            if ($this->context->cart->id_address_delivery != $addressId) {
                $this->context->cart->id_address_delivery = $addressId;
                $shallGenerateNewAddressBlock             = true;
            }
        }
        return $shallGenerateNewAddressBlock;
    }

    private function ajaxModifyAddressSelection()
    {
        $addressType = Tools::getValue("addressType");
        $addressId   = Tools::getValue("addressId");

        $invoiceVisible  = Tools::getValue('invoiceVisible');
        $deliveryVisible = Tools::getValue('deliveryVisible');

        $newAddressBlock              = null;
        $shallGenerateNewAddressBlock = false;

        // TODO: test this case for logged in and also guest customers
        // Customer selected "New..." in combobox
        // This is called with Expand and also Collapse, save new address only on Expand action
        if ((-1 == $addressId)
            && (("invoice" == $addressType && $invoiceVisible) || ("delivery" == $addressType && $deliveryVisible))
        ) {
            $this->modifyAddress($addressType,
                array(
                    'id_country' => $this->context->country->id,
                    'token'      => Tools::getValue('token')
                ), true);
            $shallGenerateNewAddressBlock = true;
        } elseif (0 == $addressId) {
            $existingAddressId = $this->getTransientAddressByCartId($this->context->cart->id);

            if (null == $existingAddressId) {
                $existingAddressId = 0;
            }

            $shallGenerateNewAddressBlock = $this->assignNewAddressIdToCart($addressType, $existingAddressId);
        } else {
            if ($this->context->customer->isLogged()
                && Customer::customerHasAddress($this->context->customer->id, $addressId)
            ) {
                $shallGenerateNewAddressBlock = $this->assignNewAddressIdToCart($addressType, $addressId);
            }
        }

        // Unify Addresses, if there's only one block visible (invoice or delivery); no further action is necessary,
        // if address was updated, it happened above and we set $newAddressBlock
        if (!$invoiceVisible || !$deliveryVisible) {
            $this->unifyAddresses($invoiceVisible, $deliveryVisible);
        }

        // fetch new address block only when there was actual address ID modification
        if ($shallGenerateNewAddressBlock) {
            $this->context->cart->update();
            $this->context->cart->setNoMultishipping();
            $this->updateAddressIdInDeliveryOptions();

            $this->context->smarty->assign($this->getCheckoutFields());
            $this->context->smarty->assign('businessFieldsList',
                array_map('trim', explode(',', $this->module->config->business_fields)));
            $newAddressBlock = $this->context->smarty->fetch(
                'module:' . $this->name . '/views/templates/front/blocks/address-' . $addressType . '.tpl');
        }

        // Update address selection dropdown in the-other address block
        $this->context->smarty->assign($this->getAddressesSelectionTplVars());
        $this->context->smarty->assign("addressType", $this->reverseAddressType($addressType));

        // $context->country is used in payment methods hook, it's set in FrontController.php, and if we modify
        // addresses in cart, we need to update this context as well
        if (isset($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}) && $this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}) {
            $infos                  = Address::getCountryAndState((int)$this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
            $country                = new Country((int)$infos['id_country']);
            $this->context->country = $country;
            // Nov.2018: On frontend, we're not dynamically switching tax labels, thus unused for now.
//            if (Validate::isLoadedObject($country)) {
//                $display_tax_label = $country->display_tax_label;
//            }
        }

        $newAddressSelection = $this->context->smarty->fetch(
            'module:' . $this->name . '/views/templates/front/_partials/customer-addresses-dropdown.tpl');

        return array_merge(
            array('newAddressBlock' => $newAddressBlock),
            array('newAddressSelection' => $newAddressSelection),
            $this->getDynamicCheckoutBlocks()
        );
    }

    private function addPaymentFee($cart, $paymentFee)
    {
        $showTaxExclPrices = !(new TaxConfiguration())->includeTaxes();
        $cartAverageTax    = $this->context->cart->getAverageProductsTaxRate();
        $paymentFeeTaxOnly = $paymentFee * $cartAverageTax;

        // If on frontend there are prices shown tax excluded, let's consider also payment fee being tax excluded
        if ($showTaxExclPrices) {
            $paymentFeeTaxExcl = $paymentFee;
            $paymentFeeTaxIncl = $paymentFee * (1 + $cartAverageTax);
        } else {
            $paymentFeeTaxExcl = $paymentFee / (1 + $cartAverageTax);
            $paymentFeeTaxIncl = $paymentFee;
        }

        // Update separate tax field if shown in cart summary
        if (isset($cart['subtotals']['tax'])) {
            $totalTaxes                         = $cart['subtotals']['tax']['amount'] + $paymentFeeTaxOnly;
            $cart['subtotals']['tax']['amount'] = $totalTaxes;
            $cart['subtotals']['tax']['value']  = Tools::displayPrice($totalTaxes);
        }

        // Update tax excluded totals
        $totalTaxExcl                                    = $cart['totals']['total_excluding_tax']['amount'] + $paymentFeeTaxExcl;
        $cart['totals']['total_excluding_tax']['amount'] = $totalTaxExcl;
        $cart['totals']['total_excluding_tax']['value']  = Tools::displayPrice($totalTaxExcl);

        // Update tax included totals
        $totalTaxIncl                                    = $cart['totals']['total_including_tax']['amount'] + $paymentFeeTaxIncl;
        $cart['totals']['total_including_tax']['amount'] = $totalTaxIncl;
        $cart['totals']['total_including_tax']['value']  = Tools::displayPrice($totalTaxIncl);

        // Update 'context dependent' totals (based on Customer's group settings) - that's in $paymentFee,
        // as that value shown in payment method list is also context dependent
        $total                             = $cart['totals']['total']['amount'] + $paymentFee;
        $cart['totals']['total']['amount'] = $total;
        $cart['totals']['total']['value']  = Tools::displayPrice($total);

        $cart['subtotals']['payment-fee'] = array(
            'amount' => $paymentFee,
            'label'  => $this->module->getTranslation('Payment fee'),
            'type'   => 'payment_fee',
            'value'  => Tools::displayPrice($paymentFee),
        );

        return $cart;
    }

    private function getCartSummaryBlock($paymentFee = 0)
    {
        $this->parentInitContent();
        $presentedCart = $this->cart_presenter->present($this->context->cart);

        if ($paymentFee > 0) {
            $presentedCart = $this->addPaymentFee($presentedCart, $paymentFee);
        }

        $this->context->smarty->assign(array(
            'cart' => $presentedCart,
        ));

        $minimalPurchase = array();
        if ('' != trim($presentedCart['minimalPurchaseRequired'])) {
            $minimalPurchase = array(
                'minimalPurchaseValue' => $presentedCart['minimalPurchase'],
                'minimalPurchaseMsg'   => $presentedCart['minimalPurchaseRequired']
            );
        }

        $cartSummaryBlock = $this->context->smarty->fetch('module:' . $this->name . '/views/templates/front/blocks/cart-summary.tpl');
        return array_merge($minimalPurchase, array(
            'cartSummaryBlock'         => $cartSummaryBlock,
            'cartSummaryBlockChecksum' => md5($cartSummaryBlock),
            'emptyCart'                => !($presentedCart['products_count']),
            'isVirtualCart'            => $this->context->cart->isVirtualCart(),
            'minimalPurchaseError'     => !empty($minimalPurchase)
        ));
    }

    private function ajaxGetCartSummary()
    {
        return $this->getCartSummaryBlock();
    }

    private function handleDefaultCartAction()
    {
        $cartController = new CartController();
        $cartController->init();
        $cartController->postProcess();

        //$cartController->displayAjaxUpdate(); // on Error, ajaxDie will be called and processing would stop here
        $this->context->cart->update();

        //$updateOperationError = "";
        try {
            $cartControllerErrorProp = new ReflectionProperty('CartControllerCore', 'updateOperationError');
            $cartControllerErrorProp->setAccessible(true);
            $updateOperationError = $cartControllerErrorProp->getValue($cartController);
            //if ("" !== trim($updateOperationError)) {
            if (!empty($updateOperationError)) {
                $cartController->errors = array_merge($cartController->errors, $updateOperationError);
            }
        } catch (Exception $e) {
            // empty
        }

        $cartErrors = array(
            "cartErrors" => $cartController->errors,
            "hasErrors"  => !empty($cartController->errors)
        );

        return array_merge($cartErrors, $this->getDynamicCheckoutBlocks());
    }

    private function ajaxDeleteFromCart()
    {
        return $this->handleDefaultCartAction();
    }

    private function ajaxUpdateQuantity()
    {
        return $this->handleDefaultCartAction();
    }

    private function ajaxAddVoucher()
    {
        return $this->handleDefaultCartAction();
    }

    private function ajaxRemoveVoucher()
    {
        return $this->handleDefaultCartAction();
    }

    private function assignCustomerIdToAddressById($customerId, $addressId)
    {
        if (null != $addressId && 0 != $addressId) {

            $address = new Address($addressId);

            if (null === $address->id_customer) {
                $address->id_customer = $customerId;
                try {
                    $address->save();
                } catch (Exception $ignored) {

                }
            }
        }
    }

    private function ajaxSignIn()
    {
        $origCartIdAddressInvoice  = $this->context->cart->id_address_invoice;
        $origCartIdAddressDelivery = $this->context->cart->id_address_delivery;

        $loginForm = new CustomerLoginForm(
            $this->context->smarty,
            $this->context,
            $this->getTranslator(),
            new CustomerLoginFormatter($this->getTranslator()),
            $this->getTemplateVarUrls()
        );

        $loginForm->fillWith(Tools::getAllValues());

        if ($loginForm->submit()) {

            // update (old) cart addresses - assign them to customer, if they're unassigned to any other customer/guest yet
            $this->assignCustomerIdToAddressById($this->context->cart->id_customer, $origCartIdAddressInvoice);

            if ($origCartIdAddressDelivery != $origCartIdAddressInvoice) {
                $this->assignCustomerIdToAddressById($this->context->cart->id_customer, $origCartIdAddressDelivery);
            }
        }

        return array("errors" => $loginForm->getErrors(), "hasErrors" => $loginForm->hasErrors());
    }

//    private function ajaxModifyAccount()
//    {
//        // Is this still used? Probably not (20.3.2019).
//        return $this->modifyAccount(Tools::getAllValues());
//    }

    private function DB_saveCheckoutSessionData($data)
    {
        Db::getInstance()->execute(
            'UPDATE ' . _DB_PREFIX_ . 'cart SET checkout_session_data = "' . pSQL(json_encode($data)) . '"
                WHERE id_cart = ' . (int)$this->cart->id
        );
    }

    private function DB_getCheckoutSessionData()
    {
        $rawData = Db::getInstance()->getValue(
            'SELECT checkout_session_data FROM ' . _DB_PREFIX_ . 'cart WHERE id_cart = ' . (int)$this->cart->id
        );
        $data    = json_decode($rawData, true);
        return $data;
    }
}
