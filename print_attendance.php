<?php
require_once 'authenticate.php';
?>
<!DOCTYPE html>
<html>

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="print_attendance.css"  />
<head/>

<!--body onload="window.print()"-->
<body>
<?php
require_once 'utility_print.php';

$group_id = $_GET[ 'group_id' ];
$group    = getGroupInfo( $group_id );
$students = getStudentsInfo( $group_id );
?>

<div class='printContainerA4'>
<h1> Attendance Sheet & Group Details </h1>





<table>
<thead>
<tr> 
    <th class="numberCol"></th>
    <th class="nameCol">Name </th>
    <th class="birthdateCol">Birthdate</th>
    <th class="testedCol">Tested<br/> Level</th>
    <th class="lastLevelCol">Last Level Studied</th>

    <th class='day'> 1  </th> <th class='day'> 2  </th> <th class='day'> 3  </th> <th class='day'> 4  </th> <th class='day'> 5  </th> 
    <th class='day'> 6  </th> <th class='day'> 7  </th> <th class='day'> 8  </th> <th class='day'> 9  </th> <th class='day'> 10 </th> 
    <th class='day'> 11 </th> <th class='day'> 12 </th> <th class='day'> 13 </th> <th class='day'> 14 </th> <th class='day'> 15 </th> 
    <th class='day'> 16 </th> <th class='day'> 17 </th> <th class='day'> 18 </th> <th class='day'> 19 </th> <th class='day'> 20 </th>
</tr>
</thead>
<tbody>


<?php
    function createCell( $content )
    {
      print( "<td>" . $content . "</td>\n" );
    }
    function printBlankCells( $count )
    {
      for ( $i = 0; $i < $count; $i++ ) {
        print( '<td></td> ' ) ;
      }
    }

    const MAX_STUDENT_COUNT      = 8;
    const MAX_NUMBER_OF_MEETINGS = 20;
    $studentCount = 0;
    foreach ( $students as $student ) {
      $nameField = '<div class="nickName">'  . $student[ 'NickName'  ]                                .  '</div>'
                 . '<div class="fullName">(' . $student[ 'FirstName' ] . ' ' . $student[ 'LastName' ] . ')</div>';
      print( "<tr>\n" );
      createCell( $studentCount + 1 );
      createCell( $nameField );
      createCell( $student[ 'Birthdate' ] );
      createCell( $student[ 'TestedLevel' ] ); //1A
      createCell( ""); //Book 1, Unit 4
      printBlankCells( MAX_NUMBER_OF_MEETINGS );
      print( "</tr>\n" );
      $studentCount++;
    }

    for ( $studentCount; $studentCount < MAX_STUDENT_COUNT; $studentCount++ ) {
      print( "<tr>\n" );
      createCell( $studentCount + 1 );
      printBlankCells( 4 ) ;
      printBlankCells( MAX_NUMBER_OF_MEETINGS );
      print( "</tr>\n" );
    }
?>
</tbody>

<tfoot>
<tr>
    <td class='noborder' colspan='4'>

<h3> Group Details </h3>
<div class="groupDetails">
  <div class='detailsLabel'> Teacher:        </div> <div> <?= $group[ 'staffFirstName' ]; ?> </div> <br/>
  <div class='detailsLabel'> Study Times:    </div> <div> <?= calculateStudyDays( $group ) ?> </div> 
                                                    <div> <?= calculateStudyTime( $group ); ?> </div> <br/>
  <div class='detailsLabel'> Starting Date:  </div> <div> <?= $group[ 'StartDate' ]; ?> </div><br/>
  <div class='detailsLabel'> Curriculum:     </div> <div id="curriculum"></div> <br/>
</div>

    </td>

    <td> <div class="verticalText"> <h3> Progress Notes </h3><br/><br/><br/> </div></td>

<?php
    printBlankCells( 20 );
?>
</tr>
</tfoot>
</table>

</div>

<script type="text/javascript" charset="utf-8" src="utility_curriculum.js" ></script>
<script>
$(function() {
  var startingBook = <?= $group[ 'StartingBook' ] ?>;
  var startingUnit = <?= $group[ 'StartingUnit' ] ?>;
  $( "#curriculum" ).html( Curriculum.getBookUnit( startingBook, startingUnit ) );
});
</script>

</body>
</html>
