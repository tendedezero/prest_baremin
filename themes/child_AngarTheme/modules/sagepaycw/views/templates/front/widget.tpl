{extends file='page.tpl'}

{block name='page_content'}
   {capture name=path}{lcw s='Payment' mod='sagepaycw'}{/capture}

	<h1 class="page-heading">{lcw s='Payment' mod='sagepaycw'}</h1>

	<div class="sagepaycw-widget">{$widget nofilter}</div>
{/block}



