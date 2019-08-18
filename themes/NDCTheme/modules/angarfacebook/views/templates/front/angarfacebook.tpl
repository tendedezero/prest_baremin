{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{if $facebookurl != ''}
<div id="likebox_content">
	<h4>{l s='Follow us on Facebook' mod='angarfacebook'}</h4>
	<div class="likebox_tab"></div>
	<div class="fb-page" data-href="{$facebookurl|escape:'html':'UTF-8'}" data-width="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="{$facebookurl|escape:'html':'UTF-8'}"><a href="{$facebookurl|escape:'html':'UTF-8'}">Facebook</a></blockquote></div></div>
</div>
{/if}
