/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Software License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 *
 *  @author    Peter Sliacky (Zelarg)
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

checkoutPaymentParser.amzpayments = {

  container: function (element) {
    var removeSubmitBtn = true;
    payment.setPopupPaymentType(element, removeSubmitBtn);
  },

  additionalInformation: function (element, triggerElementName) { 
    if (!payment.isConfirmationTrigger(triggerElementName)) {
      if (debug_js_controller) {
        console.info('[amzpayments] Not confirmation trigger, removing payment\'s additionalInformation div');
      }
      element.remove();
    }
  }

} 