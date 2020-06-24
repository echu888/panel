<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../utility/debug.php';
require_once '../database.php';

//SELECT  *
//FROM    match m
//WHERE   NOT EXISTS
//        (
//        SELECT  1
//        FROM    email e
//        WHERE   e.id = m.id
//        )

$queryString = 
"SELECT    * 
      FROM Students 
     WHERE WaitingPlacement=true";

//"SELECT    * 
//      FROM Students s, WaitingPlacement wp
//     WHERE s.student_id = wp.student_id";

$connection = getConnection();
//$queryString = "SELECT * FROM People";
$statement = $connection->query( $queryString );
$records   = $statement->fetchAll( \PDO::FETCH_ASSOC );

$result = array();
$result[ 'data' ] = $records;
$resultString = json_encode( $result );

//MSG( $resultString );
echo $resultString; //< json output for ajax call

//$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
//$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
//$offset = ($page-1)*$rows;
	
//$sql = "select count(*) from People";
//$result["total"] = $connection->getValue( $sql );


//$queryString = "SELECT * FROM People LIMIT $offset,$rows"; 
//              WHERE Id NOT in PeopleTrash
//TODO: make this into a prepared statement

}
?>
