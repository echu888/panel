<?php
namespace Action {
require_once 'authenticate.php';
require_once 'database.php';

$month = array_key_exists( 'mo', $_GET ) 
         ? intval( $_GET[ 'mo' ] ) 
         : date('m');

$who   = array_key_exists( 'who', $_GET )  //< 'all', 'sta', 'stu'
         ? $_GET[ 'who' ]
         : 'all';

	
	
$connection = getConnection();

if ( $who == 'stu' ) {
             $statement = $connection->prepare( "SELECT stu.*, sg.group_id, sg.student_id, g.group_id, g.staff_id, sta.staff_id, sta.FirstName AS TeacherName
                                                      FROM       Students     stu
                                                      INNER JOIN StudentGroup sg  ON stu.student_id = sg.student_id 
                                                      INNER JOIN Groups       g   ON sg.group_id    = g.group_id
                                                      INNER JOIN Staff        sta ON sta.staff_id   = g.staff_id 
                                                 WHERE MONTH(stu.Birthdate) = :month
                                                 GROUP BY stu.student_id 
                                                 ORDER BY DAY(stu.Birthdate) " );
}
else if ( $who == 'sta' ) {
             $statement = $connection->prepare( "SELECT * FROM Staff    WHERE MONTH(Birthdate) = :month" );
} 

             $statement->bindParam( ':month', $month );
             $statement->execute();
$results   = $statement->fetchAll( \PDO::FETCH_ASSOC );

// JSON_FORCE_OBJECT ensures that even no results will provide properly formed JSON
echo json_encode( $results, JSON_FORCE_OBJECT );

}
?>
