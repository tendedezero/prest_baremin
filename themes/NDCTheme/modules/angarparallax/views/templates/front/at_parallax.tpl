{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<div id="parallax_baner" {if isset($parallax_img)}  data-background-image="{$parallax_img|escape:'htmlall':'UTF-8'}"{/if} style="background-position: center" class="lazy">

	<div class="parallax_desc">
		{$parallax_desc nofilter} {*HTML CONTENT*}
	</div>

	{if $parallax_link|escape:'htmlall':'UTF-8'}
		<a class="parallax_button" href="{$parallax_link|escape:'htmlall':'UTF-8'}" title="{l s='See more' mod='angarparallax'}">{l s='See more' mod='angarparallax'}</a>
	{/if}

</div>
