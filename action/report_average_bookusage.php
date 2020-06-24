<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../database.php';

$month = array_key_exists( 'da', $_GET ) 
         ? $_GET[ 'da' ]
         : date( 'Y-m-d' );

	
$connection = getConnection();

             $statement = $connection->prepare( "SELECT 
                                                    StartingBook,
                                                    COUNT(*) as Count 
                                                 FROM `Groups`  
                                                 WHERE MONTH( StartDate ) = MONTH( :date1 )
                                                 AND YEAR( StartDate )  = YEAR ( :date2 )
                                                 GROUP BY StartingBook 
                                                 ORDER BY StartingBook ASC" );


             $statement->bindParam( ':date1', $month );
             $statement->bindParam( ':date2', $month );
             $statement->execute();
$results   = $statement->fetchAll( \PDO::FETCH_ASSOC );

echo json_encode( $results, JSON_FORCE_OBJECT );

}
?>
