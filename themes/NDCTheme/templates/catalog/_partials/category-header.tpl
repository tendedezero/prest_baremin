{**
 * 2007-2018 PrestaShop.
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
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{* AngarTheme *}

<div id="js-product-list-header">
    {if $listing.pagination.items_shown_from == 1}

		<div class="mobile_cat_name text-sm-center text-xs-center hidden-md-up">
		  <div class="h1">{$category.name}</div>
		</div>

		<div id="category_desc" class="hidden-sm-down">
            {if $activeFilters|count}{else}
		  {if $category.image.large.url}
			<div class="category-image col-xs-12 col-sm-2">
			  <img class="img-responsive" src="{$category.image.large.url}" alt="{if !empty($category.image.legend)}{$category.image.legend}{else}{$category.name}{/if}">
			</div>
		  {/if}

		  <div class="category-desc {if $category.image.large.url} col-xs-12 col-sm-10{/if}">
			<h1 class="cat_name">{$category.name}</h1>
			{if $category.description}
			  <div class="cat_desc">{$category.description nofilter}</div>
			{/if}
		  </div>

		  <div class="clearfix"></div>

		</div>

		{if (isset($display_subcategories) && $display_subcategories eq 1) || !isset($display_subcategories) }
			{if $subcategories|count}
				<!-- Subcategories -->
				<div id="subcategories" class="hidden-sm-down">
					<p class="subcategory-heading">{l s='Subcategories' d='Shop.Theme.Mytheme'}</p>
					<ul class="clearfix">
						{foreach from=$subcategories item=subcategory}
							<li>
								<div class="subcategory-image">
									<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
										{if $subcategory.id_image}
											<img class="replace-2x" src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'category_default')|escape:'html':'UTF-8'}" alt="{$subcategory.name|escape:'html':'UTF-8'}"/>
										{else}
											<img class="replace-2x" src="{$img_cat_dir}{$lang_iso}-default-category_default.jpg" alt="{$subcategory.name|escape:'html':'UTF-8'}"/>
										{/if}
									</a>
								</div>
								<h5><a class="subcategory-name" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}">{$subcategory.name|truncate:125:'...'|escape:'html':'UTF-8'}</a></h5>
								{*
								{if $subcategory.description}
									<div class="subcat_desc">{$subcategory.description nofilter}</div>
								{/if}
								*}
							</li>
						{/foreach}
					</ul>
				</div>
			{/if}
		{/if}
            {/if}
    {/if}
</div>