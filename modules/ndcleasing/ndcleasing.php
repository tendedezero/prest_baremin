<?php

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ndcleasing extends PaymentModule
{
    private $_html = '';
    private $_postErrors = array();

    public $checkName;
    public $address;
    public $extra_mail_vars;

    public function __construct()
    {
        $this->name = 'ndcleasing';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.1';
        $this->author = 'R Paterson';
        $this->controllers = array('payment', 'validation');

        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        $config = Configuration::getMultiple(array('LEASING_NAME', 'LEASING_ADDRESS'));
        if (isset($config['LEASING_NAME'])) {
            $this->checkName = $config['LEASING_NAME'];
        }
        if (isset($config['LEASING_ADDRESS'])) {
            $this->address = $config['LEASING_ADDRESS'];
        }

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Payments via leasing', array(), 'Modules.ndcleasing.Admin');
        $this->description = $this->trans('This module allows you to accept leasing requests.', array(), 'Modules.ndcleasing.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to delete these details?', array(), 'Modules.ndcleasing.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);

        if ((!isset($this->checkName) || !isset($this->address) || empty($this->checkName) || empty($this->address))) {
            $this->warning = $this->trans('The "Payee" and "Address" fields must be configured before using this module.', array(), 'Modules.ndcleasing.Admin');
        }
        if (!count(Currency::checkPaymentCurrencies($this->id))) {
            $this->warning = $this->trans('No currency has been set for this module.', array(), 'Modules.ndcleasing.Admin');
        }

        $this->extra_mail_vars = array(
                                    '{check_name}' => Configuration::get('LEASING_NAME'),
                                    '{check_address}' => Configuration::get('LEASING_ADDRESS'),
                                    '{check_address_html}' => Tools::nl2br(Configuration::get('LEASING_ADDRESS'))
                                );
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('paymentOptions')
            && $this->registerHook('paymentReturn')
        ;
    }

    public function uninstall()
    {
        return Configuration::deleteByName('LEASING_NAME')
            && Configuration::deleteByName('LEASING_ADDRESS')
            && parent::uninstall()
        ;
    }

    private function _postValidation()
    {
        if (Tools::isSubmit('btnSubmit')) {
            if (!Tools::getValue('LEASING_NAME')) {
                $this->_postErrors[] = $this->trans('The "Payee" field is required.', array(),'Modules.ndcleasing.Admin');
            } elseif (!Tools::getValue('LEASING_ADDRESS')) {
                $this->_postErrors[] = $this->trans('The "Address" field is required.', array(), 'Modules.ndcleasing.Admin');
            }
        }
    }

    private function _postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {
            Configuration::updateValue('LEASING_NAME', Tools::getValue('LEASING_NAME'));
            Configuration::updateValue('LEASING_ADDRESS', Tools::getValue('LEASING_ADDRESS'));
        }
        $this->_html .= $this->displayConfirmation($this->trans('Settings updated', array(), 'Admin.Notifications.Success'));
    }

    private function _displayCheck()
    {
        return $this->display(__FILE__, './views/templates/hook/infos.tpl');
    }

    public function getContent()
    {
        $this->_html = '';

        if (Tools::isSubmit('btnSubmit')) {
            $this->_postValidation();
            if (!count($this->_postErrors)) {
                $this->_postProcess();
            } else {
                foreach ($this->_postErrors as $err) {
                    $this->_html .= $this->displayError($err);
                }
            }
        }

        $this->_html .= $this->_displayCheck();
        $this->_html .= $this->renderForm();

        return $this->_html;
    }

    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $this->smarty->assign(
            $this->getTemplateVars()
        );

        $newOption = new PaymentOption();
        $newOption->setModuleName($this->name)
                ->setCallToActionText($this->trans('Pay via Leasing', array(), 'Modules.ndcleasing.Admin'))
                ->setAction($this->context->link->getModuleLink($this->name, 'validation', array(), true))
                ->setAdditionalInformation($this->fetch('module:ndcleasing/views/templates/front/payment_infos.tpl'));

        return [$newOption];
    }

    public function hookPaymentReturn($params)
    {
        if (!$this->active) {
            return;
        }

        $state = $params['order']->getCurrentState();
        if (in_array($state, array(Configuration::get('PS_OS_CHEQUE'), Configuration::get('PS_OS_OUTOFSTOCK'), Configuration::get('PS_OS_OUTOFSTOCK_UNPAID')))) {
            $this->smarty->assign(array(
                'total_to_pay' => Tools::displayPrice(
                    $params['order']->getOrdersTotalPaid(),
                    new Currency($params['order']->id_currency),
                    false
                ),
                'shop_name' => $this->context->shop->name,
                'checkName' => $this->checkName,
                'checkAddress' => Tools::nl2br($this->address),
                'status' => 'ok',
                'id_order' => $params['order']->id
            ));
            if (isset($params['order']->reference) && !empty($params['order']->reference)) {
                $this->smarty->assign('reference', $params['order']->reference);
            }
        } else {
            $this->smarty->assign('status', 'failed');
        }
        return $this->fetch('module:ndcleasing/views/templates/hook/payment_return.tpl');
    }

    public function checkCurrency($cart)
    {
        $currency_order = new Currency((int)($cart->id_currency));
        $currencies_module = $this->getCurrency((int)$cart->id_currency);

        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Contact details', array(), 'Modules.ndcleasing.Admin'),
                    'icon' => 'icon-envelope'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->trans('Payee (name)', array(), 'Modules.ndcleasing.Admin'),
                        'name' => 'LEASING_NAME',
                        'required' => true
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->trans('Address', array(), 'Modules.ndcleasing.Admin'),
                        'desc' => $this->trans('Address where the check should be sent to.', array(), 'Modules.ndcleasing.Admin'),
                        'name' => 'LEASING_ADDRESS',
                        'required' => true
                    ),
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Admin.Actions'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->id = (int)Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
        );

        $this->fields_form = array();

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'LEASING_NAME' => Tools::getValue('LEASING_NAME', Configuration::get('LEASING_NAME')),
            'LEASING_ADDRESS' => Tools::getValue(':LEASING_ADDRESS', Configuration::get('LEASING_ADDRESS')),
        );
    }

    public function getTemplateVars()
    {
        $cart = $this->context->cart;
        $total = $this->trans(
            '%amount% (tax incl.)',
            array(
                '%amount%' => Tools::displayPrice($cart->getOrderTotal(true, Cart::BOTH)),
            ),
            'Modules.ndcleasing.Admin'
        );

        $checkOrder = Configuration::get('LEASING_NAME');
        if (!$checkOrder) {
            $checkOrder = '___________';
        }

        $checkAddress = Tools::nl2br(Configuration::get('LEASING_ADDRESS'));
        if (!$checkAddress) {
            $checkAddress = '___________';
        }

        return [
            'checkTotal' => $total,
            'checkOrder' => $checkOrder,
            'checkAddress' => $checkAddress,
        ];
    }
}
