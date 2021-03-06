<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../utility/debug.php';
require_once '../database.php';


$queryString = 
"SELECT sg.student_id,
        stu.NickName, stu.FirstName, stu.LastName, 
        g.group_id
      FROM Groups       g
INNER JOIN StudentGroup sg   ON    sg.group_id = g.group_id
INNER JOIN Students     stu  ON stu.student_id = sg.student_id
  ORDER BY g.group_id";


$connection = getConnection();
$statement = $connection->query( $queryString );
$records   = $statement->fetchAll( \PDO::FETCH_ASSOC );

$result = array();
$result[ 'data' ] = $records;
$resultString = json_encode( $result );

//MSG( $resultString );
echo $resultString; //< json output for ajax call
}
?>
