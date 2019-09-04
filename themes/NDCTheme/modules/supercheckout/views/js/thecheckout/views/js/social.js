/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Software License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 *
 *  @author    Peter Sliacky (Zelarg)
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


// ========= FACEBOOK =========
var tc_facebookLogin = (function () {

  var customBtnId = 'tc-facebook-signin';

  function init() {
    return attachCustomBtn();
  }

  function attachCustomBtn() {
    document.getElementById(customBtnId).addEventListener('click', function () {
      FB.login(statusChangeCallback, {scope: 'email,public_profile', return_scopes: true});
    }, false);
    document.getElementById(customBtnId).classList.add('enabled');
  }

  function backendSignIn(access_token) {
    $.ajax({
      type: 'POST',
      cache: false,
      dataType: "json",
      data: "&ajax_request=1&action=socialLoginFacebook" +
        "&access_token=" + access_token +
        "&static_token=" + prestashop.static_token,
      success: function (jsonData) {
        if (jsonData.hasErrors) {
          // TODO: better error handling
          console.error(jsonData.errors);
        } else if ('undefined' !== typeof jsonData.email && jsonData.email) {
          signedInUpdateForm();
        }
      }
    });
  }

  function statusChangeCallback(response) {
    // The response object is returned with a status field that lets the app 	know 		the current login status of the person.
    if (response.status === 'connected') {
      backendSignIn(response.authResponse.accessToken);
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
    } else {
      // The person is not logged into Facebook, so we're not sure if they are logged into this app or not.
    }
  }

  return {
    init: init,
  };
}());


// ========= GOOGLE =========
var tc_googleLogin = (function () {

  var customBtnId = 'tc-google-signin';

  function init(google_client_id) {
    gapi.load('auth2', function () {
      auth2 = gapi.auth2.init({
        client_id: google_client_id,
        fetch_basic_profile: true,
        scope: 'email'
      });

      attachCustomBtn(document.getElementById(customBtnId));
    });
  }

  function attachCustomBtn(element) {
    if (element) {
      auth2.attachClickHandler(element, {},
        function (googleUser) {
          onSignIn(googleUser);
        }, function (error) {
          console.error(JSON.stringify(error, undefined, 2));
        });
      document.getElementById(customBtnId).classList.add('enabled');
    }
  }

  function onSignIn(googleUser) {
    var id_token = googleUser.getAuthResponse().id_token;
    var givenName = ' ';
    var familyName = ' ';
    if ('undefined' !== typeof googleUser.getBasicProfile) {
      var basicProfile = googleUser.getBasicProfile();
      if (('undefined' !== typeof basicProfile.getGivenName)) {
        givenName = basicProfile.getGivenName()
      }
      if (('undefined' !== typeof basicProfile.getFamilyName)) {
        familyName = basicProfile.getFamilyName()
      }
    }

    backendSignIn(id_token, givenName, familyName);
  }

  function backendSignIn(id_token, firstName, lastName) {
    $.ajax({
      type: 'POST',
      cache: false,
      dataType: "json",
      data: "&ajax_request=1&action=socialLoginGoogle" +
        "&id_token=" + id_token +
        "&firstname=" + firstName +
        "&lastname=" + lastName +
        "&static_token=" + prestashop.static_token,
      success: function (jsonData) {
        if (jsonData.hasErrors) {
          // TODO: better error handling
          console.error(jsonData.errors);
        } else if ('undefined' !== typeof jsonData.email && jsonData.email) {
          signedInUpdateForm();
        }
      }
    });
  }

  return {
    init: init,
  }
}());
