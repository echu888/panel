<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../utility/debug.php';
require_once '../database.php';

$input = $_GET;

$queryString = 
'SELECT sg.*,
        stu.NickName, stu.FirstName, stu.LastName, 
        g.*,
        sta1.FirstName as TeacherName,
        sta2.FirstName as Collector
      FROM StudentGroup sg
INNER JOIN Groups         g  ON g.group_id     = sg.group_id
LEFT  JOIN Students     stu  ON stu.student_id = sg.student_id
INNER JOIN Staff        sta1 ON sta1.staff_id  = g.staff_id
LEFT  JOIN Staff        sta2 ON sta2.staff_id  = sg.CollectedBy
WHERE g.group_id IS NOT NULL
  AND sta1.FirstName NOT IN ( "Test" ) ';

if ( array_key_exists( 'id', $input ) ) {
  $staff_id = preg_replace( "/[^0-9]/", "", $input[ 'id' ] ); 
  $queryString .= ' AND g.staff_id=' . $staff_id; 
}

$queryString.= "  ORDER BY TeacherName ASC, g.StartDate DESC, g.group_id DESC";

 //WHERE  Status IN ('Waiting','Started')"; ///< exclude Canceled and Finished classes here

$connection = getConnection();
$statement = $connection->query( $queryString );
$records   = $statement->fetchAll( \PDO::FETCH_ASSOC );

$result = array();
$result = $records;
$resultString = json_encode( $result );

echo $resultString; //< json output for ajax call

}
?>
