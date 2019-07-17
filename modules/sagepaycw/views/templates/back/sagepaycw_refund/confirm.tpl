
<h2>{lcw s='Refund Transaction' mod='sagepaycw'}</h2>

<p>{lcw s='You are along the way to refund the order %s.' mod='sagepaycw' sprintf=$orderId} 
{lcw s='Do you want to send this order also the?' mod='sagepaycw'}</p>

<p>{lcw s='Amount to refund:' mod='sagepaycw'} {$refundAmount} {$transaction->getCurrencyCode()}</p>

{if !$transaction->isRefundClosable()}
	<p><strong>{lcw s='This is the last refund possible on this transaction. This payment method does not support any further refunds.' mod='sagepaycw'}</strong></p>
{/if}

<form action="{$targetUrl}" method="POST">
<p>
	{$hiddenFields}	
	<a class="button" href="{$backUrl}">{lcw s='Cancel' mod='sagepaycw'}</a>
	<input type="submit" class="button" name="submitSagePayCwRefundNormal" value="{lcw s='No' mod='sagepaycw'}" />
	<input type="submit" class="button" name="submitSagePayCwRefundAuto" value="{lcw s='Yes' mod='sagepaycw'}" />
</p>
</form>