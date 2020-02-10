<?php
$DEFAULT_LANGUAGE = 'en';


if ( isset( $_GET[ 'lang' ] ) ) {
  $lang = $_GET[ 'lang' ];
  $_SESSION[ 'lang' ] = $lang;
  setcookie( 'lang', $lang, time() + (3600 * 24 * 30));
}
else if ( isset($_SESSION[ 'lang' ] ) ) {
  $lang = $_SESSION[ 'lang'];
}
else if ( isset($_COOKIE[ 'lang' ] ) ) {
  $lang = $_COOKIE[ 'lang' ];
}
else {
  $lang = $DEFAULT_LANGUAGE;
}

switch ( $lang ) {
  case 'en': $lang_file = 'lang.en.php'; break;
  case 'th': $lang_file = 'lang.th.php'; break;
  default:   $lang_file = 'lang.en.php';
}

include_once $lang_file;

function TR( $key ) {
  global $lang;
  if ( isset( $lang[ $key ] ) == false ) {
    print( '[MISSING STRING: ' . $key . ']' );
  } 
  else {
    print( $lang[ $key ] ) ;
  }
}
?>
