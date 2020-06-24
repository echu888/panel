<?php 
namespace SignIn {
session_start();
// ALL pages will include this page.  
// if the session is not set, then the user wil be redirected to LOGIN, with a redirect back to the original page
//  - at SIGNIN, the user will verified via Google account.  if successful, login details are saved in a session variable

// this file can be included by action_* as well as all pother pages
// TODO on action pages, directly print results.  
// TODO for all other pages, we can use console.log?

//require_once 'debug.php';
//print( "authenticate.php: session variable contains [ ");
//print_r( $_SESSION );
//print( "]<br/>");
//echo "This is session save path : " . session_save_path() . "</br>";
//echo "This is session id: " . session_id() . "</br>";



$verified = isset( $_SESSION[ 'verified' ] )
              && ( $_SESSION[ 'verified' ] == true );

//MSG( 'authenticate.php: verified is ' . ( $verified ? 'true' : 'false' ) . "\n" );

if ( $verified == false ) {
  header( 'Location: signin.php' );
  exit( 0 );
} 

function getAuthorizedImage()    { print( $_SESSION[ 'picture'  ] ); }
function getAuthorizedName()     { print( $_SESSION[ 'name'     ] ); }
function getAuthorizedEmail()    { print( $_SESSION[ 'email'    ] ); }
function getAuthorizedStaffId()  { print( $_SESSION[ 'staff_id' ] ); }

}
?>
