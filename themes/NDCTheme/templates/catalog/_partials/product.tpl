{extends file='parent:catalog/product.tpl'}

{block name='page_header_container'}
    {block name='page_header'}

    {/block}
{/block}

{block name='product_cover_thumbnails'}
    <div class=""cold-md-12">
    <h1 class="product_name" itemprop="name">$product.name</h1>
    </div>
    {include file='catalog/_partials/product-cover-thumbnails.tpl'}
{/block}

{block name='product_reference_top'}
    <div id="product-info-right">
    {include file='catalog/_partials/product-reference-top.tpl'}
    {if isset($product_manufacturer->id)}
        <div id="product_manufacturer" itemprop="brand" itemscope itemtype="http://schema.org/Brand">
            <label class="label">{l s='Brand' d='Shop.Theme.Catalog'} </label>
            <a class="editable" itemprop="name" href="{$product_brand_url}">{$product_manufacturer->name}</a>
        </div>
    {/if}
    </div>
{/block}


{block name='product_prices'}
    {include file='catalog/_partials/product-prices.tpl'}
{/block}

 {block name='product_tabs'}

              <div class="tabs">
                <ul class="nav nav-tabs" role="tablist">
                  {if $product.description}
                    <li class="nav-item desc_tab">
                       <a
                         class="nav-link{if $product.description} active{/if}"
                         data-toggle="tab"
                         href="#description"
                         role="tab"
                         aria-controls="description"
                         {if $product.description} aria-selected="true"{/if}>{l s='Description' d='Shop.Theme.Catalog'}</a>
                    </li>
                  {/if}
                  <li class="nav-item product_details_tab">
                    <a
                      class="nav-link{if !$product.description} active{/if}"
                      data-toggle="tab"
                      href="#product-details"
                      role="tab"
                      aria-controls="product-details"
                      {if !$product.description} aria-selected="true"{/if}>{l s='Product Details' d='Shop.Theme.Catalog'}</a>
                  </li>
                    {if $product.price < 200}
                    <li class="nav-item product_leasing_tab">
                        <a
                                class="nav-link{if !$product.description} active{/if}"
                                data-toggle="tab"
                                href="#product-leasing"
                                role="tab"
                                aria-controls="product-leasing"
                                {if !$product.description} aria-selected="true"{/if}>{l s='Leasing' d='Shop.Theme.Catalog'}</a>
                    </li>
                    {/if}
                    {if $product.attachments}
                    <li class="nav-item">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#attachments"
                        role="tab"
                        aria-controls="attachments">{l s='Attachments' d='Shop.Theme.Catalog'}</a>
                    </li>
                  {/if}
                  {foreach from=$product.extraContent item=extra key=extraKey}
                    <li class="nav-item extra-time">
                      <a
                        class="nav-link"
                        data-toggle="tab"
                        href="#extra-{$extraKey}"
                        role="tab"
                        aria-controls="extra-{$extraKey}">{$extra.title}</a>
                    </li>
                  {/foreach}

				{* AngarThemes *}
				{hook h='displayProductTab'}

                </ul>




{* AngarTheme *}
                <div class="tab-content" id="tab-content">
                 <div class="tab-pane fade in{if $product.description} active{/if}" id="description" role="tabpanel">
                   {block name='product_description'}
					 {if $product.description}<div class="h5 text-uppercase index_title"><span>{l s='Description' d='Shop.Theme.Catalog'}</span></div>{/if}
                     <div class="product-description">{$product.description nofilter}</div>
                   {/block}
                 </div>

                 {block name='product_details'}
                   {include file='catalog/_partials/product-details.tpl'}
                 {/block}

                 {block name='product_attachments'}
                   {if $product.attachments}
                    <div class="tab-pane fade in" id="attachments" role="tabpanel">
                       <section class="product-attachments">
						 <div class="h5 text-uppercase index_title"><span>{l s='Attachments' d='Shop.Theme.Catalog'}</span></div>
                         <div class="h5 text-uppercase">{l s='Download' d='Shop.Theme.Actions'}</div>
                         {foreach from=$product.attachments item=attachment}
                           <div class="attachment">
                             <h6><a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">{$attachment.name}</a></h6>
                             <p>{$attachment.description}</p>
                             <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                               {l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
                             </a>
                           </div>
                         {/foreach}
                       </section>
                     </div>
                   {/if}
                 {/block}

                 {foreach from=$product.extraContent item=extra key=extraKey}
                 <div class="tab-pane fade in {$extra.attr.class}" id="extra-{$extraKey}" role="tabpanel" {foreach $extra.attr as $key => $val} {$key}="{$val}"{/foreach}>
                   {$extra.content nofilter}
                 </div>
                 {/foreach}

				  {* AngarThemes *}
				  {hook h='displayProductTabContent'}

              </div>  
            </div>

          {/block}
