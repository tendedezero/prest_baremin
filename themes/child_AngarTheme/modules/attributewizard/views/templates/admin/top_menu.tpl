{*
 * 2008 - 2017 Presto-Changeo
 *
 * MODULE Attribute Wizard
 *
 * @version   2.0.0
 * @author    Presto-Changeo <info@presto-changeo.com>
 * @link      http://www.presto-changeo.com
 * @copyright Copyright (c) permanent, Presto-Changeo
 * @license   Addons PrestaShop license limitation
 *
 * NOTICE OF LICENSE
 *
 * Don't use this module on several shops. The license provided by PrestaShop Addons
 * for all its modules is valid only once for a single shop.
*}

<script type="text/javascript" src="{$path nofilter}views/js/globalBack.js"></script>
<script type="text/javascript" src="{$path nofilter}views/js/specificBack.js"></script>
<script type="text/javascript" src="{$path nofilter}views/js/ajaxupload.js"></script>
<script type="text/javascript" src="{$base_uri nofilter}js/jquery/ui/jquery.ui.sortable.min.js"></script>

<script type="text/javascript">
    var aw_random = '{$aw_random|escape:'htmlall':'UTF-8'}';

    var aw_psv = '{$aw_ps_version|floatval}';
    var aw_shops = '{$aw_shops|escape:'htmlall':'UTF-8'}';
    var baseDirModule = '{$module_dir|escape:'htmlall':'UTF-8'}';
    var aw_copy_src = "{l s='You must enter a Source Product ID (to copy from)' mod='attributewizard'}";
    var aw_copy_tgt = "{l s='You must enter a Target Product or Category ID (to copy to)' mod='attributewizard'}";
    var aw_invalid_src = "{l s='Invalid Source ID' mod='attributewizard'}";
    var aw_invalid_tgt = "{l s='Invalid Target ID' mod='attributewizard'}";
    var aw_copy_same = "{l s='Source and Target ID must be different' mod='attributewizard'}";
    var aw_are_you = "{l s='Are you sure you want to copy the attributes From' mod='attributewizard'}";
    var aw_will_delete = "{l s='This will delete all the existing attributes in the Target Product or Category' mod='attributewizard'}";
    var aw_to = "{l s='to' mod='attributewizard'}";
    var aw_copy = "{l s='Copy' mod='attributewizard'}";
    var aw_cancel = "{l s='Cancel' mod='attributewizard'}";
    var aw_copied = "{l s='Attributes Copied' mod='attributewizard'}";
    var aw_change_image = "{l s='Edit' mod='attributewizard'}";
    var aw_link = "{l s='Image URL' mod='attributewizard'}";
    var aw_upload_img = "{l s='Upload Image' mod='attributewizard'}";
    var aw_delete = "{l s='Delete' mod='attributewizard'}";
    var aw_id_lang = {$id_lang|intval};
</script>

<div id="module_top">
    <div id="module_header">
        <div class="module_name_presto">
            {$module_name|escape:'htmlall':'UTF-8'}
            <span class="module_version">{$mod_version|escape:'htmlall':'UTF-8'}</span>
            {if $contactUsLinkPrestoChangeo != ''}
                <div class="module_upgrade {if $upgradeCheck}showBlock{else}hideBlock{/if}">
                    {l s='A new version is available.' mod='attributewizard'}
                    <a href="{$contactUsLinkPrestoChangeo nofilter}">{l s='Upgrade now' mod='attributewizard'}</a>
                </div>
            {/if}
        </div>
        {if $contactUsLinkPrestoChangeo != ''}   
        <div class="request_upgrade">
            <a href="{$contactUsLinkPrestoChangeo nofilter}">{l s='Request an Upgrade' mod='attributewizard'}</a>
        </div>
        <div class="contact_us">
            <a href="{$contactUsLinkPrestoChangeo nofilter}">{l s='Contact us' mod='attributewizard'}</a>
        </div>

        <div class="presto_logo"><a href="{$contactUsLinkPrestoChangeo nofilter}">{$logoPrestoChangeo nofilter}</a></div>
        <div class="clear"></div>
        {/if}
    </div>
    
    
    <!-- Module upgrade popup -->
    {if $displayUpgradeCheck != ''}
    <a id="open_module_upgrade" href="#module_upgrade"></a>
    <div id="module_upgrade">
        {$displayUpgradeCheck nofilter}
    </div>
    {/if}
    <!-- END - Module upgrade popup -->
    <div class="clear"></div>
    <!-- Main menu - each main menu is connected to a submenu with the data-left-menu value -->
    <div id="main_menu">
        <div id="menu_0" class="menu_item" data-left-menu="secondary_0" data-content="basic_settings">{l s='Configuration' mod='attributewizard'}</div>
        <div id="menu_1" class="menu_item" data-contact-us="1" data-content="installation_instructions">{l s='Installation Instructions' mod='attributewizard'}</div> 
        <div id="menu_2" class="menu_item" data-contact-us="1" data-content="copy_attributes">{l s='Copy Attributes' mod='attributewizard'}</div> 
        <div class="clear"></div>
    </div>
    <!-- END Main menu - each main menu is connected to a submenu with the ALT value -->
</div>
<div class="clear"></div>