<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../utility/debug.php';
require_once '../database.php';

$input = $_POST; 

$query = 
"SELECT * FROM Staff
  LEFT JOIN StaffGoogleLogin  ON Staff.staff_id = StaffGoogleLogin.staff_id
  LEFT JOIN StaffRoles        ON Staff.staff_id = StaffRoles.staff_id";

if ( array_key_exists( 'staff_id', $input ) ) {
  $query .= ' WHERE Staff.staff_id = ' . $input[ 'staff_id' ]; 
}

$connection = getConnection();
$statement = $connection->query( $query );

$records   = $statement->fetchAll( \PDO::FETCH_ASSOC );

$result = array();
$result[ 'data' ] = $records;
$resultString = json_encode( $result );

echo $resultString; //< json output for ajax call

}
?>
