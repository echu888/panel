<?php
namespace Action {
// action_get_picture.php?staff=5 returns a picture for staff_id 5
// action_get_picture.php?student=10 returns a picture for student_id 10
//   if the file doesn't exist, a generic avatar is returned instead

$path = array_key_exists( 'staff', $_GET ) 
        ? 'faces/staff/'
        : 'faces/students/';

$file = array_key_exists( 'staff', $_GET ) 
      ? $_GET[ 'staff' ]
      : $_GET[ 'student' ];

// sanitize input, we'll only accept numbers
$file = preg_replace( "/[^0-9]/", "", $file ); 

$extension = '.jpg';

$fullpath = $path . $file . $extension;

// file doesn't exist, let's return a generic avatar instead
if ( !file_exists( $fullpath ) ) {
  $fullpath = 'images/placeholder.jpg'; 
}

//$file_modified = filemtime( $fullpath );

header( "Content-Type: image/jpeg" );
header( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1
//header( "Cache-Control: post-check=0, pre-check=0", false ); 
header( "Content-Length: " . filesize( $fullpath ) );
readfile( $fullpath );
}
?>
