<?php
require_once '../database.php';

  function getGroupInfo( $group_id ) 
  {
    $queryString = "SELECT g.*, s.FirstName as staffFirstName, s.LastName as staffLastName
                             FROM Groups g
                       INNER JOIN Staff s  
                               ON g.staff_id = s.staff_id
                            WHERE group_id=:group_id";
   
    $connection = getConnection();
    $statement = $connection->prepare( $queryString );
    $statement->bindParam( ':group_id', $group_id );
    $results   = $statement->execute();
    $records   = $statement->fetchAll( PDO::FETCH_ASSOC );
    
    return $records[ 0 ];
  }

  function getStudentsInfo( $group_id )
  {  
    $queryString = "SELECT * FROM StudentGroup sg
                       INNER JOIN Students s
                               ON sg.student_id = s.student_id
                            WHERE group_id=:group_id";
   
    $connection = getConnection();
    $statement = $connection->prepare( $queryString );
    $statement->bindParam( ':group_id', $group_id );
    $results   = $statement->execute();
    $records   = $statement->fetchAll( PDO::FETCH_ASSOC );
    
    return $records;
  }
 
  function createReceiptCode( $group, $student )
  {
    return $group[ 'staffFirstName' ][ 0 ] 
         . $group[ 'staffLastName'][ 0 ]
         . strval( $group[ 'group_id' ] )
         . '-'
         . strval( $student[ 'student_id' ] );
  }
  
  function calculateStudyDays( $group )
  {
    $studyDays = array( 'M', 'Tu', 'W', 'Th', 'F' );
    $result    = array();
    foreach ( $studyDays as $studyDay ) {
      if ( $group[ $studyDay ] ) array_push( $result, $studyDay );
    }
    return implode( ", ", $result );
  }

  function calculateStudyTime( $group )
  {
    $startTime = new DateTimeImmutable( '2000-01-01 '. $group[ 'StudyTime' ] );
    $endTime = $startTime->modify( '+' . $group[ 'ClassMinutes' ] . ' minutes' );
    
    return $startTime->format( 'g:i' )
         . ' - '
         . $endTime->format( 'g:i' );
         //. $endTime->format( 'g:i A' );
  }

?>

