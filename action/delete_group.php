<?php
namespace Action {
require_once '../auth/authenticate.php';
include '../database.php';


$input = $_POST; 
$connection = getConnection();

$statement = $connection->prepare(  "DELETE FROM `Groups` WHERE group_id=:group_id" );
             $statement->bindParam( ":group_id",    $input[ 'group_id'      ] );

$result    = $statement->execute();

echo $result;

// DEBUG:
print_r($input);
//echo $sql;

}
?>
