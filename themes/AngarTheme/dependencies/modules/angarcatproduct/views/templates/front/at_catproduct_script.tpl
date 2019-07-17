{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{if $page.page_name == 'index'}
<script>
	$(document).ready(function() {
		{foreach from=$catProducts item=catProduct name=atCatProducts}
			$('#bxslider_{$catProduct.id|escape:'htmlall':'UTF-8'}').bxSlider({
				auto: {if $auto == 0}false{else}true{/if},
				minSlides: {$visible|escape:'htmlall':'UTF-8'},
				maxSlides: {$visible|escape:'htmlall':'UTF-8'},
				mode: 'vertical',
				pager: false,
				pause: 3000,
				nextSelector: '#home-next_{$catProduct.id|escape:'htmlall':'UTF-8'}',
				prevSelector: '#home-prev_{$catProduct.id|escape:'htmlall':'UTF-8'}',
				nextText: '>',
				prevText: '<',
				moveSlides: {$move|escape:'htmlall':'UTF-8'},
				infiniteLoop: {if $loop == 0}false{else}true{/if},
				hideControlOnEnd: true,
				useCSS: false,
			});
		{/foreach}
	});
</script>
{/if}