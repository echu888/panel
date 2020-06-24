<?
session_start();
unset( $_SESSION[ 'signedIn' ] );
unset( $_SESSION[ 'sub' ] );
unset( $_SESSION[ 'name' ] );
unset( $_SESSION[ 'email' ] );
unset( $_SESSION[ 'hd' ] );
session_destroy() ;
?>

<meta name="google-signin-client_id" content="330113539831-caf2itcjuf6gamro05pq27il51srfgr0.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js" async defer></script>
<link rel="stylesheet" type="text/css" href="../css/signin.css">

<script>
window.onLoadCallback = function(){
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
      window.location.href( "../auth/signin.php" );
    });
}
</script>

<div class="infoBox">
  <h1> You've signed out.</h1>
  <h2>  Now, back to the <a href="../auth/signin.php">sign-in page</a>! </h2>
</div>

