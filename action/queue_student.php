<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../database.php';
require_once '../utility/debug.php';
require_once '../utility/note.php';

MSG( 'called', basename( __FILE__ ) );

$input = $_POST; 
$action = $input[ 'action' ];
//MSG( print_r( $input, true ) );

$connection   = getConnection();
$connection->beginTransaction(); 

    // 1. change Waiting status for this student
         MSG( 'called', basename( __FILE__ ) );
         $waitingPlacement =  '';
         if     ( $action == 'queue'   ) { $waitingPlacement = 'true';  }
         elseif ( $action == 'unqueue' ) { $waitingPlacement = 'false'; }
         else {
             MSG( 'Unknown action specified', basename( __FILE__ ) ); 
             exit;
         }

         MSG( $action . ' student_id ' . $input[ 'student_id' ], basename( __FILE__ ) );
         $statement    = $connection->prepare( 'UPDATE Students SET WaitingPlacement=' . $waitingPlacement . ' WHERE student_id=:student_id' );
                         $statement->bindValue( ":student_id",  $input[ 'student_id'    ] );
         $results      = $statement->execute();

    // 2. add Note
         if     ( $action == 'queue'   ) { 
             \Note\addStudentNote( $connection, $input[ "student_id" ], \Note\STUDENT_WAITING );
         }

$connection->commit(); 

//echo json_encode( $results );
}
?>
