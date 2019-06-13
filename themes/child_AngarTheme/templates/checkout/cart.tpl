{extends file='parent:checkout/cart.tpl'}
    {block name='cart_overview'}
            {include file='checkout/_partials/cart-detailed.tpl' cart=$cart}
    {/block}