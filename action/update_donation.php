<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../database.php';
require_once '../utility/debug.php';
require_once '../utility/note.php';

MSG( 'called', basename( __FILE__ ) );

$input = $_POST; 
//MSG( 'SESSION: ' . print_r( $_SESSION, true ), basename( __FILE__ ) );
//MSG( 'POST: '    . print_r( $_POST, true ),    basename( __FILE__ ) );

$connection = getConnection();
$connection->beginTransaction(); 
         
         $studentgroup_id = $input[ 'pk' ];

    // 1. update the donation amount and other details
         $statement = $connection->prepare(   "UPDATE `StudentGroup` 
                                               SET    `ActualDonation` = :ActualDonation,
                                                      `DonationDate`   = :DonationDate,
                                                      `CollectedBy`    = :CollectedBy
                                               WHERE  studentgroup_id  = :studentgroup_id" );
         $today       = date( "Y-m-d" );
         $collectedBy = $_SESSION[ 'staff_id' ]; //< get from authentication info
         $donation    = $input[ 'value' ];
         $statement->bindValue( ":ActualDonation",  $donation            ); 
         $statement->bindValue( ":DonationDate",    $today               ); 
         $statement->bindValue( ":CollectedBy",     $collectedBy         ); 
         $statement->bindValue( ":studentgroup_id", $studentgroup_id     ); 
         
         $results     = $statement->execute();

    // 2. note the donation amount
         $student_id = $input[ "name" ];
         \Note\addStudentNote( $connection, $student_id, \Note\STUDENT_DONATED, $studentgroup_id );

         \Note\addStaffNote( $connection, $collectedBy, \Note\STAFF_COLLECT_DONATION, $studentgroup_id );
         MSG( "[UpdateDonation] " . json_encode( $input ) );

$connection->commit(); 

//echo json_encode( $results );

// DEBUG:
//print_r($input);
//echo $sql;
}
?>
