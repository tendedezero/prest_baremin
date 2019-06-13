{extends file='parent:catalog/_partials/products.tpl'}


      {block name='product_miniature'}
        {include file='catalog/_partials/miniatures/product.tpl' product=$product}
      {/block}
