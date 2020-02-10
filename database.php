<?php
error_reporting(E_ALL); ini_set('display_errors',1);

require_once 'credentials.php';
//http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers
//https://login.justhost.com/3rdparty/phpMyAdmin/index.php?input_username=thecent9

function getConnection()
{
	$dbHost     = $dbCredentials[ 'hostname' ];
	$dbName     = $dbCredentials[ 'database' ];
	$dbUsername = $dbCredentials[ 'username' ];
	$dbPassword = $dbCredentials[ 'password' ];

	$connection = new PDO( "mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUsername, $dbPassword );
        $connection->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	// PDO::ERRMODE_SILENT                    PDO::ERRMODE_EXCEPTION 
	return $connection;
}



// filter inputArray against a whitelist of keys ($filter)
function filterInputs( $filter, $inputArray )
{
  $filteredArray = array();
  foreach ( $filter as $key )
     $filteredArray[ $key ] = $inputArray[ $key ];

  return $filteredArray;
}
 

function generateInsertStatement( $table, $fields )
{
  $statement  = 'INSERT INTO ' . $table;
  $statement .= " (`"        . implode( "`, `", array_keys( $fields ) ) . "`)";
  $statement .= " VALUES ('" . implode( "', '", $fields               ) . "')";

  return $statement;
}



