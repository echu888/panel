<meta name="google-signin-client_id" content="330113539831-caf2itcjuf6gamro05pq27il51srfgr0.apps.googleusercontent.com">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#e25f00">
<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
<link rel="icon" href="images/favicon.png" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="signin.css">

<?php
// Login mechanism:
//  
//   1. signin.php       : use Google Authentication via Javascript 
//
//   2. action_login.php : server asynchronosly verifies that the Google Authentication was valid
//                       : login credentials are stored in a session
//
//   3. authenticate.php : every page and AJAX call includes this file which will check the saved 
//                         login credentials.  if invalid, the user will be redirected to signin.php
//
//   4. signout.php      : removes credentials by clearing the session variable               
//
?>

<div id='mainInfoBox' class="infoBox">
     <h1>The Centre Panel</h1>
     <img src="images/panel.jpg">

     <div id='process'>

          <h2> Pardon me, but ... <br/><br/>
               you'll need to sign in first </h2>

          <!--div class="g-signin2" 
               data-width="240" 
               data-height="50" 
               data-longtitle="true" data-onsuccess="signIn" > </div-->

          <div id="signInButton"></div>

     </div>

     <div id='result'> </div>
</div>





<script>
 
  function checkServer( googleUser ) 
  {
    var id_token = googleUser.getAuthResponse().id_token;
    var xhr = new XMLHttpRequest();
    xhr.open( 'POST', 'action_login.php');
    xhr.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        console.log( 'Signed in as: ' + xhr.responseText );
        var process = document.getElementById( 'process' );
        process.style.display= 'none';

        var result  = document.getElementById( 'result' );
        var response = JSON.parse( xhr.responseText );
        result.innerHTML = response.message;
        if ( response.success ) {
            var redirectTarget = 'index.php';
            setTimeout( function() { window.location.href = redirectTarget; }, 1700 ); 
        }
    }; 

    xhr.send( 'id_token=' + id_token );
  }


  function renderButton() {
      gapi.signin2.render('signInButton', {
        'scope': 'profile email',
        'width': 240,
        'height': 50,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': signIn,
        'onfailure': onFailure
      });
  }
    
  function signIn(googleUser) 
  {
    var profile = googleUser.getBasicProfile();
    checkServer( googleUser );
  }

  function onFailure(error) {
    console.log(error);
  }

</script>

<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>

