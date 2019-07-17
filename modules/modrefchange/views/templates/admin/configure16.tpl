{$conf}
<form id="module_form" class="defaultForm form-horizontal" action="{$request_uri}" method="post" enctype="multipart/form-data" novalidate="">
	<input type="hidden" name="{$submitName}" value="1">
	<div class="panel" id="fieldset_0">
		<div class="panel-heading">
			<i class="icon-cogs"></i>{l s='Order reference settings' mod='modrefchange'}
		</div>
		<div class="form-label">
			<p class="help-block">{l s='Please specify the settings for the order reference change' mod='modrefchange'}</p>
		</div>
		<div id="donatebut" class="col-lg-2 col-lg-offset-10">
			{l s='Please make a small donation' mod='modrefchange'}<br />{l s='if you love this module and want to support future development.' mod='modrefchange'}
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TZ5KZ2J6CN4YE" target="_blank">
				<img src="{$donatebut}" border="0" name="submit" alt="{l s='PayPal, the complete and safe way of paying online' mod='modrefchange'}" title="{l s='PayPal, the complete and safe way of paying online' mod='modrefchange'}">
				<img alt="" border="0" src="{$donatepix}" width="1" height="1">
			</a>
		</div>
		<div class="form-wrapper">
			<div class="form-group">
				<label class="control-label col-lg-3">{l s='Use Order ID' mod='modrefchange'}</label>
				<div class="col-lg-9 ">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="ref_orderid" id="ref_orderid_on" value="1" {if $ref_orderid}checked="checked"{/if}>
						<label for="ref_orderid_on">{l s='Yes'}</label>
						<input type="radio" name="ref_orderid" id="ref_orderid_off" value="0" {if !$ref_orderid}checked=""checked"{/if}>
						<label for="ref_orderid_off">{l s='No'}</label>
						<a class="slide-button btn"></a>
					</span>
					<p class="help-block">{l s='Use the Order ID instead of the random characters as Order reference' mod='modrefchange'}</p>
				</div>
			</div>
			<div class="form-group prefixnulo">
				<label class="control-label col-lg-3">{l s='Use Zeros to prefix Order ID' mod='modrefchange'}</label>
				<div class="col-lg-9 ">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="ref_prefixnulo" id="ref_prefixnulo_on" value="1" {if $ref_prefixnulo}checked="checked"{/if}>
						<label for="ref_prefixnulo_on">{l s='Yes'}</label>
						<input type="radio" name="ref_prefixnulo" id="ref_prefixnulo_off" value="0" {if !$ref_prefixnulo}checked="checked"{/if}>
						<label for="ref_prefixnulo_off">{l s='No'}</label>
						<a class="slide-button btn"></a>
					</span>
					<p class="help-block">{l s='Prefix the Order ID with zeros (e.g. \'000000001\', \'000000010\', \'00000000[ORDER_ID]\')' mod='modrefchange'}</p>
				</div>
			</div>
			<div class="form-group prefixnulnro {if !$ref_prefixnulo}hidden{/if}">
				<label class="control-label col-lg-3">{l s='Number of zeros to prefix Order ID' mod='modrefchange'}</label>
				<div class="col-lg-9 ">
					<div class="input-group col-lg-3">
						<input type="text" name="ref_prefixnulnro" id="ref_prefixnulnro" value="{$ref_prefixnulnro}" class="" size="10" >
						<span class="input-group-addon">{l s='Zeros' mod='modrefchange'}</span>
					</div>
					<p class="help-block col-lg-12">{l s='Number of zeros to use as padding. Must be between 1 and 10' mod='modrefchange'}</p>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3">{l s='Use Character(s) to prefix Order ID' mod='modrefchange'}</label>
				<div class="col-lg-9 ">
					<div class="input-group">
						<input type="text" name="ref_prefixsigno" id="ref_prefixsigno" value="{$ref_prefixsigno}" class="">
					</div>
					<p class="help-block">
						{l s='Prefix the Order ID with one or more characters (e.g. \'O1\', \'ORD_10\')' mod='modrefchange'}. 
						{l s='Leave empty to not use prefix.' mod='modrefchange'}. 
						{l s='You can also use date/time format (e.g. %Y) See ' mod='modrefchange'}
						<a href="www.php.net/manual/function.strftime.php" target="_blank">strftime</a> {l s='for format.' mod='modrefchange'}
					</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-lg-3">{l s='Use Cart ID' mod='modrefchange'}</label>
				<div class="col-lg-9 ">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="ref_cartid" id="ref_cartid_on" value="1" {if $ref_cartid}checked="checked"{/if}>
						<label for="ref_cartid_on">{l s='Yes'}</label>
						<input type="radio" name="ref_cartid" id="ref_cartid_off" value="0" {if !$ref_cartid}checked=""checked"{/if}>
						<label for="ref_cartid_off">{l s='No'}</label>
						<a class="slide-button btn"></a>
					</span>
					<p class="help-block">{l s='Use the Cart ID instead of the random characters as Order reference' mod='modrefchange'}</p>
				</div>
			</div>
			<div class="form-group prefixnulc">
				<label class="control-label col-lg-3">{l s='Use Zeros to prefix Cart ID' mod='modrefchange'}</label>
				<div class="col-lg-9 ">
					<span class="switch prestashop-switch fixed-width-lg">
						<input type="radio" name="ref_prefixnulc" id="ref_prefixnulc_on" value="1" {if $ref_prefixnulc}checked="checked"{/if}>
						<label for="ref_prefixnulc_on">{l s='Yes'}</label>
						<input type="radio" name="ref_prefixnulc" id="ref_prefixnulc_off" value="0" {if !$ref_prefixnulc}checked="checked"{/if}>
						<label for="ref_prefixnulc_off">{l s='No'}</label>
						<a class="slide-button btn"></a>
					</span>
					<p class="help-block">{l s='Prefix the Cart ID with zeros (e.g. \'000000001\', \'000000010\', \'00000000[CART_ID]\')' mod='modrefchange'}</p>
				</div>
			</div>
			<div class="form-group prefixnulnrc {if !$ref_prefixnulc}hidden{/if}">
				<label class="control-label col-lg-3">{l s='Number of zeros to prefix Cart ID' mod='modrefchange'}</label>
				<div class="col-lg-9 ">
					<div class="input-group col-lg-3">
						<input type="text" name="ref_prefixnulnrc" id="ref_prefixnulnrc" value="{$ref_prefixnulnrc}" class="" size="10" >
						<span class="input-group-addon">{l s='Zeros' mod='modrefchange'}</span>
					</div>
					<p class="help-block col-lg-12">{l s='Number of zeros to use as padding. Must be between 1 and 10' mod='modrefchange'}</p>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3">{l s='Use Character(s) to prefix Cart ID' mod='modrefchange'}</label>
				<div class="col-lg-9 ">
					<div class="input-group">
						<input type="text" name="ref_prefixsignc" id="ref_prefixsignc" value="{$ref_prefixsignc}" class="">
					</div>
					<p class="help-block">
						{l s='Prefix the Cart ID with one or more characters (e.g. \'O1\', \'CART_10\')' mod='modrefchange'}. 
						{l s='Leave empty to not use prefix.' mod='modrefchange'}. 
						{l s='You can also use date/time format (e.g. %Y) See ' mod='modrefchange'}
						<a href="www.php.net/manual/function.strftime.php" target="_blank">strftime</a> {l s='for format.' mod='modrefchange'}
					</p>
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-lg-3">{l s='Use Character(s) to prefix Order Reference' mod='modrefchange'}</label>
				<div class="col-lg-9 ">
					<div class="input-group">
						<input type="text" name="ref_prefixsign" id="ref_prefixsign" value="{$ref_prefixsign}" class="">
					</div>
					<p class="help-block">
						{l s='Prefix the Order Reference with one or more characters (e.g. \'O1\', \'REF_10\')' mod='modrefchange'}. 
						{l s='Leave empty to not use prefix.' mod='modrefchange'}. 
						{l s='You can also use date/time format (e.g. %Y) See ' mod='modrefchange'}
						<a href="www.php.net/manual/function.strftime.php" target="_blank">strftime</a> {l s='for format.' mod='modrefchange'}
					</p>
				</div>
			</div>
			{if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}
			<div class="inv_unhide">
				<div class="form-group">
					<label class="control-label col-lg-3">{l s='Use Order ID and/or Cart ID to change the invoice and delivery slip number' mod='modrefchange'}</label>
					<div class="col-lg-9 ">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="inv_unhide" id="inv_unhide_on" value="1">
							<label for="inv_unhide_on">{l s='Yes'}</label>
							<input type="radio" name="inv_unhide" id="inv_unhide_off" value="0" checked="checked">
							<label for="inv_unhide_off">{l s='No'}</label>
							<a class="slide-button btn"></a>
						</span>
					</div>
				</div>
			</div>
			{/if}
			<div class="inv_form {if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}hidden{/if}">
				<div class="form-group">
					<hr>
					<legend>{l s='Please specify the settings for the invoice and delivery slip change' mod='modrefchange'}.</legend>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3">{l s='Use Order ID' mod='modrefchange'}</label>
					<div class="col-lg-9 ">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="inv_orderid" id="inv_orderid_on" value="1" {if $inv_orderid}checked="checked"{/if}>
							<label for="inv_orderid_on">{l s='Yes'}</label>
							<input type="radio" name="inv_orderid" id="inv_orderid_off" value="0" {if !$inv_orderid}checked=""checked"{/if}>
							<label for="inv_orderid_off">{l s='No'}</label>
							<a class="slide-button btn"></a>
						</span>
						<p class="help-block">{l s='Use the Order ID instead of the invoice/deliveryslip sequence number as invoice or slip number.' mod='modrefchange'}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3">{l s='Use Cart ID' mod='modrefchange'}</label>
					<div class="col-lg-9 ">
						<span class="switch prestashop-switch fixed-width-lg">
							<input type="radio" name="inv_cartid" id="inv_cartid_on" value="1" {if $inv_cartid}checked="checked"{/if}>
							<label for="inv_cartid_on">{l s='Yes'}</label>
							<input type="radio" name="inv_cartid" id="inv_cartid_off" value="0" {if !$inv_cartid}checked=""checked"{/if}>
							<label for="inv_cartid_off">{l s='No'}</label>
							<a class="slide-button btn"></a>
						</span>
						<p class="help-block">{l s='Use the Cart ID instead of the invoice/deliveryslip sequence number as invoice or slip number.' mod='modrefchange'}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-3">{l s='Use Date and/or Time formatting to prefix invoice and delivery slip' mod='modrefchange'}</label>
					<div class="col-lg-9 ">
						<div class="input-group">
							<input type="text" name="inv_prefixsign" id="inv_prefixsign" value="{$inv_prefixsign}" class="">
						</div>
						<p class="help-block">
							{l s='Prefix the invoice and delivery slip with date/time format (e.g. %Y) See' mod='modrefchange'} <a href="www.php.net/manual/function.strftime.php" target="_blank">strftime</a> {l s='for format.' mod='modrefchange'} {l s='Leave empty to not use prefix' mod='modrefchange'}.
						</p>
					</div>
				</div>
			</div>
		</div><!-- /.form-wrapper -->
		<div class="panel-footer">
			<button type="submit" value="1" id="module_form_submit_btn" name="{$submitName}" class="btn btn-default pull-right">
				<i class="process-icon-save"></i> Opslaan
			</button>
		</div>
	</div>
</form>
<style>
div#donatebut {
    margin-top: -5%;
}
</style>
<script language="javascript">
	$( ".inv_unhide input[type='radio']" ).on( "click", function() {
		var val = $(".inv_unhide input[type='radio']:checked").val();
		if(val == 1)
			$('.inv_form').removeClass("hidden");
		else
			$('.inv_form').addClass("hidden");
	});
	$( ".prefixnulo input[type='radio']" ).on( "click", function() {
		var val = $(".prefixnulo input[type='radio']:checked").val();
		if(val == 1)
			$('.prefixnulnro').removeClass("hidden");
		else
			$('.prefixnulnro').addClass("hidden");
	});
	$( ".prefixnulc input[type='radio']" ).on( "click", function() {
		var val = $(".prefixnulc input[type='radio']:checked").val();
		if(val == 1)
			$('.prefixnulnrc').removeClass("hidden");
		else
			$('.prefixnulnrc').addClass("hidden");
	});
</script>