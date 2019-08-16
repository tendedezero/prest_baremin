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
<div class="panel po_main_content" id="copy_attributes">
    
    <div class="panel_header">
        <div class="panel_title">{l s='Copy Attributes' mod='attributewizard'}</div>
        <div class="panel_info_text">
            <span class="simple_alert"> </span>
            {l s='This tool allows you to copy All attributes from one product to another (or to all products in a category, manufacturer or supplier)' mod='attributewizard'}
        </div>
        <div class="clear"></div>
    </div>
     <div class="two_columns">
        <div class="columns">
            <div class="left_column">
                {l s='Source (Product ID)' mod='attributewizard'}
            </div>
            <div class="right_column">
                <input type="text" id="aw_copy_src" name="aw_copy_src" />
            </div>
        </div>
            
        <div class="columns">
            <div class="left_column">
                {l s='Target Type' mod='attributewizard'}
            </div>
            <div class="right_column">
                <select name="aw_copy_tgt_type" id="aw_copy_tgt_type">
                    <option value="p">{l s='Product' mod='attributewizard'}</option>
                    <option value="c">{l s='Category' mod='attributewizard'}</option>
                    <option value="m">{l s='Manufacturer' mod='attributewizard'}</option>
                    <option value="s">{l s='Supplier' mod='attributewizard'}</option>
                </select>               
               
                
            </div>
        </div>
         <div class="columns">
            <div class="left_column">
                {l s='Target (ID)' mod='attributewizard'}
            </div>
            <div class="right_column">
                <input type="text" name="aw_copy_tgt" id="aw_copy_tgt" value="">
            </div>
        </div>
                
        <div class="columns">
            <div class="left_column">               
            </div>
            <div class="right_column">
                <input class="submit_button" type="button" id="aw_copy_validate" value="{l s='Confirm' mod='attributewizard'}">
            </div>
        </div>
        <div class="clear"></div>
        <div id="aw_copy_confirmation" class="special_instructions single_column">
        </div>
        <div class="clear"></div>
     </div>
    <div class="clear"></div>
</div>