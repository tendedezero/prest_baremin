{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<style>
{* Css *}
body {
background-color: {$css2|escape:'htmlall':'UTF-8'};
font-family: "{$css59|escape:'htmlall':'UTF-8'|replace:'_':' '}", Arial, Helvetica, sans-serif;
}

.products .product-miniature .product-title {
height: {$css26|escape:'htmlall':'UTF-8'}px;
}

.products .product-miniature .product-title a {
font-size: {$css27|escape:'htmlall':'UTF-8'}px;
line-height: {$css28|escape:'htmlall':'UTF-8'}px;
}

#content-wrapper .products .product-miniature .product-desc {
height: {$css61|escape:'htmlall':'UTF-8'}px;
}

@media (min-width: 991px) {
#home_categories ul li .cat-container {
min-height: {$css56|escape:'htmlall':'UTF-8'}px;
}
}

{* Logo *}
@media (min-width: 768px) {
a#_desktop_logo {
padding-top: {$css44|escape:'htmlall':'UTF-8'}px;
padding-bottom: {$css60|escape:'htmlall':'UTF-8'}px;
}
}

{* Colors *}
{* Nav *}
nav.header-nav {
background: {$color1|escape:'htmlall':'UTF-8'};
}

nav.header-nav,
.header_sep2 #contact-link span.shop-phone,
.header_sep2 #contact-link span.shop-phone.shop-tel,
.header_sep2 #contact-link span.shop-phone:last-child,
.header_sep2 .lang_currency_top,
.header_sep2 .lang_currency_top:last-child,
.header_sep2 #_desktop_currency_selector,
.header_sep2 #_desktop_language_selector,
.header_sep2 #_desktop_user_info {
border-color: {$color2|escape:'htmlall':'UTF-8'};
}

#contact-link,
#contact-link a,
.lang_currency_top span.lang_currency_text,
.lang_currency_top .dropdown i.expand-more,
nav.header-nav .user-info span,
nav.header-nav .user-info a.logout,
#languages-block-top div.current,
nav.header-nav a{
color: {$color3|escape:'htmlall':'UTF-8'};
}

#contact-link span.shop-phone strong,
#contact-link span.shop-phone strong a,
.lang_currency_top span.expand-more,
nav.header-nav .user-info a.account {
color: {$color4|escape:'htmlall':'UTF-8'};
}

#contact-link span.shop-phone i {
color: {$color5|escape:'htmlall':'UTF-8'};
}

{* Header *}
.header-top {
background: {$color6|escape:'htmlall':'UTF-8'};
}

div#search_widget form button[type=submit] {
background: {$color7|escape:'htmlall':'UTF-8'};
color: {$color78|escape:'htmlall':'UTF-8'};
}

div#search_widget form button[type=submit]:hover {
background: {$color8|escape:'htmlall':'UTF-8'};
color: {$color79|escape:'htmlall':'UTF-8'};
}

{* Account cart *}
{*
a.account_cart_rwd {
background: {$color59|escape:'htmlall':'UTF-8'};
}
*}

#header div#_desktop_cart .blockcart .header {
background: {$color9|escape:'htmlall':'UTF-8'};
}

#header div#_desktop_cart .blockcart .header a.cart_link {
color: {$color80|escape:'htmlall':'UTF-8'};
}

{* Sticky cart *}
{*
.cart_style2.stickycart_yes.sticky_cart #header div#_desktop_cart .blockcart .header {
background: {$color86|escape:'htmlall':'UTF-8'};
}

.cart_style2.stickycart_yes.sticky_cart #header div#_desktop_cart .blockcart .header a.cart_link span.cart-products-count {
background: {$color87|escape:'htmlall':'UTF-8'};
}

.cart_style2.stickycart_yes.sticky_cart #header div#_desktop_cart .blockcart .header a.cart_link {
color: {$color88|escape:'htmlall':'UTF-8'};
}
*}

{* Slider *}
#homepage-slider .bx-wrapper .bx-pager.bx-default-pager a:hover,
#homepage-slider .bx-wrapper .bx-pager.bx-default-pager a.active{
background: {$color10|escape:'htmlall':'UTF-8'};
}

{* Menu *}
div#rwd_menu {
background: {$color11|escape:'htmlall':'UTF-8'};
}

div#rwd_menu,
div#rwd_menu a {
color: {$color13|escape:'htmlall':'UTF-8'};
}

div#rwd_menu,
div#rwd_menu .rwd_menu_item,
div#rwd_menu .rwd_menu_item:first-child {
border-color: {$color12|escape:'htmlall':'UTF-8'};
}

div#rwd_menu .rwd_menu_item:hover,
div#rwd_menu .rwd_menu_item:focus,
div#rwd_menu .rwd_menu_item a:hover,
div#rwd_menu .rwd_menu_item a:focus {
color: {$color14|escape:'htmlall':'UTF-8'};
background: {$color15|escape:'htmlall':'UTF-8'};
}

#mobile_top_menu_wrapper2 .top-menu li a:hover,
.rwd_menu_open ul.user_info li a:hover {
background: {$color16|escape:'htmlall':'UTF-8'};
color: {$color81|escape:'htmlall':'UTF-8'};
}

#_desktop_top_menu{
background: {$color11|escape:'htmlall':'UTF-8'};
}

#_desktop_top_menu,
#_desktop_top_menu > ul > li,
.menu_sep1 #_desktop_top_menu > ul > li,
.menu_sep1 #_desktop_top_menu > ul > li:last-child,
.menu_sep2 #_desktop_top_menu,
.menu_sep2 #_desktop_top_menu > ul > li,
.menu_sep2 #_desktop_top_menu > ul > li:last-child,
.menu_sep3 #_desktop_top_menu,
.menu_sep4 #_desktop_top_menu,
.menu_sep5 #_desktop_top_menu,
.menu_sep6 #_desktop_top_menu {
border-color: {$color12|escape:'htmlall':'UTF-8'};
}

#_desktop_top_menu > ul > li > a {
color: {$color13|escape:'htmlall':'UTF-8'};
}

#_desktop_top_menu > ul > li:hover > a {
color: {$color14|escape:'htmlall':'UTF-8'};
background: {$color15|escape:'htmlall':'UTF-8'};
}

.submenu1 #_desktop_top_menu .popover.sub-menu ul.top-menu li a:hover {
background: {$color16|escape:'htmlall':'UTF-8'};
color: {$color81|escape:'htmlall':'UTF-8'};
}

{* Featured categories *}
#home_categories .homecat_title span {
border-color: {$color60|escape:'htmlall':'UTF-8'};
}

#home_categories ul li .homecat_name span {
background: {$color61|escape:'htmlall':'UTF-8'};
}

#home_categories ul li a.view_more {
background: {$color62|escape:'htmlall':'UTF-8'};
color: {$color63|escape:'htmlall':'UTF-8'};
border-color: {$color64|escape:'htmlall':'UTF-8'};
}

#home_categories ul li a.view_more:hover {
background: {$color65|escape:'htmlall':'UTF-8'};
color: {$color66|escape:'htmlall':'UTF-8'};
border-color: {$color67|escape:'htmlall':'UTF-8'};
}

{* Columns *}
.columns .text-uppercase a,
.columns .text-uppercase span,
.columns div#_desktop_cart .cart_index_title a,
#home_man_product .catprod_title a span {
border-color: {$color17|escape:'htmlall':'UTF-8'};
}

{* Tabs or blocks *}
#index .tabs ul.nav-tabs li.nav-item a.active,
#index .tabs ul.nav-tabs li.nav-item a:hover,
.index_title a,
.index_title span {
border-color: {$color19|escape:'htmlall':'UTF-8'};
}

{* Product-list *}
a.product-flags span.product-flag.new,
#home_cat_product a.product-flags span.product-flag.new,
#product #content .product-flags li,
#product #content .product-flags .product-flag.new {
background: {$color20|escape:'htmlall':'UTF-8'};
}

.products .product-miniature .product-title a,
#home_cat_product ul li .right-block .name_block a {
color: {$color21|escape:'htmlall':'UTF-8'};
}

.products .product-miniature span.price,
#home_cat_product ul li .product-price-and-shipping .price,
.ui-widget .search_right span.search_price {
color: {$color22|escape:'htmlall':'UTF-8'};
}

.button-container .add-to-cart:hover,
#subcart .cart-buttons .viewcart:hover {
background: {$color26|escape:'htmlall':'UTF-8'};
color: {$color27|escape:'htmlall':'UTF-8'};
border-color: {$color28|escape:'htmlall':'UTF-8'};
}

.button-container .add-to-cart,
.button-container .add-to-cart:disabled,
#subcart .cart-buttons .viewcart {
background: {$color23|escape:'htmlall':'UTF-8'};
color: {$color24|escape:'htmlall':'UTF-8'};
border-color: {$color25|escape:'htmlall':'UTF-8'};
}

{* Products from category *}
#home_cat_product .catprod_title span {
border-color: {$color68|escape:'htmlall':'UTF-8'};
}

{* Featured manufacturers *}
#home_man .man_title span {
border-color: {$color71|escape:'htmlall':'UTF-8'};
}

{* Footer *}
div#angarinfo_block .icon_cms {
color: {$color29|escape:'htmlall':'UTF-8'};
}

.footer-container {
background: {$color30|escape:'htmlall':'UTF-8'};
}

.footer-container,
.footer-container .h3,
.footer-container .links .title,
.row.social_footer {
border-color: {$color31|escape:'htmlall':'UTF-8'};
}

.footer-container .h3 span,
.footer-container .h3 a,
.footer-container .links .title span.h3,
.footer-container .links .title a.h3 {
border-color: {$color32|escape:'htmlall':'UTF-8'};
}

.footer-container,
.footer-container .h3,
.footer-container .links .title .h3,
.footer-container a,
.footer-container li a,
.footer-container .links ul>li a {
color: {$color33|escape:'htmlall':'UTF-8'};
}

.block_newsletter .btn-newsletter {
background: {$color34|escape:'htmlall':'UTF-8'};
color: {$color82|escape:'htmlall':'UTF-8'};
}

.block_newsletter .btn-newsletter:hover {
background: {$color35|escape:'htmlall':'UTF-8'};
color: {$color83|escape:'htmlall':'UTF-8'};
}

.footer-container .bottom-footer {
background: {$color36|escape:'htmlall':'UTF-8'};
border-color: {$color37|escape:'htmlall':'UTF-8'};
color: {$color38|escape:'htmlall':'UTF-8'};
}

{* Product page *}
.product-prices .current-price span.price {
color: {$color39|escape:'htmlall':'UTF-8'};
}

.product-add-to-cart button.btn.add-to-cart:hover {
background: {$color43|escape:'htmlall':'UTF-8'};
color: {$color44|escape:'htmlall':'UTF-8'};
border-color: {$color45|escape:'htmlall':'UTF-8'};
}

.product-add-to-cart button.btn.add-to-cart,
.product-add-to-cart button.btn.add-to-cart:disabled {
background: {$color40|escape:'htmlall':'UTF-8'};
color: {$color41|escape:'htmlall':'UTF-8'};
border-color: {$color42|escape:'htmlall':'UTF-8'};
}

#product .tabs ul.nav-tabs li.nav-item a.active,
#product .tabs ul.nav-tabs li.nav-item a:hover,
#product .index_title span,
.page-product-heading span {
border-color: {$color46|escape:'htmlall':'UTF-8'};
}

{* Other buttons *}
.btn-primary:hover {
background: {$color50|escape:'htmlall':'UTF-8'};
color: {$color51|escape:'htmlall':'UTF-8'};
border-color: {$color52|escape:'htmlall':'UTF-8'};
}

.btn-primary,
.btn-primary.disabled,
.btn-primary:disabled,
.btn-primary.disabled:hover {
background: {$color47|escape:'htmlall':'UTF-8'};
color: {$color48|escape:'htmlall':'UTF-8'};
border-color: {$color49|escape:'htmlall':'UTF-8'};
}

.btn-secondary:hover {
background: {$color56|escape:'htmlall':'UTF-8'};
color: {$color57|escape:'htmlall':'UTF-8'};
border-color: {$color58|escape:'htmlall':'UTF-8'};
}

.btn-secondary,
.btn-secondary.disabled,
.btn-secondary:disabled,
.btn-secondary.disabled:hover {
background: {$color53|escape:'htmlall':'UTF-8'};
color: {$color54|escape:'htmlall':'UTF-8'};
border-color: {$color55|escape:'htmlall':'UTF-8'};
}

{* Other elements *}
.form-control:focus, .input-group.focus {
border-color: {$color72|escape:'htmlall':'UTF-8'};;
outline-color: {$color72|escape:'htmlall':'UTF-8'};;
}

body .pagination .page-list .current a,
body .pagination .page-list a:hover,
body .pagination .page-list .current a.disabled,
body .pagination .page-list .current a.disabled:hover {
color: {$color73|escape:'htmlall':'UTF-8'};
}

.page-my-account #content .links a:hover i {
color: {$color74|escape:'htmlall':'UTF-8'};
}

{* Scroll to top *}
#scroll_top {
background: {$color76|escape:'htmlall':'UTF-8'};
color: {$color84|escape:'htmlall':'UTF-8'};
}

#scroll_top:hover,
#scroll_top:focus {
background: {$color77|escape:'htmlall':'UTF-8'};
color: {$color85|escape:'htmlall':'UTF-8'};
}

</style>