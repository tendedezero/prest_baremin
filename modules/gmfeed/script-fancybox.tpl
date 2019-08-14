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

<link rel="stylesheet" href="../modules/gmfeed/views/admin-theme.css" type="text/css" media="all">
<script>
    {literal}
    $(document).ready(function () {
        {/literal}
        var AjaxToken = "{Tools::getAdminTokenLite('AdminExportProductsFeedGoogle')}";
        var LanguageCode = "{Tools::getValue('language_code','en-us')}";

        {literal}
        function log(message) {
            $("<div>").text(message).prependTo("#log");
            $("#log").scrollTop(0);
        }

        $(".searchCategory").autocomplete(
            "ajax-tab.php?controller=AdminExportProductsFeedGoogle&action=searchCategory&token=" + AjaxToken + "&language_code=" + LanguageCode,
            {
                minLength: 2,
                max: 20,
            }
        ).result(function (event, data, formatted) {
            var categoryId =  $(this).data('category-id');
            var associationId =  $(this).data('association-id');
            $.ajax({
                type: "POST",
                url: "ajax-tab.php",
                data: {
                    language_code: LanguageCode,
                    controller: 'AdminExportProductsFeedGoogle',
                    action: 'saveCategories',
                    id_category: $(this).data('category-id'),
                    id_association: $(this).data('association-id'),
                    value: formatted,
                    token: AjaxToken,
                },
                beforeSend: function() {
                   $('.confirmation_'+categoryId).html('<span class="label label-info"><i class="icon-refresh icon-spin" aria-hidden="true"></i></span>')
                },
                success: function(result)
                {
                    $('.confirmation_'+categoryId).html('<span class="label label-success">{/literal}{l s='Saved' mod='gmfeed'}{literal}</span>')
                }
            });
        });

        $('.delete_association').click(function(e){
            var categoryId =  $(this).data('category-id');
            var associationId =  $(this).data('association-id');
            $.ajax({
                type: "POST",
                url: "ajax-tab.php",
                data: {
                    language_code: LanguageCode,
                    controller: 'AdminExportProductsFeedGoogle',
                    action: 'deleteCategories',
                    id_category: $(this).data('category-id'),
                    id_association: $(this).data('association-id'),
                    token: AjaxToken,
                },
                beforeSend: function() {
                    $('.confirmation_'+categoryId).html('<span class="label label-info"><i class="icon-refresh icon-spin" aria-hidden="true"></i></span>')
                },
                success: function(result)
                {
                    $('#category_'+categoryId).attr('data-association-id','');
                    $('#category_delete_'+categoryId).attr('data-association-id','');
                    $('#category_'+categoryId).val('');
                    $('.confirmation_'+categoryId).html('<span class="label label-success">{/literal}{l s='Saved' mod='gmfeed'}{literal}</span>')
                }
            });
        });
    });
    {/literal}
</script>