{extends file='parent:/_partials/header.tpl'}

{block name='header_banner'}

  <div class="header-banner">
    {hook h='displayBanner'}
   {hook h='displayMegaMenu'}
      <div id="rwd_menu" class="hidden-md-up ndc">
          <div class="container">
              <div id="search-icon" class="ndc_mobile_icons"><i class="material-icons search">&#xE8B6;</i></div>
              <div id="user-icon" class="ndc_mobile_icons"><i class="material-icons logged">&#xE7FF;</i></div>
              <div id="_mobile_cart" class="ndc_mobile_icons"></div>
              <div id="ndc-mobile_logo" class="ndc_mobile_menu"><img src="/img/logo-menu-mobile.png"/></div>

          </div>
          <div class="clearfix"></div>
      </div>

      <div class="container">
          <div id="mobile_search_wrapper" class="rwd_menu_open hidden-md-up" style="display:none;">
              <div id="_mobile_search_widget"></div>
          </div>

          <div id="mobile_user_wrapper" class="rwd_menu_open hidden-md-up" style="display:none;">
              <div id="_mobile_user_info"></div>
          </div>
      </div>
  </div>

   
{/block}

{block name='header_nav'}
  <nav class="header-nav">
    <div class="container">
      <div class="row">
          <div class="col-md-12 col-xs-12">
            {hook h='displayNav1'}
  
            {hook h='displayNav2'}
         
          </div>
      </div>
    </div>
  </nav>
{/block}


{block name='header_top'}
  <div class="header-top">
    <div class="container">
	  <div class="row">
		<a href="{$urls.base_url}" class="col-md-4 hidden-sm-down2" id="_desktop_logo">
			<img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">
		</a>
		{hook h='displayTop'}
		<div class="clearfix"></div>
	  </div>
    </div>

	
  {hook h='displayNavFullWidth'}
{/block}