{capture name=path}{lcw s='Payment' mod='sagepaycw'}{/capture}

<h2>{lcw s='Payment Status: Payment is open' mod='sagepaycw'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

<div class="sagepaycw-timeout-message">
	<p>
		{lcw s='It seems as your order was successful. However we do not get any feedback from the payment processor. Please contact us to find out what is going on with your order.' mod='sagepaycw'}
	</p>
	<p>
		{lcw s='Please mention the following transaction id:' mod='sagepaycw'}
		<br />{$transactionExternalId}
	</p>
</div>

