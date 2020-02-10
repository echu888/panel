<?php
namespace Action {
require_once 'authenticate.php';
require_once 'debug.php';
require_once 'database.php';


$queryString = "SELECT * FROM Students ORDER BY student_id DESC";

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
