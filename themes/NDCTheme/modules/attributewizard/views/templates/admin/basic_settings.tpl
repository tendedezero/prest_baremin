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
<script type="text/javascript">
    var baseDir = '{$module_dir|escape:'htmlall':'UTF-8'}/';
    var id_lang = '{$id_lang|intval}';
    var id_employee = '{$id_employee|intval}';
    
    var total_groups = '{$ordered_groups|count|intval}';
</script>



<div class="panel po_main_content" id="basic_settings">
    <form action="{$request_uri nofilter}" method="post">
        <div class="panel_header">
            <div class="panel_title">{l s='Basic Settings' mod='attributewizard'}</div>
            <div class="panel_info_text">
                <span class="simple_alert"> </span>
                {l s='You must click on Update for a change to take effect' mod='attributewizard'}
            </div>
            <div class="clear"></div>
        </div>
        <div class="two_columns">
            <div class="columns">
                <div class="left_column">
                    {l s='Display Wizard' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <input type="radio" name="aw_display_wizard" value="1" {if $aw_display_wizard == 1}checked{/if}/>
                    <span style="">{l s='For all products' mod='attributewizard'}</span>
                    <br/><br/>
                    <input type="radio" name="aw_display_wizard" value="0" {if $aw_display_wizard != 1}checked{/if}/>
                    <span style="">{l s='Only when' mod='attributewizard'}</span>
                 
                </div>
            </div>
                    
            <div class="columns">
                <div class="left_column">
                </div>
                <div class="right_column">
                   <select name="aw_display_wizard_field">
                        <option value="Reference" {if $aw_display_wizard_field == 'Reference'}selected{/if}>{l s='Reference' mod='attributewizard'}</option>
                        <option value="Supplier Reference" {if $aw_display_wizard_field == 'Supplier Reference'}selected{/if}>{l s='Supplier Reference' mod='attributewizard'}</option>
                        <option value="EAN13" {if $aw_display_wizard_field == 'EAN13'}selected{/if}>{l s='EAN13' mod='attributewizard'}</option>
                        <option value="UPC" {if $aw_display_wizard_field == 'UPC'}selected{/if}>{l s='UPC' mod='attributewizard'}</option>
                        <option value="Location" {if $aw_display_wizard_field == 'Location'}selected{/if}>{l s='Location' mod='attributewizard'}</option>
                    </select>
                 
                </div>
            </div>
              
            <div class="columns">
                <div class="left_column">
                </div>
                <div class="right_column">
                    <span class="aw_display_wizard_value_label">{l s='Is set to: ' mod='attributewizard'}</span>
                   <input type="text"  name="aw_display_wizard_value" id="aw_display_wizard_value" value="{$aw_display_wizard_value|escape:'htmlall':'UTF-8'}">
                 
                </div>
            </div>
                    
            <div class="columns">
                <div class="left_column">
                    {l s='Wizard Hook' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <select id="hook_option" name="hook_option">
                        {foreach $available_hooks as $hook}
                            <option value="{$hook|escape:'htmlall':'UTF-8'}" {if $hook_option == $hook}selected{/if}>{$hook|escape:'htmlall':'UTF-8'}</option>
                        {/foreach}			
                    </select>
                </div>
            </div>
                
            <div class="columns">
                <div class="left_column">
                    {l s='Wizard Location' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <input type="radio" id="aw_in_page" name="aw_popup" value="0" {if $aw_popup != 1}checked{/if}/>
                    <span style="">{l s='In Page:' mod='attributewizard'}</span>
                    <br/><br/>
                    <input type="radio" id="aw_in_popup" name="aw_popup" value="1" {if $aw_popup == 1}checked{/if}/>
                    <span style="">{l s='In Popup:' mod='attributewizard'}</span>
                 
                </div>
            </div>    
                
            <div class="columns popup_config">
                <div class="left_column">
                    {l s='Fade Background' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <input type="checkbox" name="aw_fade" id="aw_fade" value="1" {if $aw_fade == 1}checked{/if}/>                        
                </div>
            </div>    
            <div class="columns popup_config">
                <div class="left_column">
                    {l s='Opacity' mod='attributewizard'}
                </div>
                <div class="right_column">
                     <input type="text" name="aw_opacity" id="aw_opacity" value="{$aw_opacity|floatval}" />
                     0-100                      
                </div>
            </div>   
                
            <div class="columns popup_config">
                <div class="left_column">
                    {l s='Width' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <input type="text" name="aw_popup_width" id="aw_popup_width" value="{$aw_popup_width|intval}" />                      
                </div>
            </div> 
                
            <div class="columns popup_config">
                <div class="left_column">
                    {l s='Top position' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <input type="text" name="aw_popup_top"  id="aw_popup_top" value="{$aw_popup_top|intval}" />     
                </div>
            </div>  
            <div class="columns popup_config">
                <div class="left_column">
                    {l s='Left position' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <input type="text" name="aw_popup_left" id="aw_popup_left" value="{$aw_popup_left|intval}" />
                    <br/>
                    {l s='The default is center, to change enter a value like 100 or -100' mod='attributewizard'}
                </div>
            </div>     
                
                
            
            <div class="columns">
                <div class="left_column">
                    {l s='Group Image' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <input onclick="update_image_resize()" type="checkbox" name="aw_image_resize" id="aw_image_resize" value="1" {if $aw_image_resize == 1}checked{/if}/>
                    <span class="aw_image_resize_width_label">
                    {l s='Resize on upload, max width' mod='attributewizard'}
                    </span>
                    <input onblur="update_image_resize()" type="text" name="aw_image_resize_width" id="aw_image_resize_width" value="{$aw_width|intval}" />
                </div>
            </div>  
                    
            <div class="columns">
                <div class="left_column">
                    {l s='Add to cart display' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <input type="radio" name="aw_add_to_cart" value="" {if !$aw_add_to_cart}checked{/if}/>
                    {l s='No Change ' mod='attributewizard'}
                    <br/>
                    <input type="radio" name="aw_add_to_cart" value="bottom" {if $aw_add_to_cart == 'bottom'}checked{/if}/>
                    {l s='Add to Bottom ' mod='attributewizard'}
                    <br/>
                    <input type="radio" name="aw_add_to_cart" value="scroll" {if $aw_add_to_cart == 'scroll'}checked{/if}/>
                    {l s='Scroll Existing' mod='attributewizard'}
                    <br/>
                    <input type="radio" name="aw_add_to_cart" value="both" {if $aw_add_to_cart == 'both'}checked{/if}/>
                   {l s='Both' mod='attributewizard'}
                </div>
            </div>
            <div class="columns">
                <div class="left_column">
                    {l s='Add to cart button' mod='attributewizard'}
                </div>
                <div class="right_column">
                    {l s='Display additional button when more than' mod='attributewizard'}
                    <input type="text" name="aw_second_add" id="aw_second_add" value="{$aw_second_add|escape:'htmlall':'UTF-8'}" />
                    {l s='attribute groups are used' mod='attributewizard'}
                    <br />
                    <input type="checkbox" name="aw_no_customize" value="1" {if $aw_no_customize == 1}checked{/if}/>
                    {l s='Do not replace with Customize (In page)' mod='attributewizard'}
                   
                </div>
            </div>  
            <div class="columns">
                <div class="left_column">
                    {l s='Price Impact Display' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <input type="radio" name="aw_pi_display" value="" {if !$aw_pi_display}checked{/if}/>
                    {l s='None' mod='attributewizard'}
                    <br/>
                    {*<input type="radio" name="aw_pi_display" value="diff" {if $aw_pi_display == 'diff'}checked{/if}/>
                    {l s='Difference' mod='attributewizard'}
                    <br/>*}
                    <input type="radio" name="aw_pi_display" value="total" {if $aw_pi_display == 'total'}checked{/if}/>
                    {l s='Total' mod='attributewizard'}
                </div>
            </div>        
            <div class="columns">
                <div class="left_column">
                    {l s='Unavailable / Out of Stock' mod='attributewizard'}
                </div>
                <div class="right_column">
                    <input type="radio" name="aw_unavailable_type" value="2" {if $aw_unavailable_type == 2}checked{/if}/>
                    {l s='No Change' mod='attributewizard'}
                    <br/>
                    <input type="radio" name="aw_unavailable_type" value="0" {if !$aw_unavailable_type}checked{/if}/>
                    {l s='Disable' mod='attributewizard'}
                    <br/>
                    <input type="radio" name="aw_unavailable_type" value="1" {if $aw_unavailable_type == 1}checked{/if}/>
                    {l s='Hide' mod='attributewizard'}
                    <br/>
                    <input type="checkbox" name="aw_first_show_all" id="aw_first_show_all" value="1" {if $aw_first_show_all == 1}checked{/if}/>
                    {l s='Show all on first page load' mod='attributewizard'}
                </div>
            </div> 
                
           
            <div class="columns">
                <div class="left_column">
                     <input type="submit" value="{l s='Update' mod='attributewizard'}" name="submitChanges" class="submit_button" />
                </div>
                <div class="right_column">
                    <input onclick="return confirm(aw_confirm_reset)" type="submit" value="{l s='Reset Settings' mod='attributewizard'}" name="resetData" class="submit_button" />
                </div>
            </div>
                
                
        </div>
        <div class="clear"></div>
        
    </form>
</div>