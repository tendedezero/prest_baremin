<?php
/**
  * You are allowed to use this API in your web application.
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

require_once 'Customweb/SagePay/Method/DefaultMethod.php';
require_once 'Customweb/Payment/Util.php';
require_once 'Customweb/Form/ElementFactory.php';
require_once 'Customweb/Payment/Authorization/Moto/IAdapter.php';
require_once 'Customweb/Payment/Authorization/Method/CreditCard/ElementBuilder.php';

class Customweb_SagePay_Method_CreditCardMethod extends Customweb_SagePay_Method_DefaultMethod {

	public function getFormFields(Customweb_Payment_Authorization_IOrderContext $orderContext, $aliasTransaction, $failedTransaction, $authorizationMethod) {
		if($authorizationMethod == Customweb_Payment_Authorization_Server_IAdapter::AUTHORIZATION_METHOD_NAME ||
				$authorizationMethod == Customweb_Payment_Authorization_Moto_IAdapter::AUTHORIZATION_METHOD_NAME){
			$formBuilder = new Customweb_Payment_Authorization_Method_CreditCard_ElementBuilder();
				
			// Set field names
			$formBuilder
				->setCardHolderFieldName('card_holder')
				->setCardNumberFieldName('card_number')
				->setCvcFieldName('CV2')
				->setExpiryMonthFieldName('expiry_month')
				->setExpiryYearFieldName('expiry_year')
				->setCardHandler($this->getCardHandler());
			
			
			// Handle brand selection
			if (strtolower($this->getPaymentMethodName()) == 'creditcard') {
					$formBuilder->setAutoBrandSelectionActive(true);
			}
			else {
				$formBuilder
					->setFixedBrand(true)
					->setSelectedBrand($this->getPaymentMethodName())
				;
			}
			
			$formBuilder->setCardHolderName($orderContext->getBillingFirstName() . ' ' . $orderContext->getBillingLastName());
	
			if ($authorizationMethod === Customweb_Payment_Authorization_Moto_IAdapter::AUTHORIZATION_METHOD_NAME && !$this->getGlobalConfiguration()->isMotoCvcCheckEnabled()) {
				$formBuilder->setCvcFieldName(null);
			}
			
			if($aliasTransaction !== null && $aliasTransaction !== 'new'){
				$formBuilder->setCardHolderName($aliasTransaction->getCardHolderName());
				$formBuilder->setSelectedExpiryMonth($aliasTransaction->getCardExpiryMonth())
				->setSelectedExpiryYear($aliasTransaction->getCardExpiryYear())
				->setMaskedCreditCardNumber($aliasTransaction->getCardNumber());
			}
			
			return $formBuilder->build();
		}
		return array();
	}
	
	/**
	 * @return Customweb_Payment_Authorization_Method_CreditCard_CardHandler
	 */
	public function getCardHandler() {
		$parameterKeyForMappedBrand = 'CardType';
		if (strtolower($this->getPaymentMethodName()) == 'creditcard') {
			$informationMap = Customweb_Payment_Authorization_Method_CreditCard_CardInformation::getCardInformationObjects(
				$this->getPaymentInformationMap(), 
				$this->getPaymentMethodConfigurationValue('credit_card_brands'), 
				$parameterKeyForMappedBrand
			);
		}
		else {
			$informationMap = Customweb_Payment_Authorization_Method_CreditCard_CardInformation::getCardInformationObjects(
				$this->getPaymentInformationMap(),
				$this->getPaymentMethodName(),
				$parameterKeyForMappedBrand
			);
		}
		return new Customweb_Payment_Authorization_Method_CreditCard_CardHandler($informationMap);
	}
	
	public function getAuthorizationParameters(Customweb_SagePay_Authorization_Transaction $transaction, array $formData, $authorizationMethod, Customweb_DependencyInjection_IContainer $container) {
		$parameters = parent::getAuthorizationParameters($transaction, $formData, $authorizationMethod, $container);
		if($authorizationMethod == Customweb_Payment_Authorization_Server_IAdapter::AUTHORIZATION_METHOD_NAME ||
				$authorizationMethod == Customweb_Payment_Authorization_Moto_IAdapter::AUTHORIZATION_METHOD_NAME){
			if (empty($formData['card_holder'])) {
				throw new Exception(Customweb_I18n_Translation::__("You have to enter the card holder."));
			}
			$parameters['CardHolder'] = strip_tags($formData['card_holder']);
			
			if ($transaction->getTransactionContext()->getAlias() === NULL || $transaction->getTransactionContext()->getAlias() == 'new') {
				if (empty($formData['card_number'])) {
					throw new Exception(Customweb_I18n_Translation::__("You have to enter the a card number."));
				}
				$parameters['CardNumber'] = strip_tags($formData['card_number']);
				if (strtolower($this->getPaymentMethodName()) == 'creditcard') {
					$brandKey = $this->getCardHandler()->getBrandKeyByCardNumber($parameters['CardNumber']);
					$parameters['CardType'] = $this->getCardHandler()->mapBrandNameToExternalName($brandKey);
				}
					
			}
			
			if (empty($formData['expiry_month'])) {
				throw new Exception(Customweb_I18n_Translation::__("You have to enter the month of the card expiry."));
			}
			if (empty($formData['expiry_year'])) {
				throw new Exception(Customweb_I18n_Translation::__("You have to enter the year of the card expiry."));
			}
			$parameters['ExpiryDate'] = strip_tags($formData['expiry_month']) . substr(strip_tags($formData['expiry_year']), 2, 2);
			
			if (!$transaction->isMoto() || ($transaction->isMoto() && $this->getGlobalConfiguration()->isMotoCvcCheckEnabled()) ) {
				if (empty($formData['CV2'])) {
					throw new Exception(Customweb_I18n_Translation::__("You have to enter the CV2 code from the back of your credti card."));
				}
				$parameters['CV2'] = strip_tags($formData['CV2']);
				try {
					$ip = $container->getBean('Customweb_Core_Http_IRequest')->getRemoteAddress();
					if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
						//Sagepay only accepts IPv4
						$parameters['ClientIPAddress'] =  $ip;
					}
				}
				catch(Exception $e) {
					// Ignore, we simply not provide any IP address.
				}
			}
		}
		
		return $parameters;
	}
	
	public function getSpecialIframeParameters(Customweb_SagePay_Authorization_Transaction $transaction, array $formData, Customweb_DependencyInjection_IContainer $container){
		return array('Profile'=> 'LOW');
	}
	
	public function getIframeHeight(){
		return 550;
	}

}