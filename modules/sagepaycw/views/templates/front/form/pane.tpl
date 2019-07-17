{if $createTransaction}
	{if $sendFromDataBack}
		<div class="sagepaycw-payment-form sagepaycw-create-transaction" data-ajax-url="{$ajaxUrl}" data-send-form-back="true" id="sagepaycw-{$paymentMachineName}-payment-form">
	{else}
		<div class="sagepaycw-payment-form sagepaycw-create-transaction" data-ajax-url="{$ajaxUrl}" data-send-form-back="false" id="sagepaycw-{$paymentMachineName}-payment-form">
	{/if}
{else}
	<div class="sagepaycw-payment-form" id="sagepaycw-{$paymentMachineName}-payment-form">
{/if}
	<p class="payment-method-name" style="background: url({$paymentLogo}) 0px 0px no-repeat;">{$paymentMethodName}</p>
	<p class="payment-method-description">{$paymentMethodDescription}</p>

	{if isset($error_message) && !empty($error_message)}
		<p class="sagepaycw-error-message-inline payment-error alert alert-danger">
			{$error_message}
		</p>
	{/if}

	{if isset($ajaxScriptUrl)}
		<script src="{$ajaxScriptUrl}"></script>
	{/if}

	{if isset($ajaxSubmitCallback)}
		<script type="text/javascript">
			var sagepaycw_ajax_submit_callback_{$paymentMachineName} = {$ajaxSubmitCallback};
		</script>
	{/if}
	
	{if isset($ajaxScriptUrl)}
		<form class="sagepaycw-ajax-authorization-form form-horizontal" data-method-name="{$paymentMachineName}">
	{elseif isset($formActionUrl)}
		<form action="{$formActionUrl}" method="POST" class="form-horizontal {if isset($isServerAuthorization)} sagepaycw-standard-redirection{/if}" data-method-name="{$paymentMachineName}">
	{elseif isset($ajaxPendingOrderSubmit) && $ajaxPendingOrderSubmit}
		<form action="#" class="form-horizontal" data-method-name="{$paymentMachineName}">
	{/if}
		
	{if isset($aliasForm)}
		{$aliasForm nofilter}
	{/if}
	
	{if isset($visibleFormFields)}
		<fieldset>
			{$visibleFormFields nofilter}
			{if $isAnyFieldMandatory}
				<p><em>*</em> {lcw s='Required Fields' mod='sagepaycw'}</p>
			{/if}
		</fieldset>
	{/if}

	{if isset($hiddenFields)}
		{$hiddenFields nofilter}
	{/if}
	
	{if isset($additionalOutput)}
		{$additionalOutput nofilter}
	{/if}
	
	{if isset($ajaxScriptUrl)}
		</form>
	{/if}

	{if isset($formActionUrl) || (isset($ajaxPendingOrderSubmit) && $ajaxPendingOrderSubmit)}
		</form>
	{/if}
</div>
{if isset($jsFileUrl)}
	<script type="text/javascript" src="{$jsFileUrl}"></script>
{/if}