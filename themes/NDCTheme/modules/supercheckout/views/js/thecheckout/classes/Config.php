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

namespace module\thecheckout;

use \Configuration;
use \Tools;
use \Context;

class Config
{
    const TEST_MODE_KEY_NAME = 'force-thecheckout';
    const ADDRESS_TYPE_INVOICE = 'invoice';
    const ADDRESS_TYPE_DELIVERY = 'delivery';

    public function isJsonField($fieldName)
    {
        $json_fields = array('customer_fields', 'invoice_fields', 'delivery_fields', 'blocks_layout');
        return in_array($fieldName, $json_fields);
    }

    public function isMultiLangField($fieldName)
    {
        $multilang_fields = array('html_box_1', 'html_box_2', 'html_box_3', 'html_box_4', 'required_checkbox_1', 'required_checkbox_2');
        return in_array($fieldName, $multilang_fields);
    }

    public function getAllOptions($prefix = 'TC_', $json = false)
    {
        $class_vars = get_class_vars(get_class($this));

        $result = array();

        foreach (array_keys($class_vars) as $name) {
            $result[$prefix . $name] = ($json && $this->isJsonField($name)) ? json_encode($this->$name,
                JSON_PRETTY_PRINT) : $this->$name;
        }

//        $languages        = Language::getLanguages(false);
//        foreach ($languages as $lang) {
//            $result[$prefix . $name][(int)$lang['id_lang']] = $this->$name;
//        }

        return $result;
    }

    public function __construct()
    {

        // set up config values from DB
        $all_options = $this->getAllOptions('');
        foreach (array_keys($all_options) as $key) {
            $dbVal = Configuration::get('TC_' . $key, Context::getContext()->language->id);

            // special treatment for CSS and JS cache version to fix PS caching issues
            if ("ps_css_cache_version" == $key) {
                $dbVal = (int)Configuration::get('PS_CCCCSS_VERSION');
            } elseif ("ps_js_cache_version" == $key) {
                $dbVal = (int)Configuration::get('PS_CCCJS_VERSION');
            }

            // For complex options, further manipulation is necessary
            if (false !== $dbVal) // Don't override locally set properties, if they're not set in DB
            {
                switch ($key) {
                    case 'customer_fields':
                    case 'invoice_fields':
                    case 'delivery_fields':
                    case 'blocks_layout':
                        // Some JSON parsing here? Or what about having that on all config vars?
                        $decodedString = json_decode($dbVal, true);
                        if (JSON_ERROR_NONE == json_last_error()) {
                            $this->$key = $decodedString;
                            // Special treatment for password field - always set by core option 'PS_GUEST_CHECKOUT_ENABLED'
                            if ('customer_fields' === $key) {
                                $this->{$key}['password']['required'] = !Configuration::get('PS_GUEST_CHECKOUT_ENABLED');
                            }
                        }
                        break;
                    default:
                        $this->$key = $dbVal;
                        break;
                }
            }
            //echo "updating $key with: ".Tools::getValue($key)."\n\n";
        }

        // Update primary_address based on shipping/billing blocks position
        // First, flatten layout array
        $output = array();
        array_walk_recursive($this->blocks_layout, function ($item, $key) use (&$output) {
            // this check is not necessary and is here just for a sake to satisfy Prestashop Validator
            if (null !== $item) {
                $output[] = $key;
            }
        });

        if (array_search('address-delivery', $output) < array_search('address-invoice', $output)) {
            $this->primary_address = self::ADDRESS_TYPE_DELIVERY;
        }
    }

    public function translateLastJsonError()
    {
        switch (json_last_error()) {
            case JSON_ERROR_DEPTH:
                return ' - Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return ' - Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return ' - Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return ' - Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return ' - Unknown error';
        }
    }

    public function updateByName($name)
    {
        $errorMsg = '';
        if ($this->isMultiLangField($name)) {
            $translatedFields = array($name => array());

            foreach ($_POST as $key => $value) {
                if (preg_match('/TC_' . $name . '_/i', $key)) {
                    $id_lang                                   = preg_split('/TC_' . $name . '_/i', $key);
                    $translatedFields[$name][(int)$id_lang[1]] = $value;
                }
            }
            Configuration::updateValue('TC_' . $name, $translatedFields[$name], true);

        } else {
            $formVal = Tools::getValue('TC_' . $name);

            // special treatment for CSS and JS cache version to fix PS caching issues
            if ("ps_css_cache_version" == $name) {
                // Knowing existing version is not that important, we'll update to whatever
                // is provided from configuration page, sometimes it's just fine even to downgrade version
                //$version = (int)Configuration::get('PS_CCCCSS_VERSION');
                Configuration::updateValue('PS_CCCCSS_VERSION', $formVal);
            } elseif ("ps_js_cache_version" == $name) {
                //$version = (int)Configuration::get('PS_CCCJS_VERSION');
                Configuration::updateValue('PS_CCCJS_VERSION', $formVal);
            }

            if ($this->isJsonField($name)) {
                $decodedString = json_decode($formVal, true); // true = return array instead of stdObject
                if (JSON_ERROR_NONE == json_last_error()) {
                    Configuration::updateValue('TC_' . $name, $formVal);
                    $this->$name = $decodedString; // update value also locally

                    // Special treatment for password field - whenever its 'required' status is updated here
                    // on config page, let's update PS core config value also
                    if ('customer_fields' === $name) {
                        Configuration::updateValue('PS_GUEST_CHECKOUT_ENABLED',
                            !($decodedString['password']['visible'] && $decodedString['password']['required']));
                    }
                } else {
                    $errorMsg = "<" . "b" . ">$name" . "<" . "/" . "b" . ">" . $this->translateLastJsonError() . "<" . "br /" . ">";
                }
            } else {
                Configuration::updateValue('TC_' . $name, $formVal);
                $this->$name = $formVal; // update value also locally
            }
        }
        return $errorMsg;
    }


    /* Config options not editable from BO by customer */
    public $test_mode = 1; // OPC module can be installed, but active only per-session (init with _GET[self::TEST_MODE_KEY_NAME]==1

    /* Customer editable options, these are only defaults, they get overwritten by BO setup */
    public $separate_cart_summary = 1; // PS 1.7 checkout has dedicated summary review step; this option=ON, will keep it
    public $primary_address = self::ADDRESS_TYPE_INVOICE; // 'invoice' or 'delivery'

    // Layout consists of 4 sections, 'top', 'bottom' are 100% wide, 'left'/'right' are split according to option $leftRightRatio
    public $blocks_layout = array(
        'flex-split-vertical' => array(
            0 => array(
                'blocks' => array(0 => array('login-form' => '',),),
                'size'   => 50,
            ),
            1 => array(
                'flex-split-vertical' => array(
                    0 => array(
                        'flex-split-horizontal' => array(
                            0 => array(
                                'blocks' => array(
                                    0 => array('account' => 'num-1',),
                                    1 => array('newsletter' => '',),
                                    2 => array('psgdpr' => '',),
                                    3 => array('data-privacy' => '',),
                                    4 => array('html-box-1' => '',),
                                ),
                                'size'   => 33,
                            ),
                            1 => array(
                                'flex-split-horizontal' => array(
                                    0 => array(
                                        'blocks' => array(
                                            0 => array('address-invoice' => 'num-2',),
                                            1 => array('address-delivery' => '',),
                                        ),
                                        'size'   => 50,
                                    ),
                                    1 => array(
                                        'blocks' => array(
                                            0 => array('shipping' => 'num-3',),
                                            1 => array('payment' => 'num-4',),
                                        ),
                                        'size'   => 50,
                                    ),
                                ),
                                'size'                  => 67,
                            ),
                        ),
                        'size'                  => 50,
                    ),
                    1 => array(
                        'flex-split-vertical' => array(
                            0 => array(
                                'blocks' => array(0 => array('cart-summary' => '',),),
                                'size'   => 50,
                            ),
                            1 => array(
                                'blocks' => array(
                                    0 => array('order-message' => '',),
                                    1 => array('confirm' => '',),
                                    2 => array('html-box-2' => '',),
                                    3 => array('html-box-3' => '',),
                                    4 => array('html-box-4' => '',),
                                    5 => array('required-checkbox-1' => '',),
                                    6 => array('required-checkbox-2' => '',),
                                ),
                                'size'   => 50,
                            ),
                        ),
                        'size'                => 50,
                    ),
                ),
                'size'                => 50,
            ),
        ),
        'size'                => 100
    );

    public $customer_fields = array(
        "email"     => array("visible" => true, "required" => true, "width" => 50),
        "password"  => array("visible" => true, "required" => false, "width" => 50),
        "id_gender" => array("visible" => false, "required" => false, "width" => 100),
        // To support 'company' and 'siret', Configuration::get('PS_B2B_ENABLE') - must be ON (due to core controllers working with
        // siret, ape and company only then; and we'd need to resolve duplicity field name on frontend (company field in address)
//        "company"    => array("visible" => true, "required" => false, "width" => 100),
//        "siret"            => array("visible" => true, "required" => false, "width" => 100),
        "birthday"  => array("visible" => false, "required" => false, "width" => 100),
        "optin"     => array("visible" => false, "required" => false, "width" => 100),
        // Newsletter, customer_privacy and psgdpr checkboxes will be handled in separate 'checkout-blocks'
        //"newsletter"       => array("visible" => true, "required" => false, "width" => 100),
        //"customer_privacy" => array("visible" => true, "required" => true, "width" => 100),
        //"psgdpr"           => array("visible" => true, "required" => true, "width" => 100),
    );

    public $module_customer_fields = array('newsletter', 'customer_privacy', 'psgdpr');

    public $invoice_fields = array(
        "firstname"    => array("visible" => true, "required" => true, "width" => 50, "live" => false),
        "lastname"     => array("visible" => true, "required" => true, "width" => 50, "live" => false),
        "company"      => array("visible" => true, "required" => false, "width" => 100, "live" => false),
        "dni"          => array("visible" => true, "required" => false, "width" => 100, "live" => false),
        "vat_number"   => array("visible" => true, "required" => false, "width" => 100, "live" => false),
        "address1"     => array("visible" => true, "required" => true, "width" => 75, "live" => true),
        "address2"     => array("visible" => false, "required" => false, "width" => 25, "live" => false),
        "city"         => array("visible" => true, "required" => true, "width" => 100, "live" => true),
        "State:name"   => array("visible" => true, "required" => true, "width" => 100, "live" => true),
        "postcode"     => array("visible" => true, "required" => false, "width" => 100, "live" => true),
        "Country:name" => array("visible" => true, "required" => true, "width" => 100, "live" => false),
        "phone"        => array("visible" => true, "required" => true, "width" => 100, "live" => false),
        "phone_mobile" => array("visible" => false, "required" => false, "width" => 100, "live" => false),
        "other"        => array("visible" => false, "required" => false, "width" => 100, "live" => false)
    );

    public $delivery_fields = array(
        "firstname"    => array("visible" => true, "required" => true, "width" => 50, "live" => false),
        "lastname"     => array("visible" => true, "required" => true, "width" => 50, "live" => true),
        "company"      => array("visible" => false, "required" => false, "width" => 100, "live" => false),
        "dni"          => array("visible" => false, "required" => false, "width" => 100, "live" => false),
        "vat_number"   => array("visible" => false, "required" => false, "width" => 100, "live" => false),
        "address1"     => array("visible" => true, "required" => true, "width" => 75, "live" => false),
        "address2"     => array("visible" => false, "required" => false, "width" => 25, "live" => false),
        "city"         => array("visible" => true, "required" => true, "width" => 100, "live" => false),
        "State:name"   => array("visible" => true, "required" => true, "width" => 100, "live" => true),
        "postcode"     => array("visible" => true, "required" => false, "width" => 100, "live" => true),
        "Country:name" => array("visible" => true, "required" => true, "width" => 100, "live" => false),
        "phone"        => array("visible" => true, "required" => true, "width" => 100, "live" => false),
        "phone_mobile" => array("visible" => false, "required" => false, "width" => 100, "live" => false),
        "other"        => array("visible" => false, "required" => false, "width" => 100, "live" => false)
    );

    public $checkout_substyle = "cute";
    public $font = "Montserrat";
    public $fontWeight = "500";

    public $using_material_icons = true;

    public $expand_second_address = 0;
    public $offer_second_address = 1;
    //public $disable_autocomplete = 1;

    public $default_payment_method = 'ps_wirepayment';
    public $register_guest_on_blur = 0;

    public $mark_required_fields = 1;
    public $clean_checkout_session_after_confirmation = 0;

    public $show_block_reassurance = 0;

    public $show_order_message = 0;

    public $show_i_am_business = 1;
    public $create_account_checkbox = 1;
    public $business_fields = 'company, dni, vat_number';
    public $show_shipping_country_in_carriers = 0;
    public $force_customer_to_choose_country = 0;
    public $force_email_overlay = 0;
    public $blocks_update_loader = '';
    public $compact_cart = 0;


    public $html_box_1 = '';
    public $html_box_2 = '';
    public $html_box_3 = '';
    public $html_box_4 = '';
    public $required_checkbox_1 = '';
    public $required_checkbox_2 = '';

    public $social_login_fb = 0;
    public $social_login_fb_app_id = '';
    public $social_login_fb_app_secret = '';
    public $social_login_google = 0;
    public $social_login_google_client_id = '';
    public $social_login_google_client_secret = '';
    public $social_login_btn_style = 'light';

    public $ps_css_cache_version;
    public $ps_js_cache_version;

    public $custom_css = '';
    public $custom_js = "document.addEventListener('DOMContentLoaded', function(event) { \n\t//jQuery shall be loaded now\n\t\n\t\n});";

    /* Not currently used options */
    public $require_email_first = 1; // Disallow address changes, if email is not entered; useful for Abandoned cart reminders
    public $in_field_labels = 1;

    public $pre_check_customer_privacy = 1;
    public $pre_check_terms_of_service = 1;
}
