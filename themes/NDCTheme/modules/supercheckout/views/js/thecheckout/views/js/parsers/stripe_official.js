/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Software License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 *
 *  @author    Peter Sliacky (Zelarg)
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

checkoutPaymentParser.stripe_official = {
  all_hooks_content: function (content) {

  },

  form: function (element) {

    var paymentOptionForm = element;
    var staticContentContainer = $('#thecheckout-payment .static-content');

    // Now create new block with original Id and place it inside of static-content block
    if (!staticContentContainer.find('.stripe-payment-form').length) {
      $('<div class="stripe-payment-form"></div>').appendTo(staticContentContainer);
      paymentOptionForm.clone().appendTo(staticContentContainer.find('.stripe-payment-form'));
      staticContentContainer.find('.stripe-payment-form script').remove();

      // Init only once - when we're first time moving CC form
      var stripe_orig_script_tag = "<script> \
        if ('undefined' !== typeof StripePubKey) { \
          if (StripePubKey && typeof stripe_v3 !== 'object') { \
            stripe_v3 = Stripe(StripePubKey); \
          } \
          $('#stripe-payment-form').submit(function(event) { scrollToElement($('#stripe-payment-form').closest('.checkout-block')); return false; }); \
          initStripeOfficial(); \
        } \
        </script> \
      ";
      staticContentContainer.find('.stripe-payment-form').append(stripe_orig_script_tag);
    }

    // Remove stripe payment form from actual .js-payment-option-form container and keep only "dynamic" part,
    // which is <script> tag with dynamically created variables
    var scriptTag = paymentOptionForm.find('script');
    paymentOptionForm.find('*').remove();
    paymentOptionForm.prepend(scriptTag);

    // Update ID of fixed form, so that it's displayed/hidden automatically with payment method selection
    var origId = paymentOptionForm.attr('id');
    staticContentContainer.find('.stripe-payment-form .js-payment-option-form').attr('id', origId);

    // Remove tag ID and class from original form
    paymentOptionForm.attr('id', 'stripe-script-tag-container');
    paymentOptionForm.removeClass('js-payment-option-form');

  }

}


