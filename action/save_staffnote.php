<?php
namespace Action {
require_once 'authenticate.php';
require_once 'database.php';
require_once 'debug.php';
require_once 'utility_note.php';

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
