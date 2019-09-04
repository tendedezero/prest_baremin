{**
* NOTICE OF LICENSE
*
* This source file is subject to the Software License Agreement
* that is bundled with this package in the file LICENSE.txt.
*
*  @author    Peter Sliacky (Zelarg)
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*}

<script src="https://apis.google.com/js/platform.js?onload=tc_initGoogle" async defer></script>
{literal}
<script>

  function tc_initGoogle() {
    if ('undefined' !== typeof tc_googleLogin) {
      tc_googleLogin.init('{/literal}{$config->social_login_google_client_id}{literal}');
    }
  }
  //document.addEventListener("DOMContentLoaded", function(event) {
    //jQuery shall be loaded now
  //});

</script>
{/literal}
