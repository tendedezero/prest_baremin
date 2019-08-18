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

{extends file=$layout}

{block name='content'}
  <section id="main">

    {block name='brand_header'}
        <div id="brandtop" style="">
            <h1 style="color: #212121; padding-bottom: 10px; text-align: center; text-decoration: underline; text-decoration-color: #434343;">All Brands</h1>
            {*<ul class="alphabet" style="padding-left: 0px;">
                <a href="#a">
                    <li>A</li>
                </a>
                <a href="#b">
                    <li>B</li>
                </a>
                <a href="#c">
                    <li>C</li>
                </a>
                <a href="#d">
                    <li>D</li>
                </a>
                <a href="#e">
                    <li>E</li>
                </a>
                <a href="#f">
                    <li>F</li>
                </a>
                <a href="#g">
                    <li>G</li>
                </a>
                <a href="#h">
                    <li>H</li>
                </a>
                <a href="#i">
                    <li>I</li>
                </a>
                <a href="#j">
                    <li>J</li>
                </a>
                <a href="#k">
                    <li>K</li>
                </a>
                <a href="#l">
                    <li>L</li>
                </a>
                <a href="#m">
                    <li>M</li>
                </a>
                <a href="#n">
                    <li>N</li>
                </a>
                <a href="#o">
                    <li>O</li>
                </a>
                <a href="#p">
                    <li>P</li>
                </a>
                <a href="#r">
                    <li>Q</li>
                </a>
                <a href="#r">
                    <li>R</li>
                </a>
                <a href="#s">
                    <li>S</li>
                </a>
                <a href="#t">
                    <li>T</li>
                </a>
                <a href="#u">
                    <li>U</li>
                </a>
                <a href="#v">
                    <li>V</li>
                </a>
                <a href="#w">
                    <li>W</li>
                </a>
                <a href="#x">
                    <li>X</li>
                </a>
                <a href="#y">
                    <li>Y</li>
                </a>
                <a href="#z">
                    <li>Z</li>
                </a>
            </ul>*}
        </div>
    {/block}
    {block name='brand_miniature'}
      <ul class="manufacturer_list row">
        {foreach from=$brands item=brand}
          {include file='catalog/_partials/miniatures/brand.tpl' brand=$brand}
        {/foreach}
      </ul>
    {/block}

  </section>

{/block}
