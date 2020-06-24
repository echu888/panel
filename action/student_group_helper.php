<?php
namespace Action {
require_once 'database.php';
require_once 'debug.php';

// type   : add, move, remove
// group_id
// student_id
// old_group_id
	

function recalculateRecommendedDonations( $connection, $group_id )
{
// calculate value based on number of students
      $recommendedDonationRateChart = array( 0 => 0,
                                             1 => 3500,
                                             2 => 2500,
                                             3 => 1600,
                                             4 => 1200 );

// step 1: get the number of students in this group
      $statement    = $connection->prepare( "SELECT studentgroup_id FROM StudentGroup"
                                          . " WHERE group_id=:group_id" );
      $statement->bindValue( ":group_id",    $group_id );
   
                          $statement->execute();
      $students         = $statement->fetchAll( \PDO::FETCH_COLUMN );
      $numberOfStudents = count( $students );     

      if ( $numberOfStudents == 0 ) 
        return;

// step 2: calculate the appropriate donation rate
      $recommendedDonationRate = $recommendedDonationRateChart[ min( array( $numberOfStudents, 4 ) ) ];

      //print( "group_id " . $group_id . " COUNT " . $numberOfStudents . " RECOMMENDED DONATION RATE: " . $recommendedDonationRate . ", STUDENTS QUERY: " ); 
      //print_r( $students );

// step 3: update all of those students with the new donation rate
      

      $query = "UPDATE `StudentGroup` SET `RecommendedDonation`=:RecommendedDonation"
             . " WHERE studentgroup_id IN (" . implode( ",", $students ) . ")" ;

      $statement    = $connection->prepare( $query );
      $statement->bindValue( ":RecommendedDonation",    $recommendedDonationRate );
                      $statement->execute();
}

function addStudent( $student_id, $group_id ) 
{
      MSG( "[StudentGroupOp] adding student " . $student_id . " to group " . $group_id );
      $connection   = getConnection();
      $connection->beginTransaction(); 

      $statement    = $connection->prepare( "INSERT into StudentGroup ( group_id, student_id )"
                                          . " VALUES ( :group_id, :student_id ) ");
      $statement->bindValue( ":group_id",    $group_id   );
      $statement->bindValue( ":student_id",  $student_id );
      $results      = $statement->execute();

  recalculateRecommendedDonations( $connection, $group_id );

      $statement    = $connection->prepare( "UPDATE Students"
                                          . "   SET WaitingPlacement=false"
                                          . " WHERE student_id=:student_id");
      $statement->bindValue( ":student_id",  $student_id );
      $results      = $statement->execute();
      $connection->commit(); 

      return $results;
}

function moveStudent( $student_id, $old_group_id, $group_id ) 
{
      MSG( "[StudentGroupOp] moving student " . $student_id . " from group " . $old_group_id . " to group " . $group_id );
      $connection   = getConnection();
      $connection->beginTransaction(); 

      $statement    = $connection->prepare( "UPDATE StudentGroup"
                                          . "  SET group_id=:group_id"
                                          . " WHERE group_id=:old_group_id AND student_id=:student_id ");
      $statement->bindValue( ":old_group_id", $old_group_id  );
      $statement->bindValue( ":group_id",     $group_id      );
      $statement->bindValue( ":student_id",   $student_id    );
      $results      = $statement->execute();

  recalculateRecommendedDonations( $connection, $old_group_id );
  recalculateRecommendedDonations( $connection, $group_id     );

      $connection->commit(); 
      return $results;
}

function removeStudent( $student_id, $group_id ) 
{
      MSG( "[StudentGroupOp] removing student " . $student_id . " from group " . $group_id );
      $connection   = getConnection();
      $connection->beginTransaction(); 

      $statement    = $connection->prepare( "DELETE FROM StudentGroup"
                                          . " WHERE group_id=:group_id AND student_id=:student_id ");
      $statement->bindValue( ":group_id",     $group_id      );
      $statement->bindValue( ":student_id",   $student_id    );
      $results      = $statement->execute();

  recalculateRecommendedDonations( $connection, $group_id );

      $statement    = $connection->prepare( "UPDATE Students"
                                          . "   SET WaitingPlacement=true"
                                          . " WHERE student_id=:student_id");
      $statement->bindValue( ":student_id",  $student_id     );

      $results      = $statement->execute();

      $connection->commit(); 
      return $results;
}


function performStudentGroupOperation( $input ) 
{
      $results      = array();
      switch( $input[ 'type' ] ) {
      
        case 'add': 
            $results = addStudent( $input[ 'student_id' ], $input[ 'group_id' ] );
            break;
        
        case 'move': 
            $results = moveStudent( $input[ 'student_id' ], $input[ 'old_group_id' ], $input[ 'group_id' ] );
            break;
        
        case 'remove': 
            $results = removeStudent( $input[ 'student_id' ], $input[ 'group_id' ] );
            break;
      }

      echo json_encode( $results );
}

}
?>
