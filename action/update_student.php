<?php
namespace Action {
require_once 'authenticate.php';
require_once 'database.php';
require_once 'debug.php';
require_once 'utility_save_picture.php';

$input = $_POST; 

$connection = getConnection();

               $statement = $connection->prepare(   "UPDATE `Students` SET"
                                                  . " `FirstName` = :FirstName,"
                                                  . " `LastName` = :LastName,"
                                                  . " `NickName` = :NickName,"
                                                  . " `NickNameThai` =:NickNameThai,"
                                                  . " `TestedLevel` =:TestedLevel,"
                                                  . " `PhoneNumber` =:PhoneNumber,"
                                                  . " `Gender` =:Gender,"
                                                  . " `Birthdate` =:Birthdate"
                                                  . " WHERE student_id =:student_id" );

               $statement->bindValue( ":FirstName",     $input[ 'FirstName'    ] ); 
               $statement->bindValue( ":LastName",      $input[ 'LastName'     ] ); 
               $statement->bindValue( ":NickName",      $input[ 'NickName'     ] ); 
               $statement->bindValue( ":NickNameThai",  $input[ 'NickNameThai' ] ); 
               $statement->bindValue( ":TestedLevel",   $input[ 'TestedLevel'  ] ); 
               $statement->bindValue( ":PhoneNumber",   $input[ 'PhoneNumber'  ] ); 
               $statement->bindValue( ":Gender",        $input[ 'Gender'       ] ); 
               $statement->bindValue( ":Birthdate",     $input[ 'Birthdate'    ] ); 
               $statement->bindValue( ":student_id",    $input[ 'student_id'   ] ); 

$results     = $statement->execute();



    // 2. save student picture, if available
    $filename = 'faces/students/' . $input[ 'student_id' ] . '.jpg';
    saveProfilePicture( $filename, $input[ 'profileImage' ] );


echo json_encode( $results );

// DEBUG:
//print_r($input);
//echo $sql;

}
?>
