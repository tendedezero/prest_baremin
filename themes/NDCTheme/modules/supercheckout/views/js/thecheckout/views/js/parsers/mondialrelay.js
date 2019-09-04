/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Software License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 *
 *  @author    Peter Sliacky (Zelarg)
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

checkoutShippingParser.mondialrelay = {
  init_once: function (elements) {
    if (debug_js_controller) {
      console.info('[thecheckout-mondialrelay.js] init_once()');
    }
  },

  delivery_option: function (element) {
    if (debug_js_controller) {
      console.info('[thecheckout-mondialrelay.js] delivery_option()');
    }

    // Uncheck mondialrelay item, so that it can be manually selected
    element.after("<script>$('.delivery-option.mondialrelay input[name^=delivery_option]').prop('checked', false)</script>");
  },

  extra_content: function (element) {
  }

}
