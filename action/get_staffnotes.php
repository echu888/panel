<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../utility/debug.php';
require_once '../database.php';

header( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1

$staff_id = $_GET[ 'staff_id' ];
//print_r( $_GET );

$queryString = 
"SELECT * FROM StaffNote 
WHERE staff_id = :staff_id
ORDER BY NoteTimeStamp DESC";

$connection = getConnection();
$statement = $connection->prepare( $queryString );
$statement->bindValue( ":staff_id", $staff_id );
$statement->execute();

$records   = $statement->fetchAll( \PDO::FETCH_ASSOC );

$result = array();
//$result[ 'data' ] = $records;
$result = $records;
$resultString = json_encode( $result );

//MSG( $resultString );
echo $resultString; //< json output for ajax call

}
?>
