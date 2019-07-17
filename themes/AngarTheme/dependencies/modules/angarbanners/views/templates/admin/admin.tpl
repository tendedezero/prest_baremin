{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<div id="htmlcontent" class="panel">
    <div class="panel-heading">{$htmlcontent.info.name|escape:'htmlall':'UTF-8'} (v.{$htmlcontent.info.version|escape:'htmlall':'UTF-8'})</div>
    {if isset($error) && $error}
        {include file="{$htmlcontent.admin_tpl_path|escape:'htmlall':'UTF-8'}messages.tpl" id="main" text=$error class='error'}
    {/if}
    {if isset($confirmation) && $confirmation}
        {include file="{$htmlcontent.admin_tpl_path|escape:'htmlall':'UTF-8'}messages.tpl" id="main" text=$confirmation class='conf'}
    {/if}
    <!-- New -->
    {include file="{$htmlcontent.admin_tpl_path|escape:'htmlall':'UTF-8'}new.tpl"}
    <!-- Slides -->
    {include file="{$htmlcontent.admin_tpl_path|escape:'htmlall':'UTF-8'}items.tpl"}
</div>
