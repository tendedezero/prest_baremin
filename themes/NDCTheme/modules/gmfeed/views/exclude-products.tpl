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

<script>
    var img = '';
    var msgTitle = '{$msgTitle|escape:javascript}';
    var msgContents = '{$msgContents|escape:javascript}';
    var link = "ajax_products_list.php";
    var lang = jQuery('#lang_spy').val();

    function deleteProduct(id) {
        $("#selected_product_" + id).remove();
        updateFeed();
    }


    {if $version >= 1.6 && $version <1.7}
    {literal}
    $(document).ready(function () {
        $("#product_autocomplete_input").autocomplete('ajax_products_list.php?exclude_packs=0&excludeVirtuals=0&excludeIds=99999999999', {
            minChars: 1,
            autoFill: true,
            max: 20,
            matchContains: true,
            mustMatch: false,
            scroll: false,
            cacheLength: 0,
            formatItem: function (item) {
                return item[0] + ' - ' + item[1];
            }
        }).result(function (e, p) {
            var $divAccessories = $('#addProducts');
            var exclude = [];
            var selected = $('.gmfeed_products');
            for (var i = 0; i < selected.length; i++)
                exclude.push(selected[i].value);
            var ps_div = '';

            if ($.inArray(p[1], exclude) == -1) {
                ps_div = '<div id="selected_product_' + p[1] + '" class="form-control-static margin-form"><input type="hidden" name="gmfeed_products[]" value="' + p[1] + '" class="gmfeed_products"/><button type="button" class="btn btn-default remove-product" name="' + p[1] + '" onclick="deleteProduct(' + p[1] + ')">' + img + '<i class="icon-remove text-danger"></i></button>&nbsp;' + p[0] + '</div>';
                $divAccessories.show().html($divAccessories.html() + ps_div);
                showNoticeMessage(msgTitle, msgContents);
                $(this).val('');
            }
        });
    });

    $(document).ready(function () {
        $('.add_all_search_results').click(function () {
            var search_query = $('#product_autocomplete_input').val();
            $.ajax({
                url: link,
                type: "post",
                dataType: "json",
                data: {
                    'limit': 999999999,
                    'disableCombination': 1,
                    'exclude_packs': 0,
                    'excludeVirtuals': 0,
                    'id_lang': lang,
                    'forceJson': 1,
                    'q': search_query
                },
                success: function (data) {
                    var selected = $('.gmfeed_products');
                    var exclude = [];
                    for (var i = 0; i < selected.length; i++) {
                        exclude.push(selected[i].value);
                    }
                    $.each(data, function (i, val) {
                        var selected = $('.gmfeed_products');
                        var exclude = [];
                        for (var i = 0; i < selected.length; i++) {
                            exclude.push(selected[i].value);
                        }

                        if ($.inArray(val.id, exclude) == -1) {
                            $("#addProducts").append('<div id="selected_product_' + val.id + '" class="form-control-static margin-form"><input type="hidden" name="gmfeed_products[]" value="' + val.id + '" class="gmfeed_products"><button type="button" class="btn btn-default remove-product" name="' + val.id + '" onclick="deleteProduct(' + val.id + ')"><i class="icon-remove text-danger"></i></button> ' + val['name'] + '</div>');
                            $("#addProducts").show();
                            showNoticeMessage(msgTitle, msgContents);
                        }
                    });
                }
            });
        });
    });

    {/literal}
    {/if}

    {if $version >= 1.7}
    {literal}
    $(document).ready(function () {
        $("#product_autocomplete_input")
            .autocomplete(
                link, {
                    minChars: 3,
                    max: 10,
                    width: 500,
                    selectFirst: false,
                    scroll: false,
                    dataType: "json",
                    formatItem: function (data, i, max, value, term) {
                        return value;
                    },
                    parse: function (data) {
                        var mytab = new Array();
                        for (var i = 0; i < data.length; i++)
                            mytab[mytab.length] = {data: data[i], value: data[i].id + ' - ' + data[i].name};
                        return mytab;
                    },
                    extraParams: {
                        forceJson: 1,
                        disableCombination: 1,
                        exclude_packs: 0,
                        exclude_virtuals: 0,
                        excludeIds: 999999999,
                        limit: 20,
                        id_lang: lang
                    }
                }
            )
            .result(function (event, data, formatted) {
                var $divAccessories = $('#addProducts');
                if (data.id.length > 0 && data.name.length > 0) {
                    var exclude = [];
                    var selected = $('.gmfeed_products');
                    for (var i = 0; i < selected.length; i++)
                        exclude.push(selected[i].value);
                    var ps_div = '';

                    if ($.inArray(data.id_product, exclude) == -1) {
                        ps_div = '<div id="selected_product_' + data.id + '" class="form-control-static margin-form"><input type="hidden" name="gmfeed_products[]" value="' + data.id + '" class="gmfeed_products"/><button type="button" class="btn btn-default remove-product" name="' + data.id + '" onclick="deleteProduct(' + data.id + ')">' + img + '<i class="icon-remove text-danger"></i></button>&nbsp;' + data.name + '</div>';
                        $divAccessories.show().html($divAccessories.html() + ps_div);
                        showNoticeMessage(msgTitle, msgContents);
                        updateFeed();
                    }

                }
            });

        $('.add_all_search_results').click(function () {
            var search_query = $('#product_autocomplete_input').val();
            $.ajax({
                url: link,
                type: "post",
                dataType: "json",
                data: {
                    'limit': 999999999,
                    'disableCombination': 1,
                    'exclude_packs': 0,
                    'exclude_virtuals': 0,
                    'id_lang': lang,
                    'forceJson': 1,
                    'q': search_query
                },
                success: function (data) {
                    var selected = $('.gmfeed_products');
                    var exclude = [];
                    for (var i = 0; i < selected.length; i++) {
                        exclude.push(selected[i].value);
                    }
                    $.each(data, function (i, val) {
                        if ($.inArray(i, exclude) == -1) {
                            $("#addProducts").append('<div id="selected_product_' + val['id'] + '" class="form-control-static margin-form"><input type="hidden" name="gmfeed_products[]" value="' + val['id'] + '" class="gmfeed_products"><button type="button" class="btn btn-default remove-product" name="' + val['id'] + '" onclick="deleteProduct(' + val['id'] + ')"><i class="icon-remove text-danger"></i></button> ' + val['name'] + '</div>');
                            $("#addProducts").show();
                            showNoticeMessage(msgTitle, msgContents);
                            updateFeed();
                        }
                    });
                }
            });
        });
    });
    {/literal}
    {/if}
</script>
<div class="panel">
    <h3>{l s='Search for products to exclude' mod='gmfeed'}</h3>
    <div>
        <div class="alert alert-info">
            {l s='You can also search for specific product and just add it to the list of products to exclude.' mod='gmfeed'}
        </div>
        <div id="ajax_choose_product">
            <div class="row">
                <div class="col-lg-8">
                    <div class="input-group">
                        <input id="product_autocomplete_input" name="" type="text" class="text ac_input" value=""/>
                        <input id="lang_spy" type="hidden" value="{Context::getContext()->language->id}"/>
                        <span class="input-group-addon"><i class="icon-search"></i></span>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="btn btn-primary add_all_search_results"><i
                                class="icon-plus"></i> {l s='Add all search results' mod='gmfeed'}
                    </div>
                </div>
            </div>
            <p class="preference_description help-block margin-form">{l s='Start by typing the first letters of the product\'s name, then select the product from the drop-down list.' mod='gmfeed'}</p>
        </div>
        <div id="addProducts" style="display:none;"></div>
    </div>
</div>