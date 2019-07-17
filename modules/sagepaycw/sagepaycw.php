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
require_once _PS_MODULE_DIR_ . 'sagepaycw/lib/loader.php';

require_once _PS_MODULE_DIR_ . 'sagepaycw/lib/SagePayCw/TranslationResolver.php';

if (!defined('_PS_VERSION_'))
	exit();

/**
 * SagePayCw
 *
 * This class defines all central vars for the SagePayCw modules.
 *        		   	    	 		 
 *
 * @author customweb GmbH
 */
class SagePayCw extends Module {
	/**
	 *
	 * @var SagePayCw_ConfigurationApi
	 */
	private $configurationApi = null;
	public $trusted = true;
	const CREATE_PENDING_ORDER_KEY = 'CREATE_PENDING_ORDER';
	private static $recordMailMessages = false;
	private static $recordedMailMessages = array();
	private static $instance = null;
	private static $cancellingCheckIsRunning = false;
	private static $logListenerRegistered = false;
	private $initialized = false;
	private static $requiresExecuted = false;
	
	
	/**
	 * This method init the module.
	 */
	public function __construct(){
		
		// We have to make sure we can reuse the instance later.
		if (self::$instance === null) {
			self::$instance = $this;
		}
		
		$this->name = 'sagepaycw';
		$this->tab = 'checkout';
		$this->version = preg_replace('([^0-9\.a-zA-Z]+)', '', '4.0.143');
		$this->author = 'customweb ltd';
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';
		$this->bootstrap = true;
		
		parent::__construct();
		
		// The parent construct is required for translations        		   	    	 		 
		$this->displayName = SagePayCw::translate('DISPLAY NAME');
		$this->description = SagePayCw::translate('ACCEPTS PAYMENTS MAIN');
		$this->confirmUninstall = SagePayCw::translate('DELETE CONFIRMATION');
		
		if (Module::isInstalled($this->name) && !empty($this->id)) {
			$this->checkForCancellingRunningTransaction();
		}
		
		if (!isset($_GET['configure']) && $this->context->controller instanceof AdminModulesController && method_exists('Module', 'isModuleTrusted') &&
				 (!Module::isInstalled($this->name) || !Module::isInstalled('mailhook'))) {
		 	require_once 'SagePayCw/SmartyProxy.php';
			$this->context->smarty = new SagePayCw_SmartyProxy($this->context->smarty);
			if (!isset($GLOBALS['cwrmUnTrustedMs'])) {
				$GLOBALS['cwrmUnTrustedMs'] = array();
			}
			$GLOBALS['cwrmUnTrustedMs'][] = 'sagepaycw';
		}
		
		
		$this->handleChangesForAuthController();
	}
	
	
	/**
	 * This method loads the additional required classes and initializes all the things required to run the module.
	 */
	private function initialize() {
		if ($this->initialized === false) {
			$this->initialized = true;
			self::loadClasses();
			
			if (Module::isInstalled($this->name)) {
				$migration = new Customweb_Database_Migration_Manager(SagePayCw_Util::getDriver(), dirname(__FILE__) . '/updates/',
						_DB_PREFIX_ . 'sagepaycw_schema_version');
				$migration->migrate();
			}
			
			if (Module::isInstalled($this->name)) {
				$this->registerLogListener();
			}
		}
	}	
	
	private function checkLicense(){
		require_once 'Customweb/Licensing/SagePayCw/License.php';
		$arguments = null;
		return Customweb_Licensing_SagePayCw_License::run('o9jtb8q3r95ou64d', $this, $arguments);
	}

	final public function call_tnq3ogdi7lhga8vn() {
		$arguments = func_get_args();
		$method = $arguments[0];
		$call = $arguments[1];
		$parameters = array_slice($arguments, 2);
		if ($call == 's') {
			return call_user_func_array(array(get_class($this), $method), $parameters);
		}
		else {
			return call_user_func_array(array($this, $method), $parameters);
		}
		
		
	}
	
	private static function loadClasses() {
		if (self::$requiresExecuted === false) {
			self::$requiresExecuted = true;
			
			require_once 'Customweb/Payment/ExternalCheckout/IContext.php';
require_once 'Customweb/Util/Invoice.php';
require_once 'Customweb/Core/Exception/CastException.php';
require_once 'Customweb/Licensing/SagePayCw/License.php';
require_once 'Customweb/Payment/ExternalCheckout/IProviderService.php';
require_once 'Customweb/Core/Logger/Factory.php';
require_once 'Customweb/Core/Url.php';
require_once 'Customweb/Core/DateTime.php';
require_once 'Customweb/Core/String.php';
require_once 'Customweb/Database/Migration/Manager.php';
require_once 'Customweb/Payment/Authorization/ITransaction.php';

			require_once 'SagePayCw/ConfigurationApi.php';
require_once 'SagePayCw/Entity/Transaction.php';
require_once 'SagePayCw/Entity/ExternalCheckoutContext.php';
require_once 'SagePayCw/Util.php';
require_once 'SagePayCw/LoggingListener.php';
require_once 'SagePayCw/SmartyProxy.php';

			
			if (Module::isInstalled('mailhook')) {
				require_once rtrim(_PS_MODULE_DIR_, '/') . '/mailhook/MailMessage.php';
				require_once rtrim(_PS_MODULE_DIR_, '/') . '/mailhook/MailMessageAttachment.php';
				require_once rtrim(_PS_MODULE_DIR_, '/') . '/mailhook/MailMessageEvent.php';
			}
			
		}
	}
	

	private function getName(){
		return $this->name;
	}

	/**
	 * When pending orders are created, the stock may be reduced during the checkout.
	 * When
	 * the customer returns during the payment in the browser to the store, the stock is
	 * reserved for the customer, however he will never complete the payment. Hence we have to give
	 * the customer the option to cancel the running transaction.
	 */
	private function checkForCancellingRunningTransaction(){
		$controller = strtolower(Tools::getValue('controller'));
		if (($controller == 'order' || $controller == 'orderopc') && isset($this->context->cart) && !Configuration::get('PS_CATALOG_MODE') &&
				!$this->context->cart->checkQuantities()) {
			if ($this->isCreationOfPendingOrderActive() && self::$cancellingCheckIsRunning === false) {
				self::$cancellingCheckIsRunning = true;
				$originalCartId = $this->context->cart->id;
				SagePayCw_Util::getDriver()->beginTransaction();
				$cancelledTransactions = 0;
				try {
					$transactions = SagePayCw_Entity_Transaction::getTransactionsByOriginalCartId($originalCartId, false);
					foreach ($transactions as $transaction) {
						if ($transaction->getAuthorizationStatus() == Customweb_Payment_Authorization_ITransaction::AUTHORIZATION_STATUS_PENDING) {
							$transaction->forceTransactionFailing();
							$cancelledTransactions++;
						}
					}
					SagePayCw_Util::getDriver()->commit();
				}
				catch (Exception $e) {
					$this->context->controller->errors[] = $e->getMessage();
					SagePayCw_Util::getDriver()->rollBack();
				}
				if ($cancelledTransactions > 0) {
					$this->context->controller->errors[] = SagePayCw::translate(
							"It seems as you have not finished the payment. We have cancelled the running payment.");
				}
			}
			self::$cancellingCheckIsRunning = false;
		}
	}

	public static function getInstance(){
		if (self::$instance === null) {
			self::$instance = new SagePayCw();
		}
		
		return self::$instance;
	}

	/**
	 *
	 * @return SagePayCw_ConfigurationApi
	 */
	public function getConfigApi(){
		$this->initialize();
		if (empty($this->id)) {
			throw new Exception("Cannot initiate the config api wihtout the module id.");
		}
		
		if ($this->configurationApi == null) {
			$this->configurationApi = new SagePayCw_ConfigurationApi($this->id);
		}
		return $this->configurationApi;
	}

	/**
	 * This method installs the module.
	 *
	 * @return boolean if it was successful
	 */
	public function install(){
		$this->initialize();
		$this->installController('AdminSagePayCwRefund', 'Sage Pay Refund');
		$this->installController('AdminSagePayCwMoto', 'Sage Pay Moto');
		$this->installController('AdminSagePayCwForm', 'Sage Pay', 1, 
				Tab::getIdFromClassName('AdminParentModulesSf'));
		$this->installController('AdminSagePayCwTransaction', 'Sage Pay Transactions', 1);
		
		if (parent::install() && $this->installConfigurationValues() && $this->registerHook('adminOrder') && $this->registerHook('backOfficeHeader') &&
				 $this->registerHook('displayHeader') && $this->registerHook('displayCustomerAccountForm') && $this->registerHook('displayPDFInvoice')) {
			
			

			return true;
		}
		else {
			return false;
		}
	}

	public function installController($controllerName, $name, $active = 0, $parentId = null){
		$this->initialize();
		if ($parentId === null) {
			$parentId = Tab::getIdFromClassName('AdminParentOrders');
		}
		
		$tab_controller_main = new Tab();
		$tab_controller_main->active = $active;
		$tab_controller_main->class_name = $controllerName;
		foreach (Language::getLanguages() as $language) {
			//in Presta 1.5 the name length is limited to 32
			if (version_compare(_PS_VERSION_, '1.6') >= 0) {
				$tab_controller_main->name[$language['id_lang']] = substr($name, 0, 64);
			}
			else {
				//we have to cut the psp name otherwise, otherwise there could be an issue
				//where we can not distinguish the different controllers as all there visible names are identical
				if (strlen($name) > 32) {
					if (strpos($name, 'Sage Pay') !== false) {
						$name = str_replace('Sage Pay', '', $name);
						$length = strlen($name);
						if ($length < 32) {
							$pspName = substr('Sage Pay', 0, 32 - $length);
							$name = $pspName . $name;
						}
					}
				}
				$tab_controller_main->name[$language['id_lang']] = substr($name, 0, 32);
			}
		}
		$tab_controller_main->id_parent = $parentId;
		$tab_controller_main->module = $this->name;
		$tab_controller_main->add();
		$tab_controller_main->move(Tab::getNewLastPosition(0));
	}

	public function uninstall(){
		$this->initialize();
		$this->uninstallController('AdminSagePayCwRefund');
		$this->uninstallController('AdminSagePayCwMoto');
		$this->uninstallController('AdminSagePayCwForm');
		$this->uninstallController('AdminSagePayCwTransaction');
		
		return parent::uninstall() && $this->uninstallConfigurationValues();
	}

	public function uninstallController($controllerName){
		$this->initialize();
		$tab_controller_main_id = TabCore::getIdFromClassName($controllerName);
		$tab_controller_main = new Tab($tab_controller_main_id);
		$tab_controller_main->delete();
	}

	private function installConfigurationValues(){
		$this->getConfigApi()->updateConfigurationValue('CREATE_PENDING_ORDER', 'inactive');
		$this->getConfigApi()->updateConfigurationValue('VENDOR', '');
		$this->getConfigApi()->updateConfigurationValue('OPERATION_MODE', 'test');
		$this->getConfigApi()->updateConfigurationValue('DEFERRED_AUTHORIZATION_TYPE', 'deferred');
		$this->getConfigApi()->updateConfigurationValue('DIRECT_CAPTURE_TYPE', 'two_step');
		$this->getConfigApi()->updateConfigurationValue('DESCRIPTION', 'Your order description');
		$this->getConfigApi()->updateConfigurationValue('TRANSACTION_ID_SCHEMA', 'order_{id}');
		$this->getConfigApi()->updateConfigurationValue('SEND_BASKET', 'none');
		$this->getConfigApi()->updateConfigurationValue('GIFT_AID', 'disabled');
		$this->getConfigApi()->updateConfigurationValue('MOTO_CVC_CHECK', 'disabled');
		$this->getConfigApi()->updateConfigurationValue('T3M', 'off');
		$this->getConfigApi()->updateConfigurationValue('USERNAME', '');
		$this->getConfigApi()->updateConfigurationValue('PASSWORD', '');
		$this->getConfigApi()->updateConfigurationValue('LOG_LEVEL', 'off');
		
		return true;
	}

	private function uninstallConfigurationValues(){
		$this->getConfigApi()->removeConfigurationValue('CREATE_PENDING_ORDER');
		$this->getConfigApi()->removeConfigurationValue('VENDOR');
		$this->getConfigApi()->removeConfigurationValue('OPERATION_MODE');
		$this->getConfigApi()->removeConfigurationValue('DEFERRED_AUTHORIZATION_TYPE');
		$this->getConfigApi()->removeConfigurationValue('DIRECT_CAPTURE_TYPE');
		$this->getConfigApi()->removeConfigurationValue('DESCRIPTION');
		$this->getConfigApi()->removeConfigurationValue('TRANSACTION_ID_SCHEMA');
		$this->getConfigApi()->removeConfigurationValue('SEND_BASKET');
		$this->getConfigApi()->removeConfigurationValue('GIFT_AID');
		$this->getConfigApi()->removeConfigurationValue('MOTO_CVC_CHECK');
		$this->getConfigApi()->removeConfigurationValue('T3M');
		$this->getConfigApi()->removeConfigurationValue('USERNAME');
		$this->getConfigApi()->removeConfigurationValue('PASSWORD');
		$this->getConfigApi()->removeConfigurationValue('LOG_LEVEL');
		
		return true;
	}


	/**
	 * The main method for the configuration page.
	 *
	 * @return string html output
	 */
	public function getContent(){
		$this->initialize();
		$this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/css/admin.css');
		
		$html = '';
		$html .= $this->checkLicense();
		
		if (isset($_POST['submit_sagepaycw'])) {
			
			if (isset($_POST[self::CREATE_PENDING_ORDER_KEY]) && $_POST[self::CREATE_PENDING_ORDER_KEY] == 'active') {
				$this->registerHook('actionMailSend');
				if (!self::isInstalled('mailhook')) {
					$html .= $this->displayError(
							SagePayCw::translate(
									"The module 'Mail Hook' must be activated, when using the option 'create pending order', otherwise the mail sending behavior may be inappropriate."));
				}
			}
			
			$fields = $this->getConfigApi()->convertFieldTypes($this->getFormFields());
			$this->getConfigApi()->processConfigurationSaveAction($fields);
			$html .= $this->displayConfirmation(SagePayCw::translate('Settings updated'));
		}
		
		$html .= $this->getConfigurationForm();
		
		return $html;
	}

	private function getConfigurationForm(){
		$link = new Link();
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
						'title' => 'Sage Pay: ' . SagePayCw::translate('Settings'),
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

	protected function getFormFields(){
		$this->initialize();
		$fields = array(
			0 => array(
				'name' => 'CREATE_PENDING_ORDER',
 				'label' => $this->l("Create Pending Order"),
 				'desc' => $this->l("By creating pending orders the module will create a order before the payment is authorized. This not PrestaShop standard and may introduce some issues. However the module can send the order number to , which can reduce the overhead for the reconsilation. To use this feature the 'Mail Hook' module must be activated."),
 				'default' => 'inactive',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'inactive',
 							'name' => $this->l("Inactive"),
 						),
 						1 => array(
							'id' => 'active',
 							'name' => $this->l("Active"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			1 => array(
				'name' => 'VENDOR',
 				'label' => $this->l(" Vender Name"),
 				'desc' => $this->l("Used to authenticate your site. This should contain the
				 Vendor Name
				supplied by
				 when your account was created.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			2 => array(
				'name' => 'OPERATION_MODE',
 				'label' => $this->l("Operation Mode"),
 				'desc' => $this->l("You can switch between the different environments, by
				selecting the corresponding operation mode.
			"),
 				'default' => 'test',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'test',
 							'name' => $this->l("Test Mode"),
 						),
 						1 => array(
							'id' => 'live',
 							'name' => $this->l("Live Mode"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			3 => array(
				'name' => 'DEFERRED_AUTHORIZATION_TYPE',
 				'label' => $this->l("Deferred Authorization Type"),
 				'desc' => $this->l(" supports two types of
				deferred authorization. The deferred
				authorization allows only one
				capture per transaction, but it guarantees the payment, because a
				reservation is added
				on the customer's card. In case of authenticate
				you can do multiple captures per transaction, but there is no
				reservation of the amount on the card.
			"),
 				'default' => 'deferred',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'deferred',
 							'name' => $this->l("Use normal deferred authorization"),
 						),
 						1 => array(
							'id' => 'authenticate',
 							'name' => $this->l("Use authenticate authorization"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			4 => array(
				'name' => 'DIRECT_CAPTURE_TYPE',
 				'label' => $this->l("Direct Capture Type"),
 				'desc' => $this->l("Here you can select how the direct capture process is
				done. Either we first authorize the transaction and capture it
				automatically later (Two Step). Or it is done immediately within the
				authorization (During authorization).
				During the authorization means we use the transaction Type 'PAYMENT',
				we also process the feedback from  immediately.
				This can lead to issues, if your shop takes a long time to process an order.
				(e.g. send confirmation mail, update stock, etc.)
				Two Step uses the Transaction Type 'DEFERRED'.
			"),
 				'default' => 'two_step',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'single',
 							'name' => $this->l("During Authorization"),
 						),
 						1 => array(
							'id' => 'two_step',
 							'name' => $this->l("Two Step"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			5 => array(
				'name' => 'DESCRIPTION',
 				'label' => $this->l("Description of the order"),
 				'desc' => $this->l("The description of goods purchased is displayed on the
				 Server payment
				page as the
				customer enters their card details.
			"),
 				'default' => 'Your order description',
 				'lang' => 'true',
 				'type' => 'textarea',
 			),
 			6 => array(
				'name' => 'TRANSACTION_ID_SCHEMA',
 				'label' => $this->l("Transaction ID Prefix"),
 				'desc' => $this->l("Here you can insert a transaction prefix. The prefix
				allows you to change the transaction number that is
				transmitted to
				. The prefix must contain the tag
				{id}. It will then be replaced
				by
				the order number (e.g. name_{id}).
			"),
 				'default' => 'order_{id}',
 				'type' => 'text',
 			),
 			7 => array(
				'name' => 'SEND_BASKET',
 				'label' => $this->l("Basket"),
 				'desc' => $this->l("During the checkout the basket can be sent to
				. It can be sent as XML, Basic.
			"),
 				'default' => 'none',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'xml',
 							'name' => $this->l("XML"),
 						),
 						1 => array(
							'id' => 'basic',
 							'name' => $this->l("Basic"),
 						),
 						2 => array(
							'id' => 'none',
 							'name' => $this->l("Do not send basket"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			8 => array(
				'name' => 'GIFT_AID',
 				'label' => $this->l("Gift Aid"),
 				'desc' => $this->l("By enabling the gife aid option the customer can ticke a
				box during the checkout process to indicate she or he wish to donate
				the tax.This option requires that the your
				
				account has enabled the gift aid
				option.
			"),
 				'default' => 'disabled',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'enabled',
 							'name' => $this->l("Enabled"),
 						),
 						1 => array(
							'id' => 'disabled',
 							'name' => $this->l("Disabled"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			9 => array(
				'name' => 'MOTO_CVC_CHECK',
 				'label' => $this->l("MoTo CVC Check"),
 				'desc' => $this->l("When a mail / telephone order (MoTo) is made, should the
				authorization process check the CVC?
			"),
 				'default' => 'disabled',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'enabled',
 							'name' => $this->l("Enabled"),
 						),
 						1 => array(
							'id' => 'disabled',
 							'name' => $this->l("Disabled"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			10 => array(
				'name' => 'T3M',
 				'label' => $this->l("The 3rd Man"),
 				'desc' => $this->l("Should results from The 3rd Man fraud screening be
				polled and saved on the transaction?
			"),
 				'default' => 'off',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'on',
 							'name' => $this->l("On"),
 						),
 						1 => array(
							'id' => 'off',
 							'name' => $this->l("Off"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 			11 => array(
				'name' => 'USERNAME',
 				'label' => $this->l("Username"),
 				'desc' => $this->l("The username used for administrative requests.
			"),
 				'default' => '',
 				'type' => 'text',
 			),
 			12 => array(
				'name' => 'PASSWORD',
 				'label' => $this->l("Password"),
 				'desc' => $this->l("The password used for administrative requests.
			"),
 				'default' => '',
 				'type' => 'password',
 			),
 			13 => array(
				'name' => 'LOG_LEVEL',
 				'label' => $this->l("Log Level"),
 				'desc' => $this->l("Messages of this or a higher level will be logged."),
 				'default' => 'off',
 				'type' => 'select',
 				'options' => array(
					'query' => array(
						0 => array(
							'id' => 'off',
 							'name' => $this->l("Off"),
 						),
 						1 => array(
							'id' => 'error',
 							'name' => $this->l("Error"),
 						),
 						2 => array(
							'id' => 'info',
 							'name' => $this->l("Info"),
 						),
 						3 => array(
							'id' => 'debug',
 							'name' => $this->l("Debug"),
 						),
 					),
 					'name' => 'name',
 					'id' => 'id',
 				),
 			),
 		);
		
		return $fields;
	}

	public function getPath(){
		return $this->_path;
	}

	public function hookDisplayHeader(){
		// In the one page checkout the CSS files are not loaded. This method adds therefore the missing CSS files on
		// this page.        		   	    	 		 
		if ($this->context->controller instanceof OrderOpcController) {
			$this->context->controller->addCSS(_MODULE_DIR_ . 'sagepaycw/css/style.css');
		}
	}

	public function hookDisplayBeforeShoppingCartBlock(){
		
		return '';
	}

	public function hookDisplayPDFInvoice($object){
		if (!isset($object['object'])) {
			return;
		}
		$orderInvoice = $object['object'];
		if (!($orderInvoice instanceof OrderInvoice)) {
			return;
		}
		$this->initialize();
		$transactions = SagePayCw_Entity_Transaction::getTransactionsByOrderId($orderInvoice->id_order);
		$transactionObject = null;
		foreach ($transactions as $transaction) {
			if ($transaction->getTransactionObject() !== null && $transaction->getTransactionObject()->isAuthorized()) {
				$transactionObject = $transaction->getTransactionObject();
				break;
			}
		}
		if ($transactionObject === null) {
			return;
		}
		$paymentInformation = $transactionObject->getPaymentInformation();
		$result = '';
		if (!empty($paymentInformation)) {
			$result .= '<div class="sagepaycw-invoice-payment-information" id="sagepaycw-invoice-payment-information">';
			$result .= $paymentInformation;
			$result .= '</div>';
		}
		return $result;
	}
	
	
	private function handleChangesForAuthController(){
		
	}
	
	
	public function sortCheckouts($a, $b){
		if (isset($a['sortOrder']) && isset($b['sortOrder'])) {
			if ($a['sortOrder'] < $b['sortOrder']) {
				return -1;
			}
			else if ($a['sortOrder'] > $b['sortOrder']) {
				return 1;
			}
			else {
				return 0;
			}
		}
		else {
			return 0;
		}
	}

	public function hookBackOfficeHeader(){
		$id_order = Tools::getValue('id_order');
		
		// Check if we need to ask the customer to refund the amount        		   	    	 		 
		if ((isset($_POST['partialRefund']) || isset($_POST['cancelProduct'])) && !isset($_GET['confirmed']) && !(isset($_POST['generateDiscountRefund']) && $_POST['generateDiscountRefund']== 'on')) {
			$this->initialize();
			$transaction = current(SagePayCw_Entity_Transaction::getTransactionsByOrderId($id_order));
			if (is_object($transaction) && $transaction->getTransactionObject() !== null &&
					 $transaction->getTransactionObject()->isPartialRefundPossible()) {
				$order = new Order($id_order);
				if ($order->module == ('sagepaycw_' . $transaction->getPaymentMachineName())) {
					$url = '?controller=AdminSagePayCwRefund&token=' . Tools::getAdminTokenLite('AdminSagePayCwRefund');
					$url .= '&' . Customweb_Core_Url::parseArrayToString($_POST);
					header('Location: ' . $url);
					die();
				}
			}
		}
		
		if (isset($_POST['submitSagePayCwRefundAuto'])) {
			$this->initialize();
			try {
				$transaction = current(SagePayCw_Entity_Transaction::getTransactionsByOrderId($id_order));
				$this->refundTransaction($transaction->getTransactionId(), self::getRefundAmount($_POST));
			}
			catch (Exception $e) {
				$this->context->controller->errors[] = SagePayCw::translate("Could not refund the transaction: ") . $e->getMessage();
				unset($_POST['partialRefund']);
				unset($_POST['cancelProduct']);
			}
		}
		
		

		
		if (isset($_GET['controller']) && $_GET['controller'] == 'AdminOrders' && isset($_POST['submitAddOrder']) && !isset($_GET['confirmed'])) {
			$paymentMethodName = $_POST['payment_module_name'];
			if (substr($paymentMethodName, 0, strlen('sagepaycw')) == 'sagepaycw') {
				$this->initialize();
				$url = '?controller=AdminSagePayCwMoto&token=' . Tools::getAdminTokenLite('AdminSagePayCwMoto');
				$url .= '&' . Customweb_Core_Url::parseArrayToString($_POST);
				header('Location: ' . $url);
				die();
			}
		}
		
	}

	public function hookActionMailSend($data){
		$this->initialize();
		if ($this->isCreationOfPendingOrderActive()) {
			if (!isset($data['event'])) {
				throw new Exception("No item 'event' provided in the mail action function.");
			}
			$event = $data['event'];
			if (!($event instanceof MailMessageEvent)) {
				throw new Exception("Invalid type provided by the mail send action.");
			}
			
			if (self::isRecordingMailMessages()) {
				foreach ($event->getMessages() as $message) {
					self::$recordedMailMessages[] = $message;
				}
				$event->setMessages(array());
			}
		}
	}

	public static function isRecordingMailMessages(){
		return self::$recordMailMessages;
	}

	public static function startRecordingMailMessages(){
		self::$recordMailMessages = true;
		self::$recordedMailMessages = array();
	}

	/**
	 *
	 * @return MailMessage[]
	 */
	public static function stopRecordingMailMessages(){
		self::$recordMailMessages = false;
		
		return self::$recordedMailMessages;
	}

	public function isCreationOfPendingOrderActive(){
		$this->initialize();
		$createPendingOrder = $this->getConfigApi()->getConfigurationValue(self::CREATE_PENDING_ORDER_KEY);
		
		if ($createPendingOrder == 'active') {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * This method extracts the refund amount from the POST data.
	 *
	 * @param array $data
	 * @return float amount
	 */
	public static function getRefundAmount($data){
		$amount = 0;
		$order_detail_list = array();
		if (isset($data['partialRefundProduct'])) {
			foreach ($data['partialRefundProduct'] as $id_order_detail => $amount_detail) {
				$order_detail_list[$id_order_detail]['quantity'] = (int) $data['partialRefundProductQuantity'][$id_order_detail];
				
				if (empty($amount_detail)) {
					$order_detail = new OrderDetail((int) $id_order_detail);
					$order_detail_list[$id_order_detail]['amount'] = $order_detail->unit_price_tax_incl *
							 $order_detail_list[$id_order_detail]['quantity'];
				}
				else
					$order_detail_list[$id_order_detail]['amount'] = (float) str_replace(',', '.', $amount_detail);
				$amount += $order_detail_list[$id_order_detail]['amount'];
			}
			
			$shipping_cost_amount = (float) str_replace(',', '.', $data['partialRefundShippingCost']);
			if ($shipping_cost_amount > 0) {
				$amount += $shipping_cost_amount;
			}
		}
		
		// When the amount is not zero, we should consider also cancelQuantity. Otherwise the partialRefundProduct contains already the relevant stufff and
		// we do not need to take a look on cancelQuantity.
		if (isset($data['cancelQuantity']) && $amount == 0) {
			foreach ($data['cancelQuantity'] as $id_order_detail => $quantity) {
				$q = (int) $quantity;
				if ($q > 0) {
					$order_detail = new OrderDetail((int) $id_order_detail);
					$line_amount = $order_detail->unit_price_tax_incl * $q;
					$amount += $line_amount;
				}
			}
		}
		
		return $amount;
	}

	/**
	 * This method is used to add a special info field in the order
	 * Tab.
	 *
	 * @param array $params Hook parameters
	 * @return string the html output
	 */
	public function hookAdminOrder($params){
		$html = '';
		
		$order = new Order((int) $params['id_order']);
		if (!strstr($order->module, 'sagepaycw')) {
			return '';
		}
		$this->initialize();
		$errorMessage = '';
		try {
			$this->processAdminAction();
		}
		catch (Exception $e) {
			$errorMessage = $e->getMessage();
		}
		
		$transactions = SagePayCw_Entity_Transaction::getTransactionsByCartOrOrder($order->id_cart, $order->id);
		
		if (is_array($transactions) && count($transactions) > 0) {
			
			$activeTransactionId = false;
			if (isset($_POST['id_transaction'])) {
				$activeTransactionId = $_POST['id_transaction'];
			}
			
			$this->context->smarty->assign(
					array(
						'order_id' => $params['id_order'],
						'base_url' => _PS_BASE_URL_SSL_ . __PS_BASE_URI__,
						'transactions' => $transactions,
						'date_format' => $this->context->language->date_format_full,
						'errorMessage' => $errorMessage,
						'activeTransactionId' => $activeTransactionId 
					));
// 			$this->error = $errorMessage;
			
			$this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/css/admin.css');
			$this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/js/admin.js');
			$html .= $this->evaluateTemplate('/views/templates/back/admin_order.tpl');
		}
		
		return $html;
	}

	public function getConfigurationValue($key, $langId = null){
		return $this->getConfigApi()->getConfigurationValue($key, $langId);
	}

	public function hasConfigurationKey($key, $langId = null){
		return $this->getConfigApi()->hasConfigurationKey($key, $langId);
	}

	private function processAdminAction(){
		if (isset($_POST['id_transaction'])) {
			
			
			if (isset($_POST['submitSagePayCwRefund'])) {
				$amount = null;
				if (isset($_POST['refund_amount'])) {
					$amount = $_POST['refund_amount'];
				}
				
				$close = false;
				if (isset($_POST['close']) && $_POST['close'] == '1') {
					$close = true;
				}
				$this->refundTransaction($_POST['id_transaction'], $amount, $close);
			}
			
			

			
			if (isset($_POST['submitSagePayCwCancel'])) {
				$this->cancelTransaction($_POST['id_transaction']);
			}
			
			

			
			if (isset($_POST['submitSagePayCwCapture'])) {
				$amount = null;
				if (isset($_POST['capture_amount'])) {
					$amount = $_POST['capture_amount'];
				}
				
				$close = false;
				if (isset($_POST['close']) && $_POST['close'] == '1') {
					$close = true;
				}
				$this->captureTransaction($_POST['id_transaction'], $amount, $close);
			}
			
		}
	}
	
	
	public function refundTransaction($transactionId, $amount = null, $close = false){
		$this->initialize();
		$dbTransaction = SagePayCw_Entity_Transaction::loadById($transactionId);
		$adapter = SagePayCw_Util::createContainer()->getBean('Customweb_Payment_BackendOperation_Adapter_Service_IRefund');
		if ($dbTransaction->getTransactionObject() != null && $dbTransaction->getTransactionObject()->isRefundPossible()) {
			if ($amount !== null) {
				$items = Customweb_Util_Invoice::getItemsByReductionAmount(
						$dbTransaction->getTransactionObject()->getTransactionContext()->getOrderContext()->getInvoiceItems(), $amount, 
						$dbTransaction->getTransactionObject()->getCurrencyCode());
				$adapter->partialRefund($dbTransaction->getTransactionObject(), $items, $close);
			}
			else {
				$adapter->refund($dbTransaction->getTransactionObject());
			}
			SagePayCw_Util::getEntityManager()->persist($dbTransaction);
		}
		else {
			throw new Exception("The given transaction is not refundable.");
		}
	}
	
	

	
	public function captureTransaction($transactionId, $amount = null, $close = false){
		$this->initialize();
		$dbTransaction = SagePayCw_Entity_Transaction::loadById($transactionId);
		$adapter = SagePayCw_Util::createContainer()->getBean('Customweb_Payment_BackendOperation_Adapter_Service_ICapture');
		if ($dbTransaction->getTransactionObject() != null && $dbTransaction->getTransactionObject()->isCapturePossible()) {
			if ($amount !== null) {
				$items = Customweb_Util_Invoice::getItemsByReductionAmount(
						$dbTransaction->getTransactionObject()->getTransactionContext()->getOrderContext()->getInvoiceItems(), $amount, 
						$dbTransaction->getTransactionObject()->getCurrencyCode());
				$adapter->partialCapture($dbTransaction->getTransactionObject(), $items, $close);
			}
			else {
				$adapter->capture($dbTransaction->getTransactionObject());
			}
			SagePayCw_Util::getEntityManager()->persist($dbTransaction);
		}
		else {
			throw new Exception("The given transaction is not capturable.");
		}
	}
	
	

	
	public function cancelTransaction($transactionId){
		$this->initialize();
		$dbTransaction = SagePayCw_Entity_Transaction::loadById($transactionId);
		$adapter = self::createContainer()->getBean('Customweb_Payment_BackendOperation_Adapter_Service_ICancel');
		if ($dbTransaction->getTransactionObject() != null && $dbTransaction->getTransactionObject()->isCancelPossible()) {
			$adapter->cancel($dbTransaction->getTransactionObject());
			SagePayCw_Util::getEntityManager()->persist($dbTransaction);
		}
		else {
			throw new Exception("The given transaction cannot be cancelled.");
		}
	}
	
	private function evaluateTemplate($file){
		return $this->display(__FILE__, $file);
	}

	public function l($string, $specific = null, $id_lang = null){
		return self::translate($string, $specific);
	}

	public static function translate($string, $sprintf = null, $module = 'sagepaycw'){
		$stringOriginal = $string;
		$string = str_replace("\n", " ", $string);
		$string = preg_replace("/\t++/", " ", $string);
		$string = preg_replace("/( +)/", " ", $string);
		$string = preg_replace("/[^a-zA-Z0-9]*/", "", $string);
		
		$rs = Translate::getModuleTranslation($module, $string, $module, $sprintf);
		if ($string == $rs) {
			$rs = $stringOriginal;
		}
		
		if ($sprintf !== null && is_array($sprintf)) {
			$rs = Customweb_Core_String::_($rs)->format($sprintf);
		}
		
		if (version_compare(_PS_VERSION_, '1.6') > 0) {
			return htmlspecialchars_decode($rs);
		}
		else {
			return $rs;
		}
	}

	public static function getAdminUrl($controller, array $params, $token = true){
		if ($token) {
			$params['token'] = Tools::getAdminTokenLite($controller);
		}
		$id_lang = Context::getContext()->language->id;
		$path = Dispatcher::getInstance()->createUrl($controller, $id_lang, $params, false);
		$protocol = 'http://';
		$sslEnabled = Configuration::get('PS_SSL_ENABLED');
		$sslEverywhere = Configuration::get('PS_SSL_ENABLED_EVERYWHERE');
		if ((isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || $sslEnabled == '1' || $sslEverywhere == '1'){
			$protocol = 'https://';
		}
		
		return $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER["SCRIPT_NAME"]) . '/' . ltrim($path, '/');
	}

	private static function getShopIds(){
		$shops = array();
		$rs = Db::getInstance()->query('
				SELECT
					id_shop
				FROM
					`' . _DB_PREFIX_ . 'shop`');
		foreach ($rs as $data) {
			$shops[] = $data['id_shop'];
		}
		return $shops;
	}
	
	private function registerLogListener(){
		if (!self::$logListenerRegistered) {
			self::$logListenerRegistered = true;
			$level = SagePayCw::getInstance()->getConfigurationValue('log_level');
			if(strtolower($level) != 'off'){
				Customweb_Core_Logger_Factory::addListener(new SagePayCw_LoggingListener());
			}
		}
	}
}

// Register own translation function in smarty        		   	    	 		 
if (!function_exists('cwSmartyTranslate')) {
	global $smarty;

	function cwSmartyTranslate($params, $smarty){
		$sprintf = isset($params['sprintf']) ? $params['sprintf'] : null;
		if (empty($params['mod'])) {
			throw new Exception(sprintf("Could not translate string '%s' because no module was provided.", $params['s']));
		}
		
		return SagePayCw::translate($params['s'], $sprintf, $params['mod']);
	}
	smartyRegisterFunction($smarty, 'function', 'lcw', 'cwSmartyTranslate', false);
}



