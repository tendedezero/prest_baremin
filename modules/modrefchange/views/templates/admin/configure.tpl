{$conf}
<h2>{$displayname}</h2>
<form action="{$request_uri}" method="post" id="modrefchangeform">
	<fieldset>
		<legend><img src="../img/admin/cog.gif" alt="{l s='Order reference settings' mod='modrefchange'}" title="{l s='Order reference settings' mod='modrefchange'}" />{l s='Order reference settings' mod='modrefchange'}</legend>
		<div class="formlabel"><p>{l s='Please specify the settings for the order reference change' mod='modrefchange'}</p></div>
		<div id="donatebut">
			{l s='Please make a small donation' mod='modrefchange'}<br />{l s='if you love this module and want to support future development.' mod='modrefchange'}
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TZ5KZ2J6CN4YE" target="_blank">
				<img src="{$donatebut}" border="0" name="submit" alt="{l s='PayPal, the complete and safe way of paying online' mod='modrefchange'}" title="{l s='PayPal, the complete and safe way of paying online' mod='modrefchange'}">
				<img alt="" border="0" src="{$donatepix}" width="1" height="1">
			</a>
		</div>
		<label>{l s='Use Order ID' mod='modrefchange'}</label>
		<div class="margin-form">	
			&nbsp;&nbsp;
			<input type="radio" name="ref_orderid" id="ref_orderid" value="1" {if $ref_orderid}checked="checked"{/if}/>
			<label class="t" for="active_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled'}" title="{l s='Enabled'}" style="cursor:pointer" /></label>
			&nbsp;&nbsp;
			<input type="radio" name="ref_orderid" id="ref_orderid" value="0" {if !$ref_orderid}checked="checked"{/if}/>
			<label class="t" for="active_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled'}" title="{l s='Disabled'}" style="cursor:pointer" /></label>
			<p class="clear preference_description">{l s='Use the Order ID instead of the random characters as Order reference' mod='modrefchange'}.</p>
		</div>
		<label>{l s='Use Zeros to prefix Order ID' mod='modrefchange'}</label>
		<div class="margin-form prefixnulo">
			&nbsp;&nbsp;
			<input type="radio" name="ref_prefixnulo" id="ref_prefixnulo" value="1" {if $ref_prefixnulo}checked="checked"{/if}/>
			<label class="t" for="active_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled'}" title="{l s='Enabled'}" style="cursor:pointer" /></label>
			&nbsp;&nbsp;
			<input type="radio" name="ref_prefixnulo" id="ref_prefixnulo" value="0" {if !$ref_prefixnulo}checked="checked"{/if}/>
			<label class="t" for="active_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled'}" title="{l s='Disabled'}" style="cursor:pointer" /></label>
			<p class="clear preference_description">{l s='Prefix the Order ID with zeros (e.g. \'000000001\', \'000000010\', \'00000000[ORDER_ID]\')' mod='modrefchange'}.</p>
		</div>
		<label>{l s='Number of zeros to prefix Order ID' mod='modrefchange'}</label>
		<div class="margin-form prefixnulnro {if !$ref_prefixnulo}hidden{/if}">
			<input type="text" size="10" style="width: 300px;" name="ref_prefixnulnro" value="{$ref_prefixnulnro}" /> 
			<p class="clear">{l s='Number of zeros to use as padding. Must be between 1 and 10' mod='modrefchange'}</p>
		</div>
		<label>{l s='Use Character(s) to prefix Order ID' mod='modrefchange'}</label>
		<div class="margin-form">
			<input type="text" size="20" style="width: 300px;" name="ref_prefixsigno" value="{$ref_prefixsigno}" /> 
			<p class="clear">{l s='Prefix the Order ID with one or more characters (e.g. \'O1\', \'ORD_10\')' mod='modrefchange'}.<br />{l s='Leave empty to not use prefix.' mod='modrefchange'}<br />{l s='You can also use date/time format (e.g. %Y) See ' mod='modrefchange'}<a href="www.php.net/manual/function.strftime.php" target="_blank">strftime</a> {l s='for format.' mod='modrefchange'}</p>
		</div>
		<label>{l s='Use Cart ID' mod='modrefchange'}</label>
		<div class="margin-form">
			&nbsp;&nbsp;
			<input type="radio" name="ref_cartid" id="ref_cartid" value="1" {if $ref_cartid}checked="checked"{/if}/>
			<label class="t" for="active_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled'}" title="{l s='Enabled'}" style="cursor:pointer" /></label>
			&nbsp;&nbsp;
			<input type="radio" name="ref_cartid" id="ref_cartid" value="0" {if !$ref_cartid}checked="checked"{/if}/>
			<label class="t" for="active_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled'}" title="{l s='Disabled'}" style="cursor:pointer" /></label>
			<p class="clear preference_description">{l s='Use the Cart ID instead of the random characters as Order reference' mod='modrefchange'}.</p>
		</div>
		<label>{l s='Use Zeros to prefix Cart ID' mod='modrefchange'}</label>
		<div class="margin-form prefixnulc">
			&nbsp;&nbsp;
			<input type="radio" name="ref_prefixnulc" id="ref_prefixnulc" value="1" {if $ref_prefixnulc}checked="checked"{/if}/>
			<label class="t" for="active_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled'}" title="{l s='Enabled'}" style="cursor:pointer" /></label>
			&nbsp;&nbsp;
			<input type="radio" name="ref_prefixnulc" id="ref_prefixnulc" value="0" {if !$ref_prefixnulc}checked="checked"{/if}/>
			<label class="t" for="active_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled'}" title="{l s='Disabled'}" style="cursor:pointer" /></label>
			<p class="clear preference_description">{l s='Prefix the Cart ID with zeros (e.g. \'000000001\', \'000000010\', \'00000000[CART_ID]\')' mod='modrefchange'}.</p>
		</div>
		<label class="prefixnulnrc {if !$ref_prefixnulc}hidden{/if}">{l s='Number of zeros to prefix Cart ID' mod='modrefchange'}</label>
		<div class="margin-form prefixnulnrc {if !$ref_prefixnulc}hidden{/if}">
			<input type="text" size="10" style="width: 300px;" name="ref_prefixnulnrc" value="{$ref_prefixnulnrc}" /> 
			<p class="clear">{l s='Number of zeros to use as padding. Must be between 1 and 10' mod='modrefchange'}</p>
		</div>
		<label>{l s='Use Character(s) to prefix Cart ID' mod='modrefchange'}</label>
		<div class="margin-form">
			<input type="text" size="20" style="width: 300px;" name="ref_prefixsignc" value="{$ref_prefixsignc}" /> 
			<p class="clear">{l s='Prefix the Cart ID with one or more characters (e.g. \'O1\', \'CID_10\')' mod='modrefchange'}.<br />{l s='Leave empty to not use prefix.' mod='modrefchange'}<br />{l s='You can also use date/time format (e.g. %Y) See ' mod='modrefchange'}<a href="www.php.net/manual/function.strftime.php" target="_blank">strftime</a> {l s='for format.' mod='modrefchange'}</p>
		</div>
		<label>{l s='Use Character(s) to prefix Order Reference' mod='modrefchange'}</label>
		<div class="margin-form">
			<input type="text" size="20" style="width: 300px;" name="ref_prefixsign" value="{$ref_prefixsign}" /> 
			<p class="clear">{l s='Prefix the Order Reference with one or more characters (e.g. \'O1\', \'REF_10\')' mod='modrefchange'}.<br />{l s='Leave empty to not use prefix.' mod='modrefchange'}<br />{l s='You can also use date/time format (e.g. %Y) See ' mod='modrefchange'}<a href="www.php.net/manual/function.strftime.php" target="_blank">strftime</a> {l s='for format.' mod='modrefchange'}</p>
		</div>
		{if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}
		<label>{l s='Use Order ID and/or Cart ID to change the invoice and delivery slip number' mod='modrefchange'}</label>
		<div class="inv_unhide">
			&nbsp;&nbsp;
			<input type="radio" name="inv_unhide" id="inv_unhide" value="1"/>
			<label class="t" for="active_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled'}" title="{l s='Enabled'}" style="cursor:pointer" /></label>
			&nbsp;&nbsp;
			<input type="radio" name="inv_unhide" id="inv_unhide" value="0" checked="checked" />
			<label class="t" for="active_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled'}" title="{l s='Disabled'}" style="cursor:pointer" /></label>
		</div>
		{/if}
		<div class="separation inv_form {if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}hidden{/if}"></div>
		<div class="margin-form inv_form {if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}hidden{/if}"><legend>{l s='Please specify the settings for the invoice and delivery slip change' mod='modrefchange'}.</legend></div>
		<label class="inv_form {if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}hidden{/if}">{l s='Use Order ID' mod='modrefchange'}</label>
		<div class="margin-form inv_form {if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}hidden{/if}">
			&nbsp;&nbsp;
			<input type="radio" name="inv_orderid" id="inv_orderid" value="1" {if $inv_orderid}checked="checked"{/if}/>
			<label class="t" for="active_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled'}" title="{l s='Enabled'}" style="cursor:pointer" /></label>
			&nbsp;&nbsp;
			<input type="radio" name="inv_orderid" id="inv_orderid" value="0" {if !$inv_orderid}checked="checked"{/if}/>
			<label class="t" for="active_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled'}" title="{l s='Disabled'}" style="cursor:pointer" /></label>
			<p class="clear preference_description">{l s='Use the Order ID instead of the invoice/deliveryslip sequence number as invoice or slip number.' mod='modrefchange'}.</p>
		</div>
		<label class="inv_form {if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}hidden{/if}">{l s='Use Cart ID' mod='modrefchange'}</label>
		<div class="margin-form inv_form {if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}hidden{/if}">
			&nbsp;&nbsp;
			<input type="radio" name="inv_cartid" id="inv_cartid" value="1" {if $inv_cartid}checked="checked"{/if}/>
			<label class="t" for="active_on"> <img src="../img/admin/enabled.gif" alt="{l s='Enabled'}" title="{l s='Enabled'}" style="cursor:pointer" /></label>
			&nbsp;&nbsp;
			<input type="radio" name="inv_cartid" id="inv_cartid" value="0" {if !$inv_cartid}checked="checked"{/if}/>
			<label class="t" for="active_off"> <img src="../img/admin/disabled.gif" alt="{l s='Disabled'}" title="{l s='Disabled'}" style="cursor:pointer" /></label>
			<p class="clear preference_description">{l s='Use the Cart ID instead of the invoice/deliveryslip sequence number as invoice or slip number.' mod='modrefchange'}.</p>
		</div>
		<label class="inv_form {if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}hidden{/if}">{l s='Use Date and/or Time formatting to prefix invoice and delivery slip' mod='modrefchange'}</label>
		<div class="margin-form inv_form {if !$inv_orderid && !$inv_cartid && empty($inv_prefixsign)}hidden{/if}">
			<input type="text" size="20" style="width: 300px;" name="inv_prefixsign" value="{$inv_prefixsign}" /> 
			<p class="clear">{l s='Prefix the invoice and delivery slip with date/time format (e.g. %Y) See' mod='modrefchange'} <a href="www.php.net/manual/function.strftime.php" target="_blank">strftime</a> {l s='for format.' mod='modrefchange'}.'<br>{l s='Leave empty to not use prefix' mod='modrefchange'}.</p>
		</div>
		<center><input type="submit" name="{$submitName}" value="{l s='Save' mod='modrefchange'}" class="button" /></center>
	</fieldset>
</form>
<style>
	.formlabel {
		margin-bottom: 30px;
		width: 100%;
	}
	.formlabel p {
		color: #585a69;
		text-shadow: 0 1px 0#fff;
		float: left;
		padding: 0.2em 0.5em 0 0;
		text-align: right;
		font-weight: bold;
	}
	div#modrefchangesubmit {
		margin-left: 15px;
		padding: 5px 10px 10px;
	}
	div#donatebut {
		float: right;
		text-align: center;
		margin-top: -50px;
		right: -15%;
		width: 350px;
		height: 60px;
		background: rgba(200, 238, 238, 0.5);
		-webkit-border-radius: 8px;
		-moz-border-radius: 8px;
		border-radius: 8px;
		border: 2px solid #000;
	}
	#modrefchangeform .hidden {
		display: none;
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