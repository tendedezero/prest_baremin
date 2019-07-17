{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<div id="{$id|escape:'htmlall':'UTF-8'}-response" {if !isset($text)}style="display:none;"{/if} class="message alert alert-{if isset($class) && $class=='error'}danger{else}success{/if}">
	<div>{if isset($text)}{$text|escape:'htmlall':'UTF-8'}{/if}</div>
</div>