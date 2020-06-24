<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once 'save_group_helper.php';
require_once 'student_group_helper.php';

$input = $_POST;
//    [staff_id] => 17 [Room] => None
//    [M] => 1 [Tu] => 1 [W] => 0 [Th] => 1 [F] => 0
//    [StudyTime] => 15:30:00 [ClassMinutes] => 60 [StartDate] => [TotalHours] => 20
//    [StartingBook] => 0 [StartingUnit] => 0
//    [student_ids] => Array( [0] => 271 [1] => 339 )
//    [group_id] => 


// create a new group based on the POST information, then get the newly created group_id 
$group_id = saveGroup( $input );
MSG( "[ContinuingGroup] created new group " . $group_id );


// using the new group_id, loop over the student_ids and add them to the new group
if ( !empty( $input[ 'student_ids' ] ) ) {

  foreach( $input[ 'student_ids' ] as $student_id ) {

    $results = addStudent( $student_id, $group_id );

  }

}


}
?>
