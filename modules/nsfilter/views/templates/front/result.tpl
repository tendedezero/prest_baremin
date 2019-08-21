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


{if $logged}
  <div class="container result">
            <div class="row"> 
                    <div class='result-logo'>
                    <h2>{l s='Quiz Results' mod='simplequiz'}</h2>
                    </div>    
           </div>  
           <hr>   
           <div class="row"> 
                  <div class="col-xs-18 col-sm-9 col-lg-9"> 
                    {include file="./productscategory.tpl"}
                  </div>

                  <div class="col-xs-6 col-sm-3 col-lg-3"> 
                     <a href="{$nka_update_voucher_submit_link}" class='btn btn-success'>Start new Quiz!!!</a>                   
                     <a href="{$base_dir}" class='btn btn-success'>Home</a>                       

                   </div>
            </div>    
            <div class="row">    

            </div>
        </div>

{else}
</div>
			{l s='You should login before answering any question!' mod='simplequiz'} <br/><a style="color:red;" href="{$link->getPageLink('my-account', true)|escape:'html'}" title="{l s='Log in to your customer account' mod='innovavoucher'}" class="login" rel="nofollow">{l s='Login' mod='innovavoucher'}</a>
{/if}
