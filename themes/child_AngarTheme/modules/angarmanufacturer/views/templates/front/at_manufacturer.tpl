{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}


<div id="home_man">

	<div class="man_title">
		<a href="{$link->getPageLink('manufacturer')|escape:'html'}" title="{l s='Featured manufacturers' mod='angarmanufacturer'}"><span>{l s='Featured manufacturers' mod='angarmanufacturer'}</span></a>
		<div id="next_man" class="slider-btn"></div>
		<div id="prev_man" class="slider-btn"></div>
	</div>

	<ul id="bx_manufacturer">
	{foreach from=$homeManufacturers item=homeManufacturer name=atHomeMan}
		<li>
			<a  title="{$homeManufacturer.name|escape:'htmlall':'UTF-8'}" href="{$link->getManufacturerLink({$homeManufacturer.id},{$homeManufacturer.link_rewrite})|escape:'html':'UTF-8'}">
				<img class="lazy" data-src="{$urls.img_manu_url|escape:'html':'UTF-8'}{$homeManufacturer.image|escape:'html':'UTF-8'}" alt="{$homeManufacturer.name|escape:'htmlall':'UTF-8'}" />
			</a>
		</li>
	{/foreach}
	</ul>

<div class="clearfix"></div>

</div>