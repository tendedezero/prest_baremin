{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{if $homecat_id}
	<div id="home_categories"> 
		{if $show_title == 1}
			<div class="homecat_title"><span>{l s='Featured categories' mod='angarhomecat'}</span></div>
		{/if}

		<ul class="row"> 
			{foreach from=$catProducts item=catProduct name=atHomeCat}
				<li class="cat_block col-xs-12 col-sm-4 col-md-3">
					<div class="cat-container">

						{if $show_img == 1}
						<div class="catimg_container ">
								<a href="{$link->getCategoryLink({$catProduct.id|escape:'htmlall':'UTF-8'},{$catProduct.link_rewrite})|escape:'html':'UTF-8'}"  title="{$catProduct.name|escape:'htmlall':'UTF-8'}">
								<img class="replace-2x lazy" data-src="{$link->getCatImageLink($catProduct.link_rewrite, $catProduct.id_image, 'category_default')|escape:'html':'UTF-8'}" alt="{$catProduct.name|escape:'html':'UTF-8'}" />
								</a>
						</div>
						{/if}

						<div class="catlinks_container">
							<div class="homecat_name">
								<a href="{$link->getCategoryLink({$catProduct.id|escape:'htmlall':'UTF-8'},{$catProduct.link_rewrite})|escape:'html':'UTF-8'}" title="{$catProduct.name|escape:'htmlall':'UTF-8'}">
									{$catProduct.name|escape:'htmlall':'UTF-8'}
								</a>
								<span></span>
							</div>

							<ul class="sub_categories">
							{foreach from=$catProduct.sub_cat item=subcat name=foo}
								{if $smarty.foreach.foo.index == $subcat_limit}{break}{/if}
								 <li><a href="{$link->getCategoryLink({$subcat.id_category|escape:'htmlall':'UTF-8'},{$subcat.link_rewrite})|escape:'html':'UTF-8'}">{$subcat.name|escape:'htmlall':'UTF-8'}</a></li>
							{/foreach}
							</ul>

							{if $show_btn == 1}
								<a class="view_more" title="{$catProduct.name|escape:'htmlall':'UTF-8'}" href="{$link->getCategoryLink({$catProduct.id|escape:'htmlall':'UTF-8'},{$catProduct.link_rewrite})|escape:'html':'UTF-8'}">{l s='See more' mod='angarhomecat'}</a>
							{/if}
						</div>

						<div class="clearfix"></div>

					</div>
				</li>
			{/foreach}	
		</ul>

		<div class="clearfix"></div>

	</div>
{/if}