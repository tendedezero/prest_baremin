{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{* AngarThemes *}

{extends file='page.tpl'}

    {block name='page_content_container'}
      <section id="content" class="page-home">

		{if $css7 == slider_position_column}
			{if $page.page_name == 'index'}
			<div id="slider_row">
				<div id="top_column">{hook h="displayTopColumn"}</div>
				<div class="clearfix"></div>
			</div>
			{hook h='angarHomeCat'}
			{/if}
		{/if}

        {block name='page_content_top'}{/block}  
    
        {block name='page_content'}
          {assign var='HOOK_HOME_TAB_CONTENT' value=Hook::exec('displayHomeTabContent')}
          {assign var='HOOK_HOME_TAB' value=Hook::exec('displayHomeTab')}
          {if isset($HOOK_HOME_TAB_CONTENT) && $HOOK_HOME_TAB_CONTENT|trim}
            <div class="tabs">
                {if isset($HOOK_HOME_TAB) && $HOOK_HOME_TAB|trim}
                    <ul id="home-page-tabs" class="nav nav-tabs clearfix">
                        {$HOOK_HOME_TAB nofilter}
                    </ul>
                {/if}
                <div class="tab-content" id="tab-content">{$HOOK_HOME_TAB_CONTENT nofilter}</div>
            </div>
          {/if}

		  {$HOOK_HOME nofilter}

        {/block}
      </section>
    {/block}
