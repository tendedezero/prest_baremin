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

<pre>{*$questions|print_r*}</pre>

{include file="./submitAddnewQuestion.tpl"}


<form id="configuration_form" class="defaultForm form-horizontal simplequiz">
<div class="panel">												
<div class="panel-heading">
<i class="icon-edit"></i>{l s=' Questions:' mod='simplequiz'}
</div>		
<div class="form-wrapper">
</div>
<table class="table product">
<thead>
<tr class="nodrag nodrop">
<th class="center fixed-width-xs"></th>
<th class="">
<span class="title_box">
{l s='ID Question' mod='simplequiz'}
</span>
</th>
<th class="">
<span class="title_box">
{l s='Title' mod='simplequiz'}
</span>
</th>
<!--<th class="">
<span class="title_box">
{l s='question Image' mod='simplequiz'}
</span>
</th>-->

<th></th>
</tr>
</thead>
<tbody>
{foreach from=$questions item=question}  
<tr class=" odd">
<td class="row-selector text-center">
<input type="checkbox" name="productBox[]" value="" class="noborder">
</td>							
<td class="">
{$question.id_question} 
</td>
<td class="">{$question.question_name}</td>
<td class="text-right">																																																																																																																	<div class="btn-group-action">				<div class="btn-group pull-right">
<a href="index.php?controller=AdminModules&amp;configure=simplequiz&id_question={$question.id_question}&viewsimplequiz_questions&token={$token}" class=" btn btn-default" title="View">
	<i class="icon-search-plus"></i> {l s=' View' mod='simplequiz'}
</a>
<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
<i class="icon-caret-down"></i>&nbsp;
</button>
<ul class="dropdown-menu">
																																	<li>
<a href="index.php?controller=AdminModules&amp;configure=simplequiz&amp;id_question={$question.id_question}&updatesimplequiz_questions&token={$token}" title="Edit" class="edit">
	<i class="icon-pencil"></i> {l s='Edit' mod='simplequiz'}
</a>
</li>
<li class="divider">
</li>
<li>
<a href="index.php?controller=AdminModules&amp;configure=simplequiz&id={$category_id}&viewsimplequiz_categories&id_question={$question.id_question}&deletesimplequiz_questions&token={$token}" onclick="if (confirm('Delete selected item?')){ return true; }else{ event.stopPropagation(); event.preventDefault();};" title="Delete" class="delete">
<i class="icon-trash"></i>  {l s='Delete' mod='simplequiz'}
</a>
</li>
</ul>
</div>
</div>
</td>





</tr>
{/foreach}	
</tbody>
</table>
<div class="panel-footer">
<a href="{$url_back}" class="btn btn-default">
<i class="process-icon-back"></i>{l s='Back to list' mod='simplequiz'}</a>
</div>
</div>
</form>	
<br>
