{if isset($paymentMethodMessage) && !empty($paymentMethodMessage)}
	<p class="payment-method-message">{$paymentMethodMessage}</p>
{/if}

{if isset($paymentInformation) && !empty($paymentInformation)}
	<div class="sagepaycw-invoice-payment-information sagepaycw-payment-return-table" id="sagepaycw-invoice-payment-information">
		<h4>{$paymentInformationTitle}</h4>
		{$paymentInformation nofilter}
	</div>
{/if}