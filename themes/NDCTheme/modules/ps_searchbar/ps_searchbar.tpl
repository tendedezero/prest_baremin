<div id="_desktop_search_widget" class="col-lg-4 col-md-4 col-sm-12 search-widget hidden-sm-down {if $configuration.is_catalog}catalog_mode_search{/if}">
	<div id="search_widget" class="search_widget" data-search-controller-url="{$search_controller_url}">
		<form method="get" action="{$search_controller_url}">
			<input type="hidden" name="controller" value="search">
			<input type="hidden" name="order" value="product.position.desc">
			<input type="text" name="s" value="{$search_string}" placeholder="{l s='Search our catalog' d='Shop.Theme.Catalog'}" aria-label="{l s='Search' d='Shop.Theme.Catalog'}">
			<button type="submit">
				<i class="material-icons search">&#xE8B6;</i>
				<span class="hidden-xl-down">{l s='Search' d='Shop.Theme.Catalog'}</span>
			</button>
		</form>
	</div>
</div>