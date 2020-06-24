<?php
namespace Action {
require_once 'authenticate.php';
require_once 'debug.php';
require_once 'database.php';

// loads ONLY ACTIVE teachers! 


$queryString = 
"SELECT * From Staff
INNER JOIN StaffRoles ON Staff.staff_id=StaffRoles.staff_id
WHERE StaffRoles.Teacher=1
AND Staff.active=1";


//if ( isset( $_GET[ 'type' ] ) ) {
//  $type = $_GET[ 'type' ];  
//  switch ( $type ) {
//    case "student" :$queryString = "SELECT * FROM Students"; break;
//    case "staff"   :$queryString = "SELECT * FROM Staff";    break;
//  }
//}

$connection = getConnection();
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
