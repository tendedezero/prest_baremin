{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
{* AngarTheme *}
<div class="block-contact col-md-3 links wrapper">
  <div class="h3 block-contact-title hidden-sm-down">
    <a class="text-uppercase" href="{$urls.pages.contact}" rel="nofollow">
      {l s='Contact' mod='angarcontact'}
    </a>
  </div>
  <div class="title clearfix hidden-md-up" data-target="#footer_contact" data-toggle="collapse">
    <span class="h3">{l s='Contact' mod='angarcontact'}</span>
    <span class="float-xs-right">
      <span class="navbar-toggler collapse-icons">
        <i class="material-icons add">&#xE313;</i>
        <i class="material-icons remove">&#xE316;</i>
      </span>
    </span>
  </div>
  <ul class="account-list collapse" id="footer_contact">
    {if $angarcontact_company != ''}<li>{$angarcontact_company}</li>{/if}
	{if $angarcontact_address != ''}<li>{$angarcontact_address nofilter} {*HTML CONTENT*}</li>{/if}
	{if $angarcontact_phone != ''}<li>{l s='Phone:' mod='angarcontact'} <strong>{$angarcontact_phone}</strong></li>{/if}
	{if $angarcontact_email != ''}<li>{l s='Email:' mod='angarcontact'} <strong>{mailto address=$angarcontact_email|escape:'html':'UTF-8' encode="hex"}</strong></li>{/if}
  </ul>
</div>