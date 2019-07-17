{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{if $infos|@count > 0}
<!-- MODULE Block cmsinfo -->
<div id="angarinfo_desc">
	{foreach from=$infos item=info2}
		<div class="desc_block">{$info2.text nofilter} {*HTML CONTENT*}</div>
	{/foreach}
</div>
<!-- /MODULE Block cmsinfo -->
{/if}
