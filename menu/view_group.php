<?php 
require_once '../auth/authenticate.php';
require_once '../database.php';
?>

<link rel='stylesheet' type='text/css' href='../view_group.css' />

<?php
$queryString = 
"SELECT 
  CONCAT( Staff.FirstName, ' ', Staff.LastName )                                     AS Teacher
, Groups.Status
, Groups.StartDate 
, Groups.ActualFinishDate                                                            AS EndDate 
, CONCAT_WS( ':', Groups.StartingBook, Groups.StartingUnit, Groups.StartingSection ) AS StartBook 
, CONCAT_WS( ':', Groups.EndingBook, Groups.EndingUnit, Groups.EndingSection )       AS EndBook 
, Groups.M, Groups.Tu, Groups.W, Groups.Th, Groups.F
, TIME_FORMAT( Groups.StudyTime, '%l:%i %p' )                                        AS ClassTime
, Groups.ClassMinutes / 60                                                           AS Hours
, Groups.TotalHours                                                                  AS TotalHours
FROM Groups 
JOIN Staff         ON Staff.staff_id = Groups.staff_id
WHERE Groups.group_id=:group_id";

$group_id = preg_replace( "/[^0-9]/", "", $_GET[ 'id' ] ); 

$connection = getConnection();

            $statement = $connection->prepare( $queryString );
                         $statement->bindValue( ':group_id', $group_id );
                         $statement->execute();
            $records   = $statement->fetchAll( PDO::FETCH_ASSOC );
            
            $class_details = json_encode( $records );


$queryString = 
"SELECT 
CONCAT( Students.FirstName, ' ', Students.LastName ) AS StudentName, 
CONCAT( Students.NickName,  ' ', Students.NickNameThai ) AS NickName,
Students.TestedLevel,
Students.Gender,
StudentGroup.DonationDate,
StudentGroup.ActualDonation AS Donation
FROM Groups 
JOIN StudentGroup  ON StudentGroup.group_id = Groups.group_id
JOIN Students      ON StudentGroup.student_id = Students.student_id
WHERE Groups.group_id=:group_id";

$group_id = preg_replace( "/[^0-9]/", "", $_GET[ 'id' ] ); 

$connection = getConnection();

            $statement = $connection->prepare( $queryString );
                         $statement->bindValue( ':group_id', $group_id );
                         $statement->execute();
            $records   = $statement->fetchAll( PDO::FETCH_ASSOC );
            
            $student_details = json_encode( $records );

            //$result = array();
            //$result[ 'data' ] = $records;
            //$resultString = json_encode( $result );
            //echo $resultString; //< json output for ajax call

?>



<body>
<h1> Class Information </h1>
</body>



<script>
var _table_ = document.createElement('table'),
       _tr_ = document.createElement('tr'),
       _th_ = document.createElement('th'),
       _td_ = document.createElement('td');

// Builds the HTML Table out of myList json data from Ivy restful service.
 function buildHtmlTable(arr) {
     var table = _table_.cloneNode(false),
         columns = addAllColumnHeaders(arr, table);
     for (var i=0, maxi=arr.length; i < maxi; ++i) {
         var tr = _tr_.cloneNode(false);
         for (var j=0, maxj=columns.length; j < maxj ; ++j) {
             var td = _td_.cloneNode(false);
                 cellValue = arr[i][columns[j]];
             td.appendChild(document.createTextNode(arr[i][columns[j]] || ''));
             tr.appendChild(td);
         }
         table.appendChild(tr);
     }
     return table;
 }

 // Adds a header row to the table and returns the set of columns.
 // Need to do union of keys from all records as some records may not contain
 // all records
 function addAllColumnHeaders(arr, table)
 {
     var columnSet = [],
         tr = _tr_.cloneNode(false);
     for (var i=0, l=arr.length; i < l; i++) {
         for (var key in arr[i]) {
             if (arr[i].hasOwnProperty(key) && columnSet.indexOf(key)===-1) {
                 columnSet.push(key);
                 var th = _th_.cloneNode(false);
                 th.appendChild(document.createTextNode(key));
                 tr.appendChild(th);
             }
         }
     }
     table.appendChild(tr);
     return columnSet;
 }

document.body.appendChild(buildHtmlTable( <? echo $class_details;   ?> ));
document.body.appendChild(buildHtmlTable( <? echo $student_details; ?> ));
</script>


