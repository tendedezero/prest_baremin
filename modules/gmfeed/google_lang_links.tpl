{*
* PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
*
* @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
* @copyright 2010-2019 VEKIA
* @license   This program is not free software and you can't resell and redistribute it
*
* CONTACT WITH DEVELOPER http://mypresta.eu
* support@mypresta.eu
*}

<div class="panel col-lg-12">
    <h3><i class="icon-wrench"></i> {l s='Google categories' mod='gmfeed'}</h3>
    <div class="alert alert-info">
        {l s='If you want to include Google categories to pixel feed you need to pair yout shop categories with google categories' mod='gmfeed'}<br/></br>
        <strong>{l s='Taxonomy files download & synchronize problem' mod='gmfeed'}</strong><br/>
        {l s='If you can\'t download or synchronize a google category taxonomy file you will need to download it manually and copy to /modules/gmfeed/google/ directory with name like "en-us.txt", "pl-pl.txt", "es-es.txt" and so on' mod='gmfeed'}<br/>
        <a href="https://support.google.com/merchants/answer/6324436?hl=pl" target="blank">{l s='Download google taxonomy files' mod='gmfeed'}</a>
    </div>

    <div class="table-responsive-row clearfix">
        <table class="table configuration">
            <thead>
            <tr class="nodrag nodrop">
                <th class="fixed-width-md text-center">
                    <span class="title_box">{l s='Language' mod='gmfeed'}</span>
                </th>
                <th class="fixed-width-md text-center">
                    <span class="title_box">{l s='Configure' mod='gmfeed'}</span>
                </th>
                <th class="fixed-width-md text-center">
                    <span class="title_box">{l s='File' mod='gmfeed'}</span>
                </th>
                <th class="fixed-width-lg text-center">
                    <span class="title_box label-tooltip" data-toggle="tooltip"  data-toggle="tooltip" data-original-title="{l s='Option downloads google products taxonomy file automatically according to corresponding language. If google does not offer feed for selected language - you can download specific taxonomy file with tool \'synchronize specific\'' mod='gmfeed'}">{l s='Synchronize' mod='gmfeed'}</span>
                </th>
                <th class="fixed-width-lg text-center">
                    <span class="title_box" >{l s='Synchronize specific' mod='gmfeed'}</span>
                </th>
            </tr>
            </thead>
            <tbody>
            {foreach Language::getLanguages(true) AS $language}
                <tr class="pointer {if $language@iteration is odd by 1}odd{/if}">
                    <td class="fixed-width-md text-center">
                        {$language.language_code}
                    </td>
                    <td class="fixed-width-md text-center">
                        <button class="btn btn-default fancybox" href="ajax-tab.php?language_code={$language.language_code}&controller=AdminExportProductsFeedGoogle&ajax=1&action=googleCategories&token={Tools::getAdminTokenLite('AdminExportProductsFeedGoogle')}">
                            <i class="icon-random" aria-hidden="true"></i> {l s='Pair categories' mod='gmfeed'}
                        </button>
                    </td>
                    <td class="fixed-width-lg text-center">
                        {if $taxonomy_files[$language.language_code] == 0}
                            <label class="label label-danger taxonomy_info">{$language.language_code}.txt {l s='file does not exist, synchronize it first' mod='gmfeed'}</label>
                            <label class="label label-success taxonomy_info_success hide">{$language.language_code}.txt {l s='file exist' mod='gmfeed'}</label>
                        {else}
                            <label class="label label-success taxonomy_info_success">{$language.language_code}.txt {l s='file exist' mod='gmfeed'}</label>
                        {/if}
                    </td>
                    <td class="fixed-width-lg text-center">
                        <button data-language-code="{$language.language_code}" class="btn btn-default syncGooleCategories">
                            <i class="icon-refresh" aria-hidden="true"></i> {l s='Synchronize' mod='gmfeed'}
                        </button>
                    </td>
                    <td>
                        <select name="custom_taxonomy_file" class="col-lg-9">
                            <option value="0">
                                {l s='-- select --' mod='gmfeed'}
                            </option>
                            {foreach $taxonomy_files_custom AS $l=>$url}
                                <option value="{$l}">
                                    {$l}
                                </option>
                            {/foreach}
                        </select>
                        <button data-language-code="{$language.language_code}" class="col-lg-3 btn btn-default syncGooleCategoriesCustom">
                            <i class="icon-download" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>