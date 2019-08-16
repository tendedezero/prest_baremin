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

{block name='brand_miniature_item'}
<li class="brand col-xs-12 col-sm-4 col-md-3">
	<div class="brand-block">
		<div class="brands img">
			<span class="man_img_spacer"></span>
			<a href="{$brand.url}"><img data-src="{$brand.image}" src="/img/pixel.gif" alt="{$brand.name}" data-src="{$brand.image}" class="zoom lazy"></a>
			{*
				<img src="{$urls.img_manu_url|escape:'html':'UTF-8'}{$brand.id_manufacturer}.jpg" alt="{$homeManufacturer.name|escape:'htmlall':'UTF-8'}" />
			*}
		</div>

		<div class="brand-infos">
			<h3><a href="{$brand.url}">{$brand.name}</a></h3>
			<a href="{$brand.url}">{$brand.nb_products}</a>
		</div>
		
		<div class="brand-products">
			<a class="btn btn-primary" href="{$brand.url}">{l s='View products' d='Shop.Theme.Actions'}</a>
		</div>
	</div>
</li>
{/block}
