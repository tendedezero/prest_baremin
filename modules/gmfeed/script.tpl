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
    $.fn.deserialize = function (serializedString)
    {
        var serializedStringExploded = serializedString.split("?");
        var serializedString = serializedStringExploded[1];

        var $form = $(this);
        $form[0].reset();    // (A) optional
        serializedString = serializedString.replace(/\+/g, '%20'); // (B)
        var formFieldArray = serializedString.split("&");

        // Loop over all name-value pairs
        $.each(formFieldArray, function(i, pair){
            var nameValue = pair.split("=");
            var name = decodeURIComponent(nameValue[0]); // (C)
            var value = decodeURIComponent(nameValue[1]);
            // Find one or more fields
            var $field = $form.find('[name=' + name + ']');

            // Checkboxes and Radio types need to be handled differently
            if ($field[0].type == "radio" || $field[0].type == "checkbox")
            {
                var $fieldWithValue = $field.filter('[value="' + value + '"]');
                var isFound = ($fieldWithValue.length > 0);
                // Special case if the value is not defined; value will be "on"
                if (!isFound && value == "on") {
                    $field.first().prop("checked", true);
                } else {
                    $fieldWithValue.prop("checked", isFound);
                }
            } else { // input, textarea
                $field.val(value);
            }
        });
        return this;
    }

    $(document).ready(function () {
        $('.deserializeUrl').change(function(){
            $('#configuration_form').deserialize($(this).val());
        });

        var AjaxToken = "{Tools::getAdminTokenLite('AdminExportProductsFeedGoogle')}";
        updateFeed();
        $('input[name="export_instock_info"], input[name="export_what_pictures"], input[name="export_gtin"], select[name="export_product_type"], select[name="export_file_format"], select[name="export_currency"], input[name="export_instock"], input[name="export_removehtml"], input[name="export_description_what"], input[name="export_short_description_what"], input[name="export_delimiter"], input[name="export_active"], select[name="export_identification"],  select[name="export_currency"], select[name="export_category"], select[name="export_type"], select[name="export_language"], select[name="export_manufacturers"], select[name="export_suppliers"], select[name="export_tax"], select[name="export_img"]').change(function () {
            updateFeed();
        });

        $(".fancybox").fancybox({
            type        : 'ajax',
            fitToView	: true,
            width		: '80%',
            height		: '80%',
            autoSize	: false,
            closeClick	: false,
        });

        $(".syncGooleCategories").click(function(e) {
            var button = $(this);
            e.preventDefault();
            $.ajax({
                url: "ajax-tab.php?language_code="+$(this).attr('data-language-code')+"&controller=AdminExportProductsFeedGoogle&action=downloadCategories&token="+AjaxToken,
                beforeSend: function() {
                    button.find('i').addClass('icon-spin').addClass('icon-refresh').removeClass('icon-check').removeClass('icon-bug').parent().removeClass('btn-danger').removeClass('btn-success');
                },
                success: function(result)
                {
                    if (result == 1) {
                        button.find('i').removeClass('icon-refresh').removeClass('icon-spin').addClass('icon-check').parent().addClass('btn-success').removeClass('btn-danger').parent().parent().find('.taxonomy_info').hide();
                        button.parent().parent().find('.taxonomy_info_success').removeClass('hide').show();
                    } else {
                        button.find('i').removeClass('icon-refresh').removeClass('icon-spin').addClass('icon-bug').parent().addClass('btn-danger').removeClass('btn-success');
                    }
                }
            });
        });

        $(".syncGooleCategoriesCustom").click(function(e) {
            var button = $(this);
            e.preventDefault();
            $.ajax({
                url: "ajax-tab.php?language_code="+$(this).attr('data-language-code')+"&selected_option="+$(this).parent().find('select option:selected').val()+"&controller=AdminExportProductsFeedGoogle&action=downloadCategoriesCustom&token="+AjaxToken,
                beforeSend: function() {
                    button.find('i').addClass('icon-spin').addClass('icon-refresh').removeClass('icon-check').removeClass('icon-bug').parent().removeClass('btn-danger').removeClass('btn-success');
                },
                success: function(result)
                {
                    if (result == 1) {
                        button.find('i').removeClass('icon-refresh').removeClass('icon-spin').addClass('icon-check').parent().addClass('btn-success').removeClass('btn-danger').parent().parent().find('.taxonomy_info').hide();
                        button.parent().parent().find('.taxonomy_info_success').removeClass('hide').show();
                    } else {
                        button.find('i').removeClass('icon-refresh').removeClass('icon-spin').addClass('icon-bug').parent().addClass('btn-danger').removeClass('btn-success');
                    }
                }
            });
        });

    });

    $('.show-links').click(function () {
        $(this).parent().find('.hide').removeClass('hide');
    });

    function updateFeed() {
        $('.feedurl').html($('#configuration_form').serialize());
        $('.feedurl').each(function () {
            var elem = $(this);
            elem.fadeOut(200)
                .fadeIn(200)
                .fadeOut(200)
                .fadeIn(200)
                .fadeOut(200)
                .fadeIn(200)
                .fadeOut(200)
                .fadeIn(200)
                .fadeOut(200)
                .fadeIn(200);
        });
        showSuccessMessage('{$feed_updated|escape:javascript}');
        if ($('input[name="export_gtin"]:checked').val() == 'upc') {
            $("#gtin_details").html('UPC, EAN13');
        } else if ($('input[name="export_gtin"]:checked').val() == 'ean13') {
            $("#gtin_details").html('EAN13, UPC');

        } else if ($('input[name="export_gtin"]:checked').val() == 'reference') {
            $("#gtin_details").html('Reference, UPC, EAN13');

        }
    }
</script>

