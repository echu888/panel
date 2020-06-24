<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../database.php';

$month = array_key_exists( 'da', $_GET ) 
         ? $_GET[ 'da' ]
         : date( 'Y-m-d' );

	
$connection = getConnection();

             $statement = $connection->prepare( "SELECT count(1)
                                                 FROM `Students` 
                                                 INNER JOIN StudentGroup
                                                 ON StudentGroup.student_id = Students.student_id
                                                 INNER JOIN Groups
                                                 ON Groups.group_id = StudentGroup.group_id
                                                 WHERE Groups.status = 'Finished'
                                                 AND MONTH( ActualFinishDate ) = MONTH( :date1 )
                                                 AND YEAR( ActualFinishDate )  = YEAR ( :date2 )" );

             $statement->bindParam( ':date1', $month );
             $statement->bindParam( ':date2', $month );
             $statement->execute();
$results   = $statement->fetchAll( \PDO::FETCH_ASSOC );

echo json_encode( $results, JSON_FORCE_OBJECT );

}
?>
