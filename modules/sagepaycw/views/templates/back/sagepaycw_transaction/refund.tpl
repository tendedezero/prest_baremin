<div id="order_toolbar" class="toolbar-placeholder">
	<div class="toolbarBox toolbarHead">
		<div class="pageTitle">
			<h3>
				<span id="current_obj" style="font-weight: normal;"> 
					<span class="breadcrumb item-0 ">
						{lcw s='Sage Pay Transactions' mod='sagepaycw'}
						<img alt=">" style="margin-right:5px" src="../img/admin/separator_breadcrumb.png">
					</span>
					<span class="breadcrumb item-2 ">
						{lcw s='View' mod='sagepaycw'}
						<img alt=">" style="margin-right:5px" src="../img/admin/separator_breadcrumb.png">
					</span>
					<span class="breadcrumb item-2 ">
						{lcw s='Refund' mod='sagepaycw'}
					</span>
				</span>
			</h3>
		</div>
	</div>
</div>

<div class="buttons">
	<a href="{$link->getAdminLink('AdminSagePayCwTransaction')|escape:'htmlall':'UTF-8'}&transactionId={$transaction->getTransactionId()}&action=edit" class="button btn btn-default">{lcw s='Back' mod='sagepaycw'}</a>
</div>
<br />

{if isset($message)}
	<div class="alert">{$message}</div>
{/if}

{if $transaction->getTransactionObject()->isPartialRefundPossible()}
	<form method="POST" class="sagepaycw-line-item-grid" id="refund-form">
		<input type="hidden" id="sagepaycw-decimal-places" value="{Customweb_Util_Currency::getDecimalPlaces($transaction->getTransactionObject()->getCurrencyCode())}" />
		<input type="hidden" id="sagepaycw-currency-code" value="{strtoupper($transaction->getTransactionObject()->getCurrencyCode())}" />
		<table class="table table-striped table-condensed table-hover table-bordered">
			<thead>
				<tr>
					<th class="text-left">{lcw  mod='sagepaycw' s='Name'}</th>
					<th class="text-left">{lcw  mod='sagepaycw' s='SKU'}</th>
					<th class="text-left">{lcw  mod='sagepaycw' s='Type'}</th>
					<th class="text-left">{lcw  mod='sagepaycw' s='Tax Rate'}</th>
					<th class="text-right">{lcw  mod='sagepaycw' s='Quantity'}</th>
					<th class="text-right">{lcw  mod='sagepaycw' s='Total Amount (excl. Tax)'}</th>
					<th class="text-right">{lcw  mod='sagepaycw' s='Total Amount (incl. Tax)'}</th>
					</tr>
			</thead>
		
			<tbody>
			{foreach from=$transaction->getTransactionObject()->getNonRefundedLineItems() key=index item=item}
				{assign var="amountExcludingTax" value=$item->getAmountExcludingTax()|round:2}
				{assign var="amountIncludingTax" value=$item->getAmountIncludingTax()|round:2}
				{assign var="taxRate" value=$item->getTaxRate()|round:2}
				{if $item->getType() == Customweb_Payment_Authorization_IInvoiceItem::TYPE_DISCOUNT}
					{math assign="amountExcludingTax" equation="amount * -1" amount="$amountExcludingTax"}
					{math assign="amountIncludingTax" equation="amount * -1" amount="$amountIncludingTax"}
				{/if}
				
				<tr id="line-item-row-{$index}" class="line-item-row" data-line-item-index="{$index}" >
					<td class="text-left">{$item->getName()}</td>
					<td class="text-left">{$item->getSku()}</td>
					<td class="text-left">{$item->getType()}</td>
					<td class="text-left">{$taxRate} %<input type="hidden" class="form-control tax-rate" value="{$item->getTaxRate()}" /></td>
					<td class="text-right"><input type="text" class="line-item-quantity form-control" name="quantity[{$index}]" value="{$item->getQuantity()}" /></td>
					<td class="text-right"><input type="text" class="line-item-price-excluding form-control" name="price_excluding[{$index}]" value="{$amountExcludingTax}" /></td>
					<td class="text-right"><input type="text" class="line-item-price-including form-control" name="price_including[{$index}]" value="{$amountIncludingTax}" /></td>
				</tr>
			{/foreach}
			</tbody>
			<tfoot>
				<tr>
					<td colspan="6" class="text-right">{lcw  mod='sagepaycw' s='Total Refund Amount'}:</td>
					<td id="line-item-total" class="text-right">
					{$transaction->getTransactionObject()->getRefundableAmount()|round:2} 
					{$transaction->getTransactionObject()->getCurrencyCode()|strtoupper}
				</tr>
			</tfoot>
		</table>
		{if $transaction->getTransactionObject()->isRefundClosable()}
			<div class="closable-box">
				<label for="close-transaction">{lcw  mod='sagepaycw' s='Close transaction for further refunds'}</label>
				<input id="close-transaction" type="checkbox" name="close" value="on" />
			</div>
		{/if}
		
		<div class="text-right">
			<input type="submit" class="button btn btn-success" value="{lcw  mod='sagepaycw' s='Refund'}" />
		</div>
	</form>
{/if}

