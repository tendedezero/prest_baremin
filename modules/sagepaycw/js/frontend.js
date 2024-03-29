/**
 *  * You are allowed to use this API in your web application.
 *
 * Copyright (C) 2018 by customweb GmbH
 *
 * This program is licenced under the customweb software licence. With the
 * purchase or the installation of the software in your application you
 * accept the licence agreement. The allowed usage is outlined in the
 * customweb software licence which can be found under
 * http://www.sellxed.com/en/software-license-agreement
 *
 * Any modification or distribution is strictly forbidden. The license
 * grants you the installation in one application. For multiuse you will need
 * to purchase further licences at http://www.sellxed.com/shop.
 *
 * See the customweb software licence agreement for more details.
 *
 */
(function($) {
	var sagepaycwBuildHiddenFormFields = function(fields) {
		var output = '';
		for ( var key in fields) {
			if (fields.hasOwnProperty(key)) {
				output += '<input type="hidden" value="'
						+ fields[key].replace('"', '&quot;') + '" name="' + key
						+ '" />';
			}
		}
		return output;
	};

	var aliasSubmissionHandler = function(event) {
		var completeForm = $(event.target)
				.parents('.sagepaycw-payment-form');
		var completeFormId = completeForm.attr('id');

		$("#" + completeFormId).animate({
			opacity : 0.3,
			duration : 30,
		});

		$.ajax({
			type : 'POST',
			url : $('.sagepaycw-alias-pane').attr('data-ajax-action'),
			data : $('.sagepaycw-alias-pane').find(':input').serialize()
					+ '&ajaxAliasForm=true',
			success : function(response) {
				var newPane = $("#" + completeFormId, $(response));
				if (newPane.length > 0) {
					var newContent = newPane.html();
					$("#" + completeFormId).html(newContent);

					// Execute the JS to make sure any JS inside newContent is
					// executed
					$(response).each(function(k, e) {
						if (typeof e === 'object' && e.nodeName == 'SCRIPT') {
							jQuery.globalEval(e.innerHTML);
						}
					});
					attachEventHandlers();
				}

				$("#" + completeFormId).animate({
					opacity : 1,
					duration : 100,
				});
			},
		});

		return false;
	};

	var aliasChangedHandler = function(event) {
		aliasSubmissionHandler(event);
	};

	var aliasSubmitClickedHandler = function(event) {
		$(this).parents('.sagepaycw-alias-pane').append(
				'<input type="hidden" name="' + $(this).attr('name')
						+ '" value="' + $(this).val() + '" />');
		event.preventDefault();
		aliasSubmissionHandler(event);
	};

	var ajaxSubmissionHandler = function(event) {
		$(this).hide();
		var methodName = ajaxForm.attr('data-method-name');
		var callback = window['sagepaycw_ajax_submit_callback_'
				+ methodName];

		var validationCallback = window['cwValidateFields' + 'sagepaycw_'
				+ methodName + '_'];
		if (typeof validationCallback != 'undefined') {
			validationCallback(function(valid) {
				ajaxFormSagePayCw_ValidationSuccess(ajaxForm);
			}, ajaxFormSagePayCw_ValidationFailure);
			return;
		}
		ajaxFormSagePayCw_ValidationSuccess($(this));
		return;
	};

	var createTransactionSubmissionHandler = function(event) {
		if (window.sagepaycwAjaxRequestInProgress === true) {
			return false;
		}
		window.sagepaycwAjaxRequestInProgress = true;

		var validationCallback = window['cwValidateFields' + 'sagepaycw_'
				+ event.data.methodName + '_'];
		if (typeof validationCallback != 'undefined') {
			validationCallback(function(valid) {
				createTransactionSagePayCw_ValidationSuccess(
						event.data.form, event.data.ajaxUrl);
			}, createTransactionSagePayCw_ValidationFailure);
			return false;
		}
		createTransactionSagePayCw_ValidationSuccess(event.data.form,
				event.data.ajaxUrl);
		return false;
	};

	var attachEventHandlers = function() {
		$('*').off('.sagepaycw');

		// normal form submit
		// check if alias is selected
		// then call function

		$('.sagepaycw-alias-form').find("input[type='checkbox']").on(
				'change.sagepaycw', aliasChangedHandler);
		$('.sagepaycw-alias-form').find("select").on(
				'change.sagepaycw', aliasChangedHandler);
		$('.sagepaycw-alias-form').find("input[type='submit']").on(
				'click.sagepaycw', aliasSubmitClickedHandler);

		$('.sagepaycw-ajax-authorization-form').each(
				function() {
					var ajaxForm = $(this);
					ajaxForm.parents('.sagepaycw-payment-form form').on(
							'sagepaycw.send', ajaxSubmissionHandler);
				});

		$('.sagepaycw-create-transaction')
				.each(
						function() {
							var ajaxUrl = $(this).attr('data-ajax-url');
							var sendFormDataBack = $(this).attr(
									'data-send-form-back') == 'true' ? true
									: false;
							var form = $(this).children('form');
							var methodName = form.attr('data-method-name');

							var params = {
								'ajaxUrl' : ajaxUrl,
								'sendFormDataBack' : sendFormDataBack,
								'form' : form,
								'methodName' : methodName
							};

							form.on('send.sagepaycw', params,
									createTransactionSubmissionHandler);
						});

		$('.sagepaycw-standard-redirection').on('send.sagepaycw',
				function(e) {
					$(this)[0].originalSubmit();
				});

		$('.sagepaycw-error-message-inline').on(
				'load.sagepaycw',
				function(event) {
					forceCurrentPaymentMethod();
				});

		if ($('.sagepaycw-error-message-inline')[0]) {
			forceCurrentPaymentMethod();
		}

		SagePayCwRegisterSubmissionHandling();
	};
    
    var forceCurrentPaymentMethod = function() {
        var btnId = $('.sagepaycw-error-message-inline').first().parents('.sagepaycw-payment-form').find('button').last().attr('id')
				.substring(9);
        $('#' + btnId).click();
    }

	var ajaxFormSagePayCw_ValidationSuccess = function(ajaxForm) {
		var methodName = ajaxForm.attr('data-method-name');
		var callback = window['sagepaycw_ajax_submit_callback_'
				+ methodName];
		if (typeof callback == 'undefined') {
			alert("No Ajax callback found.");
		} else {
			callback(ajaxForm.serialize());
		}
		window.SagePayCwIsSubmissionRunning = false;
	}

	var ajaxFormSagePayCw_ValidationFailure = function(errors, valid) {
		alert(errors[Object.keys(errors)[0]]);
		window.SagePayCwIsSubmissionRunning = false;
	}

	var createTransactionSagePayCw_ValidationSuccess = function(form,
			ajaxUrl) {
		var data = $(form).find(':input').serializeArray();
		var fields = [];
		$(data).each(function(index, value) {
			fields[value.name] = value.value;
		});

		form.animate({
			opacity : 0.3,
			duration : 30,
		});
		$
				.ajax({
					type : 'POST',
					url : ajaxUrl,
					data : $(form).serialize(),
					success : function(response) {
						var error = response;
						try {
							var data = $.parseJSON(response);

							if (data.status == 'success') {
								var func = eval('[' + data.callback + ']')[0];
								func();
								return;
							} else {
								error = data.message;
							}
						} catch (e) {
							console.log(e);
						}

						form.animate({
							opacity : 1,
							duration : 100,
						});
						if ($('.sagepaycw-error-message-inline')[0]) {
							$('.sagepaycw-error-message-inline').html(
									error);
						} else {
							form
									.prepend("<div class='sagepaycw-error-message-inline alert alert-danger'>"
											+ error + "</div>");
						}
						window.sagepaycwAjaxRequestInProgress = false;
					},
				});

	}

	var createTransactionSagePayCw_ValidationFailure = function(
			errors, valid) {
		alert(errors[Object.keys(errors)[0]]);
		window.sagepaycwAjaxRequestInProgress = false;
		window.SagePayCwIsSubmissionRunning = false;
	}

	var SagePayCwRegisterSubmissionHandling = function() {
		$('.sagepaycw-payment-form form').each(function() {
			this.originalSubmit = this.submit;

			this.submit = function(evt) {
				if (window.SagePayCwIsSubmissionRunning) {
					return;
				}
				window.SagePayCwIsSubmissionRunning = true;
				$(this).trigger('send.sagepaycw');
			}
		});
	}

	$(document).ready(function() {
		// Make JS required section visible
		$('.sagepaycw-javascript-required').show();

		attachEventHandlers();
		
		prestashop.on('steco_event_updated', function(){
		    attachEventHandlers();
		})
	});

}(jQuery));