<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright PrestaShop SA
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

use PrestaShop\PrestaShop\Core\Crypto\Hashing as Crypto;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * StarterTheme TODO: B2B fields, Genders, CSRF.
 */
class CheckoutCustomerForm extends AbstractForm
{
    protected $template = 'customer/_partials/customer-form.tpl';

    private $context;
    private $urls;
    private $crypto;

    private $customerPersister;
    private $guest_allowed;

    public function __construct(
        Smarty $smarty,
        Context $context,
        TranslatorInterface $translator,
        CheckoutCustomerFormatter $formatter,
        Crypto $crypto,
        CustomerPersister $customerPersister,
        array $urls
    ) {
        parent::__construct(
            $smarty,
            $translator,
            $formatter
        );

        $this->context           = $context;
        $this->urls              = $urls;
        $this->customerPersister = $customerPersister;
        $this->crypto            = $crypto;
    }

    public function setGuestAllowed($guest_allowed = true)
    {
        $this->formatter->setPasswordRequired(!$guest_allowed);
        $this->guest_allowed = $guest_allowed;

        return $this;
    }

    public function fillFromCustomer(Customer $customer)
    {
        $params                = get_object_vars($customer);
        $params['id_customer'] = $customer->id;
        $params['birthday']    = $customer->birthday === '0000-00-00' ? null : Tools::displayDate($customer->birthday);

        return $this->fillWith($params);
    }

    /**
     * @return \Customer
     */
    public function getCustomer()
    {
        $customer = new Customer($this->getValue('id_customer'));

        foreach ($this->formFields as $field) {
            $customerField = $field->getName();
            if ($customerField === 'id_customer') {
                $customerField = 'id';
            }
            if (property_exists($customer, $customerField)) {
                $customer->$customerField = $field->getValue();
            }
        }

        return $customer;
    }

    public function validate($silentRegistration = false)
    {
        $emailField  = $this->getField('email');
        $id_customer = Customer::customerExists($emailField->getValue(), true, true);
        $customer    = $this->getCustomer();
        if ($id_customer && $id_customer != $customer->id) {
            if (version_compare(_PS_VERSION_, '1.7.5') >= 0) {
                $emailField->addError($this->translator->trans(
                    'The email is already used, please choose another one or sign in', array(), 'Shop.Notifications.Error'
                ));
            } else {
                $emailField->addError($this->translator->trans(
                        'The email "%mail%" is already used, please choose another one or sign in',
                        array('%mail%' => $emailField->getValue()), 'Shop.Notifications.Error'
                    ) . '<'.'span id="sign-in-link"'.'>' . $this->translator->trans('Sign in', array(),
                        'Shop.Theme.Actions') . '<'.'/'.'span'.'>');
            }
        }

        // birthday is from input type text..., so we need to convert to a valid date
        $birthdayField = $this->getField('birthday');
        if (!empty($birthdayField)) {
            $birthdayValue = $birthdayField->getValue();
            if (!empty($birthdayValue)) {
                $dateBuilt = DateTime::createFromFormat(Context::getContext()->language->date_format_lite,
                    $birthdayValue);
                if (!empty($dateBuilt)) {
                    $birthdayField->setValue($dateBuilt->format('Y-m-d'));
                }
            }
        }

        if ($silentRegistration && Validate::isEmail($emailField->getValue())) {
            // Allow silent guest registration when email field emits blur() - called from checkEmail routine
            return true;
        } else {
            $this->validateFieldsLengths();
            $this->validateByModules();
            return parent::validate();
        }
    }

    protected function validateFieldsLengths()
    {
        $this->validateFieldLength('email', 128, $this->getEmailMaxLengthViolationMessage());
        $this->validateFieldLength('firstname', 255, $this->getFirstNameMaxLengthViolationMessage());
        $this->validateFieldLength('lastname', 255, $this->getLastNameMaxLengthViolationMessage());
    }

    /**
     * @param $fieldName
     * @param $maximumLength
     * @param $violationMessage
     */
    protected function validateFieldLength($fieldName, $maximumLength, $violationMessage)
    {
        $emailField = $this->getField($fieldName);
        if (Tools::strlen($emailField->getValue()) > $maximumLength) {
            $emailField->addError($violationMessage);
        }
    }

    /**
     * @return mixed
     */
    protected function getEmailMaxLengthViolationMessage()
    {
        return $this->translator->trans(
            'The %1$s field is too long (%2$d chars max).',
            array('email', 128),
            'Shop.Notifications.Error'
        );
    }

    protected function getFirstNameMaxLengthViolationMessage()
    {
        return $this->translator->trans(
            'The %1$s field is too long (%2$d chars max).',
            array('first name', 255),
            'Shop.Notifications.Error'
        );
    }

    protected function getLastNameMaxLengthViolationMessage()
    {
        return $this->translator->trans(
            'The %1$s field is too long (%2$d chars max).',
            array('last name', 255),
            'Shop.Notifications.Error'
        );
    }

    private function saveCustomer($customer, $clearTextPassword)
    {
        if ($customer->id) {
            return $this->_updateCustomer($customer, $clearTextPassword);
        } else {
            return $this->_createCustomer($customer, $clearTextPassword);
        }
    }

    private function _updateCustomer($customer, $clearTextPassword)
    {
        if ($customer->is_guest) {
            if ($customer->id != $this->context->customer->id) {

                $this->errors['email'][] = $this->translator->trans(
                    'There seems to be an issue with your account, please contact support',
                    array(),
                    'Shop.Notifications.Error'
                );
                return false;
            }
        }

        $guest_to_customer = false;

        if ($clearTextPassword && $customer->is_guest) {
            $guest_to_customer  = true;
            $customer->is_guest = false;
            $customer->passwd   = $this->crypto->hash(
                $clearTextPassword,
                _COOKIE_KEY_
            );
        }

        if ($customer->is_guest || $guest_to_customer) {
            // guest cannot update their email to that of an existing real customer
            if (Customer::customerExists($customer->email, false, true)) {
                $this->errors['email'][] = $this->translator->trans(
                    'An account was already registered with this email address',
                    array(),
                    'Shop.Notifications.Error'
                );
                return false;
            }
        }

        $ok = $customer->save();

        if ($ok) {
            //$this->context->updateCustomer($customer);
            $this->_updateCustomerInContext($customer);
            $this->context->cart->update();
            Hook::exec('actionCustomerAccountUpdate', array(
                'customer' => $customer,
            ));
            if ($guest_to_customer) {
                $this->_sendConfirmationMail($customer);
            }
        }

        return $ok;
    }

    private function _createCustomer(Customer $customer, $clearTextPassword)
    {
        if (!$clearTextPassword) {
            if (!$this->guest_allowed) {
                $this->errors['password'][] = $this->translator->trans(
                    'Password is required',
                    array(),
                    'Shop.Notifications.Error'
                );
                return false;
            }

            $clearTextPassword = $this->crypto->hash(
                microtime(),
                _COOKIE_KEY_
            );

            $customer->is_guest = true;
        }

        $customer->passwd = $this->crypto->hash(
            $clearTextPassword,
            _COOKIE_KEY_
        );

        if (Customer::customerExists($customer->email, false, true)) {
            $this->errors['email'][] = $this->translator->trans(
                'An account was already registered with this email address',
                array(),
                'Shop.Notifications.Error'
            );
            return false;
        }

        $ok = $customer->save();

        if ($ok) {
            //$this->context->updateCustomer($customer);
            $this->_updateCustomerInContext($customer);
            $this->context->cart->update();
            $this->_sendConfirmationMail($customer);
            Hook::exec('actionCustomerAccountAdd', array(
                'newCustomer' => $customer,
            ));
        }

        return $ok;
    }

    private function _updateCustomerInContext(Customer $customer) {
        $customer->logged = 1;
        $this->context->customer = $customer;
        $this->context->cookie->id_customer = (int) $customer->id;
        $this->context->cookie->customer_lastname = $customer->lastname;
        $this->context->cookie->customer_firstname = $customer->firstname;
        $this->context->cookie->passwd = $customer->passwd;
        $this->context->cookie->logged = 1;
        $this->context->cookie->email = $customer->email;
        $this->context->cookie->is_guest =  $customer->isGuest();
        $this->context->cart->secure_key = $customer->secure_key;
    }

    private function _sendConfirmationMail(Customer $customer)
    {
        if ($customer->is_guest || !Configuration::get('PS_CUSTOMER_CREATION_EMAIL')) {
            return true;
        }

        return Mail::Send(
            $this->context->language->id,
            'account',
            $this->translator->trans(
                'Welcome!',
                array(),
                'Emails.Subject'
            ),
            array(
                '{firstname}' => $customer->firstname,
                '{lastname}'  => $customer->lastname,
                '{email}'     => $customer->email,
            ),
            $customer->email,
            $customer->firstname . ' ' . $customer->lastname
        );
    }

    public function submit($silentRegistration = false)
    {
        if ($this->context->customer->isLogged()) {
            $this->formFields['password']->setRequired(false);
        }
        if ($this->validate($silentRegistration)) {
            $clearTextPassword = $this->getValue('password');
            // $newPassword is never used, we don't change password on checkout form
            // password change is possible only through Prestashop's controllers
            //$newPassword       = $this->getValue('new_password');

            // TheCheckout modification, to allow customer details (birthday, social title) modification
            // after customer have been logged in.
            if ($this->context->customer->isLogged()) {
                $customer = $this->getCustomer();
                $saveOk   = $customer->save();

                if ($saveOk) {
                    //$this->context->updateCustomer($customer);
                    $this->_updateCustomerInContext($customer);
                    $this->context->cart->update();
                    Hook::exec('actionCustomerAccountUpdate', array(
                        'customer' => $customer,
                    ));
                }
                return true;
            }

            $ok = $this->saveCustomer(
                $this->getCustomer(),
                $clearTextPassword
            );

//            $ok = $this->customerPersister->save(
//                $this->getCustomer(),
//                $clearTextPassword,
//                $newPassword
//            );

            if (!$ok) {
                foreach ($this->customerPersister->getErrors() as $field => $errors) {
                    $this->formFields[$field]->setErrors($errors);
                }
            }

            return $ok;
        }

        return false;
    }

    public function getTemplateVariables()
    {
        return array(
            'action'                   => $this->action,
            'urls'                     => $this->urls,
            'errors'                   => $this->getErrors(),
            'hook_create_account_form' => Hook::exec('displayCustomerAccountForm'),
            'formFields'               => array_map(
                function (FormField $field) {
                    return $field->toArray();
                },
                $this->formFields
            ),
        );
    }

    /**
     * This function call the hook validateCustomerFormFields of every modules
     * which added one or several fields to the customer registration form.
     *
     * Note: they won't get all the fields from the form, but only the one
     * they added.
     */
    private function validateByModules()
    {
        $formFieldsAssociated = array();
        // Group FormField instances by module name
        foreach ($this->formFields as $formField) {
            if (!empty($formField->moduleName)) {
                $formFieldsAssociated[$formField->moduleName][] = $formField;
            }
        }
        // Because of security reasons (i.e password), we don't send all
        // the values to the module but only the ones it created
        foreach ($formFieldsAssociated as $moduleName => $formFields) {
            if ($moduleId = Module::getModuleIdByName($moduleName)) {
                // ToDo : replace Hook::exec with HookFinder, because we expect a specific class here
                $validatedCustomerFormFields = Hook::exec('validateCustomerFormFields', array('fields' => $formFields),
                    $moduleId, true);

                if (is_array($validatedCustomerFormFields)) {
                    array_merge($this->formFields, $validatedCustomerFormFields);
                }
            }
        }
    }
}
