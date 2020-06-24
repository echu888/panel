<?php
require_once '../utility/debug.php';

function saveProfilePicture( $file, $imageData ) 
{
    //$imageData = $input[ 'profileImage' ];
    if ( !empty( $imageData ) ) {

        MSG( '[SavePicture] profileImage detected, length of ' . strlen( $imageData ) );
        $filteredData                    = explode( ',', $imageData );
        $unencoded                       = base64_decode( $filteredData[ 1 ] );
     
        //$file = 'faces/students/' . $student_id . '.jpg';

        if ( file_put_contents( $file, $unencoded ) ) {
           MSG( "[SavePicture] New profile saved under file: " . $file );
        } else {
           MSG( "[SavePicture] New profile available (?) but failure to save: " . $file );
        }   
    }
    else
    {
        MSG( '[SavePicture] profileImage form field is empty' );
    }
}

?>
