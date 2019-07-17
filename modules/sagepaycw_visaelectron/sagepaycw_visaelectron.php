<?php
/**
 *  * You are allowed to use this API in your web application.
 *
 * Copyright (C) 2018 by customweb GmbH
 *
 * This program is licenced under the customweb software licence. With the
 * purchase or the installation of the software in your application you
 * accept the licence agreement. The allowed usage is outlined in the
 * customweb software licence which can be found under
 * http://www.sellxed.com/en/software-license-agreement
 *
 * Any modification or distribution is strictly forbidden. The license
 * grants you the installation in one application. For multiuse you will need
 * to purchase further licences at http://www.sellxed.com/shop.
 *
 * See the customweb software licence agreement for more details.
 *
 */
require_once _PS_MODULE_DIR_ . '/sagepaycw/sagepaycw.php';
require_once 'SagePayCw/IPaymentMethod.php';

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

/**
 * SagePayCw_VisaElectron
 *
 * This class defines is the module class for the
 * payment method "VisaElectron".
 *
 * @author customweb GmbH
 */
class SagePayCw_VisaElectron extends PaymentModule implements SagePayCw_IPaymentMethod {
	/**
	 *
	 * @var SagePayCw_ConfigurationApi
	 */
	private $configurationApi = null;
	public $currencies = true;
	public $currencies_mode = 'checkbox';
	public $version = '4.0.143';
	public $author = 'customweb ltd';
	public $is_eu_compatible = 1;
	public $name = 'sagepaycw_visaelectron';
	public $paymentMethodName = 'visaelectron';
	public $paymentMethodDisplayName = 'Visa Electron';
	private $transactionContext = null;
	private static $requiresExecuted = false;

	/**
	 * This method init the module.
	 *
	 *        		   	    	 		 
	 */
	public function __construct(){
		Context::getContext()->smarty->addTemplateDir($this->getModuleFrontendTemplateDirectory());
		parent::__construct();
		
		// The parent construct is required for translations
		if (defined('_PS_ADMIN_DIR_') || empty($this->id)) {
			$this->displayName = 'Sage Pay: ' . $this->paymentMethodDisplayName;
		}
		else {
			$this->displayName = $this->getPaymentMethodDisplayName();
		}
		
		$this->description = str_replace('!PaymentMethodName', $this->paymentMethodDisplayName, SagePayCw::translate('ACCEPTS PAYMENTS'));
		$this->confirmUninstall = SagePayCw::translate('DELETE CONFIRMATION');
		$this->tab = 'payments_gateways';
		$this->bootstrap = true;
		if (!isset($_GET['configure']) && $this->context->controller instanceof AdminModulesController && method_exists('Module', 'isModuleTrusted') &&
				 (!Module::isInstalled($this->name) || !Module::isInstalled('mailhook'))) {
			require_once 'SagePayCw/SmartyProxy.php';
			$this->context->smarty = new SagePayCw_SmartyProxy($this->context->smarty);
		}
		$this->ps_versions_compliancy = array(
			'min' => '1.7',
			'max' => _PS_VERSION_ 
		);
	}

	/**
	 * Loads the required classes.
	 */
	private static function loadClasses(){
		if (self::$requiresExecuted === false) {
			self::$requiresExecuted = true;
			
			require_once 'Customweb/Payment/Authorization/IAdapter.php';

			require_once 'SagePayCw/ConfigurationApi.php';
require_once 'SagePayCw/Entity/Transaction.php';
require_once 'SagePayCw/TransactionContext.php';
require_once 'SagePayCw/OrderContext.php';
require_once 'SagePayCw/Util.php';
require_once 'SagePayCw/IPaymentMethod.php';
require_once 'SagePayCw/OrderStatus.php';
require_once 'SagePayCw/PaymentMethodWrapper.php';
require_once 'SagePayCw/SmartyProxy.php';
require_once 'SagePayCw/Adapter/AbstractAdapter.php';

		}
	}

	private function getModuleFrontendTemplateDirectory(){
		return _PS_MODULE_DIR_ . 'sagepaycw/views/templates/front/';
	}

	public function hookPaymentOptions($params){
		self::loadClasses();
		try {
			if (!$this->isPaymentMethodVisible()) {
				return [];
			}
			
			$payment_options = [
				$this->getEmbeddedPaymentOption() 
			];
			
			return $payment_options;
		}
		catch (Customweb_Payment_Authorization_Method_PaymentMethodResolutionException $exc) {
			return [];
		}
	}

	/**
	 * This method hooks into the return payment hook.
	 * It use allways the order_confirmation.tpl!
	 *
	 * @param array $params the params of the hook point
	 * @return string the html output
	 */
	public function hookPaymentReturn($params){
		self::loadClasses();
		$this->context->controller->addCSS(_MODULE_DIR_ . 'sagepaycw/css/style.css');
		$paymentMethodMessage = $this->getPaymentMethodConfigurationValue('MESSAGE_AFTER_ORDER', $this->context->language->language_code);
		
		$id_cart = (int) (Tools::getValue('id_cart', 0));
		$order = new Order(Order::getOrderByCartId($id_cart));
		$orderId = $order->id;
		$transactions = SagePayCw_Entity_Transaction::getTransactionsByOrderId($orderId);
		$transaction = current($transactions);
		
		$nameBackup = $this->name;
		$this->name = 'sagepaycw';
		
		$paymentInformation = null;
		$paymentInformationTitle = SagePayCw::translate("Payment Information");
		if ($transaction->getTransactionObject() !== null && $transaction->getTransactionObject()->isAuthorized()) {
			$paymentInformation = $transaction->getTransactionObject()->getPaymentInformation();
		}
		$this->name = $nameBackup;
		$this->context->smarty->assign(
				[
					'paymentMethodMessage' => $paymentMethodMessage,
					'paymentInformationTitle' => $paymentInformationTitle,
					'paymentInformation' => $paymentInformation 
				]);
		
		return $this->context->smarty->fetch('module:sagepaycw/views/templates/hook/payment_return.tpl');
	}

	public function getPaymentPane(){
		self::loadClasses();
		try {
			$orderContext = $this->getOrderContext();
			$adapter = SagePayCw_Util::getShopAdapterByPaymentAdapter($this->getAuthorizationAdapter($orderContext));
			
			$errorTransaction = null;
			/* @var $request Customweb_Core_Http_IRequest */
			$errorId = Tools::getValue('error_transaction_id', false);
			$moduleId = Tools::getValue('id_module', false);
			if ($moduleId == $this->id && !empty($errorId)) {
				$errorTransaction = SagePayCw_Entity_Transaction::loadById($errorId);
			}
			
			$adapter->prepareCheckout($this, $orderContext, $errorTransaction, false);
			$form = $adapter->getCheckoutPageForm();
			
			// In a default PrestaShop everything is UTF-8 and as such decoding at this point should
			// not be required.
			
			return $form;
		}
		catch (Exception $e) {
			return $this->createErrorForm($e->getMessage());
		}
	}

	private function createErrorForm($errorMessage){
		return $errorMessage;
	}

	public function getEmbeddedPaymentOption(){
		self::loadClasses();
		$embeddedOption = new PaymentOption();
		// @formatter:off
		$embeddedOption
				->setCallToActionText($this->getPaymentMethodDisplayName())
				->setAction($this->getShopAdapter()->getRedirectionUrl())
				->setForm($this->getPaymentPane())
				->setBinary(false)
				->setLogo($this->getPaymentMethodLogo());
		//->setAdditionalInformation($this->context->smarty->fetch('module:sagepaycw/views/templates/front/payment_infos.tpl'))
		// @formatter:on
		return $embeddedOption;
	}

	public function getFormFields(){
		self::loadClasses();
		$fields = array(
			0 => array(
				'name' => 'STATUS_AUTHORIZED',
 				'label' => $this->l("Authorized Status"),
 				'desc' => $this->l("This status is set, when the payment was successfull
						and it is authorized.
					"),
 				'default' => 'authorized',
 				'order_status' => array(
				),
 				'type' => 'orderstatus',
 			),
 			1 => array(
				'name' => 'STATUS_UNCERTAIN',
 				'label' => $this->l("Uncertain Status"),
 				'desc' => $this->l("You can specify the order status for new orders that
						have an uncertain authorisation status.
					"),
 				'default' => 'uncertain',
 				'order_status' => array(
				),
 				'type' => 'orderstatus',
 			),
 			2 => array(
				'name' => 'STATUS_CANCELLED',
 				'label' => $this->l("Cancelled Status"),
 				'desc' => $this->l("You can specify the order status when an order is
						cancelled.
					"),
 				'default' => 'cancelled',
 				'order_status' => array(
					0 => array(
						'id' => 'no_status_change',
 						'name' => $this->l("Don't change order status"),
 					),
 				),
 				'type' => 'orderstatus',
 			),
 			3 => array(
				'name' => 'STATUS_CAPTURED',
 				'label' => $this->l("Captured Status"),
 				'desc' => $this->l("You can specify the order status for orders that are
						captured either directly after the order or manually in the
						backend.
					"),
 				'default' => 'no_status_change',
 				'order_status' => array(
					0 => array(
						'id' => 'no_status_change',
 						'name' => $this->l("Don't change order status"),
 					),
 				),
 				'type' => 'orderstatus',
 			),
 			4 => array(
				'name' => 'ADDRESS_CHECK_BEHAVIOR',
 				'label' => $this->l("Address Check Result"),
 				'desc' => $this->l("During the checkout the address and post code are
						checked against the linked data with the credit
						card.
						The selected
						outcomes are threaded as uncertain transactions.
					"),
 				'default' => 'NOTPROVIDED',
 				'multiple' => 'true',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'NOTPROVIDED',
 							'name' => $this->l("No address or no post code was provided
						"),
 						),
 						1 => array(
							'id' => 'NOTCHECKED',
 							'name' => $this->l("The address or post code are not checked
						"),
 						),
 						2 => array(
							'id' => 'NOTMATCHED',
 							'name' => $this->l("The address or post code do not match
						"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			5 => array(
				'name' => 'CV2_CHECK_BEHAVIOR',
 				'label' => $this->l("CV2 Check Result"),
 				'desc' => $this->l("During the checkout the CV2 code is checked. The
						selected outcomes are treated as uncertain
						transactions.
					"),
 				'default' => 'NOTMATCHED,NOTPROVIDED',
 				'multiple' => 'true',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'NOTPROVIDED',
 							'name' => $this->l("No CV2 code was provided"),
 						),
 						1 => array(
							'id' => 'NOTCHECKED',
 							'name' => $this->l("CV2 code was not checked"),
 						),
 						2 => array(
							'id' => 'NOTMATCHED',
 							'name' => $this->l("CV2 not matched"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			6 => array(
				'name' => 'THREE_D_SECURE_BEHAVIOR',
 				'label' => $this->l("3D Secure Check"),
 				'desc' => $this->l("During the authorization of the payment a 3D secure
						check may be done. The selected outcomes are
						treated
						as uncertain
						transactions.
					"),
 				'default' => 'NOTCHECKED,authentication_failed',
 				'multiple' => 'true',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'authentication_failed',
 							'name' => $this->l("The 3D secure authentication failed."),
 						),
 						1 => array(
							'id' => 'NOTCHECKED',
 							'name' => $this->l("The 3D secure check was disabled for the
							transaction.
						"),
 						),
 						2 => array(
							'id' => 'NOTAVAILABLE',
 							'name' => $this->l("The card does not participate in the 3D
							scheme.
						"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			7 => array(
				'name' => 'FRAUD_BEHAVIOR',
 				'label' => $this->l("Fraud Check Result"),
 				'desc' => $this->l("During the authorization of the payment a fraud check
						may be done by ReD. The selected outcomes are
						treated as uncertain
						transactions.
					"),
 				'default' => 'DENY',
 				'multiple' => 'true',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'DENY',
 							'name' => $this->l("ReD recommends to reject the transaction.
						"),
 						),
 						1 => array(
							'id' => 'NOTCHECKED',
 							'name' => $this->l("No fraud check was done."),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			8 => array(
				'name' => 'CAPTURING',
 				'label' => $this->l("Capturing"),
 				'desc' => $this->l("Should the amount be captured automatically after the
						order (direct) or should the amount only be reserved (deferred)?
					"),
 				'default' => 'direct',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'direct',
 							'name' => $this->l("Directly after order"),
 						),
 						1 => array(
							'id' => 'deferred',
 							'name' => $this->l("Deferred"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			9 => array(
				'name' => 'AUTHORIZATIONMETHOD',
 				'label' => $this->l("Authorization Method"),
 				'desc' => $this->l("Select the authorization method to use for processing this payment method."),
 				'default' => 'ServerAuthorization',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'ServerAuthorization',
 							'name' => $this->l("Server Authorization (Direct)"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			10 => array(
				'name' => 'ALIAS_MANAGER',
 				'label' => $this->l("Alias Manager"),
 				'desc' => $this->l("The alias manager allows the customer to select from a credit card previously stored. The sensitive data is stored by ."),
 				'default' => 'inactive',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'active',
 							'name' => $this->l("Active"),
 						),
 						1 => array(
							'id' => 'inactive',
 							'name' => $this->l("Inactive"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 		);
$fields = array_merge($this->getFormFieldsInner(), $fields);
		return $fields;
	}

	protected function getTemplateBasePath(){
		$filePath = str_replace('lib/SagePayCw/PaymentMethod', 'sagepaycw', __FILE__);
		$filePath = str_replace('lib\\SagePayCw\\PaymentMethod', 'sagepaycw', $filePath);
		return $filePath;
	}

	public function getPaymentMethodLogo(){
		return $this->_path . '/logo.png';
	}

	/**
	 * This method installs the module.
	 *
	 * @return boolean if it was successful
	 */
	public function install(){
		self::loadClasses();
		return parent::install() && $this->installPaymentConfigurations() && $this->registerHook('paymentOptions') &&
				 $this->registerHook('paymentReturn') && $this->registerHook('header') && $this->installMethodSpecificConfigurations();
	}

	public function uninstall(){
		self::loadClasses();
		return parent::uninstall() && $this->uninstallMethodSpecificConfigurations() && $this->uninstallPaymentConfigurations();
	}

	public function installMethodSpecificConfigurations(){
		self::loadClasses();
		$this->getConfigApi()->updateConfigurationValue('STATUS_AUTHORIZED', Configuration::get('PS_OS_PAYMENT'));
		$this->getConfigApi()->updateConfigurationValue('STATUS_UNCERTAIN', Configuration::get('PS_OS_PREPARATION'));
		$this->getConfigApi()->updateConfigurationValue('STATUS_CANCELLED', Configuration::get('PS_OS_CANCELED'));
		$this->getConfigApi()->updateConfigurationValue('STATUS_CAPTURED', 'no_status_change');
		$this->getConfigApi()->updateConfigurationValue('ADDRESS_CHECK_BEHAVIOR', 'NOTPROVIDED');
		$this->getConfigApi()->updateConfigurationValue('CV2_CHECK_BEHAVIOR', 'NOTMATCHED,NOTPROVIDED');
		$this->getConfigApi()->updateConfigurationValue('THREE_D_SECURE_BEHAVIOR', 'NOTCHECKED,authentication_failed');
		$this->getConfigApi()->updateConfigurationValue('FRAUD_BEHAVIOR', 'DENY');
		$this->getConfigApi()->updateConfigurationValue('CAPTURING', 'direct');
		$this->getConfigApi()->updateConfigurationValue('AUTHORIZATIONMETHOD', 'ServerAuthorization');
		$this->getConfigApi()->updateConfigurationValue('ALIAS_MANAGER', 'inactive');
		
		return true;
	}

	public function uninstallMethodSpecificConfigurations(){
		self::loadClasses();
		$this->getConfigApi()->removeConfigurationValue('STATUS_AUTHORIZED');
		$this->getConfigApi()->removeConfigurationValue('STATUS_UNCERTAIN');
		$this->getConfigApi()->removeConfigurationValue('STATUS_CANCELLED');
		$this->getConfigApi()->removeConfigurationValue('STATUS_CAPTURED');
		$this->getConfigApi()->removeConfigurationValue('ADDRESS_CHECK_BEHAVIOR');
		$this->getConfigApi()->removeConfigurationValue('CV2_CHECK_BEHAVIOR');
		$this->getConfigApi()->removeConfigurationValue('THREE_D_SECURE_BEHAVIOR');
		$this->getConfigApi()->removeConfigurationValue('FRAUD_BEHAVIOR');
		$this->getConfigApi()->removeConfigurationValue('CAPTURING');
		$this->getConfigApi()->removeConfigurationValue('AUTHORIZATIONMETHOD');
		$this->getConfigApi()->removeConfigurationValue('ALIAS_MANAGER');
		;
		return true;
	}

	public function getPaymentMethodConfigurationValue($key, $languageCode = null){
		self::loadClasses();
		$multiSelectKeys = array(
			'three_d_secure_behavior' => 'three_d_secure_behavior',
 			'address_check_behavior' => 'address_check_behavior',
 			'cv2_check_behavior' => 'cv2_check_behavior',
 			'fraud_behavior' => 'fraud_behavior',
 		);
		$rs = $this->getPaymentMethodConfigurationValueInner($key, $languageCode);
		if (isset($multiSelectKeys[$key])) {
			if (empty($rs)) {
				return array();
			}
			else {
				return explode(',', $rs);
			}
		}
		else {
			return $rs;
		}
	}

	/**
	 *
	 * @return SagePayCw_ConfigurationApi
	 */
	public function getConfigApi(){
		if (empty($this->id)) {
			throw new Exception("Cannot initiate the config api wihtout the module id.");
		}
		
		if ($this->configurationApi == null) {
			require_once 'SagePayCw/ConfigurationApi.php';
			$this->configurationApi = new SagePayCw_ConfigurationApi($this->id);
		}
		return $this->configurationApi;
	}

	public function getPaymentMethodName(){
		return $this->paymentMethodName;
	}

	public function getPaymentMethodDisplayName(){
		$configuredName = $this->getConfigApi()->getConfigurationValue('METHOD_NAME', $this->context->language->id);
		if (!empty($configuredName)) {
			return $configuredName;
		}
		else {
			return $this->paymentMethodDisplayName;
		}
	}

	public function getPaymentMethodDescription(){
		$configuredDescription = $this->getConfigApi()->getConfigurationValue('METHOD_DESCRIPTION', $this->context->language->id);
		if (!empty($configuredDescription)) {
			return $configuredDescription;
		}
		else {
			return '';
		}
	}

	public function hookDisplayHeader(){
		$this->context->controller->addCSS(_MODULE_DIR_ . 'sagepaycw/css/style.css');
		$this->context->controller->addJS(_MODULE_DIR_ . 'sagepaycw/js/frontend.js');
		// 		if(isset($_REQUEST['cw_error'])) {
		// 			$controller = $this->context->controller;
		// 			if($controller instanceof FrontController) {
		// 				$controller->errors
		// 			}
		// 		}
	}

	public function installPaymentConfigurations(){
		$this->getConfigApi()->updateConfigurationValue('MESSAGE_AFTER_ORDER', '');
		
		$languages = Language::getLanguages(false);
		foreach ($languages as $language) {
			if (isset($language['lang_id'])) {
				$this->getConfigApi()->updateConfigurationValue('METHOD_NAME', $this->getPaymentMethodDisplayName(), $language['lang_id']);
			}
		}
		
		return true;
	}

	public function uninstallPaymentConfigurations(){
		$this->getConfigApi()->removeConfigurationValue('MESSAGE_AFTER_ORDER');
		
		$languages = Language::getLanguages(false);
		foreach ($languages as $language) {
			if (isset($language['lang_id'])) {
				$this->getConfigApi()->removeConfigurationValue('METHOD_NAME', $language['lang_id']);
				$this->getConfigApi()->removeConfigurationValue('METHOD_DESCRIPTION', $language['lang_id']);
			}
		}
		$this->getConfigApi()->removeConfigurationValue('MIN_TOTAL');
		$this->getConfigApi()->removeConfigurationValue('MAX_TOTAL');
		
		return true;
	}

	/**
	 * This method checks if for the current cart, the payment can be accepted by this
	 * payment method.
	 *
	 * @throws Exception In case it is not valid
	 * @return boolean
	 */
	public function validate(){
		self::loadClasses();
		$orderContext = $this->getOrderContext();
		$adapter = $this->getAuthorizationAdapter($orderContext);
		
		$paymentContext = SagePayCw_Util::getPaymentCustomerContext($this->context->cart->id_customer);
		try {
			$adapter->validate($orderContext, $paymentContext, array());
			SagePayCw_Util::persistPaymentCustomerContext($paymentContext);
			return NULL;
		}
		catch (Exception $e) {
			SagePayCw_Util::persistPaymentCustomerContext($paymentContext);
			return $e->getMessage();
		}
	}

	/**
	 * The main method for the configuration page.
	 *
	 * @return string html output
	 */
	public function getContent(){
		self::loadClasses();
		$this->context->controller->addCSS(_MODULE_DIR_ . 'sagepaycw/css/admin.css');
		
		$html = '<p><a class="button btn btn-default" href="?controller=adminmodules&configure=sagepaycw&module_name=sagepaycw&token=' .
				 Tools::getAdminTokenLite('AdminModules') . '">' . SagePayCw::translate('CONFIGURE_BASIC_SETTINGS') . '</a></p>';
		if (isset($_POST['submit_sagepaycw'])) {
			$fields = $this->getConfigApi()->convertFieldTypes($this->getFormFields());
			$this->getConfigApi()->processConfigurationSaveAction($fields);
			$this->displayConfirmation(SagePayCw::translate('Settings updated'));
		}
		$html .= $this->getConfigurationForm();
		
		return $html;
	}

	private function getConfigurationForm(){
		$fields = $this->getConfigApi()->convertFieldTypes($this->getFormFields());
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get(
				'PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->id = (int) Tools::getValue('id_carrier');
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submit_sagepaycw';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab .
				 '&module_name=' . $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigApi()->getConfigurationValues($fields),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id 
		);
		
		$forms = array(
			array(
				'form' => array(
					'legend' => array(
						'title' => $this->paymentMethodDisplayName,
						'icon' => 'icon-envelope' 
					),
					'input' => $fields,
					'submit' => array(
						'title' => SagePayCw::translate('Save') 
					) 
				) 
			) 
		);
		
		return $helper->generateForm($forms);
	}

	private function getFormFieldsInner(){
		$fields = array(
			array(
				'name' => 'METHOD_NAME',
				'label' => SagePayCw::translate('METHOD_NAME_LABEL'),
				'desc' => SagePayCw::translate('METHOD_NAME_DESCRIPTION'),
				'type' => 'textarea',
				'lang' => 'true' 
			),
			array(
				'name' => 'METHOD_DESCRIPTION',
				'label' => SagePayCw::translate('METHOD_DESCRIPTION_LABEL'),
				'desc' => SagePayCw::translate('METHOD_DESCRIPTION_DESCRIPTION'),
				'type' => 'textarea',
				'lang' => 'true' 
			),
			array(
				'name' => 'MIN_TOTAL',
				'label' => SagePayCw::translate('MIN_TOTAL_LABEL'),
				'desc' => SagePayCw::translate('MIN_TOTAL_DESCRIPTION'),
				'type' => 'text' 
			),
			array(
				'name' => 'MAX_TOTAL',
				'label' => SagePayCw::translate('MAX_TOTAL_LABEL'),
				'desc' => SagePayCw::translate('MAX_TOTAL_DESCRIPTION'),
				'type' => 'text' 
			),
			array(
				'name' => 'MESSAGE_AFTER_ORDER',
				'label' => SagePayCw::translate('MESSAGE_AFTER_ORDER_LABEL'),
				'desc' => SagePayCw::translate('MESSAGE_AFTER_ORDER_DESCRIPTION'),
				'type' => 'textarea',
				'lang' => 'true' 
			) 
		);
		
		return $fields;
	}

	private function getPaymentMethodConfigurationValueInner($key, $languageCode = null){
		$langId = null;
		if ($languageCode !== null) {
			$languageCode = (string) $languageCode;
			$langId = SagePayCw_Util::getLanguageIdByIETFTag($languageCode);
		}
		
		return $this->getConfigApi()->getConfigurationValue($key, $langId);
	}

	public function existsPaymentMethodConfigurationValue($key, $languageCode = null){
		self::loadClasses();
		$langId = null;
		if ($languageCode !== null) {
			$languageCode = (string) $languageCode;
			$langId = SagePayCw_Util::getLanguageIdByIETFTag($languageCode);
		}
		
		return $this->getConfigApi()->hasConfigurationKey($key, $langId);
	}

	/**
	 *
	 * @return SagePayCw_OrderContext
	 */
	public function getOrderContext(){
		self::loadClasses();
		$cart = $this->context->cart;
		return new SagePayCw_OrderContext($cart, new SagePayCw_PaymentMethodWrapper($this));
	}

	public function getShopAdapter(){
		self::loadClasses();
		$adapter = SagePayCw_Util::getShopAdapterByPaymentAdapter(
				SagePayCw_Util::getAuthorizationAdapterByContext($this->getOrderContext()));
		if (!$adapter instanceof SagePayCw_Adapter_AbstractAdapter) {
			throw new Exception("Adapter must be instance of SagePayCw_Adapter_AbstractAdapter.");
		}
		return $adapter;
	}

	/**
	 *
	 * @return Customweb_Payment_Authorization_IAdapter
	 */
	public function getAuthorizationAdapter(Customweb_Payment_Authorization_IOrderContext $orderContext){
		self::loadClasses();
		return SagePayCw_Util::getAuthorizationAdapterByContext($orderContext);
	}

	public function l($string, $sprintf = null, $id_lang = null){
		return SagePayCw::translate($string, $sprintf);
	}

	public function setCart($cart){
		$this->context->cart = $cart;
	}

	/**
	 *
	 * @return SagePayCw_Entity_Transaction
	 */
	public function createTransaction(SagePayCw_OrderContext $orderContext, $aliasTransactionId = null, $failedTransactionObject = null){
		self::loadClasses();
		$adapter = SagePayCw_Util::getAuthorizationAdapterByContext($orderContext);
		if (!($adapter instanceof Customweb_Payment_Authorization_IAdapter)) {
			throw new Exception("The adapter has to implement Customweb_Payment_Authorization_IAdapter.");
		}
		
		return $this->createTransactionWithAdapter($orderContext, $adapter, $aliasTransactionId, $failedTransactionObject);
	}

	public function createTransactionWithAdapter(SagePayCw_OrderContext $orderContext, Customweb_Payment_Authorization_IAdapter $adapter, $aliasTransactionId, $failedTransactionObject){
		self::loadClasses();
		$transactionContext = $this->createTransactionContext($orderContext, $aliasTransactionId, $failedTransactionObject);
		$transactionObject = $adapter->createTransaction($transactionContext, $failedTransactionObject);
		
		$transaction = $transactionContext->getInternalTransaction();
		$transaction->setTransactionObject($transactionObject);
		SagePayCw_Util::getEntityManager()->persist($transaction);
		
		return $transaction;
	}

	public function createTransactionContext(SagePayCw_OrderContext $orderContext, $aliasTransactionId, $failedTransactionObject){
		self::loadClasses();
		$mainModule = SagePayCw::getInstance();
		if ($mainModule->isCreationOfPendingOrderActive()) {
			return $this->createTransactionContextWithPendingOrder($orderContext, $aliasTransactionId, $failedTransactionObject);
		}
		else {
			return $this->createTransactionContextWithoutPendingOrder($orderContext, $aliasTransactionId, $failedTransactionObject);
		}
	}

	private function createTransactionContextWithPendingOrder(SagePayCw_OrderContext $orderContext, $aliasTransactionId, $failedTransactionObject){
		$originalCart = new Cart($orderContext->getCartId());
		
		$rs = $originalCart->duplicate();
		if (!isset($rs['success']) || !isset($rs['cart'])) {
			throw new Exception(
					"The cart duplication failed. May be some module prevents it. To fix this you may deactivate the creation of pending orders.");
		}
		$cart = $rs['cart'];
		if (!($cart instanceof Cart)) {
			throw new Exception("The duplicated cart is not of type 'Cart'.");
		}
		
		// Those values are not currently set when cloneing
		// 		$cart->id_address_delivery = $originalCart->id_address_delivery;
		// 		$cart->id_address_invoice = $originalCart->id_address_invoice;
		// 		$cart->getPackageList(true);
		// 		$cart->save();
		
		foreach ($originalCart->getCartRules() as $rule) {
			$ruleObject = $rule['obj'];
			//Because free gift cart rules adds a product to the order, the product is already in the duplicated order,
			//before we can add the cart rule to the new cart we have to remove the existing gift.
			if ((int) $ruleObject->gift_product) { //We use the same check as the shop, to get the gift product
				$cart->updateQty(1, $ruleObject->gift_product, $ruleObject->gift_product_attribute, false, 'down', 0, null, false);
			}
			$cart->addCartRule($ruleObject->id);
		}
		
		$collection = new PrestaShopCollection('Message');
		$collection->where('id_cart', '=', $originalCart->id);
		foreach($collection->getResults() as $message){
			$duplicateMessage = $message->duplicateObject();
			$duplicateMessage->id_cart = $cart->id;
			$duplicateMessage->save();
		}
		
		// Since we have duplicate the cart we have also to recreate the order context.
		$orderContext = new SagePayCw_OrderContext($cart, new SagePayCw_PaymentMethodWrapper($this));
		
		$pendingState = SagePayCw_OrderStatus::getPendingOrderStatusId();
		$customer = new Customer(intval($cart->id_customer));
		
		// Make sure that the notification can be processed, even if the payment
		// module is deactivated in this store.
		$this->active = true;
		
		$message = SagePayCw_Util::getOrderCreationMessage(SagePayCw_Util::getEmployeeIdFromCookie());
		
		SagePayCw::startRecordingMailMessages();
		$this->validateOrder((int) $cart->id, $pendingState, $orderContext->getOrderAmountInDecimals(), $this->getPaymentMethodDisplayName(), $message,
				$extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $customer->secure_key);
		$orderId = $this->currentOrder;
		$messages = SagePayCw::stopRecordingMailMessages();
		
		$transaction = new SagePayCw_Entity_Transaction();
		$transaction->setOrderId($orderId)->setCustomerId($customer->id)->setModuleId($this->id)->setCartId($cart->id)->setMailMessages($messages)->setOriginalCartId(
				$originalCart->id);
		SagePayCw_Util::getEntityManager()->persist($transaction);
		
		return $this->createTransactionContextInner($transaction, $orderContext, $aliasTransactionId);
	}

	private function createTransactionContextWithoutPendingOrder(SagePayCw_OrderContext $orderContext, $aliasTransactionId){
		$cart = new Cart($orderContext->getCartId());
		$transaction = new SagePayCw_Entity_Transaction();
		$transaction->setModuleId($this->id)->setCartId($cart->id);
		$transaction->setCustomerId($cart->id_customer);
		SagePayCw_Util::getEntityManager()->persist($transaction);
		
		return $this->createTransactionContextInner($transaction, $orderContext, $aliasTransactionId);
	}

	private function createTransactionContextInner(SagePayCw_Entity_Transaction $transaction, SagePayCw_OrderContext $orderContext, $aliasTransactionId){
		// Reset the checkout id.
		$key = SagePayCw_Util::getCheckoutCookieKey($this);
		$this->context->cookie->{$key} = null;
		return new SagePayCw_TransactionContext($transaction, $orderContext, $aliasTransactionId);
	}

	private function isPaymentMethodVisible(){
		if (!$this->active) {
			return false;
		}
		try{
			$orderContext = $this->getOrderContext();
		}
		catch(Exception $e){
			return false;
		}
		$adapter = $this->getAuthorizationAdapter($orderContext);
		$paymentContext = SagePayCw_Util::getPaymentCustomerContext($orderContext->getCustomerId());
		try {
			$adapter->preValidate($orderContext, $paymentContext);
			SagePayCw_Util::persistPaymentCustomerContext($paymentContext);
		}
		catch (Exception $e) {
			SagePayCw_Util::persistPaymentCustomerContext($paymentContext);
			return false;
		}
		
		// Check the minimal order total
		$minTotal = floatval($this->getConfigApi()->getConfigurationValue('MIN_TOTAL'));
		if (!empty($minTotal) && $minTotal > 0 && $minTotal > $this->context->cart->getOrderTotal(true, Cart::BOTH)) {
			return false;
		}
		
		// Check the maximal order total
		$maxTotal = floatval($this->getConfigApi()->getConfigurationValue('MAX_TOTAL'));
		if (!empty($maxTotal) && $maxTotal > 0 && $maxTotal < $this->context->cart->getOrderTotal(true, Cart::BOTH)) {
			return false;
		}
		
		return true;
	}
}

