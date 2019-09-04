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

use Symfony\Component\Translation\TranslatorInterface;

class CheckoutAddressFormatter implements FormFormatterInterface
{
    private $country;
    private $translator;
    private $availableCountries;
    private $definition;
    private $requiredFields;
    private $ignoreRequired;

    public function __construct(
        Country $country,
        TranslatorInterface $translator,
        array $availableCountries,
        array $requiredFields
    ) {
        $this->country            = $country;
        $this->translator         = $translator;
        $this->availableCountries = $availableCountries;
        $this->definition         = Address::$definition['fields'];
        $this->requiredFields     = $requiredFields;
        $this->ignoreRequired     = empty ($requiredFields);
    }

    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    private function setFieldRequired($formField)
    {
        if (!$this->ignoreRequired) {
            $formField->setRequired(true);
        }
    }

    public function getFormat()
    {

        $required = array_flip($this->requiredFields);

        // TODO: add all address fields here
        $fields = array(
            "company",
            "vat_number",
            "dni",
            "firstname",
            "lastname",
            "address1",
            "address2",
            "city",
            "State:name",
            "postcode",
            "Country:name",
            "phone",
            "phone_mobile",
            "other",
        );

        $format = array(
            'id_address'  => (new FormField)
                ->setName('id_address')
                ->setType('hidden'),
            'id_customer' => (new FormField)
                ->setName('id_customer')
                ->setType('hidden'),
            'back'        => (new FormField)
                ->setName('back')
                ->setType('hidden'),
            'token'       => (new FormField)
                ->setName('token')
                ->setType('hidden'),
//            'alias'       => (new FormField)
//                ->setName('alias')
//                ->setLabel(
//                    $this->getFieldLabel('alias')
//                )
        );

        foreach ($fields as $field) {
            $formField = new FormField();
            $formField->setName($field);

            $fieldParts = explode(':', $field, 2);

            if (count($fieldParts) === 1) {
                if ($field === 'postcode') {
                    if ($this->country->need_zip_code) {
                        $this->setFieldRequired($formField);
                    }
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
                    $formField->setValue($this->country->id);
                    foreach ($this->availableCountries as $country) {
                        $formField->addAvailableValue(
                            $country['id_country'],
                            $country[$entityField]
                        );
                    }
                } elseif ($entity === 'State') {
                    if ($this->country->contains_states) {
                        $states = State::getStatesByIdCountry($this->country->id);
                        foreach ($states as $state) {
                            $formField->addAvailableValue(
                                $state['id_state'],
                                $state[$entityField]
                            );
                        }
                        $this->setFieldRequired($formField);
                    }
                }
            }

            $formField->setLabel($this->getFieldLabel($field));
            if (!$formField->isRequired()) {
                // Only trust the $required array for fields
                // that are not marked as required.
                // $required doesn't have all the info, and fields
                // may be required for other reasons than what
                // AddressFormat::getFieldsRequired() says.

                if (array_key_exists($field, $required)) {
                    $this->setFieldRequired($formField);
                }
            }

            $format[$formField->getName()] = $formField;
        }

        return $this->addConstraints(
            $this->addMaxLength(
                $format
            )
        );
    }

    private function addConstraints(array $format)
    {
        foreach ($format as $field) {
            if (!empty($this->definition[$field->getName()]['validate'])) {
                $field->addConstraint(
                    $this->definition[$field->getName()]['validate']
                );
            }
        }

        return $format;
    }

    private function addMaxLength(array $format)
    {
        foreach ($format as $field) {
            if (!empty($this->definition[$field->getName()]['size'])) {
                $field->setMaxLength(
                    $this->definition[$field->getName()]['size']
                );
            }
        }

        return $format;
    }

    private function getFieldLabel($field)
    {
        // Country:name => Country, Country:iso_code => Country,
        // same label regardless of which field is used for mapping.
        $field = explode(':', $field)[0];

        switch ($field) {
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
            default:
                return $field;
        }
    }
}
