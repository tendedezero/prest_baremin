{**
* NOTICE OF LICENSE
*
* This source file is subject to the Software License Agreement
* that is bundled with this package in the file LICENSE.txt.
*
*  @author    Peter Sliacky (Zelarg)
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}
<div id="static-customer-info-container">
  {if !$customer.is_guest && $customer.is_logged}
    <a class="edit-customer-info" href="{$urls.pages.identity}">
      <div class="static-customer-info" data-edit-label="{l s='Edit' d='Shop.Theme.Actions'}">
        <div class="customer-name">{$customer.firstname} {$customer.lastname}</div>
        <div class="customer-email">{$customer.email}</div>
      </div>
    </a>
  {/if}
</div>
