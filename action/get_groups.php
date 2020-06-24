<?php
namespace Action {
require_once '../auth/authenticate.php';
require_once '../utility/debug.php';
require_once '../database.php';

//MSG( 'GET data: ' . print_r( $_GET , true ), basename( __FILE__ ) );

$queryString = 
"SELECT * FROM Groups
 WHERE  Status IN ('Waiting','Started')"; ///< exclude Canceled and Finished classes here

if ( array_key_exists( 'staff_id', $_GET ) ) {
  $queryString .= ' AND staff_Id=' . $_GET[ 'staff_id' ];
}

$connection = getConnection();
$statement = $connection->query( $queryString );
$records   = $statement->fetchAll( \PDO::FETCH_ASSOC );

$result = array();
$result[ 'data' ] = $records;
$resultString = json_encode( $result );

echo $resultString; //< json output for ajax call

}
?>
