<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../database.php';

$month = array_key_exists( 'da', $_GET ) 
         ? $_GET[ 'da' ]
         : date( 'Y-m-d' );

	
$connection = getConnection();

             $statement = $connection->prepare( "SELECT
                                                     AVG( TIMESTAMPDIFF(DAY, StartDate, ActualFinishDate ) ) AS number_of_days_avg
                                                 FROM `Groups` 
                                                 WHERE ActualFinishDate > 0
                                                 AND MONTH( ActualFinishDate ) = MONTH( :date1 )
                                                 AND YEAR( ActualFinishDate )  = YEAR ( :date2 )" );

             $statement->bindParam( ':date1', $month );
             $statement->bindParam( ':date2', $month );
             $statement->execute();
$results   = $statement->fetchAll( \PDO::FETCH_ASSOC );

echo json_encode( $results, JSON_FORCE_OBJECT );

}
?>
