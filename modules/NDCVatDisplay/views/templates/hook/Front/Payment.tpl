{*
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2015 PrestaShop SA
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 *
 *
 * @author    R Paterson
 * @copyright  Copyright (c) NDC
*}
<script type="text/javascript" language="javascript">
    var of_ssid = '315-1';
    var cs = '{$total}';
    var oi = '{$orderno}';
    var it = '{$products}';
    var pr = '';
    var nc = 'newcustomer';
    var id = ''
    var pairs = document.cookie.split("; "),
        count = pairs.length, parts;
    while (count--) {
        parts = pairs[count].split("=");
        if (parts[0] === "user" && parts[1] === "usr")
            id = parts[2];
        {literal}
    });
    {/literal}
</script>
<script type="text/javascript" language="javascript" src="https://tracking.onefeed.co.uk/t.js"></script>
