{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<div id="_desktop_contact_link">
  <div id="contact-link">

	{if $angarcontact_phone}
		<span class="shop-phone contact_link">
			<a href="{$urls.pages.contact}">{l s='Contact' mod='angarcontact'}</a>
		</span>
	{/if}

	{if $angarcontact_phone}
		<span class="shop-phone shop-tel">
			<i class="fa fa-phone"></i>
			<span class="shop-phone_text">{l s='Phone:' mod='angarcontact'}</span>
			<strong>{$angarcontact_phone}</strong>
		</span>
	{else}
		<span class="shop-phone contact_link">
			<a href="{$urls.pages.contact}">{l s='Contact' mod='angarcontact'}</a>
		</span>
	{/if}

	{if $angarcontact_email}
		<span class="shop-phone shop-email">
			<i class="fa fa-envelope"></i>
			<span class="shop-phone_text">{l s='Email:' mod='angarcontact'}</span>
			<strong>{mailto address=$angarcontact_email|escape:'html':'UTF-8' encode="hex"}</strong>
		</span>
	{/if}

  </div>
</div>
