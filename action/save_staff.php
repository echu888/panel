<?php
namespace Action {
require_once 'authenticate.php';
require_once 'database.php';
require_once 'debug.php';
require_once 'utility_save_picture.php';

//-------------------------------------------------------------------------------------

$input = $_POST; 

$connection = getConnection();
$connection->beginTransaction();


    // 1. save staff person's basic info

         $fields = array( 'FirstName', 
                          'LastName', 
                          'NickName',
                          'PhoneNumber', 
                          'Gender', 
                          'Birthdate' ); 

         $query        = 'INSERT INTO '
                       . ' Staff (' . implode( ',', $fields  ) . ')'
                       . ' VALUES( :'  . implode( ',:', $fields ) . ')';
         $statement    = $connection->prepare( $query );
         foreach ( $fields as $field ) {
           $statement->bindValue( ':' . $field, $input[ $field ] );
         }
         $results     = $statement->execute();

         $staff_id = $connection->lastInsertId();


    // 2. save staff person's relevant Google info

         $query       = 'INSERT INTO '
                      . ' StaffGoogleLogin ( staff_id, Email ) '
                      . ' VALUES( :staff_id, :Email ) ';
         $statement   = $connection->prepare( $query );
                        $statement->bindValue( ':staff_id'     , $staff_id     );
                        $statement->bindValue( ':Email'        , $input[ 'Email' ] );
         $results    .= $statement->execute();



    // 3. save staff person's roles

         $query       = 'INSERT INTO '
                      . ' StaffRoles ( staff_id, StaffAdmin, StudentAdmin, GroupAdmin, FinanceAdmin, Teacher ) '
                      . ' VALUES( :staff_id, :StaffAdmin, :StudentAdmin, :GroupAdmin, :FinanceAdmin, :Teacher ) ';
         $statement   = $connection->prepare( $query );
                        $statement->bindValue( ':staff_id'     , $staff_id            );
                        $statement->bindValue( ':Teacher'      , array_key_exists( 'Teacher'     , $input ) );
                        $statement->bindValue( ':StudentAdmin' , array_key_exists( 'StudentAdmin', $input ) );
                        $statement->bindValue( ':GroupAdmin'   , array_key_exists( 'GroupAdmin'  , $input ) );
                        $statement->bindValue( ':FinanceAdmin' , array_key_exists( 'FinanceAdmin', $input ) );
                        $statement->bindValue( ':StaffAdmin'   , array_key_exists( 'StaffAdmin'  , $input ) );
         $results    .= $statement->execute();


    
         MSG( '[SaveStaff] staff ' . $staff_id . ': ' . json_encode( $input ) );

$connection->commit(); 




    // 4. save staff picture, if available
    $filename = 'faces/staff/' . $staff_id . '.jpg';
    saveProfilePicture( $filename, $input[ 'profileImage' ] );



echo json_encode( $results );

// DEBUG:
//print_r($input);
//echo $sql;

}
?>
