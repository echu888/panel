<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../utility/debug.php';
require_once '../database.php';

header( "Cache-Control: no-store, no-cache, must-revalidate" ); // HTTP/1.1

$student_id = $_GET[ 'student_id' ];
//print_r( $_GET );

$queryString = 
"SELECT * FROM StudentNote 
WHERE student_id = :student_id 
ORDER BY NoteTimeStamp DESC";

$connection = getConnection();
$statement = $connection->prepare( $queryString );
$statement->bindValue( ":student_id", $student_id );
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
