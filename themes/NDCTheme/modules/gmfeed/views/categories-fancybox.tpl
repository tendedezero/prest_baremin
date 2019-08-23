<div class='row'>
    <div class='bootstrap'>
        <div class="table-responsive-row clearfix">
            <table class="table configuration">
                <thead>
                <tr class="nodrag nodrop">
                    <th class="col-sm-2 text-left">
                        <span class="label label-danger">{l s='Your shop category' mod='gmfeed'}</span>
                    </th>
                    <th class="col-sm-9 text-left">
                        <span class="label label-danger">{l s='Google category' mod='gmfeed'}</span> {l s='Type the name to start search' mod='gmfeed'}
                    </th>
                    <th class="col-sm-1 text-center">
                    </th>
                </tr>
                </thead>
                <tbody>
                {foreach $gmfeed_categories AS $category}
                    <tr>
                        {if !in_array($category['id'], array(1))}
                            <td>
                                {$category['name']}
                                {if Configuration::get('pf_tree') == 1}
                                    <p class="small">
                                        {foreach $category['parents'] AS $parent name=foo}
                                            {$parent.name} {if not $smarty.foreach.foo.last}→{/if}
                                        {/foreach}
                                    </p>
                                {/if}
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type='text' value="{if isset($gmfeed_google_categories[$category['id']]['value'])}{$gmfeed_google_categories[$category['id']]['value']}{/if}" class='form-control searchCategory' id="category_{$category['id']}" data-category-id="{$category['id']}" data-association-id="{if isset($gmfeed_google_categories[$category['id']]['id_pixelgoogle'])}{$gmfeed_google_categories[$category['id']]['id_pixelgoogle']}{/if}">
                                    <span class="input-group-addon">
                                        <span id="category_delete_{$category['id']}" class="pointer delete_association" data-category-id="{$category['id']}" data-association-id="{if isset($gmfeed_google_categories[$category['id']]['id_pixelgoogle'])}{$gmfeed_google_categories[$category['id']]['id_pixelgoogle']}{/if}"><i class="icon-trash"></i></span>
                                    </span>
                                </div>
                            </td>
                            <td class="confirmation_{$category['id']}" id="confirmation_{$category['id']}">
                            </td>
                        {/if}
                    </tr>
                {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>