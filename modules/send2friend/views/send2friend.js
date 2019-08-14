/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2019 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */

$('document').ready(function () {
    $('#send_friend_button').fancybox({
        'hideOnContentClick': false
    });

    if (send2friend_SEND2FRIEND_GDPR != 1)
    {
        $('#sendEmail').click(function () {
            send2FriendEmail();
        });
    }

});

function send2FriendEmail() {
    var name = $('#friend_name').val();
    var email = $('#friend_email').val();
    var part_first_against_spambots_lol = 'send2friend';
    var part_second_against_spambots_lol = 'ajax';
    var part_third_against_spambots_lol = 'php';
    var id_product = $('#id_product_send').val();
    if (name && email && !isNaN(id_product)) {
        $.ajax({
            url: prestashop.urls.base_url + "modules/"+part_first_against_spambots_lol+"/"+part_first_against_spambots_lol+"_"+part_second_against_spambots_lol+"."+part_third_against_spambots_lol,
            type: "POST",
            headers: {"cache-control": "no-cache"},
            data: {
                action: 'sendToMyFriend',
                secure_key: send2friend_secureKey,
                name: name,
                id_lang: send2friend_id_lang,
                email: email,
                id_product: id_product
            },
            dataType: "json",
            success: function (result) {
                $.fancybox.close();
                var msg = result ? send2friend_confirmation : send2friend_problem;
                var title = send2friend_title;
                fancyMsgBoxSend2Friend(msg, title);
            }
        });
    }
    else {
        $('#send_friend_form_error').show().text(send2friend_missingFields);
    }
}

function fancyMsgBoxSend2Friend(msg, title) {
    if (title) msg = "<h2 style=\"padding-bottom:10px;\">" + title + "</h2><p>" + msg + "</p>";
    msg += "<br/><p class=\"submit\" style=\"text-align:right; padding-bottom: 0\"><input class=\" btn btn-primary\" type=\"button\" value=\"OK\" onclick=\"$.fancybox.close();\" /></p>";
    $.fancybox(msg, {
        'autoDimensions': false,
        'width': 500,
        'height': 'auto',
        'transitionIn': 'none',
        'transitionOut': 'none'
    });
}

