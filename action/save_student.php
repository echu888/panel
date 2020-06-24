<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../database.php';
require_once '../utility/debug.php';
require_once '../utility/note.php';
require_once '../utility/save_picture.php';

MSG( 'called', basename( __FILE__ ) );

$input = $_POST; 

$connection = getConnection();
$connection->beginTransaction(); 


    // 1. add new student (with all form fields)
         $fields = array( 'FirstName', 
                          'LastName', 
                          'NickName',
                          'NickNameThai', 
                          'TestedLevel', 
                          'PhoneNumber', 
                          'Gender', 
                          'Birthdate' ); 
         
         $query = 'INSERT INTO '
                . ' Students (' . implode( ',', $fields  ) . ')'
                . ' VALUES( :'  . implode( ', :', $fields ) . ')';
         
         $statement    = $connection->prepare( $query );
         
         foreach ( $fields as $field ) {
              $statement->bindValue( ':' . $field, $input[ $field ] );
         }
         $results      = $statement->execute();


    // 2. add note for new student
         $student_id = $connection->lastInsertId();
         \Note\addStudentNote( $connection, $student_id, \Note\STUDENT_APPLIED, "" );

         //MSG( '[SaveStudent] student ' . $student_id . ': ' . json_encode( $input ) );

$connection->commit(); 

    // 2. save student picture, if available
    $filename = 'faces/students/' . $student_id . '.jpg';
    saveProfilePicture( $filename, $input[ 'profileImage' ] );


//echo json_encode( $results );

// DEBUG:
//print_r($input);
//echo $sql;

}
?>
