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
<div class="panel po_main_content" id="installation_instructions">
    
    <div class="panel_header">
        <div class="panel_title">{l s='Installation Instructions' mod='attributewizard'}</div>
        <div class="panel_info_text important">
            <span class="important_alert"> </span>
            {l s='This installation instruction is very important. Please read carefully before continuing to the configuration tab.' mod='attributewizard'}
        </div>
        <div class="clear"></div>
    </div>
        
    <div class="general_instructions single_column">
        <div class="instructions_title">{l s='General Instructions' mod='attributewizard'}</div>
        <div class="general_instructions_content">
            <ul>
                <li>
                    <span>{l s='You can only use a Checkbox when an attribute group has only 2 items, give the first attribute item the same name as the attribute group, and name the other attribute item "No".' mod='attributewizard'}</span>
                    <span class="important_alert"> </span>                    
                </li>
                <li>
                    <span>
                        {l s='You can group Checkboxes in the same block by giving them the same "Group Header", make sure to sort them in order. The description of the first group will be displayed below the header (if entered).' mod='attributewizard'}
                    </span>
                </li>
                
            </ul>
        </div>
    </div>
            
    <div class="override_instructions single_column">
        <div class="instructions_title">
            {l s='Override Files when using CHECKBOXES' mod='attributewizard'}
            <a href="{$request_uri nofilter}&aw_shis={$aw_shis|intval}">
            {if $aw_shis == 'none'}
                <span class="arrow_up"></span>
            {else}
                <span class="arrow_down"></span>
            {/if}
             </a>
        </div>

        <div class="override_content {if $aw_shis == 'none'}hideADN{/if}">
            <div class="override_block">
                <div class="override_class">
                    <span  class="{if $checkInstalledCart['/override/classes/Cart.php']['file_installed']}file_installed{else}file_not_installed{/if}">/attributewizard/override_{$aw_ps_version|floatval}/classes/Cart.php</span>
                </div>
                <div class="override_lines">
                    {if $checkInstalledCart['/override/classes/Cart.php']['file_not_found']}
                        Lines <span class="{if $checkInstalledCart['/override/classes/Cart.php']['60']}file_installed{else}file_not_installed{/if}">#60<span>
                    {else}
                        {l s='Copy entire file' mod='attributewizard'}
                    {/if}                   
                    
                </div>
            </div>
           

            <div class="extra_instructions">
                <span class="important_alert"> </span>
                <span class="important_instructions important"> 
                    {l s='Make sure to clear the cache in Advanced Parameteres->Performance->Clear Cache.' mod='attributewizard'}
                </span>
            </div>
        </div>
    </div>
     
    <div class="extra_line"></div>

    <div class="hook_instructions single_column">
        <div class="panel_header">
            <div class="hook_title panel_title">
                {l s='Dedicated Hook (Optional)' mod='attributewizard'}
            </div>
            <div class="panel_info_text">
                <span class="simple_alert"> </span>
                {l s='if you wish to diplay the wizard in a different location on the product page ' mod='attributewizard'}
            </div>
            <div class="clear"></div>
         </div>
  
        <div class="hook_content">
            <ul>
                <li>
                    <span>{l s='The module can ONLY be hooked in one location, make sure to remove is from productFooter if you used the custom hook.' mod='attributewizard'}</span>
                </li>
                <li>
                    <span>{l s='In /themes/default-bootstrap/product.tpl add' mod='attributewizard'}</span>  <span class="notes">{literal}{hook h="awProduct"}{/literal}</span> <span>{l s='where you want to display the wizard, make sure it`s not inside a ' mod='attributewizard'}&lt;form&gt; {l s='tag.' mod='attributewizard'}</span>
                </li>
            </ul>
        </div>
    </div>
                
    <div class="special_instructions single_column">
        <div class="special_instructions_header">
                {l s='Adding Attribute Wizard to Quick View' mod='attributewizard'}
        </div>
        <div class="special_instructions_content">
            <ul>
                <li>
                    <span>{l s='To add Attribute Wizard to Quick View you need to install the custom hook ' mod='attributewizard'}{literal}{hook h="awProduct"}{/literal}</span>
                    
                </li>
                <li>
                    <span>{l s='Then place the custom hook inside the file ' mod='attributewizard'}/themes/classic/templates/catalog/_partials/quickview.tpl</span>
                </li>
                          
            </ul>
        </div>            
    </div>         
    <div class="special_instructions single_column">
        <div class="special_instructions_header">
                {l s='Adding attributes is done exactly like PrestaShop structure' mod='attributewizard'}
        </div>
        <div class="special_instructions_content">
            <ul>
                <li>
                    <span>{l s='To add attributes, use the regular combinations tab.' mod='attributewizard'}</span>
                    <br/>
                    <span class="important_alert"> </span>
                    <span class="important">
                        {l s='Use the attribute combination generator.' mod='attributewizard'}
                    </span>
                </li>
                <li>
                    <span>{l s='Each combination must contain one attribute value from a single attribute group.' mod='attributewizard'}</span>
                </li>
                <li>
                    <span>{l s='To enable show / hide functionality you need to delete the unavailable product combinations or to set the unavailable product combinations to out of stock.' mod='attributewizard'}</span>
                </li>                
            </ul>
        </div>            
    </div>
                
   
</div>