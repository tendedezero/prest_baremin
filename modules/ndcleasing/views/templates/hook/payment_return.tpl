{if $status == 'ok'}
	<p>{l s='Your order on %s is complete.' sprintf=[$shop_name] d='Modules.ndcleasing.Shop'}
		<br /><br />
		{l s='Your payment must include:' d='Modules.ndcleasing.Shop'}
		<br /><br />- {l s='Payment amount.' d='Modules.ndcleasing.Shop'} <span class="price"><strong>{$total_to_pay}</strong></span>
		<br /><br />- {l s='Payable to the order of' d='Modules.ndcleasing.Shop'} <strong>{if $checkName}{$checkName}{else}___________{/if}</strong>
		{if !isset($reference)}
			<br /><br />- {l s='Do not forget to insert your order number #%d.' sprintf=[$id_order] d='Modules.ndcleasing.Shop'}
		{else}
			<br /><br />- {l s='Do not forget to insert your order reference %s.' sprintf=[$reference] d='Modules.ndcleasing.Shop'}
		{/if}
		<br /><br />{l s='An email has been sent to you with this information.' d='Modules.ndcleasing.Shop'}
		<br /><br /><strong>{l s='Your order will be sent as soon as we receive your payment.' d='Modules.ndcleasing.Shop'}</strong>
		<br /><br />{l s='For any questions or for further information, please contact our' d='Modules.ndcleasing.Shop'} <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='customer service department.' d='Modules.ndcleasing.Shop'}</a>.
	</p>
{else}
	<p class="warning">
		{l s='We have noticed that there is a problem with your order. If you think this is an error, you can contact our' d='Modules.ndcleasing.Shop'}
		<a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='customer service department.' d='Modules.ndcleasing.Shop'}</a>.
	</p>
{/if}
