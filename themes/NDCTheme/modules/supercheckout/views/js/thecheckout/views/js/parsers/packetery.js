/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Software License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 *
 *  @author    Peter Sliacky (Zelarg)
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

tc_confirmOrderValidations['packetery'] = function() { 
  if (
    $('#packetery-widget select[name=name]').is(':visible') && 
    !$('#packetery-widget select[name=name]').val()
    ) {
    var shippingErrorMsg = $('#thecheckout-shipping > .inner-area > .error-msg');
    shippingErrorMsg.show();
    scrollToElement(shippingErrorMsg);
    return false; 
  } else {
    return true;
  }
}

checkoutShippingParser.packetery = {
  init_once: function (elements) {
    
  },

  delivery_option: function (element) {
    
  },

  extra_content: function (element) {
    element.after("<script>\
      $(document).ready(function(){\
        tools.fixextracontent();\
        tools.readAjaxFields();\
        var packeteryEl = $('.carrier-extra-content.packetery');\
        if (packeteryEl.length) {\
          var extra = packeteryEl.parent();\
          packetery.widgetGetCities(extra);\
        }\
      });\
      </script>");
  }

}
