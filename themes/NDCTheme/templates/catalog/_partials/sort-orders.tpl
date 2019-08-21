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

{* AngarThemes *}

{if !empty($smarty.get.order)}
    {capture assign='ordering'}order={$smarty.get.order}&a{/capture}
{else}
    {assign var='ordering' value=''}
{/if}

{if !empty($smarty.get.resultsPerPage)}
    {assign var='results_per_page' value=$smarty.get.resultsPerPage}
{else}
    {assign var='results_per_page' value=20}
{/if}

<span class="col-sm-3 col-md-5 hidden-sm-down sort-by">{l s='Sort by:' d='Shop.Theme.Global'}</span>

    <div style="float:left;padding-left: 0px;" class="sort-select dropdown js-dropdown col-lg-3 col-md-3 col-sm-3">
        <a class="custom-select select-title" rel="nofollow" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {$results_per_page}
        </a>﻿

    <div class="dropdown-menu">
        <a rel="nofollow" href="?{$ordering}resultsPerPage=20" class="dropdown-item js-search-link">
            20
        </a>
        <a rel="nofollow" href="?{$ordering}resultsPerPage=40" class="dropdown-item js-search-link">
            40
        </a>
        <a rel="nofollow" href="?{$ordering}resultsPerPage=60" class="dropdown-item js-search-link">
            60
        </a>
        <a rel="nofollow" href="?{$ordering}resultsPerPage=80" class="dropdown-item js-search-link">
            80
        </a>
        <a rel="nofollow" href="?{$ordering}resultsPerPage=100" class="dropdown-item js-search-link">
            100﻿
        </a>﻿
    </div>
</div>

<div class="{if !empty($listing.rendered_facets)}col-sm-9 col-xs-8{else}col-sm-12 col-xs-12{/if} col-md-7 products-sort-order dropdown">
  <button
    class="btn-unstyle select-title"
    rel="nofollow"
    data-toggle="dropdown"
    aria-haspopup="true"
    aria-expanded="false">
    {if isset($listing.sort_selected)}{$listing.sort_selected}{else}{l s='Select' d='Shop.Theme.Actions'}{/if}
    <i class="material-icons float-xs-right">&#xE313;</i>
  </button>

  <div class="dropdown-menu">
    {foreach from=$listing.sort_orders item=sort_order}
      <a
        rel="nofollow"
        href="{$sort_order.url}"
        class="select-list {['current' => $sort_order.current, 'js-search-link' => true]|classnames}"
      >
        {$sort_order.label}
      </a>
    {/foreach}
  </div>
</div>
