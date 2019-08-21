{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}


{if $voucher->id_invoucher!=''}
<li><a href="{$my_voucher_edit_link|escape:'html':'UTF-8'}?id_customer={$id_customer|escape:'html':'UTF-8'}&nka_token=edit&id_supplier={$voucher->invoucher_text}&voucher={$voucher->invoucher_chiffre}&id_invoucher={$voucher->id_invoucher}" title="Informations"><i class="icon-list-ol"></i><span>{l s='Edit Voucher Number'}</span></a></li>
<div class="alert alert-info">
Supplier: <em style="color:#333;">{Supplier::getNameById((int)$voucher->invoucher_text)}</em>
<br/>
Voucher number: <em style="color:#333;"> {$voucher->invoucher_chiffre} </em>
</div>
{else}
<li><a href="{$my_voucher_list_link|escape:'html':'UTF-8'}?id_customer={$id_customer|escape:'html':'UTF-8'}&nka_token=add" title="Informations"><i class="icon-list-ol"></i><span>{l s='Add Voucher Number'}</span></a></li>
{/if}