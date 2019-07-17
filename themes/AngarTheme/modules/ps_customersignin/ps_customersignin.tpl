{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div id="_desktop_user_info">
  <div class="user-info">

{*
	<a class="hidden-md-up" href="{$my_account_url}" title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">
		<i class="material-icons logged">&#xE7FF;</i>
	</a>
*}

	<ul class="user_info hidden-md-up">

		{if $logged}

			<li><a href="{$my_account_url}" title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">{l s='Your account' d='Shop.Theme.Customeraccount'}</a></li>

			<li><a id="identity-link" href="{$urls.pages.identity}">{l s='Information' d='Shop.Theme.Customeraccount'}</a></li>

			{if $customer.addresses|count}
			<li><a id="addresses-link" href="{$urls.pages.addresses}">{l s='Addresses' d='Shop.Theme.Customeraccount'}</a></li>
			{else}
			<li><a id="address-link" href="{$urls.pages.address}">{l s='Add first address' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}

			{if !$configuration.is_catalog}
			<li><a id="history-link" href="{$urls.pages.history}">{l s='Order history and details' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}

			{if !$configuration.is_catalog}
			<li><a id="order-slips-link" href="{$urls.pages.order_slip}">{l s='Credit slips' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}

			{if $configuration.voucher_enabled && !$configuration.is_catalog}
			<li><a id="discounts-link" href="{$urls.pages.discount}">{l s='Vouchers' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}

			{if $configuration.return_enabled && !$configuration.is_catalog}
			<li><a id="returns-link" href="{$urls.pages.order_follow}">{l s='Merchandise returns' d='Shop.Theme.Customeraccount'}</a></li>
			{/if}

			<li><a href="{$logout_url}" rel="nofollow">({l s='Sign out' d='Shop.Theme.Actions'})</a></li>

		{else}

			<li><a href="{$my_account_url}" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">{l s='Sign in' d='Shop.Theme.Actions'}</a></li>
			<li><a href="{$urls.pages.register}" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">{l s='Create an account' d='Shop.Theme.Mytheme'}</a></li>

		{/if}

	</ul>


	<div class="hidden-sm-down">
		<span class="welcome">{l s='Welcome,' d='Shop.Theme.Mytheme'}</span>
		{if $logged}
			<a class="account" href="{$my_account_url}" title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">{$customerName}</a>
			<a class="logout" href="{$logout_url}" rel="nofollow">({l s='Sign out' d='Shop.Theme.Actions'})</a>
		{else}
			<a class="account" href="{$my_account_url}" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">{l s='Sign in' d='Shop.Theme.Actions'}</a>

			<span class="or">{l s='or' d='Shop.Theme.Mytheme'}</span>

			<a class="account" href="{$urls.pages.register}" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">{l s='Create an account' d='Shop.Theme.Mytheme'}</a>
		{/if}
	</div>


  </div>
</div>