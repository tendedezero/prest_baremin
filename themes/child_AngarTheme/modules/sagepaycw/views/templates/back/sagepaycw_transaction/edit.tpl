<div id="order_toolbar" class="toolbar-placeholder">
	<div class="toolbarBox toolbarHead">
		<div class="pageTitle">
			<h3>
				<span id="current_obj" style="font-weight: normal;"> 
					<span class="breadcrumb item-0 ">
						{lcw s='Sage Pay Transactions' mod='sagepaycw'}
						<img alt=">" style="margin-right:5px" src="../img/admin/separator_breadcrumb.png">
					</span>
					<span class="breadcrumb item-2 ">{lcw s='View' mod='sagepaycw'}</span>
				</span>
			</h3>
		</div>
	</div>
</div>

{if is_object($transactionObject)}		
<div class="box buttons" >
	
	{if $transaction->getTransactionObject()->isCapturePossible()}
		<a href="{$link->getAdminLink('AdminSagePayCwTransaction')|escape:'htmlall':'UTF-8'}&transactionId={$transaction->getTransactionId()}&action=t_capture" class="button btn btn-success">{lcw s='Capture' mod='sagepaycw'}</a>
	{/if}
	
	
	{if $transaction->getTransactionObject()->isCancelPossible()}
		<a href="{$link->getAdminLink('AdminSagePayCwTransaction')|escape:'htmlall':'UTF-8'}&transactionId={$transaction->getTransactionId()}&action=cancel" class="button btn btn-danger">{lcw s='Cancel' mod='sagepaycw'}</a>
	{/if}
	
	
	{if $transaction->getTransactionObject()->isRefundPossible()}
		<a href="{$link->getAdminLink('AdminSagePayCwTransaction')|escape:'htmlall':'UTF-8'}&transactionId={$transaction->getTransactionId()}&action=t_refund" class="button btn btn-danger">{lcw s='Refund' mod='sagepaycw'}</a>
	{/if}
	
</div>
<br />
{/if}

<table class="table table-striped table-condensed table-hover table-bordered">

	<tr>
		<th class="col-lg-3">{lcw  mod='sagepaycw' s='Transaction ID'}</th>
		<td>{$transaction->getTransactionId()}</td>
	</tr>
	<tr>
		<th class="col-lg-3">{lcw  mod='sagepaycw' s='Transaction Number'}</th>
		<td>{$transaction->getTransactionExternalId()}</td>
	</tr>
	<tr>
		<th class="col-lg-3">{lcw  mod='sagepaycw' s='Authorization Status'}</th>
		<td>{$transaction->getAuthorizationStatus()}</td>
	</tr>
	{if !empty({$transaction->getOrderId()})}
		<tr>
			<th class="col-lg-3">{lcw  mod='sagepaycw' s='Order ID'}</th>
			<td>{$transaction->getOrderId()}
				<a href="{$link->getAdminLink('AdminOrders')|escape:'htmlall':'UTF-8'}&id_order={$transaction->getOrderId()}&vieworder" class="button">{lcw s='View' mod='sagepaycw'}</a>
			
			</td>
		</tr>
	{/if}
	<tr>
		<th class="col-lg-3">{lcw  mod='sagepaycw' s='Created On'}</th>
		<td>{$transaction->getCreatedOn()->format("Y-m-d H:i:s")}</td>
	</tr>
	<tr>
		<th class="col-lg-3">{lcw  mod='sagepaycw' s='Updated On'}</th>
		<td>{$transaction->getUpdatedOn()->format("Y-m-d H:i:s")}</td>
	</tr>
	<tr>
		<th class="col-lg-3">{lcw  mod='sagepaycw' s='Customer ID'}</th>
		<td>{$transaction->getCustomerId()}</td>
	</tr>
	<tr>
		<th class="col-lg-3">{lcw  mod='sagepaycw' s='Payment ID'}</th>
		<td>{$transaction->getPaymentId()}</td>
	</tr>
	{if is_object($transaction->getTransactionObject())}
		{foreach from=$transactionObject->getTransactionLabels()  item=label}	
			<tr>
				<th>{$label.label}
					{if isset($label.description)}
					<i data-toggle="popover" data-trigger="hover" data-placement="bottom"
					title="" data-content="{$label['description']}"
					class="glyphicon glyphicon-question-sign" data-original-title="{$label['label']}"></i>
					{/if}
				</th>
				<td>
					{$label['value']|escape:'htmlall'}
				</td>
			</tr>
		{/foreach}
		
	{/if}
	{if is_object($transaction->getTransactionObject()) && $transaction->getTransactionObject()->isAuthorized() && $transaction->getTransactionObject()->getPaymentInformation() != null}
		<tr>
			<th class="col-lg-3">{lcw  mod='sagepaycw' s='Payment Information'}</th>
			<td>{$transaction->getTransactionObject()->getPaymentInformation()}</td>
		</tr>
	{/if}
</table>
<br />

{if is_object($transactionObject) && count($transactionObject->getCaptures()) > 0}
<h3>{lcw  mod='sagepaycw' s='Captures for this transaction'}</h3>
<table class="table table-striped table-condensed table-hover table-bordered">
	<thead>
		<tr>
			<th>{lcw  mod='sagepaycw' s='Date'}</th>
			<th>{lcw  mod='sagepaycw' s='Amount'}</th>
			<th>{lcw  mod='sagepaycw' s='Status'}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$transactionObject->getCaptures() item=capture}
		<tr>
			<td>{$capture->getCaptureDate()->format("Y-m-d H:i:s")}</td>
			<td>{$capture->getAmount()}</td>
			<td>{$capture->getStatus()}</td>
		</tr>
		{/foreach}
	</tbody>
</table>
<br />
{/if}


{if is_object($transactionObject) && count($transactionObject->getRefunds()) > 0}
<h3>{lcw  mod='sagepaycw' s='Refunds for this transaction'}</h3>
<table class="table table-striped table-condensed table-hover table-bordered">
	<thead>
		<tr>
			<th>{lcw  mod='sagepaycw' s='Date'}</th>
			<th>{lcw  mod='sagepaycw' s='Amount'}</th>
			<th>{lcw  mod='sagepaycw' s='Status'}</th>
			<th> </th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$transactionObject->getRefunds() item=refund}
		<tr>
			<td>{$refund->getRefundedDate()->format("Y-m-d H:i:s")}</td>
			<td>{$refund->getAmount()}</td>
			<td>{$refund->getStatus()}</td>
		</tr>
		{/foreach}
	</tbody>
</table>
<br />
{/if}


{if is_object($transactionObject) && count($transactionObject->getHistoryItems()) > 0}
<h3>{lcw  mod='sagepaycw' s='Transactions History'}</h3>
<table class="table table-striped table-condensed table-hover table-bordered">
	<thead>
		<tr>
			<th>{lcw  mod='sagepaycw' s='Date'}</th>
			<th>{lcw  mod='sagepaycw' s='Action'}</th>
			<th>{lcw  mod='sagepaycw' s='Message'}</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$transactionObject->getHistoryItems() item=item}
		<tr>
			<td>{$item->getCreationDate()->format("Y-m-d H:i:s")}</td>
			<td>{$item->getActionPerformed()}</td>
			<td>{$item->getMessage()}</td>
		</tr>
		{/foreach}
	</tbody>
</table>
<br />
{/if}

{if is_object($transactionObject)}
<h3>{lcw  mod='sagepaycw' s='Customer Data'}</h3>
<table class="table table-striped table-condensed table-hover table-bordered">
	{assign var="context" value=$transactionObject->getTransactionContext()->getOrderContext()}
	<tr>
		<th class="col-lg-3">{lcw  mod='sagepaycw' s='Customer ID'}</th>
		<td>{$context->getCustomerId()}</td>
	</tr>
	<tr>
		<th class="col-lg-3">{lcw  mod='sagepaycw' s='Billing Address'}</th>
		<td>
			{$context->getBillingFirstName()|escape:'htmlall'} {$context->getBillingLastName()|escape:'htmlall'}<br />
			{if $context->getBillingCompanyName() !== null}
				{$context->getBillingCompanyName()|escape:'htmlall'}<br />
			{/if}
			{$context->getBillingStreet()|escape:'htmlall'}<br />
			{strtoupper($context->getBillingCountryIsoCode())}-{$context->getBillingPostCode()|escape:'htmlall'} {$context->getBillingCity()|escape:'htmlall'}<br />
			{if $context->getBillingDateOfBirth() !== null}
				{lcw  mod='sagepaycw' s='Birthday'}: {$context->getBillingDateOfBirth()->format("Y-m-d")}<br />
			{/if}
			{if $context->getBillingPhoneNumber() !== null}
				{lcw  mod='sagepaycw' s='Phone'}: {$context->getBillingPhoneNumber()|escape:'htmlall'}<br />
			{/if}
		</td>
	</tr>
	<tr>
		<th class="col-lg-3">{lcw  mod='sagepaycw' s='Shipping Address'}</th>
		<td>
			{$context->getShippingFirstName()|escape:'htmlall'} {$context->getShippingLastName()|escape:'htmlall'}<br />
			{if $context->getShippingCompanyName() !== null}
				{$context->getShippingCompanyName()|escape:'htmlall'}<br />
			{/if}
			{$context->getShippingStreet()|escape:'htmlall'}<br />
			{strtoupper($context->getShippingCountryIsoCode())}-{$context->getShippingPostCode()|escape:'htmlall'} {$context->getShippingCity()|escape:'htmlall'}<br />
			{if $context->getShippingDateOfBirth() !== null}
				{lcw  mod='sagepaycw' s='Birthday'}: {$context->getShippingDateOfBirth()->format("Y-m-d")}<br />
			{/if}
			{if $context->getShippingPhoneNumber() !== null}
				{lcw  mod='sagepaycw' s='Phone'}: {$context->getShippingPhoneNumber()|escape:'htmlall'}<br />
			{/if}
		</td>
	</tr>
</table>
<br />
<h3>{lcw  mod='sagepaycw' s='Products'}</h3>
<table class="table table-striped table-condensed table-hover table-bordered">
	<thead>
		<tr>
			<th>{lcw  mod='sagepaycw' s='Name'}</th>
			<th>{lcw  mod='sagepaycw' s='SKU'}</th>
			<th>{lcw  mod='sagepaycw' s='Quantity'}</th>
			<th>{lcw  mod='sagepaycw' s='Type'}</th>
			<th>{lcw  mod='sagepaycw' s='Tax Rate'}</th>
			<th>{lcw  mod='sagepaycw' s='Amount (excl. VAT)'}</th>
			<th>{lcw  mod='sagepaycw' s='Amount (inkl. VAT)'}</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$transactionObject->getTransactionContext()->getOrderContext()->getInvoiceItems() item=invoiceItem}
		<tr>
			<td>{$invoiceItem->getName()}</td>
			<td>{$invoiceItem->getSku()}</td>
			<td>{$invoiceItem->getQuantity()}</td>
			<td>{$invoiceItem->getType()}</td>
			<td>{$invoiceItem->getTaxRate()}%</td>
			<td>{$invoiceItem->getAmountExcludingTax()|round:2}</td>
			<td>{$invoiceItem->getAmountIncludingTax()|round:2}</td>
		</tr>
		{/foreach}
	</tbody>
</table>
<br />
{/if}