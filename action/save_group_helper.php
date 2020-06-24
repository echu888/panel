<?php
namespace Action {
require_once '../database.php';
require_once '../utility/debug.php';

function saveGroup( $input )
{

    $fields = array( 'staff_id', 
                     'StartDate', 
                     'TotalHours',
                     'StudyTime', 
                     'ClassMinutes', 
                     'M', 
                     'Tu', 
                     'W', 
                     'Th', 
                     'F', 
                     'Room', 
                     'StartingBook', 
                     'StartingUnit' ); 
    
    $query = 'INSERT INTO '
           . ' Groups (' . implode( ',', $fields  ) . ')'
           . ' VALUES( :'  . implode( ',:', $fields ) . ')';
    
    $connection   = getConnection();
    $statement    = $connection->prepare( $query );
    
    foreach ( $fields as $field ) {
                    $statement->bindValue( ':' . $field, $input[ $field ] );
    }
    $results      = $statement->execute();
    $lastInsertId = $connection->lastInsertId();
    
    MSG( '[SaveGroup] group ' . $lastInsertId . ': ' . json_encode( $input ) );
    echo json_encode( $results );

    return $lastInsertId;
}



}
?>
