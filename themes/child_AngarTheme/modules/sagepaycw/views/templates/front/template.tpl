
{capture name=path}{lcw s='Payment' mod='sagepaycw'}{/capture}

<h2>{lcw s='Payment' mod='sagepaycw'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

$$$PAYMENT ZONE$$$