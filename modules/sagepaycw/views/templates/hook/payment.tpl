<div class="row">
	<div class="col-xs-12 col-md-6">
		{if isset($paymentPane)}
			<noscript>
				<p class="payment_module payment-method-list-sagepaycw redirect-view-sagepaycw">
					<a href="{$redirectionUrl}" title="{$paymentMethodName}" style="background: url({$paymentLogo}) 15px 25px no-repeat #fbfbfb;">
						{$paymentMethodName}
						<span class="payment-method-description">{$paymentMethodDescription}</span>
					</a>
				</p>	
			</noscript>
			<div class="sagepaycw-javascript-required">
				{$paymentPane nofilter}
			</div>
		{else}
			<p class="payment_module payment-method-list-sagepaycw redirect-view-sagepaycw">
				<a href="{$redirectionUrl}" title="{$paymentMethodName}" style="background: url({$paymentLogo}) 15px 25px no-repeat #fbfbfb;">
					{$paymentMethodName}
					<span class="payment-method-description">{$paymentMethodDescription}</span>
				</a>
			</p>	
		{/if}
	</div>
</div>
