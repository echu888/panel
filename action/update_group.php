<?php
namespace Action {
require_once 'authenticate.php';
require_once 'database.php';
require_once 'debug.php';

$input = $_POST;

$fields = array( "staff_id",     
                 "StartDate",    
                 "TotalHours",   
                 "StudyTime",    
                 "ClassMinutes", 
                 "M",            
                 "Tu",           
                 "W",            
                 "Th",           
                 "F",            
                 "Room",            
                 "StartingBook",            
                 "StartingUnit" );
  
$createQuery = function( $fields, $input ) {
                   $query = 'UPDATE `Groups` SET ';
                   foreach ( $fields as $field ) {
                       $query .= ' `' . $field . '`=:' . $field . ',';
                   }
                   $query = substr( $query, 0, -1 ); //< remove trailing comma
                   $query .= ' WHERE group_id =:group_id';
                 
                   return $query;
               };

MSG( '[UpdateGroup] group ' . $input[ 'group_id' ] . ' updated: ' . json_encode( $input ) );

$connection   = getConnection();
$statement    = $connection->prepare( $createQuery( $fields, $input ) );

foreach ( $fields as $field ) {
                $statement->bindValue( ':' . $field, $input[ $field ] );
}
                $statement->bindValue( 'group_id', $input[ 'group_id' ] );

$results      = $statement->execute();

echo json_encode( $results );

}
?>
