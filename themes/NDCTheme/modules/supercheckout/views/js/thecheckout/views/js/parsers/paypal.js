/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Software License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 *
 *  @author    Peter Sliacky (Zelarg)
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

checkoutPaymentParser.paypal = {

  init_once: function (content, triggerElementName) {

    $.each(content, function (n, paymentContent) {
      if ($(paymentContent).find('.payment_module.braintree-card').length) {
        $(paymentContent).addClass('paypal-braintree-card');
        var braintreeRadio = $(paymentContent).find('.payment-option');
        payment.setPopupPaymentType(braintreeRadio);

        var formElement = $(paymentContent).find('.js-payment-option-form');

        if (!payment.isConfirmationTrigger(triggerElementName)) {
          if (debug_js_controller) {
            console.info('[paypal parser] Not confirmation trigger, removing payment form');
          }
          formElement.remove();
        } else {
          if ('undefined' !== typeof initBraintreeCard) {
            var additional_script_tag = '<script>\
        $(document).ready(function(){\
          if (\'undefined\' !== typeof initBraintreeCard) {\
            setTimeout(initBraintreeCard, 100);\
          }\
        });\
        </script>\
      ';
            formElement.append(additional_script_tag);
          }
        }

      }
    });
  },

  container: function (element) {

  },

  all_hooks_content: function (content) {

  },

  additionalInformation: function (element) {

    // Is this German paypal?
    if ('undefined' !== typeof PAYPAL && 'undefined' !== typeof PAYPAL.apps && 'undefined' !== typeof PAYPAL.apps.PPP) {
      var additional_script_tag = '<script>\
      if (\'undefined\' !== typeof ppp_mode && ppp_mode == \'sandbox\') {\
        showPui = true;\
      } else {\
        showPui = false;\
      }\
        ppp = PAYPAL.apps.PPP({\
        "approvalUrl": ppp_approval_url,\
        "placeholder": "ppplus",\
        "mode": ppp_mode,\
        "language": ppp_language_iso_code,\
        "country": ppp_country_iso_code,\
        "buttonLocation": "outside",\
        "useraction": "continue",\
        "showPuiOnSandbox": showPui,\
        });\
      </script>\
    ';

      element.append(additional_script_tag);
    }
    // end of German Paypal
  }

}
