<div class="sagepaycw-alias-pane sagepaycw-alias-form" data-ajax-action="{$aliasUrl}">
	{if isset($aliasTransactions) && count($aliasTransactions) > 0 && isset($selectedAlias) && !empty($selectedAlias) && $selectedAlias != 'new'}
		<div class="form-group">
			<label for="sagepaycw_alias" class="control-label col-sm-4">{lcw s='Use stored Card' mod='sagepaycw'}</label>
			<div class="col-sm-8">
				<select name="sagepaycw_alias" id="sagepaycw_alias" class="form-control">
					{foreach item=transaction from=$aliasTransactions}
						<option 
						{if isset($selectedAlias) && $selectedAlias == $transaction->getTransactionId()}
							selected="selected" 
						{/if}
						value="{$transaction->getTransactionId()}">{$transaction->getAliasForDisplay()}</option>
					{/foreach}
				</select>
			</div>
		</div>
	{/if}
	
	{if !isset($selectedAlias) || empty($selectedAlias) || $selectedAlias == 'new'}
		<div class="form-group">
			<div class="">
				<div class="checkbox">
					<label>
						<input type="hidden" name="sagepaycw_create_new_alias_present" value="active" />
						<input type="checkbox" name="sagepaycw_create_new_alias" value="on"
						{if $selectedAlias == 'new'} checked="checked" {/if}
						 /> {lcw s='Store card information' mod='sagepaycw'}
					</label>
				</div>
			</div>
		</div>
	{/if}
	
	<div class="form-group">
		
		{if isset($selectedAlias) && !empty($selectedAlias) && $selectedAlias != 'new'}
			<input type="submit" name="sagepaycw_alias_use_new_card" class="btn btn-default" value="{lcw s='Use new card' mod='sagepaycw'}" />
		{elseif isset($aliasTransactions) && count($aliasTransactions) > 0 && (!isset($selectedAlias) || empty($selectedAlias) || $selectedAlias == 'new')}
			<input type="submit" name="sagepaycw_alias_use_stored_card" class="btn btn-default" value="{lcw s='Use stored card' mod='sagepaycw'}" />
		{/if}
	</div>
</div>