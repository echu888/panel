<?php
namespace Action {
require_once 'authenticate.php';
require_once 'database.php';
require_once 'utility_note.php';

$month = array_key_exists( 'da', $_GET ) 
         ? $_GET[ 'da' ]
         : date( 'Y-m-d' );

	
$connection = getConnection();

             $statement = $connection->prepare( "SELECT COUNT(*) FROM Students 
                                                INNER JOIN StudentNote
                                                ON Students.student_id =  StudentNote.student_id
                                                where StudentNote.code = " . \Note\STUDENT_APPLIED . "
                                                and MONTH(NoteTimeStamp) = MONTH( :date1 )
                                                and YEAR(NoteTimeStamp)  =  YEAR( :date2 )" );
                                                    /*and YEAR(NoteTimeStamp) = YEAR(CURRENT_DATE())*/


             $statement->bindParam( ':date1', $month );
             $statement->bindParam( ':date2', $month );
             $statement->execute();
$results   = $statement->fetchAll( \PDO::FETCH_ASSOC );

echo json_encode( $results, JSON_FORCE_OBJECT );

}
?>
