{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<!-- Module AngarSlider -->
{if $angarslider.slides}
	<div id="homepage-slider">
		<ul id="angarslider">
            {foreach from=$angarslider.slides item=slide name='angarslider'}
				<li class="angarslider-container">
					<a href="{$slide.url|escape:'html':'UTF-8'}" title="{$slide.legend}">
                        {if $slide.id_slide == 1}
							<img src="{$slide.image_url}" alt="{$slide.legend}" width="100%" height="100%">
                        {else}
							<img data-src="{$slide.image_url}" alt="{$slide.legend}" width="100%" height="100%" class="lazy">
                        {/if}

					</a>
                    {if $slide.description}
						<div class="angarslider-description">{$slide.description nofilter} {*HTML CONTENT*}</div>
                    {/if}
				</li>
            {/foreach}
		</ul>

	</div>
{/if}
