<?php
namespace Action {
// NOTE: This file is executed as a nightly cronjob, therefore, no authentication is required
require_once 'database.php';
require_once 'debug.php';
require_once 'utility_note.php';

//MSG( 'called', basename( __FILE__ ) );
print( "Touching groups..." . PHP_EOL );

$connection = getConnection();
$connection->beginTransaction(); 


    // 1. get a list of the students who are starting class
         // "UPDATE Groups SET Status='Started' WHERE `StartDate` <= now() AND   (Status='Waiting')"
         $statement = $connection->prepare( "SELECT sg.group_id, sg.student_id
                                             FROM Groups as g
                                             JOIN StudentGroup as sg ON g.group_id = sg.group_id 
                                             WHERE g.StartDate <= now()
                                             AND  (g.Status='Waiting') " );
         $results   = $statement->execute();
         $students  = $statement->fetchAll();


    //    add notes for each of them
         foreach ( $students as $student ) {
             \Note\addStudentNote( $connection, $student[ "student_id" ], \Note\STUDENT_STARTED, $student[ "group_id" ] );
         }

    // 2. get a list of the students who are starting class
         // "UPDATE Groups SET Status='Started' WHERE `StartDate` <= now() AND   (Status='Waiting')"
         $statement = $connection->prepare( "SELECT g.group_id, g.staff_id
                                             FROM Groups as g
                                             WHERE g.StartDate <= now()
                                             AND  (g.Status='Waiting') " );
         $results   = $statement->execute();
         $groups  = $statement->fetchAll();


    //    add notes for each of them
         foreach ( $groups as $group ) {
//print_r( $group );
             \Note\addStaffNote( $connection, $group[ "staff_id" ], \Note\STAFF_STARTED_CLASS, $group[ "group_id" ] );
         }


    // 3. change the groups from Waiting to Started, based on the entered start date
         $statement = $connection->prepare( "UPDATE Groups SET Status='Started' WHERE `StartDate` <= now() AND (Status='Waiting')"  );
         $results   = $statement->execute();

$connection->commit(); 

//echo json_encode( $results );
// DEBUG:
//print_r($input);
//echo $sql;

}
?>
