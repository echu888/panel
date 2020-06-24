<?php
namespace Note {
require_once 'database.php';
require_once 'debug.php';
// 
//
//
//


  function addStudentNote( $connection, $student_id, $code, $reference = '' )
  {
           $statement     = $connection->prepare( "INSERT INTO StudentNote ( student_id, Code, Reference ) VALUES ( :student_id, :Code, :Reference )" );
                            $statement->bindValue( ":student_id", $student_id  );
                            $statement->bindValue( ":Code",       $code        );
                            $statement->bindValue( ":Reference",  $reference   );
                            //$statement->bindValue( ":Details",    $details     );
           $results       = $statement->execute();
           MSG( '[Note] student note ' . $code . ' added for student ' . $student_id );
  }

  function addStaffNote( $connection, $staff_id, $code, $reference = '' )
  {
           $statement     = $connection->prepare( "INSERT INTO StaffNote ( staff_id, Code, Reference ) VALUES ( :staff_id, :Code, :Reference )" );
                            $statement->bindValue( ":staff_id",   $staff_id    );
                            $statement->bindValue( ":Code",       $code        );
                            $statement->bindValue( ":Reference",  $reference   );
                            //$statement->bindValue( ":Details",    $details     );
           $results       = $statement->execute();
           MSG( '[Note] staff note ' . $code . ' added for staff ' . $staff_id );
  }


  // TODO: this data is all duplicated, once in PHP and once in Javascript.  
  // Let's turn it all into JSON and then  use a single copy in both places
  const STUDENT_FREENOTE       = 1000;  //any text
  const STUDENT_APPLIED        = 1100;  //*staff 
  const STUDENT_WAITING        = 1102;  //*
  const STUDENT_STARTED        = 1106;  //*link to class
  const STUDENT_FINISHED       = 1108;  //*link to class
  const STUDENT_CANCELED       = 1110;  //link to class
  const STUDENT_DONATED        = 1200;  //*amt
  
  // life events
  const STUDENT_MAJOR          = 1300;  //major
  const STUDENT_GRADUATED_FROM = 1302;  //University
  const STUDENT_MOVED_TO       = 1304;  //place
  const STUDENT_WORKING_AT     = 1306;  //business
  
  const STAFF_FREENOTE         = 2000; 
  const STAFF_STARTED_CLASS    = 2106; //link
  const STAFF_FINISHED_CLASS   = 2108; //link
  
  const STAFF_COLLECT_DONATION = 2200; 
  
  const STAFF_RECEIVED_WP      = 2300; 
  const STAFF_CANCELED_WP      = 2302; 
  const STAFF_STARTED_WORK     = 2304; 
  const STAFF_RECEIVED_1YR_EXT = 2306; 
}

?>
