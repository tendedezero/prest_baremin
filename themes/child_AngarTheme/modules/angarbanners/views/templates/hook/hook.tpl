{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{if isset($htmlitems) && $htmlitems}
<div id="angarbanners_{$hook|escape:'htmlall':'UTF-8'}" class="angarbanners">
	<ul class="clearfix row">
	{foreach name=items from=$htmlitems item=hItem}


{if $hook == 'left' || $hook == 'right'}

	<li class="block">

		<p class="h6 text-uppercase {if $hItem.title_use == 1}banner_title{/if}"><span>{if $hItem.title}{$hItem.title|escape:'htmlall':'UTF-8'}{else}{l s='Banner' mod='angarbanners'}{/if}</span></p>
		
		<div class="block_content">
			{if $hItem.url}
			<a href="{$hItem.url|escape:'htmlall':'UTF-8'}" class="item-link is_stlazyloading"{if $hItem.target == 1} onclick="return !window.open(this.href);"{/if} title="{$hItem.title|escape:'htmlall':'UTF-8'}">
			{/if}

				{if $hItem.image}
					<img src="/img/p/gb-default-home_default.jpg" class="stlazyloading_holder"/>
					<img data-src="{$link->getMediaLink("`$module_dir`views/img/`$hItem.image|escape:'htmlall':'UTF-8'`")}" class="stlazyloadthis item-img {if $hook == 'left' || $hook == 'right'}img-responsive{/if}" title="{$hItem.title|escape:'htmlall':'UTF-8'}" alt="{$hItem.title|escape:'htmlall':'UTF-8'}" width="{if $hItem.image_w}{$hItem.image_w|intval}{else}100%{/if}" height="{if $hItem.image_h}{$hItem.image_h|intval}{else}100%{/if}"/>
				{/if}

				{if $hItem.html}
					<div class="itemhtml_desc">{$hItem.html nofilter} {*HTML CONTENT*}</div>
				{/if}

			{if $hItem.url}
			</a>
			{/if}
		</div>

	</li>

{else}

	<li class="angarbanners-item-{$smarty.foreach.items.iteration|escape:'htmlall':'UTF-8'} {if $hook == 'footer'}col-xs-12{else} col-xs-4{/if}">

		{if $hItem.url}
			<a href="{$hItem.url|escape:'htmlall':'UTF-8'}" class="item-link is_stlazyloading"{if $hItem.target == 1} onclick="return !window.open(this.href);"{/if} title="{$hItem.title|escape:'htmlall':'UTF-8'}">
		{/if}

			{if $hItem.image}
				<img src="/img/p/gb-default-home_default.jpg" class="stlazyloading_holder"/>
				<img data-src="{$link->getMediaLink("`$module_dir`views/img/`$hItem.image|escape:'htmlall':'UTF-8'`")}" class="stlazyloadthis item-img {if $hook == 'left' || $hook == 'right'}img-responsive{/if}" title="{$hItem.title|escape:'htmlall':'UTF-8'}" alt="{$hItem.title|escape:'htmlall':'UTF-8'}" width="{if $hItem.image_w}{$hItem.image_w|intval}{else}100%{/if}" height="{if $hItem.image_h}{$hItem.image_h|intval}{else}100%{/if}"/>
			{/if}

			{if $hItem.title && $hItem.title_use == 1 OR $hItem.html}
				<div class="item_desc">
					<div class="item_desc_position">
						{if $hItem.title && $hItem.title_use == 1}
							<h3 class="item-title">{$hItem.title|escape:'htmlall':'UTF-8'}</h3>
						{/if}
						{if $hItem.html}
							<div class="item-html">
								{$hItem.html nofilter} {*HTML CONTENT*}
							</div>
						{/if}
					</div>
				</div>
			{/if}

		{if $hItem.url}
			</a>
		{/if}

	</li>

{/if}


	{/foreach}
	</ul>
</div>
{/if}
