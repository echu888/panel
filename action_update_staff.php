<?php
namespace Action {
require_once 'authenticate.php';
require_once 'database.php';
require_once 'debug.php';
require_once 'utility_save_picture.php';

$input = $_POST; 

MSG( '[UpdateStaff] ' . json_encode( $input ) );


$connection = getConnection();
$connection->beginTransaction();


    // 1. update staff person's basic info

         $fields = array( 'FirstName', 
                          'LastName', 
                          'NickName',
                          'PhoneNumber', 
                          'Birthdate', 
                          'Gender', 
                          'PhotoAlbumURL' ); 

         $query = 'UPDATE `Staff` SET ';
         foreach ( $fields as $field ) {
           $query .= ' `' . $field . '`=:' . $field . ',';
         }
         $query = substr( $query, 0, -1 ); //< remove trailing comma
         $query .= ' WHERE staff_id =:staff_id';

         $statement = $connection->prepare( $query );
         foreach ( $fields as $field ) {
           $statement->bindValue( ':' . $field, $input[ $field ] );
         }
         $statement->bindValue( 'staff_id', $input[ 'staff_id' ] );
         $results      = $statement->execute();



    // 2. update staff person's google login info

         $query = 'UPDATE `StaffGoogleLogin` SET '
                . ' Email =:Email '
                . ' WHERE staff_id =:staff_id';
         $statement   = $connection->prepare( $query );
                        $statement->bindValue( 'staff_id', $input[ 'staff_id' ] );
                        $statement->bindValue( 'Email',    $input[ 'Email' ] );
         $results     = $statement->execute();

    // 3. update staff person's roles

         $query       = 'UPDATE `StaffRoles` SET '
                      . ' Teacher =:Teacher,'
                      . ' StudentAdmin =:StudentAdmin, '
                      . ' GroupAdmin =:GroupAdmin, '
                      . ' FinanceAdmin =:FinanceAdmin, '
                      . ' StaffAdmin =:StaffAdmin '
                      . ' WHERE staff_id =:staff_id';
         $statement   = $connection->prepare( $query );
                        $statement->bindValue( 'staff_id'      , $input[ 'staff_id' ] );
                        $statement->bindValue( ':Teacher'      , array_key_exists( 'Teacher'      , $input ) ? '1' : '0' );
                        $statement->bindValue( ':StudentAdmin' , array_key_exists( 'StudentAdmin' , $input ) ? '1' : '0' );
                        $statement->bindValue( ':GroupAdmin'   , array_key_exists( 'GroupAdmin'   , $input ) ? '1' : '0' );
                        $statement->bindValue( ':FinanceAdmin' , array_key_exists( 'FinanceAdmin' , $input ) ? '1' : '0' );
                        $statement->bindValue( ':StaffAdmin'   , array_key_exists( 'StaffAdmin'   , $input ) ? '1' : '0' );
         $results     = $statement->execute();


$connection->commit(); 


    // 4. save staff picture, if available
    $filename = 'faces/staff/' . $input[ 'staff_id' ] . '.jpg';
    saveProfilePicture( $filename, $input[ 'profileImage' ] );


//$connection = getConnection();
//$statement = $connection->prepare(   "UPDATE `Staff` SET"
//                                   . " `FirstName` = :FirstName,"
//                                   . " `LastName` = :LastName,"
//                                   . " `NickName` = :NickName,"
//                                   . " `PhoneNumber` =:PhoneNumber,"
//                                   . " `Birthdate` =:Birthdate"
//                                   . " `PhotoAlbumURL` =:PhotoAlbumURL"
//                                   . " WHERE staff_id =:staff_id");
//$statement->bindParam( ":staff_id",     $input[ 'staff_id'   ]   ); 
//$statement->bindParam( ":FirstName",   $input[ 'TotalHours' ]   );
//$statement->execute();


// DEBUG:
//print_r($input);
//echo $sql;

}
?>
