<?php require_once 'menu.php'; ?>

<link rel='stylesheet' type='text/css' href='birthdays.css' />
<script defer type="text/javascript" charset="utf-8" src="../utility/select.js" ></script>


      

<!--                        BIRTHDAYS DEMO -->
<h1 class='pageTitle'> <? TR( 'birthdays' ); ?> </h1>

<div id="select"> </div>

<div id="birthdays">
<img id ="birthdaysLoadingImage" src="images/loading.gif"/>


<h2 id='selectedMonth'> </h2>

<h3>Students</h3>
<table>
  <tr><th>Name</th><th>Nickname</th><th>Birthdate</th><th>Turning</th><th>Teacher</th></tr>
  <tbody id="birthdayTableStudents"> </tbody>
</table>

<h3>Staff</h3>
<table>
  <tr><th>Name</th><th>Nickname</th><th>Birthdate</th><th>Turning</th><th></th></tr>
  <tbody id="birthdayTableStaff"> </tbody>
</table>




</div>




<script>
function calculateAge( birthdate )
{
  var dateObj    = new Date( birthdate );
  var birthYear  = dateObj.getYear();
  var thisYear   = new Date().getYear();
  return thisYear - birthYear;
}

function populateTable( tableName, type, month )  {
   $( tableName ).empty();
   $.ajax({
       url: "../action/get_birthdays.php",
       method: "GET",
       data: { mo : month, who : type },
       dataType: "json",
       beforeSend: function()     { $('#birthdaysLoadingImage').show(); },
       complete:   function()     { $('#birthdaysLoadingImage').hide(); },
       success:    function(data) {
           $.each(data, function(i, item){
             if ( typeof item.TeacherName === 'undefined' ) item.TeacherName = '';
             var content = '<tr>' 
                         + '<td data-label="Name">'      + item.FirstName + " " + item.LastName   + '</td> ' 
                         + '<td data-label="NickNAme">'  + item.NickName                          + '</td> ' 
                         + '<td data-label="Birthdate">' + item.Birthdate                         + '</td> ' 
                         + '<td data-label="Turning">'   + calculateAge( item.Birthdate )         + '</td> ' 
                         + '<td data-label="">'          + item.TeacherName                       + '</td> ' 
                         + '</tr>';
             $( tableName ).append( content );
           });
         }
  });
}

function getMonthName( month )
{
  switch( month ) {
    case  1 : return 'January'  ; 
    case  2 : return 'February' ; 
    case  3 : return 'March'    ; 
    case  4 : return 'April'    ; 
    case  5 : return 'May'      ; 
    case  6 : return 'June'     ; 
    case  7 : return 'July'     ; 
    case  8 : return 'August'   ; 
    case  9 : return 'September'; 
    case 10 : return 'October'  ; 
    case 11 : return 'November' ; 
    case 12 : return 'December' ; 
  }
}


function selectMonth( month )
{
  return function() {
    $( '#selectedMonth' ).html( getMonthName( month ) );
    populateTable( '#birthdayTableStudents', 'stu', month  );
    populateTable( '#birthdayTableStaff'   , 'sta', month  );
  }
}



$(function(){  //on document ready

    // create an array like this:
    //items = [ { text: getMonthName(  1 ), handler: function(){ selectMonth(  1 ); } } 
    //        , { text: getMonthName(  2 ), handler: function(){ selectMonth(  2 ); } } 
    //        ...
    //        ];
    var items = [];
    for ( var i = 1; i <= 12; i++ ) {
      items.push( { text: getMonthName( i ), handler: selectMonth( i ) } ); 
    } 

    // set month to current month
    var currentDate  = new Date();
    var currentMonth = currentDate.getMonth();

    // set up the month selector
    select( '#select', items, currentMonth );

});


</script>

</html>
