<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../database.php';
require_once '../utility/debug.php';
require_once '../utility/note.php';

MSG( 'called', basename( __FILE__ ) );

$input = $_POST; 

$connection = getConnection();
\Note\addStaffNote( $connection, $input[ "staff_id" ], $input[ 'Code' ], $input[ "Reference" ] );

//echo json_encode( $results );

// DEBUG:
//print_r($input);
//echo $sql;

}
?>
