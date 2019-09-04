/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Software License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 *
 *  @author    Peter Sliacky (Zelarg)
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

checkoutPaymentParser.ewayrapid = {
  additionalInformation: function (content) {

    // Unbind existing listeners and Fire DOMContentLoaded again
    /*var eway_rapid_after = '<script> \
      $("#processPayment").off("click"); \
      var DOMContentLoaded_event = document.createEvent("Event"); \
      DOMContentLoaded_event.initEvent("DOMContentLoaded", true, true); \
      window.document.dispatchEvent(DOMContentLoaded_event); \
      </script> \
    ';*/

    var initEcryptTag = "<script> \
          eCrypt.init(); \
    </script> \
    ";

    var regex = /document\.addEventListener\("DOMContentLoaded", function\(event\) {(.*?)}\);([^}]*?<\/script>)/g;
    var subst = '$1$2';

    htmlContent = content.html().replace(regex, subst);

    // remove link include if it's been once included already
    if ('undefined' !== typeof eCrypt) {
      var regex = /<script .*?eCrypt.js.*?script>/m;
      htmlContent = htmlContent.replace(regex, '');
      htmlContent = htmlContent + initEcryptTag;
    }

    content.html(htmlContent);
   // content.last().after(initEcryptTag);

  },

  container: function (element) {
  }

}
