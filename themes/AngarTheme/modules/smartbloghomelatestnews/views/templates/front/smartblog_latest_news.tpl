<div class="home_blog">
<h3 class='index_title'><span>{l s='Latest News' mod='smartblog'}</span></h3>
<div class="products">
	{if isset($view_data) AND !empty($view_data)}
		{foreach from=$view_data item=post}
			{assign var='img_url' value=$smartbloglink->getImageLink($post.link_rewrite, $post.id, 'home-default')}
			<div class="product-miniature col-xs-12 col-sm-4 col-md-4">
				<div class="thumbnail-container">
					<div class="product-image-container">
						{if $img_url != 'false'}
						<a href="{$smartbloglink->getSmartBlogPostLink($post.id,$post.link_rewrite)}">
						<img class="replace-2x img-responsive" src="{$img_url}" alt="{$post.title|escape:'html':'UTF-8'}" title="{$post.title|escape:'html':'UTF-8'}"   itemprop="image" />
						</a>
						{/if}
					</div>
					<h4 class="product-title">
						<a href="{$smartbloglink->getSmartBlogPostLink($post.id,$post.link_rewrite)}">
							{SmartBlogPost::subStr($post.title,60)}
						</a>
					</h4>
					<i class="icon icon-calendar"></i>
					<span class="sds_post_date">{$post.date_added}</span>

				</div>		
			</div>
		{/foreach}
	{/if}
</div>
</div>