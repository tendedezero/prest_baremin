{*
 *
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2019 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 *
 *}
<a id="send_friend_button" class="btn btn-primary" href="#send_friend_form">
    <i class="material-icons">account_circle</i>{l s='Send to a friend' mod='send2friend'}
</a>

<div style="display: none;">
    <div id="send_friend_form" class="card">
        <div class="card-block">
            <div class="product clearfix">
                <img src="{$link->getImageLink($stf_product->link_rewrite, $stf_product_cover, 'home_default')|escape:'html'}" alt="{$stf_product->name|escape:html:'UTF-8'}"/>
                <div class="product_desc">
                    <p class="product_name"><strong>{$stf_product->name}</strong></p>
                    {$stf_product->description_short nofilter}
                </div>
            </div>

            <div class="send_friend_form_content" id="send_friend_form_content">
                <div id="send_friend_form_error" class="alert alert-danger" style="display:none;"></div>
                <div class="form_container">
                    <fieldset class="form-group">
                        <label class="form-control-label"
                               for="friend_name">{l s='Name of your friend' mod='send2friend'}</label>
                        <input id="friend_name" name="friend_name" type="text" value="" class="form-control"/>
                    </fieldset>

                    <fieldset class="form-group">
                        <label class="form-control-label"
                               for="friend_email">{l s='E-mail address of your friend' mod='send2friend'}</label>
                        <input id="friend_email" name="friend_email" type="text" value="" class="form-control"/>
                    </fieldset>
                </div>
                <p class="submit pull-right">
                    {if $SEND2FRIEND_GDPR == 1}
                    {literal}
                        <input onchange="if($(this).is(':checked')){$('#sendEmail').removeClass('gdpr_disabled'); $('#sendEmail').removeAttr('disabled'); $('#sendEmail').click(function(){send2FriendEmail();});}else{$('#sendEmail').addClass('gdpr_disabled'); $('#sendEmail').off('click'); $('#sendEmail').attr('disabled', 1); }"
                               id="gdpr_checkbox" type="checkbox">
                    {/literal}
                        {l s='I accept ' mod='send2friend'}
                        <a target="_blank"
                           href="{$link->getCmsLink($SEND2FRIEND_GDPRCMS)}">{l s='privacy policy' mod='send2friend'}</a>
                        {l s='rules' mod='send2friend'}
                    {/if} &nbsp;
                    <input {if $SEND2FRIEND_GDPR == 1}disabled{/if} id="sendEmail"
                           class="btn btn-primary {if $SEND2FRIEND_GDPR == 1}gdpr_disabled{/if}" name="sendEmail"
                           type="submit" value="{l s='Send' mod='send2friend'}"/>
                    <input id="id_product_send" name="id_product" type="hidden" value="{$stf_product->id}"/>
                </p>
            </div>
        </div>
    </div>
</div>