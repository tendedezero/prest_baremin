{extends file='checkout/checkout.tpl'}

{* AngarThemes *}
<div class="container">
  <div class="row">
    {block name='hook_footer_before'}
      {hook h='displayFooterBefore'}
    {/block}
  </div>
</div>

<div class="footer-container">

  <div class="container">
    <div class="row">
      {block name='hook_footer'}
        {hook h='displayFooter'}
      {/block}
    </div>

	<div class="row social_footer">
      {block name='hook_footer_after'}
        {hook h='displayFooterAfter'}
      {/block}
    </div>
  </div>

  <div class="bottom-footer">
      {block name='copyright_link'}
        {l s='%copyright% Copyright %year% %shop_name%. All Rights Reserved.' sprintf=['%shop_name%' => $shop.name|escape:'html':'UTF-8', '%year%' => 'Y'|date, '%copyright%' => '©'] d='Shop.Theme.Global'}
      {/block}
  </div>

</div>