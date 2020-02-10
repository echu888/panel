<?php
namespace Action {
require_once 'authenticate.php';
require_once 'database.php';
require_once 'debug.php';
require_once 'utility_note.php';

//MSG( 'called', basename( __FILE__ ) );

$input = $_POST;


$connection   = getConnection();
$connection->beginTransaction(); 

    // 1. update finishing values, and set group status to 'Finished'
         $fields = array( "ActualFinishDate",
                          "EndingBook",            
                          "EndingUnit" );
             
         $query        = "UPDATE `Groups` SET `Status`='Finished', ";
         foreach ( $fields as $field ) {
             $query   .= ' `' . $field . '`=:' . $field . ',';
         }
         $query        = substr( $query, 0, -1 ); //< remove trailing comma
         $query       .= ' WHERE group_id =:group_id';
         $statement    = $connection->prepare( $query );
         foreach ( $fields as $field ) {
             $statement->bindValue( ':' . $field, $input[ $field ] );
         }
         $statement->bindValue( 'group_id', $input[ 'group_id' ] );
         $results      = $statement->execute();

         MSG( '[FinishGroup] group ' . $input[ 'group_id' ] . ' ' . json_encode[ 'input' ]  );
         


    // 2. note that students are finished
         if ( count( $input[ 'students' ] ) > 0 ) {
           foreach( $input[ 'students' ]  as $student_id ) {
               \Note\addStudentNote( $connection, $student_id, \Note\STUDENT_FINISHED, $input[ 'group_id' ] );
           }
         }

         \Note\addStaffNote( $connection, $input[ 'staff_id' ], \Note\STUDENT_FINISHED, $input[ 'group_id' ] );

    // 3. set some/all students to 'Waiting', if they're continuing
         if ( count( $input[ 'student_ids' ] ) > 0 ) {
         
           foreach( $input[ 'student_ids' ]  as $student_id ) {
               $query     =  "UPDATE Students SET WaitingPlacement=true WHERE student_id=:student_id";
               $statement = $connection->prepare( $query );
               $statement->bindValue( ':student_id', $student_id );
               $results   = $statement->execute();

    // 4. add note for this student
    // or we don't have to do this????
               \Note\addStudentNote( $connection, $student_id, \Note\STUDENT_WAITING );
           }
         }


$connection->commit(); 

//echo json_encode( $results );

// DEBUG:
//print_r($input);
//echo $sql;

}
?>
