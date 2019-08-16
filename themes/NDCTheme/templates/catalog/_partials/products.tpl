<div id="js-product-list">
    {block name='pagination'}
        {include file='_partials/pagination.tpl' pagination=$listing.pagination}
    {/block}

    <div class="products row">
        {foreach from=$listing.products item="product"}
            {block name='product_miniature'}
                {include file='catalog/_partials/miniatures/product.tpl' product=$product}
            {/block}
        {/foreach}
    </div>



    {* AngarThemes *}
    <div class="hidden-md-up text-xs-right up">
        <a href="#header" class="btn btn-secondary back_to_top">
            {l s='Back to top' d='Shop.Theme.Actions'}
            <i class="material-icons">&#xE316;</i>
        </a>
    </div>
</div>



