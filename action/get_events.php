<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../utility/debug.php';
require_once '../database.php';

$queryString = 
"SELECT * FROM StudentNote";
//  ORDER BY TeacherName ASC, g.StartDate DESC";

$connection = getConnection();
$statement = $connection->query( $queryString );
$records   = $statement->fetchAll( \PDO::FETCH_ASSOC );

$result = array();
$result = $records;
$resultString = json_encode( $result );

echo $resultString; //< json output for ajax call

}
?>
