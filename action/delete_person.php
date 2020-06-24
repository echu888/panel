<?php
namespace Action {
require_once '../auth/authenticate.php';

//-------------------------------------------------------------------------------------

$input = $_POST; 
$table    = isset( $input[ 'student_id' ] ) ? "Students"   : "Staff";
$idColumn = isset( $input[ 'student_id' ] ) ? "student_id" : "staff_id";
$idValue  = $input[ $idColumn ];


include '../database.php';
$connection = getConnection();
$query      = "DELETE FROM `" . $table . "` WHERE " . $idColumn . "=:" . $idColumn;
$statement  = $connection->prepare( $query );
$statement->bindValue( ':' . $idColumn, $idValue );
$results    = $statement->execute();

// DEBUG:
//print_r($input);
//echo $sql;

}
?>
