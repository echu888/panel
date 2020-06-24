<?php


function MSG( $message, $caller = '', $newline = true )
{
  $USE_DEBUGGER = true;

  if ( $USE_DEBUGGER ) 
  {
    $logfile_name = 'logs/' . date( 'Y-m-d' ) . '.log';
    $user = array_key_exists( 'FirstName', $_SESSION ) ? ' ' . $_SESSION[ 'FirstName' ] : '';
    
    //[2017-05-11 05:34:02] action_touch_groups.php : called
    $error_message = '[' . date( 'Y-m-d H:i:s' ) . $user . '] '
                   . $caller . ': ' 
                   . $message 
                   . ( $newline ? PHP_EOL : '' );

    error_log( $error_message, 3, $logfile_name );
    //error_log( $_SESSION, 3, $logfile_name );
  } 
}


?>
