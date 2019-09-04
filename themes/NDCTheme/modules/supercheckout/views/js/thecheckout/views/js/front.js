/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Software License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 *
 *  @author    Peter Sliacky (Zelarg)
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
// debug_js_controller property is set in front.tpl

var checkoutPaymentParser = {};
var checkoutShippingParser = {};

var tcIsMobileView = false; // we will allways start with false, our markup from server is desktop optimized
var tcMobileViewThreshold = 991;

var tc_confirmOrderValidations = {};

// markup added to .inner-area of checkout blocks on updateHtml, while waiting for ajax response
var tc_loaderHtml = '\
   <div class="tc-ajax-loading">\
    <div class="tc-spinner">\
    <div class="bounce1"></div>\
    <div class="bounce2"></div>\
    <div class="bounce3"></div>\
    </div>\
   </div>';

$(document).ready(function () {

  if (debug_js_controller) {
    console.info('front.js loaded!');
  }

  $('body').addClass('document-ready');

  initBlocksSelectors();
  getShippingAndPaymentBlocks();
  //getCartSummary();

  // Remove default checkout handlers (e.g. country change)
  $('body').off('change', '.js-country');

  // Set handlers
  // Check if email was registered
  $('body').on('change', '#thecheckout-account [name=email]', function (e) {
    checkEmail($(this));
  });

  // On these fields modification, address shall be stored and carriers / payments reloaded
  $('body').on('change', '.live', function () {
    modifyAccountAndAddress($(this));
  });

  $('body').on('change', '.checkout-block input.-error, .checkout-block select.-error', function () {
    $(this).removeClass('-error').addClass('-former-error');
    checkAndHideGlobalError();
  });

  $('body').on('change', '#js-delivery input', function () {
    selectDeliveryOption($('#js-delivery')); // delivery form object as parameter
  });

  $('body').on('change', '.js-gift-checkbox', function () {
    toggleGiftMessage();
  });

  $('body').on('blur', '#gift_message', function () {
    selectDeliveryOption($('#js-delivery'));
  });

  $('body').on('blur', '#delivery_message', function () {
    setDeliveryMessage();
  });

  $('body').on('click', '[data-link-action=x-delete-from-cart]', function () {
    deleteFromCart($(this).data());
    return false;
  });

  $('body').on('click', '[data-link-action=x-sign-in]', function () {
    signIn();
    return false;
  });

  $('body').on('click', '[data-link-action=x-forced-email-continue]', function () {
    var enteredEmail = $('[name=forced-email]').val();

    if (!tc_helper_validateEmail(enteredEmail)) {
      $('.error-enter-email').show();
    } else {
      // refreshing of shipping/payment blocks is done in emailCheck, if dummycontainers still exist
      $('#thecheckout-account [name=email]').val(enteredEmail).trigger('change');
      $('body').removeClass('force-email-overlay');
    }

    return false;
  });

  // Trigger above defined routine also on Enter keypress
  $('body').on('keyup', '[name=forced-email]', function (event) {
    $('.error-enter-email').hide();
    if (event.key !== "Enter")
      return; // Use `.key` instead.
    $('[data-link-action=x-forced-email-continue]').trigger('click');
    event.preventDefault();
  });


  $('body').on('click', '[data-link-action=x-confirm-order]', function () {
    confirmOrder($(this));
    return false;
  });

  $('body').on('click', '[data-link-action=x-save-account-overlay]', function () {
    confirmOrder($(this));
    return false;
  });

  $('body').on('click', '[data-link-action=x-add-voucher]', function () {
    addVoucher();
    return false;
  });

  $('body').on('click', '[data-link-action=x-remove-voucher]', function () {
    removeVoucher($(this).data());
    return false;
  });

  $('body').on('change', '[data-link-action=x-create-account]', function () {
    if ($(this).prop('checked')) {
      $('#thecheckout-account .form-group.password').slideDown('fast', function () {
        $(this).removeClass('hidden')
      });
    } else {
      $('#thecheckout-account .form-group.password').slideUp('fast');
    }
    return false;
  });

  $('body').on('change', '[data-link-action=x-ship-to-different-address]', function () {

    if ($('#thecheckout-address-delivery').is(':visible')) {
      $(this).prop('checked', false);
      $('#thecheckout-address-delivery').hide(10, function () {
        //modifyAccountAndAddress($('#thecheckout-address-invoice [name=id_country]'));
        modifyAddressSelection('delivery');
      });

    } else {
      $(this).prop('checked', true);
      $('#thecheckout-address-delivery').show(10, function () {
        //modifyAccountAndAddress($('#thecheckout-address-delivery [name=id_country]'));
        modifyAddressSelection('delivery');
      });

    }
    return false;
  });

  $('body').on('change', '[data-link-action=x-bill-to-different-address]', function () {
    if ($('#thecheckout-address-invoice').is(':visible')) {
      $(this).prop('checked', false);
      $('#thecheckout-address-invoice').hide(10, function () {
        modifyAddressSelection('invoice');
        //modifyAccountAndAddress($('#thecheckout-address-delivery [name=id_country]'));
      });
    } else {
      $(this).prop('checked', true);
      $('#thecheckout-address-invoice').show(10, function () {
        modifyAddressSelection('invoice');
        //modifyAccountAndAddress($('#thecheckout-address-invoice [name=id_country]'));
      });
    }
    return false;
  });

  $('body').on('change', '[data-link-action=x-invoice-addresses]', function () {
    modifyAddressSelection('invoice');
    return false;
  });

  $('body').on('change', '[data-link-action=x-delivery-addresses]', function () {
    modifyAddressSelection('delivery');
    return false;
  });

  $('body').on('change', '[data-link-action=x-i-am-business]', function () {
    var businessFieldsSelector = '#thecheckout-address-invoice .form-group.business-field';
    if ($(this).prop('checked')) {
      $(businessFieldsSelector).not('.hidden').show();
      $('.business-fields-container').css('display', 'flex');
    } else {
      $(businessFieldsSelector + ', .business-fields-container').hide();
    }
    if ($(businessFieldsSelector + ' .live').length) {
      modifyAccountAndAddress($(businessFieldsSelector + ' .live').first());
    }

    return false;
  });


  $('body').on('click', '[data-link-action=toggle-password-visibility]', function () {
    var input = $(this).closest('label').find('input');
    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
    return false;
  });

  $('body').on('click', '[data-link-action=x-add-new-address]', function () {
    $(this).parent('.customer-addresses').find('.addresses-selection')
      .removeClass('hidden')
      .find('select').val(-1).trigger('change');
    $(this).hide();
    return false;
  });


  var quantityInputFieldTimeout;

  $('body').on('input', '[data-link-action=x-update-cart-quantity]', function () {
    if (quantityInputFieldTimeout) {
      clearTimeout(quantityInputFieldTimeout);
    }
    var el = $(this);
    var timeout = (1 == $(this).data('no-wait')) ? 0 : 500;
    quantityInputFieldTimeout = setTimeout(function () {
      updateQuantityFromInput(el);
    }, timeout);
    return false;
  });
  $('body').on('click', '[data-link-action=x-update-cart-quantity-up]', function () {
    var inputEl = $(this).parent().find('[data-link-action=x-update-cart-quantity]');
    inputEl.val(parseInt(inputEl.val()) + 1).data('no-wait', 1).trigger('input');
    return false;
  });
  $('body').on('click', '[data-link-action=x-update-cart-quantity-down]', function () {
    var inputEl = $(this).parent().find('[data-link-action=x-update-cart-quantity]');
    if (parseInt(inputEl.attr('min')) < parseInt(inputEl.val()))
      inputEl.val(parseInt(inputEl.val()) - 1).data('no-wait', 1).trigger('input');
    return false;
  });

  // Remove errors from checkboxes on their modification
  $('body').on('change', '.form-group.checkbox input[type=checkbox], [data-link-action=x-create-account]', function () {
    $(this).closest('.form-group').find('.field.error-msg').remove();
    checkAndHideGlobalError();
    modifyCheckboxOption($(this));
  });

  $('body').on('change', 'input[id^=conditions_to_approve]', function () {
    $(this).closest('.terms-and-conditions').find('.error-msg').hide();
    checkAndHideGlobalError();
    modifyCheckboxOption($(this));
  });

  $('body').on('change', 'input[name=id_gender]', function () {
    $(this).closest('.form-group').find('.field.error-msg').remove();
    checkAndHideGlobalError();
    modifyRadioOption($(this));
  });

  $('body').on('click', '[data-link-action=x-offer-login]', function (event) {
    return openLoginForm();
  });

  $('body').on('click', '.error-msg #sign-in-link', function (event) {
    event.preventDefault();
    return openLoginForm();
  });

  // On *any* modification, hide binary payment and let user save again
  $('body').on('change', 'input', function () {
    payment.hideBinary();
  });

  // triggering 'change' events earlier then on focusOut
  var tc_fieldChangeObserverTimeout = {};
  var tc_inputTriggerChangeTimeoutMillis = 1500;
  $('body').on('input', '.checkout-block .text input', function () {
    $self = $(this);
    clearTimeout(tc_fieldChangeObserverTimeout[$self.attr('name')]);
    tc_fieldChangeObserverTimeout[$self.attr('name')] =
      setTimeout(function () {
        $self.trigger('change')
      }, tc_inputTriggerChangeTimeoutMillis);
  });
  $('body').on('change', '.checkout-block .text input', function () {
    $self = $(this);
    clearTimeout(tc_fieldChangeObserverTimeout[$self.attr('name')]);
  });

  handleWindowResize($(this)); // Init - (to switch to mobile, if we're on small screen)
  $(window).on('resize', function () {
    handleWindowResize($(this));
  });

  // Show password "red_eye" iconds to switch between password and text field
  $('[data-link-action="toggle-password-visibility"]').removeClass('hidden');

  $(document).ajaxError(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
    console.info("Ajax error \n\nDetails:\nError thrown: " + thrownError + "\n" +
      'event: ');
    console.info(event);
    console.info("\n" + 'xhr: ');
    console.info(xhr);
    console.info("\n" + 'ajaxOptions: ');
    console.info(ajaxOptions);
  });

  // Modal window on terms and conditions link click
  $("#main").on("click", ".js-terms a", function (t) {
    t.preventDefault();
    var e = $(t.target).attr("href");
    e && (e += "?content_only=1",
      $.get(e, function (t) {
        $("#modal").find(".js-modal-content").html($(t).find(".page-cms").contents())
      }).fail(function (t) {
        l.default.emit("handleError", {
          eventType: "clickTerms",
          resp: t
        })
      })),
      $("#modal").modal("show");
  });

  promoteBusinessFields();

  if (config_force_customer_to_choose_country) {
    tc_confirmOrderValidations['force_customer_to_choose_country'] = function () {
      if (
        $('#thecheckout-shipping .dummy-block-container.disallowed').is(":visible") ||
        $('#thecheckout-payment .dummy-block-container.disallowed').is(":visible")
      ) {
        scrollToElement($('.dummy-block-container.disallowed').first());
        $('.dummy-block-container.disallowed').css('color', 'red');
        return false;
      } else {
        return true;
      }
    };
  }
  // Register global events on every ajax request and watch out for property 'customPropAffectedBlocks'
  // in $.ajax settings; and for such property, display loader animation.
  if (config_blocks_update_loader) {
    $(document).ajaxSend(function (event, jqxhr, settings) {
      if ('undefined' !== typeof settings.customPropAffectedBlocks) {
        // attach loader to element specified by selector 'customPropAffectedBlocks'
        // removed: we don't need clean up, default loader serves very well and once it is removed, we do
        // standard attach / remove HTML (tc_loaderHTML)
        //$(settings.customPropAffectedBlocks).find('.inner-area .dummy-block-container .tc-spinner').remove();
        // append loader right before update
        $(settings.customPropAffectedBlocks).find('.inner-area').prepend(tc_loaderHtml);
      }
    });

    $(document).ajaxComplete(function (event, xhr, settings) {
      if ('undefined' !== typeof settings.customPropAffectedBlocks) {
        // remove loader from element specified by selector 'customPropAffectedBlocks'
        $(settings.customPropAffectedBlocks).find('.inner-area > .tc-ajax-loading:first-child').remove();
      }
    });
  }


});

function initBlocksSelectors() {
  shippingBlockElement = $('#thecheckout-shipping .inner-area');
  paymentBlockElement = $('#thecheckout-payment .inner-area .dynamic-content');
  cartSummaryBlockElement = $('#thecheckout-cart-summary .inner-area');
  invoiceAddressBlockElement = $('#thecheckout-address-invoice .inner-area');
  deliveryAddressBlockElement = $('#thecheckout-address-delivery .inner-area');
}

function handleWindowResize(win) {
  if (win.width() <= tcMobileViewThreshold && !tcIsMobileView) {
    tcIsMobileView = true;
    // Take out all checkout blocks from their desktop layout and put into new container for mobile sorting
    $('.checkout-block').each(function () {
      $(this).appendTo('#tc-container-mobile');
    });
  } else if (win.width() > tcMobileViewThreshold && tcIsMobileView) {
    tcIsMobileView = false;
    // Put .checkout-block containers back to desktop (out of mobile / single column layout)
    $('.checkout-block').each(function () {
      $(this).insertAfter('.tc-block-placeholder.' + $(this).attr('id'));
    });
  }
}

function openLoginForm() {
  $('#login-form [name=email]').val($('#thecheckout-account [name=email]').val());
  $('.offer-login').addClass('expanded');
  $('#login-form').fadeIn();
  scrollToElement($('#login-form').closest('.checkout-block'));
  return false;
}

function formatErrors(errors, tag) {
  if ('undefined' === typeof tag) {
    tag = 'div';
  }
  var errMsg = "";
  $.each(errors, function (index, value) {
    if ("" !== jQuery.trim(value)) {
      errMsg += "<" + tag + ">";
      if ("" !== jQuery.trim(index) && isNaN(index)) {
        errMsg += index + ': ';
      }
      errMsg += value + "</" + tag + ">\n";
    }
  });
  return errMsg;
}

function checkAndHideGlobalError() {
  if (0 == $('.field.error-msg:visible').length) {
    $('#tc-payment-confirmation > .error-msg').hide();
  }
}

function showGlobalError() {
  $('#tc-payment-confirmation > .error-msg').show();
  scrollToError();
}

function scrollToError() {
  scrollToElement($('.error-msg:visible').closest('.form-group'));
}

function scrollToElement(element) {
  var scrollOffset = ("undefined" !== typeof globalScrollOffset) ? globalScrollOffset : 20;
  if (element.length) {
    var actions = computeScrollIntoView(element.get(0), {
      behavior: 'smooth',
      scrollMode: 'if-needed',
      block: 'start'
    });
    if ("undefined" !== typeof actions[0]) {
      window.scrollTo({
        top: actions[0].top - scrollOffset,
        behavior: "smooth"
      });
    }
  }
}

function showError(element) {
  $(element).show();
}

function hideError(element) {
  $(element).hide();
  checkAndHideGlobalError();
}

function removeError(element) {
  $(element).remove();
  checkAndHideGlobalError();
}

// Modify checkout option (typically checkbox) and send it to backend to be remembered in session (cookie)
function modifyCheckboxOption(element) {
  $.ajax({
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "&ajax_request=1&action=modifyCheckboxOption" +
      "&name=" + element.attr('name') +
      "&isChecked=" + element.is(':checked') +
      "&token=" + static_token,
    success: function (jsonData) {

    }
  });
}

// Modify checkout option (typically checkbox) and send it to backend to be remembered in session (cookie)
function modifyRadioOption(radioElements) {
  var elName = radioElements.attr('name');
  var checkedElement = $('[name=' + elName + ']:checked');
  $.ajax({
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "&ajax_request=1&action=modifyRadioOption" +
      "&name=" + checkedElement.attr('name') +
      "&checkedValue=" + checkedElement.val() +
      "&token=" + static_token,
    success: function (jsonData) {

    }
  });
}

function printContextErrors(blockSel, errors, triggerElement, dontShowGlobal) {

  var highlightOnElements = [];
  if ("undefined" !== typeof triggerElement
    && !isMainConfirmationButton(triggerElement)
    && !isSaveAccountOverlayConfirmation(triggerElement)) {
    highlightOnElements.push(triggerElement.attr('name'));
    removeError(blockSel + ' [name=' + triggerElement.attr('name') + '] ~ .field.error-msg');
    // With country change, re-validate postcode, if it's filled in
    if ("id_country" === triggerElement.attr('name') && "" != $(blockSel + ' [name=postcode]').val()) {
      highlightOnElements.push('postcode');
      $(blockSel + ' [name=postcode]').removeClass('-error');
      removeError(blockSel + ' [name=postcode] ~ .field.error-msg');
    }
  } else {
    removeError(blockSel + ' .field.error-msg');
    $(blockSel + ' .error').removeClass('-error');
  }

  $.each(errors, function (index, value) {
    if ("" !== jQuery.trim(value) && (0 == highlightOnElements.length || highlightOnElements.indexOf(index) > -1)) {
      $(blockSel + ' [name=' + index + ']').addClass('-error');
      if ($(blockSel + ' [name=' + index + ']').is(':checkbox') || $(blockSel + ' [name=' + index + ']').is(':radio')) {
        $(blockSel + ' [name=' + index + ']').closest('.form-group').append('<div class="field error-msg">' + value + '</div>');
      } else {
        $(blockSel + ' [name=' + index + ']').after('<div class="field error-msg">' + value + '</div>');
      }

      if (0 == highlightOnElements.length && ('undefined' === typeof dontShowGlobal || !dontShowGlobal)) {
        showGlobalError();
      }
    }
  });

}

function promoteBusinessFields() {
  // Group and put in front the business fields, if "I am a business" checkbox is ticked
  if (config_show_i_am_business) {
    $('#thecheckout-address-invoice .form-group.business-field').appendTo($('.business-fields-container'));
    $('#i_am_business').prop('disabled', false);
  }
}

function addVoucher() {
  // url - implicitly using current
  $.ajax({
    customPropAffectedBlocks: '#thecheckout-shipping, #thecheckout-payment, #thecheckout-cart-summary',
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "&ajax_request=1&action=addVoucher" +
      "&addDiscount=1" +
      "&discount_name=" + $('[name=discount_name]').val() +
      "&token=" + static_token,
    success: function (jsonData) {

      if (jsonData.hasErrors) {

        var errMsg = formatErrors(jsonData.cartErrors, 'span');
        $('.promo-code > .alert-danger > .js-error-text').html(errMsg);
        $('.promo-code > .alert-danger').slideDown();

      } else {
        updateCheckoutBlocks(jsonData, true, true, false);
      }


    }
  });
}

function removeVoucher(data) {
  $.ajax({
    customPropAffectedBlocks: '#thecheckout-shipping, #thecheckout-payment, #thecheckout-cart-summary',
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "&ajax_request=1&action=removeVoucher" +
      "&deleteDiscount=" + data["discountId"] +
      "&token=" + static_token,
    success: function (jsonData) {

      if (jsonData.hasErrors) {

        var errMsg = formatErrors(jsonData.cartErrors, 'span');
        $('.promo-code > .alert-danger > .js-error-text').html(errMsg);
        $('.promo-code > .alert-danger').slideDown();

      } else {
        updateCheckoutBlocks(jsonData, true, true, false);
      }


    }
  });
}

function confirmOrder(confirmButtonEl) {

  // typically, shipping modules can attach to tc_confirmOrderValidations, their respective
  // callbacks will be called here and should they not pass, order confirmation will be stopped
  var validationFailed = false;
  $.each(tc_confirmOrderValidations, function (validationName, validationCallback) {
    if (!validationCallback()) {
      if (debug_js_controller) {
        console.info('validation did not pass for: ' + validationName);
      }
      validationFailed = true;
    }
  });

  if (validationFailed) {
    showGlobalError();
    return;
  }

  modifyAccountAndAddress(confirmButtonEl, function (jsonData) {
    // callback method, called when account/address validation was successful

    // check selected carrier and payment method (additionally if they have some selection requirements)
    var selectedDeliveryEl = $('[name^=delivery_option]:checked');
    var selectedPaymentEl = $('[name=payment-option]:checked');
    var cartSummaryErrorVisible = $('#thecheckout-cart-summary > .error-msg:visible').length;

    if (!selectedDeliveryEl.length && !jsonData.isVirtualCart) {
      var shippingErrorMsg = $('#thecheckout-shipping > .inner-area > .error-msg');
      shippingErrorMsg.show();
      scrollToElement(shippingErrorMsg);
      showGlobalError();
      return;
    }

    if (!selectedPaymentEl.length) {
      var paymentErrorMsg = $('#thecheckout-payment > .inner-area .error-msg');
      paymentErrorMsg.show();
      scrollToElement(paymentErrorMsg);
      showGlobalError();
      return;
    }

    if (cartSummaryErrorVisible) {
      showGlobalError();
      return;
    }

    // Do we have any unchecked T&C?
    if ($('input[id^=conditions_to_approve]').not(':checked').length) {
      $('.terms-and-conditions > .error-msg').show();
      showGlobalError();
      return;
    }

    if (debug_js_controller) {
      console.info('delivery: ' + selectedDeliveryEl.val());
      console.info('payment: ' + selectedPaymentEl.attr('id'));
      console.info('*VALIDATION OK* Call payment method');
    }

    // Confirmation processing effect

    showConfirmButtonLoader(confirmButtonEl, true);
    // should there be an issue in Payment method form, handled by payment module only, rather safely set
    // timeout to hide loader after few seconds.
    setTimeout(function () {
      hideConfirmButtonLoader(confirmButtonEl)
    }, 3000);
    if (isMainConfirmationButton(confirmButtonEl)) {
      payment.confirm();
      // Maybe: for some payment modules, call confirmButtonEl.find('button').click();
    } else {
      // binary payment method, just hide save account overlay
      payment.hideSaveAccountOverlay();
    }


  });
}


function updateQuantityFromInput(el) {
  var data = el.data();
  qtyWanted = parseInt(el.val());
  qtyChange = qtyWanted - parseInt(data["qtyOrig"]);
  if (isNaN(qtyWanted) || isNaN(qtyChange)) {
    return;
  }
  data["qtyOrig"] = qtyWanted; // To allow rapid type-in changes in input field, e.g. modifying from single digit to 2-digit number
  if (qtyWanted < 1 || qtyChange == 0) {
    return;
  }
  el.prop('disabled', true);
  // url - implicitly using current
  $.ajax({
    customPropAffectedBlocks: '#thecheckout-shipping, #thecheckout-payment, #thecheckout-cart-summary',
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "&ajax_request=1&action=updateQuantity" +
      "&update=1" +
      "&qty=" + Math.abs(qtyChange) +
      "&op=" + ((qtyChange > 0) ? "up" : "down") +
      "&id_product=" + data["idProduct"] +
      "&id_product_attribute=" + data["idProductAttribute"] +
      "&id_customization=" + data["idCustomization"] +
      "&token=" + static_token,
    success: function (jsonData) {

      $('#thecheckout-cart-summary > .error-msg').remove();
      if (jsonData.hasErrors) {
        var errMsg = formatErrors(jsonData.cartErrors, 'span');
        $('#thecheckout-cart-summary').prepend('<div class="error-msg">' + errMsg + '</div>')
        $('#thecheckout-cart-summary > .error-msg').show();
      }

      updateCheckoutBlocks(jsonData, true, true, false);


    }
  });
}

function modifyAddressSelection(addressType) {
  // Send to server information about expanded/collapsed second address
  // And additionaly ID of selected address from combobox (for logged-in users)

  var addressesDropdown = $('[data-link-action=x-' + addressType + '-addresses]');
  var newAddressId = 0;
  if (addressesDropdown.length) {
    newAddressId = addressesDropdown.val();
  }


  $.ajax({
    customPropAffectedBlocks: '#thecheckout-shipping, #thecheckout-payment, #thecheckout-cart-summary, #thecheckout-address-' + addressType,
    url: insertUrlParam('modifyAddressSelection'),
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "&ajax_request=1&action=modifyAddressSelection" +
      "&addressType=" + addressType +
      "&addressId=" + newAddressId +
      "&invoiceVisible=" + $('#thecheckout-address-invoice form:visible').length +
      "&deliveryVisible=" + $('#thecheckout-address-delivery form:visible').length +
      "&token=" + $('#thecheckout-account [name=token]').val(),
    success: function (jsonData) {
      updateAddressBlock(addressType, jsonData.newAddressBlock, jsonData.newAddressSelection);
      updateCheckoutBlocks(jsonData, true, true, false);
    }
  });


  // Returned value - whole address block; simply replace, and also update other blocks - cart, shipping, payment

  // for non-logged in users, simply call modifyAccountAndAddress
  // modifyAccountAndAddress($('#thecheckout-address-' + addressType + ' [name=id_country]'));
}

function showConfirmButtonLoader(buttonEl, showLoadingAnimation) {
  if (showLoadingAnimation) {
    buttonEl.addClass('confirm-loading')
  }
  buttonEl.prop('disabled', true);
  if (debug_js_controller) {
    console.info('[thecheckout] show confirm loader at ' + new Date().getSeconds() + ':' + new Date().getMilliseconds());
  }
}

function hideConfirmButtonLoader(buttonEl) {
  buttonEl.removeClass('confirm-loading').prop('disabled', false);
  if (debug_js_controller) {
    console.info('[thecheckout] hide confirm loader at ' + new Date().getSeconds() + ':' + new Date().getMilliseconds());
  }
}

function isMainConfirmationButton(element) {
  return ("x-confirm-order" === element.data()["linkAction"]);
}

function isSaveAccountOverlayConfirmation(element) {
  return ("x-save-account-overlay" === element.data()["linkAction"]);
}

function checkEmail(element) {
  // url - implicitly using current
  $.ajax({
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "&ajax_request=1&action=checkEmail" +
      "&email=" + element.val() +
      "&token=" + $('#thecheckout-account [name=token]').val(),
    success: function (jsonData) {
      if (jsonData.hasErrors) {
        blockSel = '.account-fields';
        printContextErrors(blockSel, jsonData.errors, undefined, true);
      } else {
        updateAccountToken(jsonData.newToken);
        updateStaticToken(jsonData.newStaticToken);
        // if out of some reason, shipping/payment blocks are still disallowed, maybe entering email
        // would allow them (e.g. if forced-email-overlay was active)
        if ($('.dummy-block-container.disallowed').length) {
          getShippingAndPaymentBlocks();
        }
      }
    }
  });
}

function updateAccountToken(token) {
  if ("undefined" !== typeof token) {
    $('#thecheckout-account input[type=hidden][name=token]').val(token);
  }
}

function updateStaticToken(token) {
  static_token = token;
  if ('undefined' !== typeof prestashop) {
    prestashop.static_token = token;
  }
}

function serializeVisibleFields(formSelector) {
  return encodeURIComponent($(formSelector).find('input:visible, [type=hidden]').serialize());
}

function modifyAccountAndAddress(triggerElement, callback) {
  var triggerSection = triggerElement.closest('.checkout-block').attr('id');
  // url - implicitly using current

  if (isSaveAccountOverlayConfirmation(triggerElement)) {
    showConfirmButtonLoader($('[data-link-action=x-save-account-overlay]'), true);
  } else {
    // Add loader (2nd param) only when confirmation button was pressed by user; otherwise, just disable button for a moment
    showConfirmButtonLoader($('[data-link-action=x-confirm-order]'), isMainConfirmationButton(triggerElement));
  }

  $.ajax({
    customPropAffectedBlocks: '#thecheckout-shipping, #thecheckout-payment, #thecheckout-cart-summary',
    url: insertUrlParam('modifyAccountAndAddress'),
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "modifyAccountAndAddress&ajax_request=1&action=modifyAccountAndAddress&trigger=" + triggerSection +
      "&account=" + serializeVisibleFields('form.account-fields') +
      "&invoice=" + encodeURIComponent($('#thecheckout-address-invoice form :visible').serialize()) +
      "&delivery=" + encodeURIComponent($('#thecheckout-address-delivery form :visible').serialize()) +
      "&passwordVisible=" + $('#thecheckout-account input[name=password]:visible').length +
      "&passwordRequired=" + $('#thecheckout-account input[name=create-account]:checked').length +
      "&invoiceVisible=" + $('#thecheckout-address-invoice form:visible').length +
      "&deliveryVisible=" + $('#thecheckout-address-delivery form:visible').length +
      "&token=" + $('#thecheckout-account [name=token]').val(),
    success: function (jsonData) {

      var noErrors = true;
      // We can't clean all errors here, e.g. if we're updating delivery address only and have errors in invoice
      // this would clean also invoice errors (which we don't wont unless we update invoice address too)

      if ('undefined' !== typeof jsonData.customerSignInArea && 'undefined' !== typeof jsonData.customerSignInArea.staticCustomerInfo) {
        $('#static-customer-info-container').replaceWith(jsonData.customerSignInArea.staticCustomerInfo);
      }

      // Go through account, invoice and delivery errors, show them all
      if ("undefined" !== typeof jsonData.account && null !== jsonData.account) {
        blockSel = '.account-fields';

        printContextErrors(blockSel, jsonData.account.errors);

        if (jsonData.account.hasErrors) {
          if (debug_js_controller) {
            var errMsg = formatErrors(jsonData.account.errors, triggerElement);
            console.info('modifyAccountAndAddress: account has errros');
            console.info(errMsg);
          }
          noErrors = false;
        } else {
          // Update token only when customer account ID or password is changed
          if (debug_js_controller) {
            console.info('account created, customerId=' + jsonData.account.customerId);
            console.info('updating token from: ' + $('#thecheckout-account input[type=hidden][name=token]').val() + ', to: ' + jsonData.account.newToken);
            console.info('isGuest?' + jsonData.account.isGuest);
            // TODO: if isGuest == false, disable email field; or allow update email in controller?
          }

          // Disable email field and hide password when somebody logged in
          if (!jsonData.account.isGuest) {
            $('.form-group.password, .form-group.email, #thecheckout-login-form, #create_account').hide();

          }

          if ('undefined' !== typeof jsonData.customerSignInArea.displayNav2) {
            var userInfoEl = null;
            if ($('#_desktop_user_info').length) {
              userInfoEl = $('#_desktop_user_info');
            } else if ($('#header .user-info').length) {
              userInfoEl = $('#header .user-info');
            }
            if (null !== userInfoEl) {
              userInfoEl.replaceWith(jsonData.customerSignInArea.displayNav2);
            }
          }

          updateAccountToken(jsonData.account.newToken);
          updateStaticToken(jsonData.account.newStaticToken);

        }
      }// End of jsonData.account handling

      if ("undefined" !== typeof jsonData.invoice && null !== jsonData.invoice) {
        blockSel = '#thecheckout-address-invoice';
        printContextErrors(blockSel, jsonData.invoice.errors, triggerElement);

        if (jsonData.invoice.hasErrors) {
          if (debug_js_controller) {
            var errMsg = formatErrors(jsonData.invoice.errors);
            console.info('modifyAccountAndAddress: invoice has errros');
            console.info(errMsg);
          }
          noErrors = false;
        }
      }

      if ("undefined" !== typeof jsonData.delivery && null !== jsonData.delivery) {
        blockSel = '#thecheckout-address-delivery';
        printContextErrors(blockSel, jsonData.delivery.errors, triggerElement);

        if (jsonData.delivery.hasErrors) {
          if (debug_js_controller) {
            var errMsg = formatErrors(jsonData.delivery.errors);
            console.info('modifyAccountAndAddress: delivery has errros');
            console.info(errMsg);
          }
          noErrors = false;
        }
      }

      // Handle states and refresh blocks regardless of errors status
      if ("thecheckout-address-invoice" === triggerSection || "thecheckout-address-delivery" === triggerSection) {
        var addressType = triggerSection.substring("thecheckout-address-".length);
        if ('undefined' !== typeof jsonData[addressType].states) {
          handleStates($('[id=' + triggerSection + '] [name=id_state]'), jsonData[addressType].states);
        }
        if ('undefined' !== typeof jsonData[addressType].needZipCode) {
          handlePostcode($('[id=' + triggerSection + '] [name=postcode]'), jsonData[addressType].needZipCode);
        }
      }

      updateCheckoutBlocks(jsonData, true, true, false);

      hideConfirmButtonLoader($('[data-link-action=x-confirm-order]'));
      hideConfirmButtonLoader($('[data-link-action=x-save-account-overlay]'));

      if (noErrors && "function" === typeof callback) {
        callback(jsonData);
      }

    }
  });
}

function signedInUpdateForm() {
  $('[data-link-action=x-sign-in], .forgot-password').hide();
  $('.successful-login.hidden').show();
  // simply reload the checkout page with new context; take care of cart/checkout redirection, do not display
  // cart summary again!
  window.location.reload();
}

function signIn() {
  // recover from (possible) previous login attempts
  $('#errors-login-form').slideUp();
  $('[data-link-action=x-sign-in]').prop('disabled', true).css('cursor', 'wait');

  // url - implicitly using current
  $.ajax({
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "&ajax_request=1&action=signIn&" +
      $('#login-form').serialize() +
      "&token=" + $('#thecheckout-account [name=token]').val(),
    success: function (jsonData) {

      $('[data-link-action=x-sign-in]').prop('disabled', false).css('cursor', 'pointer');

      if (jsonData.hasErrors) {

        var errMsg = formatErrors(jsonData.errors);

        $('#errors-login-form').html(errMsg).slideDown();

      } else {
        signedInUpdateForm();
      }

    }
  });
}

function deleteFromCart(data) {
  // url - implicitly using current
  $.ajax({
    customPropAffectedBlocks: '#thecheckout-shipping, #thecheckout-payment, #thecheckout-cart-summary',
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "&ajax_request=1&action=deleteFromCart" +
      "&delete=1" +
      "&id_product=" + data["idProduct"] +
      "&id_product_attribute=" + data["idProductAttribute"] +
      "&id_customization=" + data["idCustomization"] +
      "&token=" + static_token,
    success: function (jsonData) {

      updateCheckoutBlocks(jsonData, true, true, false);

    }
  });
}

// Fill in states to combobox after address change/update
function handleStates(selectEl, states) {

  var oldVal = selectEl.val();
  //var shallResetPointer = selectEl.find('option:selected').index() > states.length;
  selectEl.children('option:not(:first)').remove();

  $.each(states, function (i, item) {
    if ("1" === item.active) {
      $(selectEl).append($('<option>', {
        value: item.id_state,
        text: item.name
      }));
    }
  });

  if (selectEl.find('option[value=' + oldVal + ']').length) {
    selectEl.val(oldVal);
  } else {
    selectEl.val(null);
  }


  if (states.length > 0) {
    selectEl.closest('.form-group').show();
  } else {
    selectEl.closest('.form-group').hide();
  }
}

// Show/hide postcode input field based on country selected
function handlePostcode(postcodeEl, needZipCode) {
  if (needZipCode) {
    postcodeEl.closest('.form-group').show();
  } else {
    postcodeEl.closest('.form-group').hide();
  }
}

function parseShippingMethods(shippingModulesList, html) {

  var parsers = {};
  var doParse = false;
  $.each(shippingModulesList, function (moduleName, deliveryOptionId) {
    if ("undefined" !== typeof checkoutShippingParser[moduleName]) {
      parsers[moduleName] = checkoutShippingParser[moduleName];

      if (
        "undefined" !== typeof parsers[moduleName].init_once ||
        "undefined" !== typeof parsers[moduleName].delivery_option ||
        "undefined" !== typeof parsers[moduleName].extra_content ||
        "undefined" !== typeof parsers[moduleName].all_hooks_content) {
        doParse = true;
      }

    }
  });

  if (doParse) {
    var parsed = $('<div id="shipping-parser-wrapper">' + html + '</div>');

    $.each(parsers, function (moduleName, parser) {

      // Call once per payment module
      if ("undefined" !== typeof parser.init_once) {
        parser.init_once($('.delivery-option.' + moduleName + ', .carrier-extra-content.' + moduleName, parsed));
      }

      // Call once per payment option (payment module may have multiple options)
      $('.delivery-option.' + moduleName, parsed).each(function (i, containerSelector) {
        if ("undefined" !== typeof parser['delivery_option']) {
          parser['delivery_option']($(containerSelector));
        }
      });
      $('.carrier-extra-content.' + moduleName, parsed).each(function (i, containerSelector) {
        if ("undefined" !== typeof parser['extra_content']) {
          parser['extra_content']($(containerSelector));
        }
      });

      if ("undefined" !== typeof parser.all_hooks_content) {
        parser.all_hooks_content($('>*:last-child', parsed));
      }

    });
    html = parsed.html();
  }

  return html;
}


function parsePaymentMethods(paymentModulesList, html, triggerElementName) {

  var parsers = {};
  var doParse = false;
  $.each(paymentModulesList, function (key, moduleName) {
    if ("undefined" !== typeof checkoutPaymentParser[moduleName]) {
      parsers[moduleName] = checkoutPaymentParser[moduleName];

      if (
        "undefined" !== typeof parsers[moduleName].init_once ||
        "undefined" !== typeof parsers[moduleName].container ||
        "undefined" !== typeof parsers[moduleName].additionalInformation ||
        "undefined" !== typeof parsers[moduleName].form ||
        "undefined" !== typeof parsers[moduleName].all_hooks_content) {
        doParse = true;
      }

    }
  });

  if (doParse) {
    var parsed = $('<div id="payments-parser-wrapper">' + html + '</div>')

    $.each(parsers, function (moduleName, parser) {

      // Call once per payment module
      if ("undefined" !== typeof parser.init_once) {
        parser.init_once($('.tc-main-title.' + moduleName, parsed), triggerElementName);
      }

      // Call once per payment option (payment module may have multiple options)
      $('.tc-main-title.' + moduleName + ' .payment-option', parsed).each(function (i, containerSelector) {

        var optId = $(containerSelector).attr('id').slice(0, -10); // remove '-container' suffix

        // we need to prepare 3 selectors: container, additionalInformation, form
        var selectors = {
          container: $(containerSelector),
          additionalInformation: $(containerSelector).nextAll('[id*=' + optId + '-additional-information]'),
          form: $(containerSelector).nextAll('[id*=' + optId + '-form]')
        };

        $.each(selectors, function (sectionName, element) {
          if ("undefined" !== typeof parser[sectionName]) {
            parser[sectionName](element, triggerElementName);
          }
        });

        if ("undefined" !== typeof parser.all_hooks_content) {
          parser.all_hooks_content($('>*:last-child', parsed));
        }
      });
    });
    html = parsed.html();
  }


  return html;
}

var shippingBlockChecksum = 0;
var paymentBlockChecksum = 0;
var cartSummaryBlockChecksum = 0;

var shippingBlockElement = '';
var paymentBlockElement = '';
var cartSummaryBlockElement = '';
var invoiceAddressBlockElement = '';
var deliveryAddressBlockElement = '';

var deliveryOptionSelector = '[type=radio][name^=delivery_option]:checked';

function updateHtmlBlock(el, html) {
  el.html(html);
  if (debug_js_controller) {
    el.parent().addClass('debug-flash');
    setTimeout(function () {
      el.parent().removeClass('debug-flash');
    }, 3000);
  }
}

function updateShippingBlock(shippingModulesList, html, checksum, triggerElementName) {
  if ('undefined' !== html && null !== html && shippingBlockChecksum != checksum) {
    html = parseShippingMethods(shippingModulesList, html);
    updateHtmlBlock(shippingBlockElement, html);
    shippingBlockChecksum = checksum;
    // Some shipping modules are not extra carriers (in modules list), so we cannot parse
    // them based on their name and thus need general trick to trigger their JS methods.
    // E.g. packzkomaty (sensbitpaczkomatymap) needs to trigger radio button change in order
    // to display list of pickup points; Chronopost and Mondial relay need it as well
    if ($(deliveryOptionSelector).length && !payment.isConfirmationTrigger(triggerElementName)) {
      $(deliveryOptionSelector).prop('checked', false).trigger('click');
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
  //return value says if shipping method's 'setDeliveryMethod' might have been called
}

function selectPaymentOption(optionId, paymentFee) {

  // url - implicitly using current
  $.ajax({
    customPropAffectedBlocks: '#thecheckout-cart-summary',
    url: insertUrlParam('selectPaymentOption'),
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "optionId=" + optionId + "&payment_fee=" + paymentFee + "&ajax_request=1&action=selectPaymentOption" + "&token=" + static_token,
    success: function (jsonData) {

      updateCartSummaryBlock(jsonData.cartSummaryBlock, jsonData.cartSummaryBlockChecksum);

    }
  });
}

function updatePaymentBlock(paymentModulesList, html, checksum, triggerElementName) {
  if ('undefined' !== html && null !== html && (paymentBlockChecksum != checksum || 'thecheckout-confirm' == triggerElementName)) {

    // Temporarily store: a/ selected payment method; b/ filled-in fields

    // get selected options prior to refresh; firstly try from data-module-name, if unavailable, try element id
    var selectedOption = payment.getSelectedOptionModuleName();
    if ("undefined" !== typeof selectedOption && "" !== selectedOption) {
      selectedOption = '[data-module-name=' + selectedOption + ']'; // select by module-name
    } else {
      selectedOption = payment.getSelectedOption();
      if ("undefined" !== typeof selectedOption && "" !== selectedOption) {
        selectedOption = '#' + payment.getSelectedOption(); // select by ID
      } else {
        selectedOption = "#none";
      }

    }

    // save payment form text input fields and select boxes, so we can restore them after hook update
    var payment_fields_values = {};
    paymentBlockElement.find('input[type=text], select').each(function () {
      if ("undefined" !== typeof $(this).attr('id')) {
        payment_fields_values['[id=' + $(this).attr('id') + ']'] = $(this).val();
      } else if ("undefined" !== typeof $(this).attr('name')) {
        payment_fields_values['[name=' + $(this).attr('name') + ']'] = $(this).val();
      }
    });

    // Store iframe payment forms, if they are already pre-filled and do not need to reload when user data changes
    // if ($('#thecheckout-payment #stripe-payment-form').length) {
    //   $('#payment_forms_persistence #stripe-payment-form').remove();
    //   $('#thecheckout-payment #stripe-payment-form').appendTo($('#payment_forms_persistence'));
    // }

    html = parsePaymentMethods(paymentModulesList, html, triggerElementName);
    updateHtmlBlock(paymentBlockElement, html);
    paymentBlockChecksum = checksum;

    // restore payment for input and select fields values
    $.each(payment_fields_values, function (index, value) {
      $(index).val(value);
    });
  }
  // Reinit payments always, as there might have been no change in markup but we still need to update
  // COD amount in shopping cart
  payment.init(selectedOption, selectPaymentOption); // from file payment.js
}

function updateCartSummaryBlock(html, checksum) {
  if ('undefined' !== html && null !== html && cartSummaryBlockChecksum != checksum) {

    // We try to store either focused or disabled input element (i.e. user was just making modifications there)
    // Later on, we'll try to re-focus.
    var activeEl = $(document.activeElement);
    var el = activeEl;
    if (el.is('.cart-line-product-quantity-up') || el.is('.cart-line-product-quantity-down')) {
      // Active element could be quantity up/down link
      var qtyControl = el.parent().data('qty-control');
      var activeQtyButtonCls = el.attr('class');
    } else if (el.is('input.cart-line-product-quantity')) {
      var qtyControl = el.parent().data('qty-control');
    } else {
      el = $('input.cart-line-product-quantity:disabled');
      var qtyControl = el.parent().data('qty-control');
    }

    updateHtmlBlock(cartSummaryBlockElement, html);

    // Restore focused input field, if any
    if ('undefined' !== typeof activeQtyButtonCls) {
      // Active element could be quantity up/down link
      $('[data-qty-control="' + qtyControl + '"] .' + activeQtyButtonCls).focus();
    } else {
      // or, input field
      $('[data-qty-control="' + qtyControl + '"] input').putCursorAtEnd().focus();
    }

    cartSummaryBlockChecksum = checksum;
  }
}

function updateAddressBlock(addressType, html, htmlAddressDropdown) {
  if ('undefined' !== html && null !== html) {
    if ("invoice" === addressType) {
      updateHtmlBlock(invoiceAddressBlockElement, html);

    } else {
      updateHtmlBlock(deliveryAddressBlockElement, html);
    }
  }
  if ('undefined' !== htmlAddressDropdown && null !== htmlAddressDropdown) {
    if ("invoice" === addressType) {
      deliveryAddressBlockElement.find('.customer-addresses').replaceWith(htmlAddressDropdown);
    } else {
      invoiceAddressBlockElement.find('.customer-addresses').replaceWith(htmlAddressDropdown);
    }
  }

  promoteBusinessFields();
}

function updateCheckoutBlocks(jsonData, updateSummary, updateShipping, updatePayment) {
  if ("undefined" !== typeof jsonData.emptyCart && jsonData.emptyCart === true) {
    $('body').addClass('is-empty-cart');
    // if ("undefined" !== typeof prestashop && "undefined" !== typeof prestashop.urls) {
    //   location.href = prestashop.urls.base_url;
    // } else {
    //   location.href = 'index.php';
    // }
  }
  if ("undefined" !== typeof jsonData.isVirtualCart && jsonData.isVirtualCart === true) {
    $('body').addClass('is-virtual-cart');
  } else {
    $('body').removeClass('is-virtual-cart');
  }

  if ("undefined" !== typeof jsonData.minimalPurchaseError && jsonData.minimalPurchaseError === true) {
    $('#confirm_order .minimal-purchase-error-msg').html(jsonData.minimalPurchaseMsg);
    $('#confirm_order').addClass('minimal-purchase-error');
  } else {
    $('#confirm_order').removeClass('minimal-purchase-error');
  }
  var shippingBlockUpdated = false;

  if ('undefined' !== typeof updateSummary && updateSummary) {
    updateCartSummaryBlock(jsonData.cartSummaryBlock, jsonData.cartSummaryBlockChecksum);
  }
  if ('undefined' !== typeof updateShipping && updateShipping) {
    shippingBlockUpdated = updateShippingBlock(jsonData.externalShippingModules, jsonData.shippingBlock, jsonData.shippingBlockChecksum, jsonData.triggerElementName);
  }
  // When shipping block is updated, it triggers setDeliveryMethod which will re-update payment methods
  // one more time; so it's not necessary to updatePayments initially, only when shipping block did not update
  if (('undefined' !== typeof updatePayment && updatePayment) || !shippingBlockUpdated) {
    updatePaymentBlock(jsonData.paymentMethodsList, jsonData.paymentBlock, jsonData.paymentBlockChecksum, jsonData.triggerElementName);
  }
}

function getShippingAndPaymentBlocks() {
  // url - implicitly using current
  $.ajax({
    customPropAffectedBlocks: '#thecheckout-shipping, #thecheckout-payment, #thecheckout-cart-summary',
    type: 'POST',
    cache: false,
    dataType: "json",
    data: "&ajax_request=1&action=getShippingAndPaymentBlocks" + "&token=" + static_token,
    success: function (jsonData) {

      updateCheckoutBlocks(jsonData, true, true, false);

    }
  });
}

function toggleGiftMessage() {
  if ($('#gift.in').length) {
    $('#gift').slideUp('fast', function () {
      $(this).removeClass('in').removeClass('show');
    });
  } else {
    $('#gift').slideDown('fast', function () {
      $(this).addClass('in show')
    });
  }
}

function selectDeliveryOption(deliveryForm) {

  var selectedDeliveryOptionExtra = $(deliveryOptionSelector).closest('.delivery-option-row').next('.carrier-extra-content');
  $('.carrier-extra-content').not(selectedDeliveryOptionExtra).hide();
  if (selectedDeliveryOptionExtra.height()) {
    selectedDeliveryOptionExtra.slideDown();
  }

  // url - implicitly using current
  $.ajax({
    customPropAffectedBlocks: '#thecheckout-cart-summary, #thecheckout-payment',
    url: insertUrlParam('selectDeliveryOption'),
    type: 'POST',
    cache: false,
    dataType: "json",
    data: deliveryForm.serialize() + "&selectDeliveryOption&ajax_request=1&action=selectDeliveryOption" + "&token=" + static_token,
    success: function (jsonData) {

      $('#thecheckout-shipping .error-msg').hide();
      updateCheckoutBlocks(jsonData, true, false, true);
      checkAndHideGlobalError();

    }
  });
}

function setDeliveryMessage() {
  // url - implicitly using current
  $.ajax({
    type: 'POST',
    cache: false,
    dataType: "json",
    data: 'delivery_message=' + $('#delivery_message').val() + "&ajax_request=1&action=setDeliveryMessage" + "&token=" + static_token,
    success: function (jsonData) {
      // No action necessary, we just set it on backend to checkout_session
    }
  });
}
